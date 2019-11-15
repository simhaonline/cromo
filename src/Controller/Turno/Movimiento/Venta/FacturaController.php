<?php

namespace App\Controller\Turno\Movimiento\Venta;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenImpuesto;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurConfiguracion;
use App\Entity\Turno\TurFactura;
use App\Entity\Turno\TurFacturaDetalle;
use App\Entity\Turno\TurFacturaTipo;
use App\Entity\Turno\TurItem;
use App\Entity\Turno\TurPedidoDetalle;
use App\Form\Type\Turno\FacturaType;
use App\Formato\Turno\Factura1;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

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
            ->add('btnFiltro', SubmitType::class, array('label' => 'Filtrar'))
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
            if ($form->get('btnFiltro')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(TurFactura::class)->lista($raw), "Factura");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TurFactura::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('turno_movimiento_venta_factura_lista'));
            }
        }

        $arFacturas = $paginator->paginate($em->getRepository(TurFactura::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('turno/movimiento/venta/factura/lista.html.twig', [
            'arFacturas' => $arFacturas,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/turno/movimiento/comercial/factura/nuevo/{id}", name="turno_movimiento_venta_factura_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arFactura = new TurFactura();
        $objFunciones = new FuncionesController();
        if ($id != 0) {
            $arFactura = $em->getRepository(TurFactura::class)->find($id);
        } else {
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
                        if ($id == 0) {
                            $arFactura->setUsuario($this->getUser()->getUserName());
                        }
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
                if ($arConfiguracion->getCodigoFormatoFactura() == 1) {
                    $formatoFactura = new Factura1();
                    $formatoFactura->Generar($em, $id, $arFactura->getNumero(), $this->getUser());
                }
            }
        }
        $arImpuestosIva = $em->getRepository(GenImpuesto::class)->findBy(array('codigoImpuestoTipoFk' => 'I'));
        $arImpuestosRetencion = $em->getRepository(GenImpuesto::class)->findBy(array('codigoImpuestoTipoFk' => 'R'));
        $arFacturaDetalles = $paginator->paginate($em->getRepository(TurFacturaDetalle::class)->lista($id), $request->query->getInt('page', 1), 50);
        return $this->render('turno/movimiento/venta/factura/detalle.html.twig', [
            'form' => $form->createView(),
            'arFacturaDetalles' => $arFacturaDetalles,
            'arFactura' => $arFactura,
            'arImpuestosIva' => $arImpuestosIva,
            'arImpuestosRetencion' => $arImpuestosRetencion
        ]);
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
        $form = $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        $raw['filtros'] = ['codigoCliente' => $arFactura->getCodigoClienteFk()];
        if ($form->isSubmitted() && $form->isValid()) {
//            if ($form->get('btnFiltrar')->isClicked()) {
//                $arCuentaCobrarTipo = $form->get('cboCuentaCobrarTipo')->getData();
//                if ($arCuentaCobrarTipo) {
//                    $session->set('filtroCarCuentaCobrarTipo', $arCuentaCobrarTipo->getCodigoCuentaCobrarTipoPk());
//                } else {
//                    $session->set('filtroCarCuentaCobrarTipo', null);
//                }
//                $session->set('filtroCarCodigoCliente', $form->get('txtCodigoCliente')->getData());
//                $session->set('filtroCarCuentaCobrarCodigo', $form->get('txtCodigoCuentaCobrar')->getData());
//                $session->set('filtroCarCuentaCobrarNumero', $form->get('txtNumero')->getData());
//                $session->set('filtroCarCuentaCobrarSoporte', $form->get('txtSoporte')->getData());
//                $session->set('filtroCarFechaDesde', $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null);
//                $session->set('filtroCarFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null);
//                $session->set('filtroCarCuentaCobrarTodosClientes', $form->get('todosClientes')->getData());
//            }
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
                        $arFacturaDetalle->setPorcentajeBaseIva($arPedidoDetalle->getItemRel()->getImpuestoIvaVentaRel()->getBase());
                        $arFacturaDetalle->setConceptoRel($arPedidoDetalle->getConceptoRel());
                        $arFacturaDetalle->setPuestoRel($arPedidoDetalle->getPuestoRel());
                        $arFacturaDetalle->setCantidad($arPedidoDetalle->getCantidad());
                        $arFacturaDetalle->setVrPrecio($arPedidoDetalle->getVrPendiente());
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
        $arPedidoDetalles = $paginator->paginate($em->getRepository(TurPedidoDetalle::class)->pendienteFacturar($raw), $request->query->getInt('page', 1), 500);
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

}

