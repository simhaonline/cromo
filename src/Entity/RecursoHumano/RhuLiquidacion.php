<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuLiquidacionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuLiquidacion
{
    public $infoLog = [
        "primaryKey" => "codigoLiquidacionPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_liquidacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLiquidacionPk;

    /**
     * @ORM\Column(name="codigo_liquidacion_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoLiquidacionTipoFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="numero", options={"default" : 0}, type="integer", nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="codigo_pago_cesantia_interes_anterior_fk", type="integer", nullable=true)
     */
    private $codigoPagoCesantiaInteresAnteriorFk;

    /**
     * @ORM\Column(name="codigo_pago_cesantia_anterior_fk", type="integer", nullable=true)
     */
    private $codigoPagoCesantiaAnteriorFk;

    /**
     * @ORM\Column(name="codigo_grupo_fk", type="string", length=10, nullable=true)
     */
    private $codigoGrupoFk;

    /**
     * @ORM\Column(name="codigo_contrato_motivo_fk", type="string", length=10, nullable=true)
     */
    private $codigoContratoMotivoFk;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="fecha_inicio_contrato", type="date", nullable=true)
     */
    private $fechaInicioContrato;

    /**
     * @ORM\Column(name="numero_dias", type="string", length=30, nullable=true)
     */
    private $numeroDias;

    /**
     * @ORM\Column(name="vr_cesantias", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrCesantias = 0;

    /**
     * @ORM\Column(name="vr_intereses_cesantias", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrInteresesCesantias = 0;

    /**
     * @ORM\Column(name="vr_prima", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrPrima = 0;

    /**
     * @ORM\Column(name="vr_vacacion", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrVacacion = 0;

    /**
     * @ORM\Column(name="vr_indemnizacion", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrIndemnizacion = 0;

    /**
     * @ORM\Column(name="vr_deducciones", type="float")
     */
    private $VrDeducciones = 0;

    /**
     * @ORM\Column(name="vr_bonificaciones", type="float")
     */
    private $VrBonificaciones = 0;

    /**
     * @ORM\Column(name="vr_auxilio_transporte", type="float")
     */
    private $VrAuxilioTransporte = 0;

    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $VrSalario = 0;

    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $VrTotal = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion_cesantias", type="float")
     */
    private $VrIngresoBasePrestacionCesantias = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion_cesantias_inicial", type="float")
     */
    private $VrIngresoBasePrestacionCesantiasInicial = 0;

    /**
     * @ORM\Column(name="vr_salario_promedio_cesantias", type="float")
     */
    private $VrSalarioPromedioCesantias = 0;

    /**
     * @ORM\Column(name="vr_salario_promedio_cesantias_anterior", type="float")
     */
    private $VrSalarioPromedioCesantiasAnterior = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion_primas", type="float")
     */
    private $VrIngresoBasePrestacionPrimas = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion_primas_inicial", type="float")
     */
    private $VrIngresoBasePrestacionPrimasInicial = 0;

    /**
     * @ORM\Column(name="vr_salario_promedio_primas", type="float")
     */
    private $VrSalarioPromedioPrimas = 0;

    /**
     * @ORM\Column(name="vr_salario_vacacion_propuesto", type="float")
     */
    private $VrSalarioVacacionPropuesto = 0;

    /**
     * @ORM\Column(name="dias_cesantias", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasCesantias = 0;

    /**
     * @ORM\Column(name="dias_cesantias_ausentismo", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasCesantiasAusentismo = 0;

    /**
     * @ORM\Column(name="dias_vacacion", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasVacacion = 0;

    /**
     * @ORM\Column(name="dias_vacacion_ausentismo", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasVacacionAusentismo = 0;

    /**
     * @ORM\Column(name="dias_prima", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasPrima = 0;

    /**
     * @ORM\Column(name="dias_prima_ausentismo", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasPrimaAusentismo = 0;

    /**
     * @ORM\Column(name="dias_cesantias_anterior", type="integer")
     */
    private $diasCesantiasAnterior = 0;

    /**
     * @ORM\Column(name="dias_ausentismo_propuesto_primas", type="integer", nullable=true)
     */
    private $diasAusentismoPropuestoPrimas = 0;

    /**
     * @ORM\Column(name="dias_ausentismo_adicional", type="integer")
     */
    private $diasAusentismoAdicional = 0;

    /**
     * @ORM\Column(name="vr_cesantias_anterior", type="float")
     */
    private $VrCesantiasAnterior = 0;

    /**
     * @ORM\Column(name="vr_intereses_cesantias_anterior", type="float")
     */
    private $VrInteresesCesantiasAnterior = 0;

    /**
     * @ORM\Column(name="dias_cesantias_ausentismo_anterior", type="integer")
     */
    private $diasCesantiasAusentismo_anterior = 0;

    /**
     * @ORM\Column(name="dias_ausentismo_propuesto_cesantias", type="integer", nullable=true)
     */
    private $diasAusentismoPropuestoCesantias = 0;

    /**
     * @ORM\Column(name="porcentaje_intereses_cesantias", type="float", nullable=true)
     */
    private $porcentajeInteresesCesantias = 0;

    /**
     * @ORM\Column(name="vr_intereses_propuesto", type="integer", nullable=true)
     */
    private $vrInteresesPropuesto = 0;

    /**
     * @ORM\Column(name="vr_deduccion_prima", type="float")
     */
    private $VrDeduccionPrima = 0;

    /**
     * @ORM\Column(name="vr_deduccion_prima_propuesto", type="float",nullable=true)
     */
    private $VrDeduccionPrimaPropuesto = 0;

    /**
     * @ORM\Column(name="dias_deduccion_primas_", type="integer",nullable=true)
     */
    private $diasDeduccionPrimas = 0;

    /**
     * @ORM\Column(name="estado_indemnizacion", type="boolean", nullable = true)
     */
    private $estadoIndemnizacion = 0;

    /**
     * @ORM\Column(name="porcentaje_ibp", type="float")
     */
    private $porcentajeIbp = 100;

    /**
     * @ORM\Column(name="dias_adicionales_ibp", type="integer")
     */
    private $diasAdicionalesIBP = 0;

    /**
     * @ORM\Column(name="vr_indemnizacion_propuesto", type="float", nullable = true)
     */
    private $VrIndemnizacionPropuesto = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion_adicional", type="float")
     */
    private $VrIngresoBasePrestacionAdicional = 0;

    /**
     * @ORM\Column(name="vr_salario_cesantias_propuesto", type="float")
     */
    private $VrSalarioCesantiasPropuesto = 0;

    /**
     * @ORM\Column(name="vr_salario_prima_propuesto", type="float")
     */
    private $VrSalarioPrimaPropuesto = 0;

    /**
     * @ORM\Column(name="dias_ausentismo_propuesto_vacaciones", type="integer", nullable=true)
     */
    private $diasAusentismoPropuestoVacaciones = 0;

    /**
     * @ORM\Column(name="liquidar_cesantias", type="boolean")
     */
    private $liquidarCesantias = false;

    /**
     * @ORM\Column(name="liquidar_vacaciones", type="boolean")
     */
    private $liquidarVacaciones = false;

    /**
     * @ORM\Column(name="liquidar_prima", type="boolean")
     */
    private $liquidarPrima = false;

    /**
     * @ORM\Column(name="liquidar_salario", type="boolean")
     */
    private $liquidarSalario = false;

    /**
     * @ORM\Column(name="liquidar_manual", type="boolean")
     */
    private $liquidarManual = 0;

    /**
     * @ORM\Column(name="vr_salario_vacaciones", type="float")
     */
    private $VrSalarioVacaciones = 0;

    /**
     * @ORM\Column(name="fecha_ultimo_pago", type="date", nullable=true)
     */
    private $fechaUltimoPago;

    /**
     * @ORM\Column(name="fecha_ultimo_pago_prima", type="date", nullable=true)
     */
    private $fechaUltimoPagoPrima;

    /**
     * @ORM\Column(name="fecha_ultimo_pago_vacacion", type="date", nullable=true)
     */
    private $fechaUltimoPagoVacacion;

    /**
     * @ORM\Column(name="fecha_ultimo_pago_cesantias", type="date", nullable=true)
     */
    private $fechaUltimoPagoCesantias;

    /**
     * @ORM\Column(name="fecha_ultimo_pago_cesantias_anterior", type="date", nullable=true)
     */
    private $fechaUltimoPagoCesantiasAnterior;

    /**
     * @ORM\Column(name="eliminar_ausentismo", type="boolean")
     */
    private $eliminarAusentismo = false;

    /**
     * @ORM\Column(name="eliminar_ausentismo_cesantia", type="boolean", nullable=true)
     */
    private $eliminarAusentismoCesantia = false;

    /**
     * @ORM\Column(name="eliminar_ausentismo_primas", type="boolean", nullable=true)
     */
    private $eliminarAusentismoPrima = false;

    /**
     * @ORM\Column(name="eliminar_ausentismo_vacacion", type="boolean", nullable=true)
     */
    private $eliminarAusentismoVacacion = false;

    /**
     * @ORM\Column(name="estado_autorizado", options={"default" : false}, type="boolean", nullable=true)
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", options={"default" : false}, type="boolean", nullable=true)
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="estado_contabilizado", options={"default" : false}, type="boolean", nullable=true)
     */
    private $estadoContabilizado = false;

    /**
     * @ORM\Column(name="omitir_cesantias_anterior", type="boolean", nullable=true)
     */
    private $omitirCesantiasAnterior = false;

    /**
     * @ORM\Column(name="omitir_interes_cesantias_anterior", type="boolean", nullable=true)
     */
    private $omitirInteresCesantiasAnterior = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuLiquidacionTipo", inversedBy="liquidacionesLiquidacionTipoRel")
     * @ORM\JoinColumn(name="codigo_liquidacion_tipo_fk", referencedColumnName="codigo_liquidacion_tipo_pk")
     */
    protected $liquidacionTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="liquidacionesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="liquidacionesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuContratoMotivo", inversedBy="liquidacionesMotivoTerminacionContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_motivo_fk", referencedColumnName="codigo_contrato_motivo_pk")
     */
    protected $motivoTerminacionRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuLiquidacionAdicional", mappedBy="liquidacionRel")
     */
    protected $liquidacionesAdicionalesLiquidacionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuPago", mappedBy="liquidacionRel")
     */
    protected $pagosLiquidacionRel;

    /**
     * @return array
     */
    public function getInfoLog(): array
    {
        return $this->infoLog;
    }

    /**
     * @param array $infoLog
     */
    public function setInfoLog(array $infoLog): void
    {
        $this->infoLog = $infoLog;
    }

    /**
     * @return mixed
     */
    public function getCodigoLiquidacionPk()
    {
        return $this->codigoLiquidacionPk;
    }

    /**
     * @param mixed $codigoLiquidacionPk
     */
    public function setCodigoLiquidacionPk($codigoLiquidacionPk): void
    {
        $this->codigoLiquidacionPk = $codigoLiquidacionPk;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero): void
    {
        $this->numero = $numero;
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
    public function getCodigoGrupoFk()
    {
        return $this->codigoGrupoFk;
    }

    /**
     * @param mixed $codigoGrupoFk
     */
    public function setCodigoGrupoFk($codigoGrupoFk): void
    {
        $this->codigoGrupoFk = $codigoGrupoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoContratoMotivoFk()
    {
        return $this->codigoContratoMotivoFk;
    }

    /**
     * @param mixed $codigoContratoMotivoFk
     */
    public function setCodigoContratoMotivoFk($codigoContratoMotivoFk): void
    {
        $this->codigoContratoMotivoFk = $codigoContratoMotivoFk;
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
    public function getVrCesantias()
    {
        return $this->vrCesantias;
    }

    /**
     * @param mixed $vrCesantias
     */
    public function setVrCesantias($vrCesantias): void
    {
        $this->vrCesantias = $vrCesantias;
    }

    /**
     * @return mixed
     */
    public function getVrInteresesCesantias()
    {
        return $this->vrInteresesCesantias;
    }

    /**
     * @param mixed $vrInteresesCesantias
     */
    public function setVrInteresesCesantias($vrInteresesCesantias): void
    {
        $this->vrInteresesCesantias = $vrInteresesCesantias;
    }

    /**
     * @return mixed
     */
    public function getVrPrima()
    {
        return $this->vrPrima;
    }

    /**
     * @param mixed $vrPrima
     */
    public function setVrPrima($vrPrima): void
    {
        $this->vrPrima = $vrPrima;
    }

    /**
     * @return mixed
     */
    public function getVrVacacion()
    {
        return $this->vrVacacion;
    }

    /**
     * @param mixed $vrVacacion
     */
    public function setVrVacacion($vrVacacion): void
    {
        $this->vrVacacion = $vrVacacion;
    }

    /**
     * @return mixed
     */
    public function getVrIndemnizacion()
    {
        return $this->vrIndemnizacion;
    }

    /**
     * @param mixed $vrIndemnizacion
     */
    public function setVrIndemnizacion($vrIndemnizacion): void
    {
        $this->vrIndemnizacion = $vrIndemnizacion;
    }

    /**
     * @return mixed
     */
    public function getVrIngresoBasePrestacionCesantias()
    {
        return $this->VrIngresoBasePrestacionCesantias;
    }

    /**
     * @param mixed $VrIngresoBasePrestacionCesantias
     */
    public function setVrIngresoBasePrestacionCesantias($VrIngresoBasePrestacionCesantias): void
    {
        $this->VrIngresoBasePrestacionCesantias = $VrIngresoBasePrestacionCesantias;
    }

    /**
     * @return mixed
     */
    public function getVrIngresoBasePrestacionCesantiasInicial()
    {
        return $this->VrIngresoBasePrestacionCesantiasInicial;
    }

    /**
     * @param mixed $VrIngresoBasePrestacionCesantiasInicial
     */
    public function setVrIngresoBasePrestacionCesantiasInicial($VrIngresoBasePrestacionCesantiasInicial): void
    {
        $this->VrIngresoBasePrestacionCesantiasInicial = $VrIngresoBasePrestacionCesantiasInicial;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioPromedioCesantias()
    {
        return $this->VrSalarioPromedioCesantias;
    }

    /**
     * @param mixed $VrSalarioPromedioCesantias
     */
    public function setVrSalarioPromedioCesantias($VrSalarioPromedioCesantias): void
    {
        $this->VrSalarioPromedioCesantias = $VrSalarioPromedioCesantias;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioPromedioCesantiasAnterior()
    {
        return $this->VrSalarioPromedioCesantiasAnterior;
    }

    /**
     * @param mixed $VrSalarioPromedioCesantiasAnterior
     */
    public function setVrSalarioPromedioCesantiasAnterior($VrSalarioPromedioCesantiasAnterior): void
    {
        $this->VrSalarioPromedioCesantiasAnterior = $VrSalarioPromedioCesantiasAnterior;
    }

    /**
     * @return mixed
     */
    public function getVrIngresoBasePrestacionPrimas()
    {
        return $this->VrIngresoBasePrestacionPrimas;
    }

    /**
     * @param mixed $VrIngresoBasePrestacionPrimas
     */
    public function setVrIngresoBasePrestacionPrimas($VrIngresoBasePrestacionPrimas): void
    {
        $this->VrIngresoBasePrestacionPrimas = $VrIngresoBasePrestacionPrimas;
    }

    /**
     * @return mixed
     */
    public function getVrIngresoBasePrestacionPrimasInicial()
    {
        return $this->VrIngresoBasePrestacionPrimasInicial;
    }

    /**
     * @param mixed $VrIngresoBasePrestacionPrimasInicial
     */
    public function setVrIngresoBasePrestacionPrimasInicial($VrIngresoBasePrestacionPrimasInicial): void
    {
        $this->VrIngresoBasePrestacionPrimasInicial = $VrIngresoBasePrestacionPrimasInicial;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioPromedioPrimas()
    {
        return $this->VrSalarioPromedioPrimas;
    }

    /**
     * @param mixed $VrSalarioPromedioPrimas
     */
    public function setVrSalarioPromedioPrimas($VrSalarioPromedioPrimas): void
    {
        $this->VrSalarioPromedioPrimas = $VrSalarioPromedioPrimas;
    }

    /**
     * @return mixed
     */
    public function getDiasCesantias()
    {
        return $this->diasCesantias;
    }

    /**
     * @param mixed $diasCesantias
     */
    public function setDiasCesantias($diasCesantias): void
    {
        $this->diasCesantias = $diasCesantias;
    }

    /**
     * @return mixed
     */
    public function getDiasCesantiasAusentismo()
    {
        return $this->diasCesantiasAusentismo;
    }

    /**
     * @param mixed $diasCesantiasAusentismo
     */
    public function setDiasCesantiasAusentismo($diasCesantiasAusentismo): void
    {
        $this->diasCesantiasAusentismo = $diasCesantiasAusentismo;
    }

    /**
     * @return mixed
     */
    public function getDiasVacacion()
    {
        return $this->diasVacacion;
    }

    /**
     * @param mixed $diasVacacion
     */
    public function setDiasVacacion($diasVacacion): void
    {
        $this->diasVacacion = $diasVacacion;
    }

    /**
     * @return mixed
     */
    public function getDiasVacacionAusentismo()
    {
        return $this->diasVacacionAusentismo;
    }

    /**
     * @param mixed $diasVacacionAusentismo
     */
    public function setDiasVacacionAusentismo($diasVacacionAusentismo): void
    {
        $this->diasVacacionAusentismo = $diasVacacionAusentismo;
    }

    /**
     * @return mixed
     */
    public function getDiasPrima()
    {
        return $this->diasPrima;
    }

    /**
     * @param mixed $diasPrima
     */
    public function setDiasPrima($diasPrima): void
    {
        $this->diasPrima = $diasPrima;
    }

    /**
     * @return mixed
     */
    public function getDiasPrimaAusentismo()
    {
        return $this->diasPrimaAusentismo;
    }

    /**
     * @param mixed $diasPrimaAusentismo
     */
    public function setDiasPrimaAusentismo($diasPrimaAusentismo): void
    {
        $this->diasPrimaAusentismo = $diasPrimaAusentismo;
    }

    /**
     * @return mixed
     */
    public function getDiasCesantiasAnterior()
    {
        return $this->diasCesantiasAnterior;
    }

    /**
     * @param mixed $diasCesantiasAnterior
     */
    public function setDiasCesantiasAnterior($diasCesantiasAnterior): void
    {
        $this->diasCesantiasAnterior = $diasCesantiasAnterior;
    }

    /**
     * @return mixed
     */
    public function getDiasAusentismoPropuestoPrimas()
    {
        return $this->diasAusentismoPropuestoPrimas;
    }

    /**
     * @param mixed $diasAusentismoPropuestoPrimas
     */
    public function setDiasAusentismoPropuestoPrimas($diasAusentismoPropuestoPrimas): void
    {
        $this->diasAusentismoPropuestoPrimas = $diasAusentismoPropuestoPrimas;
    }

    /**
     * @return mixed
     */
    public function getDiasAusentismoAdicional()
    {
        return $this->diasAusentismoAdicional;
    }

    /**
     * @param mixed $diasAusentismoAdicional
     */
    public function setDiasAusentismoAdicional($diasAusentismoAdicional): void
    {
        $this->diasAusentismoAdicional = $diasAusentismoAdicional;
    }

    /**
     * @return mixed
     */
    public function getVrCesantiasAnterior()
    {
        return $this->VrCesantiasAnterior;
    }

    /**
     * @param mixed $VrCesantiasAnterior
     */
    public function setVrCesantiasAnterior($VrCesantiasAnterior): void
    {
        $this->VrCesantiasAnterior = $VrCesantiasAnterior;
    }

    /**
     * @return mixed
     */
    public function getVrInteresesCesantiasAnterior()
    {
        return $this->VrInteresesCesantiasAnterior;
    }

    /**
     * @param mixed $VrInteresesCesantiasAnterior
     */
    public function setVrInteresesCesantiasAnterior($VrInteresesCesantiasAnterior): void
    {
        $this->VrInteresesCesantiasAnterior = $VrInteresesCesantiasAnterior;
    }

    /**
     * @return mixed
     */
    public function getDiasCesantiasAusentismoAnterior()
    {
        return $this->diasCesantiasAusentismo_anterior;
    }

    /**
     * @param mixed $diasCesantiasAusentismo_anterior
     */
    public function setDiasCesantiasAusentismoAnterior($diasCesantiasAusentismo_anterior): void
    {
        $this->diasCesantiasAusentismo_anterior = $diasCesantiasAusentismo_anterior;
    }

    /**
     * @return mixed
     */
    public function getDiasAusentismoPropuestoCesantias()
    {
        return $this->diasAusentismoPropuestoCesantias;
    }

    /**
     * @param mixed $diasAusentismoPropuestoCesantias
     */
    public function setDiasAusentismoPropuestoCesantias($diasAusentismoPropuestoCesantias): void
    {
        $this->diasAusentismoPropuestoCesantias = $diasAusentismoPropuestoCesantias;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeInteresesCesantias()
    {
        return $this->porcentajeInteresesCesantias;
    }

    /**
     * @param mixed $porcentajeInteresesCesantias
     */
    public function setPorcentajeInteresesCesantias($porcentajeInteresesCesantias): void
    {
        $this->porcentajeInteresesCesantias = $porcentajeInteresesCesantias;
    }

    /**
     * @return mixed
     */
    public function getVrInteresesPropuesto()
    {
        return $this->vrInteresesPropuesto;
    }

    /**
     * @param mixed $vrInteresesPropuesto
     */
    public function setVrInteresesPropuesto($vrInteresesPropuesto): void
    {
        $this->vrInteresesPropuesto = $vrInteresesPropuesto;
    }

    /**
     * @return mixed
     */
    public function getVrDeduccionPrima()
    {
        return $this->VrDeduccionPrima;
    }

    /**
     * @param mixed $VrDeduccionPrima
     */
    public function setVrDeduccionPrima($VrDeduccionPrima): void
    {
        $this->VrDeduccionPrima = $VrDeduccionPrima;
    }

    /**
     * @return mixed
     */
    public function getVrDeduccionPrimaPropuesto()
    {
        return $this->VrDeduccionPrimaPropuesto;
    }

    /**
     * @param mixed $VrDeduccionPrimaPropuesto
     */
    public function setVrDeduccionPrimaPropuesto($VrDeduccionPrimaPropuesto): void
    {
        $this->VrDeduccionPrimaPropuesto = $VrDeduccionPrimaPropuesto;
    }

    /**
     * @return mixed
     */
    public function getEstadoIndemnizacion()
    {
        return $this->estadoIndemnizacion;
    }

    /**
     * @param mixed $estadoIndemnizacion
     */
    public function setEstadoIndemnizacion($estadoIndemnizacion): void
    {
        $this->estadoIndemnizacion = $estadoIndemnizacion;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeIbp()
    {
        return $this->porcentajeIbp;
    }

    /**
     * @param mixed $porcentajeIbp
     */
    public function setPorcentajeIbp($porcentajeIbp): void
    {
        $this->porcentajeIbp = $porcentajeIbp;
    }

    /**
     * @return mixed
     */
    public function getDiasAdicionalesIBP()
    {
        return $this->diasAdicionalesIBP;
    }

    /**
     * @param mixed $diasAdicionalesIBP
     */
    public function setDiasAdicionalesIBP($diasAdicionalesIBP): void
    {
        $this->diasAdicionalesIBP = $diasAdicionalesIBP;
    }

    /**
     * @return mixed
     */
    public function getVrIndemnizacionPropuesto()
    {
        return $this->VrIndemnizacionPropuesto;
    }

    /**
     * @param mixed $VrIndemnizacionPropuesto
     */
    public function setVrIndemnizacionPropuesto($VrIndemnizacionPropuesto): void
    {
        $this->VrIndemnizacionPropuesto = $VrIndemnizacionPropuesto;
    }

    /**
     * @return mixed
     */
    public function getVrIngresoBasePrestacionAdicional()
    {
        return $this->VrIngresoBasePrestacionAdicional;
    }

    /**
     * @param mixed $VrIngresoBasePrestacionAdicional
     */
    public function setVrIngresoBasePrestacionAdicional($VrIngresoBasePrestacionAdicional): void
    {
        $this->VrIngresoBasePrestacionAdicional = $VrIngresoBasePrestacionAdicional;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioCesantiasPropuesto()
    {
        return $this->VrSalarioCesantiasPropuesto;
    }

    /**
     * @param mixed $VrSalarioCesantiasPropuesto
     */
    public function setVrSalarioCesantiasPropuesto($VrSalarioCesantiasPropuesto): void
    {
        $this->VrSalarioCesantiasPropuesto = $VrSalarioCesantiasPropuesto;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioPrimaPropuesto()
    {
        return $this->VrSalarioPrimaPropuesto;
    }

    /**
     * @param mixed $VrSalarioPrimaPropuesto
     */
    public function setVrSalarioPrimaPropuesto($VrSalarioPrimaPropuesto): void
    {
        $this->VrSalarioPrimaPropuesto = $VrSalarioPrimaPropuesto;
    }

    /**
     * @return mixed
     */
    public function getLiquidarCesantias()
    {
        return $this->liquidarCesantias;
    }

    /**
     * @param mixed $liquidarCesantias
     */
    public function setLiquidarCesantias($liquidarCesantias): void
    {
        $this->liquidarCesantias = $liquidarCesantias;
    }

    /**
     * @return mixed
     */
    public function getLiquidarVacaciones()
    {
        return $this->liquidarVacaciones;
    }

    /**
     * @param mixed $liquidarVacaciones
     */
    public function setLiquidarVacaciones($liquidarVacaciones): void
    {
        $this->liquidarVacaciones = $liquidarVacaciones;
    }

    /**
     * @return mixed
     */
    public function getLiquidarPrima()
    {
        return $this->liquidarPrima;
    }

    /**
     * @param mixed $liquidarPrima
     */
    public function setLiquidarPrima($liquidarPrima): void
    {
        $this->liquidarPrima = $liquidarPrima;
    }

    /**
     * @return mixed
     */
    public function getLiquidarSalario()
    {
        return $this->liquidarSalario;
    }

    /**
     * @param mixed $liquidarSalario
     */
    public function setLiquidarSalario($liquidarSalario): void
    {
        $this->liquidarSalario = $liquidarSalario;
    }

    /**
     * @return mixed
     */
    public function getLiquidarManual()
    {
        return $this->liquidarManual;
    }

    /**
     * @param mixed $liquidarManual
     */
    public function setLiquidarManual($liquidarManual): void
    {
        $this->liquidarManual = $liquidarManual;
    }

    /**
     * @return mixed
     */
    public function getFechaUltimoPago()
    {
        return $this->fechaUltimoPago;
    }

    /**
     * @param mixed $fechaUltimoPago
     */
    public function setFechaUltimoPago($fechaUltimoPago): void
    {
        $this->fechaUltimoPago = $fechaUltimoPago;
    }

    /**
     * @return mixed
     */
    public function getFechaUltimoPagoPrima()
    {
        return $this->fechaUltimoPagoPrima;
    }

    /**
     * @param mixed $fechaUltimoPagoPrima
     */
    public function setFechaUltimoPagoPrima($fechaUltimoPagoPrima): void
    {
        $this->fechaUltimoPagoPrima = $fechaUltimoPagoPrima;
    }

    /**
     * @return mixed
     */
    public function getFechaUltimoPagoVacacion()
    {
        return $this->fechaUltimoPagoVacacion;
    }

    /**
     * @param mixed $fechaUltimoPagoVacacion
     */
    public function setFechaUltimoPagoVacacion($fechaUltimoPagoVacacion): void
    {
        $this->fechaUltimoPagoVacacion = $fechaUltimoPagoVacacion;
    }

    /**
     * @return mixed
     */
    public function getFechaUltimoPagoCesantias()
    {
        return $this->fechaUltimoPagoCesantias;
    }

    /**
     * @param mixed $fechaUltimoPagoCesantias
     */
    public function setFechaUltimoPagoCesantias($fechaUltimoPagoCesantias): void
    {
        $this->fechaUltimoPagoCesantias = $fechaUltimoPagoCesantias;
    }

    /**
     * @return mixed
     */
    public function getFechaUltimoPagoCesantiasAnterior()
    {
        return $this->fechaUltimoPagoCesantiasAnterior;
    }

    /**
     * @param mixed $fechaUltimoPagoCesantiasAnterior
     */
    public function setFechaUltimoPagoCesantiasAnterior($fechaUltimoPagoCesantiasAnterior): void
    {
        $this->fechaUltimoPagoCesantiasAnterior = $fechaUltimoPagoCesantiasAnterior;
    }

    /**
     * @return mixed
     */
    public function getEliminarAusentismo()
    {
        return $this->eliminarAusentismo;
    }

    /**
     * @param mixed $eliminarAusentismo
     */
    public function setEliminarAusentismo($eliminarAusentismo): void
    {
        $this->eliminarAusentismo = $eliminarAusentismo;
    }

    /**
     * @return mixed
     */
    public function getEliminarAusentismoCesantia()
    {
        return $this->eliminarAusentismoCesantia;
    }

    /**
     * @param mixed $eliminarAusentismoCesantia
     */
    public function setEliminarAusentismoCesantia($eliminarAusentismoCesantia): void
    {
        $this->eliminarAusentismoCesantia = $eliminarAusentismoCesantia;
    }

    /**
     * @return mixed
     */
    public function getEliminarAusentismoPrima()
    {
        return $this->eliminarAusentismoPrima;
    }

    /**
     * @param mixed $eliminarAusentismoPrima
     */
    public function setEliminarAusentismoPrima($eliminarAusentismoPrima): void
    {
        $this->eliminarAusentismoPrima = $eliminarAusentismoPrima;
    }

    /**
     * @return mixed
     */
    public function getEliminarAusentismoVacacion()
    {
        return $this->eliminarAusentismoVacacion;
    }

    /**
     * @param mixed $eliminarAusentismoVacacion
     */
    public function setEliminarAusentismoVacacion($eliminarAusentismoVacacion): void
    {
        $this->eliminarAusentismoVacacion = $eliminarAusentismoVacacion;
    }

    /**
     * @return mixed
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * @param mixed $estadoAutorizado
     */
    public function setEstadoAutorizado($estadoAutorizado): void
    {
        $this->estadoAutorizado = $estadoAutorizado;
    }

    /**
     * @return mixed
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * @param mixed $estadoAprobado
     */
    public function setEstadoAprobado($estadoAprobado): void
    {
        $this->estadoAprobado = $estadoAprobado;
    }

    /**
     * @return mixed
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * @param mixed $estadoAnulado
     */
    public function setEstadoAnulado($estadoAnulado): void
    {
        $this->estadoAnulado = $estadoAnulado;
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
    public function getMotivoTerminacionRel()
    {
        return $this->motivoTerminacionRel;
    }

    /**
     * @param mixed $motivoTerminacionRel
     */
    public function setMotivoTerminacionRel($motivoTerminacionRel): void
    {
        $this->motivoTerminacionRel = $motivoTerminacionRel;
    }

    /**
     * @return mixed
     */
    public function getDiasDeduccionPrimas()
    {
        return $this->diasDeduccionPrimas;
    }

    /**
     * @param mixed $diasDeduccionPrimas
     */
    public function setDiasDeduccionPrimas($diasDeduccionPrimas): void
    {
        $this->diasDeduccionPrimas = $diasDeduccionPrimas;
    }

    /**
     * @return mixed
     */
    public function getDiasAusentismoPropuestoVacaciones()
    {
        return $this->diasAusentismoPropuestoVacaciones;
    }

    /**
     * @param mixed $diasAusentismoPropuestoVacaciones
     */
    public function setDiasAusentismoPropuestoVacaciones($diasAusentismoPropuestoVacaciones): void
    {
        $this->diasAusentismoPropuestoVacaciones = $diasAusentismoPropuestoVacaciones;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioVacacionPropuesto()
    {
        return $this->VrSalarioVacacionPropuesto;
    }

    /**
     * @param mixed $VrSalarioVacacionPropuesto
     */
    public function setVrSalarioVacacionPropuesto($VrSalarioVacacionPropuesto): void
    {
        $this->VrSalarioVacacionPropuesto = $VrSalarioVacacionPropuesto;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioVacaciones()
    {
        return $this->VrSalarioVacaciones;
    }

    /**
     * @param mixed $VrSalarioVacaciones
     */
    public function setVrSalarioVacaciones($VrSalarioVacaciones): void
    {
        $this->VrSalarioVacaciones = $VrSalarioVacaciones;
    }

    /**
     * @return mixed
     */
    public function getVrAuxilioTransporte()
    {
        return $this->VrAuxilioTransporte;
    }

    /**
     * @param mixed $VrAuxilioTransporte
     */
    public function setVrAuxilioTransporte($VrAuxilioTransporte): void
    {
        $this->VrAuxilioTransporte = $VrAuxilioTransporte;
    }

    /**
     * @return mixed
     */
    public function getVrSalario()
    {
        return $this->VrSalario;
    }

    /**
     * @param mixed $VrSalario
     */
    public function setVrSalario($VrSalario): void
    {
        $this->VrSalario = $VrSalario;
    }

    /**
     * @return mixed
     */
    public function getVrTotal()
    {
        return $this->VrTotal;
    }

    /**
     * @param mixed $VrTotal
     */
    public function setVrTotal($VrTotal): void
    {
        $this->VrTotal = $VrTotal;
    }

    /**
     * @return mixed
     */
    public function getLiquidacionesAdicionalesLiquidacionRel()
    {
        return $this->liquidacionesAdicionalesLiquidacionRel;
    }

    /**
     * @param mixed $liquidacionesAdicionalesLiquidacionRel
     */
    public function setLiquidacionesAdicionalesLiquidacionRel($liquidacionesAdicionalesLiquidacionRel): void
    {
        $this->liquidacionesAdicionalesLiquidacionRel = $liquidacionesAdicionalesLiquidacionRel;
    }

    /**
     * @return mixed
     */
    public function getVrDeducciones()
    {
        return $this->VrDeducciones;
    }

    /**
     * @param mixed $VrDeducciones
     */
    public function setVrDeducciones($VrDeducciones): void
    {
        $this->VrDeducciones = $VrDeducciones;
    }

    /**
     * @return mixed
     */
    public function getVrBonificaciones()
    {
        return $this->VrBonificaciones;
    }

    /**
     * @param mixed $VrBonificaciones
     */
    public function setVrBonificaciones($VrBonificaciones): void
    {
        $this->VrBonificaciones = $VrBonificaciones;
    }

    /**
     * @return mixed
     */
    public function getNumeroDias()
    {
        return $this->numeroDias;
    }

    /**
     * @param mixed $numeroDias
     */
    public function setNumeroDias($numeroDias): void
    {
        $this->numeroDias = $numeroDias;
    }

    /**
     * @return mixed
     */
    public function getFechaInicioContrato()
    {
        return $this->fechaInicioContrato;
    }

    /**
     * @param mixed $fechaInicioContrato
     */
    public function setFechaInicioContrato($fechaInicioContrato): void
    {
        $this->fechaInicioContrato = $fechaInicioContrato;
    }

    /**
     * @return mixed
     */
    public function getOmitirCesantiasAnterior()
    {
        return $this->omitirCesantiasAnterior;
    }

    /**
     * @param mixed $omitirCesantiasAnterior
     */
    public function setOmitirCesantiasAnterior($omitirCesantiasAnterior): void
    {
        $this->omitirCesantiasAnterior = $omitirCesantiasAnterior;
    }

    /**
     * @return mixed
     */
    public function getOmitirInteresCesantiasAnterior()
    {
        return $this->omitirInteresCesantiasAnterior;
    }

    /**
     * @param mixed $omitirInteresCesantiasAnterior
     */
    public function setOmitirInteresCesantiasAnterior($omitirInteresCesantiasAnterior): void
    {
        $this->omitirInteresCesantiasAnterior = $omitirInteresCesantiasAnterior;
    }

    /**
     * @return mixed
     */
    public function getEstadoContabilizado()
    {
        return $this->estadoContabilizado;
    }

    /**
     * @param mixed $estadoContabilizado
     */
    public function setEstadoContabilizado($estadoContabilizado): void
    {
        $this->estadoContabilizado = $estadoContabilizado;
    }

    /**
     * @return mixed
     */
    public function getLiquidacionTipoRel()
    {
        return $this->liquidacionTipoRel;
    }

    /**
     * @param mixed $liquidacionTipoRel
     */
    public function setLiquidacionTipoRel($liquidacionTipoRel): void
    {
        $this->liquidacionTipoRel = $liquidacionTipoRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoLiquidacionTipoFk()
    {
        return $this->codigoLiquidacionTipoFk;
    }

    /**
     * @param mixed $codigoLiquidacionTipoFk
     */
    public function setCodigoLiquidacionTipoFk($codigoLiquidacionTipoFk): void
    {
        $this->codigoLiquidacionTipoFk = $codigoLiquidacionTipoFk;
    }

    /**
     * @return mixed
     */
    public function getPagosLiquidacionRel()
    {
        return $this->pagosLiquidacionRel;
    }

    /**
     * @param mixed $pagosLiquidacionRel
     */
    public function setPagosLiquidacionRel($pagosLiquidacionRel): void
    {
        $this->pagosLiquidacionRel = $pagosLiquidacionRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoPagoCesantiaInteresAnteriorFk()
    {
        return $this->codigoPagoCesantiaInteresAnteriorFk;
    }

    /**
     * @param mixed $codigoPagoCesantiaInteresAnteriorFk
     */
    public function setCodigoPagoCesantiaInteresAnteriorFk($codigoPagoCesantiaInteresAnteriorFk): void
    {
        $this->codigoPagoCesantiaInteresAnteriorFk = $codigoPagoCesantiaInteresAnteriorFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPagoCesantiaAnteriorFk()
    {
        return $this->codigoPagoCesantiaAnteriorFk;
    }

    /**
     * @param mixed $codigoPagoCesantiaAnteriorFk
     */
    public function setCodigoPagoCesantiaAnteriorFk($codigoPagoCesantiaAnteriorFk): void
    {
        $this->codigoPagoCesantiaAnteriorFk = $codigoPagoCesantiaAnteriorFk;
    }



}


