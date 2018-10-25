<?php

namespace App\Controller\Inventario\Movimiento\Inventario;

use App\Entity\Inventario\InvConfiguracion;
use App\Entity\Inventario\InvOrdenDetalle;
use App\Entity\Inventario\InvPedidoDetalle;
use App\Entity\Inventario\InvRemisionDetalle;
use App\Entity\Inventario\InvTercero;
use App\Form\Type\Inventario\FacturaType;
use App\Formato\Inventario\FormatoMovimiento;
use App\Entity\Inventario\InvDocumento;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvOrdenCompraDetalle;
use App\Entity\Inventario\InvSucursal;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Form\Type\Inventario\MovimientoType;
use App\Formato\Inventario\Factura1;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\General\General;

class MovimientoController extends Controller
{
    var $query = '';

    /**
     * @param Request $request
     * @param $tipoDocumento
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/movimiento/inventario/movimiento/lista/documentos/{tipoDocumento}", name="inventario_movimiento_inventario_movimiento_documentos_lista")
     */
    public function listaDocumentos(Request $request, $tipoDocumento)
    {
        $em = $this->getDoctrine()->getManager();
        $arDocumentos = $em->getRepository(InvDocumento::class)->findBy(['codigoDocumentoTipoFk' => $tipoDocumento]);
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
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/movimiento/inventario/movimiento/lista/movimientos/{tipoDocumento}/{codigoDocumento}", name="inventario_movimiento_inventario_movimiento_lista")
     */
    public function listaMovimientos(Request $request, $codigoDocumento, $tipoDocumento)
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigoTercero', TextType::class, ['required' => false, 'data' => $session->get('filtroInvCodigoTercero'), 'attr' => ['class' => 'form-control']])
            ->add('txtCodigo', TextType::class, array('data' => $session->get('filtroInvMovimientoCodigo')))
            ->add('txtNumero', TextType::class, array('data' => $session->get('filtroInvMovimientoNumero')))
            ->add('chkEstadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroInvMovimientoEstadoAutorizado'), 'required' => false])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked()) {
                    $session->set('filtroInvMovimientoNumero', $form->get('txtNumero')->getData());
                    $session->set('filtroInvMovimientoCodigo', $form->get('txtCodigo')->getData());
                    $session->set('filtroInvCodigoTercero', $form->get('txtCodigoTercero')->getData());
                    $session->set('filtroInvMovimientoEstadoAutorizado', $form->get('chkEstadoAutorizado')->getData());
                }
                if ($form->get('btnExcel')->isClicked()) {
                    General::get()->setExportar($em->createQuery($em->getRepository(InvMovimiento::class)->lista($codigoDocumento))->execute(), "Movimientos");
                }
            }
        }
        $arMovimientos = $paginator->paginate($em->getRepository(InvMovimiento::class)->lista($codigoDocumento), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/movimiento/inventario/listaMovimientos.html.twig', [
            'arMovimientos' => $arMovimientos,
            'codigoDocumento' => $codigoDocumento,
            'tipoDocumento' => $tipoDocumento,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $codigoDocumento
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/movimiento/inventario/movimiento/nuevo/{codigoDocumento}/{id}", name="inventario_movimiento_inventario_movimiento_nuevo")
     */
    public function nuevo(Request $request, $codigoDocumento, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arMovimiento = new InvMovimiento();
        if ($id != 0) {
            $arMovimiento = $em->getRepository(InvMovimiento::class)->find($id);
            if (!$arMovimiento) {
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_movimiento_lista', ['codigoDocumento' => $codigoDocumento]));
            }
        }
        $arMovimiento->setFecha(new \DateTime('now'));
        $arMovimiento->setUsuario($this->getUser()->getUserName());
        $arDocumento = $em->getRepository(InvDocumento::class)->find($codigoDocumento);
        $arMovimiento->setDocumentoRel($arDocumento);
        $form = $this->createForm(MovimientoType::class, $arMovimiento);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoTercero = $request->request->get('txtCodigoTercero');
                if ($txtCodigoTercero != '') {
                    $arTercero = $em->getRepository(InvTercero::class)->find($txtCodigoTercero);
                    if ($arTercero) {
                        $arMovimiento->setFecha(new \DateTime('now'));
                        $arMovimiento->setTerceroRel($arTercero);
                        $arMovimiento->setDocumentoTipoRel($arDocumento->getDocumentoTipoRel());
                        $arMovimiento->setOperacionInventario($arDocumento->getOperacionInventario());
                        $arMovimiento->setGeneraCostoPromedio($arDocumento->getGeneraCostoPromedio());
                        $em->persist($arMovimiento);
                        $em->flush();
                        return $this->redirect($this->generateUrl('inventario_movimiento_inventario_movimiento_detalle', ['id' => $arMovimiento->getCodigoMovimientoPk()]));
                    }
                }
            } else {
                Mensajes::error('Debes seleccionar un tercero');
            }
        }
        return $this->render('inventario/movimiento/inventario/nuevo.html.twig', [
            'form' => $form->createView(),
            'arMovimiento' => $arMovimiento,
            'tipoDocumento' => $arDocumento->getCodigoDocumentoTipoFk()
        ]);
    }

    /**
     * @param Request $request
     * @param $codigoDocumento
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/movimiento/inventario/movimiento/nuevo/factura/{codigoDocumento}/{id}", name="inventario_movimiento_inventario_movimiento_nuevo_factura")
     */
    public function nuevoFactura(Request $request, $codigoDocumento, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arMovimiento = new InvMovimiento();
        if ($id != 0) {
            $arMovimiento = $em->getRepository(InvMovimiento::class)->find($id);
            if (!$arMovimiento) {
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_movimiento_lista', ['codigoDocumento' => $codigoDocumento]));
            }
        }
        $arMovimiento->setUsuario($this->getUser()->getUserName());
        $arDocumento = $em->getRepository(InvDocumento::class)->find($codigoDocumento);
        $arMovimiento->setDocumentoRel($arDocumento);
        $form = $this->createForm(FacturaType::class, $arMovimiento);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arMovimiento->setTerceroRel($em->getRepository(InvTercero::class)->find($arMovimiento->getCodigoTerceroFk()));
                if ($id == 0) {
                    $arMovimiento->setFecha(new \DateTime('now'));
                    if ($arMovimiento->getPlazoPago() == 0) {
                        $arMovimiento->setPlazoPago($arMovimiento->getTerceroRel()->getPlazoPago());
                    }
                }
                $arMovimiento->setDocumentoTipoRel($arDocumento->getDocumentoTipoRel());
                $arMovimiento->setOperacionInventario($arDocumento->getOperacionInventario());
                $arMovimiento->setGeneraCostoPromedio($arDocumento->getGeneraCostoPromedio());
                if($arMovimiento->getCodigoSucursalFk()){
                    $arSucursal = $em->getRepository(InvSucursal::class)->find($arMovimiento->getCodigoSucursalFk());
                    if($arSucursal){
                        $arMovimiento->setSucursalRel($arSucursal);
                    }
                }
                $em->persist($arMovimiento);
                $em->flush();
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_movimiento_detalle', ['id' => $arMovimiento->getCodigoMovimientoPk()]));
            }
        }
        return $this->render('inventario/movimiento/inventario/nuevoFactura.html.twig', [
            'tipoDocumento' => $arDocumento->getCodigoDocumentoTipoFk(),
            'arMovimiento' => $arMovimiento,
            'form' => $form->createView()
        ]);
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/inventario/movimiento/inventario/movimiento/detalle/{id}", name="inventario_movimiento_inventario_movimiento_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var  $arMovimiento InvMovimiento */
        $arMovimiento = $em->getRepository(InvMovimiento::class)->find($id);
        $arMovimientoDetalles = $em->getRepository(InvMovimientoDetalle::class)->findBy(['codigoMovimientoFk' => $id]);
        $form = Estandares::botonera($arMovimiento->getEstadoAutorizado(), $arMovimiento->getEstadoAprobado(), $arMovimiento->getEstadoAnulado());

        //Controles para el formulario
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];

        if ($arMovimiento->getEstadoAutorizado()) {
            $arrBtnEliminar['disabled'] = true;
            $arrBtnActualizar['disabled'] = true;
        }
        $form
            ->add('btnActualizar', SubmitType::class, $arrBtnActualizar)
            ->add('btnEliminar', SubmitType::class, $arrBtnEliminar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(InvMovimientoDetalle::class)->actualizarDetalles($arrControles, $form, $arMovimiento);
                $em->getRepository(InvMovimiento::class)->autorizar($arMovimiento);
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(InvMovimiento::class)->desautorizar($arMovimiento);
            }
            if ($form->get('btnImprimir')->isClicked()) {
                if ($arMovimiento->getDocumentoRel()->getCodigoDocumentoTipoFk() == 'FAC') {
                    $codigoFactura = $em->getRepository(InvConfiguracion::class)->find(1)->getCodigoFormatoMovimiento();
                    if ($codigoFactura == 1) {
                        $objFormato = new Factura1();
                        $objFormato->Generar($em, $arMovimiento->getCodigoMovimientoPk());
                    }
                } else {
                    $objFormato = new FormatoMovimiento();
                    $objFormato->Generar($em, $arMovimiento->getCodigoMovimientoPk());
                }
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $respuesta = $em->getRepository(InvMovimiento::class)->aprobar($arMovimiento);
                if ($respuesta != '') {
                    foreach ($respuesta as $error) {
                        Mensajes::error($error);
                    }
                }
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(InvMovimientoDetalle::class)->actualizarDetalles($arrControles, $form, $arMovimiento);
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository(InvMovimiento::class)->anular($arMovimiento);
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $em->getRepository(InvMovimientoDetalle::class)->eliminar($arMovimiento, $arrDetallesSeleccionados);
                $em->getRepository(InvMovimiento::class)->liquidar($arMovimiento);
            }

            return $this->redirect($this->generateUrl('inventario_movimiento_inventario_movimiento_detalle', ['id' => $id]));
        }
        return $this->render('inventario/movimiento/inventario/detalle.html.twig', [
            'form' => $form->createView(),
            'arMovimientoDetalles' => $arMovimientoDetalles,
            'arMovimiento' => $arMovimiento
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/movimiento/inventario/movimiento/detalle/nuevo/{id}", name="inventario_movimiento_inventario_movimiento_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $respuesta = '';
        $arMovimiento = $em->getRepository(InvMovimiento::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('txtCodigoItem', TextType::class, ['label' => 'Codigo: ', 'required' => false])
            ->add('txtNombreItem', TextType::class, ['label' => 'Nombre: ', 'required' => false, 'data' => $session->get('filtroInvBuscarItemNombre')])
            ->add('itemConExistencia', CheckboxType::class, array('label' => ' ','required' => false, 'data' => $session->get('itemConExistencia')))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvBucarItemCodigo', $form->get('txtCodigoItem')->getData());
                $session->set('filtroInvBuscarItemNombre', $form->get('txtNombreItem')->getData());
                $session->set('itemConExistencia', $form->get('itemConExistencia')->getData());
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrItems = $request->request->get('itemCantidad');
                if (count($arrItems) > 0) {
                    foreach ($arrItems as $codigoItem => $cantidad) {
                        $arItem = $em->getRepository(InvItem::class)->find($codigoItem);
                        if ($cantidad != '' && $cantidad != 0) {
                            if ($arMovimiento->getDocumentoRel()->getCodigoDocumentoTipoFk() == 'ENT' || $cantidad <= $arItem->getCantidadExistencia() || $arItem->getAfectaInventario() == 0) {
                                $arMovimientoDetalle = new InvMovimientoDetalle();
                                $arMovimientoDetalle->setMovimientoRel($arMovimiento);
                                $arMovimientoDetalle->setOperacionInventario($arMovimiento->getOperacionInventario());
                                $arMovimientoDetalle->setItemRel($arItem);
                                $arMovimientoDetalle->setCantidad($cantidad);
                                $arMovimientoDetalle->setCantidadOperada($cantidad * $arMovimiento->getOperacionInventario());
                                if ($arMovimiento->getCodigoDocumentoTipoFk() == "SAL") {
                                    $arMovimientoDetalle->setVrPrecio($arItem->getVrCostoPromedio());
                                    $arMovimientoDetalle->setVrCosto($arItem->getVrCostoPromedio());
                                }
                                $arMovimientoDetalle->setPorcentajeIva($arItem->getPorcentajeIva());
                                $em->persist($arMovimientoDetalle);
                            } else {
                                $respuesta = "La cantidad seleccionada para el item: " . $arItem->getNombre() . " no puede ser mayor a las existencias del mismo.";
                                break;
                            }
                        }
                    }
                    if ($respuesta == '') {
                        $em->flush();
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    } else {
                        Mensajes::error($respuesta);
                    }
                }
            }
        }
        $arItems = $paginator->paginate($em->getRepository(InvItem::class)->lista(), $request->query->getInt('page', 1), 100);
        return $this->render('inventario/movimiento/inventario/detalleNuevo.html.twig', [
            'form' => $form->createView(),
            'arItems' => $arItems
        ]);
    }

    /**
     * @Route("/inventario/movimiento/inventario/movimiento/detalle/orden/nuevo/{id}", name="inventario_movimiento_inventario_movimiento_orden_detalle_nuevo")
     */
    public function detalleNuevoOrden(Request $request, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigo', TextType::class, array('data' => $session->get('filtroInvMovimientoItemCodigo'), 'required' => false))
            ->add('txtNombre', TextType::class, array('data' => $session->get('filtroInvMovimientoItemNombre'), 'required' => false, 'attr' => ['readonly' => 'readonly']))
            ->add('txtNumero', TextType::class, array('data' => $session->get('filtroInvNumeroOrdenCompra'), 'required' => false))
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $respuesta = '';
        $arMovimiento = $em->getRepository(InvMovimiento::class)->find($id);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvMovimientoItemCodigo', $form->get('txtCodigo')->getData());
                $session->set('filtroInvNumeroOrden', $form->get('txtNumero')->getData());
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrOrdenDetalles = $request->request->get('itemCantidad');
                if ($arrOrdenDetalles) {
                    if (count($arrOrdenDetalles) > 0) {
                        foreach ($arrOrdenDetalles as $codigoOrdenDetalle => $cantidad) {
                            if ($cantidad != '' && $cantidad != 0) {
                                $arOrdenDetalle = $em->getRepository(InvOrdenDetalle::class)->find($codigoOrdenDetalle);
                                if ($cantidad <= $arOrdenDetalle->getCantidadPendiente()) {
                                    $arMovimientoDetalle = new InvMovimientoDetalle();
                                    $arMovimientoDetalle->setMovimientoRel($arMovimiento);
                                    $arMovimientoDetalle->setOperacionInventario($arMovimiento->getOperacionInventario());
                                    $arMovimientoDetalle->setItemRel($arOrdenDetalle->getItemRel());
                                    $arMovimientoDetalle->setCantidad($cantidad);
                                    $arMovimientoDetalle->setVrPrecio($arOrdenDetalle->getVrPrecio());
                                    $arMovimientoDetalle->setPorcentajeDescuento($arOrdenDetalle->getPorcentajeDescuento());
                                    $arMovimientoDetalle->setPorcentajeIva($arOrdenDetalle->getPorcentajeIva());
                                    $arMovimientoDetalle->setOrdenDetalleRel($arOrdenDetalle);
                                    $em->persist($arMovimientoDetalle);
                                    $arOrdenDetalle->setCantidadAfectada($arOrdenDetalle->getCantidadAfectada() + $cantidad);
                                    $arOrdenDetalle->setCantidadPendiente($arOrdenDetalle->getCantidad() - $arOrdenDetalle->getCantidadAfectada());
                                    $em->persist($arOrdenDetalle);
                                } else {
                                    $respuesta = "Debe ingresar una cantidad menor o igual a la solicitada.";
                                }
                            }
                        }
                        if ($respuesta != '') {
                            Mensajes::error($respuesta);
                        } else {
                            $em->flush();
                            $em->getRepository(InvMovimiento::class)->liquidar($arMovimiento);
                            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                        }
                    }
                }
            }
        }
        $arOrdenDetalles = $paginator->paginate($this->getDoctrine()->getManager()->getRepository(InvOrdenDetalle::class)->listarDetallesPendientes(), $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/inventario/detalleNuevoOrden.html.twig', [
            'form' => $form->createView(),
            'arOrdenDetalles' => $arOrdenDetalles
        ]);
    }

    /**
     * @Route("/inventario/movimiento/inventario/movimiento/detalle/pedido/nuevo/{id}", name="inventario_movimiento_inventario_movimiento_pedido_detalle_nuevo")
     */
    public function detalleNuevoPedido(Request $request, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtNumero', TextType::class, array('required' => false))
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $arMovimiento = $em->getRepository(InvMovimiento::class)->find($id);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvPedidoNumero', $form->get('txtNumero')->getData());
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrDetalles = $request->request->get('itemCantidad');
                if ($arrDetalles) {
                    if (count($arrDetalles) > 0) {
                        $respuesta = "";
                        foreach ($arrDetalles as $codigo => $cantidad) {
                            if ($cantidad != '' && $cantidad != 0) {
                                $arPedidoDetalle = $em->getRepository(InvPedidoDetalle::class)->find($codigo);
                                if ($cantidad <= $arPedidoDetalle->getCantidadPendiente()) {
                                    //$arItem = $em->getRepository(InvItem::class)->find($arOrdenCompraDetalle->getCodigoItemFk());
                                    $arMovimientoDetalle = new InvMovimientoDetalle();
                                    $arMovimientoDetalle->setMovimientoRel($arMovimiento);
                                    $arMovimientoDetalle->setItemRel($arPedidoDetalle->getItemRel());
                                    $arMovimientoDetalle->setCantidad($cantidad);
                                    $arMovimientoDetalle->setVrPrecio($arPedidoDetalle->getVrPrecio());
                                    //$arMovimientoDetalle->setPorcentajeDescuento($arPedidoDetalle->getPorcentajeDescuento());
                                    $arMovimientoDetalle->setPorcentajeIva($arPedidoDetalle->getPorcentajeIva());
                                    $arMovimientoDetalle->setPedidoDetalleRel($arPedidoDetalle);
                                    $em->persist($arMovimientoDetalle);
                                    $arPedidoDetalle->setCantidadAfectada($arPedidoDetalle->getCantidadAfectada() + $cantidad);
                                    $arPedidoDetalle->setCantidadPendiente($arPedidoDetalle->getCantidad() - $arPedidoDetalle->getCantidadAfectada());
                                    $em->persist($arPedidoDetalle);
                                } else {
                                    $respuesta = "Debe ingresar una cantidad menor o igual a la solicitada en el id " . $codigo;
                                }
                            }
                        }
                        if ($respuesta != '') {
                            Mensajes::error($respuesta);
                        } else {
                            $em->flush();
                            $em->getRepository(InvMovimiento::class)->liquidar($arMovimiento);
                            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                        }
                    }
                }
            }
        }
        $arPedidoDetalles = $paginator->paginate($this->getDoctrine()->getManager()->getRepository(InvPedidoDetalle::class)->listarDetallesPendientes($arMovimiento->getCodigoTerceroFk()), $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/inventario/detalleNuevoPedido.html.twig', [
            'form' => $form->createView(),
            'arPedidoDetalles' => $arPedidoDetalles
        ]);
    }

    /**
     * @Route("/inventario/movimiento/inventario/movimiento/detalle/remision/nuevo/{id}", name="inventario_movimiento_inventario_movimiento_remision_detalle_nuevo")
     */
    public function detalleNuevoRemision(Request $request, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtNumero', TextType::class, array('required' => false))
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $arMovimiento = $em->getRepository(InvMovimiento::class)->find($id);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvRemisionNumero', $form->get('txtNumero')->getData());
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrDetalles = $request->request->get('itemCantidad');
                if ($arrDetalles) {
                    if (count($arrDetalles) > 0) {
                        $respuesta = "";
                        foreach ($arrDetalles as $codigo => $cantidad) {
                            if ($cantidad != '' && $cantidad != 0) {
                                $arRemisionDetalle = $em->getRepository(InvRemisionDetalle::class)->find($codigo);
                                if ($cantidad <= $arRemisionDetalle->getCantidadPendiente()) {
                                    $arMovimientoDetalle = new InvMovimientoDetalle();
                                    $arMovimientoDetalle->setMovimientoRel($arMovimiento);
                                    $arMovimientoDetalle->setItemRel($arRemisionDetalle->getItemRel());
                                    $arMovimientoDetalle->setCantidad($cantidad);
                                    $arMovimientoDetalle->setVrPrecio($arRemisionDetalle->getVrPrecio());
                                    $arMovimientoDetalle->setPorcentajeIva($arRemisionDetalle->getPorcentajeIva());
                                    $arMovimientoDetalle->setRemisionDetalleRel($arRemisionDetalle);
                                    $arMovimientoDetalle->setLoteFk($arRemisionDetalle->getLoteFk());
                                    $arMovimientoDetalle->setCodigoBodegaFk($arRemisionDetalle->getCodigoBodegaFk());
                                    $em->persist($arMovimientoDetalle);
                                    $arRemisionDetalle->setCantidadAfectada($arRemisionDetalle->getCantidadAfectada() + $cantidad);
                                    $arRemisionDetalle->setCantidadPendiente($arRemisionDetalle->getCantidad() - $arRemisionDetalle->getCantidadAfectada());
                                    $em->persist($arRemisionDetalle);
                                } else {
                                    $respuesta = "Debe ingresar una cantidad menor o igual a la solicitada en el id " . $codigo;
                                }
                            }
                        }
                        if ($respuesta != '') {
                            Mensajes::error($respuesta);
                        } else {
                            $em->flush();
                            $em->getRepository(InvMovimiento::class)->liquidar($arMovimiento);
                            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                        }
                    }
                }
            }
        }
        $arRemisionDetalles = $paginator->paginate($this->getDoctrine()->getManager()->getRepository(InvRemisionDetalle::class)->listarDetallesPendientes($arMovimiento->getCodigoTerceroFk()), $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/inventario/detalleNuevoRemision.html.twig', [
            'form' => $form->createView(),
            'arRemisionDetalles' => $arRemisionDetalles
        ]);
    }
}
