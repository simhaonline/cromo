<?php

namespace App\Controller\Cartera\Movimiento\Recibo;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarDescuentoConcepto;
use App\Entity\Cartera\CarIngresoConcepto;
use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboDetalle;
use App\Entity\Financiero\FinTercero;
use App\Entity\General\GenAsesor;
use App\Entity\Transporte\TteGuiaTipo;
use App\Form\Type\Cartera\ReciboType;
use App\Formato\Cartera\Recibo;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReciboController extends AbstractController
{
    protected $clase = CarRecibo::class;
    protected $claseNombre = "CarRecibo";
    protected $modulo = "Cartera";
    protected $funcion = "Movimiento";
    protected $grupo = "Recibo";
    protected $nombre = "Recibo";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
         * @Route("/cartera/movimiento/recibo/recibo/lista", name="cartera_movimiento_recibo_recibo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoClienteFk', TextType::class, array('required' => false))
            ->add('numero', TextType::class, array('required' => false))
            ->add('codigoReciboPk', TextType::class, array('required' => false))
            ->add('codigoReciboTipoFk', EntityType::class, [
                'class' => TteGuiaTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('gt')
                        ->orderBy('gt.codigoGuiaTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('codigoAsesorFk', EntityType::class, [
                'class' => GenAsesor::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.codigoAsesorPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('fechaPagoDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaPagoHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
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
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(CarRecibo::class)->lista($raw)->getQuery()->getResult(), "Recibos");
            }

            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(CarRecibo::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_lista'));
            }
        }
        $arResibos = $paginator->paginate($em->getRepository(CarRecibo::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('cartera/movimiento/recibo/recibo/lista.html.twig', [
            'arResibos' => $arResibos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/cartera/movimiento/recibo/recibo/nuevo/{id}", name="cartera_movimiento_recibo_recibo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecibo = new CarRecibo();
        if ($id != '0') {
            $arRecibo = $em->getRepository(CarRecibo::class)->find($id);
            if (!$arRecibo) {
                return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_lista'));
            }
        } else {
            $arRecibo->setFechaPago(new \DateTime('now'));
            $arRecibo->setUsuario($this->getUser()->getUserName());
        }
        $form = $this->createForm(ReciboType::class, $arRecibo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoCliente = $request->request->get('txtCodigoCliente');
                if ($txtCodigoCliente != '') {
                    $arCliente = $em->getRepository(CarCliente::class)->find($txtCodigoCliente);
                    if ($arCliente) {
                        $txtCodigoTercero = $request->request->get('txtCodigoTercero');
                        if ($txtCodigoTercero != '') {
                            $arTercero = $em->getRepository(FinTercero::class)->find($txtCodigoTercero);
                            $arRecibo->setTerceroRel($arTercero);
                        }
                        if ($id == 0) {
                            $arRecibo->setFecha(new \DateTime('now'));
                        }
                        $arRecibo->setClienteRel($arCliente);
                        $em->persist($arRecibo);
                        $em->flush();
                        return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_detalle', ['id' => $arRecibo->getCodigoReciboPk()]));
                    }
                }

            }
        }
        return $this->render('cartera/movimiento/recibo/recibo/nuevo.html.twig', [
            'arRecibo' => $arRecibo,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/cartera/movimiento/recibo/recibo/detalle/{id}", name="cartera_movimiento_recibo_recibo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecibo = $em->getRepository(CarRecibo::class)->find($id);
        $form = Estandares::botonera($arRecibo->getEstadoAutorizado(), $arRecibo->getEstadoAprobado(), $arRecibo->getEstadoAnulado());
        $arrBtnEliminarDetalle = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnActualizarDetalle = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arRecibo->getEstadoAutorizado()) {
            $arrBtnEliminarDetalle['disabled'] = true;
            $arrBtnActualizarDetalle['disabled'] = true;
        }
        $form->add('btnEliminarDetalle', SubmitType::class, $arrBtnEliminarDetalle);
        $form->add('btnActualizarDetalle', SubmitType::class, $arrBtnActualizarDetalle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnEliminarDetalle')->isClicked()) {
                if ($arRecibo->getEstadoAutorizado() == 0) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    $em->getRepository(CarReciboDetalle::class)->eliminarSeleccionados($arrSeleccionados);
                    $em->getRepository(CarReciboDetalle::class)->liquidar($id);
                } else {
                    Mensajes::error('No se puede eliminar el registro, esta autorizado');
                }
                return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_detalle', array('id' => $id)));
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(CarRecibo::class)->autorizar($arRecibo);
                return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                if ($arRecibo->getEstadoAutorizado() == 1 && $arRecibo->getEstadoImpreso() == 0) {
                    $em->getRepository(CarRecibo::class)->desAutorizar($arRecibo);
                    return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_detalle', ['id' => $id]));
                } else {
                    Mensajes::error("El recibo debe estar autorizado y no puede estar impreso");
                }
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(CarRecibo::class)->aprobar($arRecibo);
                return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_detalle', ['id' => $id]));
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Recibo();
                $formato->Generar($em, $id);
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository(CarRecibo::class)->anular($arRecibo);
                return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_detalle', ['id' => $id]));
            }
            if ($form->get('btnActualizarDetalle')->isClicked()) {
                if ($arRecibo->getEstadoAutorizado() == 0) {
                    $arrControles = $request->request->All();
                    $em->getRepository(CarReciboDetalle::class)->actualizarDetalle($arrControles, $arRecibo);
                    return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_detalle', ['id' => $id]));
                } else {
                    Mensajes::error("No se puede actualizar, el registro se encuentra autorizado");
                }
            }
        }

        $arDescuentosConceptos = $em->getRepository(CarDescuentoConcepto::class)->findAll();
        $arIngresosConceptos = $em->getRepository(CarIngresoConcepto::class)->findAll();
        $arAsesores = $em->getRepository(GenAsesor::class)->findAll();
        $arReciboDetalle = $em->getRepository(CarReciboDetalle::class)->findBy(array('codigoReciboFk' => $id));
        return $this->render('cartera/movimiento/recibo/recibo/detalle.html.twig', array(
            'arRecibo' => $arRecibo,
            'arReciboDetalle' => $arReciboDetalle,
            'arDescuentoConceptos' => $arDescuentosConceptos,
            'arIngresoConceptos' => $arIngresosConceptos,
            'arAsesores' => $arAsesores,
            'clase' => array('clase' => 'CarRecibo', 'codigo' => $id),
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * * @Route("/cartera/movimiento/recibo/recibo/detalle/nuevo/{id}", name="cartera_movimiento_recibo_recibo_detalle_nuevo")
     * @throws \Doctrine\ORM\ORMException
     */
    public function detalleNuevo(Request $request, $id, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecibo = $em->getRepository(CarRecibo::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $arrControles = $request->request->All();
                if ($arrSeleccionados) {
                    foreach ($arrSeleccionados AS $codigoCuentaCobrar) {
                        $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($codigoCuentaCobrar);
                        //Lo quito mario porque no sabia que era
                        //$vrPago = $em->getRepository(CarReciboDetalle::class)->vrPagoRecibo($codigoCuentaCobrar, $id);
                        //$saldo = $arrControles['TxtSaldo' . $codigoCuentaCobrar] - $vrPago;
                        $retencionFuente = $arrControles['TxtRetencionFuente' . $codigoCuentaCobrar];
                        $retencionIca = $arrControles['TxtRetencionIca' . $codigoCuentaCobrar];
                        $saldo = $arrControles['TxtSaldo' . $codigoCuentaCobrar];

                        $arReciboDetalle = new CarReciboDetalle();
                        $arReciboDetalle->setReciboRel($arRecibo);
                        $arReciboDetalle->setCuentaCobrarRel($arCuentaCobrar);
                        $arReciboDetalle->setVrRetencionFuente($retencionFuente);
                        $arReciboDetalle->setVrRetencionIca($retencionIca);
                        $saldo -= $retencionFuente + $retencionIca;
                        $pagoAfectar = $arrControles['TxtSaldo' . $codigoCuentaCobrar];
                        $arReciboDetalle->setVrPago($saldo);
                        $arReciboDetalle->setVrPagoAfectar($pagoAfectar);
                        $arReciboDetalle->setNumeroFactura($arCuentaCobrar->getNumeroDocumento());
                        $arReciboDetalle->setCuentaCobrarTipoRel($arCuentaCobrar->getCuentaCobrarTipoRel());
                        $arReciboDetalle->setOperacion(1);
                        $arReciboDetalle->setAsesorRel($arRecibo->getAsesorRel());
                        $em->persist($arReciboDetalle);
                    }
                    $em->flush();
                }
                $em->getRepository(CarReciboDetalle::class)->liquidar($id);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arCuentasCobrar = $em->getRepository(CarCuentaCobrar::class)->cuentasCobrar($arRecibo->getCodigoClienteFk());
        $arCuentasCobrar = $paginator->paginate($arCuentasCobrar, $request->query->get('page', 1), 50);
        return $this->render('cartera/movimiento/recibo/recibo/detalleNuevo.html.twig', array(
            'arCuentasCobrar' => $arCuentasCobrar,
            'arRecibo' => $arRecibo,
            'form' => $form->createView()));
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * * @Route("/cartera/movimiento/recibo/recibo/detalle/aplicar/{id}", name="cartera_movimiento_recibo_recibo_detalle_aplicar")
     * @throws \Doctrine\ORM\ORMException
     */
    public function detalleNuevoAplicar(Request $request, PaginatorInterface $paginator, $id)
    {
        {
            $em = $this->getDoctrine()->getManager();
            $arReciboDetalle = $em->getRepository(CarReciboDetalle::class)->find($id);
            $form = $this->createFormBuilder()
                ->getForm();
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    if ($request->request->get('OpAplicar')) {
                        set_time_limit(0);
                        ini_set("memory_limit", -1);
                        $codigoCuentaCobrar = $request->request->get('OpAplicar');
                        $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($codigoCuentaCobrar);
                        $arReciboDetalle->setCuentaCobrarAplicacionRel($arCuentaCobrar);
                        $arReciboDetalle->setNumeroDocumentoAplicacion($arCuentaCobrar->getNumeroDocumento());
                        $arReciboDetalle->setCuentaCobrarAplicacionTipoRel($arCuentaCobrar->getCuentaCobrarTipoRel());
                        $arReciboDetalle->setOperacion(1);
                        $arReciboDetalle->setVrPago($arCuentaCobrar->getVrSaldo());
                        $arReciboDetalle->setVrPagoAfectar($arCuentaCobrar->getVrSaldo());
                        $em->persist($arReciboDetalle);
                        $em->flush();
                        $em->getRepository(CarReciboDetalle::class)->liquidar($arReciboDetalle->getCodigoReciboFk());
                    }
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
            $arCuentasCobrar = $em->getRepository(CarCuentaCobrar::class)->cuentasCobrarAplicarRecibo($arReciboDetalle->getReciboRel()->getCodigoClienteFk());
            $arCuentasCobrar = $paginator->paginate($arCuentasCobrar, $request->query->get('page', 1), 50);
            return $this->render('cartera/movimiento/recibo/recibo/detalleaAplicar.html.twig', array(
                'arCuentasCobrar' => $arCuentasCobrar,
                'form' => $form->createView()));
        }
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoCliente' => $form->get('codigoClienteFk')->getData(),
            'numero' => $form->get('numero')->getData(),
            'codigoRecibo' => $form->get('codigoReciboPk')->getData(),
            'fechaPagoDesde' => $form->get('fechaPagoDesde')->getData() ? $form->get('fechaPagoDesde')->getData()->format('Y-m-d') : null,
            'fechaPagoHasta' => $form->get('fechaPagoHasta')->getData() ? $form->get('fechaPagoHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $arReciboTipo = $form->get('codigoReciboTipoFk')->getData();
        $arAsesor = $form->get('codigoAsesorFk')->getData();

        if (is_object($arReciboTipo)) {
            $filtro['codigoReciboTipo'] = $arReciboTipo->getCodigoIncidenteTipoPk();
        } else {
            $filtro['codigoReciboTipo'] = $arReciboTipo;
        }

        if (is_object($arAsesor)) {
            $filtro['codigoAsesor'] = $arAsesor->getCodigoAsesorPk();
        } else {
            $filtro['codigoAsesor'] = $arAsesor;
        }

        return $filtro;

    }
}

