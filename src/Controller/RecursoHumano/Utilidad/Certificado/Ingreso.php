<?php


namespace App\Controller\RecursoHumano\Utilidad\Certificado;


use App\Controller\MaestroController;
use App\Entity\General\GenCiudad;
use App\Entity\General\GenConfiguracion;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuPago;
use App\Formato\RecursoHumano\CertificadoIngreso;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Ingreso extends MaestroController
{


    public $tipo = "utilidad";
    public $proceso = "rhuu0004";



    /**
     * @param Request $request
     * @Route("/recursohumano/utilidad/embargo/cartificado/ingreso/lista", name="recursohumano_utilidad__certificadoIngreso_lista")
     **/
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $fechaActual = date('Y-m-j');
        $anioActual = date('Y');
        $fechaPrimeraAnterior = strtotime('-1 year', strtotime($fechaActual));
        $fechaPrimeraAnterior = date('Y', $fechaPrimeraAnterior);
        $fechaSegundaAnterior = strtotime('-2 year', strtotime($fechaActual));
        $fechaSegundaAnterior = date('Y', $fechaSegundaAnterior);
        $fechaTerceraAnterior = strtotime('-3 year', strtotime($fechaActual));
        $fechaTerceraAnterior = date('Y', $fechaTerceraAnterior);
        $formCertificado = $this->createFormBuilder()
            ->add('fechaCertificado', ChoiceType::class, array('choices' => array($anioActual = date('Y') => $anioActual = date('Y'), $fechaPrimeraAnterior => $fechaPrimeraAnterior, $fechaSegundaAnterior => $fechaSegundaAnterior, $fechaTerceraAnterior => $fechaTerceraAnterior),))
            ->add('fechaExpedicion', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => new \ DateTime('now')])
            ->add('LugarExpedicion', EntityType::class, array(
                'class' => GenCiudad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true))
            ->add('afc', NumberType::class, array('data' => '0', 'required' => false))
            ->add('codigoEmpleado', TextType::class, array('required' => true))
            ->add('certifico1', TextType::class, array('data' => '1. Mi patrimonio bruto era igual o inferior a 4.500 UVT ($123.683.000)', 'required' => true))
            ->add('certifico2', TextType::class, array('data' => '2. No fui responsable del impuesto sobre las ventas.', 'required' => true))
            ->add('certifico3', TextType::class, array('data' => '3. Mis ingresos totales fueron iguales o inferiores a 1.400 UVT ($38.479.000).', 'required' => true))
            ->add('certifico4', TextType::class, array('data' => '4. Mis consumos mediante tarjeta de crédito no excedieron la suma de 2.800 UVT ($76.958.000)', 'required' => true))
            ->add('certifico5', TextType::class, array('data' => '5. Que el total de mis compras y consumos no superaron la suma de 2.800 UVT ($76.958.000)', 'required' => true))
            ->add('certifico6', TextType::class, array('data' => '6. Que el valor total de mis consignaciones bancarias, depósitos o inversiones financieras no excedieron la suma de 4.500 UVT ($123.683.000)', 'required' => true))
            ->add('BtnGenerar', SubmitType::class, array('label' => 'Generar'))
            ->getForm();
        $formCertificado->handleRequest($request);
        if ($formCertificado->isSubmitted()) {
            if ($formCertificado->get('BtnGenerar')->isClicked()) {
                $ConfiguracionGeneral = $em->getRepository(GenConfiguracion::class)->find(1);
                $empleado = $em->getRepository(RhuEmpleado::class)->find($formCertificado->get('codigoEmpleado')->getData());
                $controles = $request->request->get('form');
                $codigoEmpleado = $empleado->getCodigoEmpleadoPk();
                $strFechaExpedicion = $formCertificado->get('fechaExpedicion')->getData();
                $strLugarExpedicion = $controles['LugarExpedicion'];
                $strFechaCertificado = $controles['fechaCertificado'];
//                $strFechaInicio = $formCertificado->get('fechaInicio')->getData()->format('Y-m-d');
//                $strFechaFin = $formCertificado->get('fechaFin')->getData()->format('Y-m-d');
                $strAfc = $controles['afc'];
                $stCertifico1 = $controles['certifico1'];
                $stCertifico2 = $controles['certifico2'];
                $stCertifico3 = $controles['certifico3'];
                $stCertifico4 = $controles['certifico4'];
                $stCertifico5 = $controles['certifico5'];
                $stCertifico6 = $controles['certifico6'];
                $datFechaCertificadoInicio = $strFechaCertificado . "-01-01";
                $datFechaCertificadoFin = $strFechaCertificado . "-12-31";
                // Se cambia logica para consultar los conceptos de pago segun el numero de la dian
                $floPagosPorSalario = (float)$em->getRepository(RhuPago::class)->devuelveCostosFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin, 37);//linea 37 pagos por salarios
                $floPagosPorHonorarios = (float)$em->getRepository(RhuPago::class)->devuelveCostosFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin, 38);//linea 38 pagos por honorarios
                $floPagosPorServicios = (float)$em->getRepository(RhuPago::class)->devuelveCostosFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin, 39);//linea 39 pagos por servicios
                $floPagosPorComisiones = (float)$em->getRepository(RhuPago::class)->devuelveCostosFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin, 40);//linea 40 pagos por comisiones
                $floPagosPorPrestacionesSociales = (float)$em->getRepository(RhuPago::class)->devuelveCostosFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin, 41);//linea 42 pagos por prestaciones sociales
                $floPagosPorViaticos = (float)$em->getRepository(RhuPago::class)->devuelveCostosFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin, 42);//linea 42 pagos por viaticos
                $floGastosDeRepresentacion = (float)$em->getRepository(RhuPago::class)->devuelveCostosFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin, 43);//linea 43 gastos de respresentacion
                $floPagosPorTrabajoAsociado = (float)$em->getRepository(RhuPago::class)->devuelveCostosFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin, 44);//linea 44 pagos por compensaciones por el trabajo asociado cooperativo
                $floOtrosPagos = (float)$em->getRepository(RhuPago::class)->devuelveCostosFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin, 45);//linea 45 otros pagos
                $totalCesantiaseIntereses = (float)$em->getRepository(RhuPago::class)->devuelveCostosFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin, 46);//linea 46 cesantias e intereses se consulta por la fecha de pago generada por el pago banco.
