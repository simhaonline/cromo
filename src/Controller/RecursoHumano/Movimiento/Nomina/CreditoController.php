<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuCreditoPagoTipo;
use App\Entity\RecursoHumano\RhuCreditoTipo;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Form\Type\RecursoHumano\CreditoPagoType;
use App\Form\Type\RecursoHumano\CreditoType;
use App\Formato\RecursoHumano\Credito;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CreditoController extends MaestroController
{


    public $tipo = "Movimiento";
    public $modelo = "RhuCredito";

    protected $clase = RhuCredito::class;
    protected $claseFormulario = CreditoType::class;
    protected $claseNombre = "RhuCredito";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "Credito";

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/credito/lista", name="recursohumano_movimiento_nomina_credito_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $raw = [
            'filtros'=> $session->get('filtroRhuCredito')
        ];
        $form = $this->createFormBuilder()
            ->add('codigoCreditoTipoFk', EntityType::class, [
                'class' => RhuCreditoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->orderBy('ct.codigoCreditoTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS',
                'attr' => ['class' => 'form-control to-select-2'],
                'data'=>  $raw['filtros']['creditoTipo'] ? $em->getReference(RhuCreditoTipo::class, $raw['filtros']['creditoTipo']) : null

            ])
            ->add('codigoCreditoPagoTipoFk', EntityType::class, [
                'class' => RhuCreditoPagoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pt')
                        ->orderBy('pt.codigoCreditoPagoTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS',
                'attr' => ['class' => 'form-control to-select-2'],
                'data'=>  $raw['filtros']['creditoPagoTipo'] ? $em->getReference(RhuCreditoPagoTipo::class, $raw['filtros']['creditoPagoTipo']) : null

            ])
            ->add('codigoCreditoPk', TextType::class, array('required' => false,  'data'=>$raw['filtros']['codigoCredito'] ))
            ->add('codigoEmpleadoFk', TextType::class, ['required' => false,  'data'=>$raw['filtros']['codigoEmpleado'] ])
            ->add('estadoPagado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoPagado']])
            ->add('estadoSuspendido', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoSuspendido']])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data'=>$raw['filtros']['fechaDesde']?date_create($raw['filtros']['fechaDesde']):null ])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data'=>$raw['filtros']['fechaHasta']?date_create($raw['filtros']['fechaHasta']):null ])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $raw['limiteRegistros'] = $form->get('limiteRegistros')->getData();
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuCredito::class)->lista($raw), "Creditos");
            }
        }
        $arCreditos = $paginator->paginate($em->getRepository(RhuCredito::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/nomina/credito/lista.html.twig', [
            'arCreditos' => $arCreditos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/credito/nuevo/{id}", name="recursohumano_movimiento_nomina_credito_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCredito = new RhuCredito();
        if ($id != 0) {
            $arCredito = $em->getRepository($this->clase)->find($id);
        } else {
            $arCredito->setFechaCredito(new \DateTime('now'));
            $arCredito->setFechaInicio(new \DateTime('now'));
            $arCredito->setFechaFinalizacion(new \DateTime('now'));
        }
        $form = $this->createForm(CreditoType::class, $arCredito);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($arCredito->getCodigoEmpleadoFk());
                if ($arEmpleado) {
                    $arContrato = null;
                    if ($arEmpleado->getCodigoContratoFk()) {
                        $arContrato = $em->getRepository(RhuContrato::class)->find($arEmpleado->getCodigoContratoFk());
                    } elseif ($arEmpleado->getCodigoContratoUltimoFk()) {
                        $arContrato = $em->getRepository(RhuContrato::class)->find($arEmpleado->getCodigoContratoUltimoFk());
                    }
                    if ($arContrato != null) {
                        if ($id == 0) {
                            $arCredito->setFecha(new \DateTime('now'));
                        }
                        $arCredito->setGrupoRel($arContrato->getGrupoRel());
                        $arCredito->setEmpleadoRel($arEmpleado);
                        $arCredito->setContratoRel($arContrato);
                        $arCredito->setUsuario($this->getUser()->getUsername());
                        $arCredito->setVrSaldo($arCredito->getVrCredito() - $arCredito->getVrAbonos());
                        $em->persist($arCredito);
                        $em->flush();
                        return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_credito_detalle', ['id' => $arCredito->getCodigoCreditoPk()]));
                    } else {
                        Mensajes::error('El empleado no tiene contratos en el sistema');
                    }
                } else {
                    Mensajes::error('No se ha encontrado un empleado con el codigo ingresado');
                }
            }
        }
        return $this->render('recursohumano/movimiento/nomina/credito/nuevo.html.twig', [
            'form' => $form->createView(),
            'arCredito' => $arCredito
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/credito/detalle/{id}", name="recursohumano_movimiento_nomina_credito_detalle")
     */
    public function detalle(Request $request, $id, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository($this->clase)->find($id);
        $form = Estandares::botonera($arRegistro->getEstadoAutorizado(), $arRegistro->getEstadoAprobado(), $arRegistro->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $objFormato = new Credito();
                $objFormato->Generar($em, $id);
            }
        }
        $arCreditoPagos = $paginator->paginate($em->getRepository(RhuCreditoPago::class)->listaPorCredito($id), $request->query->getInt('PageCreditoPago', 1), 30,
            array(
                'pageParameterName' => 'PageCreditoPago',
                'sortFieldParameterName' => 'sortPageCreditoPago',
                'sortDirectionParameterName' => 'directionPageCreditoPago',
            ));
        return $this->render('recursohumano/movimiento/nomina/credito/detalle.html.twig', [
            'arRegistro' => $arRegistro,
            'arCreditoPagos' => $arCreditoPagos,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/credito/detalle/nuevo/{id}", name="recursohumano_movimiento_nomina_credito_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCredito = $em->getRepository(RhuCredito::class)->find($id);
        $arCreditoPago = New RhuCreditoPago();
        $form = $this->createForm(CreditoPagoType::class, $arCreditoPago);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($arCredito->getEstadoPagado() == 0) {
                $saldoActual = $arCredito->getVrSaldo();
                $abono = $form->get('vrPago')->getData();
                if ($abono > $arCredito->getVrSaldo()) {
                    Mensajes::error("El valor del pago no puede ser superior al saldo");
                } else {
                    $arCredito->setVrSaldo($saldoActual - $abono);
                    if ($arCredito->getVrSaldo() == 0) {
                        $arCredito->setEstadoPagado(1);
                    }
                    $cuotasActuales = $arCredito->getNumeroCuotaActual();
                    $arCredito->setNumeroCuotaActual($cuotasActuales + 1);
                    $arCreditoPago->setCreditoRel($arCredito);
                    $arCreditoPago->setfechaPago(new \ DateTime("now"));
                    $em->persist($arCreditoPago);
                    $em->persist($arCredito);
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            } else {
                Mensajes::error("El credito ya se encuentra pagado");
            }
        }
        return $this->render('recursohumano/movimiento/nomina/credito/detalleNuevo.html.twig', [
            'arCredito' => $arCredito,
            'arCreditoPago' => $arCreditoPago,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $session = new Session();

        $filtro = [
            'codigoCredito' => $form->get('codigoCreditoPk')->getData(),
            'codigoEmpleado' => $form->get('codigoEmpleadoFk')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoSuspendido' => $form->get('estadoSuspendido')->getData(),
            'estadoPagado' => $form->get('estadoPagado')->getData(),
        ];

        $arCreditoTipo = $form->get('codigoCreditoTipoFk')->getData();
        $arCreditoPagoTipo = $form->get('codigoCreditoPagoTipoFk')->getData();

        if (is_object($arCreditoTipo)) {
            $filtro['creditoTipo'] = $arCreditoTipo->getCodigoCreditoTipoPk();
        } else {
            $filtro['creditoTipo'] = $arCreditoTipo;
        }
        if (is_object($arCreditoPagoTipo)) {
            $filtro['creditoPagoTipo'] = $arCreditoPagoTipo->getCodigoCreditoPagoTipoPk();
        } else {
            $filtro['creditoPagoTipo'] = $arCreditoPagoTipo;
        }
        $session->set('filtroRhuCredito', $filtro);

        return $filtro;

    }
}

