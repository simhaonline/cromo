<?php

namespace App\Controller\Inventario\Movimiento\Comercial;

use App\Entity\Inventario\InvCotizacionDetalle;
use App\Form\Type\Inventario\CotizacionType;
use App\General\General;
use App\Utilidades\Mensajes;
use App\Utilidades\Estandares;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvCotizacion;
use App\Entity\Inventario\InvCotizacionTipo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CotizacionController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/movimiento/comercial/cotizacion/lista", name="inventario_movimiento_comercial_cotizacion_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('txtNumero', NumberType::class, ['required' => false, 'data' => $session->get('filtroInvCotizacionNumero')])
            ->add('chkEstadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroInvCotizacionEstadoAprobado'), 'required' => false])
            ->add('cboCotizacionTipoRel', EntityType::class, $em->getRepository(InvCotizacionTipo::class)->llenarCombo())
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvCotizacionNumero', $form->get('txtNumero')->getData());
                $session->set('filtroInvCotizacionEstadoAprobado', $form->get('chkEstadoAprobado')->getData());
                $solicitudTipo = $form->get('cboCotizacionTipoRel')->getData();
                if($solicitudTipo != ''){
                    $session->set('filtroInvCotizacionTipoCodigo', $form->get('cboCotizacionTipoRel')->getData()->getCodigoCotizacionTipoPk());
                } else {
                    $session->set('filtroInvCotizacionTipoCodigo', null);
                }
            }
            if($form->get('btnEliminar')->isClicked()){
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(InvCotizacion::class)->eliminar($arrSeleccionados);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($em->getRepository(InvCotizacion::class)->lista())->execute(), "Cotizacion");
            }
        }
        $arCotizaciones = $paginator->paginate($em->getRepository(InvCotizacion::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/movimiento/comercial/cotizacion/lista.html.twig', [
            'arCotizaciones' => $arCotizaciones,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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
        }
        $form = $this->createForm(CotizacionType::class, $arCotizacion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if($id == 0){
                    $arCotizacion->setFecha(new \DateTime('now'));
                }
                $arCotizacion->setUsuario($this->getUser()->getUserName());
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
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCotizacion = $em->getRepository(InvCotizacion::class)->find($id);
        $paginator = $this->get('knp_paginator');
        $form = Estandares::botonera($arCotizacion->getEstadoAutorizado(),$arCotizacion->getEstadoAprobado(),$arCotizacion->getEstadoAnulado());
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if($arCotizacion->getEstadoAutorizado()){
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
            if ($form->get('btnImprimir')->isClicked()) {
//                $objFormatoSolicitud = new Solicitud();
//                $objFormatoSolicitud->Generar($em, $id);
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(InvCotizacion::class)->aprobar($arCotizacion);
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
                $session->set('filtroInvItemCodigo', $form->get('txtCodigoItem')->getData());
                $session->set('filtroInvItemNombre', $form->get('txtNombreItem')->getData());
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
                            $em->persist($arCotizacionDetalle);
                        }
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $arItems = $paginator->paginate($em->getRepository(InvItem::class)->lista(), $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/comercial/cotizacion/detalleNuevo.html.twig', [
            'arItems' => $arItems,
            'form' => $form->createView()
        ]);
    }
}
