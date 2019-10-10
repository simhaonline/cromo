<?php

namespace App\Controller\Inventario\Movimiento\Comercial;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Inventario\InvConfiguracion;
use App\Entity\Inventario\InvContacto;
use App\Entity\Inventario\InvCotizacionDetalle;
use App\Entity\Inventario\InvTercero;
use App\Form\Type\Inventario\CotizacionType;
use App\Formato\Inventario\Cotizacion;
use App\Formato\Inventario\Cotizacion2;
use App\General\General;
use App\Utilidades\Mensajes;
use App\Utilidades\Estandares;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvCotizacion;
use App\Entity\Inventario\InvCotizacionTipo;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Calculation\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CotizacionController extends AbstractController
{
    protected $class = InvCotizacion::class;
    protected $claseNombre = "InvCotizacion";
    protected $modulo = "Inventario";
    protected $funcion = "Movimiento";
    protected $grupo = "Comercial";
    protected $nombre = "Cotizacion";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/movimiento/comercial/cotizacion/lista", name="inventario_movimiento_comercial_cotizacion_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('numero', TextType::class, array('required' => false))
            ->add('codigoCotizacionPk', TextType::class, array('required' => false))
            ->add('codigoTerceroFk', TextType::class, array('required' => false))
            ->add('codigoCotizacionTipoFk', EntityType::class, [
                'class' => InvCotizacionTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->orderBy('ct.codigoCotizacionTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(InvCotizacion::class)->lista($raw)->getQuery()->execute(), "Cotizaciones ");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(InvCotizacion::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('inventario_movimiento_comercial_cotizacion_lista'));
            }
        }
        $arCotizaciones = $paginator->paginate($em->getRepository(InvCotizacion::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('inventario/movimiento/comercial/cotizacion/lista.html.twig', [
            'arCotizaciones' => $arCotizaciones,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/inventario/movimiento/comercial/cotizacion/nuevo/{id}", name="inventario_movimiento_comercial_cotizacion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCotizacion = new InvCotizacion();
        if ($id != 0) {
            $arCotizacion = $em->getRepository(InvCotizacion::class)->find($id);
            if (!$arCotizacion) {
                return $this->redirect($this->generateUrl('inventario_movimiento_comercial_cotizacion_lista'));
            }
        } else {
            $arCotizacion->setVencimiento(New \DateTime('Now'));
        }
        $form = $this->createForm(CotizacionType::class, $arCotizacion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arCotizacion->setTerceroRel($em->getRepository(InvTercero::class)->find($arCotizacion->getCodigoTerceroFk()));
                $arCotizacion->setUsuario($this->getUser()->getUserName());
                if ($id == 0) {
                    $arCotizacion->setFecha(new \DateTime('now'));
                }
                if ($arCotizacion->getCodigoContactoFk()) {
                    $arContacto = $em->getRepository(InvContacto::class)->find($arCotizacion->getCodigoContactoFk());
                    if ($arContacto) {
                        $arCotizacion->setContactoRel($arContacto);
                    }
                }
                $em->persist($arCotizacion);
                $em->flush();
                return $this->redirect($this->generateUrl('inventario_movimiento_comercial_cotizacion_detalle', ['id' => $arCotizacion->getCodigoCotizacionPk()]));
            }
        }
        return $this->render('inventario/movimiento/comercial/cotizacion/nuevo.html.twig', [
            'arCotizacion' => $arCotizacion,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/inventario/movimiento/comercial/cotizacion/detalle/{id}", name="inventario_movimiento_comercial_cotizacion_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCotizacion = $em->getRepository(InvCotizacion::class)->find($id);
        $form = Estandares::botonera($arCotizacion->getEstadoAutorizado(), $arCotizacion->getEstadoAprobado(), $arCotizacion->getEstadoAnulado());
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arCotizacion->getEstadoAutorizado()) {
            $arrBtnEliminar['disabled'] = true;
            $arrBtnActualizar['disabled'] = true;
        }
        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar)
            ->add('btnActualizar', SubmitType::class, $arrBtnActualizar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrIva = $request->request->get('arrIva');
            $arrValor = $request->request->get('arrValor');
            $arrCantidad = $request->request->get('arrCantidad');
            $arrDescuento = $request->request->get('arrDescuento');
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(InvCotizacion::class)->autorizar($arCotizacion);
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(InvCotizacion::class)->desautorizar($arCotizacion);
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(InvCotizacion::class)->aprobar($arCotizacion);
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $codigoFormatoCotizacion = $em->getRepository(InvConfiguracion::class)->find(1)->getCodigoFormatoCotizacion();
                if ($codigoFormatoCotizacion == 1 || $codigoFormatoCotizacion == 0 || $codigoFormatoCotizacion == "") {
                    $objFormatoCotizacion = new Cotizacion();
                    $objFormatoCotizacion->Generar($em, $id);
                }
                if ($codigoFormatoCotizacion == 2) {
                    $objFormatoCotizacion = new Cotizacion2();
                    $objFormatoCotizacion->Generar($em, $id);
                }

            }
            if ($form->get('btnAnular')->isClicked()) {
                $respuesta = $em->getRepository(InvCotizacion::class)->anular($arCotizacion);
                if (count($respuesta) > 0) {
                    foreach ($respuesta as $error) {
                        Mensajes::error($error);
                    }
                }
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(InvCotizacion::class)->actualizar($arCotizacion, $arrValor, $arrCantidad, $arrIva, $arrDescuento);
                return $this->redirect($this->generateUrl('inventario_movimiento_comercial_cotizacion_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(InvCotizacionDetalle::class)->eliminar($arCotizacion, $arrDetallesSeleccionados);
            }
            return $this->redirect($this->generateUrl('inventario_movimiento_comercial_cotizacion_detalle', ['id' => $id]));
        }
        $arCotizacionDetalles = $paginator->paginate($em->getRepository(InvCotizacionDetalle::class)->lista($arCotizacion->getCodigoCotizacionPk()), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/movimiento/comercial/cotizacion/detalle.html.twig', [
            'arCotizacionDetalles' => $arCotizacionDetalles,
            'arCotizacion' => $arCotizacion,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/inventario/movimiento/comercial/cotizacion/detalle/nuevo/{id}", name="inventario_movimiento_comercial_cotizacion_detalle_nuevo")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detalleNuevo(Request $request, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arCotizacion = $em->getRepository(InvCotizacion::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('txtCodigoItem', TextType::class, ['label' => 'Codigo: ', 'required' => false])
            ->add('txtNombreItem', TextType::class, ['label' => 'Nombre: ', 'required' => false])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvBucarItemCodigo', $form->get('txtCodigoItem')->getData());
                $session->set('filtroInvBuscarItemNombre', $form->get('txtNombreItem')->getData());
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrItems = $request->request->get('itemCantidad');
                if (count($arrItems) > 0) {
                    foreach ($arrItems as $codigoItem => $cantidad) {
                        if ($cantidad != '' && $cantidad != 0) {
                            $arItem = $em->getRepository(InvItem::class)->find($codigoItem);
                            $arCotizacionDetalle = new InvCotizacionDetalle();
                            $arCotizacionDetalle->setCotizacionRel($arCotizacion);
                            $arCotizacionDetalle->setItemRel($arItem);
                            $arCotizacionDetalle->setCantidad($cantidad);
                            $arCotizacionDetalle->setCantidadPendiente($cantidad);
                            $arCotizacionDetalle->setPorcentajeIva($arItem->getPorcentajeIva());
                            $em->persist($arCotizacionDetalle);
                        }
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $arItems = $paginator->paginate($em->getRepository(InvItem::class)->lista(), $request->query->getInt('page', 1), 50);
        return $this->render('inventario/movimiento/comercial/cotizacion/detalleNuevo.html.twig', [
            'arItems' => $arItems,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'numero' => $form->get('numero')->getData(),
            'codigoCotizacion' => $form->get('codigoCotizacionPk')->getData(),
            'codigoTercero' => $form->get('codigoTerceroFk')->getData(),
            'numero' => $form->get('numero')->getData(),
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $arCotizacionTipo = $form->get('codigoCotizacionTipoFk')->getData();

        if (is_object($arCotizacionTipo)) {
            $filtro['cotizacionTipo'] = $arCotizacionTipo->getCodigoCotizacionTipoPk();
        } else {
            $filtro['cotizacionTipo'] = $arCotizacionTipo;
        }

        return $filtro;

    }
}
