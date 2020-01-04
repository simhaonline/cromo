<?php

namespace App\Controller\Turno\Movimiento\Venta;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenImpuesto;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurClienteIca;
use App\Entity\Turno\TurConfiguracion;
use App\Entity\Turno\TurFactura;
use App\Entity\Turno\TurFacturaDetalle;
use App\Entity\Turno\TurFacturaTipo;
use App\Entity\Turno\TurItem;
use App\Entity\Turno\TurPedidoDetalle;
use App\Form\Type\Turno\FacturaType;
use App\Formato\Turno\Factura1;
use App\Formato\Turno\Factura2;
use App\Formato\Turno\Factura3;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FacturaController extends AbstractController
{
    protected $clase = TurFactura::class;
    protected $claseNombre = "TurFactura";
    protected $modulo = "Turno";
    protected $funcion = "Movimiento";
    protected $grupo = "Venta";
    protected $nombre = "Factura";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/turno/movimiento/comercial/factura/lista", name="turno_movimiento_venta_factura_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoClienteFk', TextType::class, array('required' => false))
            ->add('numero', TextType::class, array('required' => false))
            ->add('codigoFacturaPk', TextType::class, array('required' => false))
            ->add('codigoFacturaTipoFk', EntityType::class, [
                'class' => TurFacturaTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ft')
                        ->orderBy('ft.codigoFacturaTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoContabilizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('btnFiltro', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnContabilizar', SubmitType::class, ['label' => 'Contabilizar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('BtnInterfazTrade', SubmitType::class, array('label' => 'TRADE',))
            ->add('BtnInterfazMvTrade', SubmitType::class, array('label' => 'MVTRADE',))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltro')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(TurFactura::class)->lista($raw), "Factura");
            }
            if ($form->get('btnContabilizar')->isClicked()) {
                $arr = $request->query->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TurFactura::class)->contabilizar($arr);
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(TurFactura::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('turno_movimiento_venta_factura_lista'));
            }
            if ($form->get('BtnInterfazTrade')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $raw['filtros'] = $this->getFiltros($form);
                $arFacturas = $em->getRepository(TurFactura::class)->listaPendienteExportarOfimatica($raw);
                $this->generarInterfazOfimaticaTrade($arFacturas);
            }
            if ($form->get('BtnInterfazMvTrade')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $raw['filtros'] = $this->getFiltros($form);
                $arFacturaDetalles = $em->getRepository(TurFacturaDetalle::class)->listaPendienteExportarOfimatica($raw);
                $this->generarInterfazOfimaticaMvTrade($arFacturaDetalles);
            }
        }

        $arFacturas = $paginator->paginate($em->getRepository(TurFactura::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('turno/movimiento/venta/factura/lista.html.twig', [
            'arFacturas' => $arFacturas,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/turno/movimiento/comercial/factura/nuevo/{id}/{clase}", name="turno_movimiento_venta_factura_nuevo")
     */
    public function nuevo(Request $request, $id, $clase)
    {
        $em = $this->getDoctrine()->getManager();
        $arFactura = new TurFactura();
        $objFunciones = new FuncionesController();
        if ($id != 0) {
            $arFactura = $em->getRepository(TurFactura::class)->find($id);
        } else {
            $arFactura->setCodigoFacturaClaseFk($clase);
            $arFactura->setUsuario($this->getUser()->getUserName());
            $arFactura->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(FacturaType::class, $arFactura);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoCliente = $request->request->get('txtCodigoCliente');
                if ($txtCodigoCliente != '') {
                    $arCliente = $em->getRepository(TurCliente::class)->find($txtCodigoCliente);
                    if ($arCliente) {
                        $fecha = new \DateTime('now');
                        $arFactura->setFechaVence($arFactura->getPlazoPago() == 0 ? $fecha : $objFunciones->sumarDiasFecha($fecha, $arFactura->getPlazoPago()));
                        $arFactura->setClienteRel($arCliente);
                        $arFactura->setFecha(new \DateTime('now'));
                        $arFactura->setCodigoFacturaClaseFk($arFactura->getFacturaTipoRel()->getCodigoFacturaClaseFk());
                        $em->persist($arFactura);
                        $em->flush();
                        return $this->redirect($this->generateUrl('turno_movimiento_venta_factura_detalle', ['id' => $arFactura->getCodigoFacturaPk()]));
                    }
                } else {
                    Mensajes::error('Debe seleccionar un cliente');
                }
            }
        }
        return $this->render('turno/movimiento/venta/factura/nuevo.html.twig', [
            'arFactura' => $arFactura,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/turno/movimiento/comercial/factura/detalle/{id}", name="turno_movimiento_venta_factura_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arFactura = $em->getRepository(TurFactura::class)->find($id);
        $form = Estandares::botonera($arFactura->getEstadoAutorizado(), $arFactura->getEstadoAprobado(), $arFactura->getEstadoAnulado());

        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAutorizar = ['label' => 'Autorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAprobado = ['label' => 'Aprobar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDesautorizar = ['label' => 'Desautorizar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arFactura->getEstadoAutorizado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnAprobado['disabled'] = false;
            $arrBtnActualizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = false;
        }
        if ($arFactura->getEstadoAprobado()) {
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnAprobado['disabled'] = true;
        }

        $form->add('btnActualizar', SubmitType::class, $arrBtnActualizar);
        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TurFactura::class)->autorizar($arFactura);
                $em->getRepository(TurFactura::class)->liquidar($arFactura);
                return $this->redirect($this->generateUrl('turno_movimiento_venta_factura_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TurFactura::class)->desautorizar($arFactura);
                return $this->redirect($this->generateUrl('turno_movimiento_venta_factura_detalle', ['id' => $id]));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TurFactura::class)->aprobar($arFactura);
                return $this->redirect($this->generateUrl('turno_movimiento_venta_factura_detalle', ['id' => $id]));
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository(TurFactura::class)->anular($arFactura);
                return $this->redirect($this->generateUrl('turno_movimiento_venta_factura_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $em->getRepository(TurFacturaDetalle::class)->eliminar($arFactura, $arrDetallesSeleccionados);
                $em->getRepository(TurFactura::class)->liquidar($arFactura);
                return $this->redirect($this->generateUrl('turno_movimiento_venta_factura_detalle', ['id' => $id]));
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(TurFacturaDetalle::class)->actualizarDetalles($arrControles, $form, $arFactura);
                return $this->redirect($this->generateUrl('turno_movimiento_venta_factura_detalle', ['id' => $id]));
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $arConfiguracion = $em->getRepository(TurConfiguracion::class)->find(1);
                switch ($arConfiguracion->getCodigoFormatoFactura()){
                    case 1:
                        $formatoFactura = new Factura1();
                        $formatoFactura->Generar($em, $id, $arFactura->getNumero(), $this->getUser());
                        break;
                    case 2:
                        $formatoFactura = new Factura2();
                        $formatoFactura->Generar($em, $id);
                        break;
                    case 3:
                        $formatoFactura = new Factura3();
                        $formatoFactura->Generar($em, $id);
                        break;
                    default:
                        Mensajes::error("SIN FORMATO DE FACTURA");
                        break;
                }
            }
        }
        $arImpuestosIva = $em->getRepository(GenImpuesto::class)->findBy(array('codigoImpuestoTipoFk' => 'I'));
        $arImpuestosRetencion = $em->getRepository(GenImpuesto::class)->findBy(array('codigoImpuestoTipoFk' => 'R'));
        $arFacturaDetalles = $paginator->paginate($em->getRepository(TurFacturaDetalle::class)->lista($id), $request->query->getInt('page', 1), 500);
        return $this->render('turno/movimiento/venta/factura/detalle.html.twig', [
            'form' => $form->createView(),
            'arFacturaDetalles' => $arFacturaDetalles,
            'arFactura' => $arFactura,
            'arImpuestosIva' => $arImpuestosIva,
            'arImpuestosRetencion' => $arImpuestosRetencion,
            'clase' => array('clase' => 'TurFactura', 'codigo' => $id)
        ]);
    }

    /**
     * @Route("/turno/movimiento/comercial/factura/detalle/factura/nuevo/{id}", name="turno_movimiento_venta_factura_detalle_factura_nuevo")
     */
    public function detalleNuevoFactura(Request $request, PaginatorInterface $paginator, $id)
    {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $arFactura = $em->getRepository(TurFactura::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('txtNumero', TextType::class, ['required' => false])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar',))
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('btnGuardar')->isClicked()) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if ($arrSeleccionados) {
                        foreach ($arrSeleccionados AS $codigo) {
                            $arFacturaDetalle = $em->getRepository(TurFacturaDetalle::class)->find($codigo);
                            $arFacturaDetalleNueva = new TurFacturaDetalle();
                            $arFacturaDetalleNueva->setFacturaRel($arFactura);
                            $arFacturaDetalleNueva->setConceptoRel($arFacturaDetalle->getConceptoRel());
                            $arFacturaDetalleNueva->setItemRel($arFacturaDetalle->getItemRel());
                            $arFacturaDetalleNueva->setPuestoRel($arFacturaDetalle->getPuestoRel());
                            $arFacturaDetalleNueva->setPedidoDetalleRel($arFacturaDetalle->getPedidoDetalleRel());
                            $arFacturaDetalleNueva->setFacturaDetalleRel($arFacturaDetalle);
                            $arFacturaDetalleNueva->setCantidad($arFacturaDetalle->getCantidad());
                            $arFacturaDetalleNueva->setVrPrecio($arFacturaDetalle->getVrPrecio());
                            $arFacturaDetalleNueva->setCodigoImpuestoIvaFk($arFacturaDetalle->getCodigoImpuestoIvaFk());
                            $arFacturaDetalleNueva->setCodigoImpuestoRetencionFk($arFacturaDetalle->getCodigoImpuestoRetencionFk());
                            $arFacturaDetalleNueva->setPorcentajeIva($arFacturaDetalle->getItemRel()->getImpuestoIvaVentaRel()->getPorcentaje());
                            $arFacturaDetalleNueva->setPorcentajeBaseIva($arFacturaDetalle->getItemRel()->getImpuestoIvaVentaRel()->getPorcentajeBase());
                            $arFacturaDetalleNueva->setDetalle($arFacturaDetalle->getDetalle());
                            $em->persist($arFacturaDetalleNueva);
                        }
                    }
                    $em->flush();
                    $em->getRepository(TurFactura::class)->liquidar($arFactura);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
                if ($form->get('BtnFiltrar')->isClicked()) {
                    $session->set('filtroCodigoFactura', $form->get('txtCodigo')->getData());
                    $session->set('filtroNumeroFactura', $form->get('txtNumero')->getData());
                }
            }
        }
        $dql = $em->getRepository(TurFacturaDetalle::class)->listaCliente($arFactura->getCodigoClienteFk(), "");
        $arFacturaDetalles = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 1000);
        return $this->render('turno/movimiento/venta/factura/detalleNuevoFactura.html.twig', array(
            'arFactura' => $arFactura,
            'arFacturaDetalles' => $arFacturaDetalles,
            'form' => $form->createView()));
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * @Route("/turno/movimiento/comercial/factura/detalle/nuevo/{id}", name="turno_movimiento_venta_factura_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $id, PaginatorInterface $paginator)
    {
        /**
         * @var $arItem TurItem
         */
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $respuesta = '';
        $arFactura = $em->getRepository(TurFactura::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('txtCodigoItem', TextType::class, ['label' => 'Codigo: ', 'required' => false])
            ->add('txtNombreItem', TextType::class, ['label' => 'Nombre: ', 'required' => false, 'data' => $session->get('filtroInvBuscarItemNombre')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => null
        ];

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrItems = $request->request->get('itemCantidad');
                if (count($arrItems) > 0) {
                    foreach ($arrItems as $codigoItem => $cantidad) {
                        $arItem = $em->getRepository(TurItem::class)->find($codigoItem);
                        if ($cantidad != '' && $cantidad != 0) {
                            $arFacturaDetalle = New TurFacturaDetalle();
                            $arFacturaDetalle->setFacturaRel($arFactura);
                            $arFacturaDetalle->setItemRel($arItem);
                            $arFacturaDetalle->setCantidad($cantidad);
                            $arFacturaDetalle->setCodigoImpuestoRetencionFk($arItem->getCodigoImpuestoRetencionFk());
                            $arFacturaDetalle->setCodigoImpuestoIvaFk($arItem->getCodigoImpuestoIvaVentaFk());
                            $arFacturaDetalle->setPorcentajeIva($arItem->getImpuestoIvaVentaRel()->getPorcentaje());
                            $arFacturaDetalle->setPorcentajeBaseIva($arItem->getImpuestoIvaVentaRel()->getBase());
                            $em->persist($arFacturaDetalle);
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
            if ($form->get('btnFiltrar')) {
                $raw['filtros'] = ['itemCodigo' => $form->get('txtCodigoItem')->getData(), 'itemNombre' => $form->get('txtNombreItem')->getData()];
            }
        }
        $arItems = $paginator->paginate($em->getRepository(TurItem::class)->lista($raw), $request->query->getInt('page', 1), 50);
        return $this->render('turno/movimiento/venta/factura/detalleNuevo.html.twig', [
            'arItems' => $arItems,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * @Route("/turno/movimiento/comercial/factura/detalle/pedido/nuevo/{id}", name="turno_movimiento_venta_factura_detalle_pedido_nuevo")
     */
    public function detalleNuevoPedido(Request $request, PaginatorInterface $paginator, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arFactura = $em->getRepository(TurFactura::class)->find($id);
        $codigoCliente =  $arFactura->getCodigoClienteFk();
        $form = $this->createFormBuilder()
            ->add('ChkMostrarTodo', CheckboxType::class, array('label' => false, 'required' => false))
            ->add('numero', TextType::class, array('required' => false))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        $raw['filtros'] = [
            'mostrarTodo' => $form->get('ChkMostrarTodo')->getData(),
            'numero' => $form->get('numero')->getData(),
        ];
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrSeleccionados) {
                    foreach ($arrSeleccionados as $codigo) {
                        $arPedidoDetalle = $em->getRepository(TurPedidoDetalle::class)->find($codigo);
                        $arFacturaDetalle = new TurFacturaDetalle();
                        $arFacturaDetalle->setFacturaRel($arFactura);
                        $arFacturaDetalle->setPedidoDetalleRel($arPedidoDetalle);
                        $arFacturaDetalle->setItemRel($arPedidoDetalle->getItemRel());
                        $arFacturaDetalle->setCodigoImpuestoIvaFk($arPedidoDetalle->getItemRel()->getCodigoImpuestoIvaVentaFk());
                        $arFacturaDetalle->setPorcentajeIva($arPedidoDetalle->getItemRel()->getImpuestoIvaVentaRel()->getPorcentaje());
                        $arFacturaDetalle->setPorcentajeBaseIva($arPedidoDetalle->getItemRel()->getImpuestoIvaVentaRel()->getPorcentajeBase());
                        $arFacturaDetalle->setConceptoRel($arPedidoDetalle->getConceptoRel());
                        $arFacturaDetalle->setPuestoRel($arPedidoDetalle->getPuestoRel());
                        $arFacturaDetalle->setCantidad($arPedidoDetalle->getCantidad());
                        $arFacturaDetalle->setVrPrecio($arPedidoDetalle->getVrPendiente());
                        $arFacturaDetalle->setFechaOperacion($arPedidoDetalle->getPedidoRel()->getFecha());
                        $arFacturaDetalle->setModalidadRel($arPedidoDetalle->getModalidadRel());
                        $arFacturaDetalle->setGrupoRel($arPedidoDetalle->getGrupoRel());
                        $em->persist($arFacturaDetalle);
                    }
                    $em->flush();
                    $em->getRepository(TurFactura::class)->liquidar($arFactura);
                    if ($form->get('btnGuardar')->isClicked()) {
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    }
                }
            }
        }
        $arPedidoDetalles = $paginator->paginate($em->getRepository(TurPedidoDetalle::class)->pendienteFacturar($raw, $codigoCliente), $request->query->getInt('page', 1), 500);
        return $this->render('turno/movimiento/venta/factura/detalleNuevoPedido.html.twig', [
            'arPedidoDetalles' => $arPedidoDetalles,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $fitro = [
            'codigoClienteFk' => $form->get('codigoClienteFk')->getData(),
            'numero' => $form->get('numero')->getData(),
            'codigoFacturaPk' => $form->get('codigoFacturaPk')->getData(),
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
            'estadoContabilizado' => $form->get('estadoContabilizado')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,

        ];
        $arFacturaTipo = $form->get('codigoFacturaTipoFk')->getData();

        if (is_object($arFacturaTipo)) {
            $fitro['codigoFacturaTipoFk'] = $arFacturaTipo->getCodigoFacturaTipoPk();
        } else {
            $fitro['codigoFacturaTipoFk'] = $arFacturaTipo;
        }
        return $fitro;
    }

    private function generarInterfazOfimaticaTrade($arFacturas)
    {
        /**
         * @var TurFactura $arFactura
         * @var \PHPExcel $objPhpExcel
         */
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $libro = new Spreadsheet();
        $hoja = $libro->getActiveSheet();
        $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
//        $hoja->setTitle('Movimientos');
        // Set document properties
        for ($col = 'A'; $col !== 'O'; $col++) {
            $hoja->getColumnDimension($col)->setAutoSize(true);
        }
        $hoja->getStyle('G')->getNumberFormat()->setFormatCode('yyyy/mm/dd');
        $hoja->getStyle('H')->getNumberFormat()->setFormatCode('yyyy/mm/dd');
        $hoja
            ->setCellValue('A1', 'TRADE')
            ->setCellValue('A2', 'ORIGEN')//Tipo de movimiento de la factura
            ->setCellValue('B2', 'TIPODCTO')//Tipo de movimiento de la factura
            ->setCellValue('C2', 'NRODCTO')//Numero de documento de la factura
            ->setCellValue('D2', 'NIT')//Nit del cliente
            ->setCellValue('E2', 'DIR')//Direccion del cliente o de la factura
            ->setCellValue('F2', 'CIUDADCLI')//Ciudad del cliente
            ->setCellValue('G2', 'FECHA')//Fecha de la factura
            ->setCellValue('H2', 'FECHA1')//Fecha de vencimiento de la factura
            ->setCellValue('I2', 'RESPRETE')
            ->setCellValue('J2', 'CALRETE')
            ->setCellValue('K2', 'CALRETICA')
            ->setCellValue('L2', 'CTRTOPES')
            ->setCellValue('M2', 'PGIVA')
            ->setCellValue('N2', 'PRETIVA')
            ->setCellValue('O2', 'NOTA')
            ->setCellValue('P2', 'CODIGOCTA')
            ->setCellValue('Q2', 'TIPOMVTO')
            ->setCellValue('R2', 'CODINT')
            ->setCellValue('S2', 'CONTADO')
            ->setCellValue("T2", "MEDIOPAG")
            ->setCellValue("U2", "DECIMALES");
        $libro->setActiveSheetIndex(0);

        $i = 3;
        foreach ($arFacturas as $arFactura) {
            $hoja->getStyle($i)->getFont()->setName('Arial')->setSize(9);
            $hoja->setCellValue('A' . $i, 'FAC')//Tipo de movimiento de la factura
                ->setCellValue('B' . $i, $arFactura['abreviatura'])//Tipo de movimiento de la factura
                ->setCellValue('C' . $i, $arFactura['numero'])//Numero de documento de la factura
                ->setCellValue('D' . $i,  $arFactura['numeroIdentificacion']. "" . ( $arFactura['direccion'] ? "S" : "") . "-" . $arFactura['digitoVerificacion'] )//Nit del cliente
                ->setCellValue('E' . $i, $arFactura['direccion'])//Direccion del cliente o de la factura
                ->setCellValue('F' . $i, $arFactura['codigoDane'])// dane
                ->setCellValue('G' . $i, $arFactura['fecha']->format('Y/m/d'))//Fecha de la factura
                ->setCellValue('H' . $i, $arFactura['fechaVence']->format('Y/m/d'))//Fecha de la factura//Fecha de vencimiento de la factura
                ->setCellValue('I' . $i, $arFactura['vrRetencionFuente'] > 0 ? "1" : 0)//Si el encabezado maneja retencion en la fuente los detalles van en 1
                ->setCellValue('J' . $i, $arFactura['vrRetencionFuente'] > 0 ? 1 : 0)//Si el encabezado maneja retencion en la renta los detalles van en 1
                ->setCellValue('K' . $i, 0)//CALRETICA reteica PENDIENTE VALIDAR CUANDO SE CALCULE LA RETENCION DEL ICA
                ->setCellValue('L' . $i, "1")//CTRTOPES Siempre va 1
                ->setCellValue('M' . $i, "19")//Porcentaje del iva
                ->setCellValue('N' . $i, $arFactura['vrRetencionIva'] > 0 ? "15" : 0)//CTRTOPES Siempre va 1
                ->setCellValue('O' . $i, $arFactura['descripcion'])//Descripcion del encabezado
                ->setCellValue('P' . $i, "13050501")//Siempre ese valor
                ->setCellValue('Q' . $i, "2001")//Siempre ese valor
                ->setCellValue('R' . $i,  $arFactura['codigoFacturaClaseFk'] == "NC" ? 402 : 401)//Siempre ese valor
                ->setCellValue('S' . $i, 0)//Siempre va 0
                ->setCellValue('T' . $i, 01)//Siempre va 05
                ->setCellValue('U' . $i, 9)//Siempre va 9
            ;
            $i++;
        }
        $hoja->setTitle('Facturas');
        $libro->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=Trade.xls");
        header('Cache-Control: max-age=0');
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
        $writer->save('php://output');
    }

    /**
     * Funcion para generar los detalles de la factura para la interfaz de ofimatica
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    private function generarInterfazOfimaticaMvTrade($arFacturaDetalles)
    {
        /**
         * @var TurFacturaDetalle $arFacturaDetalle
         */
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $libro = new Spreadsheet();
        $hoja = $libro->getActiveSheet();
        $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
        for ($col = 'A'; $col !== 'O'; $col++) {
            $hoja->getColumnDimension($col)->setAutoSize(true);
        }
        $hoja->getStyle('Y')->getNumberFormat()->setFormatCode('yyyy/mm/dd');
        $hoja->getStyle('Z')->getNumberFormat()->setFormatCode('yyyy/mm/dd');
        $hoja->getStyle('AA')->getNumberFormat()->setFormatCode('yyyy/mm/dd');
        $hoja->getStyle('AB')->getNumberFormat()->setFormatCode('yyyy/mm/dd');
        $hoja->getStyle('AG')->getNumberFormat()->setFormatCode('yyyy/mm/dd');
        $hoja->getStyle(2)->getFont()->setName('Arial')->setSize(9);

        $hoja
            ->setCellValue('A1', 'MVTRADE')
            ->setCellValue('A2', 'ORIGEN')//Tipo de movimiento de la factura
            ->setCellValue('B2', 'TIPODCTO')//Tipo de movimiento de la factura
            ->setCellValue('C2', 'NRODCTO')//Numero de documento de la factura
            ->setCellValue('D2', 'BODEGA')//Bodega
            ->setCellValue('E2', 'PRODUCTO')//codigo de interfaz del concepto de servicio
            ->setCellValue('F2', 'NOMBRE')//Nombre del concepto de servicio
            ->setCellValue('G2', 'CANTIDAD')//Cantidad unitaria
            ->setCellValue('H2', 'CANTORIG')//Cantidad unitaria
            ->setCellValue('I2', 'CANVENTA')//Cantidad unitaria
            ->setCellValue('J2', 'VALORUNIT')//Valor unitario del producto
            ->setCellValue('K2', 'VLRVENTA')//Valor unitario del producto
            ->setCellValue('L2', 'ZVALORUNIT')//Valor unitario del producto
            ->setCellValue('M2', 'CODCC')//Codigo del centro de costo del puesto por cada copncepto de servicio
            ->setCellValue('N2', 'ITEMIVA')//Si tiene iva 1 sino 0
            ->setCellValue('O2', 'TARIVA')//Se debe concater SG con el porcentaje del iva
            ->setCellValue('P2', 'IVA')//Porcentaje del iva
            ->setCellValue('Q2', 'ITEMRETE')//Si lleva retencion
            ->setCellValue('R2', 'CODRETE')//Se debe concatenar SG y el porcentaje de la retencion
            ->setCellValue('S2', 'PORETE')//Pocentaje de la retencion
            ->setCellValue('T2', 'ITEMICA')//SI maneja reteica 1 sino 0
            ->setCellValue('U2', 'CODRETICA')//Se debe concatenar SG y el porcentaje del reteica
            ->setCellValue('V2', 'PORICA')//Porcentaje del reteica
            ->setCellValue('W2', 'TIPOMVTO')//Tipo de movimiento siempre 2001
            ->setCellValue('X2', 'TOPRETE')//Campo en servicio factura PENDIENTE DEFINIR
            ->setCellValue('Y2', 'FECENT')//Fecha del documento
            ->setCellValue('Z2', 'FECHA')//Fecha del documento
            ->setCellValue('AA2', 'FECING')//Fecha del documento
            ->setCellValue('AB2', 'FECMOD')//Siempre vacio
            ->setCellValue('AC2', 'CODINT')//Siempre 603
            ->setCellValue('AD2', 'CTAVTA')//Siempre 0
            ->setCellValue('AE2', 'NUMFACTNC')//Cuando sea una ND o una NC se deben llenar esos dos campos con el número de factura y el tipo de documento de la factura asociada. En el caso de una factura deben ir en blanco
            ->setCellValue('AF2', 'TIPODCTOFA')//Cuando sea una ND o una NC se deben llenar esos dos campos con el número de factura y el tipo de documento de la factura asociada. En el caso de una factura deben ir en blanco
            ->setCellValue('AG2', 'FECPEDIDO')
            ->setCellValue('AH2', 'NROPEDIDO')
            ->setCellValue('AI2', 'NOTA');

        $i = 3;
        foreach ($arFacturaDetalles as $arFacturaDetalle) {
            $hoja->getStyle($i)->getFont()->setName('Arial')->setSize(9);

            $strBodega = "";
            $codigoCentroCosto =  $arFacturaDetalle["codigoPuestoFk"]  ? $arFacturaDetalle["codigoCentroCostoFk"] : "";
            if (substr($codigoCentroCosto, 0, 1) == 1) {//Segun primer numero del centro de costo.
                $strBodega = "BBARRANQUILLA";
            }
            if (substr($codigoCentroCosto, 0, 1) == 2) {//Segun primer numero del centro de costo.
                $strBodega = "BMEDELLIN";
            }
            if (substr($codigoCentroCosto, 0, 1) == 3) {//Segun primer numero del centro de costo.
                $strBodega = "BMANIZALES";
            }
            if (substr($codigoCentroCosto, 0, 1) == 4) {//Segun primer numero del centro de costo.
                $strBodega = "BOGOTA";
            }
            $porIva = $arFacturaDetalle["porcentajeBaseIva"] > 0 ? ($arFacturaDetalle["vrIva"] * $arFacturaDetalle["porcentajeBaseIva"]) / 100 : $arFacturaDetalle["vrIva"];
//            $porReteFuente =  $arFacturaDetalle["porcentajeBaseIva"] > 0 ? $arFacturaDetalle[] getConceptoServicioRel()->getPorRetencionFuente() *  $arFacturaDetalle["vrBaseIva"] / 100 : $arFacturaDetalle->getFacturaRel()->getFacturaServicioRel()->getPorRetencionFuente();
            $porReteFuente = 0 ; //quitar esta linea
            $tarIca = 0;
            //Logica de negocio si el detalle maneja retencion ICA
            // comentado desde proto att: andres
//            if ($arFacturaDetalle->getCodigoConceptoServicioFk() && $arFacturaDetalle->getFacturaRel()->getCodigoClienteFk() && $arFacturaDetalle->getCodigoPuestoFk() && $arFacturaDetalle->getPuestoRel()->getCodigoCiudadFk()) {//Validar si el detalle si tiene asociado un cliente y un concepto de servicio
//                $arClienteIca = $em->getRepository(TurClienteIca::class)->findOneBy(array('codigoClienteFk' => $arFacturaDetalle->getFacturaRel()->getClienteRel()->getCodigoClientePk(),
//                    'codigoDane' => $arFacturaDetalle->getPuestoRel()->getCiudadRel()->getCodigoInterface(), 'codigoServicioErp' => $arFacturaDetalle->getConceptoServicioRel()->getCodigoServicioErp()));
//                if ($arClienteIca) {
//                    $tarIca = $arClienteIca->getPorIca();
//                }
//            }
//            if ($arFacturaDetalle->getPuestoRel() && $arFacturaDetalle->getPuestoRel()->getCiudadRel() && $arFacturaDetalle['co']getConceptoServicioRel()) {
//                $tarIca = $em->getRepository(TurClienteIca::class)->tarifaIca($arFacturaDetalle['codigoClienteFk'],
//                                                                                        $arFacturaDetalle['codigoCiudadFk'],
//                                                                                        $arFacturaDetalle->getConceptoServicioRel()->getCodigoServicioErp(),
//                                                                                        $arFacturaDetalle->getConceptoServicioRel()->getCodigoConceptoServicioPk());
//            }
//            if ($arFacturaDetalle['porcentajeBaseIva'] > 0 && $tarIca > 0) {
//                $tarIca = ($tarIca * $arFacturaDetalle['porcentajeBaseIva']) / 1000;//Se valida si el detalle maneja porcentaje de base.
//            }
            $hoja
                ->setCellValue('A' . $i, 'FAC')//Tipo de movimiento de la factura
                ->setCellValue('B' . $i, $arFacturaDetalle['abreviatura'])//Tipo de movimiento de la factura
                ->setCellValue('C' . $i, $arFacturaDetalle['numero'])//Numero de documento de la factura
                ->setCellValue('D' . $i, $strBodega)//Nombre de bodega
//                ->setCellValue('E' . $i, $arFacturaDetalle->getConceptoServicioRel()->getCodigoServicioErp() . "" . $arFacturaDetalle->getCodigoModalidadServicioFk())//Codigo del producto
                ->setCellValue('E' . $i, "")//Codigo del producto
                ->setCellValue('F' . $i, $arFacturaDetalle['nombre'])//Nombre de facturacion del servicio
                ->setCellValue('G' . $i, $arFacturaDetalle['cantidad'])//Cantidad
                ->setCellValue('H' . $i, $arFacturaDetalle['cantidad'])//Cantidad
                ->setCellValue('I' . $i, $arFacturaDetalle['cantidad'])//Cantidad
                ->setCellValue('J' . $i, $arFacturaDetalle['vrPrecio'] )//Valor unitario
                ->setCellValue('K' . $i, $arFacturaDetalle['vrPrecio'])//Valor unitario
                ->setCellValue('L' . $i, $arFacturaDetalle['vrPrecio'])//Valor unitario
                ->setCellValue('M' . $i, $codigoCentroCosto)//Centro de costo contabilidad
                ->setCellValue('N' . $i, $arFacturaDetalle['vrIva'] > 0 ? 1 : 0)//Si el item maneja iva 1 sino 0
                ->setCellValue('O' . $i, str_replace('.', '_', $porIva))//Porcentaje del iva, si el iva tiene punto se pone _ validar
                ->setCellValue('P' . $i, $porIva)//Porcentaje del iva
                ->setCellValue('Q' . $i, $arFacturaDetalle['vrRetencionFuente'] > 0 ? "1" : 0)//Si el encabezado maneja retencion en la fuenta va 1
                ->setCellValue('R' . $i, str_replace('.', '_', $porReteFuente))//Porcentaje de retencion en la fuente
                ->setCellValue('S' . $i, $porReteFuente)//Porcentaje de retencion
                ->setCellValue('T' . $i, $tarIca > 0 ? 1 : 0)//Si tiene ica de retencion ica, PENDIENTE VALIDAR CUANDO LA FACTURA MANEJE VALOR DE RETENCION ICA.
                ->setCellValue('U' . $i, str_replace('.', '_', $tarIca))//Porcentaje de retencion ica
                ->setCellValue('V' . $i, $tarIca)//Porcentaje de retencion ica
                ->setCellValue('W' . $i, 2001)//Siempre ese valor
                ->setCellValue('X' . $i, 1)//Siempre va en 1
                ->setCellValue('Y' . $i, $arFacturaDetalle['fecha']->format('Y-m-d'))//Fecha del movimiento
                ->setCellValue('Z' . $i, $arFacturaDetalle['fecha']->format('Y-m-d'))//Fecha del movimiento
                ->setCellValue('AA' .$i, $arFacturaDetalle['fecha']->format('Y-m-d'))//Fecha del movimiento
                ->setCellValue('AB' .$i, $arFacturaDetalle['fecha']->format('Y-m-d'))//Fecha del movimiento
                ->setCellValue('AC' . $i, 603)//Siempre 603
                ->setCellValue('AD' . $i, 0)//Siempre 0
                ->setCellValue('AE' . $i, $arFacturaDetalle['codigoFacturaDetalleFk'] != null ? $arFacturaDetalle['numero'] : "")//Cuando sea una ND o una NC se deben llenar esos dos campos con el número de factura y el tipo de documento de la factura asociada. En el caso de una factura deben ir en blanco
                ->setCellValue('AF' . $i, $arFacturaDetalle['codigoFacturaDetalleFk'] != null ? $arFacturaDetalle['abreviatura'] : "")//Cuando sea una ND o una NC se deben llenar esos dos campos con el número de factura y el tipo de documento de la factura asociada. En el caso de una factura deben ir en blanco
                ->setCellValue('AG' . $i, $arFacturaDetalle['codigoFacturaDetalleFk'] != null ? $arFacturaDetalle['fecha']->format('Y-m-d') : "")//Si la factura detalle proviene de un pedido capturar la fecha del pedido
                ->setCellValue('AH' . $i, $arFacturaDetalle['codigoFacturaDetalleFk'] != null ? $arFacturaDetalle['pedidoNumero'] : "")//Si la factura detalle proviene de un pedido capturar el numero de pedido
                ->setCellValue('AI' . $i, $arFacturaDetalle['puestoNombre'] . "/" . ( $arFacturaDetalle['codigoFacturaDetalleFk'] != null ? $arFacturaDetalle['modalidadNombre'] : ""));//Nota
            ;
            $i++;
        }
        $hoja->setTitle('Facturas');
        $libro->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=MVTRADE.xls");
        header('Cache-Control: max-age=0');
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
        $writer->save('php://output');
    }
}

