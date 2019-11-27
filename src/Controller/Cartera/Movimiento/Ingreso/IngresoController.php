<?php

namespace App\Controller\Cartera\Movimiento\Ingreso;

use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Entity\Cartera\CarIngresoTipo;
use App\Entity\Financiero\FinCuenta;
use App\Entity\General\GenBanco;
use App\Entity\General\GenConfiguracion;
use App\Entity\Cartera\TesCuentaPagar;
use App\Entity\Cartera\TesCuentaPagarTipo;
use App\Entity\Cartera\CarIngreso;
use App\Entity\Cartera\CarIngresoDetalle;
use App\Entity\Cartera\TesTercero;
use App\Entity\General\GenImpuesto;
use App\Form\Type\Cartera\IngresoType;
use App\Formato\Cartera\Ingreso;
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

class IngresoController extends BaseController
{
    protected $clase = CarIngreso::class;
    protected $claseNombre = "CarIngreso";
    protected $modulo = "Cartera";
    protected $funcion = "Movimiento";
    protected $grupo = "Ingreso";
    protected $nombre = "Ingreso";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/cartera/movimiento/ingreso/ingreso/lista", name="cartera_movimiento_ingreso_ingreso_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoClienteFk', TextType::class, array('required' => false))
            ->add('numero', NumberType::class, array('required' => false))
            ->add('codigoIngresoPk', TextType::class, array('required' => false))
            ->add('fechaPagoDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaPagoHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('codigoIngresoTipoFk', EntityType::class, [
                'class' => CarIngresoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('it')
                        ->orderBy('it.codigoIngresoTipoPk', 'ASC');
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
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked() || $form->get('btnContabilizar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(CarIngreso::class)->lista($raw)->getQuery()->execute(), "Ingresos");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(CarIngreso::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('cartera_movimiento_ingreso_ingreso_lista'));
            }
            if ($form->get('btnContabilizar')->isClicked()) {
                $arr = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(CarIngreso::class)->contabilizar($arr);
            }
        }
        $arIngresos = $paginator->paginate($em->getRepository(CarIngreso::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('cartera/movimiento/ingreso/ingreso/lista.html.twig', [
            'arIngresos' => $arIngresos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/cartera/movimiento/ingreso/ingreso/nuevo/{id}", name="cartera_movimiento_ingreso_ingreso_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arIngreso = new CarIngreso();
        if ($id != 0) {
            $arIngreso = $em->getRepository(CarIngreso::class)->find($id);
            if (!$arIngreso) {
                return $this->redirect($this->generateUrl('cartera_movimiento_ingreso_ingreso_lista'));
            }
        } else {
            $arIngreso->setFechaPago(new \DateTime('now'));
        }
        $form = $this->createForm(IngresoType::class, $arIngreso);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoCliente = $request->request->get('txtCodigoCliente');
                if ($txtCodigoCliente != '') {
                    $arCliente = $em->getRepository(CarCliente::class)->find($txtCodigoCliente);
                    if ($arCliente) {
                        $arIngreso->setClienteRel($arCliente);
                        if ($id == 0) {
                            $arIngreso->setFecha(new \DateTime('now'));
                        }
                        $arIngreso->setUsuario($this->getUser()->getUsername());
                        $em->persist($arIngreso);
                        $em->flush();
                        return $this->redirect($this->generateUrl('cartera_movimiento_ingreso_ingreso_detalle', ['id' => $arIngreso->getCodigoIngresoPk()]));
                    }
                } else {
                    Mensajes::error('Debe seleccionar un cliente');
                }
            }
        }
        return $this->render('cartera/movimiento/ingreso/ingreso/nuevo.html.twig', [
            'arIngreso' => $arIngreso,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/cartera/movimiento/ingreso/ingreso/detalle/{id}", name="cartera_movimiento_ingreso_ingreso_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arIngreso = $em->getRepository(CarIngreso::class)->find($id);
        $form = Estandares::botonera($arIngreso->getEstadoAutorizado(), $arIngreso->getEstadoAprobado(), $arIngreso->getEstadoAnulado());
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDuplicar = ['label' => 'Duplicar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-primary']];
        $arrBtnAdicionar = ['label' => 'Adicionar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arIngreso->getEstadoAutorizado()) {
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
                $em->getRepository(CarIngresoDetalle::class)->actualizar($arrControles, $id);
                $em->getRepository(CarIngreso::class)->liquidar($id);
                $em->getRepository(CarIngreso::class)->autorizar($arIngreso);
                return $this->redirect($this->generateUrl('cartera_movimiento_ingreso_ingreso_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                if ($arIngreso->getEstadoAutorizado() == 1 && $arIngreso->getEstadoAprobado() == 0) {
                    $em->getRepository(CarIngreso::class)->desAutorizar($arIngreso);
                    return $this->redirect($this->generateUrl('cartera_movimiento_ingreso_ingreso_detalle', ['id' => $id]));
                } else {
                    Mensajes::error("El ingreso debe estar autorizado y no puede estar aprobado");
                }
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(CarIngreso::class)->aprobar($arIngreso);
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Ingreso();
                $formato->Generar($em, $id);

            }
            if ($form->get('btnAnular')->isClicked()) {
                $respuesta = $em->getRepository(CarIngreso::class)->anular($arIngreso);
                if ($respuesta != '') {
                    Mensajes::error($respuesta);
                }
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(CarIngresoDetalle::class)->actualizar($arrControles, $id);
                $em->getRepository(CarIngreso::class)->liquidar($id);
                return $this->redirect($this->generateUrl('cartera_movimiento_ingreso_ingreso_detalle', ['id' => $id]));
            }
            if ($form->get('btnAdicionar')->isClicked()) {
                $em->getRepository(CarIngresoDetalle::class)->actualizar($arrControles, $id);
                $em->getRepository(CarIngreso::class)->liquidar($id);
                $arIngresoDetalle = new CarIngresoDetalle();
                $arIngresoDetalle->setIngresoRel($arIngreso);
                $arIngresoDetalle->setClienteRel($arIngreso->getClienteRel());
                $arIngresoDetalle->setNaturaleza('D');
                $em->persist($arIngresoDetalle);
                $em->flush();
                $em->getRepository(CarIngreso::class)->liquidar($id);
                return $this->redirect($this->generateUrl('cartera_movimiento_ingreso_ingreso_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(CarIngresoDetalle::class)->eliminar($arIngreso, $arrDetallesSeleccionados);
                $em->getRepository(CarIngreso::class)->liquidar($id);
            }
            if ($form->get('btnDuplicar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(CarIngresoDetalle::class)->duplicar($arIngreso, $arrDetallesSeleccionados);
                $em->getRepository(CarIngreso::class)->liquidar($id);
            }
            return $this->redirect($this->generateUrl('cartera_movimiento_ingreso_ingreso_detalle', ['id' => $id]));
        }
        $arImpuestosRetencion = $em->getRepository(GenImpuesto::class)->findBy(array('codigoImpuestoTipoFk' => 'R'));
        $arIngresoDetalles = $paginator->paginate($em->getRepository(CarIngresoDetalle::class)->lista($arIngreso->getCodigoIngresoPk()), $request->query->getInt('page', 1), 500);
        return $this->render('cartera/movimiento/ingreso/ingreso/detalle.html.twig', [
            'arIngresoDetalles' => $arIngresoDetalles,
            'arIngreso' => $arIngreso,
            'arImpuestosRetencion' => $arImpuestosRetencion,
            'clase' => array('clase' => 'CarIngreso', 'codigo' => $id),
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/cartera/movimiento/ingreso/ingreso/detalle/nuevo/{id}", name="cartera_movimiento_ingreso_ingreso_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, PaginatorInterface $paginator, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arIngreso = $em->getRepository(CarIngreso::class)->find($id);
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
                        $arIngresoDetalle = new CarIngresoDetalle();
                        $arIngresoDetalle->setIngresoRel($arIngreso);
                        $arIngresoDetalle->setNumero($arCuentaCobrar->getNumeroDocumento());
                        $arIngresoDetalle->setCuentaCobrarRel($arCuentaCobrar);
                        $arIngresoDetalle->setCuentaCobrarTipoRel($arCuentaCobrar->getCuentaCobrarTipoRel());
                        $arIngresoDetalle->setVrPago($arCuentaCobrar->getVrSaldo());
                        $arIngresoDetalle->setUsuario($this->getUser()->getUserName());
                        $arIngresoDetalle->setCuentaRel($em->getReference(FinCuenta::class, $arCuentaCobrar->getCuentaCobrarTipoRel()->getCodigoCuentaClienteFk()));
                        $arIngresoDetalle->setClienteRel($arCuentaCobrar->getClienteRel());
                        if ($arCuentaCobrar->getOperacion() == 1) {
                            $arIngresoDetalle->setNaturaleza('C');
                        } else {
                            $arIngresoDetalle->setNaturaleza('D');
                        }
                        $arIngresoDetalle->setCodigoImpuestoRetencionFk('R00');
                        $em->persist($arIngresoDetalle);
                    }
                    $em->flush();
                    $em->getRepository(CarIngreso::class)->liquidar($id);
                    if ($form->get('btnGuardar')->isClicked()) {
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    }
                    if ($form->get('btnGuardarNuevo')->isClicked()) {
                        echo "<script languaje='javascript' type='text/javascript'>window.opener.location.reload();</script>";
                    }
                }
            }
        }
        $arCuentasCobrar = $paginator->paginate($em->getRepository(CarCuentaCobrar::class)->cuentasCobrarDetalleNuevo($arIngreso->getCodigoClienteFk()), $request->query->getInt('page', 1), 500);
        return $this->render('cartera/movimiento/ingreso/ingreso/detalleNuevo.html.twig', [
            'arCuentasCobrar' => $arCuentasCobrar,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoCliente' => $form->get('codigoClienteFk')->getData(),
            'numero' => $form->get('numero')->getData(),
            'codigoIngreso' => $form->get('codigoIngresoPk')->getData(),
            'fechaDesde' => $form->get('fechaPagoDesde')->getData() ? $form->get('fechaPagoDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaPagoHasta')->getData() ? $form->get('fechaPagoHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
            'estadoContabilizado' => $form->get('estadoContabilizado')->getData(),
        ];

        $codigoIngresoTipo = $form->get('codigoIngresoTipoFk')->getData();

        if (is_object($codigoIngresoTipo)) {
            $filtro['codigoIngresoTipo'] = $codigoIngresoTipo->getCodigoIngresoTipoPk();
        } else {
            $filtro['codigoIngresoTipo'] = $codigoIngresoTipo;
        }

        return $filtro;

    }
}
