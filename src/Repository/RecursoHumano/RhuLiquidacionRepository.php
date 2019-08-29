<?php

namespace App\Repository\RecursoHumano;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinTercero;
use App\Entity\RecursoHumano\RhuConceptoCuenta;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuConfiguracionCuenta;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmbargo;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuLiquidacion;
use App\Entity\RecursoHumano\RhuLiquidacionAdicional;
use App\Entity\RecursoHumano\RhuNovedad;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
use App\Entity\RecursoHumano\RhuReclamo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuLiquidacionRepository extends ServiceEntityRepository
{

    /**
     * @return string
     */
    public function getRuta()
    {
        return 'recursohumano_movimiento_reclamo_reclamo_';
    }

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuLiquidacion::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuReclamo::class, 'e');
        $queryBuilder
            ->select('e.codigoEmbargoPk');
        return $queryBuilder;
    }

    /**
     * @return array
     */
    public function parametrosLista()
    {
        $arEmbargo = new RhuEmbargo();
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmbargo::class, 're')
            ->select('re.codigoEmbargoPk')
            ->addSelect('re.fecha')
            ->where('re.codigoEmbargoPk <> 0');
        $arrOpciones = ['json' => '[{"campo":"codigoEmbargoPk","ayuda":"Codigo del embargo","titulo":"ID"},
        {"campo":"fecha","ayuda":"Fecha de registro","titulo":"FECHA"}]',
            'query' => $queryBuilder, 'ruta' => $this->getRuta()];
        return $arrOpciones;
    }

    /**
     * @return mixed
     */
    public function parametrosExcel()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmbargo::class, 're')
            ->select('re.codigoEmbargoPk')
            ->addSelect('re.fecha')
            ->where('re.codigoEmbargoPk <> 0');
        return $queryBuilder->getQuery()->execute();
    }

    public function diasPrestacionesHasta($intDias, $dateFechaDesde)
    {
        $strFechaHasta = "";
        $intAnio = $dateFechaDesde->format('Y');
        $intMes = $dateFechaDesde->format('n');
        $intDia = $dateFechaDesde->format('j');
        $intDiasAcumulados = 1;
        $i = $intDia;
        while ($intDiasAcumulados <= $intDias) {
            //echo $intDiasAcumulados . "(" . $i . ")" . "(" . $intMes . ")" . "(" . $intAnio . ")" . "<br />";
            $fechaHastaPeriodo = $intAnio . "-" . $intMes . "-" . $i;
            if ($i == 30 || $i == 31) {
                $i = 1;
                if ($intMes == 12) {
                    $intMes = 1;
                    $intAnio++;
                } else {
                    $intMes++;
                }
            } else {
                $i++;
            }
            $intDiasAcumulados++;
        }
        $fechaHastaPeriodo = date_create_from_format('Y-n-j H:i', $fechaHastaPeriodo . " 00:00");
        // validacion para los meses de 31 dias
        if ($intDia == 31 && $intDias == 361) {
            $fechaHastaPeriodo = date_create(date('Y-m-j', strtotime('+1 day', strtotime($fechaHastaPeriodo->format('Y-m-d')))));
        }
        return $fechaHastaPeriodo;
    }

    public function diasPrestaciones($dateFechaDesde, $dateFechaHasta)
    {
        $intDias = 0;
        $intAnioInicio = $dateFechaDesde->format('Y');
        $intAnioFin = $dateFechaHasta->format('Y');
        $intAnios = 0;
        $intMeses = 0;
        if ($dateFechaHasta >= $dateFechaDesde) {
            if ($dateFechaHasta->format('d') == '31' && ($dateFechaHasta == $dateFechaDesde)) {
                $intDias = 0;
            } else {
                if ($intAnioInicio != $intAnioFin) {
                    $intDiferenciaAnio = $intAnioFin - $intAnioInicio;
                    if (($intDiferenciaAnio) > 1) {
                        $intAnios = $intDiferenciaAnio - 1;
                        $intAnios = $intAnios * 12 * 30;
                    }

                    $dateFechaTemporal = date_create_from_format('Y-m-d H:i', $intAnioInicio . '-12-30' . "00:00");
                    if ($dateFechaDesde->format('n') != $dateFechaTemporal->format('n')) {
                        $intMeses = $dateFechaTemporal->format('n') - $dateFechaDesde->format('n') - 1;
                        $intDiasInicio = $this->diasPrestacionesMes($dateFechaDesde->format('j'), 1);
                        $intDiasFinal = $this->diasPrestacionesMes($dateFechaTemporal->format('j'), 0);
                        $intDias = $intDiasInicio + $intDiasFinal + ($intMeses * 30);
                    } else {
                        if ($dateFechaTemporal->format('j') == $dateFechaDesde->format('j')) {
                            $intDias = 0;
                        } else {
                            $intDias = 1 + ($dateFechaTemporal->format('j') - $dateFechaDesde->format('j'));
                        }
                    }

                    $dateFechaTemporal = date_create_from_format('Y-m-d H:i', $intAnioFin . '-01-01' . "00:00");
                    if ($dateFechaTemporal->format('n') != $dateFechaHasta->format('n')) {
                        $intMeses = $dateFechaHasta->format('n') - $dateFechaTemporal->format('n') - 1;
                        $intDiasInicio = $this->diasPrestacionesMes($dateFechaTemporal->format('j'), 1);
                        $intDiasFinal = $this->diasPrestacionesMes($dateFechaHasta->format('j'), 0, $dateFechaHasta->format('m'));
                        $intDias += $intDiasInicio + $intDiasFinal + ($intMeses * 30);
                    } else {
                        $incremento = $dateFechaHasta->format('d') != 31 ? 1 : 0;
                        $intDias += $incremento + ($dateFechaHasta->format('j') - $dateFechaTemporal->format('j'));
                    }
                    $intDias += $intAnios;
                } else {
                    if ($dateFechaDesde->format('n') != $dateFechaHasta->format('n')) {
                        $intMeses = $dateFechaHasta->format('n') - $dateFechaDesde->format('n') - 1;
                        $intDiasInicio = $this->diasPrestacionesMes($dateFechaDesde->format('j'), 1);
                        $intDiasFinal = $this->diasPrestacionesMes($dateFechaHasta->format('j'), 0, $dateFechaHasta->format('m'));
                        $intDias = $intDiasInicio + $intDiasFinal + ($intMeses * 30);
                    } else {
                        if ($dateFechaHasta->format('j') == 31) {
                            $intDias = ($dateFechaHasta->format('j') - $dateFechaDesde->format('j'));
                        } else {
                            if ($dateFechaHasta->format('j') - $dateFechaDesde->format('j') < 0) {
                                $intDias = 0;
                            } else {
                                $intDias = 1 + ($dateFechaHasta->format('j') - $dateFechaDesde->format('j'));
                            }

                        }
                    }
                }
            }
        } else {
            $intDias = 0;
        }

        return $intDias;
    }

    public function diasPrestacionesMes($intDia, $intDesde, $intMes = "")
    {
        $intDiasDevolver = 0;
        if ($intDesde == 1) {
            $intDiasDevolver = 31 - $intDia;
        } else {
            $intDiasDevolver = $intDia;
            if ($intDia == 31) {
                $intDiasDevolver = 30;
            }
            /* Quito esta validacion porque si la persona termina el 28 no esta el periodo completo del mes
            deben ser solo 28 dias*/
            //if ($intDia == 28 && $intMes == 02) {//Validacion si es del mes de febrero y del dia 28, deben ser 30 dias prestacionales del mes.
            //    $intDiasDevolver = 30;
            //}
        }

        return $intDiasDevolver;
    }

    public function liquidar($codigoLiquidacion)
    {
        /**
         * @var $arConfiguracion RhuConfiguracion
         */
        $em = $this->getEntityManager();
        $objFunciones = new FuncionesController();
        $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
        $arLiquidacion = $em->getRepository(RhuLiquidacion::class)->find($codigoLiquidacion);
        $salarioMinimo = $arConfiguracion->getVrSalarioMinimo();
        $auxilioTransporte = $arConfiguracion->getVrAuxilioTransporte();
        $respuesta = "";
        $douSalario = 0;
        $douCesantias = 0;
        $douInteresesCesantias = 0;
        $douPrima = 0;
        $douAdicionalesPrima = 0;
        $douVacaciones = 0;
        $intDiasLaborados = 0;
        $ibpCesantias = 0;
        $salarioPromedioCesantias = 0;
        $vrDeduccionPrima = 0;
        $arContrato = $em->getRepository(RhuContrato::class)->find($arLiquidacion->getCodigoContratoFk());
        if ($arContrato->getFechaUltimoPagoVacaciones() == null) {
            $respuesta = "El contrato no tiene fecha del ultimo pago de vacaciones, corrija este error para continuar";
        }
        if ($respuesta == "") {
            //Liquidar Indemnización
            if ($arLiquidacion->getEstadoIndemnizacion() || $arLiquidacion->getVrIndemnizacionPropuesto() != 0) {
                //se crea funcion para indemnizar un empleado de acuerdo al art 64 de CST.
                /**
                 * @var RhuLiquidacion $arLiquidacion
                 */

                $fechaDesde = $arLiquidacion->getContratoRel()->getFechaDesde();
                $fechaHasta = $arLiquidacion->getContratoRel()->getFechaHasta();

                $vrIndemnizacion = 0;
                $salarioBase = $arLiquidacion->getContratoRel()->getVrSalario();
                $diaSalario = $salarioBase / 30;
                $tiempoLaborado = date_diff($fechaDesde, $fechaHasta);
                if ($arLiquidacion->getContratoRel()->getContratoTipoRel()->getCodigoContratoClaseFk() == 'IND') {
                    if ($fechaDesde < new \DateTime('1981-01-01')) {// se verifica que la fecha de incio del contrato sea mayo a 10 años para acogerse a ley 50 de 1990.
                        $fechaControlLey50 = new \DateTime('1991-01-01');
                        $diferenciaAl91 = date_diff($fechaDesde, $fechaControlLey50);// se verifica si tenia mas de 10 años laborando al 1 de enero de 1991
                        if ($diferenciaAl91->y >= 10) {//Ley 50 de 1990 art 6
                            /*
                            * a) Cuarenta y cinco (45) días de salario cuando el trabajador tuviere un tiempo de servicio no mayor de un (1) año;
                            * b) Si el trabajador tuviere más de un (1) año de servicio continuo y menos de cinco (5), se le pagarán quince (15) días adicionales de salario sobre los
                            * cuarenta y cinco (45) básicos del literal a), por cada uno de los años de servicio subsiguientes al primero, y proporcionalmente por fracción;
                            * c) Si el trabajador tuviere cinco (5) años o más de servicio continuo y menos de diez (10), se le pagarán veinte (20) días adicionales de salario sobre los
                            * cuarenta y cinco (45) básicos del literal a), por cada uno de los años de servicio subsiguientes al primero, y proporcionalmente por fracción; y
                            * d) Si el trabajador tuviere diez (10) o más años de servicio continuo se le pagarán cuarenta (40) días adicionales de salario sobre los cuarenta y cinco
                            * (45) días básicos del literal a), por cada uno de los años de servicio subsiguientes al primero, y proporcionalmente por fracción.
                            * Parágrafo transitorio. Los trabajadores que al momento de entrar en vigencia la presente ley tuvieren diez (10) o más años al servicio continuo del
                            * empleador, seguirán amparados por el ordinal 5o del artículo 8 del Decreto-ley 2351 de 1965, salvo que el trabajador manifieste su voluntad de acogerse al
                            * nuevo régimen.
                            */
                            $vrIndemnizacion = $diaSalario * 45;
                            if ($tiempoLaborado->y > 1 && $tiempoLaborado->y < 5) {
                                for ($i = 2; $i <= $tiempoLaborado->y; $i++) { // se recorren los años a partir del 1 y hasta el 4 para pagar el valor indicado por
                                    // la ley por año.
                                    $vrIndemnizacion += ($diaSalario * 15);
                                }
                                // se verifican los meses y dias pendientes para pagar la proporción de lo restante
                                $meses = $tiempoLaborado->format('m');
                                $dias = $tiempoLaborado->format('d');
                                $dias += ($meses * 30);
                                $diasProporcion = (($dias * 15) / 365);
                                $vrIndemnizacion += ($diasProporcion * $diaSalario);
                            } else if ($tiempoLaborado->y >= 5 && $tiempoLaborado->y <= 10) {
                                for ($i = 2; $i <= $tiempoLaborado->y; $i++) { // se recorren los años
                                    $vrIndemnizacion += ($diaSalario * 20);
                                }
                                // se verifican los meses y dias pendientes para pagar la proporción de lo restante
                                $meses = $tiempoLaborado->m;
                                $dias = $tiempoLaborado->d;
                                $dias += ($meses * 30);
                                $diasProporcion = (($dias * 20) / 365);
                                $vrIndemnizacion += ($diasProporcion * $diaSalario);
                            } else if ($tiempoLaborado->y >= 10) {
                                for ($i = 2; $i <= $tiempoLaborado->y; $i++) { // se recorren los años
                                    $vrIndemnizacion += ($diaSalario * 40);
                                }
                                // se verifican los meses y dias pendientes para pagar la proporción de lo restante
                                $meses = $tiempoLaborado->m;
                                $dias = $tiempoLaborado->d;
                                $dias += ($meses * 30);
                                $diasProporcion = (($dias * 40) / 365);
                                $vrIndemnizacion += ($diasProporcion * $diaSalario);
                            }
                        }
                    } else { // art 64 del CST
                        /*
                         * En los contratos a término indefinido la indemnización se pagará así:
                         * a) Para trabajadores que devenguen un salario inferior a diez (10) salarios mínimos mensuales legales:
                         * 1. Treinta (30) días de salario cuando el trabajador tuviere un tiempo de servicio no mayor de un (1) año.
                         * 2. Si el trabajador tuviere más de un (1) año de servicio continuo se le pagarán veinte (20) días adicionales de salario sobre los treinta (30) básicos del
                         * numeral 1, por cada uno de los años de servicio subsiguientes al primero y proporcionalmente por fracción;
                         * b) Para trabajadores que devenguen un salario igual o superior a diez (10), salarios mínimos legales mensuales.
                         * 1. Veinte (20) días de salario cuando el trabajador tuviere un tiempo de servicio no mayor de un (1) año.
                         * 2. Si el trabajador tuviere más de un (1) año de servicio continuo, se le pagarán quince (15) días adicionales de salario sobre los veinte (20) días básicos
                         * del numeral 1 anterior, por cada uno de los años de servicio subsiguientes al primero y proporcionalmente por fracción.
                         * PARÁGRAFO TRANSITORIO. Los trabajadores que al momento de entrar en vigencia la presente ley, tuvieren diez (10) o más años al servicio continuo del empleador,
                         * se les aplicará la tabla de indemnización establecida en los literales b), c) y d) del artículo 6o. de la Ley 50 de 1990, exceptuando el parágrafo transitorio,
                         * el cual se aplica únicamente para los trabajadores que tenían diez (10) o más años el primero de enero de 1991(artículo 28 de la Ley 789 de 2002).
                         */
                        if ($salarioBase >= ($salarioMinimo * 10)) {// se verifica si el salario base es mayor o igual a 10 salarios minimos mensuales
                            $vrIndemnizacion += ($diaSalario * 20);
                            for ($i = 2; $i <= $tiempoLaborado->y; $i++) {
                                $vrIndemnizacion += ($diaSalario * 15);
                            }
                            $meses = $tiempoLaborado->m;
                            $dias = $tiempoLaborado->d;
                            $dias += ($meses * 30);
                            $diasProporcion = (($dias * 15) / 365);
                            $vrIndemnizacion += ($diasProporcion * $diaSalario);

                        } else {
                            $vrIndemnizacion += ($diaSalario * 30);
                            if ($tiempoLaborado->y > 1) {
                                for ($i = 2; $i <= $tiempoLaborado->y; $i++) {
                                    $vrIndemnizacion += ($diaSalario * 20);
                                }
                                $meses = $tiempoLaborado->m;
                                $dias = $tiempoLaborado->d;
                                $dias += ($meses * 30);
                                $diasProporcion = (($dias * 20) / 365);
                                $vrIndemnizacion += ($diasProporcion * $diaSalario);
                            }
                        }
                    }
                } else {// liquidacion contrato termino fijo
                    $diasProporcion = 0;
                    $tiempoLaborado = date_diff($fechaHasta, $arLiquidacion->getFechaHastaContratoFijo());

                    $anios = $tiempoLaborado->y;
                    $meses = $tiempoLaborado->m;
                    $dias = $tiempoLaborado->d;

                    $diasProporcion += (($anios * 12) * 30);
                    $diasProporcion += ($meses * 30);
                    $diasProporcion += $dias;
                    $vrIndemnizacion = ($diasProporcion * $diaSalario);


                }
                if ($arLiquidacion->getVrIndemnizacionPropuesto() != 0) {
                    $vrIndemnizacion = $arLiquidacion->getVrIndemnizacionPropuesto();
                }
                $arLiquidacion->setVrIndemnizacion($vrIndemnizacion);
            } else {
                $arLiquidacion->setVrIndemnizacion(0);
            }
            if ($arLiquidacion->getLiquidarManual() == 0) {
                $douIBPAdicional = 0;
                $dateFechaUltimoPago = $arLiquidacion->getContratoRel()->getFechaUltimoPago();
                //Ibc de dias adicionales
                if ($dateFechaUltimoPago != null) {
                    $arLiquidacion->setFechaUltimoPago($dateFechaUltimoPago);
                    if ($dateFechaUltimoPago < $arLiquidacion->getFechaHasta()) {
                        $strFechaUltimoPagoLiquidacion = $dateFechaUltimoPago->format("Y-m-d");
                        $dateFechaUltimoPagoLiquidacion = new \DateTime("$strFechaUltimoPagoLiquidacion");
                        $dateFechaUltimoPagoLiquidacion->modify('+1 day');
                        $diasAdicionales = $this->diasPrestaciones($dateFechaUltimoPagoLiquidacion, $arLiquidacion->getFechaHasta());
                        $douIBPAdicional = ($arLiquidacion->getContratoRel()->getVrSalario() / 30) * $diasAdicionales;
                        $douIBPAdicional = round($douIBPAdicional);
                        $arLiquidacion->setVrIngresoBasePrestacionAdicional($douIBPAdicional);
                        $arLiquidacion->setDiasAdicionalesIBP($diasAdicionales);
                    } else {
                        $arLiquidacion->setVrIngresoBasePrestacionAdicional(0);
                        $arLiquidacion->setDiasAdicionalesIBP(0);
                    }
                }

                $intDiasLaborados = $this->diasPrestaciones($arLiquidacion->getContratoRel()->getFechaDesde(), $arLiquidacion->getContratoRel()->getFechaHasta());
                $douSalario = $arLiquidacion->getContratoRel()->getVrSalario();

                //Liquidar cesantias
                $salarioPromedioCesantiasAnterior = 0;
                $diasCesantiaAnterior = 0;
                $cesantiaAnterior = 0;
                $interesCesantiaAnterior = 0;
                $diasCesantiaAusentismoAnterior = 0;
                //Validar si la fecha de terminacion del contrato es mayor a la fecha del ultimo pago de cesantias que liquide las cesantias de lo contrario no debe liquidar, ya que estas fueron pagadas.
                if ($arLiquidacion->getFechaHasta() >= $arLiquidacion->getFechaUltimoPagoCesantias()) {
                    if ($arLiquidacion->getLiquidarCesantias() == 1) {
                        // se valida la fecha de ultimo pago de cesantias cuando el dia es 31
                        if ($arLiquidacion->getContratoRel()->getFechaUltimoPagoCesantias()->format('d') == 31) {
                            $fechaActualUltimoPagoCesantias = date_create(($arLiquidacion->getContratoRel()->getFechaUltimoPagoCesantias()->format('Y-m')) . '-30');
                            $arLiquidacion->getContratoRel()->setFechaUltimoPagoCesantias($fechaActualUltimoPagoCesantias);
                        }
                        $fechaUltimoPago = $arLiquidacion->getContratoRel()->getFechaUltimoPagoCesantias();
                        if ($arConfiguracion->getLiquidarCesantiaAnioAnterior()) {
                            //$arLiquidacion->setCodigoPagoFk(null);
                            //$arLiquidacion->setCodigoPagoInteresFk(null);
                            //$arLiquidacion->setCodigoProgramacionPagoDetalleFk(null);
                            //$arLiquidacion->setCodigoProgramacionPagoDetalleInteresFk(null);
                            // validacion y liquidacion de cesantias año anterior
                            if ($arLiquidacion->getOmitirCesantiasAnterior() == false) { // validacion y liquidacion de cesantias año anterior
                                $arPago = $em->getRepository(RhuPago::class)->findOneBy(array('codigoPagoTipoFk' => 3, 'codigoEmpleadoFk' => $arLiquidacion->getCodigoEmpleadoFk(), 'estadoAprobado' => 0));
                                if ($arPago) {
                                    $arProgramacionPagoDetalle = $em->getRepository(RhuProgramacionDetalle::class)->find($arPago->getCodigoProgramacionDetalleFk());
                                    if ($arProgramacionPagoDetalle) {
                                        $cesantiaAnterior = $arProgramacionPagoDetalle->getVrNetoPagar();
                                        $diasCesantiaAusentismoAnterior = $arProgramacionPagoDetalle->getDiasAusentismo() + $arProgramacionPagoDetalle->getDiasAusentismoAdicional();
                                        $salarioPromedioCesantiasAnterior = $arProgramacionPagoDetalle->getVrSalarioCesantia();
                                        if ($arProgramacionPagoDetalle->getVrSalarioCesantiaPropuesto() > 0) {
                                            $salarioPromedioCesantiasAnterior = $arProgramacionPagoDetalle->getVrSalarioCesantiaPropuesto();
                                        }
                                        $fechaUltimoPago = $arProgramacionPagoDetalle->getFechaDesde();
                                        $arLiquidacion->setCodigoProgramacionPagoDetalleFk($arPago->getCodigoProgramacionPagoDetalleFk());
                                    }
                                    $arLiquidacion->setCodigoPagoFk($arPago->getCodigoPagoPk());
                                }
                            }
                            // validacion y liquidacion de intereses cesantias año anterior
                            if ($arLiquidacion->getOmitirInteresCesantiasAnterior() == false) {
                                $arPago = $em->getRepository(RhuPago::class)->findOneBy(array('codigoPagoTipoFk' => 6, 'codigoEmpleadoFk' => $arLiquidacion->getCodigoEmpleadoFk(), 'estadoAprobado' => 0));
                                if ($arPago) {
                                    $arProgramacionPagoDetalle = $em->getRepository(RhuProgramacionDetalle::class)->find($arPago->getCodigoProgramacionDetalleFk());
                                    if ($arProgramacionPagoDetalle) {
                                        $interesCesantiaAnterior = $arProgramacionPagoDetalle->getVrInteresCesantia();
                                        $fechaUltimoPago = $arProgramacionPagoDetalle->getFechaDesde();
                                        $arLiquidacion->setCodigoProgramacionPagoDetalleInteresFk($arPago->getCodigoProgramacionPagoDetalleFk());
                                    }
                                    $arLiquidacion->setCodigoPagoInteresFk($arPago->getCodigoPagoPk());
                                }
                            }


//                                if ($arLiquidacion->getOmitirCesantiasAnterior() == false || $arLiquidacion->getOmitirInteresCesantiasAnterior() == false) {
//                                    $arProgramacionPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->find($arPago->getCodigoProgramacionPagoDetalleFk());
//                                    if ($arProgramacionPagoDetalle) {
//                                        if ($arLiquidacion->getOmitirCesantiasAnterior() == false) {
//                                            $cesantiaAnterior = $arProgramacionPagoDetalle->getVrNetoPagar();
//                                            $diasCesantiaAusentismoAnterior = $arProgramacionPagoDetalle->getDiasAusentismo() + $arProgramacionPagoDetalle->getDiasAusentismoAdicional();
//                                            $salarioPromedioCesantiasAnterior = $arProgramacionPagoDetalle->getVrSalarioCesantia();
//                                            if ($arProgramacionPagoDetalle->getVrSalarioCesantiaPropuesto() > 0) {
//                                                $salarioPromedioCesantiasAnterior = $arProgramacionPagoDetalle->getVrSalarioCesantiaPropuesto();
//                                            }
//                                        }
//                                        if ($arLiquidacion->getOmitirInteresCesantiasAnterior() == false) {
//                                            $interesCesantiaAnterior = $arProgramacionPagoDetalle->getVrInteresCesantia();
//                                        }
//                                        $fechaUltimoPago = $arProgramacionPagoDetalle->getFechaDesde();
//                                        $arLiquidacion->setCodigoProgramacionPagoDetalleFk($arPago->getCodigoProgramacionPagoDetalleFk());
//                                    }
//                                    $arLiquidacion->setCodigoPagoFk($arPago->getCodigoPagoPk());
//                                }
//                            }
                        }
                        $arLiquidacion->setDiasCesantiasAnterior($diasCesantiaAnterior);
                        $arLiquidacion->setVrCesantiasAnterior($cesantiaAnterior);
                        $arLiquidacion->setVrInteresesCesantiasAnterior($interesCesantiaAnterior);
                        $arLiquidacion->setDiasCesantiasAusentismoAnterior($diasCesantiaAusentismoAnterior);
                        $arLiquidacion->setVrSalarioPromedioCesantiasAnterior($salarioPromedioCesantiasAnterior);
                        $arLiquidacion->setFechaUltimoPagoCesantiasAnterior($fechaUltimoPago);

                        $dateFechaDesde = $arLiquidacion->getContratoRel()->getFechaUltimoPagoCesantias();
                        $dateFechaHasta = $arLiquidacion->getContratoRel()->getFechaHasta();
                        if ($dateFechaHasta >= $dateFechaDesde) {
                            $ibpCesantiasInicial = $arContrato->getIbpCesantiasInicial();
                            $ibpCesantiasInicial = round($ibpCesantiasInicial);
                            $ibpCesantias = $em->getRepository(RhuPagoDetalle::class)->ibp($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arLiquidacion->getCodigoContratoFk());
                            $ibpCesantias += $ibpCesantiasInicial + $douIBPAdicional;
                            $ibpCesantias = round($ibpCesantias);
                            if ($arConfiguracion->getDescontarAusentismosDeLicencias()) {
                                $intDiasAusentismo = $em->getRepository(RhuNovedad::class)->diasAusentismoMovimiento($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arLiquidacion->getCodigoContratoFk());
                            } else {
                                $intDiasAusentismo = $em->getRepository(RhuPago::class)->diasAusentismo($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arLiquidacion->getCodigoContratoFk());
                            }

//                            // validacion para descontar todos los ausentimos durante la vigencia del contrato
//                            if ($arConfiguracion->getDescontarTotalAusentismosContratoTerminadoEnLiquidacion()) {
//                                $intDiasAusentismo = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->diasAusentismo($arLiquidacion->getContratoRel()->getFechaDesde()->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arLiquidacion->getCodigoContratoFk());
//                            }
                            $intDiasAusentismo += $arLiquidacion->getDiasAusentismoAdicional();
                            if ($arLiquidacion->getDiasAusentismoPropuestoCesantias()) {
                                $intDiasAusentismo = $arLiquidacion->getDiasAusentismoPropuestoCesantias();
                            }
                            if ($arLiquidacion->getEliminarAusentismo() > 0) {
                                $intDiasAusentismo = 0;
                            }
                            if ($arLiquidacion->getEliminarAusentismoCesantia() > 0) {
                                $intDiasAusentismo = 0;
                            }
                            // No quitar por favor, esto tiene su logica, ingreso del dia 30dic
                            $fechaDesdeCesantias = date_create($dateFechaDesde->format('Y-m-d'));
                            if ($fechaDesdeCesantias > $arLiquidacion->getContratoRel()->getFechaDesde()) {
                                $fechaDesdeCesantias->modify('+2 day');
                            }

                            $intDiasCesantias = $this->diasPrestaciones($fechaDesdeCesantias, $dateFechaHasta);
                            if ($arContrato->getCodigoSalarioTipoFk() == 'VAR') {
                                if ($intDiasCesantias > 0) {
                                    $salarioPromedioCesantias = ($ibpCesantias / $intDiasCesantias) * 30;
                                }
                                //Configuracion especifica para grtemporales
                                if ($arConfiguracion->getAuxilioTransporteNoPrestacional()) {
                                    if ($arContrato->getAuxilioTransporte() == 1) {
                                        $salarioPromedioCesantias += $auxilioTransporte;
                                    }
                                }
                            } else {
                                if ($arContrato->getAuxilioTransporte() == 1) {
                                    $salarioPromedioCesantias = $douSalario + $auxilioTransporte;
                                } else {
                                    $salarioPromedioCesantias = $douSalario;
                                }
                            }
                            if ($arLiquidacion->getPorcentajeIbp() > 0) {
                                $salarioPromedioCesantias = ($salarioPromedioCesantias * $arLiquidacion->getPorcentajeIbp()) / 100;
                            }
                            //Liquidar solo al salario
                            if ($arLiquidacion->getLiquidarSalario() == true) {
                                if ($arContrato->getAuxilioTransporte() == 1) {
                                    $salarioPromedioCesantias = $douSalario + $auxilioTransporte;
                                } else {
                                    $salarioPromedioCesantias = $douSalario;
                                }
                            }

                            //Liquidar con salario y suplementario
                            if ($arConfiguracion->getLiquidarPrestacionesSalarioSuplementario()) {
                                $suplementario = $em->getRepository(RhuPagoDetalle::class)->ibpSuplementario($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arLiquidacion->getCodigoContratoFk());
                                $suplementacioAdicional = $em->getRepository(RhuLiquidacionAdicional::class)->ibpSuplementario($arLiquidacion);
                                $suplementario = $suplementacioAdicional + $suplementario;
                                $suplementarioPromedio = 0;
                                $intDiasPeriodoSuplementarioCesantia = $intDiasCesantias;
                                if ($intDiasPeriodoSuplementarioCesantia > 0) {
                                    $suplementarioPromedio = ($suplementario / $intDiasPeriodoSuplementarioCesantia) * 30;
                                }
                                $salarioPromedioCesantias = $arContrato->getVrSalarioPago() + $suplementarioPromedio;
                                if ($arContrato->getAuxilioTransporte()) {
                                    $salarioPromedioCesantias += $auxilioTransporte;
                                }
                                $arLiquidacion->setVrSuplementarioCesantias($suplementarioPromedio);
                            }


                            //No se puede liquidar por menos del minimo
                            $salarioPromedioMinimo = $salarioMinimo;
                            if ($arContrato->getAuxilioTransporte() == 1) {
                                $salarioPromedioMinimo += $auxilioTransporte;
                            }
                            if ($salarioPromedioCesantias < $salarioPromedioMinimo) {
                                if ($arContrato->getCodigoTiempoFk() == 'TCOMP') {
                                    $salarioPromedioCesantias = $salarioPromedioMinimo;
                                }
                            }

                            if ($arLiquidacion->getVrSalarioCesantiasPropuesto() > 0) {
                                $salarioPromedioCesantias = $arLiquidacion->getVrSalarioCesantiasPropuesto();
                            }
                            $salarioPromedioCesantias = round($salarioPromedioCesantias);
                            $intDiasCesantias = $intDiasCesantias - $intDiasAusentismo;
                            $douCesantias = ($salarioPromedioCesantias * $intDiasCesantias) / 360;
                            $douCesantias = round($douCesantias);
                            $floPorcentajeIntereses = (($intDiasCesantias * 12) / 360) / 100;
                            $douInteresesCesantias = $douCesantias * $floPorcentajeIntereses;
                            if ($arLiquidacion->getVrSalarioCesantiasPropuesto() > 0 && $arConfiguracion->getLiquidarInteresCesantiaPropuesto()) {
                                $douInteresesCesantias = $salarioPromedioCesantias * $floPorcentajeIntereses;
                            }
                            $douInteresesCesantias = round($douInteresesCesantias);
                            if ($arLiquidacion->getVrInteresesPropuesto()) {
                                if ($arLiquidacion->getVrInteresesPropuesto() > 0) {
                                    $douInteresesCesantias = $arLiquidacion->getVrInteresesPropuesto();
                                }
                            }
                            $arLiquidacion->setFechaUltimoPagoCesantias($dateFechaDesde);
                            $arLiquidacion->setDiasCesantias($intDiasCesantias);
                            $arLiquidacion->setPorcentajeInteresesCesantias($floPorcentajeIntereses);
                            $arLiquidacion->setVrCesantias($douCesantias);
                            $arLiquidacion->setVrInteresesCesantias($douInteresesCesantias);
                            $arLiquidacion->setVrIngresoBasePrestacionCesantias($ibpCesantias);
                            $arLiquidacion->setVrIngresoBasePrestacionCesantiasInicial($ibpCesantiasInicial);
                            $arLiquidacion->setVrSalarioPromedioCesantias($salarioPromedioCesantias);
                            $arLiquidacion->setDiasCesantiasAusentismo($intDiasAusentismo);
                        } else {
                            $arLiquidacion->setDiasCesantias(0);
                            $arLiquidacion->setVrCesantias(0);
                            $arLiquidacion->setVrInteresesCesantias(0);
                            $arLiquidacion->setVrIngresoBasePrestacionCesantias(0);
                            $arLiquidacion->setVrIngresoBasePrestacionCesantiasInicial(0);
                            $arLiquidacion->setVrSalarioPromedioCesantias(0);
                            $arLiquidacion->setDiasCesantiasAusentismo(0);
                            $arLiquidacion->setFechaUltimoPagoCesantias($arLiquidacion->getContratoRel()->getFechaUltimoPagoCesantias());
                            $arLiquidacion->setFechaUltimoPagoCesantiasAnterior($arLiquidacion->getContratoRel()->getFechaUltimoPagoCesantias());
                        }
                    } else {
                        $arLiquidacion->setDiasCesantias(0);
                        $arLiquidacion->setVrCesantias(0);
                        $arLiquidacion->setVrInteresesCesantias(0);
                        $arLiquidacion->setVrIngresoBasePrestacionCesantias(0);
                        $arLiquidacion->setVrIngresoBasePrestacionCesantiasInicial(0);
                        $arLiquidacion->setVrSalarioPromedioCesantias(0);
                        $arLiquidacion->setDiasCesantiasAusentismo(0);
                        $arLiquidacion->setFechaUltimoPagoCesantias($arLiquidacion->getContratoRel()->getFechaUltimoPagoCesantias());
                        $arLiquidacion->setFechaUltimoPagoCesantiasAnterior($arLiquidacion->getContratoRel()->getFechaUltimoPagoCesantias());
                    }
                } else {
                    //CESANTIAS
                    $arLiquidacion->setDiasCesantias(0);
                    $arLiquidacion->setVrCesantias(0);
                    $arLiquidacion->setVrInteresesCesantias(0);
                    $arLiquidacion->setVrIngresoBasePrestacionCesantias(0);
                    $arLiquidacion->setVrIngresoBasePrestacionCesantiasInicial(0);
                    $arLiquidacion->setVrSalarioPromedioCesantias(0);
                    $arLiquidacion->setDiasCesantiasAusentismo(0);
                    $arLiquidacion->setFechaUltimoPagoCesantias($arLiquidacion->getContratoRel()->getFechaUltimoPagoCesantias());
                    ///CESANTIAS ANTERIOR
                    $arLiquidacion->setDiasCesantiasAnterior(0);
                    $arLiquidacion->setVrCesantiasAnterior(0);
                    $arLiquidacion->setVrInteresesCesantiasAnterior(0);
                    $arLiquidacion->setDiasCesantiasAusentismoAnterior(0);
                    $arLiquidacion->setVrSalarioPromedioCesantiasAnterior(0);
                    $arLiquidacion->setFechaUltimoPagoCesantiasAnterior($arLiquidacion->getContratoRel()->getFechaUltimoPagoCesantias());
                }

                //Liquidar primas
                if ($arLiquidacion->getLiquidarPrima() == 1) {
                    $dateFechaDesde = $arLiquidacion->getContratoRel()->getFechaUltimoPagoPrimas();
                    $dateFechaHasta = $arLiquidacion->getContratoRel()->getFechaHasta();
                    $intDiasPrima = 0;
//                    if ($dateFechaDesde == $dateFechaHasta) {
//                        $intDiasPrimaLiquidar = 0;
//                    }
                    if ($dateFechaDesde <= $dateFechaHasta) {
                        // No quitar por favor, esto tiene su logica, ingreso del dia 30dic
                        $fechaDesdePrimas = date_create($dateFechaDesde->format('Y-m-d'));
                        if ($fechaDesdePrimas > $arLiquidacion->getContratoRel()->getFechaDesde()) {
                            if ($fechaDesdePrimas->format('m-d') == '06-30') {
                                $fechaDesdePrimas->modify('+1 day');
                            } else {
                                $fechaDesdePrimas->modify('+2 day');
                            }
                        }
                        $intDiasPrima = $this->diasPrestaciones($fechaDesdePrimas, $dateFechaHasta);
                        $intDiasPrimaLiquidar = $intDiasPrima;
                        if ($dateFechaDesde->format('m-d') == '06-30') {
                            //if($dateFechaHasta->format('m-d') != '01-01') {
//                            $intDiasPrimaLiquidar = $intDiasPrimaLiquidar - 1;
                            //}
                        }
                        $ibpPrimasInicial = $arContrato->getIbpPrimasInicial();
                        $ibpPrimasInicial = round($ibpPrimasInicial);
                        $ibpPrimas = $em->getRepository(RhuPagoDetalle::class)->ibp($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arLiquidacion->getCodigoContratoFk());
                        $ibpPrimas += $ibpPrimasInicial + $douIBPAdicional;
                        $ibpPrimas = round($ibpPrimas);
                        if ($arContrato->getCodigoSalarioTipoFk() == 'VAR') {
                            if ($intDiasPrimaLiquidar > 0) {
                                $salarioPromedioPrimas = ($ibpPrimas / $intDiasPrimaLiquidar) * 30;
                                //Configuracion especifica para grtemporales
                                if ($arConfiguracion->getAuxilioTransporteNoPrestacional()) {
                                    if ($arConfiguracion->getLiquidarAuxilioTransportePrima()) {
                                        $salarioPromedioPrimas += $auxilioTransporte;
                                    }
                                }
                            } else {
                                $salarioPromedioPrimas = 0;
                            }
                        } else {
                            if ($arContrato->getAuxilioTransporte() == 1) {
                                $salarioPromedioPrimas = $douSalario + $auxilioTransporte;
                            } else {
                                $salarioPromedioPrimas = $douSalario;
                            }
                        }
                        if ($arLiquidacion->getPorcentajeIbp() > 0) {
                            $salarioPromedioPrimas = ($salarioPromedioPrimas * $arLiquidacion->getPorcentajeIbp()) / 100;
                        }
                        if ($arLiquidacion->getLiquidarSalario() == true) {
                            if ($arContrato->getAuxilioTransporte() == 1) {
                                $salarioPromedioPrimas = $douSalario + $auxilioTransporte;
                            } else {
                                $salarioPromedioPrimas = $douSalario;
                            }
                        }

                        $diasAusentismo = 0;
                        if ($arConfiguracion->getDescontarAusentismosDeLicencias()) {
                            $diasAusentismo = $em->getRepository(RhuNovedad::class)->diasAusentismoMovimiento($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arLiquidacion->getCodigoContratoFk());
                        } else {
                            $diasAusentismo = $em->getRepository(RhuPago::class)->diasAusentismo($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arContrato->getCodigoContratoPk());
                        }


                        // validacion para descontar todos los ausentimos durante la vigencia del contrato
//                        if ($arConfiguracion->getDescontarTotalAusentismosContratoTerminadoEnLiquidacion()) {
//                            $diasAusentismo = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->diasAusentismo($arLiquidacion->getContratoRel()->getFechaDesde()->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arLiquidacion->getCodigoContratoFk());
//                        }
                        $diasAusentismo += $arLiquidacion->getDiasAusentismoAdicional();
                        if ($arLiquidacion->getDiasAusentismoPropuestoPrimas()) {
                            $diasAusentismo = $arLiquidacion->getDiasAusentismoPropuestoPrimas();
                        }
                        if ($arLiquidacion->getEliminarAusentismo() > 0) {
                            $diasAusentismo = 0;
                        }
                        if ($arLiquidacion->getEliminarAusentismoPrima() > 0) {
                            $diasAusentismo = 0;
                        }

                        //Liquidar con salario y suplementario
                        if ($arConfiguracion->getLiquidarPrestacionesSalarioSuplementario()) {
                            $suplementario = $em->getRepository(RhuPagoDetalle::class)->ibpSuplementario($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arLiquidacion->getCodigoContratoFk());
                            $suplementacioAdicional = $em->getRepository(RhuLiquidacionAdicional::class)->ibpSuplementario($arLiquidacion);
                            $suplementario = $suplementacioAdicional + $suplementario;
                            $suplementarioPromedio = 0;
                            $intDiasPeriodoSuplementarioPrima = $intDiasPrimaLiquidar;
                            if ($intDiasPeriodoSuplementarioPrima > 0) {
                                $suplementarioPromedio = ($suplementario / $intDiasPeriodoSuplementarioPrima) * 30;
                            }
                            $salarioPromedioPrimas = $arContrato->getVrSalarioPago() + $suplementarioPromedio;
                            if ($arContrato->getAuxilioTransporte()) {
                                $salarioPromedioPrimas += $auxilioTransporte;
                            }
                            $arLiquidacion->setVrSuplementarioPrimas($suplementarioPromedio);
                        }

                        $salarioPromedioMinimo = $salarioMinimo;
                        if ($arContrato->getAuxilioTransporte() == 1) {
                            $salarioPromedioMinimo += $auxilioTransporte;
                        }
                        if ($salarioPromedioPrimas < $salarioPromedioMinimo) {
                            if ($arContrato->getCodigoTiempoFk() == 'TCOMP') {
                                $salarioPromedioPrimas = $salarioPromedioMinimo;
                            }
                        }
                        if ($arLiquidacion->getVrSalarioPrimaPropuesto() > 0) {
                            $salarioPromedioPrimas = $arLiquidacion->getVrSalarioPrimaPropuesto();
                        }

                        $diasPrimaLiquidarFinal = $intDiasPrimaLiquidar - $diasAusentismo;
                        $salarioPromedioPrimas = round($salarioPromedioPrimas);
                        $douPrima = ($salarioPromedioPrimas * $diasPrimaLiquidarFinal) / 360;
                        $douPrima = round($douPrima);
                        $arLiquidacion->setDiasPrima($diasPrimaLiquidarFinal);
                        $arLiquidacion->setDiasPrimaAusentismo($diasAusentismo);
                        $arLiquidacion->setVrPrima($douPrima);
                        $arLiquidacion->setVrIngresoBasePrestacionPrimas($ibpPrimas);
                        $arLiquidacion->setVrIngresoBasePrestacionPrimasInicial($ibpPrimasInicial);
                        $arLiquidacion->setVrSalarioPromedioPrimas($salarioPromedioPrimas);
                    } else {
                        $arLiquidacion->setDiasPrimas(0);
                        $arLiquidacion->setVrPrima(0);
                        $arLiquidacion->setVrIngresoBasePrestacionPrimas(0);
                        $arLiquidacion->setVrIngresoBasePrestacionPrimasInicial(0);
                        $arLiquidacion->setVrSalarioPromedioPrimas(0);
                        $arLiquidacion->setFechaUltimoPagoPrima($arLiquidacion->getContratoRel()->getFechaUltimoPagoPrimas());
                    }
                    $arLiquidacion->setFechaUltimoPagoPrima($arLiquidacion->getContratoRel()->getFechaUltimoPagoPrimas());
                } else {
                    $arLiquidacion->setDiasPrimas(0);
                    $arLiquidacion->setVrPrima(0);
                    $arLiquidacion->setVrIngresoBasePrestacionPrimas(0);
                    $arLiquidacion->setVrIngresoBasePrestacionPrimasInicial(0);
                    $arLiquidacion->setVrSalarioPromedioPrimas(0);
                    $arLiquidacion->setFechaUltimoPagoPrimas($arLiquidacion->getContratoRel()->getFechaUltimoPagoPrimas());
                }

                //Liquidar devolucion prima
                if ($arLiquidacion->getFechaHasta() < $arContrato->getFechaUltimoPagoPrimas()) {
                    $intDiasDeduccionPrima = $arLiquidacion->getFechaHasta()->diff($arContrato->getFechaUltimoPagoPrimas());
                    $intDiasDeduccionPrima = $intDiasDeduccionPrima->format('%d');
                    $arProgramacionPagoDetalle = $em->getRepository(RhuProgramacionDetalle::class)->pagoPrimaDeduccion($arLiquidacion->getCodigoEmpleadoFk(), 2);
                    if ($arProgramacionPagoDetalle) {
                        $diasRealesPrima = $arProgramacionPagoDetalle['diasReales'];
                        $vrNetoPagoPrima = $arProgramacionPagoDetalle['vrNetoPagar'];
                        $vrDiaPrima = $vrNetoPagoPrima / $diasRealesPrima;
                        $vrDeduccionPrima = $intDiasDeduccionPrima * $vrDiaPrima;
                    }

                    if ($arLiquidacion->getVrDeduccionPrimaPropuesto() > 0) {
                        $vrDeduccionPrima = $arLiquidacion->getVrDeduccionPrimaPropuesto();
                    }
                    $arLiquidacion->setVrDeduccionPrima($vrDeduccionPrima);
                    $arLiquidacion->setDiasDeduccionPrimas($intDiasDeduccionPrima);
                } else {
                    $arLiquidacion->setVrDeduccionPrima(0);
                    $arLiquidacion->setDiasDeduccionPrimas(0);
                }

                //Liquidar vacaciones
                if ($arLiquidacion->getContratoRel()->getFechaUltimoPagoVacaciones() <= $arLiquidacion->getFechaHasta()) {
                    if ($arLiquidacion->getLiquidarVacaciones() == 1) {
                        $dateFechaDesde = $arLiquidacion->getContratoRel()->getFechaUltimoPagoVacaciones();
                        $dateFechaHasta = $arLiquidacion->getContratoRel()->getFechaHasta();
                        $recargosNocturnos = $em->getRepository(RhuPagoDetalle::class)->recargosNocturnosFecha($dateFechaDesde, $dateFechaHasta, $arLiquidacion->getContratoRel()->getCodigoContratoPk());
                        $intDiasVacaciones = $this->diasPrestaciones($dateFechaDesde, $dateFechaHasta);
                        //TAMBIEN LO DEBE HACER CON CUALQUIER TIPO DE TERMINACION DE CONTRATO.
                        if ($arLiquidacion->getCodigoContratoMotivoFk() == 'CJC' || $arLiquidacion->getCodigoContratoMotivoFk() == 'SJC' || $arConfiguracion->getLiquidarVacacionesSalarioPromedioCesantias()) {
                            if ($arContrato->getAuxilioTransporte() == 1) {
                                $salarioVacaciones = $salarioPromedioCesantias - $auxilioTransporte;
                            } else {
                                $salarioVacaciones = $salarioPromedioCesantias;
                            }
                            if ($arContrato->getSalarioIntegral()) {
                                $salarioVacaciones = $arContrato->getVrSalario();
                            }
                        } else {
                            $salarioVacaciones = $arContrato->getVrSalario();//El artículo 192 del código sustantivo del trabajo claramente dice que las vacaciones se remuneran con base al salario que se esté devengado el día en que se esta liquidando las vacaciones
                        }
                        if ($arConfiguracion->getLiquidarVacacionesSalario()) {
                            $salarioVacaciones = $arContrato->getVrSalario();
                        }

                        //LIQUIDAR SALARIO VACACIONES, CON SALARIO, RECARGOS Y PRESTACIONES.
                        if ($arConfiguracion->getLiquidarVacacionesSalarioRecargoPrestacion()) {
                            $prestacional = $em->getRepository(RhuPagoDetalle::class)->adicionalPrestacional("", $arLiquidacion->getContratoRel()->getCodigoContratoPk());
                            $licencias = $em->getRepository(RhuPagoDetalle::class)->valorLicenciaPago("", $arLiquidacion->getContratoRel()->getCodigoContratoPk());
                            $incapacidades = $em->getRepository(RhuPagoDetalle::class)->valorIncapacidadPago("", $arLiquidacion->getContratoRel()->getCodigoContratoPk());
                            $salario = $em->getRepository(RhuPagoDetalle::class)->valorSalarioPago("", $arLiquidacion->getContratoRel()->getCodigoContratoPk());
                            $salarioVacaciones = $salario + $licencias + $incapacidades + $prestacional + $recargosNocturnos;
                            $salarioVacaciones = ($salarioVacaciones / $intDiasVacaciones) * 30;
                            if ($salarioVacaciones < $arContrato->getVrSalario()) {
                                $salarioVacaciones = $arContrato->getVrSalario();
                            }
                        }

                        if ($arConfiguracion->getDescontarAusentismosDeLicencias()) {
                            $intDiasAusentismo = $em->getRepository(RhuNovedad::class)->diasAusentismoMovimiento($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arLiquidacion->getCodigoContratoFk());
                        } else {
                            $intDiasAusentismo = $em->getRepository(RhuPago::class)->diasAusentismo($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arLiquidacion->getCodigoContratoFk());
                        }


                        // validacion para descontar todos los ausentimos durante la vigencia del contrato
                        if ($arConfiguracion->getDescontarTotalAusentismosContratoTerminadoEnLiquidacion()) {
                            $intDiasAusentismo = $em->getRepository(RhuPago::class)->diasAusentismo($arLiquidacion->getContratoRel()->getFechaDesde()->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arLiquidacion->getCodigoContratoFk());
                        }
                        $intDiasAusentismo += $arLiquidacion->getDiasAusentismoAdicional();

                        if ($arLiquidacion->getDiasAusentismoPropuestoVacaciones()) {
                            $intDiasAusentismo = $arLiquidacion->getDiasAusentismoPropuestoVacaciones();
                        }
                        if ($arLiquidacion->getEliminarAusentismo() > 0) {
                            $intDiasAusentismo = 0;
                        }
                        if ($arLiquidacion->getEliminarAusentismoVacacion() > 0) {
                            $intDiasAusentismo = 0;
                        }
                        $intDiasVacacionesLiquidar = $intDiasVacaciones - $intDiasAusentismo;
                        if ($arContrato->getCodigoTiempoFk() == 'TSAB') {
                            $ibpSalario = $em->getRepository(RhuPago::class)->ibpSalario($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arLiquidacion->getCodigoContratoFk());
                            $salarioVacaciones = $ibpSalario;
                        }
                        if ($arConfiguracion->getLiquidarVacacionesPromedioUltimoAnio()) {
                            $fechaActual = date_create($dateFechaHasta->format('Y-m-d'));
                            $fechaInicio = date_create($dateFechaHasta->format('Y-m-d'));
                            date_add($fechaInicio, date_interval_create_from_date_string('-364 days'));
                            if ($fechaInicio < $dateFechaDesde) {
                                $fechaInicio = $dateFechaDesde;
                            }
                            $intDias = $fechaInicio->diff($fechaActual);
                            $intDias = $intDias->format('%a') - 1;

                            //Fecha ultimo anio
                            $fechaHastaUltimoAnio = date_create($arLiquidacion->getFechaHasta()->format('Y-m-d'));
                            $fechaDesdeUltimoAnio = date_create($fechaActual->format('Y-m-d'));
                            date_add($fechaDesdeUltimoAnio, date_interval_create_from_date_string('-365 days'));
                            if ($fechaDesdeUltimoAnio < $arLiquidacion->getContratoRel()->getFechaDesde()) {
                                $fechaDesdeUltimoAnio = $arLiquidacion->getContratoRel()->getFechaDesde();
                            }

                            $diasPeriodoSuplementario = $objFunciones->diasPrestaciones($fechaDesdeUltimoAnio, $fechaHastaUltimoAnio);
                            if ($diasPeriodoSuplementario > 360) {
                                $diasPeriodoSuplementario--;
                            }
                            if ($arConfiguracion->getLiquidarPrestacionesSalarioSuplementario()) {
                                $intDiasVacacionesPrestacionales = $this->diasPrestaciones($fechaInicio, $fechaActual);
                                $suplementario = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->ibpSuplementarioVacaciones($fechaDesdeUltimoAnio->format('Y-m-d'), $fechaHastaUltimoAnio->format('Y-m-d'), $arLiquidacion->getContratoRel()->getCodigoContratoPk());
                                $suplementarioAdicionalVacacion = $em->getRepository("BrasaRecursoHumanoBundle:RhuLiquidacionAdicionales")->ibpSuplementarioVacaciones($arLiquidacion);
                                $suplementario = $suplementarioAdicionalVacacion + $suplementario;
                                $suplementarioPromedio = 0;
                                if ($intDiasVacacionesPrestacionales > 0 && $diasPeriodoSuplementario > 0) {
                                    $suplementarioPromedio = ($suplementario / $diasPeriodoSuplementario) * 30;
                                }

                                $salarioVacaciones = $arContrato->getVrSalario() + $suplementarioPromedio;
                                $arLiquidacion->setVrSuplementarioVacaciones($suplementarioPromedio);
                            }
                        }

                        //Recargos nocturnos sobre el porcentaje de vacaciones que tiene el concepto
                        if ($arConfiguracion->getVacacionesLiquidarRecargoNocturnoPorcentajeConcepto()) {
                            if ($arConfiguracion->getVacacionesRecargoNocturnoUltimoAnio()) {
                                //$fechaHastaVacaciones = "";
                                // se valida si la fecha del ultimo pago es superior a la fecha fin del contrato,
                                // ya que la fecha hastaPago de los pagos queda con la fecha hasta de la programacion de pago
                                // y se necesita que incluya el ultimo pago para el calculo del recargo nocturno
                                if ($arLiquidacion->getContratoRel()->getFechaUltimoPago() > $dateFechaHasta) {
                                    $fechaHastaVacaciones = $arLiquidacion->getContratoRel()->getFechaUltimoPago()->format('Y-m-d');
                                } else {
                                    $fechaHastaVacaciones = $arLiquidacion->getFechaHasta()->format('Y-m-d');
                                }
                                $fechaHastaUltimoAnio = date_create($fechaHastaVacaciones);
                                $fechaDesdeUltimoAnio = date_create($arLiquidacion->getFechaHasta()->format('Y-m-d'));
//                                $fechaDesdeUltimoAnio = date_create($arLiquidacion->getFechaHasta()->format('Y-m-d'));
                                date_add($fechaDesdeUltimoAnio, date_interval_create_from_date_string('-365 days'));
                                if ($fechaDesdeUltimoAnio < $arLiquidacion->getFechaDesde()) {
                                    $fechaDesdeUltimoAnio = $arLiquidacion->getFechaDesde();
                                }
                                $intDiasVacacionesRecargo = $this->diasPrestaciones($dateFechaDesde, $dateFechaHasta);
                                $intDiasVacacionesRecargo = $intDiasVacacionesRecargo - $intDiasAusentismo;
                                $recargosNocturnos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->recargosNocturnosIbp($fechaDesdeUltimoAnio->format('Y-m-d'), $fechaHastaUltimoAnio->format('Y-m-d'), $arContrato->getCodigoContratoPk());
                                // calcular el promedio mensual de recargos nocturnos
                                if ($intDiasVacacionesRecargo > 360) {
                                    $recargosNocturnos = $recargosNocturnos / 360 * 30;
                                } else {
                                    $recargosNocturnos = $recargosNocturnos / $intDiasVacacionesRecargo * 30;
                                }
                                $salarioVacaciones = $arContrato->getVrSalario() + $recargosNocturnos;
                            } else {
                                $intDiasVacacionesRecargo = $this->diasPrestaciones($dateFechaDesde, $dateFechaHasta);
                                $intDiasVacacionesRecargo = $intDiasVacacionesRecargo - $intDiasAusentismo;
                                $recargosNocturnos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->recargosNocturnosIbp($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arContrato->getCodigoContratoPk());
                                $salarioVacaciones = $arContrato->getVrSalario() + ($recargosNocturnos / $intDiasVacacionesRecargo * 30);
                            }
                        }


                        if ($arLiquidacion->getVrSalarioVacacionPropuesto() > 0) {
                            $salarioVacaciones = $arLiquidacion->getVrSalarioVacacionPropuesto();
                        }
                        $salarioVacaciones = round($salarioVacaciones);
                        $douVacaciones = ($salarioVacaciones * $intDiasVacacionesLiquidar) / 720;
                        $douVacaciones = round($douVacaciones);
                        $arLiquidacion->setVrSalarioVacaciones($salarioVacaciones);
                        $arLiquidacion->setDiasVacacion($intDiasVacacionesLiquidar);
                        $arLiquidacion->setDiasVacacionAusentismo($intDiasAusentismo);
                        $arLiquidacion->setVrVacacion($douVacaciones);
                        $arLiquidacion->setFechaUltimoPagoVacacion($arLiquidacion->getContratoRel()->getFechaUltimoPagoVacaciones());
                    }
                } else {
                    $arLiquidacion->setVrSalarioVacaciones(0);
                    $arLiquidacion->setDiasVacaciones(0);
                    $arLiquidacion->setDiasVacacionesAusentismo(0);
                    $arLiquidacion->setVrVacaciones(0);
                }
            } else {
                $douCesantias = $arLiquidacion->getVrCesantias();
                $douInteresesCesantias = $arLiquidacion->getVrInteresesCesantias();
                $douPrima = $arLiquidacion->getVrPrima();
                $douVacaciones = $arLiquidacion->getVrVacaciones();

                $arLiquidacion->setFechaUltimoPago($arContrato->getFechaUltimoPago());
                $arLiquidacion->setFechaUltimoPagoCesantias($arContrato->getFechaUltimoPagoCesantias());
                $arLiquidacion->setFechaUltimoPagoPrimas($arContrato->getFechaUltimoPagoPrimas());
                $arLiquidacion->setFechaUltimoPagoVacaciones($arContrato->getFechaUltimoPagoVacaciones());
            }
            $floAdicionales = 0;
            $floDeducciones = 0;

            //Liquidar los embargos si el empleado tiene embargos
            $this->liquidarEmbargos($arLiquidacion);
            $arLiquidacionAdicionales = $em->getRepository(RhuLiquidacionAdicional::class)->FindBy(array('codigoLiquidacionFk' => $codigoLiquidacion));
            foreach ($arLiquidacionAdicionales as $arLiquidacionAdicional) {
                $floDeducciones += $arLiquidacionAdicional->getVrDeduccion();
                $floAdicionales += $arLiquidacionAdicional->getVrBonificacion();
            }
            $floDeducciones = round($floDeducciones);
            $floAdicionales = round($floAdicionales);
            $floDeduccionPrimas = round($vrDeduccionPrima);
            $douTotal = $douCesantias + $douInteresesCesantias + ($cesantiaAnterior + $interesCesantiaAnterior) + $douPrima + $douVacaciones + $arLiquidacion->getVrIndemnizacion();
            $douTotal = $douTotal + $floAdicionales - $douAdicionalesPrima - $floDeducciones - $floDeduccionPrimas;
            $douTotal = round($douTotal);
            $arLiquidacion->setVrTotal($douTotal);
            $arLiquidacion->setVrSalario($arContrato->getVrSalario());
            $arLiquidacion->setVrDeducciones($floDeducciones);
            $arLiquidacion->setVrBonificaciones($floAdicionales);
            $arLiquidacion->setNumeroDias($intDiasLaborados);
            $arLiquidacion->setFechaInicioContrato($arContrato->getFechaDesde());
            $arLiquidacion->setFechaDesde($arContrato->getFechaDesde());
            $arLiquidacion->setFechaHasta($arContrato->getFechaHasta());
            $arLiquidacion->setMotivoTerminacionRel($arContrato->getContratoMotivoRel());
            $em->persist($arLiquidacion);
        }

        return $respuesta;
    }

    /**
     * @param $arLiquidacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arLiquidacion)
    {
        $em = $this->getEntityManager();
        if ($arLiquidacion->getEstadoAutorizado()) {
            $arLiquidacion->setEstadoAutorizado(0);
            $em->persist($arLiquidacion);
            $em->flush();
        }
    }

    /**
     * @param $arLiquiracion RhuLiquidacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arLiquiracion)
    {
        $em = $this->getEntityManager();
        if ($arLiquiracion->getEstadoAutorizado() == 1) {
            if ($arLiquiracion->getEstadoAprobado() == 0) {
                $arLiquiracion->setEstadoAprobado(1);
                $em->persist($arLiquiracion);
                $em->flush();
            } else {
                Mensajes::error('La visita ya esta aprobada');
            }

        } else {
            Mensajes::error('La visita ya esta desautorizada');
        }
    }

    public function liquidarEmbargos($arLiquidacion)
    {
        $em = $this->getEntityManager();
        $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
        $douVrSalarioMinimo = $arConfiguracion->getVrSalarioMinimo();
        $em->getRepository(RhuLiquidacionAdicional::class)->eliminarEmbargosLiquidacion($arLiquidacion->getCodigoLiquidacionPk());
        /** @var  $arEmbargo RhuEmbargo */
        $arEmbargos = $em->getRepository(RhuEmbargo::class)->listaEmbargo($arLiquidacion->getCodigoEmpleadoFk(), 1, 0, 0, 0);
        foreach ($arEmbargos as $arEmbargo) {
            $vrDeduccionEmbargo = 0;
            $vrPago = 0;
            if ($arEmbargo->getAfectaVacacion()) {
                $vrPago += $arLiquidacion->getVrVacaciones();
            }
            if ($arEmbargo->getAfectaPrima()) {
                $vrPago += $arLiquidacion->getVrPrima();
            }
            if ($arEmbargo->getAfectaCesantia()) {
                $vrPago += $arLiquidacion->getVrCesantias();
            }
            if ($arEmbargo->getAfectaIndemnizacion()) {
                $vrPago += $arLiquidacion->getVrIndemnizacion();
            }
            //Calcular la deduccion del embargo
            if ($arEmbargo->getValorFijo()) {
                $vrDeduccionEmbargo = $arEmbargo->getValor();
            }
            $boolPorcentaje = $arEmbargo->getPorcentajeDevengado() || $arEmbargo->getPorcentajeDevengadoPrestacional() || $arEmbargo->getPorcentajeDevengadoPrestacionalMenosDescuentoLey() || $arEmbargo->getPorcentajeDevengadoPrestacionalMenosDescuentoLeyTransporte() || $arEmbargo->getPorcentajeDevengadoMenosDescuentoLey() || $arEmbargo->getPorcentajeDevengadoMenosDescuentoLeyTransporte();
            if ($boolPorcentaje) {
                $vrDeduccionEmbargo = ($vrPago * $arEmbargo->getPorcentaje()) / 100;
                $vrDeduccionEmbargo = round($vrDeduccionEmbargo);
            }
            if ($arEmbargo->getPorcentajeSalarioMinimo()) {
                if ($douVrSalarioMinimo > 0) {
                    $vrDeduccionEmbargo = ($douVrSalarioMinimo * $arEmbargo->getPorcentaje()) / 100;
                    $vrDeduccionEmbargo = round($vrDeduccionEmbargo);
                }
            }
            if ($arEmbargo->getValidarMontoMaximo()) {
                $saldo = $arEmbargo->getVrMontoMaximo() - $arEmbargo->getDescuento();
                if ($saldo > 0) {
                    if ($saldo < $vrDeduccionEmbargo) {
                        $vrDeduccionEmbargo = round($saldo);
                    }
                } else {
                    $vrDeduccionEmbargo = 0;
                }
            }
            //Aqui se registra la deduccion del embargo en la liquidacion adicionales
            if ($vrDeduccionEmbargo > 0) {
                $arLiquidacionAdicionales = new RhuLiquidacionAdicional();
                $arLiquidacionAdicionales->setConceptoRel($arEmbargo->getEmbargoTipoRel()->getConceptoRel());
                $arLiquidacionAdicionales->setEmbargoRel($arEmbargo);
                $arLiquidacionAdicionales->setLiquidacionRel($arLiquidacion);
                $arLiquidacionAdicionales->setVrDeduccion($vrDeduccionEmbargo);
                $em->persist($arLiquidacionAdicionales);
            }
        }
    }

    /**
     * @param $arr
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function contabilizar($arr): bool
    {
        /**
         * @var $arLiquidacion RhuLiquidacion
         */
        $em = $this->getEntityManager();
        if ($arr) {
            $error = "";
            $arCentroCosto = null;
            $arCuenta = $em->getRepository(RhuConfiguracionCuenta::class)->find(1);
            $codigoCuentaCesantias = $arCuenta->getCodigoCuentaFk();
            $arCuenta = $em->getRepository(RhuConfiguracionCuenta::class)->find(2);
            $codigoCuentaInteresesCesantias = $arCuenta->getCodigoCuentaFk();
            //Cesantias año anterior
            $arCuenta = $em->getRepository(RhuConfiguracionCuenta::class)->find(17);
            $codigoCuentaCesantiasAnterior = $arCuenta->getCodigoCuentaFk();
            $arCuenta = $em->getRepository(RhuConfiguracionCuenta::class)->find(18);
            $codigoCuentaInteresesCesantiasAnterior = $arCuenta->getCodigoCuentaFk();

            $arCuenta = $em->getRepository(RhuConfiguracionCuenta::class)->find(3);
            $codigoCuentaPrimas = $arCuenta->getCodigoCuentaFk();
            $arCuenta = $em->getRepository(RhuConfiguracionCuenta::class)->find(4);
            $codigoCuentaVacaciones = $arCuenta->getCodigoCuentaFk();
            $arCuenta = $em->getRepository(RhuConfiguracionCuenta::class)->find(6);
            $codigoCuentaIndemnizacion = $arCuenta->getCodigoCuentaFk();
            $arCuenta = $em->getRepository(RhuConfiguracionCuenta::class)->find(5);
            $codigoCuentaLiquidacion = $arCuenta->getCodigoCuentaFk();
            $arCuenta = $em->getRepository(RhuConfiguracionCuenta::class)->find(19);
            $codigoCuentaDeduccionPrima = $arCuenta->getCodigoCuentaFk();
            $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
            $arComprobanteContable = $em->getRepository(FinComprobante::class)->find($arConfiguracion->getCodigoComprobanteLiquidacion());
            foreach ($arr AS $codigo) {
                $arLiquidacion = $em->getRepository(RhuLiquidacion::class)->find($codigo);
                if ($arLiquidacion) {
                    if ($arLiquidacion->getEstadoAprobado() == 1 && $arLiquidacion->getEstadoContabilizado() == 0) {
                        $arTercero = $em->getRepository(RhuEmpleado::class)->terceroFinanciero($arLiquidacion->getCodigoEmpleadoFk());

                        //Cesantias
                        if ($arLiquidacion->getVrCesantias() > 0) {
                            $arCuenta = $em->getRepository(FinCuenta::class)->find($codigoCuentaCesantias);
                            if ($arCuenta) {
                                $arRegistro = new FinRegistro();
                                $arRegistro->setComprobanteRel($arComprobanteContable);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setNumero($arLiquidacion->getNumero());
                                $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                $arRegistro->setFecha($arLiquidacion->getFecha());
                                $arRegistro->setVrDebito($arLiquidacion->getVrCesantias());
                                $arRegistro->setNaturaleza("D");
                                $arRegistro->setDescripcion('CESANTIAS');
                                $arRegistro->setCodigoModeloFk('RhuLiquidacion');
                                $arRegistro->setCodigoDocumento($arLiquidacion->getCodigoLiquidacionPk());
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $em->persist($arRegistro);
                            }
                        }

                        //Intereses cesantias
                        if ($arLiquidacion->getVrInteresesCesantias() > 0) {
                            $arCuenta = $em->getRepository(FinCuenta::class)->find($codigoCuentaInteresesCesantias);
                            if ($arCuenta) {
                                $arRegistro = new FinRegistro();
                                $arRegistro->setComprobanteRel($arComprobanteContable);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setNumero($arLiquidacion->getNumero());
                                $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                $arRegistro->setFecha($arLiquidacion->getFecha());
                                $arRegistro->setVrDebito($arLiquidacion->getVrInteresesCesantias());
                                $arRegistro->setNaturaleza("D");
                                $arRegistro->setDescripcion('INTERESES CESANTIAS');
                                $arRegistro->setCodigoModeloFk('RhuLiquidacion');
                                $arRegistro->setCodigoDocumento($arLiquidacion->getCodigoLiquidacionPk());
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $em->persist($arRegistro);
                            }
                        }

                        //Cesantias anteriores
                        if ($arLiquidacion->getVrCesantiasAnterior() > 0) {
                            $arCuenta = $em->getRepository(FinCuenta::class)->find($codigoCuentaCesantiasAnterior);
                            if ($arCuenta) {
                                $arRegistro = new FinRegistro();
                                $arRegistro->setComprobanteRel($arComprobanteContable);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setNumero($arLiquidacion->getNumero());
                                $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                $arRegistro->setFecha($arLiquidacion->getFecha());
                                $arRegistro->setVrDebito($arLiquidacion->getVrCesantiasAnterior());
                                $arRegistro->setNaturaleza("D");
                                $arRegistro->setCodigoModeloFk('RhuLiquidacion');
                                $arRegistro->setCodigoDocumento($arLiquidacion->getCodigoLiquidacionPk());
                                $arRegistro->setDescripcion('CESANTIAS AÑO ANTERIOR');
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $em->persist($arRegistro);
                            }
                        }

                        //Intereses cesantias anteriores
                        if ($arLiquidacion->getVrInteresesCesantiasAnterior() > 0) {
                            $arCuenta = $em->getRepository(FinCuenta::class)->find($codigoCuentaInteresesCesantiasAnterior);
                            if ($arCuenta) {
                                $arRegistro = new FinRegistro();
                                $arRegistro->setComprobanteRel($arComprobanteContable);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setNumero($arLiquidacion->getNumero());
                                $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                $arRegistro->setFecha($arLiquidacion->getFecha());
                                $arRegistro->setVrDebito($arLiquidacion->getVrInteresesCesantiasAnterior());
                                $arRegistro->setNaturaleza("D");
                                $arRegistro->setCodigoModeloFk('RhuLiquidacion');
                                $arRegistro->setCodigoDocumento($arLiquidacion->getCodigoLiquidacionPk());
                                $arRegistro->setDescripcion('INTERESES CESANTIAS AÑO ANTERIOR');
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $em->persist($arRegistro);
                            }
                        }

                        //Primas
                        if ($arLiquidacion->getVrPrima() > 0) {
                            $arCuenta = $em->getRepository(FinCuenta::class)->find($codigoCuentaPrimas);
                            if ($arCuenta) {
                                $arRegistro = new FinRegistro();
                                $arRegistro->setComprobanteRel($arComprobanteContable);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setNumero($arLiquidacion->getNumero());
                                $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                $arRegistro->setFecha($arLiquidacion->getFecha());
                                $arRegistro->setVrDebito($arLiquidacion->getVrPrima());
                                $arRegistro->setNaturaleza("D");
                                $arRegistro->setCodigoModeloFk('RhuLiquidacion');
                                $arRegistro->setCodigoDocumento($arLiquidacion->getCodigoLiquidacionPk());
                                $arRegistro->setDescripcion('PRIMAS');
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $em->persist($arRegistro);
                            }
                        }

                        //Vacaciones
                        if ($arLiquidacion->getVrVacacion() > 0) {
                            $arCuenta = $em->getRepository(FinCuenta::class)->find($codigoCuentaVacaciones);
                            if ($arCuenta) {
                                $arRegistro = new FinRegistro();
                                $arRegistro->setComprobanteRel($arComprobanteContable);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setNumero($arLiquidacion->getNumero());
                                $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                $arRegistro->setFecha($arLiquidacion->getFecha());
                                $arRegistro->setVrDebito($arLiquidacion->getVrVacacion());
                                $arRegistro->setNaturaleza("D");
                                $arRegistro->setCodigoModeloFk('RhuLiquidacion');
                                $arRegistro->setCodigoDocumento($arLiquidacion->getCodigoLiquidacionPk());
                                $arRegistro->setDescripcion('VACACIONES');
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $em->persist($arRegistro);
                            }
                        }

                        //Indemnizacion
                        if ($arLiquidacion->getVrIndemnizacion() > 0) {
                            $arCuenta = $em->getRepository(FinCuenta::class)->find($codigoCuentaIndemnizacion);
                            if ($arCuenta) {
                                $arRegistro = new FinRegistro();
                                $arRegistro->setComprobanteRel($arComprobanteContable);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setNumero($arLiquidacion->getNumero());
                                $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                $arRegistro->setFecha($arLiquidacion->getFecha());
                                $arRegistro->setVrDebito($arLiquidacion->getVrIndemnizacion());
                                $arRegistro->setNaturaleza("D");
                                $arRegistro->setCodigoModeloFk('RhuLiquidacion');
                                $arRegistro->setCodigoDocumento($arLiquidacion->getCodigoLiquidacionPk());
                                $arRegistro->setDescripcion('INDEMNIZACION');
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $em->persist($arRegistro);
                            }
                        }

                        //Deduccion prima
                        if ($arLiquidacion->getVrDeduccionPrima() > 0) {
                            $arCuenta = $em->getRepository(FinCuenta::class)->find($codigoCuentaDeduccionPrima);
                            if ($arCuenta) {
                                $arRegistro = new FinRegistro();
                                $arRegistro->setComprobanteRel($arComprobanteContable);
                                //$arRegistro->setCentroCostoRel($arCentroCosto);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setNumero($arLiquidacion->getNumero());
                                $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                $arRegistro->setFecha($arLiquidacion->getFecha());
                                $arRegistro->setVrCredito($arLiquidacion->getVrDeduccionPrima());
                                $arRegistro->setNaturaleza("C");
                                $arRegistro->setCodigoModeloFk('RhuLiquidacion');
                                $arRegistro->setCodigoDocumento($arLiquidacion->getCodigoLiquidacionPk());
                                $arRegistro->setDescripcion('DEDUDUCCION PRIMA');
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $em->persist($arRegistro);
                            }
                        }

                        //Adicionales Se deja comentado inicialmente para refactorizacion
                        $arLiquidacionAdicionales = $em->getRepository(RhuLiquidacionAdicional::class)->findBy(array('codigoLiquidacionFk' => $codigo));
                        foreach ($arLiquidacionAdicionales as $arLiquidacionAdicional) {
                            $arConceptoCuenta = $em->getRepository(RhuConceptoCuenta::class)->findOneBy(array('codigoConceptoFk' => $arLiquidacionAdicional->getCodigoConceptoFk()));
                            if ($arConceptoCuenta) {
                                $arCuenta = $em->getRepository(FinCuenta::class)->find($arConceptoCuenta->getCodigoCuentaFk());
                                if ($arCuenta) {
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    // Cuando el detalle es de salud y pension se lleva al nit de la entidad
                                    if ($arCuenta->getExigeTercero() == 1) {
                                        if ($arLiquidacionAdicional->getConceptoRel()->getSalud() || $arLiquidacionAdicional->getConceptoRel()->getIncapacidadEntidad()) {
                                            $arTerceroSalud = $em->getRepository(FinCuenta::class)->findOneBy(array('numeroIdentificacion' => $arLiquidacion->getContratoRel()->getEntidadSaludRel()->getNit()));
                                            if ($arTerceroSalud) {
                                                $arRegistro->setTerceroRel($arTerceroSalud);
                                            } else {
                                                $error = "La entidad de salud " . $arLiquidacion->getContratoRel()->getEntidadSaludRel()->getNit() . "-" . $arLiquidacion->getContratoRel()->getEntidadSaludRel()->getNombre() . " de la liquidacion " . $arLiquidacion->getNumero() . " no existe en contabilidad";
                                                break;
                                            }
                                        }
                                        if ($arLiquidacionAdicional->getConceptoRel()->getPension() || $arLiquidacionAdicional->getConceptoRel()->getFondoSolidaridadPensional()) {
                                            $arTerceroPension = $em->getRepository(FinTercero::class)->findOneBy(array('numeroIdentificacion' => $arLiquidacion->getContratoRel()->getEntidadPensionRel()->getNit()));
                                            if ($arTerceroPension) {
                                                $arRegistro->setTerceroRel($arTerceroPension);
                                            } else {
                                                $error = "La entidad de pension " . $arLiquidacion->getContratoRel()->getEntidadPensionRel()->getNombre() . " de la liquidacion " . $arLiquidacion->getNumero() . " no existe en contabilidad";
                                                break;
                                            }
                                        }
                                    }

                                    //Esta propiedad no se encuentra en la entidad finCuenta
//                                    if ($arCuenta->getCodigoTerceroFijoFk()) {
//                                        $arTerceroDetalle = $em->getRepository(FinTercero::class)->find($arCuenta->getCodigoTerceroFijoFk());
//                                        $arRegistro->setTerceroRel($arTerceroDetalle);
//                                    }
                                    //Para contabilizar al nit de la entidad del credito
                                    if ($arLiquidacionAdicional->getCodigoCreditoFk() != null) {
                                        if ($arConfiguracion->getContabilizarCreditoNitEntidad()) {
                                            if ($arLiquidacionAdicional->getCreditoRel()->getCreditoTipoRel()->getNumeroIdentificacionTerceroContabilidad() != "") {
                                                $arTerceroCredito = $em->getRepository(FinTercero::class)->findOneBy(array('numeroIdentificacion' => $arLiquidacionAdicional->getCreditoRel()->getCreditoTipoRel()->getNumeroIdentificacionTerceroContabilidad()));
                                                $arRegistro->setTerceroRel($arTerceroCredito);
                                            }
                                        }
                                    }
                                    //Para contabilizar al nit fijo el concepto:: Estas dos funciones se comentan para anilisis de casos de uso
//                                    if ($arLiquidacionAdicional->getConceptoRel()->getNumeroIdentificacionTerceroContabilidad() != null) {
//                                        $arTerceroConcepto = $em->getRepository(FinTercero::class)->findOneBy(array('numeroIdentificacion' => $arLiquidacionAdicional->getConceptoRel()->getNumeroIdentificacionTerceroContabilidad()));
//                                        $arRegistro->setTerceroRel($arTerceroConcepto);
//                                    }
//                                    //Contabilizar concepto si es a el empleado.
//                                    if ($arLiquidacionAdicional->getConceptoRel()->getContabilizarEmpleado()) {
//                                        $arRegistro->setTerceroRel($arTercero);
//                                    }
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setNumero($arLiquidacion->getNumero());
                                    $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setFecha($arLiquidacion->getFecha());
                                    if ($arLiquidacionAdicional->getVrBonificacion() > 0) {
                                        $arRegistro->setVrDebito($arLiquidacionAdicional->getVrBonificacion());
                                        $arRegistro->setNaturaleza("D");
                                    } else {
                                        $arRegistro->setVrCredito($arLiquidacionAdicional->getVrDeduccion());
                                        $arRegistro->setNaturaleza("C");
                                    }
                                    if ($arCuenta->getExigeCentroCosto()) {
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                    }
                                    $arRegistro->setCodigoModeloFk('RhuLiquidacion');
                                    $arRegistro->setCodigoDocumento($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setDescripcion($arLiquidacionAdicional->getConceptoRel()->getNombre());
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "La cuenta " . $arConceptoCuenta->getCodigoCuentaFk() . " no existe en el plan de cuentas";
                                    break;
                                }
                            } else {
                                $error = "El concepto adicional de la liquidacion " . $arLiquidacionAdicional->getConceptoRel()->getNombre() . " no tiene cuenta configurada";
                                break;
                            }
                        }

                        //Liquidacion
                        if ($arLiquidacion->getVrTotal() > 0) {
                            $arCuenta = $em->getRepository(FinCuenta::class)->find($codigoCuentaLiquidacion);
                            if ($arCuenta) {
                                $arRegistro = new FinRegistro();
                                $arRegistro->setComprobanteRel($arComprobanteContable);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setNumero($arLiquidacion->getNumero());
                                $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                $arRegistro->setFecha($arLiquidacion->getFecha());
                                $arRegistro->setVrCredito($arLiquidacion->getVrTotal());
                                $arRegistro->setNaturaleza("C");
                                $arRegistro->setCodigoModeloFk('RhuLiquidacion');
                                $arRegistro->setCodigoDocumento($arLiquidacion->getCodigoLiquidacionPk());
                                $arRegistro->setDescripcion('LIQUIDACION POR PAGAR');
                                if ($arCuenta->getExigeCentroCosto()) {
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }
                                $em->persist($arRegistro);
                            }
                        }


                    } else {
                        $error = "La liquidacion con codigo . $codigo  se encuentra sin aprobar o ya se encuentra contabilizada";
                    }

                } else {
                    $error = "La liquidacion con codigo . $codigo. no existe";
                }

                $arLiquidacionAct = $em->getRepository(RhuLiquidacion::class)->find($arLiquidacion->getCodigoLiquidacionPk());
                $arLiquidacionAct->setEstadoContabilizado(1);
                $em->persist($arLiquidacionAct);
            }
            if ($error == "") {
                $em->flush();
            } else {
                Mensajes::error($error);
            }
        }
        return true;
    }


}