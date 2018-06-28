<?php

namespace App\Controller\Inventario\Movimiento\Compra;

use App\Utilidades\Mensajes;
use App\Entity\Inventario\InvOrdenCompra;
use App\Entity\Inventario\InvOrdenCompraDetalle;
use App\Formato\Inventario\OrdenCompra;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrdenCompraController extends Controller
{
    var $query = '';

    /**
     * @Route("/inv/mto/inventario/ordencompra/detalle/{id}", name="inventario_movimiento_inventario_ordenCompra_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $objFormatoOrdenCompra = new OrdenCompra();
        $em = $this->getDoctrine()->getManager();
        $arOrdenCompra = $em->getRepository('App:Inventario\InvOrdenCompra')->find($id);
        $arOrdenCompraDetalles = $em->getRepository('App:Inventario\InvOrdenCompraDetalle')->findBy(['codigoOrdenCompraFk' => $id]);
        $form = $this->formularioDetalles($arOrdenCompra);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrIva = $request->request->get('arrIva');
            $arrValor = $request->request->get('arrValor');
            $arrCantidad = $request->request->get('arrCantidad');
            $arrDescuento = $request->request->get('arrDescuento');
            $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository('App:Inventario\InvOrdenCompra')->actualizar($arOrdenCompra, $arrValor, $arrCantidad, $arrIva, $arrDescuento);
                $em->getRepository('App:Inventario\InvOrdenCompra')->autorizar($arOrdenCompra);
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_ordenCompra_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository('App:Inventario\InvOrdenCompra')->desautorizar($arOrdenCompra);
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_ordenCompra_detalle', ['id' => $id]));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository('App:Inventario\InvOrdenCompra')->aprobar($arOrdenCompra);
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_ordenCompra_detalle', ['id' => $id]));
            }
            if  ($form->get('btnActualizar')->isClicked()){
                $em->getRepository('App:Inventario\InvOrdenCompra')->actualizar($arOrdenCompra, $arrValor, $arrCantidad, $arrIva, $arrDescuento);
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_ordenCompra_detalle', ['id' => $id]));
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $objFormatoOrdenCompra->Generar($em, $id);
            }
            if ($form->get('btnAnular')->isClicked()) {
                $respuesta = $em->getRepository('App:Inventario\InvOrdenCompra')->anular($arOrdenCompra);
                if(count($respuesta) > 0){
                    foreach ($respuesta as $error){
                        Mensajeserror($error);
                    }
                }
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_ordenCompra_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('App:Inventario\InvOrdenCompraDetalle')->eliminar($arOrdenCompra, $arrDetallesSeleccionados);
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_ordenCompra_detalle', ['id' => $id]));
            }
        }
        return $this->render('inventario/movimiento/compra/ordenCompra/detalle.html.twig', [
            'form' => $form->createView(),
            'arOrdenCompraDetalles' => $arOrdenCompraDetalles,
            'arOrdenCompra' => $arOrdenCompra
        ]);
    }

    /**
     * @Route("/inv/mto/inventario/ordencompra/detalle/nuevo/{id}", name="inventario_movimiento_inventario_ordenCompra_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arOrdenCompra = $em->getRepository('App:Inventario\InvOrdenCompra')->find($id);
        $form = $this->formularioFiltroItems();
        $form->handleRequest($request);
        $this->listaItems($em, $form);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $this->listaItems($em, $form);
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrItems = $request->request->get('itemCantidad');
                if (count($arrItems) > 0) {
                    foreach ($arrItems as $codigoItem => $cantidad) {
                        if ($cantidad != '' && $cantidad != 0) {
                            $arItem = $em->getRepository('App:Inventario\InvItem')->find($codigoItem);
                            $arOrdenCompraDetalle = new InvOrdenCompraDetalle();
                            $arOrdenCompraDetalle->setOrdenCompraRel($arOrdenCompra);
                            $arOrdenCompraDetalle->setItemRel($arItem);
                            $arOrdenCompraDetalle->setCantidad($cantidad);
                            $arOrdenCompraDetalle->setCantidadPendiente($cantidad);
                            $em->persist($arOrdenCompraDetalle);
                        }
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $arItems = $paginator->paginate($this->query, $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/compra/ordenCompra/detalleNuevo.html.twig', [
            'form' => $form->createView(),
            'arItems' => $arItems
        ]);
    }

    private function formularioFiltroDetalleSolicitud()
    {
        return $this->createFormBuilder()
            ->add('txtCodigoItem', TextType::class, ['label' => 'Codigo: ', 'required' => false])
            ->add('txtNombreItem', TextType::class, ['label' => 'Nombre: ', 'required' => false])
            ->add('txtCodigoSolicitud', TextType::class, ['label' => 'Codigo solicitud: ', 'required' => false])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
    }

    /**
     * @Route("/inv/mto/inventario/ordencompra/solicitud/detalle/nuevo/{id}", name="inventario_movimiento_inventario_ordenCompra_solicitud_detalle_nuevo")
     */
    public function detalleNuevoSolicitud(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltroDetalleSolicitud();
        $form->handleRequest($request);
        $this->listaDetallesSolicitud($em, $form);
        $respuesta = '';
        $arOrdenCompra = $em->getRepository('App:Inventario\InvOrdenCompra')->findOneBy(['codigoOrdenCompraPk' => $id]);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrSolicitudDetalles = $request->request->get('itemCantidad');
                if ($arrSolicitudDetalles) {
                    if (count($arrSolicitudDetalles) > 0) {
                        foreach ($arrSolicitudDetalles as $codigoSolicitudDetalle => $cantidad) {
                            if ($cantidad != '' && $cantidad != 0) {
                                $arSolicitudDetalle = $em->getRepository('App:Inventario\InvSolicitudDetalle')->find($codigoSolicitudDetalle);
                                if ($cantidad <= $arSolicitudDetalle->getCantidadPendiente()) {
                                    $arItem = $em->getRepository('App:Inventario\InvItem')->find($arSolicitudDetalle->getCodigoItemFk());
                                    $arOrdenCompraDetalle = new InvOrdenCompraDetalle();
                                    $arOrdenCompraDetalle->setOrdenCompraRel($arOrdenCompra);
                                    $arOrdenCompraDetalle->setItemRel($arItem);
                                    $arOrdenCompraDetalle->setCantidadPendiente($cantidad);
                                    $arOrdenCompraDetalle->setCantidad($cantidad);
                                    $arOrdenCompraDetalle->setSolicitudDetalleRel($arSolicitudDetalle);
                                    $arSolicitudDetalle->setCantidadPendiente($arSolicitudDetalle->getCantidadPendiente() - $cantidad);
                                    $em->persist($arSolicitudDetalle);
                                    $em->persist($arOrdenCompraDetalle);
                                } else {
                                    $respuesta = "Debe ingresar una cantidad menor o igual a la solicitada.";
                                }
                            }
                        }
                        if ($respuesta != '') {
                            Mensajeserror($respuesta);
                        } else {
                            $em->flush();
                            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                        }
                    }
                }
            }
        }
        $arSolicitudesDetalles = $paginator->paginate($this->query, $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/compra/ordenCompra/detalleNuevoSolicitud.html.twig', [
            'form' => $form->createView(),
            'arSolicitudesDetalles' => $arSolicitudesDetalles
        ]);
    }

    /**
     * @param $arOrdenCompra InvOrdenCompra
     * @return \Symfony\Component\Form\FormInterface
     */
    private function formularioDetalles($arOrdenCompra)
    {
        $arrBtnAutorizar = ['label' => 'Autorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAprobar = ['label' => 'Aprobar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDesautorizar = ['label' => 'Desautorizar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnImprimir = ['label' => 'Imprimir', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAnular = ['label' => 'Anular', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arOrdenCompra->getEstadoAnulado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnImprimir['disabled'] = true;
            $arrBtnAnular['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnAprobar['disabled'] = true;
            $arrBtnActualizar['disabled'] = true;
        } elseif ($arOrdenCompra->getEstadoAprobado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnImprimir['disabled'] = false;
            $arrBtnAnular['disabled'] = false;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnAprobar['disabled'] = true;
            $arrBtnActualizar['disabled'] = true;
        } elseif ($arOrdenCompra->getEstadoAutorizado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = false;
            $arrBtnImprimir['disabled'] = true;
            $arrBtnAnular['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnAprobar['disabled'] = false;
            $arrBtnActualizar['disabled'] = true;
        } else {
            $arrBtnAutorizar['disabled'] = false;
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnImprimir['disabled'] = true;
            $arrBtnAnular['disabled'] = true;
            $arrBtnEliminar['disabled'] = false;
            $arrBtnAprobar['disabled'] = true;
            $arrBtnActualizar['disabled'] = false;
        }
        return $this
            ->createFormBuilder()
            ->add('btnAutorizar', SubmitType::class, $arrBtnAutorizar)
            ->add('btnActualizar', SubmitType::class, $arrBtnActualizar)
            ->add('btnAprobar', SubmitType::class, $arrBtnAprobar)
            ->add('btnDesautorizar', SubmitType::class, $arrBtnDesautorizar)
            ->add('btnImprimir', SubmitType::class, $arrBtnImprimir)
            ->add('btnAnular', SubmitType::class, $arrBtnAnular)
            ->add('btnEliminar', SubmitType::class, $arrBtnEliminar)
            ->getForm();
    }

    private function formularioFiltroItems()
    {
        return $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('txtCodigoItem', TextType::class, ['label' => 'Codigo: ', 'required' => false])
            ->add('txtNombreItem', TextType::class, ['label' => 'Nombre: ', 'required' => false])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
    }

    /**
     * @param $em ObjectManager
     * @param $form \Symfony\Component\Form\FormInterface
     */
    private function listaItems($em, $form)
    {
        $session = new Session();
        $session->set('filtroCodigoItem', $form->get('txtCodigoItem')->getData());
        $session->set('filtroNombreItem', $form->get('txtNombreItem')->getData());
        $this->query = $em->getRepository('App:Inventario\InvItem')->listarItems($session->get('filtroNombreItem'), $session->get('filtroCodigoItem'));
    }

    /**
     * @param $em ObjectManager
     * @param $form
     */
    private function listaDetallesSolicitud($em, $form)
    {
        $session = new Session();
        $session->set('filtroCodigoItem', $form->get('txtCodigoItem')->getData());
        $session->set('filtroNombreItem', $form->get('txtNombreItem')->getData());
        $session->set('filtroCodigoSolicitud', $form->get('txtCodigoSolicitud')->getData());
        $this->query = $em->getRepository('App:Inventario\InvSolicitudDetalle')->listarDetallesPendientes(
            $session->get('filtroNombreItem'),
            $session->get('filtroCodigoItem'),
            $session->get('filtroCodigoSolicitud'));
    }
}
