<?php


namespace App\Controller\RecursoHumano\Informe\Nomina;


use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuVacacion;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class vacacionesPendientesController extends AbstractController
{
    /**
     * @Route("/recursohumano/informe/nomina/vacacionesPendientes/lista", name="recursohumano_informe_nomina_vacacionesPendientes_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoEmpleadoFk', TextType::class, array('required' => false))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
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
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuContrato::class)->informeVacacionesPendiente($raw), "Vacaciones pendientes");
            }
        }
        $raw['filtros'] = $this->getFiltros($form);
        $fechaHasta = new \DateTime('now');
        if ($raw['filtros']['fechaHasta'] != "") {
            $fechaHasta =$raw['filtros']['fechaHasta'];
        }
        $arVacacionesPagar= $em->getRepository(RhuContrato::class)->informeVacacionesPendiente($raw);
        foreach ($arVacacionesPagar as $key =>$arVacacion) {
            $fechaDesde = $arVacacion['fechaUltimoPagoVacaciones'];
            $arVacacionesAnteriores = $em->getRepository(RhuVacacion::class)->findOneBy(array('codigoContratoFk' =>  $arVacacion['codigoContratoPk'] ));
            if ($arVacacionesAnteriores != null) {
                if ($arVacacionesAnteriores->getFecha() > $fechaHasta & $session->get('chkVacacionesCorte')) {
                    $fechaDesde = $arVacacionesAnteriores->getFechaDesdePeriodo();
                } else if ($fechaDesde > $fechaHasta && $session->get('chkVacacionesCorte')) {
                    $fechaDesde = $arVacacionesAnteriores->getFechaDesdePeriodo();
                    $arVacacion->setFechaUltimoPagoVacaciones($fechaDesde);
                }
            }

            $intVrVacaciones = $this->liquidarVacaciones($arVacacion, $fechaHasta);
            $intDiasVacaciones = $this->diasVacaciones($arVacacion, $fechaHasta);
//          don mario autotoriza
//            $intDiasAusentismo = $em->getRepository(RhuPago::class)->diasAusentismo($fechaDesde->format('Y-m-d'), $fechaHasta->format('Y-m-d'), $arVacacion['codigoContratoPk']);
            $intDiasAusentismo=0;
            $arVacacionesPagar[$key]['valorVacaciones'] = $intVrVacaciones ;
            $arVacacionesPagar[$key]['diasVacaciones'] = $intDiasVacaciones ;
            $arVacacionesPagar[$key]['diasAusentismo'] = $intDiasAusentismo ;
        }
        $arVacacionesPagar = $paginator->paginate($arVacacionesPagar, $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/informe/nomina/vacacionesPendientes/lista.html.twig', [
            'arVacacionesPagar' => $arVacacionesPagar,
            'form' => $form->createView(),
        ]);
    }

    public function liquidarVacaciones($arVacacionPagar, $dateFechaHasta = '')
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
//        $arCambioSalario = $em->getRepository('BrasaRecursoHumanoBundle:RhuCambioSalario')->findOneBy(array('codigoEmpleadoFk' => $arVacacionPagar->getCodigoEmpleadoFk()));
        $arCambioSalario=null;

        $dateFechaDesde = $arVacacionPagar['fechaUltimoPagoVacaciones'];
        $arVacacionesAnteriores = $em->getRepository(RhuVacacion::class)->findOneBy(array('codigoContratoFk' => $arVacacionPagar['codigoContratoPk']));

        if ($arVacacionesAnteriores != null) {
            if ($arVacacionesAnteriores->getFechaHastaPeriodo() > $dateFechaHasta && $session->get('chkVacacionesCorte')) {
                $arVacacionPagar->setFechaUltimoPagoVacaciones($arVacacionesAnteriores->getFechaDesdePeriodo());
                $dateFechaDesde = $arVacacionesAnteriores->getFechaDesdePeriodo();
            } else if ($dateFechaDesde > $dateFechaHasta ) {
                $dateFechaDesde = $arVacacionesAnteriores->getFechaDesdePeriodo();
                $arVacacionPagar->setFechaUltimoPagoVacaciones($dateFechaDesde);
            }
        }

        $recargosNocturnos = $em->getRepository(RhuPagoDetalle::class)->recargosNocturnosFecha($dateFechaDesde, $dateFechaHasta, $arVacacionPagar['codigoContratoPk']);
        $salarioVacaciones = $arVacacionPagar['vrSalario'];

        $fechaActual = date_create($dateFechaHasta->format('Y-m-d'));
        $fechaInicio = date_create($dateFechaDesde->format('Y-m-d'));

        $stringOneyearLessfechaInicio = $fechaActual->modify('-1 year');
        $stringOneyearLessfechaInicio = $stringOneyearLessfechaInicio->modify('+1 day');
        $intDiasVacacionesPrestacionales = FuncionesController::diasPrestaciones($fechaInicio, $dateFechaHasta);
        $intDaysEvaluate = $dateFechaDesde->diff($dateFechaHasta);
        if ($intDaysEvaluate->days < 360 && $arVacacionPagar['fechaDesde'] != $arVacacionPagar['fechaUltimoPagoVacaciones']) {
            $intDiasVacacionesPrestacionales--;
            $dateFechaDesde = $dateFechaDesde->modify('+1 day');
            $intDiasVacacionesPrestacionales = FuncionesController::diasPrestaciones($fechaInicio, $dateFechaHasta);
            $ausentismo = $em->getRepository(RhuPago::class)->diasAusentismo($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arVacacionPagar['codigoContratoPk']);
//            $suplementario = $em->getRepository(RhuPagoDetalle::class)->ibpSuplementario($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arVacacionPagar['codigoContratoPk']);
        } else if ($intDaysEvaluate->days < 360 && $arVacacionPagar['fechaDesde'] == $arVacacionPagar['fechaUltimoPagoVacaciones']) {
            $ausentismo = $em->getRepository(RhuPago::class)->diasAusentismo($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arVacacionPagar['codigoContratoPk']);
//            $suplementario = $em->getRepository(RhuPagoDetalle::class)->ibpSuplementario($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arVacacionPagar['codigoContratoPk']);
        } else {
            $ausentismo = $em->getRepository(RhuPago::class)->diasAusentismo($stringOneyearLessfechaInicio->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arVacacionPagar['codigoContratoPk']);
//            $suplementario = $em->getRepository(RhuPagoDetalle::class)->ibpSuplementario($stringOneyearLessfechaInicio->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arVacacionPagar['codigoContratoPk']);
        }
        $suplementario=0;

//        $strDqlAusentismoCactus = $em->getRepository('BrasaRecursoHumanoBundle:RhuAusentismoCactus')->dqlAusentismoCactus($arVacacionPagar->getEmpleadoRel()->getNumeroIdentificacion(), $arVacacionPagar->getFechaUltimoPagoVacaciones()->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'));
//        $query2 = $em->createQuery($strDqlAusentismoCactus);
//        $intDiasAusentismoCactus = $query2->getResult();
//
//        if ($intDiasAusentismoCactus[0]['ausentismoCactus'] != null) {
//            $intDiasAusentismo = $ausentismo + (Int)$intDiasAusentismoCactus;
//        } else {
//            $intDiasAusentismo = $ausentismo;
//        }

        $intDiasAusentismo=0;
        $suplementarioPromedio = 0;

        if ($intDiasVacacionesPrestacionales > 0) {
            if ($intDiasVacacionesPrestacionales > 360) {
                $intDiasVacacionesPrestacionalesCalculo = 360;
                $suplementarioPromedio = ($suplementario / ($intDiasVacacionesPrestacionalesCalculo - $intDiasAusentismo)) * 30;
            } else {
                $suplementarioPromedio = ($suplementario / ($intDiasVacacionesPrestacionales - $intDiasAusentismo)) * 30;
            }
        }

        if ($arCambioSalario != null && $session->get('chkVacacionesCorte')) {
            $dateCambioSalario = $arCambioSalario->getFecha();

            if ($dateCambioSalario >= $dateFechaHasta) {
                $salario = $arCambioSalario->getVrSalarioAnterior();
                $arVacacionPagar->setVrSalario($salario);
            } else {
//                $salario = $arVacacionPagar->getVrSalario();
                $salario=0;
            }
        } else {
//            $salario = $arVacacionPagar->getVrSalario();
            $salario=0;
        }

//        $arVacacionPagar->vrSalarioVariable = $suplementarioPromedio;

        $salarioVacaciones = (($salario + $suplementarioPromedio) * ($intDiasVacacionesPrestacionales - $intDiasAusentismo)) / 720;

        $salarioVacaciones = round($salarioVacaciones);

        return $salarioVacaciones;
    }

    private function diasVacaciones($arVacacion, $fechaHasta)
    {
        $em = $this->getDoctrine()->getManager();
        $intDiasPrestacion = FuncionesController::diasPrestaciones($arVacacion['fechaUltimoPagoVacaciones'], $fechaHasta);
//        $intDiasPrestacion = $this->diasPrestaciones($arVacacion->getFechaUltimoPagoVacaciones(), $fechaHasta);
//        $strDqlAusentismo = $em->getRepository('BrasaRecursoHumanoBundle:RhuAusentismoCactus')->dqlAusentismoCactus($arVacacion->getEmpleadoRel()->getnumeroIdentificacion(), $arVacacion->getFechaUltimoPagoVacaciones()->format('Y-m-d'), $fechaHasta->format('Y-m-d'));
//        $query2 = $em->createQuery($strDqlAusentismo);
//        $intDiasAusentismo = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->diasAusentismo($arVacacion->getFechaUltimoPagoVacaciones()->format('Y-m-d'), $fechaHasta->format('Y-m-d'), $arVacacion->getCodigoContratoPk());

        $intDiasAusentismo=0;
        $intDiasPrestacion = $intDiasPrestacion - $intDiasAusentismo;
        $aniosVacaciones = $intDiasPrestacion / 360;
        $diasVacaciones = $aniosVacaciones * 15;

        return $diasVacaciones;

    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoEmpleado' => $form->get('codigoEmpleadoFk')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
        ];

        return $filtro;

    }
}