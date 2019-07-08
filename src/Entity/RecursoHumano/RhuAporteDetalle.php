<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAporteDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuAporteDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoAporteDetallePk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_aporte_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAporteDetallePk;

    /**
     * @ORM\Column(name="codigo_aporte_fk", type="integer")
     */
    private $codigoAporteFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_sucursal_fk", length=10, nullable=true)
     */
    private $codigoSucursalFk;

    /**
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio = 0;

    /**
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes = 0;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;


    /**
     * @ORM\Column(name="tipo_registro", type="bigint", nullable=true)
     */
    private $tipoRegistro;

    /**
     * @ORM\Column(name="secuencia", type="smallint", nullable=true)
     */
    private $secuencia;

    /**
     * @ORM\Column(name="tipo_documento", type="string", length=10, nullable=true)
     */
    private $tipoDocumento;

    /**
     * @ORM\Column(name="tipo_cotizante", type="smallint", nullable=true)
     */
    private $tipoCotizante;

    /**
     * @ORM\Column(name="subtipo_cotizante", type="smallint", nullable=true)
     */
    private $subtipoCotizante;

    /**
     * @ORM\Column(name="extranjero_no_obligado_cotizar_pension", type="string", length=1, nullable=true)
     */
    private $extranjeroNoObligadoCotizarPension;

    /**
     * @ORM\Column(name="colombiano_residente_exterior", type="string", length=1, nullable=true)
     */
    private $colombianoResidenteExterior;

    /**
     * @ORM\Column(name="codigo_departamento_ubicacion_laboral", type="string", length=2, nullable=true)
     */
    private $codigoDepartamentoUbicacionlaboral;

    /**
     * @ORM\Column(name="codigo_municipio_ubicacion_laboral", type="string", length=5, nullable=true)
     */
    private $codigoMunicipioUbicacionlaboral;

    /**
     * @ORM\Column(name="primer_nombre", type="string", length=20, nullable=true)
     */
    private $primerNombre;

    /**
     * @ORM\Column(name="segundo_nombre", type="string", length=30, nullable=true, nullable=true)
     */
    private $segundoNombre;

    /**
     * @ORM\Column(name="primer_apellido", type="string", length=20, nullable=true)
     */
    private $primerApellido;

    /**
     * @ORM\Column(name="segundo_apellido", type="string", length=30, nullable=true)
     */
    private $segundoApellido;

    /**
     * @ORM\Column(name="ingreso", type="string", length=1, nullable=true)
     */
    private $ingreso = ' ';

    /**
     * @ORM\Column(name="retiro", type="string", length=1, nullable=true)
     */
    private $retiro = ' ';

    /**
     * @ORM\Column(name="traslado_desde_otra_eps", type="string", length=1, nullable=true)
     */
    private $trasladoDesdeOtraEps = ' ';

    /**
     * @ORM\Column(name="traslado_a_otra_eps", type="string", length=1, nullable=true)
     */
    private $trasladoAOtraEps = ' ';

    /**
     * @ORM\Column(name="traslado_desde_otra_pension", type="string", length=1, nullable=true)
     */
    private $trasladoDesdeOtraPension = ' ';

    /**
     * @ORM\Column(name="traslado_a_otra_pension", type="string", length=1, nullable=true)
     */
    private $trasladoAOtraPension = ' ';

    /**
     * @ORM\Column(name="variacion_permanente_salario", type="string", length=1, nullable=true)
     */
    private $variacionPermanenteSalario = ' ';

    /**
     * @ORM\Column(name="correcciones", type="string", length=1, nullable=true)
     */
    private $correcciones = ' ';

    /**
     * @ORM\Column(name="variacion_transitoria_salario", type="string", length=1, nullable=true)
     */
    private $variacionTransitoriaSalario = ' ';

    /**
     * @ORM\Column(name="suspension_temporal_contrato_licencia_servicios", type="string", length=1, nullable=true)
     */
    private $suspensionTemporalContratoLicenciaServicios = ' ';

    /**
     * @ORM\Column(name="dias_licencia", type="integer", nullable=true)
     */
    private $diasLicencia = 0;

    /**
     * @ORM\Column(name="incapacidad_general", type="string", length=1, nullable=true)
     */
    private $incapacidadGeneral = ' ';

    /**
     * @ORM\Column(name="dias_incapacidad_general", type="integer", nullable=true)
     */
    private $diasIncapacidadGeneral = 0;

    /**
     * @ORM\Column(name="licencia_maternidad", type="string", length=1, nullable=true)
     */
    private $licenciaMaternidad = ' ';

    /**
     * @ORM\Column(name="dias_licencia_maternidad", type="integer", nullable=true)
     */
    private $diasLicenciaMaternidad = 0;

    /**
     * @ORM\Column(name="vacaciones", type="string", length=1, nullable=true)
     */
    private $vacaciones = ' ';

    /**
     * @ORM\Column(name="dias_vacaciones", type="integer", nullable=true)
     */
    private $diasVacaciones = 0;

    /**
     * @ORM\Column(name="aporte_voluntario", type="string", length=1, nullable=true)
     */
    private $aporteVoluntario = ' ';

    /**
     * @ORM\Column(name="variacion_centros_trabajo", type="string", length=1, nullable=true)
     */
    private $variacionCentrosTrabajo = ' ';

    /**
     * @ORM\Column(name="incapacidad_accidente_trabajo_enfermedad_profesional", type="integer", nullable=true)
     */
    private $incapacidadAccidenteTrabajoEnfermedadProfesional = 0;

    /**
     * @ORM\Column(name="codigo_entidad_pension_pertenece", type="string", length=6, nullable=true)
     */
    private $codigoEntidadPensionPertenece;

    /**
     * @ORM\Column(name="codigo_entidad_pension_traslada", type="string", length=6, nullable=true)
     */
    private $codigoEntidadPensionTraslada;

    /**
     * @ORM\Column(name="codigo_entidad_salud_pertenece", type="string", length=6, nullable=true)
     */
    private $codigoEntidadSaludPertenece;

    /**
     * @ORM\Column(name="codigo_entidad_salud_traslada", type="string", length=6, nullable=true)
     */
    private $codigoEntidadSaludTraslada;

    /**
     * @ORM\Column(name="codigo_entidad_caja_pertenece", type="string", length=6, nullable=true)
     */
    private $codigoEntidadCajaPertenece;

    /**
     * @ORM\Column(name="dias_cotizados_pension", type="integer", nullable=true)
     */
    private $diasCotizadosPension = 0;

    /**
     * @ORM\Column(name="dias_cotizados_salud", type="integer", nullable=true)
     */
    private $diasCotizadosSalud = 0;

    /**
     * @ORM\Column(name="dias_cotizados_riesgos_profesionales", type="integer", nullable=true)
     */
    private $diasCotizadosRiesgosProfesionales = 0;

    /**
     * @ORM\Column(name="dias_cotizados_caja_compensacion", type="integer", nullable=true)
     */
    private $diasCotizadosCajaCompensacion = 0;

    /**
     * @ORM\Column(name="salario_basico", type="float", nullable=true)
     */
    private $salarioBasico = 0;

    /**
     * @ORM\Column(name="salario_mes_anterior", type="float", nullable=true)
     */
    private $salarioMesAnterior = 0;

    /**
     * @ORM\Column(name="vr_vacaciones", type="float", nullable=true)
     */
    private $vrVacaciones = 0;

    /**
     * @ORM\Column(name="salario_integral", type="string", length=1, nullable=true)
     */
    private $salarioIntegral = ' ';

    /**
     * @ORM\Column(name="suplementario", type="float", nullable=true)
     */
    private $suplementario = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_cotizacion", type="float", nullable=true)
     */
    private $vrIngresoBaseCotizacion = 0;

    /**
     * @ORM\Column(name="ibc_pension", type="float", nullable=true)
     */
    private $ibcPension = 0;

    /**
     * @ORM\Column(name="ibc_salud", type="float", nullable=true)
     */
    private $ibcSalud = 0;

    /**
     * @ORM\Column(name="ibc_riesgos_profesionales", type="float", nullable=true)
     */
    private $ibcRiesgosProfesionales = 0;

    /**
     * @ORM\Column(name="ibc_caja", type="float", nullable=true)
     */
    private $ibcCaja = 0;

    /**
     * @ORM\Column(name="tarifa_pension", type="float", nullable=true)
     */
    private $tarifaPension = 0;

    /**
     * @ORM\Column(name="tarifa_salud", type="float", nullable=true)
     */
    private $tarifaSalud = 0;

    /**
     * @ORM\Column(name="tarifa_riesgos", type="float", nullable=true)
     */
    private $tarifaRiesgos = 0;

    /**
     * @ORM\Column(name="tarifa_caja", type="float", nullable=true)
     */
    private $tarifaCaja = 0;

    /**
     * @ORM\Column(name="tarifa_sena", type="float", nullable=true)
     */
    private $tarifaSena = 0;

    /**
     * @ORM\Column(name="tarifa_icbf", type="float", nullable=true)
     */
    private $tarifaIcbf = 0;

    /**
     * @ORM\Column(name="cotizacion_pension", type="float", nullable=true)
     */
    private $cotizacionPension = 0;

    /**
     * @ORM\Column(name="cotizacion_salud", type="float", nullable=true)
     */
    private $cotizacionSalud = 0;

    /**
     * @ORM\Column(name="cotizacion_riesgos", type="float", nullable=true)
     */
    private $cotizacionRiesgos = 0;

    /**
     * @ORM\Column(name="cotizacion_caja", type="float", nullable=true)
     */
    private $cotizacionCaja = 0;

    /**
     * @ORM\Column(name="cotizacion_sena", type="float", nullable=true)
     */
    private $cotizacionSena = 0;

    /**
     * @ORM\Column(name="cotizacion_icbf", type="float", nullable=true)
     */
    private $cotizacionIcbf = 0;

    /**
     * @ORM\Column(name="aporte_voluntario_fondo_pensiones_obligatorias", type="string", length=9, nullable=true)
     */
    private $aporteVoluntarioFondoPensionesObligatorias;

    /**
     * @ORM\Column(name="cotizacion_voluntario_fondo_pensiones_obligatorias", type="string", length=9, nullable=true)
     */
    private $cotizacionVoluntarioFondoPensionesObligatorias;

    /**
     * @ORM\Column(name="total_cotizacion_fondos", type="float", nullable=true)
     */
    private $totalCotizacionFondos;

    /**
     * @ORM\Column(name="aportes_fondo_solidaridad_pensional_solidaridad", type="float", nullable=true)
     */
    private $aportesFondoSolidaridadPensionalSolidaridad = 0;

    /**
     * @ORM\Column(name="aportes_fondo_solidaridad_pensional_subsistencia", type="float", nullable=true)
     */
    private $aportesFondoSolidaridadPensionalSubsistencia = 0;

    /**
     * @ORM\Column(name="valor_upc_adicional", type="float", nullable=true)
     */
    private $valorUpcAdicional = 0;

    /**
     * @ORM\Column(name="numero_autorizacion_incapacidad_enfermedad_general", type="string", length=30, nullable=true)
     */
    private $numeroAutorizacionIncapacidadEnfermedadGeneral;

    /**
     * @ORM\Column(name="valor_incapacidad_enfermedad_general", type="float", nullable=true)
     */
    private $valorIncapacidadEnfermedadGeneral = 0;

    /**
     * @ORM\Column(name="numero_autorizacion_licencia_maternidad_paternidad", type="string", length=30, nullable=true)
     */
    private $numeroAutorizacionLicenciaMaternidadPaternidad;

    /**
     * @ORM\Column(name="valor_incapacidad_licencia_maternidad_paternidad", type="float", nullable=true)
     */
    private $valorIncapacidadLicenciaMaternidadPaternidad = 0;

    /**
     * @ORM\Column(name="centro_trabajo_codigo_ct", type="string", length=30, nullable=true)
     */
    private $centroTrabajoCodigoCt;

    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */
    private $codigoCargoFk;

    /**
     * @ORM\Column(name="tarifa_aporte_esap", type="float", nullable=true)
     */
    private $tarifaAportesESAP = 0;

    /**
     * @ORM\Column(name="valor_aporte_esap", type="float", nullable=true)
     */
    private $valorAportesESAP = 0;

    /**
     * @ORM\Column(name="tarifa_aporte_men", type="float", nullable=true)
     */
    private $tarifaAportesMEN = 0;

    /**
     * @ORM\Column(name="valor_aporte_men", type="float", nullable=true)
     */
    private $valorAportesMEN = 0;

    /**
     * @ORM\Column(name="tipo_documento_responsable_upc", type="string", length=4, nullable=true)
     */
    private $tipoDocumentoResponsableUPC;

    /**
     * @ORM\Column(name="numero_identificacion_responsable_upc_adicional", type="string", length=30, nullable=true)
     */
    private $numeroIdentificacionResponsableUPCAdicional;

    /**
     * @ORM\Column(name="cotizante_exonerado_pago_aporte_parafiscales_salud", type="string", length=20, nullable=true)
     */
    private $cotizanteExoneradoPagoAporteParafiscalesSalud;

    /**
     * @ORM\Column(name="codigo_administradora_riesgos_laborales", type="string", length=20, nullable=true)
     */
    private $codigoAdministradoraRiesgosLaborales;

    /**
     * @ORM\Column(name="clase_riesgo_afiliado", type="string", length=20, nullable=true)
     */
    private $claseRiesgoAfiliado;

    /**
     * @ORM\Column(name="total_cotizacion", type="float", nullable=true)
     */
    private $totalCotizacion;

    /**
     * @ORM\Column(name="codigo_entidad_pension_fk", type="integer", nullable=true)
     */
    private $codigoEntidadPensionFk;

    /**
     * @ORM\Column(name="codigo_entidad_salud_fk", type="integer", nullable=true)
     */
    private $codigoEntidadSaludFk;

    /**
     * @ORM\Column(name="codigo_entidad_riesgo_fk", type="integer", nullable=true)
     */
    private $codigoEntidadRiesgoFk;

    /**
     * @ORM\Column(name="codigo_entidad_caja_fk", type="integer", nullable=true)
     */
    private $codigoEntidadCajaFk;

    /**
     * @ORM\Column(name="indicador_tarifa_especial_pensiones", type="string", length=1, nullable=true)
     */
    private $indicadorTarifaEspecialPensiones;

    /**
     * @ORM\Column(name="fecha_ingreso", type="string", length=10, nullable=true)
     */
    private $fechaIngreso;

    /**
     * @ORM\Column(name="fecha_retiro", type="string", length=10, nullable=true)
     */
    private $fechaRetiro;

    /**
     * @ORM\Column(name="fecha_inicio_vsp", type="string", length=10, nullable=true)
     */
    private $fechaInicioVsp;

    /**
     * @ORM\Column(name="fecha_inicio_sln", type="string", length=10, nullable=true)
     */
    private $fechaInicioSln;

    /**
     * @ORM\Column(name="fecha_fin_sln", type="string", length=10, nullable=true)
     */
    private $fechaFinSln;

    /**
     * @ORM\Column(name="fecha_inicio_ige", type="string", length=10, nullable=true)
     */
    private $fechaInicioIge;

    /**
     * @ORM\Column(name="fecha_fin_ige", type="string", length=10, nullable=true)
     */
    private $fechaFinIge;

    /**
     * @ORM\Column(name="fecha_inicio_lma", type="string", length=10, nullable=true)
     */
    private $fechaInicioLma;

    /**
     * @ORM\Column(name="fecha_fin_lma", type="string", length=10, nullable=true)
     */
    private $fechaFinLma;

    /**
     * @ORM\Column(name="fecha_inicio_vac_lr", type="string", length=10, nullable=true)
     */
    private $fechaInicioVacLr;

    /**
     * @ORM\Column(name="fecha_fin_vac_lr", type="string", length=10, nullable=true)
     */
    private $fechaFinVacLr;

    /**
     * @ORM\Column(name="fecha_inicio_vct", type="string", length=10, nullable=true)
     */
    private $fechaInicioVct;

    /**
     * @ORM\Column(name="fecha_fin_vct", type="string", length=10, nullable=true)
     */
    private $fechaFinVct;

    /**
     * @ORM\Column(name="fecha_inicio_irl", type="string", length=10, nullable=true)
     */
    private $fechaInicioIrl;

    /**
     * @ORM\Column(name="fecha_fin_irl", type="string", length=10, nullable=true)
     */
    private $fechaFinIrl;

    /**
     * @ORM\Column(name="ibc_otros_parafiscales_diferentes_ccf", type="float", nullable=true)
     */
    private $ibcOtrosParafiscalesDiferentesCcf = 0;

    /**
     * @ORM\Column(name="numero_horas_laboradas", type="float", nullable=true)
     */
    private $numeroHorasLaboradas = 0;

    /**
     * @ORM\ManyToOne(targetEntity="RhuAporte", inversedBy="aportesDetallesAporteRel")
     * @ORM\JoinColumn(name="codigo_aporte_fk",referencedColumnName="codigo_aporte_pk")
     */
    protected $aporteRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="aportesDetallesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk",referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="aportesDetallesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk",referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuSucursal", inversedBy="aportesDetallesSucursalRel")
     * @ORM\JoinColumn(name="codigo_sucursal_fk",referencedColumnName="codigo_sucursal_pk")
     */
    protected $sucursalRel;

    /**
     * @return mixed
     */
    public function getCodigoAporteDetallePk()
    {
        return $this->codigoAporteDetallePk;
    }

    /**
     * @param mixed $codigoAporteDetallePk
     */
    public function setCodigoAporteDetallePk($codigoAporteDetallePk): void
    {
        $this->codigoAporteDetallePk = $codigoAporteDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoAporteFk()
    {
        return $this->codigoAporteFk;
    }

    /**
     * @param mixed $codigoAporteFk
     */
    public function setCodigoAporteFk($codigoAporteFk): void
    {
        $this->codigoAporteFk = $codigoAporteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoContratoFk()
    {
        return $this->codigoContratoFk;
    }

    /**
     * @param mixed $codigoContratoFk
     */
    public function setCodigoContratoFk($codigoContratoFk): void
    {
        $this->codigoContratoFk = $codigoContratoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEmpleadoFk()
    {
        return $this->codigoEmpleadoFk;
    }

    /**
     * @param mixed $codigoEmpleadoFk
     */
    public function setCodigoEmpleadoFk($codigoEmpleadoFk): void
    {
        $this->codigoEmpleadoFk = $codigoEmpleadoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSucursalFk()
    {
        return $this->codigoSucursalFk;
    }

    /**
     * @param mixed $codigoSucursalFk
     */
    public function setCodigoSucursalFk($codigoSucursalFk): void
    {
        $this->codigoSucursalFk = $codigoSucursalFk;
    }

    /**
     * @return mixed
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * @param mixed $anio
     */
    public function setAnio($anio): void
    {
        $this->anio = $anio;
    }

    /**
     * @return mixed
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * @param mixed $mes
     */
    public function setMes($mes): void
    {
        $this->mes = $mes;
    }

    /**
     * @return mixed
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * @param mixed $fechaDesde
     */
    public function setFechaDesde($fechaDesde): void
    {
        $this->fechaDesde = $fechaDesde;
    }

    /**
     * @return mixed
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * @param mixed $fechaHasta
     */
    public function setFechaHasta($fechaHasta): void
    {
        $this->fechaHasta = $fechaHasta;
    }

    /**
     * @return mixed
     */
    public function getTipoRegistro()
    {
        return $this->tipoRegistro;
    }

    /**
     * @param mixed $tipoRegistro
     */
    public function setTipoRegistro($tipoRegistro): void
    {
        $this->tipoRegistro = $tipoRegistro;
    }

    /**
     * @return mixed
     */
    public function getSecuencia()
    {
        return $this->secuencia;
    }

    /**
     * @param mixed $secuencia
     */
    public function setSecuencia($secuencia): void
    {
        $this->secuencia = $secuencia;
    }

    /**
     * @return mixed
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * @param mixed $tipoDocumento
     */
    public function setTipoDocumento($tipoDocumento): void
    {
        $this->tipoDocumento = $tipoDocumento;
    }

    /**
     * @return mixed
     */
    public function getTipoCotizante()
    {
        return $this->tipoCotizante;
    }

    /**
     * @param mixed $tipoCotizante
     */
    public function setTipoCotizante($tipoCotizante): void
    {
        $this->tipoCotizante = $tipoCotizante;
    }

    /**
     * @return mixed
     */
    public function getSubtipoCotizante()
    {
        return $this->subtipoCotizante;
    }

    /**
     * @param mixed $subtipoCotizante
     */
    public function setSubtipoCotizante($subtipoCotizante): void
    {
        $this->subtipoCotizante = $subtipoCotizante;
    }

    /**
     * @return mixed
     */
    public function getExtranjeroNoObligadoCotizarPension()
    {
        return $this->extranjeroNoObligadoCotizarPension;
    }

    /**
     * @param mixed $extranjeroNoObligadoCotizarPension
     */
    public function setExtranjeroNoObligadoCotizarPension($extranjeroNoObligadoCotizarPension): void
    {
        $this->extranjeroNoObligadoCotizarPension = $extranjeroNoObligadoCotizarPension;
    }

    /**
     * @return mixed
     */
    public function getColombianoResidenteExterior()
    {
        return $this->colombianoResidenteExterior;
    }

    /**
     * @param mixed $colombianoResidenteExterior
     */
    public function setColombianoResidenteExterior($colombianoResidenteExterior): void
    {
        $this->colombianoResidenteExterior = $colombianoResidenteExterior;
    }

    /**
     * @return mixed
     */
    public function getCodigoDepartamentoUbicacionlaboral()
    {
        return $this->codigoDepartamentoUbicacionlaboral;
    }

    /**
     * @param mixed $codigoDepartamentoUbicacionlaboral
     */
    public function setCodigoDepartamentoUbicacionlaboral($codigoDepartamentoUbicacionlaboral): void
    {
        $this->codigoDepartamentoUbicacionlaboral = $codigoDepartamentoUbicacionlaboral;
    }

    /**
     * @return mixed
     */
    public function getCodigoMunicipioUbicacionlaboral()
    {
        return $this->codigoMunicipioUbicacionlaboral;
    }

    /**
     * @param mixed $codigoMunicipioUbicacionlaboral
     */
    public function setCodigoMunicipioUbicacionlaboral($codigoMunicipioUbicacionlaboral): void
    {
        $this->codigoMunicipioUbicacionlaboral = $codigoMunicipioUbicacionlaboral;
    }

    /**
     * @return mixed
     */
    public function getPrimerNombre()
    {
        return $this->primerNombre;
    }

    /**
     * @param mixed $primerNombre
     */
    public function setPrimerNombre($primerNombre): void
    {
        $this->primerNombre = $primerNombre;
    }

    /**
     * @return mixed
     */
    public function getSegundoNombre()
    {
        return $this->segundoNombre;
    }

    /**
     * @param mixed $segundoNombre
     */
    public function setSegundoNombre($segundoNombre): void
    {
        $this->segundoNombre = $segundoNombre;
    }

    /**
     * @return mixed
     */
    public function getPrimerApellido()
    {
        return $this->primerApellido;
    }

    /**
     * @param mixed $primerApellido
     */
    public function setPrimerApellido($primerApellido): void
    {
        $this->primerApellido = $primerApellido;
    }

    /**
     * @return mixed
     */
    public function getSegundoApellido()
    {
        return $this->segundoApellido;
    }

    /**
     * @param mixed $segundoApellido
     */
    public function setSegundoApellido($segundoApellido): void
    {
        $this->segundoApellido = $segundoApellido;
    }

    /**
     * @return mixed
     */
    public function getIngreso()
    {
        return $this->ingreso;
    }

    /**
     * @param mixed $ingreso
     */
    public function setIngreso($ingreso): void
    {
        $this->ingreso = $ingreso;
    }

    /**
     * @return mixed
     */
    public function getRetiro()
    {
        return $this->retiro;
    }

    /**
     * @param mixed $retiro
     */
    public function setRetiro($retiro): void
    {
        $this->retiro = $retiro;
    }

    /**
     * @return mixed
     */
    public function getTrasladoDesdeOtraEps()
    {
        return $this->trasladoDesdeOtraEps;
    }

    /**
     * @param mixed $trasladoDesdeOtraEps
     */
    public function setTrasladoDesdeOtraEps($trasladoDesdeOtraEps): void
    {
        $this->trasladoDesdeOtraEps = $trasladoDesdeOtraEps;
    }

    /**
     * @return mixed
     */
    public function getTrasladoAOtraEps()
    {
        return $this->trasladoAOtraEps;
    }

    /**
     * @param mixed $trasladoAOtraEps
     */
    public function setTrasladoAOtraEps($trasladoAOtraEps): void
    {
        $this->trasladoAOtraEps = $trasladoAOtraEps;
    }

    /**
     * @return mixed
     */
    public function getTrasladoDesdeOtraPension()
    {
        return $this->trasladoDesdeOtraPension;
    }

    /**
     * @param mixed $trasladoDesdeOtraPension
     */
    public function setTrasladoDesdeOtraPension($trasladoDesdeOtraPension): void
    {
        $this->trasladoDesdeOtraPension = $trasladoDesdeOtraPension;
    }

    /**
     * @return mixed
     */
    public function getTrasladoAOtraPension()
    {
        return $this->trasladoAOtraPension;
    }

    /**
     * @param mixed $trasladoAOtraPension
     */
    public function setTrasladoAOtraPension($trasladoAOtraPension): void
    {
        $this->trasladoAOtraPension = $trasladoAOtraPension;
    }

    /**
     * @return mixed
     */
    public function getVariacionPermanenteSalario()
    {
        return $this->variacionPermanenteSalario;
    }

    /**
     * @param mixed $variacionPermanenteSalario
     */
    public function setVariacionPermanenteSalario($variacionPermanenteSalario): void
    {
        $this->variacionPermanenteSalario = $variacionPermanenteSalario;
    }

    /**
     * @return mixed
     */
    public function getCorrecciones()
    {
        return $this->correcciones;
    }

    /**
     * @param mixed $correcciones
     */
    public function setCorrecciones($correcciones): void
    {
        $this->correcciones = $correcciones;
    }

    /**
     * @return mixed
     */
    public function getVariacionTransitoriaSalario()
    {
        return $this->variacionTransitoriaSalario;
    }

    /**
     * @param mixed $variacionTransitoriaSalario
     */
    public function setVariacionTransitoriaSalario($variacionTransitoriaSalario): void
    {
        $this->variacionTransitoriaSalario = $variacionTransitoriaSalario;
    }

    /**
     * @return mixed
     */
    public function getSuspensionTemporalContratoLicenciaServicios()
    {
        return $this->suspensionTemporalContratoLicenciaServicios;
    }

    /**
     * @param mixed $suspensionTemporalContratoLicenciaServicios
     */
    public function setSuspensionTemporalContratoLicenciaServicios($suspensionTemporalContratoLicenciaServicios): void
    {
        $this->suspensionTemporalContratoLicenciaServicios = $suspensionTemporalContratoLicenciaServicios;
    }

    /**
     * @return mixed
     */
    public function getDiasLicencia()
    {
        return $this->diasLicencia;
    }

    /**
     * @param mixed $diasLicencia
     */
    public function setDiasLicencia($diasLicencia): void
    {
        $this->diasLicencia = $diasLicencia;
    }

    /**
     * @return mixed
     */
    public function getIncapacidadGeneral()
    {
        return $this->incapacidadGeneral;
    }

    /**
     * @param mixed $incapacidadGeneral
     */
    public function setIncapacidadGeneral($incapacidadGeneral): void
    {
        $this->incapacidadGeneral = $incapacidadGeneral;
    }

    /**
     * @return mixed
     */
    public function getDiasIncapacidadGeneral()
    {
        return $this->diasIncapacidadGeneral;
    }

    /**
     * @param mixed $diasIncapacidadGeneral
     */
    public function setDiasIncapacidadGeneral($diasIncapacidadGeneral): void
    {
        $this->diasIncapacidadGeneral = $diasIncapacidadGeneral;
    }

    /**
     * @return mixed
     */
    public function getLicenciaMaternidad()
    {
        return $this->licenciaMaternidad;
    }

    /**
     * @param mixed $licenciaMaternidad
     */
    public function setLicenciaMaternidad($licenciaMaternidad): void
    {
        $this->licenciaMaternidad = $licenciaMaternidad;
    }

    /**
     * @return mixed
     */
    public function getDiasLicenciaMaternidad()
    {
        return $this->diasLicenciaMaternidad;
    }

    /**
     * @param mixed $diasLicenciaMaternidad
     */
    public function setDiasLicenciaMaternidad($diasLicenciaMaternidad): void
    {
        $this->diasLicenciaMaternidad = $diasLicenciaMaternidad;
    }

    /**
     * @return mixed
     */
    public function getVacaciones()
    {
        return $this->vacaciones;
    }

    /**
     * @param mixed $vacaciones
     */
    public function setVacaciones($vacaciones): void
    {
        $this->vacaciones = $vacaciones;
    }

    /**
     * @return mixed
     */
    public function getDiasVacaciones()
    {
        return $this->diasVacaciones;
    }

    /**
     * @param mixed $diasVacaciones
     */
    public function setDiasVacaciones($diasVacaciones): void
    {
        $this->diasVacaciones = $diasVacaciones;
    }

    /**
     * @return mixed
     */
    public function getAporteVoluntario()
    {
        return $this->aporteVoluntario;
    }

    /**
     * @param mixed $aporteVoluntario
     */
    public function setAporteVoluntario($aporteVoluntario): void
    {
        $this->aporteVoluntario = $aporteVoluntario;
    }

    /**
     * @return mixed
     */
    public function getVariacionCentrosTrabajo()
    {
        return $this->variacionCentrosTrabajo;
    }

    /**
     * @param mixed $variacionCentrosTrabajo
     */
    public function setVariacionCentrosTrabajo($variacionCentrosTrabajo): void
    {
        $this->variacionCentrosTrabajo = $variacionCentrosTrabajo;
    }

    /**
     * @return mixed
     */
    public function getIncapacidadAccidenteTrabajoEnfermedadProfesional()
    {
        return $this->incapacidadAccidenteTrabajoEnfermedadProfesional;
    }

    /**
     * @param mixed $incapacidadAccidenteTrabajoEnfermedadProfesional
     */
    public function setIncapacidadAccidenteTrabajoEnfermedadProfesional($incapacidadAccidenteTrabajoEnfermedadProfesional): void
    {
        $this->incapacidadAccidenteTrabajoEnfermedadProfesional = $incapacidadAccidenteTrabajoEnfermedadProfesional;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadPensionPertenece()
    {
        return $this->codigoEntidadPensionPertenece;
    }

    /**
     * @param mixed $codigoEntidadPensionPertenece
     */
    public function setCodigoEntidadPensionPertenece($codigoEntidadPensionPertenece): void
    {
        $this->codigoEntidadPensionPertenece = $codigoEntidadPensionPertenece;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadPensionTraslada()
    {
        return $this->codigoEntidadPensionTraslada;
    }

    /**
     * @param mixed $codigoEntidadPensionTraslada
     */
    public function setCodigoEntidadPensionTraslada($codigoEntidadPensionTraslada): void
    {
        $this->codigoEntidadPensionTraslada = $codigoEntidadPensionTraslada;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadSaludPertenece()
    {
        return $this->codigoEntidadSaludPertenece;
    }

    /**
     * @param mixed $codigoEntidadSaludPertenece
     */
    public function setCodigoEntidadSaludPertenece($codigoEntidadSaludPertenece): void
    {
        $this->codigoEntidadSaludPertenece = $codigoEntidadSaludPertenece;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadSaludTraslada()
    {
        return $this->codigoEntidadSaludTraslada;
    }

    /**
     * @param mixed $codigoEntidadSaludTraslada
     */
    public function setCodigoEntidadSaludTraslada($codigoEntidadSaludTraslada): void
    {
        $this->codigoEntidadSaludTraslada = $codigoEntidadSaludTraslada;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadCajaPertenece()
    {
        return $this->codigoEntidadCajaPertenece;
    }

    /**
     * @param mixed $codigoEntidadCajaPertenece
     */
    public function setCodigoEntidadCajaPertenece($codigoEntidadCajaPertenece): void
    {
        $this->codigoEntidadCajaPertenece = $codigoEntidadCajaPertenece;
    }

    /**
     * @return mixed
     */
    public function getDiasCotizadosPension()
    {
        return $this->diasCotizadosPension;
    }

    /**
     * @param mixed $diasCotizadosPension
     */
    public function setDiasCotizadosPension($diasCotizadosPension): void
    {
        $this->diasCotizadosPension = $diasCotizadosPension;
    }

    /**
     * @return mixed
     */
    public function getDiasCotizadosSalud()
    {
        return $this->diasCotizadosSalud;
    }

    /**
     * @param mixed $diasCotizadosSalud
     */
    public function setDiasCotizadosSalud($diasCotizadosSalud): void
    {
        $this->diasCotizadosSalud = $diasCotizadosSalud;
    }

    /**
     * @return mixed
     */
    public function getDiasCotizadosRiesgosProfesionales()
    {
        return $this->diasCotizadosRiesgosProfesionales;
    }

    /**
     * @param mixed $diasCotizadosRiesgosProfesionales
     */
    public function setDiasCotizadosRiesgosProfesionales($diasCotizadosRiesgosProfesionales): void
    {
        $this->diasCotizadosRiesgosProfesionales = $diasCotizadosRiesgosProfesionales;
    }

    /**
     * @return mixed
     */
    public function getDiasCotizadosCajaCompensacion()
    {
        return $this->diasCotizadosCajaCompensacion;
    }

    /**
     * @param mixed $diasCotizadosCajaCompensacion
     */
    public function setDiasCotizadosCajaCompensacion($diasCotizadosCajaCompensacion): void
    {
        $this->diasCotizadosCajaCompensacion = $diasCotizadosCajaCompensacion;
    }

    /**
     * @return mixed
     */
    public function getSalarioBasico()
    {
        return $this->salarioBasico;
    }

    /**
     * @param mixed $salarioBasico
     */
    public function setSalarioBasico($salarioBasico): void
    {
        $this->salarioBasico = $salarioBasico;
    }

    /**
     * @return mixed
     */
    public function getSalarioMesAnterior()
    {
        return $this->salarioMesAnterior;
    }

    /**
     * @param mixed $salarioMesAnterior
     */
    public function setSalarioMesAnterior($salarioMesAnterior): void
    {
        $this->salarioMesAnterior = $salarioMesAnterior;
    }

    /**
     * @return mixed
     */
    public function getVrVacaciones()
    {
        return $this->vrVacaciones;
    }

    /**
     * @param mixed $vrVacaciones
     */
    public function setVrVacaciones($vrVacaciones): void
    {
        $this->vrVacaciones = $vrVacaciones;
    }

    /**
     * @return mixed
     */
    public function getSalarioIntegral()
    {
        return $this->salarioIntegral;
    }

    /**
     * @param mixed $salarioIntegral
     */
    public function setSalarioIntegral($salarioIntegral): void
    {
        $this->salarioIntegral = $salarioIntegral;
    }

    /**
     * @return mixed
     */
    public function getSuplementario()
    {
        return $this->suplementario;
    }

    /**
     * @param mixed $suplementario
     */
    public function setSuplementario($suplementario): void
    {
        $this->suplementario = $suplementario;
    }

    /**
     * @return mixed
     */
    public function getVrIngresoBaseCotizacion()
    {
        return $this->vrIngresoBaseCotizacion;
    }

    /**
     * @param mixed $vrIngresoBaseCotizacion
     */
    public function setVrIngresoBaseCotizacion($vrIngresoBaseCotizacion): void
    {
        $this->vrIngresoBaseCotizacion = $vrIngresoBaseCotizacion;
    }

    /**
     * @return mixed
     */
    public function getIbcPension()
    {
        return $this->ibcPension;
    }

    /**
     * @param mixed $ibcPension
     */
    public function setIbcPension($ibcPension): void
    {
        $this->ibcPension = $ibcPension;
    }

    /**
     * @return mixed
     */
    public function getIbcSalud()
    {
        return $this->ibcSalud;
    }

    /**
     * @param mixed $ibcSalud
     */
    public function setIbcSalud($ibcSalud): void
    {
        $this->ibcSalud = $ibcSalud;
    }

    /**
     * @return mixed
     */
    public function getIbcRiesgosProfesionales()
    {
        return $this->ibcRiesgosProfesionales;
    }

    /**
     * @param mixed $ibcRiesgosProfesionales
     */
    public function setIbcRiesgosProfesionales($ibcRiesgosProfesionales): void
    {
        $this->ibcRiesgosProfesionales = $ibcRiesgosProfesionales;
    }

    /**
     * @return mixed
     */
    public function getIbcCaja()
    {
        return $this->ibcCaja;
    }

    /**
     * @param mixed $ibcCaja
     */
    public function setIbcCaja($ibcCaja): void
    {
        $this->ibcCaja = $ibcCaja;
    }

    /**
     * @return mixed
     */
    public function getTarifaPension()
    {
        return $this->tarifaPension;
    }

    /**
     * @param mixed $tarifaPension
     */
    public function setTarifaPension($tarifaPension): void
    {
        $this->tarifaPension = $tarifaPension;
    }

    /**
     * @return mixed
     */
    public function getTarifaSalud()
    {
        return $this->tarifaSalud;
    }

    /**
     * @param mixed $tarifaSalud
     */
    public function setTarifaSalud($tarifaSalud): void
    {
        $this->tarifaSalud = $tarifaSalud;
    }

    /**
     * @return mixed
     */
    public function getTarifaRiesgos()
    {
        return $this->tarifaRiesgos;
    }

    /**
     * @param mixed $tarifaRiesgos
     */
    public function setTarifaRiesgos($tarifaRiesgos): void
    {
        $this->tarifaRiesgos = $tarifaRiesgos;
    }

    /**
     * @return mixed
     */
    public function getTarifaCaja()
    {
        return $this->tarifaCaja;
    }

    /**
     * @param mixed $tarifaCaja
     */
    public function setTarifaCaja($tarifaCaja): void
    {
        $this->tarifaCaja = $tarifaCaja;
    }

    /**
     * @return mixed
     */
    public function getTarifaSena()
    {
        return $this->tarifaSena;
    }

    /**
     * @param mixed $tarifaSena
     */
    public function setTarifaSena($tarifaSena): void
    {
        $this->tarifaSena = $tarifaSena;
    }

    /**
     * @return mixed
     */
    public function getTarifaIcbf()
    {
        return $this->tarifaIcbf;
    }

    /**
     * @param mixed $tarifaIcbf
     */
    public function setTarifaIcbf($tarifaIcbf): void
    {
        $this->tarifaIcbf = $tarifaIcbf;
    }

    /**
     * @return mixed
     */
    public function getCotizacionPension()
    {
        return $this->cotizacionPension;
    }

    /**
     * @param mixed $cotizacionPension
     */
    public function setCotizacionPension($cotizacionPension): void
    {
        $this->cotizacionPension = $cotizacionPension;
    }

    /**
     * @return mixed
     */
    public function getCotizacionSalud()
    {
        return $this->cotizacionSalud;
    }

    /**
     * @param mixed $cotizacionSalud
     */
    public function setCotizacionSalud($cotizacionSalud): void
    {
        $this->cotizacionSalud = $cotizacionSalud;
    }

    /**
     * @return mixed
     */
    public function getCotizacionRiesgos()
    {
        return $this->cotizacionRiesgos;
    }

    /**
     * @param mixed $cotizacionRiesgos
     */
    public function setCotizacionRiesgos($cotizacionRiesgos): void
    {
        $this->cotizacionRiesgos = $cotizacionRiesgos;
    }

    /**
     * @return mixed
     */
    public function getCotizacionCaja()
    {
        return $this->cotizacionCaja;
    }

    /**
     * @param mixed $cotizacionCaja
     */
    public function setCotizacionCaja($cotizacionCaja): void
    {
        $this->cotizacionCaja = $cotizacionCaja;
    }

    /**
     * @return mixed
     */
    public function getCotizacionSena()
    {
        return $this->cotizacionSena;
    }

    /**
     * @param mixed $cotizacionSena
     */
    public function setCotizacionSena($cotizacionSena): void
    {
        $this->cotizacionSena = $cotizacionSena;
    }

    /**
     * @return mixed
     */
    public function getCotizacionIcbf()
    {
        return $this->cotizacionIcbf;
    }

    /**
     * @param mixed $cotizacionIcbf
     */
    public function setCotizacionIcbf($cotizacionIcbf): void
    {
        $this->cotizacionIcbf = $cotizacionIcbf;
    }

    /**
     * @return mixed
     */
    public function getAporteVoluntarioFondoPensionesObligatorias()
    {
        return $this->aporteVoluntarioFondoPensionesObligatorias;
    }

    /**
     * @param mixed $aporteVoluntarioFondoPensionesObligatorias
     */
    public function setAporteVoluntarioFondoPensionesObligatorias($aporteVoluntarioFondoPensionesObligatorias): void
    {
        $this->aporteVoluntarioFondoPensionesObligatorias = $aporteVoluntarioFondoPensionesObligatorias;
    }

    /**
     * @return mixed
     */
    public function getCotizacionVoluntarioFondoPensionesObligatorias()
    {
        return $this->cotizacionVoluntarioFondoPensionesObligatorias;
    }

    /**
     * @param mixed $cotizacionVoluntarioFondoPensionesObligatorias
     */
    public function setCotizacionVoluntarioFondoPensionesObligatorias($cotizacionVoluntarioFondoPensionesObligatorias): void
    {
        $this->cotizacionVoluntarioFondoPensionesObligatorias = $cotizacionVoluntarioFondoPensionesObligatorias;
    }

    /**
     * @return mixed
     */
    public function getTotalCotizacionFondos()
    {
        return $this->totalCotizacionFondos;
    }

    /**
     * @param mixed $totalCotizacionFondos
     */
    public function setTotalCotizacionFondos($totalCotizacionFondos): void
    {
        $this->totalCotizacionFondos = $totalCotizacionFondos;
    }

    /**
     * @return mixed
     */
    public function getAportesFondoSolidaridadPensionalSolidaridad()
    {
        return $this->aportesFondoSolidaridadPensionalSolidaridad;
    }

    /**
     * @param mixed $aportesFondoSolidaridadPensionalSolidaridad
     */
    public function setAportesFondoSolidaridadPensionalSolidaridad($aportesFondoSolidaridadPensionalSolidaridad): void
    {
        $this->aportesFondoSolidaridadPensionalSolidaridad = $aportesFondoSolidaridadPensionalSolidaridad;
    }

    /**
     * @return mixed
     */
    public function getAportesFondoSolidaridadPensionalSubsistencia()
    {
        return $this->aportesFondoSolidaridadPensionalSubsistencia;
    }

    /**
     * @param mixed $aportesFondoSolidaridadPensionalSubsistencia
     */
    public function setAportesFondoSolidaridadPensionalSubsistencia($aportesFondoSolidaridadPensionalSubsistencia): void
    {
        $this->aportesFondoSolidaridadPensionalSubsistencia = $aportesFondoSolidaridadPensionalSubsistencia;
    }

    /**
     * @return mixed
     */
    public function getValorUpcAdicional()
    {
        return $this->valorUpcAdicional;
    }

    /**
     * @param mixed $valorUpcAdicional
     */
    public function setValorUpcAdicional($valorUpcAdicional): void
    {
        $this->valorUpcAdicional = $valorUpcAdicional;
    }

    /**
     * @return mixed
     */
    public function getNumeroAutorizacionIncapacidadEnfermedadGeneral()
    {
        return $this->numeroAutorizacionIncapacidadEnfermedadGeneral;
    }

    /**
     * @param mixed $numeroAutorizacionIncapacidadEnfermedadGeneral
     */
    public function setNumeroAutorizacionIncapacidadEnfermedadGeneral($numeroAutorizacionIncapacidadEnfermedadGeneral): void
    {
        $this->numeroAutorizacionIncapacidadEnfermedadGeneral = $numeroAutorizacionIncapacidadEnfermedadGeneral;
    }

    /**
     * @return mixed
     */
    public function getValorIncapacidadEnfermedadGeneral()
    {
        return $this->valorIncapacidadEnfermedadGeneral;
    }

    /**
     * @param mixed $valorIncapacidadEnfermedadGeneral
     */
    public function setValorIncapacidadEnfermedadGeneral($valorIncapacidadEnfermedadGeneral): void
    {
        $this->valorIncapacidadEnfermedadGeneral = $valorIncapacidadEnfermedadGeneral;
    }

    /**
     * @return mixed
     */
    public function getNumeroAutorizacionLicenciaMaternidadPaternidad()
    {
        return $this->numeroAutorizacionLicenciaMaternidadPaternidad;
    }

    /**
     * @param mixed $numeroAutorizacionLicenciaMaternidadPaternidad
     */
    public function setNumeroAutorizacionLicenciaMaternidadPaternidad($numeroAutorizacionLicenciaMaternidadPaternidad): void
    {
        $this->numeroAutorizacionLicenciaMaternidadPaternidad = $numeroAutorizacionLicenciaMaternidadPaternidad;
    }

    /**
     * @return mixed
     */
    public function getValorIncapacidadLicenciaMaternidadPaternidad()
    {
        return $this->valorIncapacidadLicenciaMaternidadPaternidad;
    }

    /**
     * @param mixed $valorIncapacidadLicenciaMaternidadPaternidad
     */
    public function setValorIncapacidadLicenciaMaternidadPaternidad($valorIncapacidadLicenciaMaternidadPaternidad): void
    {
        $this->valorIncapacidadLicenciaMaternidadPaternidad = $valorIncapacidadLicenciaMaternidadPaternidad;
    }

    /**
     * @return mixed
     */
    public function getCentroTrabajoCodigoCt()
    {
        return $this->centroTrabajoCodigoCt;
    }

    /**
     * @param mixed $centroTrabajoCodigoCt
     */
    public function setCentroTrabajoCodigoCt($centroTrabajoCodigoCt): void
    {
        $this->centroTrabajoCodigoCt = $centroTrabajoCodigoCt;
    }

    /**
     * @return mixed
     */
    public function getCodigoCargoFk()
    {
        return $this->codigoCargoFk;
    }

    /**
     * @param mixed $codigoCargoFk
     */
    public function setCodigoCargoFk($codigoCargoFk): void
    {
        $this->codigoCargoFk = $codigoCargoFk;
    }

    /**
     * @return mixed
     */
    public function getTarifaAportesESAP()
    {
        return $this->tarifaAportesESAP;
    }

    /**
     * @param mixed $tarifaAportesESAP
     */
    public function setTarifaAportesESAP($tarifaAportesESAP): void
    {
        $this->tarifaAportesESAP = $tarifaAportesESAP;
    }

    /**
     * @return mixed
     */
    public function getValorAportesESAP()
    {
        return $this->valorAportesESAP;
    }

    /**
     * @param mixed $valorAportesESAP
     */
    public function setValorAportesESAP($valorAportesESAP): void
    {
        $this->valorAportesESAP = $valorAportesESAP;
    }

    /**
     * @return mixed
     */
    public function getTarifaAportesMEN()
    {
        return $this->tarifaAportesMEN;
    }

    /**
     * @param mixed $tarifaAportesMEN
     */
    public function setTarifaAportesMEN($tarifaAportesMEN): void
    {
        $this->tarifaAportesMEN = $tarifaAportesMEN;
    }

    /**
     * @return mixed
     */
    public function getValorAportesMEN()
    {
        return $this->valorAportesMEN;
    }

    /**
     * @param mixed $valorAportesMEN
     */
    public function setValorAportesMEN($valorAportesMEN): void
    {
        $this->valorAportesMEN = $valorAportesMEN;
    }

    /**
     * @return mixed
     */
    public function getTipoDocumentoResponsableUPC()
    {
        return $this->tipoDocumentoResponsableUPC;
    }

    /**
     * @param mixed $tipoDocumentoResponsableUPC
     */
    public function setTipoDocumentoResponsableUPC($tipoDocumentoResponsableUPC): void
    {
        $this->tipoDocumentoResponsableUPC = $tipoDocumentoResponsableUPC;
    }

    /**
     * @return mixed
     */
    public function getNumeroIdentificacionResponsableUPCAdicional()
    {
        return $this->numeroIdentificacionResponsableUPCAdicional;
    }

    /**
     * @param mixed $numeroIdentificacionResponsableUPCAdicional
     */
    public function setNumeroIdentificacionResponsableUPCAdicional($numeroIdentificacionResponsableUPCAdicional): void
    {
        $this->numeroIdentificacionResponsableUPCAdicional = $numeroIdentificacionResponsableUPCAdicional;
    }

    /**
     * @return mixed
     */
    public function getCotizanteExoneradoPagoAporteParafiscalesSalud()
    {
        return $this->cotizanteExoneradoPagoAporteParafiscalesSalud;
    }

    /**
     * @param mixed $cotizanteExoneradoPagoAporteParafiscalesSalud
     */
    public function setCotizanteExoneradoPagoAporteParafiscalesSalud($cotizanteExoneradoPagoAporteParafiscalesSalud): void
    {
        $this->cotizanteExoneradoPagoAporteParafiscalesSalud = $cotizanteExoneradoPagoAporteParafiscalesSalud;
    }

    /**
     * @return mixed
     */
    public function getCodigoAdministradoraRiesgosLaborales()
    {
        return $this->codigoAdministradoraRiesgosLaborales;
    }

    /**
     * @param mixed $codigoAdministradoraRiesgosLaborales
     */
    public function setCodigoAdministradoraRiesgosLaborales($codigoAdministradoraRiesgosLaborales): void
    {
        $this->codigoAdministradoraRiesgosLaborales = $codigoAdministradoraRiesgosLaborales;
    }

    /**
     * @return mixed
     */
    public function getClaseRiesgoAfiliado()
    {
        return $this->claseRiesgoAfiliado;
    }

    /**
     * @param mixed $claseRiesgoAfiliado
     */
    public function setClaseRiesgoAfiliado($claseRiesgoAfiliado): void
    {
        $this->claseRiesgoAfiliado = $claseRiesgoAfiliado;
    }

    /**
     * @return mixed
     */
    public function getTotalCotizacion()
    {
        return $this->totalCotizacion;
    }

    /**
     * @param mixed $totalCotizacion
     */
    public function setTotalCotizacion($totalCotizacion): void
    {
        $this->totalCotizacion = $totalCotizacion;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadPensionFk()
    {
        return $this->codigoEntidadPensionFk;
    }

    /**
     * @param mixed $codigoEntidadPensionFk
     */
    public function setCodigoEntidadPensionFk($codigoEntidadPensionFk): void
    {
        $this->codigoEntidadPensionFk = $codigoEntidadPensionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadSaludFk()
    {
        return $this->codigoEntidadSaludFk;
    }

    /**
     * @param mixed $codigoEntidadSaludFk
     */
    public function setCodigoEntidadSaludFk($codigoEntidadSaludFk): void
    {
        $this->codigoEntidadSaludFk = $codigoEntidadSaludFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadRiesgoFk()
    {
        return $this->codigoEntidadRiesgoFk;
    }

    /**
     * @param mixed $codigoEntidadRiesgoFk
     */
    public function setCodigoEntidadRiesgoFk($codigoEntidadRiesgoFk): void
    {
        $this->codigoEntidadRiesgoFk = $codigoEntidadRiesgoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadCajaFk()
    {
        return $this->codigoEntidadCajaFk;
    }

    /**
     * @param mixed $codigoEntidadCajaFk
     */
    public function setCodigoEntidadCajaFk($codigoEntidadCajaFk): void
    {
        $this->codigoEntidadCajaFk = $codigoEntidadCajaFk;
    }

    /**
     * @return mixed
     */
    public function getIndicadorTarifaEspecialPensiones()
    {
        return $this->indicadorTarifaEspecialPensiones;
    }

    /**
     * @param mixed $indicadorTarifaEspecialPensiones
     */
    public function setIndicadorTarifaEspecialPensiones($indicadorTarifaEspecialPensiones): void
    {
        $this->indicadorTarifaEspecialPensiones = $indicadorTarifaEspecialPensiones;
    }

    /**
     * @return mixed
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * @param mixed $fechaIngreso
     */
    public function setFechaIngreso($fechaIngreso): void
    {
        $this->fechaIngreso = $fechaIngreso;
    }

    /**
     * @return mixed
     */
    public function getFechaRetiro()
    {
        return $this->fechaRetiro;
    }

    /**
     * @param mixed $fechaRetiro
     */
    public function setFechaRetiro($fechaRetiro): void
    {
        $this->fechaRetiro = $fechaRetiro;
    }

    /**
     * @return mixed
     */
    public function getFechaInicioVsp()
    {
        return $this->fechaInicioVsp;
    }

    /**
     * @param mixed $fechaInicioVsp
     */
    public function setFechaInicioVsp($fechaInicioVsp): void
    {
        $this->fechaInicioVsp = $fechaInicioVsp;
    }

    /**
     * @return mixed
     */
    public function getFechaInicioSln()
    {
        return $this->fechaInicioSln;
    }

    /**
     * @param mixed $fechaInicioSln
     */
    public function setFechaInicioSln($fechaInicioSln): void
    {
        $this->fechaInicioSln = $fechaInicioSln;
    }

    /**
     * @return mixed
     */
    public function getFechaFinSln()
    {
        return $this->fechaFinSln;
    }

    /**
     * @param mixed $fechaFinSln
     */
    public function setFechaFinSln($fechaFinSln): void
    {
        $this->fechaFinSln = $fechaFinSln;
    }

    /**
     * @return mixed
     */
    public function getFechaInicioIge()
    {
        return $this->fechaInicioIge;
    }

    /**
     * @param mixed $fechaInicioIge
     */
    public function setFechaInicioIge($fechaInicioIge): void
    {
        $this->fechaInicioIge = $fechaInicioIge;
    }

    /**
     * @return mixed
     */
    public function getFechaFinIge()
    {
        return $this->fechaFinIge;
    }

    /**
     * @param mixed $fechaFinIge
     */
    public function setFechaFinIge($fechaFinIge): void
    {
        $this->fechaFinIge = $fechaFinIge;
    }

    /**
     * @return mixed
     */
    public function getFechaInicioLma()
    {
        return $this->fechaInicioLma;
    }

    /**
     * @param mixed $fechaInicioLma
     */
    public function setFechaInicioLma($fechaInicioLma): void
    {
        $this->fechaInicioLma = $fechaInicioLma;
    }

    /**
     * @return mixed
     */
    public function getFechaFinLma()
    {
        return $this->fechaFinLma;
    }

    /**
     * @param mixed $fechaFinLma
     */
    public function setFechaFinLma($fechaFinLma): void
    {
        $this->fechaFinLma = $fechaFinLma;
    }

    /**
     * @return mixed
     */
    public function getFechaInicioVacLr()
    {
        return $this->fechaInicioVacLr;
    }

    /**
     * @param mixed $fechaInicioVacLr
     */
    public function setFechaInicioVacLr($fechaInicioVacLr): void
    {
        $this->fechaInicioVacLr = $fechaInicioVacLr;
    }

    /**
     * @return mixed
     */
    public function getFechaFinVacLr()
    {
        return $this->fechaFinVacLr;
    }

    /**
     * @param mixed $fechaFinVacLr
     */
    public function setFechaFinVacLr($fechaFinVacLr): void
    {
        $this->fechaFinVacLr = $fechaFinVacLr;
    }

    /**
     * @return mixed
     */
    public function getFechaInicioVct()
    {
        return $this->fechaInicioVct;
    }

    /**
     * @param mixed $fechaInicioVct
     */
    public function setFechaInicioVct($fechaInicioVct): void
    {
        $this->fechaInicioVct = $fechaInicioVct;
    }

    /**
     * @return mixed
     */
    public function getFechaFinVct()
    {
        return $this->fechaFinVct;
    }

    /**
     * @param mixed $fechaFinVct
     */
    public function setFechaFinVct($fechaFinVct): void
    {
        $this->fechaFinVct = $fechaFinVct;
    }

    /**
     * @return mixed
     */
    public function getFechaInicioIrl()
    {
        return $this->fechaInicioIrl;
    }

    /**
     * @param mixed $fechaInicioIrl
     */
    public function setFechaInicioIrl($fechaInicioIrl): void
    {
        $this->fechaInicioIrl = $fechaInicioIrl;
    }

    /**
     * @return mixed
     */
    public function getFechaFinIrl()
    {
        return $this->fechaFinIrl;
    }

    /**
     * @param mixed $fechaFinIrl
     */
    public function setFechaFinIrl($fechaFinIrl): void
    {
        $this->fechaFinIrl = $fechaFinIrl;
    }

    /**
     * @return mixed
     */
    public function getIbcOtrosParafiscalesDiferentesCcf()
    {
        return $this->ibcOtrosParafiscalesDiferentesCcf;
    }

    /**
     * @param mixed $ibcOtrosParafiscalesDiferentesCcf
     */
    public function setIbcOtrosParafiscalesDiferentesCcf($ibcOtrosParafiscalesDiferentesCcf): void
    {
        $this->ibcOtrosParafiscalesDiferentesCcf = $ibcOtrosParafiscalesDiferentesCcf;
    }

    /**
     * @return mixed
     */
    public function getNumeroHorasLaboradas()
    {
        return $this->numeroHorasLaboradas;
    }

    /**
     * @param mixed $numeroHorasLaboradas
     */
    public function setNumeroHorasLaboradas($numeroHorasLaboradas): void
    {
        $this->numeroHorasLaboradas = $numeroHorasLaboradas;
    }

    /**
     * @return mixed
     */
    public function getAporteRel()
    {
        return $this->aporteRel;
    }

    /**
     * @param mixed $aporteRel
     */
    public function setAporteRel($aporteRel): void
    {
        $this->aporteRel = $aporteRel;
    }

    /**
     * @return mixed
     */
    public function getContratoRel()
    {
        return $this->contratoRel;
    }

    /**
     * @param mixed $contratoRel
     */
    public function setContratoRel($contratoRel): void
    {
        $this->contratoRel = $contratoRel;
    }

    /**
     * @return mixed
     */
    public function getEmpleadoRel()
    {
        return $this->empleadoRel;
    }

    /**
     * @param mixed $empleadoRel
     */
    public function setEmpleadoRel($empleadoRel): void
    {
        $this->empleadoRel = $empleadoRel;
    }

    /**
     * @return mixed
     */
    public function getSucursalRel()
    {
        return $this->sucursalRel;
    }

    /**
     * @param mixed $sucursalRel
     */
    public function setSucursalRel($sucursalRel): void
    {
        $this->sucursalRel = $sucursalRel;
    }



}