//                $totalCesantiaseInteresesLiquidacion = (float)$em->getRepository(RhuPago::class)->devuelveCostosFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin, 46, 0, 1);//linea 46 cesantias e intereses se debe enviar la fecha del año anterior realmente pagadas en en año de consulta.
//                $totalCesantiaseIntereses = $totalCesantiaseInteresesNomina + $totalCesantiaseInteresesLiquidacion;
                $floPensionJubilacion = (float)$em->getRepository(RhuPago::class)->devuelveCostosFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin, 47);//linea 47 pension de jubilacion
                //Concepto de los aportes --------------------------------
                $floSalud = (float)$em->getRepository(RhuPago::class)->devuelveAportesFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin, 49);// Line 49 Aporte salud
                $floPensionFsp = (float)$em->getRepository(RhuPago::class)->devuelveAportesFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin, 50);// Line 50 Aporte pension y fsp
                $floPensionVoluntarias = (float)$em->getRepository(RhuPago::class)->devuelveAportesFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin, 51);// Line 51 Aporte obligatorio a fondos de pensiones voluntarias
                $floCuentasAFC = (float)$em->getRepository(RhuPago::class)->devuelveAportesFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin, 52);// Line 52 Aporte Cuentas AFC
                $floCuentasAFC = $strAfc != 0 ? $strAfc : $floCuentasAFC;
                $floRetencionFuente = (float)$em->getRepository(RhuPago::class)->devuelveAportesFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin, 53);// Line 53 retencion fuente
