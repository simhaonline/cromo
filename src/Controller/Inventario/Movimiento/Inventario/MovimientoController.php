<?php

namespace App\Controller\Inventario\Movimiento\Inventario;

use App\Controller\Estructura\MensajesController;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Form\Type\Inventario\MovimientoType;
use App\Formato\Inventario\Factura1;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MovimientoController extends Controller
{
    var $query = '';

    /**
     * @param Request $request
     * @param $tipoDocumento
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/inv/mto/inventario/movimiento/lista/documentos/{tipoDocumento}", name="inv_mto_inventario_movimiento_documentos_lista")
     */
    public function listaDocumentos(Request $request, $tipoDocumento)
    {
        $em = $this->getDoctrine()->getManager();
        $arDocumentos = $em->getRepository('App:Inventario\InvDocumento')->findBy(['codigoDocumentoTipoFk' => $tipoDocumento]);
        return $this->render('inventario/movimiento/inventario/listaDocumentos.html.twig', [
            'arDocumentos' => $arDocumentos,
            'tipoDocumento' => $tipoDocumento
        ]);
    }

    /**
     * @param Request $request
     * @param $codigoDocumento
     * @param $tipoDocumento
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/inv/mto/inventario/movimiento/lista/movimientos/{tipoDocumento}/{codigoDocumento}", name="inv_mto_inventario_movimiento_lista")
     */
    public function listaMovimientos(Request $request, $codigoDocumento, $tipoDocumento)
    {
        $em = $this->getDoctrine()->getManager();
        $arMovimientos = $em->getRepository('App:Inventario\InvMovimiento')->findBy(['codigoDocumentoFk' => $codigoDocumento]);
        return $this->render('inventario/movimiento/inventario/listaMovimientos.html.twig', [
            'arMovimientos' => $arMovimientos,
            'codigoDocumento' => $codigoDocumento,
            'tipoDocumento' => $tipoDocumento
        ]);
    }

    /**
     * @param Request $request
     * @param $codigoDocumento
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/inv/mto/inventario/movimiento/nuevo/{codigoDocumento}/{id}", name="inv_mto_inventario_movimiento_nuevo")
     */
    public function nuevo(Request $request, $codigoDocumento, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arMovimiento = new InvMovimiento();
        if ($id != 0) {
            $arMovimiento = $em->getRepository('App:Inventario\InvMovimiento')->find($id);
            if (!$arMovimiento) {
                return $this->redirect($this->generateUrl('inv_mto_inventario_movimiento_lista', ['codigoDocumento' => $codigoDocumento]));
            }
        }
        $arMovimiento->setFecha(new \DateTime('now'));
        $arMovimiento->setUsuario($this->getUser()->getUserName());
        $arDocumento = $em->getRepository('App:Inventario\InvDocumento')->find($codigoDocumento);
        $arMovimiento->setDocumentoRel($arDocumento);
        $form = $this->createForm(MovimientoType::class, $arMovimiento);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arMovimiento->setFecha(new \DateTime('now'));
                $em->persist($arMovimiento);
                $em->flush();
                return $this->redirect($this->generateUrl('inv_mto_inventario_movimiento_detalle', ['id' => $arMovimiento->getCodigoMovimientoPk()]));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $em->persist($arMovimiento);
                $em->flush();
                return $this->redirect($this->generateUrl('inv_mto_inventario_movimiento_nuevo', ['id' => 0]));
            }
        }
        return $this->render('inventario/movimiento/inventario/nuevo.html.twig', [
            'form' => $form->createView(),
            'arMovimiento' => $arMovimiento,
            'tipoDocumento' => $arDocumento->getCodigoDocumentoTipoFk()
        ]);
    }

    /**
     * @Route("/inv/mto/inventario/movimiento/detalle/{id}", name="inv_mto_inventario_movimiento_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arMovimiento = $em->getRepository('App:Inventario\InvMovimiento')->find($id);
        $arMovimientoDetalles = $em->getRepository('App:Inventario\InvMovimientoDetalle')->findBy(['codigoMovimientoFk' => $id]);
        $form = $this->formularioDetalles($arMovimiento);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrIva = $request->request->get('arrIva');
            $arrLote = $request->request->get('arrLote');
            $arrValor = $request->request->get('arrValor');
            $arrBodega = $request->request->get('arrBodega');
            $arrCantidad = $request->request->get('arrCantidad');
            $arrDescuento = $request->request->get('arrDescuento');
            $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');

            if ($form->get('btnAutorizar')->isClicked()) {
                $respuesta = $em->getRepository('App:Inventario\InvMovimiento')->actualizar($arMovimiento, $arrValor, $arrCantidad, $arrDescuento, $arrIva, $arrBodega, $arrLote);
                if ($respuesta == '') {
                    $em->getRepository('App:Inventario\InvMovimiento')->autorizar($arMovimiento);
                } else {
                    MensajesController::error($respuesta);
                }
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository('App:Inventario\InvMovimiento')->desautorizar($arMovimiento);
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $arDocumento = $em->getRepository('App:Inventario\InvDocumento')->findOneBy(['codigoDocumentoPk' => $arMovimiento->getCodigoDocumentoFk()]);
                if($arDocumento->getCodigoDocumentoTipoFk() == 1){
                } elseif($arDocumento->getCodigoDocumentoTipoFk() == 2){
                } elseif($arDocumento->getCodigoDocumentoTipoFk() == 3){
                    $objFormato = new Factura1();
                    $objFormato->Generar($em, $arMovimiento->getCodigoMovimientoPk());
                }
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $respuesta = $em->getRepository('App:Inventario\InvMovimiento')->aprobar($arMovimiento);
                if ($respuesta != '') {
                    foreach ($respuesta as $respuesta){
                        MensajesController::error($respuesta);
                    }
                }
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $respuesta = $em->getRepository('App:Inventario\InvMovimiento')->actualizar($arMovimiento, $arrValor, $arrCantidad, $arrDescuento, $arrIva, $arrBodega, $arrLote);
                if ($respuesta != '') {
                    MensajesController::error($respuesta);
                }
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository('App:Inventario\InvMovimiento')->anular($arMovimiento);
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $em->getRepository('App:Inventario\InvMovimientoDetalle')->eliminar($arMovimiento, $arrDetallesSeleccionados);
                $respuesta = $em->getRepository('App:Inventario\InvMovimiento')->actualizar($arMovimiento, $arrValor, $arrCantidad, $arrDescuento, $arrIva, $arrBodega, $arrLote);
            }
            return $this->redirect($this->generateUrl('inv_mto_inventario_movimiento_detalle', ['id' => $id]));
        }
        return $this->render('inventario/movimiento/inventario/detalle.html.twig', [
            'form' => $form->createView(),
            'arMovimientoDetalles' => $arMovimientoDetalles,
            'arMovimiento' => $arMovimiento
        ]);
    }

    /**
     * @Route("/inv/mto/inventario/movimiento/detalle/nuevo/{id}", name="inv_mto_inventario_movimiento_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arMovimiento = $em->getRepository('App:Inventario\InvMovimiento')->find($id);
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
                            $arMovimientoDetalle = new InvMovimientoDetalle();
                            $arMovimientoDetalle->setMovimientoRel($arMovimiento);
                            $arMovimientoDetalle->setItemRel($arItem);
                            $arMovimientoDetalle->setCantidad($cantidad);
                            $em->persist($arMovimientoDetalle);
                        }
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $arItems = $paginator->paginate($this->query, $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/inventario/detalleNuevo.html.twig', [
            'form' => $form->createView(),
            'arItems' => $arItems
        ]);
    }

    /**
     * @Route("/inv/mto/inventario/movimiento/detalle/ordencompra/nuevo/{id}", name="inv_mto_inventario_movimiento_ordencompra_detalle_nuevo")
     */
    public function detalleNuevoOrdenCompra(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltroDetalleOrdenCompra();
        $form->handleRequest($request);
        $this->listaDetallesOrdenCompra($em, $form);
        $respuesta = '';
        $arMovimiento = $em->getRepository('App:Inventario\InvMovimiento')->find($id);
        $arOrdenCompra = $em->getRepository('App:Inventario\InvOrdenCompra')->findOneBy(['codigoOrdenCompraPk' => $id]);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrOrdenCompraDetalles = $request->request->get('itemCantidad');
                if ($arrOrdenCompraDetalles) {
                    if (count($arrOrdenCompraDetalles) > 0) {
                        foreach ($arrOrdenCompraDetalles as $codigoOrdenCompraDetalle => $cantidad) {
                            if ($cantidad != '' && $cantidad != 0) {
                                $arOrdenCompraDetalle = $em->getRepository('App:Inventario\InvOrdenCompraDetalle')->find($codigoOrdenCompraDetalle);
                                if ($cantidad <= $arOrdenCompraDetalle->getCantidadPendiente()) {
                                    $arItem = $em->getRepository('App:Inventario\InvItem')->find($arOrdenCompraDetalle->getCodigoItemFk());
                                    $arMovimientoDetalle = new InvMovimientoDetalle();
                                    $arMovimientoDetalle->setMovimientoRel($arMovimiento);
                                    $arMovimientoDetalle->setItemRel($arItem);
                                    $arMovimientoDetalle->setCantidad($cantidad);
                                    $arMovimientoDetalle->setVrPrecio($arOrdenCompraDetalle->getVrPrecio());
                                    $arMovimientoDetalle->setPorDescuento($arOrdenCompraDetalle->getPorDescuento());
                                    $arMovimientoDetalle->setVrDescuento($arOrdenCompraDetalle->getVrDescuento());
                                    $arMovimientoDetalle->setOrdenCompraDetalleRel($arOrdenCompraDetalle);
                                    $arOrdenCompraDetalle->setCantidadPendiente($arOrdenCompraDetalle->getCantidadPendiente() - $cantidad);
                                    $em->persist($arMovimientoDetalle);
                                    $em->persist($arOrdenCompraDetalle);
                                } else {
                                    $respuesta = "Debe ingresar una cantidad menor o igual a la solicitada.";
                                }
                            }
                        }
                        if ($respuesta != '') {
                            MensajesController::error($respuesta);
                        } else {
                            $em->flush();
                            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                        }
                    }
                }
            }
        }
        $arOrdenCompraDetalles = $paginator->paginate($this->query, $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/inventario/detalleNuevoOrdenCompra.html.twig', [
            'form' => $form->createView(),
            'arOrdenCompraDetalles' => $arOrdenCompraDetalles
        ]);
    }

    private function formularioFiltroDetalleOrdenCompra()
    {
        return $this->createFormBuilder()
            ->add('txtCodigoItem', TextType::class, ['label' => 'Codigo: ', 'required' => false])
            ->add('txtNombreItem', TextType::class, ['label' => 'Nombre: ', 'required' => false])
            ->add('txtCodigoOrdenCompra', TextType::class, ['label' => 'Codigo orden compra: ', 'required' => false])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
    }

    private function listaDetallesOrdenCompra()
    {
        $this->query = $this->getDoctrine()->getManager()->getRepository('App:Inventario\InvOrdenCompraDetalle')->listarDetallesPendientes();
    }

    /**
     * @param $arMovimiento InvMovimiento
     * @return \Symfony\Component\Form\FormInterface
     */
    private function formularioDetalles($arMovimiento)
    {
        $arrBtnAutorizar = ['label' => 'Autorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAprobar = ['label' => 'Aprobar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDesautorizar = ['label' => 'Desautorizar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnImprimir = ['label' => 'Imprimir', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAnular = ['label' => 'Anular', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arMovimiento->getEstadoAnulado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnImprimir['disabled'] = true;
            $arrBtnAnular['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnAprobar['disabled'] = true;
            $arrBtnActualizar['disabled'] = true;
        } elseif ($arMovimiento->getEstadoAprobado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnImprimir['disabled'] = false;
            $arrBtnAnular['disabled'] = false;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnAprobar['disabled'] = true;
            $arrBtnActualizar['disabled'] = true;
        } elseif ($arMovimiento->getEstadoAutorizado()) {
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
}
