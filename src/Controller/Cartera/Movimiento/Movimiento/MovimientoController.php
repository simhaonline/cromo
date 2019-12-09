<?php

namespace App\Controller\Cartera\Movimiento\Movimiento;

use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Entity\Cartera\CarMovimientoTipo;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Cartera\CarMovimiento;
use App\Entity\Cartera\CarMovimientoDetalle;
use App\Entity\General\GenImpuesto;
use App\Form\Type\Cartera\MovimientoType;
use App\Formato\Cartera\Ingreso;
use App\Formato\Cartera\Movimiento;
use App\Formato\Cartera\Recibo;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class MovimientoController extends BaseController
{
    protected $clase = CarMovimiento::class;
    protected $claseNombre = "CarMovimiento";
    protected $modulo = "Cartera";
    protected $funcion = "Movimiento";
    protected $grupo = "Documento";
    protected $nombre = "Movimiento";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/cartera/movimiento/documento/movimiento/lista/{clase}", name="cartera_movimiento_documento_movimiento_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator, $clase)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoClienteFk', TextType::class, array('required' => false))
            ->add('numero', NumberType::class, array('required' => false))
            ->add('codigoMovimientoPk', TextType::class, array('required' => false))
            ->add('fechaPagoDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaPagoHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('codigoMovimientoTipoFk', EntityType::class, [
                'class' => CarMovimientoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('it')
                        ->orderBy('it.codigoMovimientoTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoContabilizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('btnContabilizar', SubmitType::class, ['label' => 'Contabilizar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData(),
            'codigoMovimientoClase' => $clase
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked() || $form->get('btnContabilizar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(CarMovimiento::class)->lista($raw)->getQuery()->execute(), "Movimientos");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(CarMovimiento::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('cartera_movimiento_movimiento_movimiento_lista'));
            }
            if ($form->get('btnContabilizar')->isClicked()) {
                $arr = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(CarMovimiento::class)->contabilizar($arr);
            }
        }
        $arMovimientos = $paginator->paginate($em->getRepository(CarMovimiento::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('cartera/movimiento/documento/movimiento/lista.html.twig', [
            'arMovimientos' => $arMovimientos,
            'clase' => $clase,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/cartera/movimiento/documento/movimiento/nuevo/{id}/{clase}", name="cartera_movimiento_documento_movimiento_nuevo")
     */
    public function nuevo(Request $request, $id, $clase)
    {
        $em = $this->getDoctrine()->getManager();
        $arMovimiento = new CarMovimiento();
        if ($id != 0) {
            $arMovimiento = $em->getRepository(CarMovimiento::class)->find($id);
            if (!$arMovimiento) {
                return $this->redirect($this->generateUrl('cartera_movimiento_movimiento_movimiento_lista'));
            }
        } else {
            $arMovimiento->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(MovimientoType::class, $arMovimiento);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoCliente = $request->request->get('txtCodigoCliente');
                if ($txtCodigoCliente != '') {
                    $arCliente = $em->getRepository(CarCliente::class)->find($txtCodigoCliente);
                    if ($arCliente) {
                        $arMovimiento->setClienteRel($arCliente);
                        $arMovimiento->setUsuario($this->getUser()->getUsername());
                        $arMovimiento->setMovimientoClaseRel($arMovimiento->getMovimientoTipoRel()->getMovimientoClaseRel());
                        $em->persist($arMovimiento);
                        $em->flush();
                        return $this->redirect($this->generateUrl('cartera_movimiento_documento_movimiento_detalle', ['id' => $arMovimiento->getCodigoMovimientoPk()]));
                    }
                } else {
                    Mensajes::error('Debe seleccionar un cliente');
                }
            }
        }
        return $this->render('cartera/movimiento/documento/movimiento/nuevo.html.twig', [
            'arMovimiento' => $arMovimiento,
            'clase' => $clase,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/cartera/movimiento/documento/movimiento/detalle/{id}", name="cartera_movimiento_documento_movimiento_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arMovimiento = $em->getRepository(CarMovimiento::class)->find($id);
        $form = Estandares::botonera($arMovimiento->getEstadoAutorizado(), $arMovimiento->getEstadoAprobado(), $arMovimiento->getEstadoAnulado());
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDuplicar = ['label' => 'Duplicar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-primary']];
        $arrBtnAdicionar = ['label' => 'Adicionar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arMovimiento->getEstadoAutorizado()) {
            $arrBtnEliminar['disabled'] = true;
            $arrBtnActualizar['disabled'] = true;
            $arrBtnAdicionar['disabled'] = true;
            $arrBtnDuplicar['disabled'] = true;
        }
        $form
            ->add('btnEliminar', SubmitType::class, $arrBtnEliminar)
            ->add('btnDuplicar', SubmitType::class, $arrBtnDuplicar)
            ->add('btnActualizar', SubmitType::class, $arrBtnActualizar)
            ->add('btnAdicionar', SubmitType::class, $arrBtnAdicionar);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->All();
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(CarMovimientoDetalle::class)->actualizar($arrControles, $id);
                $em->getRepository(CarMovimiento::class)->liquidar($id);
                $em->getRepository(CarMovimiento::class)->autorizar($arMovimiento);
                return $this->redirect($this->generateUrl('cartera_movimiento_documento_movimiento_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                if ($arMovimiento->getEstadoAutorizado() == 1 && $arMovimiento->getEstadoAprobado() == 0) {
                    $em->getRepository(CarMovimiento::class)->desAutorizar($arMovimiento);
                    return $this->redirect($this->generateUrl('cartera_movimiento_documento_movimiento_detalle', ['id' => $id]));
                } else {
                    Mensajes::error("El movimiento debe estar autorizado y no puede estar aprobado");
                }
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(CarMovimiento::class)->aprobar($arMovimiento);
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Movimiento();
                $formato->Generar($em, $id);
            }
            if ($form->get('btnAnular')->isClicked()) {
                $respuesta = $em->getRepository(CarMovimiento::class)->anular($arMovimiento);
                if ($respuesta != '') {
                    Mensajes::error($respuesta);
                }
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(CarMovimientoDetalle::class)->actualizar($arrControles, $id);
                $em->getRepository(CarMovimiento::class)->liquidar($id);
                return $this->redirect($this->generateUrl('cartera_movimiento_documento_movimiento_detalle', ['id' => $id]));
            }
            if ($form->get('btnAdicionar')->isClicked()) {
                $em->getRepository(CarMovimientoDetalle::class)->actualizar($arrControles, $id);
                $em->getRepository(CarMovimiento::class)->liquidar($id);
                $arMovimientoDetalle = new CarMovimientoDetalle();
                $arMovimientoDetalle->setMovimientoRel($arMovimiento);
                $arMovimientoDetalle->setClienteRel($arMovimiento->getClienteRel());
                $arMovimientoDetalle->setNaturaleza('D');
                $em->persist($arMovimientoDetalle);
                $em->flush();
                $em->getRepository(CarMovimiento::class)->liquidar($id);
                return $this->redirect($this->generateUrl('cartera_movimiento_documento_movimiento_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(CarMovimientoDetalle::class)->eliminar($arMovimiento, $arrDetallesSeleccionados);
                $em->getRepository(CarMovimiento::class)->liquidar($id);
            }
            if ($form->get('btnDuplicar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(CarMovimientoDetalle::class)->duplicar($arMovimiento, $arrDetallesSeleccionados);
                $em->getRepository(CarMovimiento::class)->liquidar($id);
            }
            return $this->redirect($this->generateUrl('cartera_movimiento_documento_movimiento_detalle', ['id' => $id]));
        }
        $arImpuestosRetencion = $em->getRepository(GenImpuesto::class)->findBy(array('codigoImpuestoTipoFk' => 'R'));
        $arMovimientoDetalles = $paginator->paginate($em->getRepository(CarMovimientoDetalle::class)->lista($arMovimiento->getCodigoMovimientoPk()), $request->query->getInt('page', 1), 500);
        return $this->render('cartera/movimiento/documento/movimiento/detalle.html.twig', [
            'arMovimientoDetalles' => $arMovimientoDetalles,
            'arMovimiento' => $arMovimiento,
            'arImpuestosRetencion' => $arImpuestosRetencion,
            'clase' => array('clase' => 'CarMovimiento', 'codigo' => $id),
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/cartera/movimiento/documento/movimiento/detalle/nuevo/{id}", name="cartera_movimiento_documento_movimiento_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, PaginatorInterface $paginator, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arMovimiento = $em->getRepository(CarMovimiento::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('todosClientes', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroCarCuentaCobrarTodosClientes')))
            ->add('txtCodigoCliente', TextType::class, ['label' => 'Codigo: ', 'required' => false, 'data' => ""])
            ->add('cboCuentaCobrarTipo', EntityType::class, $em->getRepository(CarCuentaCobrarTipo::class)->llenarCombo())
            ->add('txtCodigoCuentaCobrar', TextType::class, ['label' => 'Codigo: ', 'required' => false, 'data' => $session->get('filtroCarCuentaCobrarCodigo')])
            ->add('txtNumero', TextType::class, ['label' => 'Numero: ', 'required' => false, 'data' => $session->get('filtroCarCuentaCobrarNumero')])
            ->add('txtSoporte', TextType::class, ['label' => 'Numero: ', 'required' => false, 'data' => $session->get('filtroCarCuentaCobrarSoporte')])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroCarFechaDesde') ? date_create($session->get('filtroCarFechaDesde')) : null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroCarFechaHasta') ? date_create($session->get('filtroCarFechaHasta')) : null])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('btnGuardarNuevo', SubmitType::class, ['label' => 'Guardar y nuevo', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $arCuentaCobrarTipo = $form->get('cboCuentaCobrarTipo')->getData();
                if ($arCuentaCobrarTipo) {
                    $session->set('filtroCarCuentaCobrarTipo', $arCuentaCobrarTipo->getCodigoCuentaCobrarTipoPk());
                } else {
                    $session->set('filtroCarCuentaCobrarTipo', null);
                }
                $session->set('filtroCarCodigoCliente', $form->get('txtCodigoCliente')->getData());
                $session->set('filtroCarCuentaCobrarCodigo', $form->get('txtCodigoCuentaCobrar')->getData());
                $session->set('filtroCarCuentaCobrarNumero', $form->get('txtNumero')->getData());
                $session->set('filtroCarCuentaCobrarSoporte', $form->get('txtSoporte')->getData());
                $session->set('filtroCarFechaDesde', $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null);
                $session->set('filtroCarFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null);
                $session->set('filtroCarCuentaCobrarTodosClientes', $form->get('todosClientes')->getData());
            }
            if ($form->get('btnGuardar')->isClicked() || $form->get('btnGuardarNuevo')->isClicked()) {
                $arrCuentasCobrar = $request->request->get('ChkSeleccionar');
                if ($arrCuentasCobrar) {
                    foreach ($arrCuentasCobrar as $codigoCuentaCobrar) {
                        $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($codigoCuentaCobrar);
                        $arMovimientoDetalle = new CarMovimientoDetalle();
                        $arMovimientoDetalle->setMovimientoRel($arMovimiento);
                        $arMovimientoDetalle->setNumero($arCuentaCobrar->getNumeroDocumento());
                        $arMovimientoDetalle->setCuentaCobrarRel($arCuentaCobrar);
                        $arMovimientoDetalle->setCuentaCobrarTipoRel($arCuentaCobrar->getCuentaCobrarTipoRel());
                        $arMovimientoDetalle->setVrPago($arCuentaCobrar->getVrSaldo());
                        $arMovimientoDetalle->setUsuario($this->getUser()->getUserName());
                        $arMovimientoDetalle->setCuentaRel($em->getReference(FinCuenta::class, $arCuentaCobrar->getCuentaCobrarTipoRel()->getCodigoCuentaClienteFk()));
                        $arMovimientoDetalle->setClienteRel($arCuentaCobrar->getClienteRel());
                        if ($arCuentaCobrar->getOperacion() == 1) {
                            $arMovimientoDetalle->setNaturaleza('C');
                        } else {
                            $arMovimientoDetalle->setNaturaleza('D');
                        }
                        $arMovimientoDetalle->setCodigoImpuestoRetencionFk('R00');
                        $em->persist($arMovimientoDetalle);
                    }
                    $em->flush();
                    $em->getRepository(CarMovimiento::class)->liquidar($id);
                    if ($form->get('btnGuardar')->isClicked()) {
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    }
                    if ($form->get('btnGuardarNuevo')->isClicked()) {
                        echo "<script languaje='javascript' type='text/javascript'>window.opener.location.reload();</script>";
                    }
                }
            }
        }
        $arCuentasCobrar = $paginator->paginate($em->getRepository(CarCuentaCobrar::class)->cuentasCobrarDetalleNuevo($arMovimiento->getCodigoClienteFk()), $request->query->getInt('page', 1), 500);
        return $this->render('cartera/movimiento/documento/movimiento/detalleNuevo.html.twig', [
            'arCuentasCobrar' => $arCuentasCobrar,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoCliente' => $form->get('codigoClienteFk')->getData(),
            'numero' => $form->get('numero')->getData(),
            'codigoMovimiento' => $form->get('codigoMovimientoPk')->getData(),
            'fechaDesde' => $form->get('fechaPagoDesde')->getData() ? $form->get('fechaPagoDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaPagoHasta')->getData() ? $form->get('fechaPagoHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
            'estadoContabilizado' => $form->get('estadoContabilizado')->getData(),
        ];

        $codigoMovimientoTipo = $form->get('codigoMovimientoTipoFk')->getData();

        if (is_object($codigoMovimientoTipo)) {
            $filtro['codigoMovimientoTipo'] = $codigoMovimientoTipo->getCodigoMovimientoTipoPk();
        } else {
            $filtro['codigoMovimientoTipo'] = $codigoMovimientoTipo;
        }

        return $filtro;

    }
}
