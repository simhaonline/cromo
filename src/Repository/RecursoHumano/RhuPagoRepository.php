<?php

namespace App\Repository\RecursoHumano;

use App\Entity\Financiero\FinCentroCosto;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\RecursoHumano\RhuAdicional;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConceptoCuenta;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuConfiguracionProvision;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuEmbargo;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuEntidad;
use App\Entity\RecursoHumano\RhuIncapacidad;
use App\Entity\RecursoHumano\RhuLicencia;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuProgramacion;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesCuentaPagarTipo;
use App\Entity\Turno\TurProgramacionRespaldo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuPagoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuPago::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoPago = null;
        $codigoEmpleado = null;
        $pagoTipo = null;
        $numero = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoContabilizado = null;
        if ($filtros) {
            $codigoPago = $filtros['codigoPago'] ?? null;
            $numero = $filtros['numero'] ?? null;
            $pagoTipo = $filtros['pagoTipo'] ?? null;
            $codigoEmpleado = $filtros['codigoEmpleado'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoContabilizado = $filtros['estadoContabilizado'] ?? null;
        }

        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuPago::class, 'p')
            ->select('p.codigoPagoPk')
            ->addSelect('p.numero')
            ->addSelect('pt.nombre as pagoTipo')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.nombreCorto AS empleado')
            ->addSelect('p.fechaDesde')
            ->addSelect('p.fechaHasta')
            ->addSelect('p.vrSalarioContrato')
            ->addSelect('p.vrDevengado')
            ->addSelect('p.vrDeduccion')
            ->addSelect('p.vrNeto')
            ->addSelect('p.vrIngresoBasePrestacion as IBP')
            ->addSelect('p.vrIngresoBasePrestacionVacacion as IBPV')
            ->addSelect('p.vrCesantia')
            ->addSelect('p.vrInteres')
            ->addSelect('p.vrPrima')
            ->addSelect('p.vrVacacion')
            ->addSelect('p.codigoGrupoFk')
            ->addSelect('p.estadoAutorizado')
            ->addSelect('p.estadoAprobado')
            ->addSelect('p.estadoAnulado')
            ->addSelect('p.estadoContabilizado')
            ->leftJoin('p.pagoTipoRel', 'pt')
            ->leftJoin('p.empleadoRel', 'e');
        if ($codigoPago) {
            $queryBuilder->andWhere("p.codigoPagoPk = '{$codigoPago}'");
        }
        if ($codigoEmpleado) {
            $queryBuilder->andWhere("p.codigoEmpleadoFk = '{$codigoEmpleado}'");
        }
        if ($pagoTipo) {
            $queryBuilder->andWhere("p.codigoPagoTipoFk = '{$pagoTipo}'");
        }
        if ($numero) {
            $queryBuilder->andWhere("p.numero = '{$numero}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("p.fechaDesde >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("p.fechaHasta <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoContabilizado) {
            case '0':
                $queryBuilder->andWhere("p.estadoContabilizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("p.estadoContabilizado = 1");
                break;
        }
        $queryBuilder->addOrderBy('p.codigoPagoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $codigoProgramacion integer
     */
    public function eliminarPagos($codigoProgramacion)
    {
        $em = $this->getEntityManager();
        $subQuery = $em->createQueryBuilder()->from(RhuPago::class, 'pp')
            ->select('pp.codigoPagoPk')
            ->where("pp.codigoProgramacionFk = {$codigoProgramacion}");

        $em->createQueryBuilder()
            ->delete(RhuPagoDetalle::class, 'pd')
            ->where("pd.codigoPagoFk IN ({$subQuery})")->getQuery()->execute();

        $codigosPagos = implode(',', array_map(function ($v) {
            return $v['codigoPagoPk'];
        }, $subQuery->getQuery()->execute()));

        if($codigosPagos) {
            $em->createQueryBuilder()->delete(RhuPago::class, 'p')
                ->where("p.codigoPagoPk IN ({$codigosPagos})")->getQuery()->execute();
        }
    }

    /**
     * @param $arProgramacionDetalle RhuProgramacionDetalle
     * @param $arProgramacion RhuProgramacion
     * @param $arConceptoHora array
     * @param $usuario string
     * @return int|mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public function generar($arProgramacionDetalle, $arProgramacion, $arConceptoHora, $arConfiguracion, $arConceptoFondoSolidaridadPension, $usuario)
    {
        $em = $this->getEntityManager();
        $neto = 0;
        if ($arProgramacion->getCodigoPagoTipoFk() == 'NOM') {
            $arPago = new RhuPago();
            $arContrato = $em->getRepository(RhuContrato::class)->find($arProgramacionDetalle->getCodigoContratoFk());
            $arPago->setPagoTipoRel($arProgramacion->getPagoTipoRel());
            $arPago->setEmpleadoRel($arProgramacionDetalle->getEmpleadoRel());
            $arPago->setContratoRel($arProgramacionDetalle->getContratoRel());
            $arPago->setTiempoRel($arProgramacionDetalle->getContratoRel()->getTiempoRel());
            $arPago->setGrupoRel($arProgramacionDetalle->getContratoRel()->getGrupoRel());
            $arPago->setProgramacionDetalleRel($arProgramacionDetalle);
            $arPago->setProgramacionRel($arProgramacion);
            $arPago->setVrSalarioContrato($arContrato->getVrSalario());
            $arPago->setCuenta($arContrato->getEmpleadoRel()->getCuenta());
            $arPago->setCuentaTipo($arContrato->getEmpleadoRel()->getCodigoCuentaTipoFk());
            $arPago->setBancoRel($arContrato->getEmpleadoRel()->getBancoRel());
            $arPago->setUsuario($usuario);
            $arPago->setEntidadPensionRel($arContrato->getEntidadPensionRel());
            $arPago->setEntidadSaludRel($arContrato->getEntidadSaludRel());
            $arPago->setFechaDesde($arProgramacion->getFechaDesde());
            $arPago->setFechaHasta($arProgramacion->getFechaHasta());
            $arPago->setFechaDesdeContrato($arProgramacionDetalle->getFechaDesdeContrato());
            $arPago->setFechaDesdeContrato($arProgramacionDetalle->getFechaHastaContrato());
            $arPago->setCodigoSoporteContratoFk($arProgramacionDetalle->getCodigoSoporteContratoFk());

            $arrDatosGenerales = array(
                'pago' => $arPago,
                'devengado' => 0,
                'deduccion' => 0,
                'ingresoBaseCotizacion' => 0,
                'ingresoBasePrestacion' => 0,
                'ingresoBasePrestacionVacacion' => 0,
                'neto' => 0);
            $valorDia = $arProgramacionDetalle->getVrDia();
            $valorHora = $arProgramacionDetalle->getVrHora();
            $factorHorasDia = $arProgramacionDetalle->getFactorHorasDia();
            $auxilioTransporte = $arConfiguracion['vrAuxilioTransporte'];
            $diaAuxilioTransporte = $auxilioTransporte / 30;
            $salarioMinimo = $arConfiguracion['vrSalarioMinimo'];
            $ibcVacaciones = 0;
            $vrPagoIncapacidad = 0;
            $vrPagoLicencia = 0;
            $devengado = 0;
            $devengadoPrestacional = 0;
            $salud = 0;
            $pension = 0;
            $transporte = 0;

            //Vacaciones
            $arVacaciones = $em->getRepository(RhuVacacion::class)->periodo($arProgramacionDetalle->getFechaDesdeContrato(), $arProgramacionDetalle->getFechaHastaContrato(), $arProgramacionDetalle->getCodigoEmpleadoFk());
            foreach ($arVacaciones as $arVacacion) {
                if ($arVacacion['diasDisfrutadosReales'] > 0) {
                    $arConcepto = $em->getRepository(RhuConcepto::class)->find($arConfiguracion['codigoConceptoVacacionFk']);
                    $arPagoDetalle = new RhuPagoDetalle();
                    $fechaDesde = $arProgramacionDetalle->getFechaDesdeContrato();
                    $fechaHasta = $arProgramacionDetalle->getFechaHasta();
                    if ($arVacacion['fechaDesdeDisfrute'] > $fechaDesde) {
                        $fechaDesde = $arVacacion['fechaDesdeDisfrute'];
                    }
                    if ($arVacacion['fechaHastaDisfrute'] < $fechaHasta) {
                        $fechaHasta = $arVacacion['fechaHastaDisfrute'];
                    }
                    $diasVacaciones = $fechaDesde->diff($fechaHasta);
                    $diasVacaciones = $diasVacaciones->format('%a');
                    $diasVacaciones += 1;
                    $horas = $diasVacaciones * $factorHorasDia;
                    $ibcVacaciones = $diasVacaciones * $arVacacion['vrIbcPromedio'];
                    $arPagoDetalle->setDias($diasVacaciones);
                    $arPagoDetalle->setHoras($horas);
                    $arPagoDetalle->setVacacionRel($em->getReference(RhuVacacion::class, $arVacacion['codigoVacacionPk']));
                    //$arPagoDetalle->setFechaDesdeNovedad($fechaDesde);
                    //$arPagoDetalle->setFechaHastaNovedad($fechaHasta);
                    $this->getValoresPagoDetalle($arrDatosGenerales, $arPagoDetalle, $arConcepto, 0);
                    $arPagoDetalle->setVrIngresoBaseCotizacion($ibcVacaciones);
                    $em->persist($arPagoDetalle);
                }
            }

            //Procesar Incapacidad empresa
            $arIncapacidades = $em->getRepository(RhuIncapacidad::class)->periodoEmpresa($arProgramacionDetalle->getFechaDesdeContrato(), $arProgramacionDetalle->getFechaHastaContrato(), $arProgramacionDetalle->getCodigoEmpleadoFk());
            foreach ($arIncapacidades as $arIncapacidad) {
                $arConcepto = $arIncapacidad->getIncapacidadTipoRel()->getConceptoEmpresaRel();
                $arPagoDetalle = new RhuPagoDetalle();

                $fechaDesde = $arProgramacionDetalle->getFechaDesdeContrato();
                $fechaHasta = $arProgramacionDetalle->getFechaHasta();
                if ($arIncapacidad->getFechaDesdeEmpresa() > $fechaDesde) {
                    $fechaDesde = $arIncapacidad->getFechaDesdeEmpresa();
                }
                if ($arIncapacidad->getFechaHastaEmpresa() < $fechaHasta) {
                    $fechaHasta = $arIncapacidad->getFechaHastaEmpresa();
                }

                $vrHoraIncapacidad = $arIncapacidad->getVrHoraEmpresa();
                /*if ($arConfiguracion->getPagarIncapacidadEmpresaCompleto()) {
                    $vrHoraIncapacidad = $arIncapacidad->getVrHoraEmpresa();
                    if ($vrHoraIncapacidad == 0) {
                        $vrHoraIncapacidad = $arIncapacidad->getVrHora();
                    }
                }*/

                $vrSalarioIncapacidad = $arProgramacionDetalle->getVrSalario();
                $intDias = $fechaDesde->diff($fechaHasta);
                $intDias = $intDias->format('%a');
                $intDias += 1;
                $intHorasProcesarIncapacidad = $intDias * $factorHorasDia;
                //$intHorasLaboradas = $intHorasLaboradas - $intHorasProcesarIncapacidad;
                $douPagoDetalle = 0;
                $douIngresoBaseCotizacionIncapacidad = 0;
                $douIngresoBasePrestacionIncapacidad = 0;
                $douIngresoBaseCotizacionIncapacidadControl = 0;
                //$intDiasTransporte = $intDiasTransporte - ($intHorasProcesarIncapacidad / $intFactorDia);
                //$douPagoDetalle = ($douPagoDetalle * $arIncapacidad->getPorcentajePago())/100;
                $pagoDetalle = $intHorasProcesarIncapacidad * $vrHoraIncapacidad;
                $douIngresoBasePrestacionIncapacidad = $intHorasProcesarIncapacidad * $vrHoraIncapacidad;
                $douIngresoBaseCotizacionIncapacidadControl = $douPagoDetalle;
                if ($arIncapacidad->getIncapacidadTipoRel()->getGeneraIbc() == 1) {
                    $douIngresoBaseCotizacionIncapacidad = $douPagoDetalle;
                }
                if ($arIncapacidad->getIncapacidadTipoRel()->getGeneraPago() == 0) {
                    $pagoDetalle = 0;
                }
                if ($arProgramacionDetalle->getEmpleadoRel()->getPagadoEntidad() == 1) {
                    $pagoDetalle = 0;
                }
                $pagoDetalle = round($pagoDetalle);
                $vrPagoIncapacidad += $pagoDetalle;
                $devengado += $pagoDetalle;
                $devengadoPrestacional += $pagoDetalle;
                $arPagoDetalle->setHoras($intHorasProcesarIncapacidad);
                $arPagoDetalle->setDias($intDias);
                $arPagoDetalle->setVrHora($vrHoraIncapacidad);
                if ($arPagoDetalle->getVrHora() == null) {
                    echo "hola";
                }
                //$arPagoDetalle->setVrDia($douVrDia);

                $this->getValoresPagoDetalle($arrDatosGenerales, $arPagoDetalle, $arConcepto, $pagoDetalle);
                $arPagoDetalle->setIncapacidadRel($arIncapacidad);
                $em->persist($arPagoDetalle);
            }

            //Procesar Incapacidad entidad
            $arIncapacidades = $em->getRepository(RhuIncapacidad::class)->periodoEntidad($arProgramacionDetalle->getFechaDesdeContrato(), $arProgramacionDetalle->getFechaHasta(), $arProgramacionDetalle->getCodigoEmpleadoFk());
            foreach ($arIncapacidades as $arIncapacidad) {
                $arConcepto = $arIncapacidad->getIncapacidadTipoRel()->getConceptoRel();
                $arPagoDetalle = new RhuPagoDetalle();
                $fechaDesde = $arProgramacionDetalle->getFechaDesdeContrato();
                $fechaHasta = $arProgramacionDetalle->getFechaHasta();
                if ($arIncapacidad->getFechaDesdeEntidad() > $fechaDesde) {
                    $fechaDesde = $arIncapacidad->getFechaDesdeEntidad();
                }
                if ($arIncapacidad->getFechaHastaEntidad() < $fechaHasta) {
                    $fechaHasta = $arIncapacidad->getFechaHastaEntidad();
                }
                #$vrHoraIncapacidad = $douVrHora;
                $vrHoraIncapacidad = $arIncapacidad->getVrHora();
                $vrSalarioIncapacidad = $arProgramacionDetalle->getVrSalario();
                $intDias = $fechaDesde->diff($fechaHasta);
                $intDias = $intDias->format('%a');
                $intDias += 1;

                $intHorasProcesarIncapacidad = $intDias * $factorHorasDia;
                //$intHorasLaboradas = $intHorasLaboradas - $intHorasProcesarIncapacidad;
                $pagoDetalle = 0;
                //$intDiasTransporte = $intDiasTransporte - ($intHorasProcesarIncapacidad / $intFactorDia);
                //$douPagoDetalle = ($douPagoDetalle * $arIncapacidad->getPorcentajePago())/100;
                $pagoDetalle = $intHorasProcesarIncapacidad * $vrHoraIncapacidad;
                $douIngresoBasePrestacionIncapacidad = $intHorasProcesarIncapacidad * $vrHoraIncapacidad;
                $douIngresoBaseCotizacionIncapacidadControl = $pagoDetalle;
                if ($arIncapacidad->getIncapacidadTipoRel()->getGeneraIbc() == 1) {
                    $douIngresoBaseCotizacionIncapacidad = $pagoDetalle;
                }
                if ($arIncapacidad->getIncapacidadTipoRel()->getGeneraPago() == 0) {
                    $pagoDetalle = 0;
                }
                if ($arProgramacionDetalle->getEmpleadoRel()->getPagadoEntidad() == 1) {
                    $pagoDetalle = 0;
                }
                $pagoDetalle = round($pagoDetalle);
                $vrPagoIncapacidad += $pagoDetalle;
                $devengado += $pagoDetalle;
                $devengadoPrestacional += $pagoDetalle;

                $arPagoDetalle->setHoras($intHorasProcesarIncapacidad);
                $arPagoDetalle->setDias($intDias);
                $arPagoDetalle->setVrHora($vrHoraIncapacidad);
                //$arPagoDetalle->setVrDia($douVrDia);
                $this->getValoresPagoDetalle($arrDatosGenerales, $arPagoDetalle, $arConcepto, $pagoDetalle);
                $arPagoDetalle->setIncapacidadRel($arIncapacidad);
                $em->persist($arPagoDetalle);
            }

            //Procesar licencias
            $arLicencias = $em->getRepository(RhuLicencia::class)->periodo($arProgramacionDetalle->getFechaDesdeContrato(), $arProgramacionDetalle->getFechaHasta(), $arProgramacionDetalle->getCodigoEmpleadoFk());
            foreach ($arLicencias as $arLicencia) {
                $arConcepto = $arLicencia->getLicenciaTipoRel()->getConceptoRel();
                $arPagoDetalle = new RhuPagoDetalle();
                $arPagoDetalle->setLicenciaRel($arLicencia);
                $fechaDesde = $arProgramacionDetalle->getFechaDesdeContrato();
                $fechaHasta = $arProgramacionDetalle->getFechaHasta();
                if ($arLicencia->getFechaDesde() > $fechaDesde) {
                    $fechaDesde = $arLicencia->getFechaDesde();
                }
                if ($arLicencia->getFechaHasta() < $fechaHasta) {
                    $fechaHasta = $arLicencia->getFechaHasta();
                }
                $intDias = $fechaDesde->diff($fechaHasta);
                $intDias = $intDias->format('%a');
                $intDias += 1;
                $douVrHora = 0;
                if ($arLicencia->getLicenciaTipoRel()->getPaternidad() || $arLicencia->getLicenciaTipoRel()->getMaternidad() || $arLicencia->getLicenciaTipoRel()->getRemunerada()) {
                    $douVrHora = ($arLicencia->getVrLicencia() / $arLicencia->getCantidad()) / $factorHorasDia;
                }
                $arPagoDetalle->setDias($intDias);
                $intHorasProcesarLicencia = $intDias * $factorHorasDia;
                //$intHorasLaboradas = $intHorasLaboradas - $intHorasProcesarLicencia;
                $pagoDetalle = $intHorasProcesarLicencia * $douVrHora;
                /*if ($arConfiguracion->getPagarLicenciaSalarioPactado()) {
                    $devengadoPactado = $arContrato->getVrDevengadoPactado();
                    if ($devengadoPactado > 0) {
                        $vrHoraDevengadoPactado = $devengadoPactado / 30 / 8;
                        $douPagoDetalle = $intHorasProcesarLicencia * $vrHoraDevengadoPactado;
                    }
                }*/
                $pagoDetalle = round($pagoDetalle);
                /*if (!$arLicencia->getLicenciaTipoRel()->getSuspensionContratoTrabajo()) {
                    $douIngresoBasePrestacional += $douPagoDetalle;
                    $arPagoDetalle->setVrIngresoBasePrestacion($douPagoDetalle);
                    $arPagoDetalle->setVrIngresoBaseCotizacion($douPagoDetalle);
                }*/
                /*if ($arConcepto->getGeneraIngresoBaseCotizacion() == 1) {
                    $douIngresoBaseCotizacion += $douPagoDetalle;
                    $arPagoDetalle->setVrIngresoBaseCotizacion($douPagoDetalle);
                }*/
                if ($arConcepto->getOperacion() == 0) {
                    $pagoDetalle = 0;
                }
                /*if ($pagoDetalle > 0) {
                    $douIngresoBaseCotizacionSalud += $douPagoDetalle;
                }*/
                $devengado += $pagoDetalle;
                $devengadoPrestacional += $pagoDetalle;
                $this->getValoresPagoDetalle($arrDatosGenerales, $arPagoDetalle, $arConcepto, $pagoDetalle);
                //$arPagoDetalle->setNumeroHoras($intHorasProcesarLicencia);
                $em->persist($arPagoDetalle);
                /*if ($arLicencia->getAfectaTransporte() == 1) {
                    $intDiasLicenciaProcesar = intval($intHorasProcesarLicencia / $factorHorasDia);
                    $intDiasTransporte = $intDiasTransporte - $intDiasLicenciaProcesar;
                }*/
            }

            //Adicionales
            $arAdicionales = $em->getRepository(RhuAdicional::class)->programacionPago($arProgramacionDetalle->getCodigoEmpleadoFk(), $arContrato->getCodigoContratoPk(), $arProgramacion->getCodigoPagoTipoFk(), $arProgramacion->getFechaDesde()->format('Y/m/d'), $arProgramacion->getFechaHasta()->format('Y/m/d'));
            foreach ($arAdicionales as $arAdicional) {
                $arConcepto = $em->getRepository(RhuConcepto::class)->find($arAdicional['codigoConceptoFk']);
                $arPagoDetalle = new RhuPagoDetalle();
                $pagoDetalle = $arAdicional['vrValor'];
                if ($arAdicional['aplicaDiaLaborado']) {
                    $valorDia = $arAdicional['vrValor'] / $arProgramacion->getDias();
                    $pagoDetalle = $valorDia * $arProgramacionDetalle->getDias();
                }
//            if ($arPagoAdicional->getAplicaDiaLaboradoSinDescanso() == 1) {
//                $diasPeriodo = $arCentroCosto->getPeriodoPagoRel()->getDias();
//                $valorDia = $arPagoAdicional->getValor() / $diasPeriodo;
//                $douPagoDetalle = $valorDia * ($arProgramacionPagoDetalle->getDias() - ($arProgramacionPagoDetalle->getHorasDescanso() / 8));
//            }
                $pagoDetalle = round($pagoDetalle);
                if ($arConcepto->getOperacion() == 1) {
                    $devengado += $pagoDetalle;
                    if ($arConcepto->getGeneraIngresoBasePrestacion()) {
                        $devengadoPrestacional += $pagoDetalle;
                    }
                }
                //$arPagoDetalle->setNumeroHoras($arPagoAdicional->getHoras());
                $arPagoDetalle->setDetalle($arAdicional['detalle']);
                $this->getValoresPagoDetalle($arrDatosGenerales, $arPagoDetalle, $arConcepto, $pagoDetalle);
                $em->persist($arPagoDetalle);
            }

            //Horas
            $arrHoras = $this->getHoras($arProgramacionDetalle);
            foreach ($arrHoras AS $arrHora) {
                if ($arrHora['cantidad'] > 0) {
                    /** @var  $arConcepto RhuConcepto */
                    $arConcepto = $arConceptoHora[$arrHora['clave']]->getConceptoRel();
                    $arPagoDetalle = new RhuPagoDetalle();
                    $valorHoraDetalle = ($valorHora * $arConcepto->getPorcentaje()) / 100;
                    $pagoDetalle = $valorHoraDetalle * $arrHora['cantidad'];
                    $pagoDetalle = round($pagoDetalle);
                    $devengado += $pagoDetalle;
                    $devengadoPrestacional += $pagoDetalle;
                    $arPagoDetalle->setVrHora($valorHoraDetalle);
                    $arPagoDetalle->setPorcentaje($arConcepto->getPorcentaje());
                    $arPagoDetalle->setHoras($arrHora['cantidad']);
                    $this->getValoresPagoDetalle($arrDatosGenerales, $arPagoDetalle, $arConcepto, $pagoDetalle);
                    $em->persist($arPagoDetalle);
                }
            }

            //Creditos
            $arCreditos = $em->getRepository(RhuCredito::class)->findBy(array('codigoEmpleadoFk' => $arProgramacionDetalle->getCodigoEmpleadoFk(), 'codigoCreditoPagoTipoFk' => 'NOM', 'estadoPagado' => 0, 'estadoSuspendido' => 0, "inactivoPeriodo" => 0));
            foreach ($arCreditos as $arCredito) {
                if ($arCredito->getVrSaldo() > 0) {
                    $descontarCuota = true;
                    $numeroCuotas = $arCredito->getNumeroCuotas();
                    $numeroCuotaActual = $arCredito->getNumeroCuotaActual();
                    if ($arCredito->getValidarCuotas() == 1) {
                        if ($numeroCuotaActual > $numeroCuotas) {
                            $descontarCuota = false;
                        }
                    }
                    if ($arCredito->getFechaInicio() > $arProgramacionDetalle->getFechaHasta()) {
                        $descontarCuota = false;
                    }
                    if ($arCredito->getFechaInicio() < $arContrato->getFechaDesde()) {
                        $descontarCuota = false;
                    }
                    if ($descontarCuota) {
                        $arConcepto = $arCredito->getCreditoTipoRel()->getConceptoRel();
                        if ($arCredito->getVrSaldo() >= $arCredito->getVrCuota()) {
                            $cuota = $arCredito->getVrCuota();
                        } else {
                            $cuota = $arCredito->getVrSaldo();
                        }
                        $pagoDetalle = $cuota;
                        $arPagoDetalle = new RhuPagoDetalle();
                        $arPagoDetalle->setDetalle($arCredito->getCreditoTipoRel()->getNombre());
                        $arPagoDetalle->setCreditoRel($arCredito);
                        $this->getValoresPagoDetalle($arrDatosGenerales, $arPagoDetalle, $arConcepto, $pagoDetalle);
                        $em->persist($arPagoDetalle);
                    }
                }
            }

            //Salud
            if ($arProgramacionDetalle->getDescuentoSalud()) {
                $arSalud = $arContrato->getSaludRel();
                $porcentajeSalud = $arSalud->getPorcentajeEmpleado();
                if ($porcentajeSalud > 0) {
                    $ingresoBaseCotizacionSalud = $arrDatosGenerales['ingresoBaseCotizacion'];
                    $arConcepto = $arSalud->getConceptoRel();
                    if ($arConcepto) {
                        /*
                         * La base de aportes a seguridad comunidad tanto en salud como en pensión,
                         * no puede ser inferior al salario mínimo ni superior a los 25 salarios mínimos mensuales.
                         * Esta limitación está dada por el artículo 5 de la ley 797 de 2003, reglamentado por el decreto 510 de 2003 en su artículo 3:
                         */
                        if ($ingresoBaseCotizacionSalud > ($salarioMinimo * 25)) {
                            $ingresoBaseCotizacionSalud = $salarioMinimo * 25;
                        }

                        $pagoDetalle = ($ingresoBaseCotizacionSalud * $porcentajeSalud) / 100;
                        $pagoDetalle = round($pagoDetalle);
                        $salud = $pagoDetalle;
                        $arPagoDetalle = new RhuPagoDetalle();
                        $arPagoDetalle->setPorcentaje($porcentajeSalud);
                        $this->getValoresPagoDetalle($arrDatosGenerales, $arPagoDetalle, $arConcepto, $pagoDetalle);
                        $em->persist($arPagoDetalle);
                    }
                }
            }

            //Pension
            if ($arProgramacionDetalle->getDescuentoPension()) {
                $arPension = $arContrato->getPensionRel();
                $porcentajePension = $arPension->getPorcentajeEmpleado();
                if ($porcentajePension > 0) {
                    $ingresoBaseCotizacionPension = $arrDatosGenerales['ingresoBaseCotizacion'];
                    $arConcepto = $arPension->getConceptoRel();
                    if ($arConcepto) {
                        /*
                         * La base de aportes a seguridad comunidad tanto en salud como en pensión,
                         * no puede ser inferior al salario mínimo ni superior a los 25 salarios mínimos mensuales.
                         * Esta limitación está dada por el artículo 5 de la ley 797 de 2003, reglamentado por el decreto 510 de 2003 en su artículo 3:
                         */
                        if ($ingresoBaseCotizacionPension > ($salarioMinimo * 25)) {
                            $ingresoBaseCotizacionPension = $salarioMinimo * 25;
                        }

                        $pagoDetalle = ($ingresoBaseCotizacionPension * $porcentajePension) / 100;
                        $pagoDetalle = round($pagoDetalle);
                        $pension = $pagoDetalle;
                        $arPagoDetalle = new RhuPagoDetalle();
                        $arPagoDetalle->setPorcentaje($porcentajePension);
                        $this->getValoresPagoDetalle($arrDatosGenerales, $arPagoDetalle, $arConcepto, $pagoDetalle);
                        $em->persist($arPagoDetalle);

                        //Fondo de solidaridad pensional
                        $tope = $salarioMinimo * 4;
                        $ingresoBaseCotizacionTotal = $arrDatosGenerales['ingresoBaseCotizacion'] + $arProgramacionDetalle->getVrIbcAcumulado() + $ibcVacaciones;//Se suman los IBC que ha devengado el empleado en el mes, mas el IBC de la nomina actual.
                        //Se validad si el ingreso base cotizacion es mayor que los 4 salarios minimos legales vigentes, se debe calcular valor a aportar al fondo
                        if ($ingresoBaseCotizacionTotal > $tope) {
                            $porcentajeFondo = $em->getRepository(RhuConfiguracion::class)->porcentajeFondo($salarioMinimo, $ingresoBaseCotizacionTotal);
                            if ($porcentajeFondo > 0) {
                                $pagoDetalle = ($ingresoBaseCotizacionTotal * $porcentajeFondo) / 100;
                                $pagoDetalle -= $arProgramacionDetalle->getVrDeduccionFondoPensionAnterior();//Se resta la deduccion que ha tenido el empleado de la 15na anterior.
                                $pagoDetalle = round($pagoDetalle);
                                //Se guarda el concepto deduccion de fondo solidaridad pensional
                                $arPagoDetalle = new RhuPagoDetalle();
                                $arPagoDetalle->setPorcentaje($porcentajeFondo);
                                $this->getValoresPagoDetalle($arrDatosGenerales, $arPagoDetalle, $arConceptoFondoSolidaridadPension, $pagoDetalle);
                                $em->persist($arPagoDetalle);

                            }
                        }
                    }
                }
            }

            //Auxilio de transporte
            if ($arProgramacionDetalle->getPagoAuxilioTransporte()) {
                if ($arContrato->getAuxilioTransporte() == 1) {
                    $arConcepto = $em->getRepository(RhuConcepto::class)->find($arConfiguracion['codigoConceptoAuxilioTransporteFk']);
                    $pagoDetalle = round($diaAuxilioTransporte * $arProgramacionDetalle->getDiasTransporte());
                    $transporte += $pagoDetalle;
                    $devengado += $pagoDetalle;
                    $arPagoDetalle = new RhuPagoDetalle();
                    $arPagoDetalle->setDias($arProgramacionDetalle->getDiasTransporte());
                    $this->getValoresPagoDetalle($arrDatosGenerales, $arPagoDetalle, $arConcepto, $pagoDetalle);
                    $em->persist($arPagoDetalle);
                }
            }

            //Embargos
            $arEmbargos = $em->getRepository(RhuEmbargo::class)->findBy(array('codigoEmpleadoFk' => $arProgramacionDetalle->getCodigoEmpleadoFk(), 'estadoActivo' => 1));
            foreach ($arEmbargos as $arEmbargo) {
                $douPagoDetalle = 0;
                $detalle = "";
                if ($arEmbargo->getValorFijo()) {
                    $douPagoDetalle = $arEmbargo->getVrValor();
                }
                if ($arEmbargo->getPorcentajeDevengado()) {
                    $douPagoDetalle = (($devengado) * $arEmbargo->getPorcentaje()) / 100;
                    $douPagoDetalle = round($douPagoDetalle);
                    $detalle = $arEmbargo->getPorcentaje() . "% devengado";
                }
                if ($arEmbargo->getPorcentajeDevengadoPrestacional()) {
                    $douPagoDetalle = (($devengadoPrestacional) * $arEmbargo->getPorcentaje()) / 100;
                    $douPagoDetalle = round($douPagoDetalle);
                    $detalle = $arEmbargo->getPorcentaje() . "% prestacional";
                }
                if ($arEmbargo->getPorcentajeDevengadoPrestacionalMenosDescuentoLey()) {
                    $douPagoDetalle = ((($devengadoPrestacional) - $salud - $pension) * $arEmbargo->getPorcentaje()) / 100;
                    $douPagoDetalle = round($douPagoDetalle);
                    $detalle = $arEmbargo->getPorcentaje() . "% prestacional (menos dcto ley)";
                }
                if ($arEmbargo->getPorcentajeDevengadoPrestacionalMenosDescuentoLeyTransporte()) {
                    $douPagoDetalle = ((($devengadoPrestacional) - $salud - $pension) * $arEmbargo->getPorcentaje()) / 100;
                    $douPagoDetalle = round($douPagoDetalle);
                    $detalle = $arEmbargo->getPorcentaje() . "% prestacional (menos dcto ley + tte)";
                }
                if ($arEmbargo->getPorcentajeDevengadoMenosDescuentoLey()) {
                    $douPagoDetalle = ((($devengado) - $salud - $pension) * $arEmbargo->getPorcentaje()) / 100;
                    $douPagoDetalle = round($douPagoDetalle);
                    $detalle = $arEmbargo->getPorcentaje() . "% devengado (menos dcto ley)";
                }
                if ($arEmbargo->getPorcentajeDevengadoMenosDescuentoLeyTransporte()) {
                    $douPagoDetalle = ((($devengado) - $salud - $pension - $transporte) * $arEmbargo->getPorcentaje()) / 100;
                    $douPagoDetalle = round($douPagoDetalle);
                    $detalle = $arEmbargo->getPorcentaje() . "% devengado (menos dcto ley + tte)";
                }
                if ($arEmbargo->getPorcentajeExcedaSalarioMinimo()) {
                    $salarioMinimoDevengado = ($salarioMinimo / 30) * $arProgramacionDetalle->getDiasTransporte();
                    $baseDescuento = ($devengado) - $salarioMinimoDevengado;
                    if ($baseDescuento > 0) {
                        $douPagoDetalle = ($baseDescuento * $arEmbargo->getPorcentaje()) / 100;
                        $douPagoDetalle = round($douPagoDetalle);
                    }
                    $detalle = $arEmbargo->getPorcentaje() . "% exceda el salario minimo";
                }
                if ($arEmbargo->getPorcentajeSalarioMinimo()) {
                    if ($salarioMinimo > 0) {
                        $douPagoDetalle = ($salarioMinimo * $arEmbargo->getPorcentaje()) / 100;
                        $douPagoDetalle = round($douPagoDetalle);
                    }
                    $detalle = $arEmbargo->getPorcentaje() . "% exceda el salario minimo";
                }
                if ($arEmbargo->getPartesExcedaSalarioMinimo()) {
                    $salarioMinimoDevengado = ($salarioMinimo / 30) * $arProgramacionDetalle->getDiasTransporte();
                    $baseDescuento = (($devengado) - $transporte) - $salarioMinimoDevengado;
                    if ($baseDescuento > 0) {
                        $douPagoDetalle = $baseDescuento / $arEmbargo->getPartes();
                    }
                    $detalle = $arEmbargo->getPartes() . " partes exceda el salario minimo";
                }
                if ($arEmbargo->getPartesExcedaSalarioMinimoMenosDescuentoLey()) {
                    $salarioMinimoDevengado = ($salarioMinimo / 30) * $arProgramacionDetalle->getDiasTransporte();
                    $baseDescuento = (($devengado) - $salud - $pension) - $salarioMinimoDevengado;
                    if ($baseDescuento > 0) {
                        $douPagoDetalle = $baseDescuento / $arEmbargo->getPartes();
                    }
                    $detalle = $arEmbargo->getPartes() . " partes exceda el salario minimo";
                }
                if ($arEmbargo->getPartesExcedaSalarioMinimoPrestacionalesMenosDescuentoLey()) {
                    $salarioMinimoDevengado = ($salarioMinimo / 30) * $arProgramacionDetalle->getDiasTransporte();
                    $baseDescuento = (($devengadoPrestacional) - $salud - $pension) - $salarioMinimoDevengado;
                    if ($baseDescuento > 0) {
                        $douPagoDetalle = $baseDescuento / $arEmbargo->getPartes();
                    }
                    $detalle = $arEmbargo->getPartes() . " partes exceda el salario minimo";
                }
                if ($arEmbargo->getValidarMontoMaximo()) {
                    $saldo = $arEmbargo->getVrMontoMaximo() - $arEmbargo->getDescuento();
                    if ($saldo > 0) {
                        if ($saldo < $douPagoDetalle) {
                            $douPagoDetalle = round($saldo);
                        }
                    } else {
                        $douPagoDetalle = 0;
                    }
                }

                if ($arEmbargo->getAfectaNomina()) {
                    if ($douPagoDetalle > 0) {
                        $arConcepto = $arEmbargo->getEmbargoTipoRel()->getConceptoRel();
                        $pagoDetalle = round($douPagoDetalle);
                        $arPagoDetalle = new RhuPagoDetalle();
                        $arPagoDetalle->setEmbargoRel($arEmbargo);
                        $arPagoDetalle->setDetalle($detalle);
                        $this->getValoresPagoDetalle($arrDatosGenerales, $arPagoDetalle, $arConcepto, $pagoDetalle);
                        $em->persist($arPagoDetalle);
                    }
                }
            }

            $arPago->setVrNeto($arrDatosGenerales['neto']);
            $arPago->setVrDeduccion($arrDatosGenerales['deduccion']);
            $arPago->setVrDevengado($arrDatosGenerales['devengado']);
            $arPago->setVrIngresoBaseCotizacion($arrDatosGenerales['ingresoBaseCotizacion']);
            $arPago->setVrIngresoBasePrestacion($arrDatosGenerales['ingresoBasePrestacion']);
            $arPago->setVrIngresoBasePrestacionVacacion($arrDatosGenerales['ingresoBasePrestacionVacacion']);
            //Calcular provision
            $this->liquidarProvision($arPago, $arConfiguracion);

            $em->persist($arPago);
            $neto = $arrDatosGenerales['neto'];
        }

        if ($arProgramacion->getCodigoPagoTipoFk() == 'ANT') {
            if ($arProgramacionDetalle->getVrAnticipo() > 0) {
                $arConcepto = $em->getRepository(RhuConcepto::class)->find($arConfiguracion['codigoConceptoAnticipoFk']);
                if ($arConcepto) {
                    $arPago = new RhuPago();
                    $arContrato = $em->getRepository(RhuContrato::class)->find($arProgramacionDetalle->getCodigoContratoFk());
                    $arPago->setPagoTipoRel($arProgramacion->getPagoTipoRel());
                    $arPago->setEmpleadoRel($arProgramacionDetalle->getEmpleadoRel());
                    $arPago->setContratoRel($arProgramacionDetalle->getContratoRel());
                    $arPago->setGrupoRel($arProgramacionDetalle->getContratoRel()->getGrupoRel());
                    $arPago->setProgramacionDetalleRel($arProgramacionDetalle);
                    $arPago->setProgramacionRel($arProgramacion);
                    $arPago->setVrSalarioContrato($arContrato->getVrSalario());
                    $arPago->setCuenta($arContrato->getEmpleadoRel()->getCuenta());
                    $arPago->setCuentaTipo($arContrato->getEmpleadoRel()->getCodigoCuentaTipoFk());
                    $arPago->setBancoRel($arContrato->getEmpleadoRel()->getBancoRel());
                    $arPago->setUsuario($usuario);
                    $arPago->setEntidadPensionRel($arContrato->getEntidadPensionRel());
                    $arPago->setEntidadSaludRel($arContrato->getEntidadSaludRel());
                    $arPago->setFechaDesde($arProgramacion->getFechaDesde());
                    $arPago->setFechaHasta($arProgramacion->getFechaHasta());
                    $arPago->setFechaDesdeContrato($arProgramacionDetalle->getFechaDesdeContrato());
                    $arPago->setFechaDesdeContrato($arProgramacionDetalle->getFechaHastaContrato());
                    $arPago->setCodigoSoporteContratoFk($arProgramacionDetalle->getCodigoSoporteContratoFk());

                    $arrDatosGenerales = array(
                        'pago' => $arPago,
                        'devengado' => 0,
                        'deduccion' => 0,
                        'ingresoBaseCotizacion' => 0,
                        'ingresoBasePrestacion' => 0,
                        'ingresoBasePrestacionVacacion' => 0,
                        'neto' => 0);
                    $valorDia = $arProgramacionDetalle->getVrDia();
                    $valorHora = $arProgramacionDetalle->getVrHora();
                    $factorHorasDia = $arProgramacionDetalle->getFactorHorasDia();
                    $auxilioTransporte = $arConfiguracion['vrAuxilioTransporte'];
                    $diaAuxilioTransporte = $auxilioTransporte / 30;
                    $salarioMinimo = $arConfiguracion['vrSalarioMinimo'];
                    $ibcVacaciones = 0;
                    $vrPagoIncapacidad = 0;
                    $vrPagoLicencia = 0;
                    $devengado = 0;
                    $devengadoPrestacional = 0;
                    $salud = 0;
                    $pension = 0;
                    $transporte = 0;

                    //Detalle
                    $arPagoDetalle = new RhuPagoDetalle();
                    $pagoDetalle = round($arProgramacionDetalle->getVrAnticipo());
                    $arPagoDetalle->setDetalle('Anticipo');
                    $this->getValoresPagoDetalle($arrDatosGenerales, $arPagoDetalle, $arConcepto, $pagoDetalle);
                    $em->persist($arPagoDetalle);

                    $arPago->setVrNeto($arrDatosGenerales['neto']);
                    $arPago->setVrDeduccion($arrDatosGenerales['deduccion']);
                    $arPago->setVrDevengado($arrDatosGenerales['devengado']);
                    $arPago->setVrIngresoBaseCotizacion($arrDatosGenerales['ingresoBaseCotizacion']);
                    $arPago->setVrIngresoBasePrestacion($arrDatosGenerales['ingresoBasePrestacion']);
                    $arPago->setVrIngresoBasePrestacionVacacion($arrDatosGenerales['ingresoBasePrestacionVacacion']);
                    $em->persist($arPago);
                    $neto = $arrDatosGenerales['neto'];
                }
            }
        }

        if ($arProgramacion->getCodigoPagoTipoFk() == 'PRI') {
            $arPago = new RhuPago();
            $arContrato = $em->getRepository(RhuContrato::class)->find($arProgramacionDetalle->getCodigoContratoFk());
            $arPago->setPagoTipoRel($arProgramacion->getPagoTipoRel());
            $arPago->setEmpleadoRel($arProgramacionDetalle->getEmpleadoRel());
            $arPago->setContratoRel($arProgramacionDetalle->getContratoRel());
            $arPago->setTiempoRel($arProgramacionDetalle->getContratoRel()->getTiempoRel());
            $arPago->setGrupoRel($arProgramacionDetalle->getContratoRel()->getGrupoRel());
            $arPago->setProgramacionDetalleRel($arProgramacionDetalle);
            $arPago->setProgramacionRel($arProgramacion);
            $arPago->setVrSalarioContrato($arContrato->getVrSalario());
            $arPago->setCuenta($arContrato->getEmpleadoRel()->getCuenta());
            $arPago->setCuentaTipo($arContrato->getEmpleadoRel()->getCodigoCuentaTipoFk());
            $arPago->setBancoRel($arContrato->getEmpleadoRel()->getBancoRel());
            $arPago->setUsuario($usuario);
            $arPago->setEntidadPensionRel($arContrato->getEntidadPensionRel());
            $arPago->setEntidadSaludRel($arContrato->getEntidadSaludRel());
            $arPago->setFechaDesde($arProgramacion->getFechaDesde());
            $arPago->setFechaHasta($arProgramacion->getFechaHasta());
            $arPago->setFechaDesdeContrato($arProgramacionDetalle->getFechaDesdeContrato());
            $arPago->setFechaDesdeContrato($arProgramacionDetalle->getFechaHastaContrato());
            $arPago->setCodigoSoporteContratoFk($arProgramacionDetalle->getCodigoSoporteContratoFk());

            $arrDatosGenerales = array(
                'pago' => $arPago,
                'devengado' => 0,
                'deduccion' => 0,
                'ingresoBaseCotizacion' => 0,
                'ingresoBasePrestacion' => 0,
                'ingresoBasePrestacionVacacion' => 0,
                'neto' => 0);
            $valorDia = $arProgramacionDetalle->getVrDia();
            $valorHora = $arProgramacionDetalle->getVrHora();
            $factorHorasDia = $arProgramacionDetalle->getFactorHorasDia();
            $auxilioTransporte = $arConfiguracion['vrAuxilioTransporte'];
            $diaAuxilioTransporte = $auxilioTransporte / 30;
            $salarioMinimo = $arConfiguracion['vrSalarioMinimo'];
            $ibcVacaciones = 0;
            $vrPagoIncapacidad = 0;
            $vrPagoLicencia = 0;
            $devengado = 0;
            $devengadoPrestacional = 0;
            $salud = 0;
            $pension = 0;
            $transporte = 0;

            //Prima
            $dias = $arProgramacionDetalle->getDias();

            /*if ($arConfiguracion->getDiasAusentismoPrimas()) {
                $dias = $arProgramacionDetalle->getDias() - $arProgramacionDetalle->getDiasAusentismo();
                if ($arProgramacionDetalle->getDiasAusentismoPropuesto() > 0) {
                    $dias = $arProgramacionDetalle->getDias() - $arProgramacionDetalle->getDiasAusentismoPropuesto();
                }
            }*/

            $salarioPrima = $arProgramacionDetalle->getVrSalarioPrima();
            /*if ($arProgramacionDetalle->getVrSalarioPrimaPropuesto() > 0) {
                $salarioPrima = $arProgramacionDetalle->getVrSalarioPrimaPropuesto();
            }*/


            $douPrima = round(($salarioPrima * $dias) / 360);
            $arConcepto = $em->getRepository(RhuConcepto::class)->find($arConfiguracion['codigoConceptoPrimaFk']);
            $arPagoDetalle = new RhuPagoDetalle();
            $arPagoDetalle->setDias($dias);
            $this->getValoresPagoDetalle($arrDatosGenerales, $arPagoDetalle, $arConcepto, $douPrima);
            $em->persist($arPagoDetalle);

            $devengadoPrestacional = $douPrima;

            //Adicionales
            $arAdicionales = $em->getRepository(RhuAdicional::class)->programacionPago($arProgramacionDetalle->getCodigoEmpleadoFk(), $arContrato->getCodigoContratoPk(), $arProgramacion->getCodigoPagoTipoFk(), $arProgramacion->getFechaDesde()->format('Y/m/d'), $arProgramacion->getFechaHasta()->format('Y/m/d'));
            foreach ($arAdicionales as $arAdicional) {
                $arConcepto = $em->getRepository(RhuConcepto::class)->find($arAdicional['codigoConceptoFk']);
                $arPagoDetalle = new RhuPagoDetalle();
                $pagoDetalle = $arAdicional['vrValor'];
                if ($arAdicional['aplicaDiaLaborado']) {
                    $valorDia = $arAdicional['vrValor'] / $arProgramacion->getDias();
                    $pagoDetalle = $valorDia * $arProgramacionDetalle->getDias();
                }
                $pagoDetalle = round($pagoDetalle);
                if ($arConcepto->getOperacion() == 1) {
                    $devengado += $pagoDetalle;
                    if ($arConcepto->getGeneraIngresoBasePrestacion()) {
                        $devengadoPrestacional += $pagoDetalle;
                    }
                }
                $arPagoDetalle->setDetalle($arAdicional['detalle']);
                $this->getValoresPagoDetalle($arrDatosGenerales, $arPagoDetalle, $arConcepto, $pagoDetalle);
                $em->persist($arPagoDetalle);
            }

            //Procesar creditos
            $arCreditos = $em->getRepository(RhuCredito::class)->findBy(array('codigoEmpleadoFk' => $arProgramacionDetalle->getCodigoEmpleadoFk(), 'codigoCreditoPagoTipoFk' => 'NOM', 'estadoPagado' => 0, 'estadoSuspendido' => 0, "aplicarCuotaPrima" => 1));
            foreach ($arCreditos as $arCredito) {
                if ($arCredito->getVrSaldo() > 0) {
                    $descontarCuota = true;
                    $numeroCuotas = $arCredito->getNumeroCuotas();
                    $numeroCuotaActual = $arCredito->getNumeroCuotaActual();
                    if ($arCredito->getValidarCuotas() == 1) {
                        if ($numeroCuotaActual > $numeroCuotas) {
                            $descontarCuota = false;
                        }
                    }
                    if ($arCredito->getFechaInicio() > $arProgramacionDetalle->getFechaHasta()) {
                        $descontarCuota = false;
                    }
                    if ($arCredito->getFechaInicio() < $arContrato->getFechaDesde()) {
                        $descontarCuota = false;
                    }
                    if ($descontarCuota) {
                        $arConcepto = $arCredito->getCreditoTipoRel()->getConceptoRel();
                        if ($arCredito->getVrSaldo() >= $arCredito->getVrCuota()) {
                            $cuota = $arCredito->getVrCuota();
                        } else {
                            $cuota = $arCredito->getVrSaldo();
                        }
                        $pagoDetalle = $cuota;
                        $arPagoDetalle = new RhuPagoDetalle();
                        $arPagoDetalle->setDetalle($arCredito->getCreditoTipoRel()->getNombre());
                        $arPagoDetalle->setCreditoRel($arCredito);
                        $this->getValoresPagoDetalle($arrDatosGenerales, $arPagoDetalle, $arConcepto, $pagoDetalle);
                        $em->persist($arPagoDetalle);
                    }
                }
            }

            //Embargos
            $arEmbargos = $em->getRepository(RhuEmbargo::class)->findBy(array('codigoEmpleadoFk' => $arProgramacionDetalle->getCodigoEmpleadoFk(), 'estadoActivo' => 1, 'afectaPrima' => 1));
            foreach ($arEmbargos as $arEmbargo) {
                $douPagoDetalle = 0;
                $detalle = "";
                if ($arEmbargo->getValorFijo()) {
                    $douPagoDetalle = $arEmbargo->getVrValor();
                }
                if ($arEmbargo->getPorcentajeDevengado()) {
                    $douPagoDetalle = (($devengado) * $arEmbargo->getPorcentaje()) / 100;
                    $douPagoDetalle = round($douPagoDetalle);
                    $detalle = $arEmbargo->getPorcentaje() . "% devengado";
                }
                if ($arEmbargo->getPorcentajeDevengadoPrestacional()) {
                    $douPagoDetalle = (($devengadoPrestacional) * $arEmbargo->getPorcentaje()) / 100;
                    $douPagoDetalle = round($douPagoDetalle);
                    $detalle = $arEmbargo->getPorcentaje() . "% prestacional";
                }
                if ($arEmbargo->getPorcentajeDevengadoPrestacionalMenosDescuentoLey()) {
                    $douPagoDetalle = ((($devengadoPrestacional) - $salud - $pension) * $arEmbargo->getPorcentaje()) / 100;
                    $douPagoDetalle = round($douPagoDetalle);
                    $detalle = $arEmbargo->getPorcentaje() . "% prestacional (menos dcto ley)";
                }
                if ($arEmbargo->getPorcentajeDevengadoPrestacionalMenosDescuentoLeyTransporte()) {
                    $douPagoDetalle = ((($devengadoPrestacional) - $salud - $pension) * $arEmbargo->getPorcentaje()) / 100;
                    $douPagoDetalle = round($douPagoDetalle);
                    $detalle = $arEmbargo->getPorcentaje() . "% prestacional (menos dcto ley + tte)";
                }
                if ($arEmbargo->getPorcentajeDevengadoMenosDescuentoLey()) {
                    $douPagoDetalle = ((($devengado) - $salud - $pension) * $arEmbargo->getPorcentaje()) / 100;
                    $douPagoDetalle = round($douPagoDetalle);
                    $detalle = $arEmbargo->getPorcentaje() . "% devengado (menos dcto ley)";
                }
                if ($arEmbargo->getPorcentajeDevengadoMenosDescuentoLeyTransporte()) {
                    $douPagoDetalle = ((($devengado) - $salud - $pension - $transporte) * $arEmbargo->getPorcentaje()) / 100;
                    $douPagoDetalle = round($douPagoDetalle);
                    $detalle = $arEmbargo->getPorcentaje() . "% devengado (menos dcto ley + tte)";
                }
                if ($arEmbargo->getPorcentajeExcedaSalarioMinimo()) {
                    $salarioMinimoDevengado = ($salarioMinimo / 30) * $arProgramacionDetalle->getDiasTransporte();
                    $baseDescuento = ($devengado) - $salarioMinimoDevengado;
                    if ($baseDescuento > 0) {
                        $douPagoDetalle = ($baseDescuento * $arEmbargo->getPorcentaje()) / 100;
                        $douPagoDetalle = round($douPagoDetalle);
                    }
                    $detalle = $arEmbargo->getPorcentaje() . "% exceda el salario minimo";
                }
                if ($arEmbargo->getPorcentajeSalarioMinimo()) {
                    if ($salarioMinimo > 0) {
                        $douPagoDetalle = ($salarioMinimo * $arEmbargo->getPorcentaje()) / 100;
                        $douPagoDetalle = round($douPagoDetalle);
                    }
                    $detalle = $arEmbargo->getPorcentaje() . "% exceda el salario minimo";
                }
                if ($arEmbargo->getPartesExcedaSalarioMinimo()) {
                    $salarioMinimoDevengado = ($salarioMinimo / 30) * $arProgramacionDetalle->getDiasTransporte();
                    $baseDescuento = (($devengado) - $transporte) - $salarioMinimoDevengado;
                    if ($baseDescuento > 0) {
                        $douPagoDetalle = $baseDescuento / $arEmbargo->getPartes();
                    }
                    $detalle = $arEmbargo->getPartes() . " partes exceda el salario minimo";
                }
                if ($arEmbargo->getPartesExcedaSalarioMinimoMenosDescuentoLey()) {
                    $salarioMinimoDevengado = ($salarioMinimo / 30) * $arProgramacionDetalle->getDiasTransporte();
                    $baseDescuento = (($devengado) - $salud - $pension) - $salarioMinimoDevengado;
                    if ($baseDescuento > 0) {
                        $douPagoDetalle = $baseDescuento / $arEmbargo->getPartes();
                    }
                    $detalle = $arEmbargo->getPartes() . " partes exceda el salario minimo";
                }
                if ($arEmbargo->getPartesExcedaSalarioMinimoPrestacionalesMenosDescuentoLey()) {
                    $salarioMinimoDevengado = ($salarioMinimo / 30) * $arProgramacionDetalle->getDiasTransporte();
                    $baseDescuento = (($devengadoPrestacional) - $salud - $pension) - $salarioMinimoDevengado;
                    if ($baseDescuento > 0) {
                        $douPagoDetalle = $baseDescuento / $arEmbargo->getPartes();
                    }
                    $detalle = $arEmbargo->getPartes() . " partes exceda el salario minimo";
                }
                if ($arEmbargo->getValidarMontoMaximo()) {
                    $saldo = $arEmbargo->getVrMontoMaximo() - $arEmbargo->getDescuento();
                    if ($saldo > 0) {
                        if ($saldo < $douPagoDetalle) {
                            $douPagoDetalle = round($saldo);
                        }
                    } else {
                        $douPagoDetalle = 0;
                    }
                }

                if ($arEmbargo->getAfectaNomina()) {
                    if ($douPagoDetalle > 0) {
                        $arConcepto = $arEmbargo->getEmbargoTipoRel()->getConceptoRel();
                        $pagoDetalle = round($douPagoDetalle);
                        $arPagoDetalle = new RhuPagoDetalle();
                        $arPagoDetalle->setEmbargoRel($arEmbargo);
                        $arPagoDetalle->setDetalle($detalle);
                        $this->getValoresPagoDetalle($arrDatosGenerales, $arPagoDetalle, $arConcepto, $pagoDetalle);
                        $em->persist($arPagoDetalle);
                    }
                }
            }

            $arPago->setVrNeto($arrDatosGenerales['neto']);
            $arPago->setVrDeduccion($arrDatosGenerales['deduccion']);
            $arPago->setVrDevengado($arrDatosGenerales['devengado']);
            $arPago->setVrIngresoBaseCotizacion($arrDatosGenerales['ingresoBaseCotizacion']);
            $arPago->setVrIngresoBasePrestacion($arrDatosGenerales['ingresoBasePrestacion']);
            $arPago->setVrIngresoBasePrestacionVacacion($arrDatosGenerales['ingresoBasePrestacionVacacion']);

            $this->liquidarProvision($arPago, $arConfiguracion);
            $em->persist($arPago);
            $neto = $arrDatosGenerales['neto'];
        }

        return $neto;
    }

    /**
     * @param $arPago RhuPago
     * @return int|mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public function liquidar($arPago)
    {
        $em = $this->getEntityManager();
        $douDeducciones = 0;
        $douDevengado = 0;
        $arPagoDetalles = $em->getRepository(RhuPagoDetalle::class)->findBy(array('codigoPagoFk' => $arPago->getCodigoPagoPk()));
        foreach ($arPagoDetalles as $arPagoDetalle) {
            if ($arPagoDetalle->getOperacion() == 1) {
                $douDevengado += $arPagoDetalle->getVrPago();
            }
            if ($arPagoDetalle->getOperacion() == -1) {
                $douDeducciones += $arPagoDetalle->getVrPago();
            }

        }
        $douNeto = $douDevengado - $douDeducciones;
        $arPago->setVrNeto($douNeto);
        $arPago->setVrDevengado($douDevengado);
        $arPago->setVrDeduccion($douDeducciones);
        $em->persist($arPago);
        return $douNeto;
    }

    /**
     * @param $arProgramacionDetalle
     * @return mixed
     */
    private function getHoras($arProgramacionDetalle)
    {
        $arrHoras['D'] = array('tipo' => 'D', 'cantidad' => $arProgramacionDetalle->getHorasDiurnas(), 'clave' => 0);
        $arrHoras['DS'] = array('tipo' => 'DS', 'cantidad' => $arProgramacionDetalle->getHorasDescanso(), 'clave' => 1);
        $arrHoras['EFD'] = array('tipo' => 'DS', 'cantidad' => $arProgramacionDetalle->getHorasExtrasFestivasDiurnas(), 'clave' => 2);
        $arrHoras['EFN'] = array('tipo' => 'DS', 'cantidad' => $arProgramacionDetalle->getHorasExtrasFestivasNocturnas(), 'clave' => 3);
        $arrHoras['EOD'] = array('tipo' => 'DS', 'cantidad' => $arProgramacionDetalle->getHorasExtrasOrdinariasDiurnas(), 'clave' => 4);
        $arrHoras['EON'] = array('tipo' => 'DS', 'cantidad' => $arProgramacionDetalle->getHorasExtrasOrdinariasNocturnas(), 'clave' => 5);
        $arrHoras['FD'] = array('tipo' => 'DS', 'cantidad' => $arProgramacionDetalle->getHorasFestivasDiurnas(), 'clave' => 6);
        $arrHoras['FN'] = array('tipo' => 'DS', 'cantidad' => $arProgramacionDetalle->getHorasFestivasNocturnas(), 'clave' => 7);
        $arrHoras['N'] = array('tipo' => 'DS', 'cantidad' => $arProgramacionDetalle->getHorasNocturnas(), 'clave' => 8);
        $arrHoras['RFD'] = array('tipo' => 'DS', 'cantidad' => $arProgramacionDetalle->getHorasRecargoFestivoDiurno(), 'clave' => 9);
        $arrHoras['RFN'] = array('tipo' => 'DS', 'cantidad' => $arProgramacionDetalle->getHorasRecargoFestivoNocturno(), 'clave' => 10);
        $arrHoras['RN'] = array('tipo' => 'DS', 'cantidad' => $arProgramacionDetalle->getHorasRecargoNocturno(), 'clave' => 11);
        return $arrHoras;
    }

    private function getValoresPagoDetalle(&$arrDatosGenerales, $arPagoDetalle, $arConcepto, $pagoDetalle)
    {
        if ($arConcepto) {
            $pagoDetalle = round($pagoDetalle);
            $arPagoDetalle->setPagoRel($arrDatosGenerales['pago']);
            $arPagoDetalle->setVrPago($pagoDetalle);
            if ($arConcepto) {
                $arPagoDetalle->setConceptoRel($arConcepto);
            }
            $pagoDetalleOperado = $pagoDetalle * $arConcepto->getOperacion();
            $arPagoDetalle->setVrPagoOperado($pagoDetalleOperado);
            $arPagoDetalle->setOperacion($arConcepto->getOperacion());
            if ($arConcepto->getOperacion() == -1) {
                $arPagoDetalle->setVrDeduccion($pagoDetalle);
                $arrDatosGenerales['deduccion'] += $pagoDetalle;
            } else {
                $arPagoDetalle->setVrDevengado($pagoDetalle);
                $arrDatosGenerales['devengado'] += $pagoDetalle;
            }
            $arrDatosGenerales['neto'] += $pagoDetalleOperado;

            if ($arConcepto->getGeneraIngresoBaseCotizacion()) {
                $arrDatosGenerales['ingresoBaseCotizacion'] += $pagoDetalleOperado;
                $arPagoDetalle->setVrIngresoBaseCotizacion($pagoDetalleOperado);
            }

            if ($arConcepto->getGeneraIngresoBasePrestacion()) {
                $arrDatosGenerales['ingresoBasePrestacion'] += $pagoDetalleOperado;
                $arPagoDetalle->setVrIngresoBasePrestacion($pagoDetalleOperado);
            }
            if ($arConcepto->getGeneraIngresoBasePrestacionVacacion()) {
                $arrDatosGenerales['ingresoBasePrestacionVacacion'] += $pagoDetalleOperado;
                $arPagoDetalle->setVrIngresoBasePrestacionVacacion($pagoDetalleOperado);
            }
        } else {
            Mensajes::error("El concepto tiene un error en el pago del contrato " . $arrDatosGenerales['pago']->getContratoRel()->getCodigoContratoPk());
        }
    }

    public function resumenConceptos($codigoProgramacion)
    {
        $query = $this->_em->createQueryBuilder()
            ->select('p.codigoPagoPk')
            ->from(RhuPago::class, 'p')
            ->where("p.codigoProgramacionFk = {$codigoProgramacion}");
        return $this->_em->createQueryBuilder()
            ->select('SUM(pd.vrPago) AS total')
            ->addSelect('c.nombre')
            ->addSelect('c.operacion')
            ->addSelect('pd.codigoConceptoFk')
            ->from(RhuPagoDetalle::class, 'pd')
            ->leftJoin('pd.conceptoRel', 'c')
            ->where("pd.codigoPagoFk IN ({$query})")
            ->groupBy('c.nombre,pd.codigoConceptoFk,c.operacion')->getQuery()->execute();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCodigoPago($id)
    {
        return $this->_em->createQueryBuilder()
            ->from(RhuPago::class, 'p')
            ->select("p.codigoProgramacionDetalleFk = {$id}")->getQuery()->execute();
    }

    public function informe()
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuPago::class, 'p')
            ->select('p');
        if ($session->get('filtroRhuInformePagoCodigoEmpleado') != null) {
            $queryBuilder->andWhere("p.codigoEmpleadoFk = {$session->get('filtroRhuInformePagoCodigoEmpleado')}");
        }
        if ($session->get('filtroRhuInformePagoFechaDesde') != null) {
            $queryBuilder->andWhere("p.fechaDesde >= '{$session->get('filtroRhuInformePagoFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroRhuInformePagoFechaHasta') != null) {
            $queryBuilder->andWhere("p.fechaHasta <= '{$session->get('filtroRhuInformePagoFechaHasta')} 23:59:59'");
        }
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $fechaDesde \DateTime | string
     * @param $fechaHasta \DateTime | string
     * @param $codigoContrato
     * @return int
     */
    public function diasAusentismo($fechaDesde, $fechaHasta, $codigoContrato)
    {
        $fechaDesde = $fechaDesde instanceof \DateTime ? $fechaDesde->format("Y-m-d") : $fechaDesde;
        $fechaHasta = $fechaHasta instanceof \DateTime ? $fechaHasta->format("Y-m-d") : $fechaHasta;

        $em = $this->getEntityManager();
        $dql = "SELECT SUM(p.diasAusentismo) as diasAusentismo FROM App\Entity\RecursoHumano\RhuPago p "
            . "WHERE p.codigoContratoFk = " . $codigoContrato . " "
            . "AND p.fechaDesde >= '" . $fechaDesde . "' AND p.fechaDesde <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $intDiasAusentismo = $arrayResultado[0]['diasAusentismo'];
        if ($intDiasAusentismo == null) {
            $intDiasAusentismo = 0;
        }
        return $intDiasAusentismo;
    }

    public function ibpSalario($fechaDesde, $fechaHasta, $codigoContrato)
    {
        $em = $this->getEntityManager();
        $dql = "SELECT SUM(p.vrSalario) as ibp FROM FROM App\Entity\RecursoHumano\RhuPago p "
            . "WHERE p.estadoAprobado = 1 AND p.codigoContratoFk = " . $codigoContrato . " "
            . "AND p.fechaDesde >= '" . $fechaDesde . "' AND p.fechaDesde <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $ibp = $arrayResultado[0]['ibp'];
        if ($ibp == null) {
            $ibp = 0;
        }
        return $ibp;
    }

    public function listaImpresionDql($codigoProgramacionPago = "", $porFecha = false, $fechaDesde = "", $fechaHasta = "", $codigoPagoTipo = "", $codigoGrupo = "")
    {
        $qb = $this->_em->createQueryBuilder()->from(RhuPago::class, 'p');
        $qb->select('p,e')
            ->addSelect('abs(e.numeroIdentificacion) AS HIDDEN ordered')
            ->join('p.empleadoRel', 'e')
            ->leftJoin('p.contratoRel', 'c')
            ->where('p.codigoPagoPk <> 0');
        if ($codigoPagoTipo != "") {
            $qb->andWhere("p.codigoPagoTipoFk = {$codigoPagoTipo}");
        }
        if ($codigoGrupo != "") {
            $qb->andWhere(" c.codigoGrupoFk = {$codigoGrupo}");
        }
        if ($codigoProgramacionPago != "") {
            $qb->andWhere("p.codigoProgramacionFk = {$codigoProgramacionPago}");
        }
        if ($porFecha == true) {
            if ($fechaDesde != "" && $fechaHasta != "") {
                $qb->andWhere(" (p.fechaDesde >= '" . $fechaDesde . "' AND p.fechaDesde <= '" . $fechaHasta . "')");
            }
        }
        $qb->orderBy('ordered', 'ASC');
        return $qb->getDQL();
    }

    /**
     * @param $arPago RhuPago
     * @throws \Doctrine\ORM\ORMException
     */
    public function generarCuentaPagar($arPago)
    {
        $em = $this->getEntityManager();
        $arTercero = $em->getRepository(RhuEmpleado::class)->terceroTesoreria($arPago->getEmpleadoRel());
        $arCuentaPagarTipo = $em->getRepository(TesCuentaPagarTipo::class)->find($arPago->getPagoTipoRel()->getCodigoCuentaPagarTipoFk());
        $arCuentaPagar = New TesCuentaPagar();
        $arCuentaPagar->setCuentaPagarTipoRel($arCuentaPagarTipo);
        $arCuentaPagar->setTerceroRel($arTercero);
        $arCuentaPagar->setBancoRel($arPago->getEmpleadoRel()->getBancoRel());
        $arCuentaPagar->setCuenta($arPago->getEmpleadoRel()->getCuenta());
        $arCuentaPagar->setModulo('rhu');
        $arCuentaPagar->setCodigoDocumento($arPago->getCodigoPagoPk());
        $arCuentaPagar->setNumeroDocumento($arPago->getNumero());
        $arCuentaPagar->setFecha($arPago->getFechaDesde());
        $arCuentaPagar->setFechaVence($arPago->getFechaDesde());
        $arCuentaPagar->setVrSubtotal($arPago->getVrDevengado());
        $arCuentaPagar->setVrTotal($arPago->getVrNeto());
        $arCuentaPagar->setVrSaldoOriginal($arPago->getVrNeto());
        $arCuentaPagar->setVrSaldo($arPago->getVrNeto());
        $arCuentaPagar->setVrSaldoOperado($arPago->getVrNeto() * $arCuentaPagarTipo->getOperacion());
        $arCuentaPagar->setOperacion(1);
        $arCuentaPagar->setEstadoAutorizado(1);
        $arCuentaPagar->setEstadoAprobado(1);
        $em->persist($arCuentaPagar);

    }

    public function getPago($codigoProgramacion)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuPago::class, 'p')
            ->select('p.codigoPagoPk')
            ->select('p.codigoPagoTipoFk')
            ->where("p.codigoProgramacionFk = {$codigoProgramacion}");

        return $queryBuilder->getQuery()->getArrayResult();
    }

    public function programacion($codigoProgramacion)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(RhuPago::class, 'p')
            ->select('p.codigoPagoPk')
            ->addSelect('p.numero')
            ->addSelect('p.codigoContratoFk')
            ->addSelect('p.fechaDesde')
            ->addSelect('p.fechaHasta')
            ->addSelect('p.fechaDesdeContrato')
            ->addSelect('p.fechaHastaContrato')
            ->addSelect('p.vrSalarioContrato')
            ->addSelect('p.vrDevengado')
            ->addSelect('p.vrDeduccion')
            ->addSelect('p.vrNeto')
            ->addSelect('p.comentario')
            ->addSelect('g.nombre as grupoNombre')
            ->addSelect('e.codigoIdentificacionFk')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.cuenta')
            ->addSelect('b.nombre as bancoNombre')
            ->addSelect('ep.nombre as entidadPension')
            ->addSelect('es.nombre as entidadSalud')
            ->addSelect('ca.nombre as cargo')
            ->addSelect('p.codigoSoporteContratoFk')
            ->leftJoin('p.empleadoRel', 'e')
            ->leftJoin('p.grupoRel', 'g')
            ->leftJoin('e.bancoRel', 'b')
            ->leftJoin('p.entidadPensionRel', 'ep')
            ->leftJoin('p.entidadSaludRel', 'es')
            ->leftJoin('p.contratoRel', 'c')
            ->leftJoin('c.cargoRel', 'ca')
            ->where("p.codigoProgramacionFk = '{$codigoProgramacion}'");
        $arPagos = $queryBuilder->getQuery()->getResult();
        $i = 0;
        foreach ($arPagos as $arPago) {
            $queryBuilder = $em->createQueryBuilder()->from(RhuPagoDetalle::class, 'pd')
                ->select('pd.codigoPagoDetallePk')
                ->addSelect('pd.codigoConceptoFk')
                ->addSelect('c.nombre as conceptoNombre')
                ->addSelect('pd.vrPago')
                ->addSelect('pd.operacion')
                ->addSelect('pd.vrPagoOperado')
                ->addSelect('pd.horas')
                ->addSelect('pd.vrHora')
                ->addSelect('pd.porcentaje')
                ->addSelect('pd.dias')
                ->addSelect('pd.detalle')
                ->addSelect('pd.vrDeduccion')
                ->addSelect('pd.vrDevengado')
                ->addSelect('pd.vrIngresoBaseCotizacion')
                ->addSelect('pd.vrIngresoBasePrestacion')
                ->leftJoin('pd.conceptoRel', 'c')
                ->where("pd.codigoPagoFk = {$arPago['codigoPagoPk']}");
            $arPagoDetalles = $queryBuilder->getQuery()->getResult();
            if (!$arPagoDetalles) {
                $arPagoDetalles = [];
            }
            if (is_null($arPago['codigoSoporteContratoFk'])) {
                $arPago['codigoSoporteContratoFk'] = 0;
            }
            $queryBuilder = $em->createQueryBuilder()->from(TurProgramacionRespaldo::class, 'pr')
                ->select('pr.codigoProgramacionRespaldoPk')
                ->addSelect('pr.dia1')
                ->addSelect('pr.dia2')
                ->addSelect('pr.dia3')
                ->addSelect('pr.dia4')
                ->addSelect('pr.dia5')
                ->addSelect('pr.dia6')
                ->addSelect('pr.dia7')
                ->addSelect('pr.dia8')
                ->addSelect('pr.dia9')
                ->addSelect('pr.dia10')
                ->addSelect('pr.dia11')
                ->addSelect('pr.dia12')
                ->addSelect('pr.dia13')
                ->addSelect('pr.dia14')
                ->addSelect('pr.dia15')
                ->addSelect('pr.dia16')
                ->addSelect('pr.dia17')
                ->addSelect('pr.dia18')
                ->addSelect('pr.dia19')
                ->addSelect('pr.dia20')
                ->addSelect('pr.dia21')
                ->addSelect('pr.dia22')
                ->addSelect('pr.dia23')
                ->addSelect('pr.dia24')
                ->addSelect('pr.dia25')
                ->addSelect('pr.dia26')
                ->addSelect('pr.dia27')
                ->addSelect('pr.dia28')
                ->addSelect('pr.dia29')
                ->addSelect('pr.dia30')
                ->addSelect('pr.dia31')
                ->where("pr.codigoSoporteContratoFk = {$arPago['codigoSoporteContratoFk']}");
            $arProgramacionesRespaldo = $queryBuilder->getQuery()->getResult();
            if (!$arProgramacionesRespaldo) {
                $arProgramacionesRespaldo = [];
            }

            $arPagos[$i]['arrDetalles'] = $arPagoDetalles;
            $arPagos[$i]['arrProgramaciones'] = $arProgramacionesRespaldo;
            $arPagos[$i]['fechaDesde'] = $arPago['fechaDesde'] ? $arPago['fechaDesde']->format('Y-m-d') : null;
            $arPagos[$i]['fechaHasta'] = $arPago['fechaHasta'] ? $arPago['fechaHasta']->format('Y-m-d') : null;
            $arPagos[$i]['fechaDesdeContrato'] = $arPago['fechaDesdeContrato'] ? $arPago['fechaDesdeContrato']->format('Y-m-d') : null;
            $arPagos[$i]['fechaHastaContrato'] = $arPago['fechaHastaContrato'] ? $arPago['fechaHastaContrato']->format('Y-m-d') : null;

            $i++;
        }
        return $arPagos;
    }

    public function regenerarProvision($fechaDesde, $fechaHasta)
    {
        $em = $this->getEntityManager();
        $arrConfiguracionNomina = $em->getRepository(RhuConfiguracion::class)->provision();
        $porcentajeCesantia = $arrConfiguracionNomina['provisionPorcentajeCesantia'];
        $porcentajeInteres = $arrConfiguracionNomina['provisionPorcentajeInteres'];
        $porcentajePrima = $arrConfiguracionNomina['provisionPorcentajePrima'];
        $porcentajeVacacion = $arrConfiguracionNomina['provisionPorcentajeVacacion'];

        $queryBuilder = $em->createQueryBuilder()->from(RhuPago::class, 'p')
            ->select('p.codigoPagoPk')
            ->where("p.fechaDesde >= '{$fechaDesde}' AND p.fechaDesde <= '{$fechaHasta}'")
            ->andWhere('p.estadoContabilizado = 0');
        $arPagos = $queryBuilder->getQuery()->getResult();
        foreach ($arPagos as $arPago) {
            $arPago = $em->getRepository(RhuPago::class)->find($arPago['codigoPagoPk']);
            $ingresoBasePrestacion = $arPago->getVrIngresoBasePrestacion();
            $ingresoBasePrestacionVacacion = $arPago->getVrIngresoBasePrestacionVacacion();
            $cesantia = ($ingresoBasePrestacion * $porcentajeCesantia) / 100; // Porcentaje 8.33
            $interes = ($cesantia * $porcentajeInteres) / 100; // Porcentaje 1 sobre las cesantias
            $prima = ($ingresoBasePrestacion * $porcentajePrima) / 100; // 8.33
            $vacacion = ($ingresoBasePrestacionVacacion * $porcentajeVacacion) / 100; // 5
            $arPago->setVrCesantia($cesantia);
            $arPago->setVrInteres($interes);
            $arPago->setVrPrima($prima);
            $arPago->setVrVacacion($vacacion);
            $em->persist($arPago);
        }
        $em->flush();

    }

    public function regenerarIbp($fechaDesde, $fechaHasta)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(RhuPago::class, 'p')
            ->select('p.codigoPagoPk')
            ->where("p.fechaDesde >= '{$fechaDesde}' AND p.fechaDesde <= '{$fechaHasta}'")
            ->andWhere('p.estadoContabilizado = 0');
        $arPagos = $queryBuilder->getQuery()->getResult();
        foreach ($arPagos as $arPago) {
            $queryBuilder = $em->createQueryBuilder()->from(RhuPagoDetalle::class, 'pd')
                ->select('pd.codigoPagoDetallePk')
                ->addSelect('c.generaIngresoBasePrestacion')
                ->addSelect('c.generaIngresoBasePrestacionVacacion')
                ->addSelect('pd.vrPago')
                ->leftJoin('pd.conceptoRel', 'c')
                ->where("pd.codigoPagoFk = {$arPago['codigoPagoPk']}");
            $arPagosDetalles = $queryBuilder->getQuery()->getResult();
            $ingresoBasePrestacion = 0;
            $ingresoBasePrestacionVacacion = 0;
            foreach ($arPagosDetalles as $arPagoDetalle) {
                $arPagoDetalleAct = $em->getRepository(RhuPagoDetalle::class)->find($arPagoDetalle['codigoPagoDetallePk']);
                if ($arPagoDetalle['generaIngresoBasePrestacion']) {
                    $arPagoDetalleAct->setVrIngresoBasePrestacion($arPagoDetalle['vrPago']);
                    $ingresoBasePrestacion += $arPagoDetalle['vrPago'];
                } else {
                    $arPagoDetalleAct->setVrIngresoBasePrestacion(0);
                }
                if ($arPagoDetalle['generaIngresoBasePrestacionVacacion']) {
                    $arPagoDetalleAct->setVrIngresoBasePrestacionVacacion($arPagoDetalle['vrPago']);
                    $ingresoBasePrestacionVacacion += $arPagoDetalle['vrPago'];
                } else {
                    $arPagoDetalleAct->setVrIngresoBasePrestacionVacacion(0);
                }
                $em->persist($arPagoDetalleAct);
            }
            $arPagoAct = $em->getRepository(RhuPago::class)->find($arPago['codigoPagoPk']);
            $arPagoAct->setVrIngresoBasePrestacion($ingresoBasePrestacion);
            $arPagoAct->setVrIngresoBasePrestacionVacacion($ingresoBasePrestacionVacacion);
            $em->persist($arPagoAct);
        }
        $em->flush();
    }

    public function contabilizar($arr): bool
    {
        /**
         * @var $arVacacion RhuVacacion
         */
        $em = $this->getEntityManager();
        if ($arr) {
            $this->contabilizarCrearTercero($arr);
            $error = "";
            foreach ($arr AS $codigo) {
                /** @var $arPago RhuPago */
                $arPago = $em->getRepository(RhuPago::class)->find($codigo);
                if ($arPago->getEstadoAprobado() == 1 && $arPago->getEstadoContabilizado() == 0) {
                    $arContrato = $em->getRepository(RhuContrato::class)->find($arPago->getCodigoContratoFk());
                    $arCentroCosto = $arContrato->getCentroCostoRel();
                    $arTercero = $em->getRepository(RhuEmpleado::class)->terceroFinanciero($arPago->getCodigoEmpleadoFk());
                    if ($arPago->getPagoTipoRel()->getCodigoComprobanteFk()) {
                        $arComprobante = $em->getRepository(FinComprobante::class)->find($arPago->getPagoTipoRel()->getCodigoComprobanteFk());
                        if ($arComprobante) {
                            //$participacionTotal = 0;
                            $datos = $this->contabilizarGenerarDistribucion($arContrato->getCodigoCostoTipoFk(), $arCentroCosto, $arPago);
                            if ($datos['error'] == "") {
                                $arContratoDistribucion = $datos['arContratoDistribucion'];
                                $arPagoDetalles = $em->getRepository(RhuPagoDetalle::class)->findBy(array('codigoPagoFk' => $codigo));
                                foreach ($arPagoDetalles as $arPagoDetalle) {
                                    if ($arPagoDetalle->getVrPago() > 0) {
                                        $arConceptoCuenta = $em->getRepository(RhuConceptoCuenta::class)->findOneBy(array('codigoConceptoFk' => $arPagoDetalle->getCodigoConceptoFk(), 'codigoCostoClaseFk' => $arContrato->getCodigoCostoClaseFk()));
                                        if ($arConceptoCuenta) {
                                            $arCuenta = $em->getRepository(FinCuenta::class)->find($arConceptoCuenta->getCodigoCuentaFk());
                                            if ($arCuenta) {
                                                if ($arContrato->getCodigoCostoTipoFk() == 'DIS' || $arContrato->getCodigoCostoTipoFk() == 'OPE') {
                                                    /*$arTerceroDetalle = $arTercero;
                                                    if ($arCuenta->getCodigoTerceroFijoFk()) {
                                                        $arTerceroDetalle = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->find($arCuenta->getCodigoTerceroFijoFk());
                                                    }
                                                    foreach ($arEmpleadoDistribucion as $arEmpleadoDistribucionFijo) {
                                                        $participacionDetalle = $arEmpleadoDistribucionFijo['participacion'];
                                                        $valor = ($arPagoDetalle->getVrPago() / 100) * $participacionDetalle;

                                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                                        $arCentroCosto = null;
                                                        if ($arEmpleadoDistribucionFijo['codigoCentroCostoFk']) {
                                                            $arCentroCosto = $em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->find($arEmpleadoDistribucionFijo['codigoCentroCostoFk']);
                                                        }
                                                        $arSucursal = null;
                                                        if ($arEmpleadoDistribucionFijo['codigoSucursalFk']) {
                                                            $arSucursal = $em->getRepository('BrasaContabilidadBundle:CtbSucursal')->find($arEmpleadoDistribucionFijo['codigoSucursalFk']);
                                                        }
                                                        $arArea = null;
                                                        if ($arEmpleadoDistribucionFijo['codigoAreaFk']) {
                                                            $arArea = $em->getRepository('BrasaContabilidadBundle:CtbArea')->find($arEmpleadoDistribucionFijo['codigoAreaFk']);
                                                        }
                                                        $arProyecto = null;
                                                        if ($arEmpleadoDistribucionFijo['codigoProyectoFk']) {
                                                            $arProyecto = $em->getRepository('BrasaContabilidadBundle:CtbProyecto')->find($arEmpleadoDistribucionFijo['codigoProyectoFk']);
                                                        }

                                                        //$arRegistro->setComprobanteRel($arComprobanteContable);
                                                        if ($arConfiguracionNomina->getContabilizarPagoComprobantePagoTipo()) {
                                                            $arComprobantePagoTipo = $em->getRepository('BrasaContabilidadBundle:CtbComprobante')->find($arPago->getPagoTipoRel()->getCodigoComprobante());
                                                            if ($arComprobantePagoTipo) {
                                                                $arRegistro->setComprobanteRel($arComprobantePagoTipo);
                                                            } else {
                                                                $respuesta = "La cofiguracion esta para contabilizar con el comprobante del pago tipo y este no existe en comprobantes ";
                                                            }
                                                        } else {
                                                            $arRegistro->setComprobanteRel($arComprobanteContable);
                                                        }

                                                        if ($arCuenta->getExigeCentroCostos()) {
                                                            $arRegistro->setCentroCostoRel($arCentroCosto);
                                                        }
                                                        if ($arCuenta->getExigeSucursal()) {
                                                            $arRegistro->setSucursalRel($arSucursal);
                                                        }
                                                        if ($arCuenta->getExigeArea()) {
                                                            $arRegistro->setAreaRel($arArea);
                                                        }
                                                        if ($arCuenta->getExigeProyecto()) {
                                                            $arRegistro->setProyectoRel($arProyecto);
                                                        }
                                                        $arRegistro->setCuentaRel($arCuenta);
                                                        $arRegistro->setTerceroRel($arTerceroDetalle);
                                                        // Cuando el detalle es de salud y pension se lleva al nit de la entidad
                                                        if ($arCuenta->getExigeNit() == 1) {
                                                            if ($arPagoDetalle->getPagoConceptoRel()->getConceptoSalud() || $arPagoDetalle->getPagoConceptoRel()->getConceptoIncapacidadEntidad()) {
                                                                $arTerceroSalud = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPago->getEntidadSaludRel()->getNit()));
                                                                if ($arTerceroSalud) {
                                                                    $arRegistro->setTerceroRel($arTerceroSalud);
                                                                } else {
                                                                    $respuesta = "La entidad de salud " . $arPago->getEntidadSaludRel()->getNit() . "-" . $arPago->getEntidadSaludRel()->getNombre() . " del pago " . $arPago->getNumero() . " no existe en contabilidad";
                                                                    break;
                                                                }
                                                            }
                                                            if ($arPagoDetalle->getPagoConceptoRel()->getConceptoPension() || $arPagoDetalle->getPagoConceptoRel()->getConceptoFondoSolidaridadPensional()) {
                                                                $arTerceroPension = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPago->getEntidadPensionRel()->getNit()));
                                                                if ($arTerceroPension) {
                                                                    $arRegistro->setTerceroRel($arTerceroPension);
                                                                } else {
                                                                    $respuesta = "La entidad de pension " . $arPago->getEntidadPensionRel()->getNombre() . " del pago " . $arPago->getNumero() . " no existe en contabilidad";
                                                                    break;
                                                                }
                                                            }
                                                            if ($arPagoDetalle->getPagoConceptoRel()->getConceptoLicenciaEntidad()) {
                                                                $arTerceroSaludLicencia = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPago->getEntidadSaludRel()->getNit()));
                                                                if ($arTerceroSaludLicencia) {
                                                                    $arRegistro->setTerceroRel($arTerceroSaludLicencia);
                                                                } else {
                                                                    $respuesta = "La entidad de salud " . $arPago->getEntidadSaludRel()->getNit() . "-" . $arPago->getEntidadSaludRel()->getNombre() . " del pago " . $arPago->getNumero() . " no existe en contabilidad";
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        if ($arCuenta->getCodigoTerceroFijoFk()) {
                                                            $arTerceroDetalle = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->find($arCuenta->getCodigoTerceroFijoFk());
                                                            $arRegistro->setTerceroRel($arTerceroDetalle);
                                                        }
                                                        //Para contabilizar al nit de la entidad del credito
                                                        if ($arPagoDetalle->getCodigoCreditoFk() != null) {
                                                            if ($arConfiguracionNomina->getContabilizarCreditoNitEntidad()) {
                                                                if ($arPagoDetalle->getCreditoRel()->getCreditoTipoRel()->getNumeroIdentificacionTerceroContabilidad() != "") {
                                                                    $arTerceroCredito = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPagoDetalle->getCreditoRel()->getCreditoTipoRel()->getNumeroIdentificacionTerceroContabilidad()));
                                                                    $arRegistro->setTerceroRel($arTerceroCredito);
                                                                }
                                                            }
                                                        }
                                                        //Para contabilizar al nit fijo el concepto
                                                        if ($arPagoDetalle->getPagoConceptoRel()->getNumeroIdentificacionTerceroContabilidad() != null) {
                                                            $arTerceroConcepto = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPagoDetalle->getPagoConceptoRel()->getNumeroIdentificacionTerceroContabilidad()));
                                                            $arRegistro->setTerceroRel($arTerceroConcepto);
                                                        }

                                                        //Para contabilizar al nit del cliente
                                                        if ($arPagoDetalle->getPagoConceptoRel()->getContabilizarNitCliente()) {
                                                            if ($arConfiguracionNomina->getContabilizarConceptoPagoNitCliente()) {//Validar si tambien el cliente maneja la configuracion para contabilizar el centro de costo del cliente
                                                                if ($arPago->getEmpleadoRel()->getEmpleadoTipoRel()->getOperativo()) {
                                                                    if ($arPago->getContratoRel()->getCentroCostoRel()->getClienteRel()) {
                                                                        $arTerceroCliente = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPago->getCentroCostoRel()->getClienteRel()->getNit()));
                                                                        $arRegistro->setTerceroRel($arTerceroCliente);
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        //fin de validación para contablizar al nit del cliente

                                                        if ($arCuenta->getExigeBase()) {
                                                            $arRegistro->setBase($arPagoDetalle->getVrPago());
                                                            if ($arPagoDetalle->getPagoConceptoRel()->getConceptoRetencion() || $arPagoDetalle->getPagoConceptoRel()->getConceptoFondoSolidaridadPensional()) {
                                                                $ibp = $em->getRepository("BrasaRecursoHumanoBundle:RhuPagoDetalle")->ibp($arPagoDetalle->getPagoRel()->getFechaDesde()->format("Y-m-d"), $arPagoDetalle->getPagoRel()->getFechaHasta()->format("Y-m-d"), $arPagoDetalle->getPagoRel()->getCodigoContratoFk());
                                                                $arRegistro->setBase($ibp);
                                                            }
                                                        }
                                                        $arRegistro->setNumero($arPago->getNumero());
                                                        $arRegistro->setNumeroReferencia($arPago->getNumero());
                                                        $arRegistro->setFecha($arPago->getFechaHasta());
                                                        if ($arPagoConceptoCuenta->getTipoCuenta() == 1 && $arPagoDetalle->getVrPagoOperado() >= 0) {
                                                            $arRegistro->setDebito($valor);
                                                            $arRegistro->setNaturaleza(1);
                                                        } else {
                                                            $arRegistro->setCredito($valor);
                                                            $arRegistro->setNaturaleza(2);
                                                        }
                                                        $arRegistro->setDescripcionContable($arPagoDetalle->getPagoConceptoRel()->getNombre());
                                                        $em->persist($arRegistro);
                                                    }
                                                    */
                                                } else {
                                                    $arRegistro = new FinRegistro();
                                                    $arRegistro->setComprobanteRel($arComprobante);
                                                    $arRegistro->setCuentaRel($arCuenta);
                                                    $arRegistro->setTerceroRel($arTercero);
                                                    // Cuando el detalle es de salud y pension se lleva al nit de la entidad
                                                    /*if ($arCuenta->getExigeNit() == 1) {
                                                        if ($arPagoDetalle->getSalud() || $arPagoDetalle->getPagoConceptoRel()->getConceptoIncapacidadEntidad()) {
                                                            $arTerceroSalud = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPago->getEntidadSaludRel()->getNit()));
                                                            if ($arTerceroSalud) {
                                                                $arRegistro->setTerceroRel($arTerceroSalud);
                                                            } else {
                                                                $respuesta = "La entidad de salud " . $arPago->getEntidadSaludRel()->getNit() . "-" . $arPago->getEntidadSaludRel()->getNombre() . " del pago " . $arPago->getNumero() . " no existe en contabilidad";
                                                                break;
                                                            }
                                                        }
                                                        if ($arPagoDetalle->getPension() || $arPagoDetalle->getPagoConceptoRel()->getConceptoFondoSolidaridadPensional()) {
                                                            $arTerceroPension = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPago->getEntidadPensionRel()->getNit()));
                                                            if ($arTerceroPension) {
                                                                $arRegistro->setTerceroRel($arTerceroPension);
                                                            } else {
                                                                $respuesta = "La entidad de pension " . $arPago->getEntidadPensionRel()->getNombre() . " del pago " . $arPago->getNumero() . " no existe en contabilidad";
                                                                break;
                                                            }
                                                        }
                                                        if ($arPagoDetalle->getPagoConceptoRel()->getConceptoLicenciaEntidad()) {
                                                            $arTerceroLicenciaEntidad = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPago->getEntidadSaludRel()->getNit()));
                                                            if ($arTerceroLicenciaEntidad) {
                                                                $arRegistro->setTerceroRel($arTerceroLicenciaEntidad);
                                                            } else {
                                                                $respuesta = "La entidad de salud " . $arPago->getEntidadSaludRel()->getNombre() . " del pago " . $arPago->getNumero() . " no existe en contabilidad";
                                                                break;
                                                            }
                                                        }
                                                    }*/

                                                    $arRegistro->setNumero($arPago->getNumero());
                                                    $arRegistro->setNumeroReferencia($arPago->getNumero());
                                                    $arRegistro->setFecha($arPago->getFechaHasta());
                                                    if ($arConceptoCuenta->getNaturaleza() == 'D') {
                                                        $arRegistro->setVrDebito($arPagoDetalle->getVrPago());
                                                        $arRegistro->setNaturaleza('D');
                                                    } else {
                                                        $arRegistro->setVrCredito($arPagoDetalle->getVrPago());
                                                        $arRegistro->setNaturaleza('C');
                                                    }
                                                    if ($arPagoDetalle->getConceptoRel()->getSalud()) {
                                                        $arTerceroRegistro = $em->getRepository(RhuEntidad::class)->terceroFinanciero($arPago->getCodigoEntidadSaludFk());
                                                        $arRegistro->setTerceroRel($arTerceroRegistro);
                                                    }
                                                    if ($arPagoDetalle->getConceptoRel()->getPension()) {
                                                        $arTerceroRegistro = $em->getRepository(RhuEntidad::class)->terceroFinanciero($arPago->getCodigoEntidadPensionFk());
                                                        $arRegistro->setTerceroRel($arTerceroRegistro);
                                                    }
                                                    /*if ($arCuenta->getCodigoTerceroFijoFk() != "" && $arPagoDetalle->getPagoConceptoRel()->getConceptoIncapacidadEntidad() == 0) {
                                                        $arTerceroCuenta = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->find($arCuenta->getCodigoTerceroFijoFk());
                                                        $arRegistro->setTerceroRel($arTerceroCuenta);
                                                    }*/
                                                    //Para contabilizar al nit de la entidad del credito
                                                    /*if ($arPagoDetalle->getCodigoCreditoFk() != null) {
                                                        if ($arConfiguracionNomina->getContabilizarCreditoNitEntidad()) {
                                                            if ($arPagoDetalle->getCreditoRel()->getCreditoTipoRel()->getNumeroIdentificacionTerceroContabilidad() != "") {
                                                                $arTerceroCredito = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPagoDetalle->getCreditoRel()->getCreditoTipoRel()->getNumeroIdentificacionTerceroContabilidad()));
                                                                $arRegistro->setTerceroRel($arTerceroCredito);
                                                            }
                                                        }
                                                    }*/
                                                    //Para contabilizar al nit fijo el concepto
                                                    /*if ($arPagoDetalle->getPagoConceptoRel()->getNumeroIdentificacionTerceroContabilidad() != null) {
                                                        $arTerceroConcepto = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPagoDetalle->getPagoConceptoRel()->getNumeroIdentificacionTerceroContabilidad()));
                                                        $arRegistro->setTerceroRel($arTerceroConcepto);
                                                    }*/
                                                    /*if ($arCuenta->getExigeBase()) {
                                                        $arRegistro->setBase($arPagoDetalle->getVrPago());
                                                        if ($arPagoDetalle->getPagoConceptoRel()->getConceptoRetencion() || $arPagoDetalle->getPagoConceptoRel()->getConceptoFondoSolidaridadPensional()) {
                                                            $ibp = $em->getRepository("BrasaRecursoHumanoBundle:RhuPagoDetalle")->ibp($arPagoDetalle->getPagoRel()->getFechaDesde()->format("Y-m-d"), $arPagoDetalle->getPagoRel()->getFechaHasta()->format("Y-m-d"), $arPagoDetalle->getPagoRel()->getCodigoContratoFk());
                                                            $arRegistro->setBase($ibp);
                                                        }
                                                    }*/
                                                    $arRegistro->setDescripcion($arPagoDetalle->getConceptoRel()->getNombre());
                                                    if ($arCuenta->getExigeCentroCosto() == 1) {
                                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                                    }
                                                    $arRegistro->setCodigoModeloFk('RhuPago');
                                                    $arRegistro->setCodigoDocumento($arPago->getCodigoPagoPk());
                                                    $em->persist($arRegistro);
                                                }
                                            } else {
                                                $$error = "La cuenta " . $arConceptoCuenta->getCodigoCuentaFk() . " no existe en el plan de cuentas";
                                                break;
                                            }
                                        } else {
                                            $error = "El concepto de pago " . $arPagoDetalle->getCodigoConceptoFk() . " no tiene cuenta para la clase de costo " . $arContrato->getCodigoCostoClaseFk();
                                            break;
                                        }
                                    }
                                }

                                //Procesar cuenta por pagar
                                if ($arPago->getVrNeto() > 0) {
                                    if ($arPago->getPagoTipoRel()->getCodigoCuentaFk()) {
                                        $arCuenta = $em->getRepository(FinCuenta::class)->find($arPago->getPagoTipoRel()->getCodigoCuentaFk());
                                        if ($arCuenta) {
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setCuentaRel($arCuenta);
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arPago->getNumero());
                                            $arRegistro->setNumeroReferencia($arPago->getNumero());
                                            $arRegistro->setFecha($arPago->getFechaHasta());
                                            $arRegistro->setVrCredito($arPago->getVrNeto());
                                            $arRegistro->setNaturaleza("C");
                                            $arRegistro->setDescripcion('POR PAGAR');
                                            if ($arCuenta->getExigeCentroCosto() == 1) {
                                                $arRegistro->setCentroCostoRel($arCentroCosto);
                                            }
                                            $arRegistro->setCodigoModeloFk('RhuPago');
                                            $arRegistro->setCodigoDocumento($arPago->getCodigoPagoPk());
                                            $em->persist($arRegistro);
                                        } else {
                                            $error = "La cuenta " . $arPago->getPagoTipoRel()->getCodigoCuentaFk() . " no existe en el plan de cuentas";
                                            break;
                                        }
                                    } else {
                                        $error = "La cuenta por pagar no esta configurada en el tipo de pago";
                                        break;
                                    }
                                }

                                //Provision cesantia
                                if ($arPago->getVrCesantia() > 0) {
                                    $arConfiguracionProvision = $em->getRepository(RhuConfiguracionProvision::class)->findOneBy(['tipo' => 'CES', 'codigoCostoClaseFk' => $arContrato->getCodigoCostoClaseFk()]);
                                    if ($arConfiguracionProvision) {
                                        $arCuentaDebito = $em->getRepository(FinCuenta::class)->find($arConfiguracionProvision->getCodigoCuentaDebitoFk());
                                        $arCuentaCredito = $em->getRepository(FinCuenta::class)->find($arConfiguracionProvision->getCodigoCuentaCreditoFk());
                                        if ($arCuentaDebito && $arCuentaCredito) {
                                            //Debito
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setCuentaRel($arCuentaDebito);
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arPago->getNumero());
                                            $arRegistro->setNumeroReferencia($arPago->getNumero());
                                            $arRegistro->setFecha($arPago->getFechaHasta());
                                            $arRegistro->setVrDebito($arPago->getVrCesantia());
                                            $arRegistro->setNaturaleza("D");
                                            $arRegistro->setDescripcion('PROV CESANTIA DEB');
                                            if ($arCuentaDebito->getExigeCentroCosto() == 1) {
                                                $arRegistro->setCentroCostoRel($arCentroCosto);
                                            }
                                            $arRegistro->setCodigoModeloFk('RhuPago');
                                            $arRegistro->setCodigoDocumento($arPago->getCodigoPagoPk());
                                            $em->persist($arRegistro);

                                            //Credito
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setCuentaRel($arCuentaCredito);
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arPago->getNumero());
                                            $arRegistro->setNumeroReferencia($arPago->getNumero());
                                            $arRegistro->setFecha($arPago->getFechaHasta());
                                            $arRegistro->setVrCredito($arPago->getVrCesantia());
                                            $arRegistro->setNaturaleza("C");
                                            $arRegistro->setDescripcion('PROV CESANTIA CRE');
                                            if ($arCuentaCredito->getExigeCentroCosto() == 1) {
                                                $arRegistro->setCentroCostoRel($arCentroCosto);
                                            }
                                            $arRegistro->setCodigoModeloFk('RhuPago');
                                            $arRegistro->setCodigoDocumento($arPago->getCodigoPagoPk());
                                            $em->persist($arRegistro);

                                        } else {
                                            $error = "La cuenta debito o credito no existe";
                                            break;
                                        }
                                    } else {
                                        $error = "No esta configurada la cuenta para provision de cesantias de esta clase de costo";
                                        break;
                                    }
                                }

                                //Provision interes
                                if ($arPago->getVrInteres() > 0) {
                                    $arConfiguracionProvision = $em->getRepository(RhuConfiguracionProvision::class)->findOneBy(['tipo' => 'INT', 'codigoCostoClaseFk' => $arContrato->getCodigoCostoClaseFk()]);
                                    if ($arConfiguracionProvision) {
                                        $arCuentaDebito = $em->getRepository(FinCuenta::class)->find($arConfiguracionProvision->getCodigoCuentaDebitoFk());
                                        $arCuentaCredito = $em->getRepository(FinCuenta::class)->find($arConfiguracionProvision->getCodigoCuentaCreditoFk());
                                        if ($arCuentaDebito && $arCuentaCredito) {
                                            //Debito
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setCuentaRel($arCuentaDebito);
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arPago->getNumero());
                                            $arRegistro->setNumeroReferencia($arPago->getNumero());
                                            $arRegistro->setFecha($arPago->getFechaHasta());
                                            $arRegistro->setVrDebito($arPago->getVrInteres());
                                            $arRegistro->setNaturaleza("D");
                                            $arRegistro->setDescripcion('PROV INTERES DEB');
                                            if ($arCuentaDebito->getExigeCentroCosto() == 1) {
                                                $arRegistro->setCentroCostoRel($arCentroCosto);
                                            }
                                            $arRegistro->setCodigoModeloFk('RhuPago');
                                            $arRegistro->setCodigoDocumento($arPago->getCodigoPagoPk());
                                            $em->persist($arRegistro);

                                            //Credito
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setCuentaRel($arCuentaCredito);
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arPago->getNumero());
                                            $arRegistro->setNumeroReferencia($arPago->getNumero());
                                            $arRegistro->setFecha($arPago->getFechaHasta());
                                            $arRegistro->setVrCredito($arPago->getVrInteres());
                                            $arRegistro->setNaturaleza("C");
                                            $arRegistro->setDescripcion('PROV INTERES CRE');
                                            if ($arCuentaCredito->getExigeCentroCosto() == 1) {
                                                $arRegistro->setCentroCostoRel($arCentroCosto);
                                            }
                                            $arRegistro->setCodigoModeloFk('RhuPago');
                                            $arRegistro->setCodigoDocumento($arPago->getCodigoPagoPk());
                                            $em->persist($arRegistro);

                                        } else {
                                            $error = "La cuenta debito o credito no existe";
                                            break;
                                        }
                                    } else {
                                        $error = "No esta configurada la cuenta para provision de cesantias de esta clase de costo";
                                        break;
                                    }
                                }

                                //Provision prima
                                if ($arPago->getVrPrima() > 0) {
                                    $arConfiguracionProvision = $em->getRepository(RhuConfiguracionProvision::class)->findOneBy(['tipo' => 'PRI', 'codigoCostoClaseFk' => $arContrato->getCodigoCostoClaseFk()]);
                                    if ($arConfiguracionProvision) {
                                        $arCuentaDebito = $em->getRepository(FinCuenta::class)->find($arConfiguracionProvision->getCodigoCuentaDebitoFk());
                                        $arCuentaCredito = $em->getRepository(FinCuenta::class)->find($arConfiguracionProvision->getCodigoCuentaCreditoFk());
                                        if ($arCuentaDebito && $arCuentaCredito) {
                                            //Debito
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setCuentaRel($arCuentaDebito);
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arPago->getNumero());
                                            $arRegistro->setNumeroReferencia($arPago->getNumero());
                                            $arRegistro->setFecha($arPago->getFechaHasta());
                                            $arRegistro->setVrDebito($arPago->getVrPrima());
                                            $arRegistro->setNaturaleza("D");
                                            $arRegistro->setDescripcion('PROV PRIMA DEB');
                                            if ($arCuentaDebito->getExigeCentroCosto() == 1) {
                                                $arRegistro->setCentroCostoRel($arCentroCosto);
                                            }
                                            $arRegistro->setCodigoModeloFk('RhuPago');
                                            $arRegistro->setCodigoDocumento($arPago->getCodigoPagoPk());
                                            $em->persist($arRegistro);

                                            //Credito
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setCuentaRel($arCuentaCredito);
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arPago->getNumero());
                                            $arRegistro->setNumeroReferencia($arPago->getNumero());
                                            $arRegistro->setFecha($arPago->getFechaHasta());
                                            $arRegistro->setVrCredito($arPago->getVrPrima());
                                            $arRegistro->setNaturaleza("C");
                                            $arRegistro->setDescripcion('PROV PRIMA CRE');
                                            if ($arCuentaCredito->getExigeCentroCosto() == 1) {
                                                $arRegistro->setCentroCostoRel($arCentroCosto);
                                            }
                                            $arRegistro->setCodigoModeloFk('RhuPago');
                                            $arRegistro->setCodigoDocumento($arPago->getCodigoPagoPk());
                                            $em->persist($arRegistro);

                                        } else {
                                            $error = "La cuenta debito o credito no existe";
                                            break;
                                        }
                                    } else {
                                        $error = "No esta configurada la cuenta para provision de cesantias de esta clase de costo";
                                        break;
                                    }
                                }

                                //Provision vacacion
                                if ($arPago->getVrVacacion() > 0) {
                                    $arConfiguracionProvision = $em->getRepository(RhuConfiguracionProvision::class)->findOneBy(['tipo' => 'VAC', 'codigoCostoClaseFk' => $arContrato->getCodigoCostoClaseFk()]);
                                    if ($arConfiguracionProvision) {
                                        $arCuentaDebito = $em->getRepository(FinCuenta::class)->find($arConfiguracionProvision->getCodigoCuentaDebitoFk());
                                        $arCuentaCredito = $em->getRepository(FinCuenta::class)->find($arConfiguracionProvision->getCodigoCuentaCreditoFk());
                                        if ($arCuentaDebito && $arCuentaCredito) {
                                            //Debito
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setCuentaRel($arCuentaDebito);
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arPago->getNumero());
                                            $arRegistro->setNumeroReferencia($arPago->getNumero());
                                            $arRegistro->setFecha($arPago->getFechaHasta());
                                            $arRegistro->setVrDebito($arPago->getVrVacacion());
                                            $arRegistro->setNaturaleza("D");
                                            $arRegistro->setDescripcion('PROV VACACION DEB');
                                            if ($arCuentaDebito->getExigeCentroCosto() == 1) {
                                                $arRegistro->setCentroCostoRel($arCentroCosto);
                                            }
                                            $arRegistro->setCodigoModeloFk('RhuPago');
                                            $arRegistro->setCodigoDocumento($arPago->getCodigoPagoPk());
                                            $em->persist($arRegistro);

                                            //Credito
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setCuentaRel($arCuentaCredito);
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arPago->getNumero());
                                            $arRegistro->setNumeroReferencia($arPago->getNumero());
                                            $arRegistro->setFecha($arPago->getFechaHasta());
                                            $arRegistro->setVrCredito($arPago->getVrVacacion());
                                            $arRegistro->setNaturaleza("C");
                                            $arRegistro->setDescripcion('PROV VACACION CRE');
                                            if ($arCuentaCredito->getExigeCentroCosto() == 1) {
                                                $arRegistro->setCentroCostoRel($arCentroCosto);
                                            }
                                            $arRegistro->setCodigoModeloFk('RhuPago');
                                            $arRegistro->setCodigoDocumento($arPago->getCodigoPagoPk());
                                            $em->persist($arRegistro);

                                        } else {
                                            $error = "La cuenta debito o credito no existe";
                                            break;
                                        }
                                    } else {
                                        $error = "No esta configurada la cuenta para provision de cesantias de esta clase de costo";
                                        break;
                                    }
                                }

                                $arPago->setEstadoContabilizado(1);
                                $em->persist($arPago);
                            } else {
                                $error = $datos['error'];
                                break;
                            }
                        } else {
                            $error = "El comprobante " . $arPago->getPagoTipoRel()->getCodigoComprobanteFk() . " no existe";
                            break;
                        }
                    } else {
                        $error = "El tipo de pago no tiene un comprobante asignado";
                        break;
                    }
                }
            }
            if ($error == "") {
                $em->flush();
            } else {
                Mensajes::error($error);
            }
        }
        return true;
    }

    public function contabilizarCrearTercero($arr)
    {
        $em = $this->getEntityManager();
        foreach ($arr AS $codigo) {
            $arPago = $em->getRepository(RhuPago::class)->find($codigo);
            if ($arPago) {
                $em->getRepository(RhuEmpleado::class)->terceroFinanciero($arPago->getCodigoEmpleadoFk());
            }
        }
    }

    public function contabilizarGenerarDistribucion($costotipo = 'FIJ', $arCentroCosto, $arPago)
    {
        $respuesta = [
            'error' => "",
            'arContratoDistribucion' => null
        ];
        if ($costotipo == 'OPE') {
            /*$anio = $arPago->getFechaHasta()->format('Y');
            $mes = $arPago->getFechaHasta()->format('m');
            $dql = "SELECT ed.codigoCentroCostoFk, ed.codigoSucursalFk, ed.codigoAreaFk, ed.codigoProyectoFk, SUM(ed.participacion) AS participacion FROM BrasaRecursoHumanoBundle:RhuEmpleadoDistribucion ed WHERE ed.codigoEmpleadoFk = " . $arPago->getCodigoEmpleadoFk() . " AND ed.anio = " . $anio . " AND ed.mes = " . $mes . " ";
            $dql .= " GROUP BY ed.codigoCentroCostoFk, ed.codigoAreaFk, ed.codigoProyectoFk, ed.codigoSucursalFk ";
            $query = $em->createQuery($dql);
            $arEmpleadoDistribucion = $query->getResult();*/
        }
        if ($costotipo == 'DIS') {
            /*$dql = "SELECT edf.codigoCentroCostoFk, edf.codigoSucursalFk, edf.codigoAreaFk, edf.codigoProyectoFk, SUM(edf.participacion) AS participacion FROM BrasaRecursoHumanoBundle:RhuEmpleadoDistribucionFijo edf WHERE edf.codigoEmpleadoFk = " . $arPago->getCodigoEmpleadoFk();
            $dql .= " GROUP BY edf.codigoCentroCostoFk, edf.codigoAreaFk, edf.codigoProyectoFk, edf.codigoSucursalFk, edf.participacion ";
            $query = $em->createQuery($dql);
            $arEmpleadoDistribucion = $query->getResult();*/
        }
        if ($costotipo == 'FIJ') {
            $respuesta['arContratoDistribucion'] =
                array(
                    array(
                        'codigoCentroCostoFk' => $arCentroCosto->getCodigoCentroCostoPk(),
                        'participacion' => 100
                    )
                );
            /*if ($arConfiguracionNomina->getContabilizarPagoCentroCostoCliente()) {//Validar si tambien el cliente maneja la configuracion para contabilizar el centro de costo del cliente
                if ($arPago->getEmpleadoRel()->getEmpleadoTipoRel()->getOperativo()) {
                    if ($arPago->getContratoRel()->getCentroCostoRel()->getClienteRel()->getCentroCostoRel()) {
                        $arEmpleadoDistribucion[0]["codigoCentroCostoFk"] = $arPago->getCentroCostoRel()->getClienteRel()->getCodigoCentroCostoFk();
                        $arEmpleadoDistribucion[0]["codigoSucursalFk"] = $arPago->getCentroCostoRel()->getClienteRel()->getCodigoSucursalFk();
                        $arEmpleadoDistribucion[0]["codigoAreaFk"] = "1101";//Temporal agregar relaciones en el cliente.
                        $arEmpleadoDistribucion[0]["codigoProyectoFk"] = "20002";//Temporal agregar relaciones en el cliente.
                    }
                }
            }*/
        }
        if ($respuesta['arContratoDistribucion']) {
            $participacionTotal = 0;
            foreach ($respuesta['arContratoDistribucion'] as $arContratoDistribucionFijo) {
                $participacionDetalle = $arContratoDistribucionFijo['participacion'];
                $participacionTotal += $participacionDetalle;
            }
            if (round($participacionTotal) != 100) {
                $respuesta['error'] = "En el pago numero " . $arPago->getNumero() . " el empleado no tiene el 100% en la distribucion de costos";
            }
        } else {
            $respuesta['error'] = "En el pago numero " . $arPago->getNumero() . " el empleado no tiene distribucion de costos";
        }
        return $respuesta;
    }

    /**
     * @param $arPago RhuPago
     * @param $arConfiguracion
     */
    public function liquidarProvision($arPago, $arConfiguracion)
    {
        $em = $this->getEntityManager();
        $porcentajeCesantia = $arConfiguracion['provisionPorcentajeCesantia'];
        $porcentajeInteres = $arConfiguracion['provisionPorcentajeInteres'];
        $porcentajePrima = $arConfiguracion['provisionPorcentajePrima'];
        $porcentajeVacacion = $arConfiguracion['provisionPorcentajeVacacion'];
        $cesantia = ($arPago->getVrIngresoBasePrestacion() * $porcentajeCesantia) / 100; // Porcentaje 8.33
        $interes = ($cesantia * $porcentajeInteres) / 100; // Porcentaje 1 sobre las cesantias
        $prima = ($arPago->getVrIngresoBasePrestacion() * $porcentajePrima) / 100; // 8.33
        $vacacion = ($arPago->getVrIngresoBasePrestacionVacacion() * $porcentajeVacacion) / 100; // 5
        $arPago->setVrCesantia($cesantia);
        $arPago->setVrInteres($interes);
        $arPago->setVrPrima($prima);
        $arPago->setVrVacacion($vacacion);

    }

}