//                Consultar si tiene acumulados en la tabla RhuAcumuladoCertificadoIngreso por motivos de migracion de datos.
//                $arrAcumuladoCertificadoIngreso = $em->getRepository(RhuCertificadoIngresoAcumulado::class)->acumuladoCertificadoIngreso($strFechaCertificado, $codigoEmpleado);
//                if ($arrAcumuladoCertificadoIngreso) {
//                    $floPagosPorSalario = $floPagosPorSalario + $arrAcumuladoCertificadoIngreso['salario'];
//                    $floSalud = $floSalud + $arrAcumuladoCertificadoIngreso['salud'];
//                    $floPensionFsp = $floPensionFsp + $arrAcumuladoCertificadoIngreso['pension'];
//                    $floPagosPorPrestacionesSociales = $floPagosPorPrestacionesSociales + $arrAcumuladoCertificadoIngreso['prestacionSocial'];
//                    $floPagosPorHonorarios = $floPagosPorHonorarios + $arrAcumuladoCertificadoIngreso['honorario'];
//                    $floPagosPorServicios = $floPagosPorServicios + $arrAcumuladoCertificadoIngreso['servicio'];
//                    $floPagosPorComisiones = $floPagosPorComisiones + $arrAcumuladoCertificadoIngreso['comision'];
//                    $floPagosPorViaticos = $floPagosPorViaticos + $arrAcumuladoCertificadoIngreso['viatico'];
//                    $floGastosDeRepresentacion = $floGastosDeRepresentacion + $arrAcumuladoCertificadoIngreso['gastoRepresentacion'];
//                    $floOtrosPagos = $floOtrosPagos + $arrAcumuladoCertificadoIngreso['otroPago'];
//                    $totalCesantiaseIntereses = $totalCesantiaseIntereses + $arrAcumuladoCertificadoIngreso['cesantiaInteres'];
//                    $floPensionJubilacion = $floPensionJubilacion + $arrAcumuladoCertificadoIngreso['pensionJubilacion'];
//                    $floRetencionFuente = $floRetencionFuente + $arrAcumuladoCertificadoIngreso['retencionFuente'];
//                }
                $duoTotalIngresos = $floPagosPorSalario + $floPagosPorHonorarios + $floPagosPorServicios + $floPagosPorComisiones + $floPagosPorPrestacionesSociales + $floPagosPorViaticos + $floGastosDeRepresentacion + $floPagosPorTrabajoAsociado + $floOtrosPagos + $totalCesantiaseIntereses + $floPensionJubilacion;
                //Generar el archivo
                $strRuta = "";
                if ($duoTotalIngresos > 0) {
                    $objFormatoCertificadoIngreso = new CertificadoIngreso();
                    $informacionFormato= [
                        'codigoEmpleado'=>$codigoEmpleado,
                        'strFechaExpedicion'=>$strFechaExpedicion,
                        'strFechaCertificado'=>$strFechaCertificado,
                        'floCuentasAFC'=>$floCuentasAFC,
                        'strCertifico1'=>$stCertifico1,
                        'strCertifico2'=>$stCertifico2,
                        'strCertifico3'=>$stCertifico3,
                        'strCertifico4'=>$stCertifico4,
                        'strCertifico5'=>$stCertifico5,
                        'strCertifico6'=>$stCertifico6,
                        'floPagosPorSalario'=>$floPagosPorSalario,
                        'floPensionFsp'=>$floPensionFsp,
                        'floSalud'=>$floSalud,
//                        '$strFechaInicio'=>$strFechaInicio,
                        'totalCesantiaseIntereses'=>$totalCesantiaseIntereses,
                        'floRetencionFuente'=>$floRetencionFuente,
                        'floGastosDeRepresentacion'=>$floGastosDeRepresentacion,
                        'floOtrosPagos'=>$floOtrosPagos,
                        'duoTotalIngresos'=>$duoTotalIngresos,
                        'strRuta'=>$strRuta,
                        'floPagosPorPrestacionesSociales'=>$floPagosPorPrestacionesSociales,
                        'floPagosPorComisiones'=>$floPagosPorComisiones,
                        'floPagosPorHonorarios'=>$floPagosPorHonorarios,
                        'floPagosPorServicios'=>$floPagosPorServicios,
                        'floPagosPorViaticos'=>$floPagosPorViaticos,
                        'floPagosPorViaticos'=>$floPagosPorViaticos,
                        'floPensionJubilacion'=>$floPensionJubilacion,
                        'codigoContrato'=>$empleado->getCodigoContratoFk(),
                        'strAfc'=>$strAfc,
                        'strLugarExpedicion'=>$strLugarExpedicion,
                        'totalPrestacional'=>'totalPrestacional',
                        'floPension'=>'floPension',
                        'datFechaInicio'=>'datFechaInicio',
                        'datFechaFin'=>'datFechaFin',
                        'douRetencion'=>'douRetencion',
                        'duoGestosRepresentacion'=>'duoGestosRepresentacion',
                        'douOtrosIngresos'=>'douOtrosIngresos',
                        'totalPrestacionSocial'=>'totalPrestacionSocial',
                        'floComisiones'=>'floComisiones',
                        'pagosPorHonorarios'=>'pagosPorHonorarios',
                        'pagosPorServicios'=>'pagosPorServicios',
                        'pagosPorViaticos'=>'pagosPorViaticos',
                        'arrValoresRecibidos'=>[
                            'arrendamientos'=>'arrendamientos',
                            'honorarios'=>'honorarios',
                            'intereses'=>'intereses',
                            'enajenacion'=>'enajenacion',
                            'loterias'=>'loterias',
                            'otros'=>'otros',
                        ],
                        'duoTotalValorRecibido'=>'duoTotalValorRecibido',
                        'arrValoresRetenidos'=>'arrValoresRetenidos',
                        'duoTotalRetencionGravable'=>'duoTotalRetencionGravable',
                    ];
                    $objFormatoCertificadoIngreso->Generar($em, $informacionFormato);
                } else {
                    Mensajes::error("Este empleado no registra información de ingresos  y retenciones para el año {$strFechaCertificado}");

                }
            }
        }
        return $this->render('recursohumano/utilidad/certificado/ingresoRetencion.html.twig', [
            'formCertificado' => $formCertificado->createView(),
        ]);
    }
}