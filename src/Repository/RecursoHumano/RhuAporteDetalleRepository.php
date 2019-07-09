<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteContrato;
use App\Entity\RecursoHumano\RhuAporteDetalle;
use App\Entity\RecursoHumano\RhuAporteSoporte;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
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
            ->addSelect('e.nombreCorto as empleadoNombreCorto')
            ->addSelect('e.numeroIdentificacion as empleadoNumeroIdentificacion')
            ->leftJoin('ad.empleadoRel', 'e')
            ->where('ad.codigoAporteFk = ' . $codigoAporte);
        return $queryBuilder;
    }


    public function generar($arAporte)
    {
        $em = $this->getEntityManager();
        $secuencia = 1 ;
        $arAporteContratos = $em->getRepository(RhuAporteContrato::class)->listaGenerarDetalle($arAporte->getCodigoAportePk());
        foreach ($arAporteContratos as $arAporteContrato) {
            $arContrato = $em->getRepository(RhuContrato::class)->find($arAporteContrato['codigoContratoFk']);
            $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($arAporteContrato['codigoEmpleadoFk']);
            $arAporteSoportes = $em->getRepository(RhuAporteSoporte::class)->listaGenerarDetalle($arAporteContrato['codigoAporteContratoPk']);
            foreach ($arAporteSoportes as $arAporteSoporte) {
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
                /*$codigoTipoDocumento = $arEmpleado->getPermisoEspecial() != "" ? "PE" : $arEmpleado->getTipoIdentificacionRel()->getCodigoInterface();
                $arAporte->setTipoDocumento($codigoTipoDocumento);
                $arAporte->setTipoCotizante($arPeriodoEmpleado->getContratoRel()->getCodigoTipoCotizanteFk());
                $arAporte->setSubtipoCotizante($arPeriodoEmpleado->getContratoRel()->getCodigoSubtipoCotizanteFk());
                $arAporte->setExtranjeroNoObligadoCotizarPension(' ');
                $arAporte->setColombianoResidenteExterior(' ');
                $arAporte->setCodigoDepartamentoUbicacionlaboral($arPeriodoEmpleado->getContratoRel()->getCiudadLaboraRel()->getDepartamentoRel()->getCodigoDane());
                $arAporte->setCodigoMunicipioUbicacionlaboral($arPeriodoEmpleado->getContratoRel()->getCiudadLaboraRel()->getCodigoDane());
                $arAporte->setPrimerNombre($arEmpleado->getNombre1());
                $arAporte->setSegundoNombre($arEmpleado->getNombre2());
                $arAporte->setPrimerApellido($arEmpleado->getApellido1());
                $arAporte->setSegundoApellido($arEmpleado->getApellido2());
                $arAporte->setIngreso($arPeriodoEmpleadoDetalle->getIngreso());
                $arAporte->setRetiro($arPeriodoEmpleadoDetalle->getRetiro());
                $arAporte->setCargoRel($arContrato->getCargoRel());*/
                //Parametros generales
                $dias = $arAporteSoporte['dias'];
                /*$salario = $arPeriodoEmpleadoDetalle->getVrSalario();
                $ibc = $arPeriodoEmpleadoDetalle->getIbc();
                $ibcCajaVacaciones = $arPeriodoEmpleadoDetalle->getIbcCajaVacaciones();
                $vacaciones = $arPeriodoEmpleadoDetalle->getVrVacaciones();
                $arAporte->setVrVacaciones($vacaciones);
                $arAporte->setVrIngresoBaseCotizacion($ibc);
                if ($arPeriodoEmpleadoDetalle->getIncapacidadGeneral()) {
                    $arAporte->setIncapacidadGeneral('X');
                    $arAporte->setDiasIncapacidadGeneral($dias);
                    $arAporte->setFechaInicioIge($arPeriodoEmpleadoDetalle->getFechaDesde()->format('Y-m-d'));
                    $arAporte->setFechaFinIge($arPeriodoEmpleadoDetalle->getFechaHasta()->format('Y-m-d'));
                }
                if ($arPeriodoEmpleadoDetalle->getIncapacidadLaboral()) {
                    $arAporte->setIncapacidadAccidenteTrabajoEnfermedadProfesional($dias);
                    $arAporte->setFechaInicioIrl($arPeriodoEmpleadoDetalle->getFechaDesde()->format('Y-m-d'));
                    $arAporte->setFechaFinIrl($arPeriodoEmpleadoDetalle->getFechaHasta()->format('Y-m-d'));
                }
                if ($arPeriodoEmpleadoDetalle->getLicencia()) {
                    $arAporte->setDiasLicencia($dias);
                    $arAporte->setSuspensionTemporalContratoLicenciaServicios('X');
                    $arAporte->setFechaInicioSln($arPeriodoEmpleadoDetalle->getFechaDesde()->format('Y-m-d'));
                    $arAporte->setFechaFinSln($arPeriodoEmpleadoDetalle->getFechaHasta()->format('Y-m-d'));
                }
                if ($arPeriodoEmpleadoDetalle->getLicenciaMaternidad()) {
                    $arAporte->setLicenciaMaternidad('X');
                    $arAporte->setDiasLicenciaMaternidad($dias);
                    $arAporte->setFechaInicioLma($arPeriodoEmpleadoDetalle->getFechaDesde()->format('Y-m-d'));
                    $arAporte->setFechaFinLma($arPeriodoEmpleadoDetalle->getFechaHasta()->format('Y-m-d'));
                }
                if ($arPeriodoEmpleadoDetalle->getVacaciones()) {
                    $arAporte->setVacaciones('X');
                    $arAporte->setDiasVacaciones($dias);
                    $arAporte->setFechaInicioVacLr($arPeriodoEmpleadoDetalle->getFechaDesde()->format('Y-m-d'));
                    $arAporte->setFechaFinVacLr($arPeriodoEmpleadoDetalle->getFechaHasta()->format('Y-m-d'));
                }
                if ($arPeriodoEmpleadoDetalle->getLicenciaRemunerada()) {
                    $arAporte->setVacaciones('L');
                    $arAporte->setFechaInicioVacLr($arPeriodoEmpleadoDetalle->getFechaDesde()->format('Y-m-d'));
                    $arAporte->setFechaFinVacLr($arPeriodoEmpleadoDetalle->getFechaHasta()->format('Y-m-d'));
                    $arAporte->setSuspensionTemporalContratoLicenciaServicios('');
                    $arAporte->setFechaInicioSln(null);
                    $arAporte->setFechaFinSln(null);
                }
                if ($arPeriodoEmpleadoDetalle->getRetiro() == "X") {
                    $arAporte->setFechaRetiro($arPeriodoEmpleadoDetalle->getFechaRetiro()->format('Y-m-d'));
                }
                if ($arPeriodoEmpleadoDetalle->getIngreso() == "X") {
                    $arAporte->setFechaIngreso($arPeriodoEmpleadoDetalle->getFechaIngreso()->format('Y-m-d'));
                }
                //Validar si el empleado tiene traslado a otra eps
                if ($arPeriodoEmpleadoDetalle->getTrasladoAOtraEps()) {
                    $arAporte->setTrasladoAOtraEps("X");
                    $arAporte->setCodigoEntidadSaludTraslada($arPeriodoEmpleadoDetalle->getCodigoEntidadSaludTraslada());
                }
                //Validar si el empleado tiene traslado a otra pension
                if ($arPeriodoEmpleadoDetalle->getTrasladoAOtraPension()) {
                    $arAporte->setTrasladoAOtraPension("X");
                    $arAporte->setCodigoEntidadPensionTraslada($arPeriodoEmpleadoDetalle->getCodigoEntidadPensionTraslada());
                }
                //Validar si el empleado tiene traslado desde otra eps
                if ($arPeriodoEmpleadoDetalle->getTrasladoDesdeOtraEps()) {
                    $arAporte->setTrasladoDesdeOtraEps("X");
                }
                //Validar si el empleado tiene traslado desde otra pension
                if ($arPeriodoEmpleadoDetalle->getTrasladoDesdeOtraPension()) {
                    $arAporte->setTrasladoDesdeOtraPension("X");
                }
                // 19 Aprendices del Sena en etapa productiva
                if ($arPeriodoEmpleado->getContratoRel()->getCodigoTipoCotizanteFk() != 19 && $arPeriodoEmpleado->getContratoRel()->getCodigoTipoCotizanteFk() != 12) {
                    $arAporte->setVariacionTransitoriaSalario($arPeriodoEmpleadoDetalle->getVariacionTransitoriaSalario());
                }
                $arAporte->setSalarioIntegral($arPeriodoEmpleado->getSalarioIntegral());
                $arAporte->setSalarioBasico($salario);
                $arAporte->setCodigoEntidadPensionPertenece($arPeriodoEmpleado->getCodigoEntidadPensionPertenece());
                $arAporte->setCodigoEntidadSaludPertenece($arPeriodoEmpleado->getCodigoEntidadSaludPertenece());
                $arAporte->setCodigoEntidadCajaPertenece($arPeriodoEmpleado->getCodigoEntidadCajaPertenece());
                $arAporte->setEntidadPensionRel($arContrato->getEntidadPensionRel());
                $arAporte->setEntidadSaludRel($arContrato->getEntidadSaludRel());
                $arAporte->setEntidadCajaRel($arContrato->getEntidadCajaRel());
                $arAporte->setEntidadRiesgoProfesionalRel($arEntidadRiesgos);

                $diasPension = $dias;
                $diasRiesgos = $dias;
                $diasCaja = $dias;

                $ibc = $this->redondearIbc2($ibc);
                $ibcPension = $ibc;
                $ibcSalud = $ibc;
                $ibcRiesgos = $ibc;
                $ibcCaja = $this->redondearIbc2($ibc + $vacaciones);
                if ($arPeriodoEmpleadoDetalle->getVacaciones()) {
                    $ibcCaja = $this->redondearIbc2($ibcCajaVacaciones);
                }
                //Si tiene licencia y retiro
                if ($arPeriodoEmpleadoDetalle->getLicenciaMaternidad()) {
                    if ($arPeriodoEmpleadoDetalle->getRetiro() == "X") {
                        $ibcCaja = $this->redondearIbc2($vacaciones);
                    }
                }
                //Si tiene incapacidad y retiro
                if ($arPeriodoEmpleadoDetalle->getIncapacidadGeneral()) {
                    if ($arPeriodoEmpleadoDetalle->getRetiro() == "X") {
                        $ibcCaja = $this->redondearIbc2($vacaciones);
                    }
                }
                $ibcOtrosParafiscales = $ibc;
                // se valida si es empleado devenga mas de 25 SMLV y aporta unicamente sobre los 25 SMLv
                //Según la ley 797 de 2003 en su articulo 5.
                if ($ibc >= ($arConfiguracionNomina->getVrSalario() * 25)) {
                    $ibc = $arConfiguracionNomina->getVrSalario() * 25;
                    $ibcPension = $ibc;
                    $ibcSalud = $ibc;
                    $ibcRiesgos = $ibc;
                }


                $tarifaPension = $arPeriodoEmpleadoDetalle->getTarifaPension();
                $tarifaSalud = $arPeriodoEmpleadoDetalle->getTarifaSalud();
                $tarifaRiesgos = $arPeriodoEmpleadoDetalle->getTarifaRiesgos();
                $tarifaCaja = $arPeriodoEmpleadoDetalle->getTarifaCaja();
                $tarifaIcbf = 0;
                $tarifaSena = 0;
                if ($arAporte->getTipoCotizante() == '19' || $arAporte->getTipoCotizante() == '12' || $arAporte->getTipoCotizante() == '23') {
                    $diasPension = 0;
                    $tarifaPension = 0;
                    $ibcPension = 0;
                    $tarifaSalud = 12.5;
                    $ibcCaja = 0;
                    $diasCaja = 0;
                    $tarifaCaja = 0;
                }
                if ($arAporte->getTipoCotizante() == '12') {
                    $diasRiesgos = 0;
                    $tarifaRiesgos = 0;
                    if ($arAporte->getSubtipoCotizante() == '0') {
                        $ibcRiesgos = 0;
                    }
                }
                if ($arAporte->getTipoCotizante() == '23') {
                    $tarifaSalud = 0;
                }
                if ((($ibc) > (10 * $arConfiguracionNomina->getVrSalario()))) {
                    $tarifaSalud = 12.5;
                    $tarifaIcbf = 3;
                    $tarifaSena = 2;
                } else {
                    $diasNovedad = $arAporte->getDiasLicencia() + $arAporte->getDiasIncapacidadGeneral() + $arAporte->getDiasLicenciaMaternidad() + $arAporte->getDiasVacaciones() + $arAporte->getIncapacidadAccidenteTrabajoEnfermedadProfesional();
                    //Si el ibc no alcanzo para reportar parafiscales, se debe validar si no tuvo dias de novedad para reportar sobre los dias realmente cotizados
                    if (($arContrato->getVrSalario() > (10 * $arConfiguracionNomina->getVrSalario())) && $diasNovedad == 0) {
                        $tarifaSalud = 12.5;
                        $tarifaIcbf = 3;
                        $tarifaSena = 2;
                    } else {
                        $ibcOtrosParafiscales = 0;
                    }
                }
                //20 Estudiantes (Régimen especial-Ley 789/2002)
                if ($arAporte->getTipoCotizante() == '20') {
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

                if ($arAporte->getTipoCotizante() == '19' || $arAporte->getTipoCotizante() == '12' || $arAporte->getTipoCotizante() == '23') {
                    $cotizacionPension = 0;
                    $cotizacionCaja = 0;
                }
                if ($arAporte->getTipoCotizante() == '12') {
                    $cotizacionRiesgos = 0;
                }
                if ($arAporte->getTipoCotizante() == '23') {
                    $cotizacionSalud = 0;
                }
                //1 Dependiente pensionado por vejez activo (SI no es pensionado es = a 00)
                //3 Cotizante no obligado a cotización a pensiones por edad.
                if ($arPeriodoEmpleado->getContratoRel()->getCodigoSubtipoCotizanteFk() == 1 || $arPeriodoEmpleado->getContratoRel()->getCodigoSubtipoCotizanteFk() == 3) {
                    $arAporte->setEntidadPensionRel(NULL);
                    $arAporte->setCodigoEntidadPensionPertenece(NULL);
                    $diasPension = 0;
                    $ibcPension = 0;
                    $tarifaPension = 0;
                    $cotizacionPension = 0;
                }
                if ($arPeriodoEmpleadoDetalle->getLicenciaMaternidad()) {
                    if ($arPeriodoEmpleadoDetalle->getRetiro() != "X") {
                        if (!$arConfiguracionNomina->getAportarCajaLicenciaMaternidadPaternidad()) {
                            $tarifaCaja = 0;
                            $cotizacionCaja = 0;
                        }
                    }
                    $tarifaRiesgos = 0;
                    $cotizacionRiesgos = 0;

                }
                $ibcCajaTotal += $ibcCaja;
                $arAporte->setDiasCotizadosPension($diasPension);
                $arAporte->setDiasCotizadosSalud($dias);
                $arAporte->setDiasCotizadosRiesgosProfesionales($diasRiesgos);
                $arAporte->setDiasCotizadosCajaCompensacion($diasCaja);

                $arAporte->setIbcPension($ibcPension);
                $arAporte->setIbcSalud($ibcSalud);
                $arAporte->setIbcRiesgosProfesionales($ibcRiesgos);
                $arAporte->setIbcCaja($ibcCaja);
                $arAporte->setIbcOtrosParafiscalesDiferentesCcf($ibcOtrosParafiscales);
                $arAporte->setTarifaPension($tarifaPension);
                $arAporte->setTarifaSalud($tarifaSalud);
                $arAporte->setTarifaRiesgos($tarifaRiesgos);
                $arAporte->setTarifaCaja($tarifaCaja);
                $arAporte->setTarifaIcbf($tarifaIcbf);
                $arAporte->setTarifaSena($tarifaSena);

                $floCotizacionFSPSolidaridad = 0;
                $floCotizacionFSPSubsistencia = 0;
                $floAporteVoluntarioFondoPensionesObligatorias = 0;
                $floCotizacionVoluntariaFondoPensionesObligatorias = 0;
                //if(!$arPeriodoEmpleadoDetalle->getVacaciones()) {
                if ($arPeriodoEmpleado->getIbcFondoSolidaridad() >= ($arConfiguracionNomina->getVrSalario() * 4)) {
                    $porcentajeSolidaridad = $this->porcentajeFondo($arConfiguracionNomina->getVrSalario(), $arPeriodoEmpleado->getIbcFondoSolidaridad());
                    $porcentajeSubsistencia = $porcentajeSolidaridad - 0.5;
                    //Antes era asi para que quedara todo en una sola linea
                    //$cotizacionSolidaridad = $arPeriodoEmpleado->getIbcFondoSolidaridad() * 0.5 / 100;
                    //$cotizacionSubsistencia = $arPeriodoEmpleado->getIbcFondoSolidaridad() * $porcentajeSubsistencia / 100;

                    $cotizacionSolidaridad = $ibcPension * 0.5 / 100;
                    $cotizacionSubsistencia = $ibcPension * $porcentajeSubsistencia / 100;

                    $floCotizacionFSPSolidaridad = $this->redondearAporte3($cotizacionSolidaridad);
                    $floCotizacionFSPSubsistencia = $this->redondearAporte3($cotizacionSubsistencia);
                }
                //}

                $cotizacionFondos = $floAporteVoluntarioFondoPensionesObligatorias + $floCotizacionVoluntariaFondoPensionesObligatorias + $cotizacionPension;

                $arAporte->setAporteVoluntarioFondoPensionesObligatorias($floAporteVoluntarioFondoPensionesObligatorias);
                $arAporte->setCotizacionVoluntarioFondoPensionesObligatorias($floCotizacionVoluntariaFondoPensionesObligatorias);
                $arAporte->setAportesFondoSolidaridadPensionalSolidaridad($floCotizacionFSPSolidaridad);
                $arAporte->setAportesFondoSolidaridadPensionalSubsistencia($floCotizacionFSPSubsistencia);
                $arAporte->setTotalCotizacionFondos($cotizacionFondos);
                $arAporte->setCotizacionPension($cotizacionPension);
                $arAporte->setCotizacionSalud($cotizacionSalud);
                $arAporte->setCotizacionRiesgos($cotizacionRiesgos);
                $arAporte->setCotizacionCaja($cotizacionCaja);
                $arAporte->setCotizacionIcbf($cotizacionIcbf);
                $arAporte->setCotizacionSena($cotizacionSena);
                $arAporte->setCentroTrabajoCodigoCt($arPeriodoEmpleado->getContratoRel()->getCodigoCentroCostoFk());
                $totalCotizacion = $cotizacionFondos + $cotizacionSalud + $cotizacionRiesgos + $cotizacionCaja + $cotizacionIcbf + $cotizacionSena + $floCotizacionFSPSolidaridad + $floCotizacionFSPSubsistencia;
                $arAporte->setTotalCotizacion($totalCotizacion);
                $arAporte->setNumeroHorasLaboradas($arPeriodoEmpleadoDetalle->getHoras());
                */
                if ($dias > 0) {
                    //$totalCotizacionGeneral += $totalCotizacion;
                    $em->persist($arAporteDetalle);
                    $secuencia++;
                }
            }
        }
        $em->flush();
    }
}