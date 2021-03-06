<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuProgramacionDetalleRepository")
 */
class RhuProgramacionDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_programacion_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProgramacionDetallePk;

    /**
     * @ORM\Column(name="codigo_programacion_fk", type="integer", nullable=true)
     */
    private $codigoProgramacionFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="dias", type="integer", options={"default": 0})
     */
    private $dias = 0;

    /**
     * Para el auxilio de transporte
     * @ORM\Column(name="dias_transporte", type="integer", options={"default": 0})
     */
    private $diasTransporte = 0;

    /**
     * @ORM\Column(name="dias_vacaciones", type="integer", nullable=true, options={"default": 0})
     */
    private $diasVacaciones = 0;

    /**
     * @ORM\Column(name="dias_licencia", type="integer", nullable=true, options={"default": 0})
     */
    private $diasLicencia = 0;

    /**
     * @ORM\Column(name="dias_incapacidad", type="integer", nullable=true, options={"default": 0})
     */
    private $diasIncapacidad = 0;

    /**
     * @ORM\Column(name="dias_ausentismo", type="integer", nullable=true, options={"default": 0})
     */
    private $diasAusentismo = 0;

    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $vrSalario = 0;

    /**
     * @ORM\Column(name="vr_salario_prima", type="float")
     */
    private $vrSalarioPrima = 0;

    /**
     * @ORM\Column(name="vr_salario_cesantia", type="float")
     */
    private $vrSalarioCesantia = 0;

    /**
     * @ORM\Column(name="vr_neto", type="float", nullable=true)
     */
    private $vrNeto = 0;

    /**
     * @ORM\Column(name="vr_dia", type="float")
     */
    private $vrDia = 0;

    /**
     * @ORM\Column(name="vr_hora", type="float")
     */
    private $vrHora = 0;

    /**
     * @ORM\Column(name="vr_anticipo", type="float", options={"default": true})
     */
    private $vrAnticipo = 0;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="fecha_desde_contrato", type="date", nullable=true)
     */
    private $fechaDesdeContrato;

    /**
     * @ORM\Column(name="fecha_hasta_contrato", type="date", nullable=true)
     */
    private $fechaHastaContrato;

    /**
     * @ORM\Column(name="horas_diurnas", type="float")
     */
    private $horasDiurnas = 0;

    /**
     * @ORM\Column(name="horas_descanso", type="float")
     */
    private $horasDescanso = 0;

    /**
     * @ORM\Column(name="horas_nocturnas", type="float")
     */
    private $horasNocturnas = 0;

    /**
     * @ORM\Column(name="horas_festivas_diurnas", type="float")
     */
    private $horasFestivasDiurnas = 0;

    /**
     * @ORM\Column(name="horas_festivas_nocturnas", type="float")
     */
    private $horasFestivasNocturnas = 0;

    /**
     * @ORM\Column(name="horas_extras_ordinarias_diurnas", type="float")
     */
    private $horasExtrasOrdinariasDiurnas = 0;

    /**
     * @ORM\Column(name="horas_extras_ordinarias_nocturnas", type="float")
     */
    private $horasExtrasOrdinariasNocturnas = 0;

    /**
     * @ORM\Column(name="horas_extras_festivas_diurnas", type="float")
     */
    private $horasExtrasFestivasDiurnas = 0;

    /**
     * @ORM\Column(name="horas_extras_festivas_nocturnas", type="float")
     */
    private $horasExtrasFestivasNocturnas = 0;

    /**
     * @ORM\Column(name="horas_recargo", type="float")
     */
    private $horasRecargo = 0;

    /**
     * @ORM\Column(name="horas_recargo_nocturno", type="float")
     */
    private $horasRecargoNocturno = 0;

    /**
     * @ORM\Column(name="horas_recargo_festivo_diurno", type="float")
     */
    private $horasRecargoFestivoDiurno = 0;

    /**
     * @ORM\Column(name="horas_recargo_festivo_nocturno", type="float")
     */
    private $horasRecargoFestivoNocturno = 0;

    /**
     * @ORM\Column(name="descuento_salud", options={"default": true}, type="boolean", nullable=true)
     */
    private $descuentoSalud = true;

    /**
     * @ORM\Column(name="descuento_pension", options={"default": true}, type="boolean", nullable=true)
     */
    private $descuentoPension = true;

    /**
     * @ORM\Column(name="pago_auxilio_transporte", options={"default": true}, type="boolean", nullable=true)
     */
    private $pagoAuxilioTransporte = true;

    /**
     * @ORM\Column(name="vr_ibc_acumulado", type="float", options={"default": 0})
     */
    private $vrIbcAcumulado = 0;

    /**
     * @ORM\Column(name="vr_deduccion_fondo_pension_anterior", type="float", options={"default": 0})
     */
    private $vrDeduccionFondoPensionAnterior = 0;

    /**
     * @ORM\Column(name="factor_horas_dia", type="integer", nullable=true, options={"default": 0})
     */
    private $factorHorasDia = 0;

    /**
     * @ORM\Column(name="codigo_soporte_contrato_fk", type="integer", nullable=true)
     */
    private $codigoSoporteContratoFk = null;

    /**
     * @ORM\Column(name="marca", type="boolean", nullable=true)
     */
    private $marca = false;

    /**
     * @ORM\ManyToOne(targetEntity="RhuProgramacion", inversedBy="programacionesDetallesProgramacionRel")
     * @ORM\JoinColumn(name="codigo_programacion_fk", referencedColumnName="codigo_programacion_pk")
     */
    protected $programacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="programacionesPagosDetallesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="programacionesDetallesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuPago", mappedBy="programacionDetalleRel")
     */
    protected $pagosProgramacionDetalleRel;

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
    public function getCodigoProgramacionDetallePk()
    {
        return $this->codigoProgramacionDetallePk;
    }

    /**
     * @param mixed $codigoProgramacionDetallePk
     */
    public function setCodigoProgramacionDetallePk($codigoProgramacionDetallePk): void
    {
        $this->codigoProgramacionDetallePk = $codigoProgramacionDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoProgramacionFk()
    {
        return $this->codigoProgramacionFk;
    }

    /**
     * @param mixed $codigoProgramacionFk
     */
    public function setCodigoProgramacionFk($codigoProgramacionFk): void
    {
        $this->codigoProgramacionFk = $codigoProgramacionFk;
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
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * @param mixed $dias
     */
    public function setDias($dias): void
    {
        $this->dias = $dias;
    }

    /**
     * @return mixed
     */
    public function getDiasTransporte()
    {
        return $this->diasTransporte;
    }

    /**
     * @param mixed $diasTransporte
     */
    public function setDiasTransporte($diasTransporte): void
    {
        $this->diasTransporte = $diasTransporte;
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
    public function getDiasIncapacidad()
    {
        return $this->diasIncapacidad;
    }

    /**
     * @param mixed $diasIncapacidad
     */
    public function setDiasIncapacidad($diasIncapacidad): void
    {
        $this->diasIncapacidad = $diasIncapacidad;
    }

    /**
     * @return mixed
     */
    public function getDiasAusentismo()
    {
        return $this->diasAusentismo;
    }

    /**
     * @param mixed $diasAusentismo
     */
    public function setDiasAusentismo($diasAusentismo): void
    {
        $this->diasAusentismo = $diasAusentismo;
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
    public function getVrSalarioPrima()
    {
        return $this->vrSalarioPrima;
    }

    /**
     * @param mixed $vrSalarioPrima
     */
    public function setVrSalarioPrima($vrSalarioPrima): void
    {
        $this->vrSalarioPrima = $vrSalarioPrima;
    }

    /**
     * @return mixed
     */
    public function getVrNeto()
    {
        return $this->vrNeto;
    }

    /**
     * @param mixed $vrNeto
     */
    public function setVrNeto($vrNeto): void
    {
        $this->vrNeto = $vrNeto;
    }

    /**
     * @return mixed
     */
    public function getVrDia()
    {
        return $this->vrDia;
    }

    /**
     * @param mixed $vrDia
     */
    public function setVrDia($vrDia): void
    {
        $this->vrDia = $vrDia;
    }

    /**
     * @return mixed
     */
    public function getVrHora()
    {
        return $this->vrHora;
    }

    /**
     * @param mixed $vrHora
     */
    public function setVrHora($vrHora): void
    {
        $this->vrHora = $vrHora;
    }

    /**
     * @return mixed
     */
    public function getVrAnticipo()
    {
        return $this->vrAnticipo;
    }

    /**
     * @param mixed $vrAnticipo
     */
    public function setVrAnticipo($vrAnticipo): void
    {
        $this->vrAnticipo = $vrAnticipo;
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
    public function getFechaDesdeContrato()
    {
        return $this->fechaDesdeContrato;
    }

    /**
     * @param mixed $fechaDesdeContrato
     */
    public function setFechaDesdeContrato($fechaDesdeContrato): void
    {
        $this->fechaDesdeContrato = $fechaDesdeContrato;
    }

    /**
     * @return mixed
     */
    public function getFechaHastaContrato()
    {
        return $this->fechaHastaContrato;
    }

    /**
     * @param mixed $fechaHastaContrato
     */
    public function setFechaHastaContrato($fechaHastaContrato): void
    {
        $this->fechaHastaContrato = $fechaHastaContrato;
    }

    /**
     * @return mixed
     */
    public function getHorasDiurnas()
    {
        return $this->horasDiurnas;
    }

    /**
     * @param mixed $horasDiurnas
     */
    public function setHorasDiurnas($horasDiurnas): void
    {
        $this->horasDiurnas = $horasDiurnas;
    }

    /**
     * @return mixed
     */
    public function getHorasDescanso()
    {
        return $this->horasDescanso;
    }

    /**
     * @param mixed $horasDescanso
     */
    public function setHorasDescanso($horasDescanso): void
    {
        $this->horasDescanso = $horasDescanso;
    }

    /**
     * @return mixed
     */
    public function getHorasNocturnas()
    {
        return $this->horasNocturnas;
    }

    /**
     * @param mixed $horasNocturnas
     */
    public function setHorasNocturnas($horasNocturnas): void
    {
        $this->horasNocturnas = $horasNocturnas;
    }

    /**
     * @return mixed
     */
    public function getHorasFestivasDiurnas()
    {
        return $this->horasFestivasDiurnas;
    }

    /**
     * @param mixed $horasFestivasDiurnas
     */
    public function setHorasFestivasDiurnas($horasFestivasDiurnas): void
    {
        $this->horasFestivasDiurnas = $horasFestivasDiurnas;
    }

    /**
     * @return mixed
     */
    public function getHorasFestivasNocturnas()
    {
        return $this->horasFestivasNocturnas;
    }

    /**
     * @param mixed $horasFestivasNocturnas
     */
    public function setHorasFestivasNocturnas($horasFestivasNocturnas): void
    {
        $this->horasFestivasNocturnas = $horasFestivasNocturnas;
    }

    /**
     * @return mixed
     */
    public function getHorasExtrasOrdinariasDiurnas()
    {
        return $this->horasExtrasOrdinariasDiurnas;
    }

    /**
     * @param mixed $horasExtrasOrdinariasDiurnas
     */
    public function setHorasExtrasOrdinariasDiurnas($horasExtrasOrdinariasDiurnas): void
    {
        $this->horasExtrasOrdinariasDiurnas = $horasExtrasOrdinariasDiurnas;
    }

    /**
     * @return mixed
     */
    public function getHorasExtrasOrdinariasNocturnas()
    {
        return $this->horasExtrasOrdinariasNocturnas;
    }

    /**
     * @param mixed $horasExtrasOrdinariasNocturnas
     */
    public function setHorasExtrasOrdinariasNocturnas($horasExtrasOrdinariasNocturnas): void
    {
        $this->horasExtrasOrdinariasNocturnas = $horasExtrasOrdinariasNocturnas;
    }

    /**
     * @return mixed
     */
    public function getHorasExtrasFestivasDiurnas()
    {
        return $this->horasExtrasFestivasDiurnas;
    }

    /**
     * @param mixed $horasExtrasFestivasDiurnas
     */
    public function setHorasExtrasFestivasDiurnas($horasExtrasFestivasDiurnas): void
    {
        $this->horasExtrasFestivasDiurnas = $horasExtrasFestivasDiurnas;
    }

    /**
     * @return mixed
     */
    public function getHorasExtrasFestivasNocturnas()
    {
        return $this->horasExtrasFestivasNocturnas;
    }

    /**
     * @param mixed $horasExtrasFestivasNocturnas
     */
    public function setHorasExtrasFestivasNocturnas($horasExtrasFestivasNocturnas): void
    {
        $this->horasExtrasFestivasNocturnas = $horasExtrasFestivasNocturnas;
    }

    /**
     * @return mixed
     */
    public function getHorasRecargo()
    {
        return $this->horasRecargo;
    }

    /**
     * @param mixed $horasRecargo
     */
    public function setHorasRecargo($horasRecargo): void
    {
        $this->horasRecargo = $horasRecargo;
    }

    /**
     * @return mixed
     */
    public function getHorasRecargoNocturno()
    {
        return $this->horasRecargoNocturno;
    }

    /**
     * @param mixed $horasRecargoNocturno
     */
    public function setHorasRecargoNocturno($horasRecargoNocturno): void
    {
        $this->horasRecargoNocturno = $horasRecargoNocturno;
    }

    /**
     * @return mixed
     */
    public function getHorasRecargoFestivoDiurno()
    {
        return $this->horasRecargoFestivoDiurno;
    }

    /**
     * @param mixed $horasRecargoFestivoDiurno
     */
    public function setHorasRecargoFestivoDiurno($horasRecargoFestivoDiurno): void
    {
        $this->horasRecargoFestivoDiurno = $horasRecargoFestivoDiurno;
    }

    /**
     * @return mixed
     */
    public function getHorasRecargoFestivoNocturno()
    {
        return $this->horasRecargoFestivoNocturno;
    }

    /**
     * @param mixed $horasRecargoFestivoNocturno
     */
    public function setHorasRecargoFestivoNocturno($horasRecargoFestivoNocturno): void
    {
        $this->horasRecargoFestivoNocturno = $horasRecargoFestivoNocturno;
    }

    /**
     * @return mixed
     */
    public function getDescuentoSalud()
    {
        return $this->descuentoSalud;
    }

    /**
     * @param mixed $descuentoSalud
     */
    public function setDescuentoSalud($descuentoSalud): void
    {
        $this->descuentoSalud = $descuentoSalud;
    }

    /**
     * @return mixed
     */
    public function getDescuentoPension()
    {
        return $this->descuentoPension;
    }

    /**
     * @param mixed $descuentoPension
     */
    public function setDescuentoPension($descuentoPension): void
    {
        $this->descuentoPension = $descuentoPension;
    }

    /**
     * @return mixed
     */
    public function getPagoAuxilioTransporte()
    {
        return $this->pagoAuxilioTransporte;
    }

    /**
     * @param mixed $pagoAuxilioTransporte
     */
    public function setPagoAuxilioTransporte($pagoAuxilioTransporte): void
    {
        $this->pagoAuxilioTransporte = $pagoAuxilioTransporte;
    }

    /**
     * @return mixed
     */
    public function getVrIbcAcumulado()
    {
        return $this->vrIbcAcumulado;
    }

    /**
     * @param mixed $vrIbcAcumulado
     */
    public function setVrIbcAcumulado($vrIbcAcumulado): void
    {
        $this->vrIbcAcumulado = $vrIbcAcumulado;
    }

    /**
     * @return mixed
     */
    public function getVrDeduccionFondoPensionAnterior()
    {
        return $this->vrDeduccionFondoPensionAnterior;
    }

    /**
     * @param mixed $vrDeduccionFondoPensionAnterior
     */
    public function setVrDeduccionFondoPensionAnterior($vrDeduccionFondoPensionAnterior): void
    {
        $this->vrDeduccionFondoPensionAnterior = $vrDeduccionFondoPensionAnterior;
    }

    /**
     * @return mixed
     */
    public function getFactorHorasDia()
    {
        return $this->factorHorasDia;
    }

    /**
     * @param mixed $factorHorasDia
     */
    public function setFactorHorasDia($factorHorasDia): void
    {
        $this->factorHorasDia = $factorHorasDia;
    }

    /**
     * @return mixed
     */
    public function getCodigoSoporteContratoFk()
    {
        return $this->codigoSoporteContratoFk;
    }

    /**
     * @param mixed $codigoSoporteContratoFk
     */
    public function setCodigoSoporteContratoFk($codigoSoporteContratoFk): void
    {
        $this->codigoSoporteContratoFk = $codigoSoporteContratoFk;
    }

    /**
     * @return mixed
     */
    public function getMarca()
    {
        return $this->marca;
    }

    /**
     * @param mixed $marca
     */
    public function setMarca($marca): void
    {
        $this->marca = $marca;
    }

    /**
     * @return mixed
     */
    public function getProgramacionRel()
    {
        return $this->programacionRel;
    }

    /**
     * @param mixed $programacionRel
     */
    public function setProgramacionRel($programacionRel): void
    {
        $this->programacionRel = $programacionRel;
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
    public function getPagosProgramacionDetalleRel()
    {
        return $this->pagosProgramacionDetalleRel;
    }

    /**
     * @param mixed $pagosProgramacionDetalleRel
     */
    public function setPagosProgramacionDetalleRel($pagosProgramacionDetalleRel): void
    {
        $this->pagosProgramacionDetalleRel = $pagosProgramacionDetalleRel;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioCesantia()
    {
        return $this->vrSalarioCesantia;
    }

    /**
     * @param mixed $vrSalarioCesantia
     */
    public function setVrSalarioCesantia($vrSalarioCesantia): void
    {
        $this->vrSalarioCesantia = $vrSalarioCesantia;
    }
}
