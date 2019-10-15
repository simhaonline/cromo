<?php

namespace App\Controller\Inventario\Movimiento\Compra;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvOrdenTipo;
use App\Entity\Inventario\InvSolicitud;
use App\Entity\Inventario\InvSolicitudDetalle;
use App\Entity\Inventario\InvSolicitudTipo;
use App\Formato\Inventario\Solicitud;
use App\Utilidades\BaseDatos;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use App\General\General;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Form\Type\Inventario\SolicitudType;

class SolicitudController extends ControllerListenerGeneral
{
    protected $class= InvSolicitud::class;
    protected $claseNombre = "InvSolicitud";
    protected $modulo = "Inventario";
    protected $funcion = "Movimiento";
    protected $grupo = "Compra";
    protected $nombre = "Solicitud";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/movimiento/compra/solicitud/lista", name="inventario_movimiento_compra_solicitud_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoSolicitudTipoFk', EntityType::class, [
                'class' => InvOrdenTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('st')
                        ->orderBy('st.codigoOrdenTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('numero', TextType::class, array('required' => false))
            ->add('codigoSolicitudPk', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(InvSolicitud::class)->lista($raw)->getQuery()->getResult(), "Solicitudes");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(InvSolicitud::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('inventario_movimiento_compra_solicitud_lista'));
            }
        }
        $arSolicitudes = $paginator->paginate($em->getRepository(InvSolicitud::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('inventario/movimiento/compra/solicitud/lista.html.twig', [
            'arSolicitudes' => $arSolicitudes,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/movimiento/compra/solicitud/nuevo/{id}", name="inventario_movimiento_compra_solicitud_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSolicitud = new InvSolicitud();
        if ($id != 0) {
            $arSolicitud = $em->getRepository(InvSolicitud::class)->find($id);
            if (!$arSolicitud) {
                return $this->redirect($this->generateUrl('inventario_movimiento_compra_solicitud_lista'));
            }
        }
        $form = $this->createForm(SolicitudType::class, $arSolicitud);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if($id == 0){
                    $arSolicitud->setFecha(new \DateTime('now'));
                }
                $arSolicitud->setUsuario($this->getUser()->getUserName());
                $em->persist($arSolicitud);
                $em->flush();
                return $this->redirect($this->generateUrl('inventario_movimiento_compra_solicitud_detalle', ['id' => $arSolicitud->getCodigoSolicitudPk()]));
            }
        }
        return $this->render('inventario/movimiento/compra/solicitud/nuevo.html.twig', [
            'arSolicitud' => $arSolicitud,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/inventario/movimiento/compra/solicitud/detalle/{id}", name="inventario_movimiento_compra_solicitud_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSolicitud = $em->getRepository(InvSolicitud::class)->find($id);
        $form = Estandares::botonera($arSolicitud->getEstadoAutorizado(),$arSolicitud->getEstadoAprobado(),$arSolicitud->getEstadoAnulado());
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        if($arSolicitud->getEstadoAutorizado()){
            $arrBtnEliminar['disabled'] = true;
        }
        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(InvSolicitud::class)->autorizar($arSolicitud);
                return $this->redirect($this->generateUrl('inventario_movimiento_compra_solicitud_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(InvSolicitud::class)->desautorizar($arSolicitud);
                return $this->redirect($this->generateUrl('inventario_movimiento_compra_solicitud_detalle', ['id' => $id]));
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $objFormatoSolicitud = new Solicitud();
                $objFormatoSolicitud->Generar($em, $id);
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(InvSolicitud::class)->aprobar($arSolicitud);
                return $this->redirect($this->generateUrl('inventario_movimiento_compra_solicitud_detalle', ['id' => $id]));
            }
            if ($form->get('btnAnular')->isClicked()) {
                $respuesta = $em->getRepository(InvSolicitud::class)->anular($arSolicitud);
                if (count($respuesta) > 0) {
                    foreach ($respuesta as $error) {
                        Mensajes::error($error);
                    }
                }
                return $this->redirect($this->generateUrl('inventario_movimiento_compra_solicitud_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(InvSolicitudDetalle::class)->eliminar($arSolicitud, $arrDetallesSeleccionados);
                return $this->redirect($this->generateUrl('inventario_movimiento_compra_solicitud_detalle', ['id' => $id]));
            }
        }
        $arSolicitudDetalles = $paginator->paginate($em->getRepository(InvSolicitudDetalle::class)->lista($arSolicitud->getCodigoSolicitudPk()), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/movimiento/compra/solicitud/detalle.html.twig', [
            'arSolicitudDetalles' => $arSolicitudDetalles,
            'arSolicitud' => $arSolicitud,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/inventario/movimiento/compra/solicitud/detalle/nuevo/{id}", name="inventario_movimiento_compra_solicitud_detalle_nuevo")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detalleNuevo(Request $request, PaginatorInterface $paginator,$id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arSolicitud = $em->getRepository(InvSolicitud::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('txtCodigoItem', TextType::class, ['label' => 'Codigo: ', 'required' => false])
            ->add('txtNombreItem', TextType::class, ['label' => 'Nombre: ', 'required' => false, 'data' => $session->get('filtroInvBuscarItemNombre')])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => null
        ];
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] =[
                    'codigoItem'=> $form->get('txtCodigoItem')->getData(),
                    'nombreItem'=> $form->get('txtNombreItem')->getData()
                ];
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrItems = $request->request->get('itemCantidad');
                if (count($arrItems) > 0) {
                    foreach ($arrItems as $codigoItem => $cantidad) {
                        if ($cantidad != '' && $cantidad != 0) {
                            $arItem = $em->getRepository(InvItem::class)->find($codigoItem);
                            $arSolicitudDetalle = new InvSolicitudDetalle();
                            $arSolicitudDetalle->setSolicitudRel($arSolicitud);
                            $arSolicitudDetalle->setItemRel($arItem);
                            $arSolicitudDetalle->setCantidad($cantidad);
                            $arSolicitudDetalle->setCantidadPendiente($cantidad);
                            $em->persist($arSolicitudDetalle);
                        }
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $arItems = $paginator->paginate($em->getRepository(InvItem::class)->lista($raw), $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/compra/solicitud/detalleNuevo.html.twig', [
            'arItems' => $arItems,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'numero' => $form->get('numero')->getData(),
            'codigoSolicitud' => $form->get('codigoSolicitudPk')->getData(),
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $arSolicitudTipo = $form->get('codigoSolicitudTipoFk')->getData();

        if (is_object($arSolicitudTipo)) {
            $filtro['$arSolicitudTipo'] = $arSolicitudTipo->getCodigoIncidenteTipoPk();
        } else {
            $filtro['$arSolicitudTipo'] = $arSolicitudTipo;
        }

        return $filtro;

    }
}
