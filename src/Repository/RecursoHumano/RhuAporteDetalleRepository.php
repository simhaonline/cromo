<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteContrato;
use App\Entity\RecursoHumano\RhuAporteDetalle;
use App\Entity\RecursoHumano\RhuAporteSoporte;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuConfiguracionAporte;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuEntidad;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuAporteDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuAporteDetalle::class);
    }

    public function lista($codigoAporte)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(RhuAporteDetalle::class, 'ad')
            ->select('ad.codigoAporteDetallePk')
            ->addSelect('ad.codigoContratoFk')
            ->addSelect('e.numeroIdentificacion as empleadoNumeroIdentificacion')
            ->addSelect('e.nombreCorto as empleadoNombreCorto')
            ->addSelect('ad.ingreso')
            ->addSelect('ad.retiro')
            ->addSelect('ad.variacionTransitoriaSalario')
            ->addSelect('ad.suspensionTemporalContratoLicenciaServicios')
            ->addSelect('ad.diasLicencia')
            ->addSelect('ad.incapacidadGeneral')
            ->addSelect('ad.diasIncapacidadGeneral')
            ->addSelect('ad.licenciaMaternidad')
            ->addSelect('ad.diasLicenciaMaternidad')
            ->addSelect('ad.vacaciones')
            ->addSelect('ad.diasVacaciones')
            ->addSelect('ad.trasladoAOtraEps')
            ->addSelect('ad.codigoEntidadSaludTraslada')
            ->addSelect('ad.trasladoDesdeOtraEps')
            ->addSelect('ad.incapacidadAccidenteTrabajoEnfermedadProfesional')
            ->addSelect('ad.salarioIntegral')
            ->addSelect('ad.salarioBasico')
            ->addSelect('ad.vrIngresoBaseCotizacion')
            ->addSelect('ad.numeroHorasLaboradas')
            ->addSelect('ad.diasCotizadosPension')
            ->addSelect('ad.diasCotizadosSalud')
            ->addSelect('ad.diasCotizadosRiesgosProfesionales')
            ->addSelect('ad.diasCotizadosCajaCompensacion')
            ->addSelect('ad.ibcPension')
            ->addSelect('ad.ibcSalud')
            ->addSelect('ad.ibcRiesgosProfesionales')
            ->addSelect('ad.ibcCaja')
            ->addSelect('ad.tarifaPension')
            ->addSelect('ad.tarifaSalud')
            ->addSelect('ad.tarifaRiesgos')
            ->addSelect('ad.tarifaCaja')
            ->addSelect('ad.tarifaSena')
            ->addSelect('ad.tarifaIcbf')
            ->addSelect('ad.cotizacionPension')
            ->addSelect('ad.aportesFondoSolidaridadPensionalSolidaridad')
            ->addSelect('ad.aportesFondoSolidaridadPensionalSubsistencia')
            ->addSelect('ad.cotizacionSalud')
            ->addSelect('ad.cotizacionRiesgos')
            ->addSelect('ad.cotizacionCaja')
            ->addSelect('ad.cotizacionSena')
            ->addSelect('ad.cotizacionIcbf')
            ->addSelect('ad.totalCotizacion')

            ->leftJoin('ad.empleadoRel', 'e')
            ->where('ad.codigoAporteFk = ' . $codigoAporte);
        return $queryBuilder;
    }


    public function generar($arAporte)
    {
        $em = $this->getEntityManager();
        $arrConfiguracionNomina = $em->getRepository(RhuConfiguracion::class)->generarAporte();
        $arEntidadRiesgos = $em->getRepository(RhuEntidad::class)->find($arrConfiguracionNomina['codigoEntidadRiesgosProfesionalesFk']);
        $totalCotizacionGeneral = 0;
        $ibcCajaTotal = 0;
        $secuencia = 1 ;
        $arAporteContratos = $em->getRepository(RhuAporteContrato::class)->listaGenerarDetalle($arAporte->getCodigoAportePk());
        foreach ($arAporteContratos as $arAporteContratoConsulta) {
            /** @var $arAporteContrato RhuAporteContrato */
            $arAporteContrato = $em->getRepository(RhuAporteContrato::class)->find($arAporteContratoConsulta['codigoAporteContratoPk']);
            /** @var $arContrato RhuContrato */
            $arContrato = $em->getRepository(RhuContrato::class)->find($arAporteContratoConsulta['codigoContratoFk']);
            /** @var $arEmpleado RhuEmpleado */
            $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($arAporteContratoConsulta['codigoEmpleadoFk']);
            $arAporteSoportes = $em->getRepository(RhuAporteSoporte::class)->listaGenerarDetalle($arAporteContratoConsulta['codigoAporteContratoPk']);
            foreach ($arAporteSoportes as $arAporteSoporteConsulta) {
                /** @var $arAporteSoporte RhuAporteSoporte */
                $arAporteSoporte = $em->getRepository(RhuAporteSoporte::class)->find($arAporteSoporteConsulta['codigoAporteSoportePk']);
                $arAporteDetalle = new RhuAporteDetalle();
                $arAporteDetalle->setAporteRel($arAporte);
                $arAporteDetalle->setSucursalRel($arAporte->getSucursalRel());
                $arAporteDetalle->setEmpleadoRel($arEmpleado);
                $arAporteDetalle->setContratoRel($arContrato);
                $arAporteDetalle->setAnio($arAporte->getAnio());
                $arAporteDetalle->setMes($arAporte->getMes());
                $arAporteDetalle->setFechaDesde($arAporte->getFechaDesde());
                $arAporteDetalle->setFechaHasta($arAporte->getFechaHasta());
                $arAporteDetalle->setTipoRegistro(2);
                $arAporteDetalle->setSecuencia($secuencia);

                $arAporteDetalle->setTipoDocumento($arEmpleado->getCodigoIdentificacionFk());
                $arAporteDetalle->setTipoCotizante($arContrato->getCodigoTipoCotizanteFk());
                $arAporteDetalle->setSubtipoCotizante($arContrato->getCodigoSubtipoCotizanteFk());
                $arAporteDetalle->setExtranjeroNoObligadoCotizarPension(' ');
                $arAporteDetalle->setColombianoResidenteExterior(' ');
                $arAporteDetalle->setCodigoDepartamentoUbicacionlaboral($arContrato->getCiudadLaboraRel()->getDepartamentoRel()->getCodigoDane());
                $arAporteDetalle->setCodigoMunicipioUbicacionlaboral($arContrato->getCiudadLaboraRel()->getCodigoDane());
                $arAporteDetalle->setPrimerNombre($arEmpleado->getNombre1());
                $arAporteDetalle->setSegundoNombre($arEmpleado->getNombre2());
                $arAporteDetalle->setPrimerApellido($arEmpleado->getApellido1());
                $arAporteDetalle->setSegundoApellido($arEmpleado->getApellido2());
                $arAporteDetalle->setIngreso($arAporteSoporte->getIngreso());
                $arAporteDetalle->setRetiro($arAporteSoporte->getRetiro());
                /*$arAporte->setCargoRel($arContrato->getCargoRel());*/
                //Parametros generales
                $dias = $arAporteSoporte->getDias();
                $salario = $arAporteSoporte->getVrSalario();
                $salarioMinimo = $arrConfiguracionNomina['vrSalarioMinimo'];
                $ibc = $arAporteSoporte->getIbc();
                $ibcCajaVacaciones = $arAporteSoporte->getIbcCajaVacaciones();
                $vacaciones = $arAporteSoporte->getVrVacaciones();
                $arAporteDetalle->setVrVacaciones($vacaciones);
                $arAporteDetalle->setVrIngresoBaseCotizacion($ibc);
                if ($arAporteSoporte->getIncapacidadGeneral()) {
                    $arAporteDetalle->setIncapacidadGeneral('X');
                    $arAporteDetalle->setDiasIncapacidadGeneral($dias);
                    $arAporteDetalle->setFechaInicioIge($arAporteSoporte->getFechaDesde()->format('Y-m-d'));
                    $arAporteDetalle->setFechaFinIge($arAporteSoporte->getFechaHasta()->format('Y-m-d'));
                }
                if ($arAporteSoporte->getIncapacidadLaboral()) {
                    $arAporteDetalle->setIncapacidadAccidenteTrabajoEnfermedadProfesional($dias);
                    $arAporteDetalle->setFechaInicioIrl($arAporteSoporte->getFechaDesde()->format('Y-m-d'));
                    $arAporteDetalle->setFechaFinIrl($arAporteSoporte->getFechaHasta()->format('Y-m-d'));
                }
                if ($arAporteSoporte->getLicencia()) {
                    $arAporteDetalle->setDiasLicencia($dias);
                    $arAporteDetalle->setSuspensionTemporalContratoLicenciaServicios('X');
                    $arAporteDetalle->setFechaInicioSln($arAporteSoporte->getFechaDesde()->format('Y-m-d'));
                    $arAporteDetalle->setFechaFinSln($arAporteSoporte->getFechaHasta()->format('Y-m-d'));
                }
                if ($arAporteSoporte->getLicenciaMaternidad()) {
                    $arAporteDetalle->setLicenciaMaternidad('X');
                    $arAporteDetalle->setDiasLicenciaMaternidad($dias);
                    $arAporteDetalle->setFechaInicioLma($arAporteSoporte->getFechaDesde()->format('Y-m-d'));
                    $arAporteDetalle->setFechaFinLma($arAporteSoporte->getFechaHasta()->format('Y-m-d'));
                }
                if ($arAporteSoporte->getVacaciones()) {
                    $arAporteDetalle->setVacaciones('X');
                    $arAporteDetalle->setDiasVacaciones($dias);
                    $arAporteDetalle->setFechaInicioVacLr($arAporteSoporte->getFechaDesde()->format('Y-m-d'));
                    $arAporteDetalle->setFechaFinVacLr($arAporteSoporte->getFechaHasta()->format('Y-m-d'));
                }
                if ($arAporteSoporte->getLicenciaRemunerada()) {
                    $arAporteDetalle->setVacaciones('L');
                    $arAporteDetalle->setFechaInicioVacLr($arAporteSoporte->getFechaDesde()->format('Y-m-d'));
                    $arAporteDetalle->setFechaFinVacLr($arAporteSoporte->getFechaHasta()->format('Y-m-d'));
                    $arAporteDetalle->setSuspensionTemporalContratoLicenciaServicios('');
                    $arAporteDetalle->setFechaInicioSln(null);
                    $arAporteDetalle->setFechaFinSln(null);
                }
                if ($arAporteSoporte->getRetiro() == "X") {
                    $arAporteDetalle->setFechaRetiro($arAporteSoporte->getFechaRetiro()->format('Y-m-d'));
                }
                if ($arAporteSoporte->getIngreso() == "X") {
                    $arAporteDetalle->setFechaIngreso($arAporteSoporte->getFechaIngreso()->format('Y-m-d'));
                }
                //Validar si el empleado tiene traslado a otra eps
                if ($arAporteSoporte->getTrasladoAOtraEps()) {
                    $arAporteDetalle->setTrasladoAOtraEps("X");
                    $arAporteDetalle->setCodigoEntidadSaludTraslada($arAporteSoporte->getCodigoEntidadSaludTraslada());
                }
                //Validar si el empleado tiene traslado a otra pension
                if ($arAporteSoporte->getTrasladoAOtraPension()) {
                    $arAporteDetalle->setTrasladoAOtraPension("X");
                    $arAporteDetalle->setCodigoEntidadPensionTraslada($arAporteSoporte->getCodigoEntidadPensionTraslada());
                }
                //Validar si el empleado tiene traslado desde otra eps
                if ($arAporteSoporte->getTrasladoDesdeOtraEps()) {
                    $arAporteDetalle->setTrasladoDesdeOtraEps("X");
                }
                //Validar si el empleado tiene traslado desde otra pension
                if ($arAporteSoporte->getTrasladoDesdeOtraPension()) {
                    $arAporteDetalle->setTrasladoDesdeOtraPension("X");
                }
                // 19 Aprendices del Sena en etapa productiva
                if ($arContrato->getCodigoTipoCotizanteFk() != 19 && $arContrato->getCodigoTipoCotizanteFk() != 12) {
                    $arAporteDetalle->setVariacionTransitoriaSalario($arAporteSoporte->getVariacionTransitoriaSalario());
                }
                $arAporteDetalle->setSalarioIntegral($arAporteContrato->getSalarioIntegral());
                $arAporteDetalle->setSalarioBasico($salario);
                $arAporteDetalle->setCodigoEntidadPensionPertenece($arAporteContrato->getCodigoEntidadPensionPertenece());
                $arAporteDetalle->setCodigoEntidadSaludPertenece($arAporteContrato->getCodigoEntidadSaludPertenece());
                $arAporteDetalle->setCodigoEntidadCajaPertenece($arAporteContrato->getCodigoEntidadCajaPertenece());
                $arAporteDetalle->setEntidadPensionRel($arContrato->getEntidadPensionRel());
                $arAporteDetalle->setEntidadSaludRel($arContrato->getEntidadSaludRel());
                $arAporteDetalle->setEntidadCajaRel($arContrato->getEntidadCajaRel());
                $arAporteDetalle->setEntidadRiesgosRel($arEntidadRiesgos);

                $diasPension = $dias;
                $diasRiesgos = $dias;
                $diasCaja = $dias;

                $ibc = $this->redondearIbc2($ibc);
                $ibcPension = $ibc;
                $ibcSalud = $ibc;
                $ibcRiesgos = $ibc;
                $ibcCaja = $this->redondearIbc2($ibc + $vacaciones);
                if ($arAporteSoporte->getVacaciones()) {
                    $ibcCaja = $this->redondearIbc2($ibcCajaVacaciones);
                }
                //Si tiene licencia y retiro
                if ($arAporteSoporte->getLicenciaMaternidad()) {
                    if ($arAporteSoporte->getRetiro() == "X") {
                        $ibcCaja = $this->redondearIbc2($vacaciones);
                    }
                }
                //Si tiene incapacidad y retiro
                if ($arAporteSoporte->getIncapacidadGeneral()) {
                    if ($arAporteSoporte->getRetiro() == "X") {
                        $ibcCaja = $this->redondearIbc2($vacaciones);
                    }
                }
                $ibcOtrosParafiscales = $ibc;
                // se valida si es empleado devenga mas de 25 SMLV y aporta unicamente sobre los 25 SMLv
                //Según la ley 797 de 2003 en su articulo 5.
                if ($ibc >= ($salarioMinimo * 25)) {
                    $ibc = $salarioMinimo * 25;
                    $ibcPension = $ibc;
                    $ibcSalud = $ibc;
                    $ibcRiesgos = $ibc;
                }


                $tarifaPension = $arAporteSoporte->getTarifaPension();
                $tarifaSalud = $arAporteSoporte->getTarifaSalud();
                $tarifaRiesgos = $arAporteSoporte->getTarifaRiesgos();
                $tarifaCaja = $arAporteSoporte->getTarifaCaja();
                $tarifaIcbf = 0;
                $tarifaSena = 0;
                if ($arAporteDetalle->getTipoCotizante() == '19' || $arAporteDetalle->getTipoCotizante() == '12' || $arAporteDetalle->getTipoCotizante() == '23') {
                    $diasPension = 0;
                    $tarifaPension = 0;
                    $ibcPension = 0;
                    $tarifaSalud = 12.5;
                    $ibcCaja = 0;
                    $diasCaja = 0;
                    $tarifaCaja = 0;
                }
                if ($arAporteDetalle->getTipoCotizante() == '12') {
                    $diasRiesgos = 0;
                    $tarifaRiesgos = 0;
                    if ($arAporteDetalle->getSubtipoCotizante() == '0') {
                        $ibcRiesgos = 0;
                    }
                }
                if ($arAporteDetalle->getTipoCotizante() == '23') {
                    $tarifaSalud = 0;
                }
                if ((($ibc) > (10 * $salarioMinimo))) {
                    $tarifaSalud = 12.5;
                    $tarifaIcbf = 3;
                    $tarifaSena = 2;
                } else {
                    $diasNovedad = $arAporteDetalle->getDiasLicencia() + $arAporteDetalle->getDiasIncapacidadGeneral() + $arAporteDetalle->getDiasLicenciaMaternidad() + $arAporteDetalle->getDiasVacaciones() + $arAporteDetalle->getIncapacidadAccidenteTrabajoEnfermedadProfesional();
                    //Si el ibc no alcanzo para reportar parafiscales, se debe validar si no tuvo dias de novedad para reportar sobre los dias realmente cotizados
                    if (($arContrato->getVrSalario() > (10 * $salarioMinimo)) && $diasNovedad == 0) {
                        $tarifaSalud = 12.5;
                        $tarifaIcbf = 3;
                        $tarifaSena = 2;
                    } else {
                        $ibcOtrosParafiscales = 0;
                    }
                }
                //20 Estudiantes (Régimen especial-Ley 789/2002)
                if ($arAporteDetalle->getTipoCotizante() == '20') {
                    $ibcCaja = 0;
                    $diasCaja = 0;
                    $tarifaCaja = 0;
                }
                $cotizacionPension = $ibcPension * $tarifaPension / 100;
                $cotizacionSalud = $ibcSalud * $tarifaSalud / 100;
                $cotizacionRiesgos = $ibcRiesgos * $tarifaRiesgos / 100;
                $cotizacionCaja = $ibcCaja * $tarifaCaja / 100;
                $cotizacionSena = $ibcOtrosParafiscales * $tarifaSena / 100;
                $cotizacionIcbf = $ibcOtrosParafiscales * $tarifaIcbf / 100;

                $cotizacionPension = $this->redondearAporte3($cotizacionPension);
                $cotizacionSalud = $this->redondearAporte3($cotizacionSalud);
                $cotizacionRiesgos = $this->redondearAporte3($cotizacionRiesgos);
                $cotizacionCaja = $this->redondearAporte3($cotizacionCaja);
                $cotizacionSena = $this->redondearAporte3($cotizacionSena);
                $cotizacionIcbf = $this->redondearAporte3($cotizacionIcbf);

                if ($arAporteDetalle->getTipoCotizante() == '19' || $arAporteDetalle->getTipoCotizante() == '12' || $arAporteDetalle->getTipoCotizante() == '23') {
                    $cotizacionPension = 0;
                    $cotizacionCaja = 0;
                }
                if ($arAporteDetalle->getTipoCotizante() == '12') {
                    $cotizacionRiesgos = 0;
                }
                if ($arAporteDetalle->getTipoCotizante() == '23') {
                    $cotizacionSalud = 0;
                }
                //1 Dependiente pensionado por vejez activo (SI no es pensionado es = a 00)
                //3 Cotizante no obligado a cotización a pensiones por edad.
                if ($arAporteContrato->getContratoRel()->getCodigoSubtipoCotizanteFk() == 1 || $arAporteContrato->getContratoRel()->getCodigoSubtipoCotizanteFk() == 3) {
                    $arAporteDetalle->setEntidadPensionRel(NULL);
                    $arAporteDetalle->setCodigoEntidadPensionPertenece(NULL);
                    $diasPension = 0;
                    $ibcPension = 0;
                    $tarifaPension = 0;
                    $cotizacionPension = 0;
                }
                if ($arAporteSoporte->getLicenciaMaternidad()) {
                    if ($arAporteSoporte->getRetiro() != "X") {
                        if (!$arrConfiguracionNomina['aportarCajaLicenciaMaternidadPaternidad']) {
                            $tarifaCaja = 0;
                            $cotizacionCaja = 0;
                        }
                    }
                    $tarifaRiesgos = 0;
                    $cotizacionRiesgos = 0;

                }
                $ibcCajaTotal += $ibcCaja;
                $arAporteDetalle->setDiasCotizadosPension($diasPension);
                $arAporteDetalle->setDiasCotizadosSalud($dias);
                $arAporteDetalle->setDiasCotizadosRiesgosProfesionales($diasRiesgos);
                $arAporteDetalle->setDiasCotizadosCajaCompensacion($diasCaja);

                $arAporteDetalle->setIbcPension($ibcPension);
                $arAporteDetalle->setIbcSalud($ibcSalud);
                $arAporteDetalle->setIbcRiesgosProfesionales($ibcRiesgos);
                $arAporteDetalle->setIbcCaja($ibcCaja);
                $arAporteDetalle->setIbcOtrosParafiscalesDiferentesCcf($ibcOtrosParafiscales);
                $arAporteDetalle->setTarifaPension($tarifaPension);
                $arAporteDetalle->setTarifaSalud($tarifaSalud);
                $arAporteDetalle->setTarifaRiesgos($tarifaRiesgos);
                $arAporteDetalle->setTarifaCaja($tarifaCaja);
                $arAporteDetalle->setTarifaIcbf($tarifaIcbf);
                $arAporteDetalle->setTarifaSena($tarifaSena);

                $floCotizacionFSPSolidaridad = 0;
                $floCotizacionFSPSubsistencia = 0;
                $floAporteVoluntarioFondoPensionesObligatorias = 0;
                $floCotizacionVoluntariaFondoPensionesObligatorias = 0;
                if ($arAporteContrato->getIbcFondoSolidaridad() >= ($salarioMinimo * 4)) {
                    $porcentajeSolidaridad = $this->porcentajeFondo($salarioMinimo, $arAporteContrato->getIbcFondoSolidaridad());
                    $porcentajeSubsistencia = $porcentajeSolidaridad - 0.5;
                    $cotizacionSolidaridad = $ibcPension * 0.5 / 100;
                    $cotizacionSubsistencia = $ibcPension * $porcentajeSubsistencia / 100;
                    $floCotizacionFSPSolidaridad = $this->redondearAporte3($cotizacionSolidaridad);
                    $floCotizacionFSPSubsistencia = $this->redondearAporte3($cotizacionSubsistencia);
                }
                $cotizacionFondos = $floAporteVoluntarioFondoPensionesObligatorias + $floCotizacionVoluntariaFondoPensionesObligatorias + $cotizacionPension;

                $arAporteDetalle->setAporteVoluntarioFondoPensionesObligatorias($floAporteVoluntarioFondoPensionesObligatorias);
                $arAporteDetalle->setCotizacionVoluntarioFondoPensionesObligatorias($floCotizacionVoluntariaFondoPensionesObligatorias);
                $arAporteDetalle->setAportesFondoSolidaridadPensionalSolidaridad($floCotizacionFSPSolidaridad);
                $arAporteDetalle->setAportesFondoSolidaridadPensionalSubsistencia($floCotizacionFSPSubsistencia);
                $arAporteDetalle->setTotalCotizacionFondos($cotizacionFondos);
                $arAporteDetalle->setCotizacionPension($cotizacionPension);
                $arAporteDetalle->setCotizacionSalud($cotizacionSalud);
                $arAporteDetalle->setCotizacionRiesgos($cotizacionRiesgos);
                $arAporteDetalle->setCotizacionCaja($cotizacionCaja);
                $arAporteDetalle->setCotizacionIcbf($cotizacionIcbf);
                $arAporteDetalle->setCotizacionSena($cotizacionSena);
                $arAporteDetalle->setCentroTrabajoCodigoCt('0');
                $totalCotizacion = $cotizacionFondos + $cotizacionSalud + $cotizacionRiesgos + $cotizacionCaja + $cotizacionIcbf + $cotizacionSena + $floCotizacionFSPSolidaridad + $floCotizacionFSPSubsistencia;
                $arAporteDetalle->setTotalCotizacion($totalCotizacion);
                $arAporteDetalle->setNumeroHorasLaboradas($arAporteSoporte->getHoras());

                if ($dias > 0) {
                    $totalCotizacionGeneral += $totalCotizacion;
                    $em->persist($arAporteDetalle);
                    $secuencia++;
                }
            }
        }
        $arAporte->setVrTotal($totalCotizacionGeneral);
        $arAporte->setVrIngresoBaseCotizacion($ibcCajaTotal);
        $em->persist($arAporte);
        $em->flush();
    }

    public function redondearIbc($intDias, $floIbcBruto)
    {
        $em = $this->getEntityManager();
        $floIbc = 0;
        $floIbcRedondedado = round($floIbcBruto, -3, PHP_ROUND_HALF_DOWN);
        $floIbcMinimo = $this->redondearIbcMinimo($intDias);
        $floResiduo = fmod($floIbcBruto, 1000);
        if ($floIbcRedondedado < $floIbcMinimo) {
            if ($floResiduo > 500) {
                $floIbc = intval($floIbcBruto / 1000) * 1000 + 1000;
            } else {
                $floIbc = $floIbcBruto;
            }
            $floIbc = ceil($floIbc);
        } else {
            $floIbc = $floIbcRedondedado;
        }

        return $floIbc;
    }

    public function redondearIbcMinimo($intDias)
    {
        $em = $this->getEntityManager();
        $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        $floValorDia = $arConfiguracionNomina->getVrSalario() / 30;
        $floIbcBruto = intval($intDias * $floValorDia);
        return $floIbcBruto;
    }

    public function redondearIbc2($ibc)
    {
        $ibcRetornar = ceil($ibc);
        return $ibcRetornar;
    }

    public function redondearAporte($floIbcTotal, $floIbc, $floTarifa, $intDias)
    {
        $em = $this->getEntityManager();
        $floTarifa = $floTarifa / 100;
        $floIbcBruto = ($floIbcTotal / 30) * $intDias;
        $floCotizacionRedondeada = round($floIbc * $floTarifa, -2, PHP_ROUND_HALF_DOWN);
        $floCotizacionCalculada = $floIbcBruto * $floTarifa;
        $floCotizacionIBC = $floIbc * $floTarifa;
        $floResiduo = fmod($floCotizacionIBC, 100);
        $floCotizacionMinimo = $this->redondearAporteMinimo($floTarifa, $intDias);
        if ($floCotizacionRedondeada < $floCotizacionMinimo) {
            if ($floResiduo > 50) {
                $floCotizacionRedondeada = intval($floCotizacionIBC / 100) * 100 + 100;
            } else {
                if ($floCotizacionIBC - $floResiduo >= $floCotizacionCalculada) {
                    $floCotizacionRedondeada = $floCotizacionIBC - $floResiduo;
                } else {
                    $floCotizacionRedondeada = $floCotizacionIBC;
                }
            }

            if (round($floCotizacionRedondeada) >= $floCotizacionCalculada) {
                $floCotizacion = round($floCotizacionRedondeada);
            } else {
                $floCotizacion = ceil($floCotizacionRedondeada);
            }
        } else {
            $floCotizacion = $floCotizacionRedondeada;
        }
        return $floCotizacion;
    }

    public function redondearAporteMinimo($floTarifa, $intDias)
    {
        $em = $this->getEntityManager();
        $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        $floSalario = $arConfiguracionNomina->getVrSalario();
        $douValorDia = $floSalario / 30;
        $floIbcReal = $douValorDia * $intDias;
        if ($intDias != 30) {
            $floIbcRedondeo = round($floIbcReal, -3, PHP_ROUND_HALF_DOWN);
            if ($floIbcRedondeo > $floIbcReal) {
                $floIbc = ceil($floIbcRedondeo);
            } else {
                $floIbc = ceil($floIbcReal);
            }

        } else {
            $floIbc = $floSalario;
        }
        $douCotizacion = 0;
        $floCotizacionCalculada = $floIbcReal * $floTarifa;
        $floCotizacionIBC = $floIbc * $floTarifa;
        $floResiduo = fmod($floCotizacionIBC, 100);
        if ($floResiduo > 50) {
            $floCotizacionRedondeada = intval($floCotizacionIBC / 100) * 100 + 100;
        } else {
            if ($floCotizacionIBC - $floResiduo >= $floCotizacionCalculada) {
                $floCotizacionRedondeada = $floCotizacionIBC - $floResiduo;
            } else {
                $floCotizacionRedondeada = $floCotizacionIBC;
            }
        }

        if (round($floCotizacionRedondeada) >= $floCotizacionCalculada) {
            $douCotizacion = round($floCotizacionRedondeada);
        } else {
            $douCotizacion = ceil($floCotizacionRedondeada);
        }
        return $douCotizacion;
    }

    public function redondearAporte2($cotizacion, $dias, $tarifa)
    {
        $cotizacionRetornar = 0;
        $cotizacionSalarioMinimo = ((737717 / 30) * $dias) * ($tarifa / 100);
        $cotizacionSalarioMinimo = round($cotizacionSalarioMinimo, -1, PHP_ROUND_HALF_DOWN);
        $residuo = fmod($cotizacion, 100);
        if ($residuo > 50) {
            $cotizacionRetornar = intval($cotizacion / 100) * 100 + 100;
        } else {
            $cotizacionSinResiduo = ceil($cotizacion - $residuo);
            if ($cotizacionSinResiduo <= $cotizacionSalarioMinimo) {
                $cotizacionRetornar = ceil($cotizacion);
            } else {
                $cotizacionRetornar = $cotizacionSinResiduo;
            }
        }
        if ($cotizacionSalarioMinimo > $cotizacionRetornar) {
            $cotizacionRetornar = $cotizacionSalarioMinimo;
        }
        /*$cotizacionSalarioMinimo = round($cotizacionSalarioMinimo, -1, PHP_ROUND_HALF_DOWN);
        if($cotizacionSalarioMinimo > $cotizacionRedondeada) {            
            $cotizacionRetornar = $cotizacionSalarioMinimo;                       
        } else {
            $cotizacionRetornar = $cotizacionRedondeada;
        } */
        return $cotizacionRetornar;
    }

    public function redondearAporte3($cotizacion, $significance = 100)
    {
        $cotizacionRetornar = 0;
        return (is_numeric($cotizacion) && is_numeric($significance)) ? (ceil($cotizacion / $significance) * $significance) : 0;
    }

    public function porcentajeFondo($salarioMinimo, $ibc)
    {
        $salariosMinimos = $ibc / $salarioMinimo;
        $porcentaje = 0;
        if ($salariosMinimos >= 4 && $salariosMinimos < 16) {
            $porcentaje = 1;
        }
        if ($salariosMinimos >= 16 && $salariosMinimos < 17) {
            $porcentaje = 1.2;
        }
        if ($salariosMinimos >= 17 && $salariosMinimos < 18) {
            $porcentaje = 1.4;
        }
        if ($salariosMinimos >= 18 && $salariosMinimos < 19) {
            $porcentaje = 1.6;
        }
        if ($salariosMinimos >= 19 && $salariosMinimos < 20) {
            $porcentaje = 1.8;
        }
        if ($salariosMinimos >= 20) {
            $porcentaje = 2;
        }
        return $porcentaje;
    }

    public function informe(){
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuAporteDetalle::class, 'ad')
            ->select('ad.codigoAporteDetallePk')
            ->addSelect('a.anio')
            ->addSelect('a.mes')
            ->addSelect('ad.fechaDesde')
            ->addSelect('ad.fechaHasta')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.nombreCorto')
            ->addSelect('ad.codigoContratoFk')
            ->addSelect('ad.ingreso')
            ->addSelect('ad.retiro')
            ->addSelect('ad.variacionTransitoriaSalario')
            ->addSelect('ad.suspensionTemporalContratoLicenciaServicios')
            ->addSelect('ad.incapacidadGeneral')
            ->addSelect('ad.licenciaMaternidad')
            ->addSelect('ad.incapacidadAccidenteTrabajoEnfermedadProfesional')
            ->addSelect('ad.salarioBasico')
            ->addSelect('ad.suplementario')
            ->addSelect('ad.diasCotizadosPension')
            ->addSelect('ad.diasCotizadosSalud')
            ->addSelect('ad.diasCotizadosRiesgosProfesionales')
            ->addSelect('ad.diasCotizadosCajaCompensacion')
            ->addSelect('ad.ibcPension')
            ->addSelect('ad.ibcSalud')
            ->addSelect('ad.ibcRiesgosProfesionales')
            ->addSelect('ad.ibcCaja')
            ->addSelect('ad.tarifaPension')
            ->addSelect('ad.tarifaSalud')
            ->addSelect('ad.tarifaRiesgos')
            ->addSelect('ad.tarifaCaja')
            ->addSelect('ad.cotizacionPension')
            ->addSelect('ad.cotizacionSalud')
            ->addSelect('ad.cotizacionRiesgos')
            ->addSelect('ad.cotizacionCaja')
            ->addSelect('ad.aportesFondoSolidaridadPensionalSolidaridad')
            ->addSelect('ad.aportesFondoSolidaridadPensionalSubsistencia')
            ->addSelect('ad.diasLicencia')
            ->leftJoin('ad.aporteRel', 'a')
            ->leftJoin('ad.empleadoRel', 'e')
            ->orderBy('a.codigoAportePk', 'DESC');
        if ($session->get('filtroRhuAporteAnio') != '') {
            $queryBuilder->andWhere("a.anio LIKE '%{$session->get('filtroRhuAporteAnio')}%' ");
        }
        if ($session->get('filtroRhuAporteMes') != '') {
            $queryBuilder->andWhere("a.mes = {$session->get('filtroRhuAporteMes')} ");
        }
        if ($session->get('filtroRhuInformeAporteFechaDesde') != null) {
            $queryBuilder->andWhere("ad.fechaDesde >= '{$session->get('filtroRhuInformeAporteFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroRhuInformeAporteFechaHasta') != null) {
            $queryBuilder->andWhere("ad.fechaHasta <= '{$session->get('filtroRhuInformeAporteFechaHasta')} 23:59:59'");
        }
        if ($session->get('filtroRhuInformeAporteCodigoEmpleado') != null) {
            $queryBuilder->andWhere("ad.codigoEmpleadoFk = {$session->get('filtroRhuInformeAporteCodigoEmpleado')}");
        }
        return $queryBuilder;
    }

    public function ibcMesAnterior($anio, $mes, $codigoEmpleado)
    {
        if ($mes == 1) {
            $anio -= 1;
            $mes = 12;
        } else {
            $mes -= 1;
        }
        $arrResultado = array('respuesta' => false, 'ibc' => 0, 'dias' => 0);
        $em = $this->getEntityManager();
        $dql = "SELECT SUM(ssoa.ibcPension) as ibcPension, SUM(ssoa.ibcSalud) as ibcSalud, SUM(ssoa.diasCotizadosPension) as diasPension, SUM(ssoa.diasCotizadosSalud) as diasSalud FROM App\Entity\RecursoHumano\RhuAporteDetalle ssoa "
            . "WHERE ssoa.anio = $anio AND ssoa.mes = $mes" . " "
            . "AND ssoa.codigoEmpleadoFk = " . $codigoEmpleado;
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $resultados = $arrayResultado[0];
        if ($resultados['ibcPension'] == null) {
            if ($mes == 1) {
                $anio -= 1;
                $mes = 12;
            } else {
                $mes -= 1;
            }
            $arrResultado = array('respuesta' => false, 'ibc' => 0, 'dias' => 0);
            $em = $this->getEntityManager();
            $dql = "SELECT SUM(ssoa.ibcPension) as ibcPension, SUM(ssoa.diasCotizadosPension) as diasPension, SUM(ssoa.ibcSalud) as ibcSalud,SUM(ssoa.diasCotizadosSalud) as diasSalud FROM App\Entity\RecursoHumano\RhuAporteDetalle ssoa "
                . "WHERE ssoa.anio = $anio AND ssoa.mes = $mes" . " "
                . "AND ssoa.codigoEmpleadoFk = " . $codigoEmpleado;
            $query = $em->createQuery($dql);
            $arrayResultado = $query->getResult();
            $resultados = $arrayResultado[0];
            if ($resultados['ibcPension'] == null) {
                $arrResultado['ibc'] = 0;
                $arrResultado['dias'] = 0;
                $arrResultado['respuesta'] = false;
            } else {
                $arrResultado['ibc'] = $resultados['ibcPension'];
                $arrResultado['dias'] = $resultados['diasPension'];
                if ($arrResultado['ibc'] == 0 && $arrResultado['dias'] == 0) {
                    $arrResultado['ibc'] = $resultados['ibcSalud'];
                    $arrResultado['dias'] = $resultados['diasSalud'];
                }
                $arrResultado['respuesta'] = true;
            }
        } else {
            $arrResultado['ibc'] = $resultados['ibcPension'];
            $arrResultado['dias'] = $resultados['diasPension'];
            if ($arrResultado['ibc'] == 0 && $arrResultado['dias'] == 0) {
                $arrResultado['ibc'] = $resultados['ibcSalud'];
                $arrResultado['dias'] = $resultados['diasSalud'];
            }
            $arrResultado['respuesta'] = true;
        }
        return $arrResultado;
    }

}
