<?php

namespace App\Controller\Inventario\Movimiento\Inventario;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenAsesor;
use App\Entity\General\GenImpuesto;
use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvConfiguracion;
use App\Entity\Inventario\InvImportacionDetalle;
use App\Entity\Inventario\InvOrden;
use App\Entity\Inventario\InvOrdenDetalle;
use App\Entity\Inventario\InvPedidoDetalle;
use App\Entity\Inventario\InvRemisionDetalle;
use App\Entity\Inventario\InvTercero;
use App\Form\Type\Inventario\FacturaType;
use App\Formato\Inventario\Factura3;
use App\Formato\Inventario\FormatoMovimiento;
use App\Entity\Inventario\InvDocumento;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvSucursal;
use App\Formato\Inventario\FormatoMovimientoTraslado;
use App\Formato\Inventario\NotaCredito;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Form\Type\Inventario\MovimientoType;
use App\Formato\Inventario\Factura1;
use App\Formato\Inventario\Factura2;

;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\General\General;

class MovimientoController extends ControllerListenerGeneral
{
    protected $clase = InvMovimiento::class;
    protected $claseNombre = "InvMovimiento";
    protected $modulo = "Inventario";
    protected $funcion = "Movimiento";
    protected $grupo = "Inventario";
    protected $nombre = "Movimiento";
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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
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
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroInvMovimientoFechaDesde') ? date_create($session->get('filtroInvMovimientoFechaDesde')): null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroInvMovimeintoFechaHasta') ? date_create($session->get('filtroInvMovimeintoFechaHasta')): null])
            ->add('txtCodigoTercero', TextType::class, ['required' => false, 'data' => $session->get('filtroInvCodigoTercero'), 'attr' => ['class' => 'form-control']])
            ->add('txtCodigo', TextType::class, array('data' => $session->get('filtroInvMovimientoCodigo')))
            ->add('txtNumero', TextType::class, array('data' => $session->get('filtroInvMovimientoNumero')))
            ->add('cboAsesor', EntityType::class, $em->getRepository(GenAsesor::class)->llenarCombo())
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('chkEstadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroInvMovimientoEstadoAutorizado'), 'required' => false])
            ->add('chkEstadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroInvMovimientoEstadoAprobado'), 'required' => false])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked()) {
                    $session->set('filtroInvMovimientoFechaDesde',  $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null);
                    $session->set('filtroInvMovimeintoFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d'): null);
                    $session->set('filtroInvMovimientoNumero', $form->get('txtNumero')->getData());
                    $session->set('filtroInvMovimientoCodigo', $form->get('txtCodigo')->getData());
                    $session->set('filtroInvCodigoTercero', $form->get('txtCodigoTercero')->getData());
                    $session->set('filtroInvMovimientoEstadoAutorizado', $form->get('chkEstadoAutorizado')->getData());
                    $session->set('filtroInvMovimientoEstadoAprobado', $form->get('chkEstadoAprobado')->getData());
                    $arAsesor = $form->get('cboAsesor')->getData();
                    if ($arAsesor != '') {
                        $session->set('filtroGenAsesor', $form->get('cboAsesor')->getData()->getCodigoAsesorPk());
                    } else {
                        $session->set('filtroGenAsesor', null);
                    }
                }
                if ($form->get('btnExcel')->isClicked()) {
                    General::get()->setExportar($em->createQuery($em->getRepository(InvMovimiento::class)->lista($codigoDocumento, $this->getUser()))->execute(), "Movimientos");
                }
                if ($form->get('btnEliminar')->isClicked()) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    $em->getRepository(InvMovimiento::class)->eliminar($arrSeleccionados);
                }
            }
        }
        $arMovimientos = $paginator->paginate($em->getRepository(InvMovimiento::class)->lista($codigoDocumento, $this->getUser()), $request->query->getInt('page', 1), 30);
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
                        $arMovimiento->setOperacionComercial($arDocumento->getOperacionComercial());
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
        $objFunciones = new FuncionesController();
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
                $fecha = new \DateTime('now');
                $arMovimiento->setFechaVence($arMovimiento->getPlazoPago() == 0 ? $fecha : $objFunciones->sumarDiasFecha($fecha, $arMovimiento->getPlazoPago()));
                $arMovimiento->setDocumentoTipoRel($arDocumento->getDocumentoTipoRel());
                $arMovimiento->setOperacionInventario($arDocumento->getOperacionInventario());
                $arMovimiento->setGeneraCostoPromedio($arDocumento->getGeneraCostoPromedio());
                if ($arMovimiento->getCodigoSucursalFk()) {
                    $arSucursal = $em->getRepository(InvSucursal::class)->find($arMovimiento->getCodigoSucursalFk());
                    if ($arSucursal) {
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
        $form = Estandares::botonera($arMovimiento->getEstadoAutorizado(), $arMovimiento->getEstadoAprobado(), $arMovimiento->getEstadoAnulado());

        //Controles para el formulario
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDuplicar = ['label' => 'Duplicar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnActualizarImportacion = ['label' => 'Actualizar precio importacion', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arMovimiento->getEstadoAutorizado()) {
            $arrBtnEliminar['disabled'] = true;
            $arrBtnActualizar['disabled'] = true;
            $arrBtnDuplicar['disabled'] = true;
        }
        if($arMovimiento->getEstadoContabilizado()) {
            $arrBtnActualizarImportacion['disabled'] = true;
        }
        $form
            ->add('btnActualizar', SubmitType::class, $arrBtnActualizar)
            ->add('btnEliminar', SubmitType::class, $arrBtnEliminar)
            ->add('btnDuplicar', SubmitType::class, $arrBtnDuplicar)
            ->add('btnActualizarImportacion', SubmitType::class, $arrBtnActualizarImportacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(InvMovimientoDetalle::class)->actualizarDetalles($arrControles, $form, $arMovimiento);
                $em->getRepository(InvMovimiento::class)->autorizar($arMovimiento, $this->getUser()->getUsername());
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(InvMovimiento::class)->desautorizar($arMovimiento);
            }
            if ($form->get('btnImprimir')->isClicked()) {
                if ($arMovimiento->getDocumentoRel()->getCodigoDocumentoTipoFk() == 'FAC' && $arMovimiento->getDocumentoRel()->getNotaCredito() == 0) {
                    $codigoFactura = $em->getRepository(InvConfiguracion::class)->find(1)->getCodigoFormatoMovimiento();
                    if ($codigoFactura == 1) {
                        $objFormato = new Factura1();
                        $objFormato->Generar($em, $arMovimiento->getCodigoMovimientoPk());
                    }
                    if ($codigoFactura == 2) {
                        $objFormato = new Factura2();
                        $objFormato->Generar($em, $arMovimiento->getCodigoMovimientoPk());
                    }
                    if ($codigoFactura == 3) {
                        $objFormato = new Factura3();
                        $objFormato->Generar($em, $arMovimiento->getCodigoMovimientoPk());
                    }
                } elseif ($arMovimiento->getDocumentoRel()->getCodigoDocumentoTipoFk() == 'TRA') {
                    $objFormato = new FormatoMovimientoTraslado();
                    $objFormato->Generar($em, $arMovimiento->getCodigoMovimientoPk());

                } elseif ($arMovimiento->getDocumentoRel()->getNotaCredito() == 1) {
                    $objFormato = new NotaCredito();
                    $objFormato->Generar($em, $arMovimiento->getCodigoMovimientoPk());
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
            if ($form->get('btnActualizarImportacion')->isClicked()) {
                $em->getRepository(InvMovimientoDetalle::class)->actualizarImportacion($arMovimiento);
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository(InvMovimiento::class)->anular($arMovimiento);
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $em->getRepository(InvMovimientoDetalle::class)->eliminar($arMovimiento, $arrDetallesSeleccionados);
                $em->getRepository(InvMovimiento::class)->liquidar($arMovimiento);
            }
            if ($form->get('btnDuplicar')->isClicked()) {
                $em->getRepository(InvMovimientoDetalle::class)->duplicar($arrDetallesSeleccionados);
                $em->getRepository(InvMovimiento::class)->liquidar($arMovimiento);
            }
            return $this->redirect($this->generateUrl('inventario_movimiento_inventario_movimiento_detalle', ['id' => $id]));
        }
        $arImpuestosIva = $em->getRepository(GenImpuesto::class)->findBy(array('codigoImpuestoTipoFk' => 'I'));
        $arImpuestosRetencion = $em->getRepository(GenImpuesto::class)->findBy(array('codigoImpuestoTipoFk' => 'R'));
        $arMovimientoDetalles = $em->getRepository(InvMovimientoDetalle::class)->listaDetalle($id, $arMovimiento->getCodigoDocumentoTipoFk());
        return $this->render('inventario/movimiento/inventario/detalle.html.twig', [
            'form' => $form->createView(),
            'arMovimientoDetalles' => $arMovimientoDetalles,
            'arMovimiento' => $arMovimiento,
            'arImpuestosIva' => $arImpuestosIva,
            'arImpuestosRetencion' => $arImpuestosRetencion,
            'clase' => array('clase'=>'InvMovimiento', 'codigo' => $id),
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
            ->add('txtReferenciaItem', TextType::class, ['label' => 'Referencia: ', 'required' => false, 'data' => $session->get('filtroInvBuscarItemReferencia')])
            ->add('itemConExistencia', CheckboxType::class, array('label' => ' ', 'required' => false, 'data' => $session->get('itemConExistencia')))
            ->add('itemConDisponibilidad', CheckboxType::class, array('label' => ' ', 'required' => false, 'data' => $session->get('filtroItemConDisponibilidad')))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvBucarItemCodigo', $form->get('txtCodigoItem')->getData());
                $session->set('filtroInvBuscarItemNombre', $form->get('txtNombreItem')->getData());
                $session->set('filtroInvBuscarItemReferencia', $form->get('txtReferenciaItem')->getData());
                $session->set('itemConExistencia', $form->get('itemConExistencia')->getData());
                $session->set('filtroItemConDisponibilidad', $form->get('itemConDisponibilidad')->getData());
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrItems = $request->request->get('itemCantidad');
                if (count($arrItems) > 0) {
                    foreach ($arrItems as $codigoItem => $cantidad) {
                        $arItem = $em->getRepository(InvItem::class)->find($codigoItem);
                        if ($cantidad != '' && $cantidad != 0) {
                            if ($arMovimiento->getDocumentoRel()->getCodigoDocumentoTipoFk() == 'ENT' or 'COM'|| $cantidad <= $arItem->getCantidadExistencia() || $arItem->getAfectaInventario() == 0) {
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
                                $arMovimientoDetalle->setCodigoImpuestoRetencionFk($arItem->getCodigoImpuestoRetencionFk());
                                $arMovimientoDetalle->setCodigoImpuestoIvaFk($arItem->getCodigoImpuestoIvaVentaFk());
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
        $arItems = $paginator->paginate($em->getRepository(InvItem::class)->lista(), $request->query->getInt('page', 1), 50);
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
                                    $arMovimientoDetalle->setCantidadOperada($cantidad * $arMovimiento->getOperacionInventario());
                                    $arMovimientoDetalle->setVrPrecio($arOrdenDetalle->getVrPrecio());
                                    $arMovimientoDetalle->setPorcentajeDescuento($arOrdenDetalle->getPorcentajeDescuento());
                                    $arMovimientoDetalle->setCodigoImpuestoRetencionFk($arOrdenDetalle->getItemRel()->getCodigoImpuestoRetencionFk());
                                    $arMovimientoDetalle->setPorcentajeIva($arOrdenDetalle->getItemRel()->getPorcentajeIva());
                                    $arMovimientoDetalle->setCodigoImpuestoIvaFk($arOrdenDetalle->getItemRel()->getCodigoImpuestoIvaVentaFk());
                                    $arMovimientoDetalle->setOrdenDetalleRel($arOrdenDetalle);
                                    $em->persist($arMovimientoDetalle);
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
     * @Route("/inventario/movimiento/inventario/movimiento/detalle/importacion/nuevo/{id}", name="inventario_movimiento_inventario_movimiento_importacion_detalle_nuevo")
     */
    public function detalleNuevoImportacion(Request $request, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigo', TextType::class, array('data' => $session->get('filtroInvMovimientoItemCodigo'), 'required' => false))
            ->add('txtNombre', TextType::class, array('data' => $session->get('filtroInvMovimientoItemNombre'), 'required' => false, 'attr' => ['readonly' => 'readonly']))
            ->add('txtNumero', TextType::class, array('data' => $session->get('filtroInvNumeroImportacion'), 'required' => false))
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $respuesta = '';
        $arMovimiento = $em->getRepository(InvMovimiento::class)->find($id);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvMovimientoItemCodigo', $form->get('txtCodigo')->getData());
                $session->set('filtroInvNumeroImportacion', $form->get('txtNumero')->getData());
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrImportacionDetalles = $request->request->get('itemCantidad');
                if ($arrImportacionDetalles) {
                    if (count($arrImportacionDetalles) > 0) {
                        foreach ($arrImportacionDetalles as $codigoImportacionDetalle => $cantidad) {
                            if ($cantidad != '' && $cantidad != 0) {
                                $arImportacionDetalle = $em->getRepository(InvImportacionDetalle::class)->find($codigoImportacionDetalle);
                                if ($cantidad <= $arImportacionDetalle->getCantidadPendiente()) {
                                    $arMovimientoDetalle = new InvMovimientoDetalle();
                                    $arMovimientoDetalle->setMovimientoRel($arMovimiento);
                                    $arMovimientoDetalle->setOperacionInventario($arMovimiento->getOperacionInventario());
                                    $arMovimientoDetalle->setItemRel($arImportacionDetalle->getItemRel());
                                    $arMovimientoDetalle->setCantidad($cantidad);
                                    $arMovimientoDetalle->setCantidadOperada($cantidad * $arMovimiento->getOperacionInventario());
                                    $arMovimientoDetalle->setVrPrecio($arImportacionDetalle->getVrPrecioLocalTotal());
                                    //$arMovimientoDetalle->setPorcentajeDescuento($arImportacionDetalle->getPorcentajeDescuentoLocal());
                                    $arMovimientoDetalle->setPorcentajeIva($arImportacionDetalle->getItemRel()->getPorcentajeIva());
                                    $arMovimientoDetalle->setCodigoImpuestoIvaFk($arImportacionDetalle->getItemRel()->getCodigoImpuestoIvaVentaFk());
                                    $arMovimientoDetalle->setImportacionDetalleRel($arImportacionDetalle);
                                    $em->persist($arMovimientoDetalle);
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
        $arImportacionDetalles = $paginator->paginate($this->getDoctrine()->getManager()->getRepository(InvImportacionDetalle::class)->listarDetallesPendientes(), $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/inventario/detalleNuevoImportacion.html.twig', [
            'form' => $form->createView(),
            'arImportacionDetalles' => $arImportacionDetalles
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
                                    $arMovimientoDetalle->setCantidadOperada($cantidad * $arMovimiento->getOperacionInventario());
                                    $arMovimientoDetalle->setVrPrecio($arPedidoDetalle->getVrPrecio());
                                    $arMovimientoDetalle->setOperacionInventario($arMovimiento->getOperacionInventario());
                                    $arMovimientoDetalle->setCodigoImpuestoRetencionFk($arPedidoDetalle->getItemRel()->getCodigoImpuestoRetencionFk());
                                    $arMovimientoDetalle->setPorcentajeIva($arPedidoDetalle->getItemRel()->getPorcentajeIva());
                                    $arMovimientoDetalle->setCodigoImpuestoIvaFk($arPedidoDetalle->getItemRel()->getCodigoImpuestoIvaVentaFk());
                                    $arMovimientoDetalle->setPedidoDetalleRel($arPedidoDetalle);
                                    $em->persist($arMovimientoDetalle);
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
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/inventario/movimiento/inventario/movimiento/detalle/remision/nuevo/{id}", name="inventario_movimiento_inventario_movimiento_remision_detalle_nuevo")
     */
    public function detalleNuevoRemision(Request $request, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigoTercero', TextType::class, ['required' => false, 'data' => $session->get('filtroInvCodigoTercero'), 'attr' => ['class' => 'form-control']])
            ->add('txtNumero', TextType::class, array('required' => false))
            ->add('txtLote', TextType::class, array('required' => false))
            ->add('cboBodega', EntityType::class, $em->getRepository(InvBodega::class)->llenarCombo())
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $arMovimiento = $em->getRepository(InvMovimiento::class)->find($id);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvCodigoTercero', $form->get('txtCodigoTercero')->getData());
                $session->set('filtroInvRemisionNumero', $form->get('txtNumero')->getData());
                $session->set('filtroInvRemisionDetalleLote', $form->get('txtLote')->getData());
                $arBodega = $form->get('cboBodega')->getData();
                if ($arBodega != '') {
                    $session->set('filtroInvBodega', $form->get('cboBodega')->getData()->getCodigoBodegaPk());
                } else {
                    $session->set('filtroInvBodega', null);
                }
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
                                    $arMovimientoDetalle->setOperacionInventario($arMovimiento->getOperacionInventario());
                                    $arMovimientoDetalle->setCantidad($cantidad);
                                    $arMovimientoDetalle->setCantidadOperada($cantidad * $arMovimiento->getOperacionInventario());
                                    $arMovimientoDetalle->setVrPrecio($arRemisionDetalle->getVrPrecio());
                                    $arMovimientoDetalle->setCodigoImpuestoRetencionFk($arRemisionDetalle->getItemRel()->getCodigoImpuestoRetencionFk());
                                    $arMovimientoDetalle->setPorcentajeIva($arRemisionDetalle->getItemRel()->getPorcentajeIva());
                                    $arMovimientoDetalle->setCodigoImpuestoIvaFk($arRemisionDetalle->getItemRel()->getCodigoImpuestoIvaVentaFk());
                                    $arMovimientoDetalle->setRemisionDetalleRel($arRemisionDetalle);
                                    $arMovimientoDetalle->setLoteFk($arRemisionDetalle->getLoteFk());
                                    $arMovimientoDetalle->setCodigoBodegaFk($arRemisionDetalle->getCodigoBodegaFk());
                                    $em->persist($arMovimientoDetalle);
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
        $arRemisionDetalles = $paginator->paginate($this->getDoctrine()->getManager()->getRepository(InvRemisionDetalle::class)->listarDetallesPendientes($arMovimiento->getCodigoTerceroFk()), $request->query->getInt('page', 1), 500);
        return $this->render('inventario/movimiento/inventario/detalleNuevoRemision.html.twig', [
            'form' => $form->createView(),
            'arRemisionDetalles' => $arRemisionDetalles
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/inventario/movimiento/inventario/movimiento/movimiento/nuevo/{id}", name="inventario_movimiento_inventario_movimiento_movimiento_nuevo")
     */
    public function detalleNuevoMovimiento(Request $request, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigoTercero', TextType::class, ['required' => false, 'data' => $session->get('filtroInvCodigoTercero'), 'attr' => ['class' => 'form-control']])
            ->add('txtNumero', TextType::class, array('required' => false))
            ->add('txtLote', TextType::class, array('required' => false))
            ->add('cboBodega', EntityType::class, $em->getRepository(InvBodega::class)->llenarCombo())
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $arMovimiento = $em->getRepository(InvMovimiento::class)->find($id);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvCodigoTercero', $form->get('txtCodigoTercero')->getData());
                $session->set('filtroInvRemisionNumero', $form->get('txtNumero')->getData());
                $session->set('filtroInvRemisionDetalleLote', $form->get('txtLote')->getData());
                $arBodega = $form->get('cboBodega')->getData();
                if ($arBodega != '') {
                    $session->set('filtroInvBodega', $form->get('cboBodega')->getData()->getCodigoBodegaPk());
                } else {
                    $session->set('filtroInvBodega', null);
                }
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrSeleccionados) {
                    foreach ($arrSeleccionados as $codigo) {
                        $arMovimientoDetallesOrigen = $em->getRepository(InvMovimientoDetalle::class)->findBy(array('codigoMovimientoFk' => $codigo));
                        foreach ($arMovimientoDetallesOrigen as $arMovimientoDetalleOrigen) {
                            $arMovimientoDetalle = new InvMovimientoDetalle();
                            $arMovimientoDetalle->setMovimientoRel($arMovimiento);
                            $arMovimientoDetalle->setItemRel($arMovimientoDetalleOrigen->getItemRel());
                            $arMovimientoDetalle->setOperacionInventario($arMovimiento->getOperacionInventario());
                            $cantidad = $arMovimientoDetalleOrigen->getCantidad();
                            $arMovimientoDetalle->setCantidad($cantidad);
                            $arMovimientoDetalle->setCantidadOperada($cantidad * $arMovimiento->getOperacionInventario());
                            $arMovimientoDetalle->setVrPrecio($arMovimientoDetalleOrigen->getVrPrecio());
                            $arMovimientoDetalle->setCodigoImpuestoRetencionFk($arMovimientoDetalleOrigen->getItemRel()->getCodigoImpuestoRetencionFk());
                            $arMovimientoDetalle->setPorcentajeIva($arMovimientoDetalleOrigen->getItemRel()->getPorcentajeIva());
                            $arMovimientoDetalle->setCodigoImpuestoIvaFk($arMovimientoDetalleOrigen->getItemRel()->getCodigoImpuestoIvaVentaFk());
                            $arMovimientoDetalle->setMovimientoDetalleRel($arMovimientoDetalleOrigen);
                            $arMovimientoDetalle->setLoteFk($arMovimientoDetalleOrigen->getLoteFk());
                            $arMovimientoDetalle->setCodigoBodegaFk($arMovimientoDetalleOrigen->getCodigoBodegaFk());
                            $em->persist($arMovimientoDetalle);
                        }
                    }
                    $em->flush();
                    $em->getRepository(InvMovimiento::class)->liquidar($arMovimiento);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $arMovimientos = $paginator->paginate($this->getDoctrine()->getManager()->getRepository(InvMovimiento::class)->listarPendientesNotaCredito($arMovimiento->getCodigoTerceroFk()), $request->query->getInt('page', 1), 500);
        return $this->render('inventario/movimiento/inventario/detalleNuevoMovimiento.html.twig', [
            'form' => $form->createView(),
            'arMovimientos' => $arMovimientos
        ]);
    }

    /**
     * @Route("/inventario/movimiento/inventario/movimiento/detalle/distrubucion/cargar/{id}", name="inventario_movimiento_inventario_movimiento_remision_distribucion_cargar")
     */
    public function cargarDatosDistribuidos(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('txtDatos', TextareaType::class, ['required' => true, 'attr' => ['rows' => '6']])
            ->getForm();
        $form->handleRequest($request);
        $arMovimientoDetalle = $em->find(InvMovimientoDetalle::class, $id);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $datos = $form->get('txtDatos')->getData();
                $arrDatos = array_map(function ($var) {
                    return str_replace("\r", '', $var);
                }, preg_split("/[\n]/", $datos));
                foreach ($arrDatos as $registro) {
                    $arrCampos = preg_split("/[\t]/", $registro);
                    $arMovimientoDetalleNuevo = clone $arMovimientoDetalle;
                    $arMovimientoDetalleNuevo->setLoteFk($arrCampos[0]);
                    $arMovimientoDetalleNuevo->setCodigoBodegaFk($arrCampos[1]);
                    $arMovimientoDetalleNuevo->setFechaVencimiento(date_create($arrCampos[2]));
                    $arMovimientoDetalleNuevo->setCantidad($arrCampos[3]);
                    $em->persist($arMovimientoDetalleNuevo);
                }
            }
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('inventario/movimiento/inventario/cargarDistrubucion.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/movimiento/inventario/movimiento/detalle/referenciaDetalle/{id}", name="inventario_movimiento_inventario_movimiento_detalle_referenciaDetalle")
     */
    public function referenciaDetalle($id)
    {
        $em = $this->getDoctrine()->getManager();
        $arMovimientoDetalle = $em->find(InvMovimientoDetalle::class, $id);
        $arPedidoDetalle = $arMovimientoDetalle->getCodigoPedidoDetalleFk() != null ? $em->find(InvPedidoDetalle::class, $arMovimientoDetalle->getCodigoPedidoDetalleFk()) : null;
        $arOrdenDetalle = $arMovimientoDetalle->getCodigoOrdenDetalleFk() != null ? $em->find(InvOrdenDetalle::class, $arMovimientoDetalle->getCodigoOrdenDetalleFk()) : null;
        return $this->render('inventario/movimiento/inventario/referenciaDetalle.html.twig', [
            'arMovimientoDetalle' => $arMovimientoDetalle,
            'arPedidoDetalle' => $arPedidoDetalle,
            'arOrdenDetalle' => $arOrdenDetalle
        ]);
    }
}
