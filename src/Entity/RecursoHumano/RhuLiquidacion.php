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
        "todos"     => true,
    ];
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
}


