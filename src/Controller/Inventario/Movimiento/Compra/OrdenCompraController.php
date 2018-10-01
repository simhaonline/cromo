<?php

namespace App\Controller\Inventario\Movimiento\Compra;

use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvOrdenCompra;
use App\Entity\Inventario\InvPrecioDetalle;
use App\Entity\Inventario\InvSolicitudDetalle;
use App\Entity\Inventario\InvTercero;
use App\Form\Type\Inventario\OrdenCompraType;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use App\Entity\Inventario\InvOrdenCompraDetalle;
use App\Formato\Inventario\OrdenCompra;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrdenCompraController extends Controller
{

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/movimiento/inventario/ordenCompra/nuevo/{id}",name="inventario_movimiento_inventario_ordenCompra_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arOrdenCompra = new InvOrdenCompra();
        if ($id != 0) {
            $arOrdenCompra = $em->getRepository(InvOrdenCompra::class)->find($id);
        }
        $form = $this->createForm(OrdenCompraType::class, $arOrdenCompra);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoTercero = $request->request->get('txtCodigoTercero');
                if ($txtCodigoTercero != '') {
                    $arTercero = $em->getRepository(InvTercero::class)->find($txtCodigoTercero);
                    if ($arTercero) {
                        $arOrdenCompra->setTerceroRel($arTercero);
                        $arOrdenCompra->setFecha(new \DateTime('now'));
                        $em->persist($arOrdenCompra);
                        $em->flush();
                        return $this->redirect($this->generateUrl('inventario_movimiento_inventario_ordenCompra_detalle', ['id' => $arOrdenCompra->getCodigoOrdenCompraPk()]));
                    }
                }
            } else {
                Mensajes::error('Debes seleccionar un tercero');
            }
        }
        return $this->render('inventario/movimiento/compra/ordenCompra/nuevo.html.twig', [
            'arOrdenCompra' => $arOrdenCompra,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/movimiento/inventario/ordencompra/detalle/{id}", name="inventario_movimiento_inventario_ordenCompra_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arOrdenCompra = $em->getRepository(InvOrdenCompra::class)->find($id);
        $form = Estandares::botonera($arOrdenCompra->getEstadoAutorizado(), $arOrdenCompra->getEstadoAprobado(), $arOrdenCompra->getEstadoAnulado());
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arOrdenCompra->getEstadoAutorizado()) {
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
                $em->getRepository(InvOrdenCompra::class)->actualizar($arOrdenCompra, $arrValor, $arrCantidad, $arrIva, $arrDescuento);
                $em->getRepository(InvOrdenCompra::class)->autorizar($arOrdenCompra);
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_ordenCompra_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(InvOrdenCompra::class)->desautorizar($arOrdenCompra);
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_ordenCompra_detalle', ['id' => $id]));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(InvOrdenCompra::class)->aprobar($arOrdenCompra);
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_ordenCompra_detalle', ['id' => $id]));
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(InvOrdenCompra::class)->actualizar($arOrdenCompra, $arrValor, $arrCantidad, $arrIva, $arrDescuento);
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_ordenCompra_detalle', ['id' => $id]));
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $objFormatoOrdenCompra = new OrdenCompra();
                $objFormatoOrdenCompra->Generar($em, $id);
            }
            if ($form->get('btnAnular')->isClicked()) {
                $respuesta = $em->getRepository(InvOrdenCompra::class)->anular($arOrdenCompra);
                if (count($respuesta) > 0) {
                    foreach ($respuesta as $error) {
                        Mensajes::error($error);
                    }
                }
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_ordenCompra_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrDetallesSeleccionados) {
                    $em->getRepository(InvOrdenCompraDetalle::class)->eliminar($arOrdenCompra, $arrDetallesSeleccionados);
                    return $this->redirect($this->generateUrl('inventario_movimiento_inventario_ordenCompra_detalle', ['id' => $id]));
                }
            }
        }
        $arOrdenCompraDetalles = $paginator->paginate($em->getRepository(InvOrdenCompraDetalle::class)->lista($arOrdenCompra->getCodigoOrdenCompraPk()), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/movimiento/compra/ordenCompra/detalle.html.twig', [
            'form' => $form->createView(),
            'arOrdenCompraDetalles' => $arOrdenCompraDetalles,
            'arOrdenCompra' => $arOrdenCompra
        ]);
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @Route("/inventario/movimiento/inventario/ordencompra/detalle/nuevo/{id}", name="inventario_movimiento_inventario_ordenCompra_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arOrdenCompra = $em->getRepository(InvOrdenCompra::class)->find($id);
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
                            $arOrdenCompraDetalle = new InvOrdenCompraDetalle();
                            $precioVenta = $em->getRepository(InvPrecioDetalle::class)->obtenerPrecio($arOrdenCompra->getTerceroRel()->getCodigoPrecioCompraFk(), $codigoItem);
                            $arOrdenCompraDetalle->setVrPrecio(is_array($precioVenta) ? $precioVenta['precio'] : 0);
                            $arOrdenCompraDetalle->setOrdenCompraRel($arOrdenCompra);
                            $arOrdenCompraDetalle->setItemRel($arItem);
                            $arOrdenCompraDetalle->setPorcentajeIva($arItem->getPorcentajeIva());
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
        $arItems = $paginator->paginate($em->getRepository(InvItem::class)->lista(), $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/compra/ordenCompra/detalleNuevo.html.twig', [
            'form' => $form->createView(),
            'arItems' => $arItems
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @Route("/inventario/movimiento/inventario/ordencompra/solicitud/detalle/nuevo/{id}", name="inventario_movimiento_inventario_ordenCompra_solicitud_detalle_nuevo")
     */
    public function detalleNuevoSolicitud(Request $request, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigoItem', TextType::class, ['label' => 'Codigo: ', 'required' => false, 'data' => $session->get('filtroInvItemCodigo')])
            ->add('txtNombreItem', TextType::class, ['label' => 'Nombre: ', 'required' => false, 'data' => $session->get('filtroInvItemNombre')])
            ->add('txtCodigoSolicitud', TextType::class, ['label' => 'Codigo solicitud: ', 'required' => false, 'data' => $session->get('filtroInvSolicitudCodigo')])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $respuesta = '';
        $arOrdenCompra = $em->getRepository('App:Inventario\InvOrdenCompra')->findOneBy(['codigoOrdenCompraPk' => $id]);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrSolicitudDetalles = $request->request->get('itemCantidad');
                if ($arrSolicitudDetalles) {
                    if (count($arrSolicitudDetalles) > 0) {
                        foreach ($arrSolicitudDetalles as $codigoSolicitudDetalle => $cantidad) {
                            if ($cantidad != '' && $cantidad != 0 && $cantidad > 0) {
                                $arSolicitudDetalle = $em->getRepository('App:Inventario\InvSolicitudDetalle')->find($codigoSolicitudDetalle);
                                if ($cantidad <= $arSolicitudDetalle->getCantidadPendiente()) {
                                    $arItem = $em->getRepository('App:Inventario\InvItem')->find($arSolicitudDetalle->getCodigoItemFk());
                                    $arOrdenCompraDetalle = new InvOrdenCompraDetalle();
                                    $precioVenta = $em->getRepository(InvPrecioDetalle::class)->obtenerPrecio($arOrdenCompra->getTerceroRel()->getCodigoPrecioCompraFk(), $arItem->getCodigoItemPk());
                                    $arOrdenCompraDetalle->setVrPrecio(is_array($precioVenta) ? $precioVenta['precio'] : 0);
                                    $arOrdenCompraDetalle->setOrdenCompraRel($arOrdenCompra);
                                    $arOrdenCompraDetalle->setItemRel($arItem);
                                    $arOrdenCompraDetalle->setCantidadPendiente($cantidad);
                                    $arOrdenCompraDetalle->setPorcentajeIva($arItem->getPorcentajeIva());
                                    $arOrdenCompraDetalle->setCantidad($cantidad);
                                    $arOrdenCompraDetalle->setSolicitudDetalleRel($arSolicitudDetalle);
                                    $arSolicitudDetalle->setCantidadPendiente($arSolicitudDetalle->getCantidadPendiente() - $cantidad);
                                    $em->persist($arSolicitudDetalle);
                                    $em->persist($arOrdenCompraDetalle);
                                } else {
                                    $respuesta = "Debe ingresar una cantidad menor o igual a la solicitada.";
                                }
                            }
                            if ($respuesta != '') {
                                Mensajes::error($respuesta);
                            } else {
                                $em->flush();
                                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                            }
                        }
                    }
                }
                if ($form->get('btnFiltrar')->isClicked()) {
                    $session->set('filtroInvItemCodigo', $form->get('txtCodigoItem')->getData());
                    $session->set('filtroInvItemNombre', $form->get('txtNombreItem')->getData());
                    $session->set('filtroInvSolicitudCodigo', $form->get('txtCodigoSolicitud')->getData());
                }
            }
            $arSolicitudesDetalles = $paginator->paginate($em->getRepository(InvSolicitudDetalle::class)->listarDetallesPendientes(), $request->query->getInt('page', 1), 10);
            if (count($arSolicitudesDetalles) == 0) {
                Mensajes::error('No hay solicitudes pendientes o no se encuentran aprobadas');
            }
            return $this->render('inventario/movimiento/compra/ordenCompra/detalleNuevoSolicitud.html.twig', [
                'form' => $form->createView(),
                'arSolicitudesDetalles' => $arSolicitudesDetalles
            ]);
        }

    }
}
