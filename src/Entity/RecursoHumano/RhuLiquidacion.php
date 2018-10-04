<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuLiquidacionRepository")
 */
class RhuLiquidacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_liquidacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLiquidacionPk;

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
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */
    private $codigoCentroCostoFk;

    /**
     * @ORM\Column(name="codigo_motivo_terminacion_contrato_fk", type="integer", nullable=true)
     */
    private $codigoMotivoTerminacionContratoFk;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="fecha_hasta_contrato_fijo", type="date", nullable=true)
     */
    private $fechaHastaContratoFijo;

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
     * @ORM\Column(name="vr_cesantias_anterior", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrCesantiasAnterior = 0;

    /**
     * @ORM\Column(name="vr_intereses_cesantias_anterior", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrInteresesCesantiasAnterior = 0;

    /**
     * @ORM\Column(name="vr_prima", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrPrima = 0;

    /**
     * @ORM\Column(name="vr_deduccion_prima", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrDeduccionPrima = 0;

    /**
     * @ORM\Column(name="vr_deduccion_prima_propuesto", options={"default" : 0}, type="float",nullable=true)
     */
    private $vrDeduccionPrimaPropuesto = 0;

    /**
     * @ORM\Column(name="vr_vacaciones", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrVacaciones = 0;

    /**
     * @ORM\Column(name="vr_indemnizacion", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrIndemnizacion = 0;



    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="dias_cesantias", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasCesantias = 0;

    /**
     * @ORM\Column(name="dias_cesantias_ausentismo", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasCesantiasAusentismo = 0;

    /**
     * @ORM\Column(name="dias_cesantias_anterior", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasCesantiasAnterior = 0;

    /**
     * @ORM\Column(name="dias_cesantias_ausentismo_anterior", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasCesantiasAusentismo_anterior = 0;

    /**
     * @ORM\Column(name="dias_vacaciones", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasVacaciones = 0;

    /**
     * @ORM\Column(name="dias_vacaciones_ausentismo", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasVacacionesAusentismo = 0;

    /**
     * @ORM\Column(name="dias_primas", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasPrimas = 0;

    /**
     * @ORM\Column(name="dias_primas_ausentismo", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasPrimasAusentismo = 0;

    /**
     * @ORM\Column(name="dias_deduccion_primas_", options={"default" : 0}, type="integer",nullable=true)
     */
    private $diasDeduccionPrimas = 0;

    /**
     * @ORM\Column(name="dias_laborados", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasLaborados = 0;

    /**
     * @ORM\Column(name="fecha_ultimo_pago", type="date", nullable=true)
     */
    private $fechaUltimoPago;

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion_adicional", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrIngresoBasePrestacionAdicional = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion_cesantias", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrIngresoBasePrestacionCesantias = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion_primas", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrIngresoBasePrestacionPrimas = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion_cesantias_inicial", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrIngresoBasePrestacionCesantiasInicial = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion_primas_inicial", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrIngresoBasePrestacionPrimasInicial = 0;

    /**
     * @ORM\Column(name="dias_adicionales_ibp", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasAdicionalesIBP = 0;

    /**
     * @ORM\Column(name="vr_base_prestaciones", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrBasePrestaciones = 0;

    /**
     * @ORM\Column(name="vr_base_prestaciones_total", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrBasePrestacionesTotal = 0;

    /**
     * @ORM\Column(name="vr_auxilio_transporte", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrAuxilioTransporte = 0;

    /**
     * @ORM\Column(name="vr_salario", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrSalario = 0;

    /**
     * @ORM\Column(name="vr_salario_promedio_cesantias", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrSalarioPromedioCesantias = 0;

    /**
     * @ORM\Column(name="vr_salario_promedio_cesantias_anterior", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrSalarioPromedioCesantiasAnterior = 0;

    /**
     * @ORM\Column(name="vr_salario_promedio_primas", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrSalarioPromedioPrimas = 0;

    /**
     * @ORM\Column(name="vr_salario_vacaciones", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrSalarioVacaciones = 0;

    /**
     * @ORM\Column(name="vr_total", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="liquidar_cesantias", options={"default" : false}, type="boolean", nullable=true)
     */
    private $liquidarCesantias = false;

    /**
     * @ORM\Column(name="liquidar_vacaciones", options={"default" : false}, type="boolean", nullable=true)
     */
    private $liquidarVacaciones = false;

    /**
     * @ORM\Column(name="liquidar_prima", options={"default" : false}, type="boolean", nullable=true)
     */
    private $liquidarPrima = false;

    /**
     * @ORM\Column(name="fecha_ultimo_pago_primas", type="date", nullable=true)
     */
    private $fechaUltimoPagoPrimas;

    /**
     * @ORM\Column(name="fecha_ultimo_pago_vacaciones", type="date", nullable=true)
     */
    private $fechaUltimoPagoVacaciones;

    /**
     * @ORM\Column(name="fecha_ultimo_pago_cesantias", type="date", nullable=true)
     */
    private $fechaUltimoPagoCesantias;

    /**
     * @ORM\Column(name="fecha_ultimo_pago_cesantias_anterior", type="date", nullable=true)
     */
    private $fechaUltimoPagoCesantiasAnterior;

    /**
     * @ORM\Column(name="vr_deducciones", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrDeducciones = 0;

    /**
     * @ORM\Column(name="vr_bonificaciones", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrBonificaciones = 0;

    /**
     * @ORM\Column(name="estado_autorizado", options={"default" : false}, type="boolean", nullable=true)
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_generado", options={"default" : false}, type="boolean", nullable=true)
     */
    private $estadoGenerado = false;

    /**
     * @ORM\Column(name="estado_anulado", options={"default" : false}, type="boolean", nullable=true)
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAprobado = false;


    /**
     * @ORM\Column(name="estado_indemnizacion", options={"default" : false}, type="boolean", nullable = true)
     */
    private $estadoIndemnizacion = false;

    /**
     * @ORM\Column(name="estado_pago_generado", options={"default" : false}, type="boolean", nullable=true)
     */
    private $estadoPagoGenerado = false;

    /**
     * @ORM\Column(name="estado_pago_banco", options={"default" : false}, type="boolean", nullable=true)
     */
    private $estadoPagoBanco = false;

    /**
     * @ORM\Column(name="estado_contabilizado", options={"default" : false}, type="boolean", nullable=true)
     */
    private $estadoContabilizado = false;

    /**
     * @ORM\Column(name="fecha_inicio_contrato", type="date", nullable=true)
     */
    private $fechaInicioContrato;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="liquidar_manual", options={"default" : 0}, type="boolean", nullable=true)
     */
    private $liquidarManual = 0;



    /**
     * @ORM\Column(name="liquidar_salario", options={"default" : 0}, type="boolean", nullable=true)
     */
    private $liquidarSalario = 0;

    /**
     * @ORM\Column(name="porcentaje_ibp", options={"default" : 100}, type="float", nullable=true)
     */
    private $porcentajeIbp = 100;

    /**
     * @ORM\Column(name="dias_ausentismo_adicional", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasAusentismoAdicional = 0;

    /**
     * @ORM\Column(name="vr_salario_vacacion_propuesto", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrSalarioVacacionPropuesto = 0;

    /**
     * @ORM\Column(name="vr_indemnizacion_propuesto", options={"default" : 0}, type="float", nullable = true)
     */
    private $vrIndemnizacionPropuesto = 0;

    /**
     * @ORM\Column(name="vr_salario_prima_propuesto", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrSalarioPrimaPropuesto = 0;

    /**
     * @ORM\Column(name="vr_salario_cesantias_propuesto", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrSalarioCesantiasPropuesto = 0;

    /**
     * @ORM\Column(name="eliminar_ausentismo", options={"default" : false}, type="boolean", nullable=true)
     */
    private $eliminarAusentismo = false;

    /**
     * @ORM\Column(name="eliminar_ausentismo_cesantia", options={"default" : false}, type="boolean", nullable=true)
     */
    private $eliminarAusentismoCesantia = false;

    /**
     * @ORM\Column(name="eliminar_ausentismo_primas", options={"default" : false}, type="boolean", nullable=true)
     */
    private $eliminarAusentismoPrima = false;

    /**
     * @ORM\Column(name="eliminar_ausentismo_vacacion", options={"default" : false}, type="boolean", nullable=true)
     */
    private $eliminarAusentismoVacacion = false;

    /**
     * @ORM\Column(name="dias_ausentismo_propuesto", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasAusentismoPropuesto = 0;

    /**
     * @ORM\Column(name="dias_ausentismo_propuesto_cesantias", options={"default" : 0}, options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasAusentismoPropuestoCesantias = 0;

    /**
     * @ORM\Column(name="dias_ausentismo_propuesto_primas", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasAusentismoPropuestoPrimas = 0;

    /**
     * @ORM\Column(name="dias_ausentismo_propuesto_vacaciones", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasAusentismoPropuestoVacaciones = 0;

    /**
     * @ORM\Column(name="codigo_programacion_pago_detalle_fk", type="integer", nullable=true)
     */
    private $codigoProgramacionPagoDetalleFk;

    /**
     * @ORM\Column(name="codigo_programacion_pago_detalle_interes_fk", type="integer", nullable=true)
     */
    private $codigoProgramacionPagoDetalleInteresFk;

    /**
     * @ORM\Column(name="codigo_pago_fk", type="integer", nullable=true)
     */
    private $codigoPagoFk;

    /**
     * @ORM\Column(name="codigo_pago_interes_fk", type="integer", nullable=true)
     */
    private $codigoPagoInteresFk;

    /**
     * @ORM\Column(name="omitir_cesantias_anterior", options={"default" : false}, type="boolean", nullable=true)
     */
    private $omitirCesantiasAnterior = false;

    /**
     * @ORM\Column(name="omitir_interes_cesantias_anterior", options={"default" : false}, type="boolean", nullable=true)
     */
    private $omitirInteresCesantiasAnterior = false;

    /**
     * @ORM\Column(name="vr_suplementario_censatias", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrSuplementarioCesantias = 0;

    /**
     * @ORM\Column(name="vr_suplementario_primas", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrSuplementarioPrimas = 0;

    /**
     * @ORM\Column(name="vr_suplementario_vacaciones", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrSuplementarioVacaciones = 0;

    /**
     * @ORM\Column(name="porcentaje_intereses_cesantias", options={"default" : 0}, type="float", nullable=true)
     */
    private $porcentajeInteresesCesantias = 0;

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
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * @param mixed $codigoCentroCostoFk
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk): void
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoMotivoTerminacionContratoFk()
    {
        return $this->codigoMotivoTerminacionContratoFk;
    }

    /**
     * @param mixed $codigoMotivoTerminacionContratoFk
     */
    public function setCodigoMotivoTerminacionContratoFk($codigoMotivoTerminacionContratoFk): void
    {
        $this->codigoMotivoTerminacionContratoFk = $codigoMotivoTerminacionContratoFk;
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
    public function getFechaHastaContratoFijo()
    {
        return $this->fechaHastaContratoFijo;
    }

    /**
     * @param mixed $fechaHastaContratoFijo
     */
    public function setFechaHastaContratoFijo($fechaHastaContratoFijo): void
    {
        $this->fechaHastaContratoFijo = $fechaHastaContratoFijo;
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
    public function getVrCesantiasAnterior()
    {
        return $this->vrCesantiasAnterior;
    }

    /**
     * @param mixed $vrCesantiasAnterior
     */
    public function setVrCesantiasAnterior($vrCesantiasAnterior): void
    {
        $this->vrCesantiasAnterior = $vrCesantiasAnterior;
    }

    /**
     * @return mixed
     */
    public function getVrInteresesCesantiasAnterior()
    {
        return $this->vrInteresesCesantiasAnterior;
    }

    /**
     * @param mixed $vrInteresesCesantiasAnterior
     */
    public function setVrInteresesCesantiasAnterior($vrInteresesCesantiasAnterior): void
    {
        $this->vrInteresesCesantiasAnterior = $vrInteresesCesantiasAnterior;
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
    public function getVrDeduccionPrima()
    {
        return $this->vrDeduccionPrima;
    }

    /**
     * @param mixed $vrDeduccionPrima
     */
    public function setVrDeduccionPrima($vrDeduccionPrima): void
    {
        $this->vrDeduccionPrima = $vrDeduccionPrima;
    }

    /**
     * @return mixed
     */
    public function getVrDeduccionPrimaPropuesto()
    {
        return $this->vrDeduccionPrimaPropuesto;
    }

    /**
     * @param mixed $vrDeduccionPrimaPropuesto
     */
    public function setVrDeduccionPrimaPropuesto($vrDeduccionPrimaPropuesto): void
    {
        $this->vrDeduccionPrimaPropuesto = $vrDeduccionPrimaPropuesto;
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
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * @param mixed $comentarios
     */
    public function setComentarios($comentarios): void
    {
        $this->comentarios = $comentarios;
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
    public function getDiasVacacionesAusentismo()
    {
        return $this->diasVacacionesAusentismo;
    }

    /**
     * @param mixed $diasVacacionesAusentismo
     */
    public function setDiasVacacionesAusentismo($diasVacacionesAusentismo): void
    {
        $this->diasVacacionesAusentismo = $diasVacacionesAusentismo;
    }

    /**
     * @return mixed
     */
    public function getDiasPrimas()
    {
        return $this->diasPrimas;
    }

    /**
     * @param mixed $diasPrimas
     */
    public function setDiasPrimas($diasPrimas): void
    {
        $this->diasPrimas = $diasPrimas;
    }

    /**
     * @return mixed
     */
    public function getDiasPrimasAusentismo()
    {
        return $this->diasPrimasAusentismo;
    }

    /**
     * @param mixed $diasPrimasAusentismo
     */
    public function setDiasPrimasAusentismo($diasPrimasAusentismo): void
    {
        $this->diasPrimasAusentismo = $diasPrimasAusentismo;
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
    public function getDiasLaborados()
    {
        return $this->diasLaborados;
    }

    /**
     * @param mixed $diasLaborados
     */
    public function setDiasLaborados($diasLaborados): void
    {
        $this->diasLaborados = $diasLaborados;
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
    public function getVrIngresoBasePrestacionAdicional()
    {
        return $this->vrIngresoBasePrestacionAdicional;
    }

    /**
     * @param mixed $vrIngresoBasePrestacionAdicional
     */
    public function setVrIngresoBasePrestacionAdicional($vrIngresoBasePrestacionAdicional): void
    {
        $this->vrIngresoBasePrestacionAdicional = $vrIngresoBasePrestacionAdicional;
    }

    /**
     * @return mixed
     */
    public function getVrIngresoBasePrestacionCesantias()
    {
        return $this->vrIngresoBasePrestacionCesantias;
    }

    /**
     * @param mixed $vrIngresoBasePrestacionCesantias
     */
    public function setVrIngresoBasePrestacionCesantias($vrIngresoBasePrestacionCesantias): void
    {
        $this->vrIngresoBasePrestacionCesantias = $vrIngresoBasePrestacionCesantias;
    }

    /**
     * @return mixed
     */
    public function getVrIngresoBasePrestacionPrimas()
    {
        return $this->vrIngresoBasePrestacionPrimas;
    }

    /**
     * @param mixed $vrIngresoBasePrestacionPrimas
     */
    public function setVrIngresoBasePrestacionPrimas($vrIngresoBasePrestacionPrimas): void
    {
        $this->vrIngresoBasePrestacionPrimas = $vrIngresoBasePrestacionPrimas;
    }

    /**
     * @return mixed
     */
    public function getVrIngresoBasePrestacionCesantiasInicial()
    {
        return $this->vrIngresoBasePrestacionCesantiasInicial;
    }

    /**
     * @param mixed $vrIngresoBasePrestacionCesantiasInicial
     */
    public function setVrIngresoBasePrestacionCesantiasInicial($vrIngresoBasePrestacionCesantiasInicial): void
    {
        $this->vrIngresoBasePrestacionCesantiasInicial = $vrIngresoBasePrestacionCesantiasInicial;
    }

    /**
     * @return mixed
     */
    public function getVrIngresoBasePrestacionPrimasInicial()
    {
        return $this->vrIngresoBasePrestacionPrimasInicial;
    }

    /**
     * @param mixed $vrIngresoBasePrestacionPrimasInicial
     */
    public function setVrIngresoBasePrestacionPrimasInicial($vrIngresoBasePrestacionPrimasInicial): void
    {
        $this->vrIngresoBasePrestacionPrimasInicial = $vrIngresoBasePrestacionPrimasInicial;
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
    public function getVrBasePrestaciones()
    {
        return $this->vrBasePrestaciones;
    }

    /**
     * @param mixed $vrBasePrestaciones
     */
    public function setVrBasePrestaciones($vrBasePrestaciones): void
    {
        $this->vrBasePrestaciones = $vrBasePrestaciones;
    }

    /**
     * @return mixed
     */
    public function getVrBasePrestacionesTotal()
    {
        return $this->vrBasePrestacionesTotal;
    }

    /**
     * @param mixed $vrBasePrestacionesTotal
     */
    public function setVrBasePrestacionesTotal($vrBasePrestacionesTotal): void
    {
        $this->vrBasePrestacionesTotal = $vrBasePrestacionesTotal;
    }

    /**
     * @return mixed
     */
    public function getVrAuxilioTransporte()
    {
        return $this->vrAuxilioTransporte;
    }

    /**
     * @param mixed $vrAuxilioTransporte
     */
    public function setVrAuxilioTransporte($vrAuxilioTransporte): void
    {
        $this->vrAuxilioTransporte = $vrAuxilioTransporte;
    }

    /**
     * @return mixed
     */
    public function getVrSalario()
    {
        return $this->vrSalario;
    }

    /**
     * @param mixed $vrSalario
     */
    public function setVrSalario($vrSalario): void
    {
        $this->vrSalario = $vrSalario;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioPromedioCesantias()
    {
        return $this->vrSalarioPromedioCesantias;
    }

    /**
     * @param mixed $vrSalarioPromedioCesantias
     */
    public function setVrSalarioPromedioCesantias($vrSalarioPromedioCesantias): void
    {
        $this->vrSalarioPromedioCesantias = $vrSalarioPromedioCesantias;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioPromedioCesantiasAnterior()
    {
        return $this->vrSalarioPromedioCesantiasAnterior;
    }

    /**
     * @param mixed $vrSalarioPromedioCesantiasAnterior
     */
    public function setVrSalarioPromedioCesantiasAnterior($vrSalarioPromedioCesantiasAnterior): void
    {
        $this->vrSalarioPromedioCesantiasAnterior = $vrSalarioPromedioCesantiasAnterior;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioPromedioPrimas()
    {
        return $this->vrSalarioPromedioPrimas;
    }

    /**
     * @param mixed $vrSalarioPromedioPrimas
     */
    public function setVrSalarioPromedioPrimas($vrSalarioPromedioPrimas): void
    {
        $this->vrSalarioPromedioPrimas = $vrSalarioPromedioPrimas;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioVacaciones()
    {
        return $this->vrSalarioVacaciones;
    }

    /**
     * @param mixed $vrSalarioVacaciones
     */
    public function setVrSalarioVacaciones($vrSalarioVacaciones): void
    {
        $this->vrSalarioVacaciones = $vrSalarioVacaciones;
    }

    /**
     * @return mixed
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * @param mixed $vrTotal
     */
    public function setVrTotal($vrTotal): void
    {
        $this->vrTotal = $vrTotal;
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
    public function getFechaUltimoPagoPrimas()
    {
        return $this->fechaUltimoPagoPrimas;
    }

    /**
     * @param mixed $fechaUltimoPagoPrimas
     */
    public function setFechaUltimoPagoPrimas($fechaUltimoPagoPrimas): void
    {
        $this->fechaUltimoPagoPrimas = $fechaUltimoPagoPrimas;
    }

    /**
     * @return mixed
     */
    public function getFechaUltimoPagoVacaciones()
    {
        return $this->fechaUltimoPagoVacaciones;
    }

    /**
     * @param mixed $fechaUltimoPagoVacaciones
     */
    public function setFechaUltimoPagoVacaciones($fechaUltimoPagoVacaciones): void
    {
        $this->fechaUltimoPagoVacaciones = $fechaUltimoPagoVacaciones;
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
    public function getVrDeducciones()
    {
        return $this->vrDeducciones;
    }

    /**
     * @param mixed $vrDeducciones
     */
    public function setVrDeducciones($vrDeducciones): void
    {
        $this->vrDeducciones = $vrDeducciones;
    }

    /**
     * @return mixed
     */
    public function getVrBonificaciones()
    {
        return $this->vrBonificaciones;
    }

    /**
     * @param mixed $vrBonificaciones
     */
    public function setVrBonificaciones($vrBonificaciones): void
    {
        $this->vrBonificaciones = $vrBonificaciones;
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
    public function getEstadoGenerado()
    {
        return $this->estadoGenerado;
    }

    /**
     * @param mixed $estadoGenerado
     */
    public function setEstadoGenerado($estadoGenerado): void
    {
        $this->estadoGenerado = $estadoGenerado;
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
    public function getEstadoPagoGenerado()
    {
        return $this->estadoPagoGenerado;
    }

    /**
     * @param mixed $estadoPagoGenerado
     */
    public function setEstadoPagoGenerado($estadoPagoGenerado): void
    {
        $this->estadoPagoGenerado = $estadoPagoGenerado;
    }

    /**
     * @return mixed
     */
    public function getEstadoPagoBanco()
    {
        return $this->estadoPagoBanco;
    }

    /**
     * @param mixed $estadoPagoBanco
     */
    public function setEstadoPagoBanco($estadoPagoBanco): void
    {
        $this->estadoPagoBanco = $estadoPagoBanco;
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
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
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
    public function getVrSalarioVacacionPropuesto()
    {
        return $this->vrSalarioVacacionPropuesto;
    }

    /**
     * @param mixed $vrSalarioVacacionPropuesto
     */
    public function setVrSalarioVacacionPropuesto($vrSalarioVacacionPropuesto): void
    {
        $this->vrSalarioVacacionPropuesto = $vrSalarioVacacionPropuesto;
    }

    /**
     * @return mixed
     */
    public function getVrIndemnizacionPropuesto()
    {
        return $this->vrIndemnizacionPropuesto;
    }

    /**
     * @param mixed $vrIndemnizacionPropuesto
     */
    public function setVrIndemnizacionPropuesto($vrIndemnizacionPropuesto): void
    {
        $this->vrIndemnizacionPropuesto = $vrIndemnizacionPropuesto;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioPrimaPropuesto()
    {
        return $this->vrSalarioPrimaPropuesto;
    }

    /**
     * @param mixed $vrSalarioPrimaPropuesto
     */
    public function setVrSalarioPrimaPropuesto($vrSalarioPrimaPropuesto): void
    {
        $this->vrSalarioPrimaPropuesto = $vrSalarioPrimaPropuesto;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioCesantiasPropuesto()
    {
        return $this->vrSalarioCesantiasPropuesto;
    }

    /**
     * @param mixed $vrSalarioCesantiasPropuesto
     */
    public function setVrSalarioCesantiasPropuesto($vrSalarioCesantiasPropuesto): void
    {
        $this->vrSalarioCesantiasPropuesto = $vrSalarioCesantiasPropuesto;
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
    public function getDiasAusentismoPropuesto()
    {
        return $this->diasAusentismoPropuesto;
    }

    /**
     * @param mixed $diasAusentismoPropuesto
     */
    public function setDiasAusentismoPropuesto($diasAusentismoPropuesto): void
    {
        $this->diasAusentismoPropuesto = $diasAusentismoPropuesto;
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
    public function getCodigoProgramacionPagoDetalleFk()
    {
        return $this->codigoProgramacionPagoDetalleFk;
    }

    /**
     * @param mixed $codigoProgramacionPagoDetalleFk
     */
    public function setCodigoProgramacionPagoDetalleFk($codigoProgramacionPagoDetalleFk): void
    {
        $this->codigoProgramacionPagoDetalleFk = $codigoProgramacionPagoDetalleFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoProgramacionPagoDetalleInteresFk()
    {
        return $this->codigoProgramacionPagoDetalleInteresFk;
    }

    /**
     * @param mixed $codigoProgramacionPagoDetalleInteresFk
     */
    public function setCodigoProgramacionPagoDetalleInteresFk($codigoProgramacionPagoDetalleInteresFk): void
    {
        $this->codigoProgramacionPagoDetalleInteresFk = $codigoProgramacionPagoDetalleInteresFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPagoFk()
    {
        return $this->codigoPagoFk;
    }

    /**
     * @param mixed $codigoPagoFk
     */
    public function setCodigoPagoFk($codigoPagoFk): void
    {
        $this->codigoPagoFk = $codigoPagoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPagoInteresFk()
    {
        return $this->codigoPagoInteresFk;
    }

    /**
     * @param mixed $codigoPagoInteresFk
     */
    public function setCodigoPagoInteresFk($codigoPagoInteresFk): void
    {
        $this->codigoPagoInteresFk = $codigoPagoInteresFk;
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
    public function getVrSuplementarioCesantias()
    {
        return $this->vrSuplementarioCesantias;
    }

    /**
     * @param mixed $vrSuplementarioCesantias
     */
    public function setVrSuplementarioCesantias($vrSuplementarioCesantias): void
    {
        $this->vrSuplementarioCesantias = $vrSuplementarioCesantias;
    }

    /**
     * @return mixed
     */
    public function getVrSuplementarioPrimas()
    {
        return $this->vrSuplementarioPrimas;
    }

    /**
     * @param mixed $vrSuplementarioPrimas
     */
    public function setVrSuplementarioPrimas($vrSuplementarioPrimas): void
    {
        $this->vrSuplementarioPrimas = $vrSuplementarioPrimas;
    }

    /**
     * @return mixed
     */
    public function getVrSuplementarioVacaciones()
    {
        return $this->vrSuplementarioVacaciones;
    }

    /**
     * @param mixed $vrSuplementarioVacaciones
     */
    public function setVrSuplementarioVacaciones($vrSuplementarioVacaciones): void
    {
        $this->vrSuplementarioVacaciones = $vrSuplementarioVacaciones;
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
}


