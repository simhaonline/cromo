<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuProvisionDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuProvisionDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_provision_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProvisionDetallePk;

    /**
     * @ORM\Column(name="codigo_provision_periodo_fk", type="integer", nullable=false)
     */
    private $codigoProvisionPeriodoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */
    private $anio;

    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */
    private $mes;

    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="vr_pension", type="float")
     */
    private $vrPension = 0;

    /**
     * @ORM\Column(name="vr_salud", type="float")
     */
    private $vrSalud = 0;

    /**
     * @ORM\Column(name="vr_riesgos", type="float")
     */
    private $vrRiesgos = 0;

    /**
     * @ORM\Column(name="vr_caja", type="float")
     */
    private $vrCaja = 0;

    /**
     * @ORM\Column(name="vr_sena", type="float")
     */
    private $vrSena = 0;

    /**
     * @ORM\Column(name="vr_icbf", type="float")
     */
    private $vrIcbf = 0;

    /**
     * @ORM\Column(name="vr_cesantias", type="float")
     */
    private $vrCesantias = 0;

    /**
     * @ORM\Column(name="vr_intereses_cesantias", type="float")
     */
    private $vrInteresesCesantias = 0;

    /**
     * @ORM\Column(name="vr_vacaciones", type="float")
     */
    private $vrVacaciones = 0;

    /**
     * @ORM\Column(name="vr_primas", type="float")
     */
    private $vrPrimas = 0;

    /**
     * @ORM\Column(name="vr_indemnizacion", type="float")
     */
    private $vrIndemnizacion = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_cotizacion", type="float")
     */
    private $vrIngresoBaseCotizacion = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion", type="float")
     */
    private $vrIngresoBasePrestacion = 0;

    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $vrSalario = 0;

    /**
     * @ORM\Column(name="vr_salud_aporte", type="float", nullable=true)
     */
    private $vrSaludAporte = 0;

    /**
     * @ORM\Column(name="vr_salud_descuento", type="float", nullable=true)
     */
    private $vrSaludDescuento = 0;

    /**
     * @ORM\Column(name="vr_salud_mayor_descuento", type="float", nullable=true)
     */
    private $vrSaludMayorDescuento = 0;

    /**
     * @ORM\Column(name="vr_pension_aporte", type="float", nullable=true)
     */
    private $vrPensionAporte = 0;

    /**
     * @ORM\Column(name="vr_pension_descuento", type="float", nullable=true)
     */
    private $vrPensionDescuento = 0;

    /**
     * @ORM\Column(name="vr_pension_mayor_descuento", type="float", nullable=true)
     */
    private $vrPensionMayorDescuento = 0;

    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean")
     */
    private $estadoContabilizado = 0;

    /**
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */
    private $estadoCerrado = 0;

    /**
     * @return mixed
     */
    public function getCodigoProvisionDetallePk()
    {
        return $this->codigoProvisionDetallePk;
    }

    /**
     * @param mixed $codigoProvisionDetallePk
     */
    public function setCodigoProvisionDetallePk($codigoProvisionDetallePk): void
    {
        $this->codigoProvisionDetallePk = $codigoProvisionDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoProvisionPeriodoFk()
    {
        return $this->codigoProvisionPeriodoFk;
    }

    /**
     * @param mixed $codigoProvisionPeriodoFk
     */
    public function setCodigoProvisionPeriodoFk($codigoProvisionPeriodoFk): void
    {
        $this->codigoProvisionPeriodoFk = $codigoProvisionPeriodoFk;
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
    public function getVrPension()
    {
        return $this->vrPension;
    }

    /**
     * @param mixed $vrPension
     */
    public function setVrPension($vrPension): void
    {
        $this->vrPension = $vrPension;
    }

    /**
     * @return mixed
     */
    public function getVrSalud()
    {
        return $this->vrSalud;
    }

    /**
     * @param mixed $vrSalud
     */
    public function setVrSalud($vrSalud): void
    {
        $this->vrSalud = $vrSalud;
    }

    /**
     * @return mixed
     */
    public function getVrRiesgos()
    {
        return $this->vrRiesgos;
    }

    /**
     * @param mixed $vrRiesgos
     */
    public function setVrRiesgos($vrRiesgos): void
    {
        $this->vrRiesgos = $vrRiesgos;
    }

    /**
     * @return mixed
     */
    public function getVrCaja()
    {
        return $this->vrCaja;
    }

    /**
     * @param mixed $vrCaja
     */
    public function setVrCaja($vrCaja): void
    {
        $this->vrCaja = $vrCaja;
    }

    /**
     * @return mixed
     */
    public function getVrSena()
    {
        return $this->vrSena;
    }

    /**
     * @param mixed $vrSena
     */
    public function setVrSena($vrSena): void
    {
        $this->vrSena = $vrSena;
    }

    /**
     * @return mixed
     */
    public function getVrIcbf()
    {
        return $this->vrIcbf;
    }

    /**
     * @param mixed $vrIcbf
     */
    public function setVrIcbf($vrIcbf): void
    {
        $this->vrIcbf = $vrIcbf;
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
    public function getVrPrimas()
    {
        return $this->vrPrimas;
    }

    /**
     * @param mixed $vrPrimas
     */
    public function setVrPrimas($vrPrimas): void
    {
        $this->vrPrimas = $vrPrimas;
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
    public function getVrIngresoBasePrestacion()
    {
        return $this->vrIngresoBasePrestacion;
    }

    /**
     * @param mixed $vrIngresoBasePrestacion
     */
    public function setVrIngresoBasePrestacion($vrIngresoBasePrestacion): void
    {
        $this->vrIngresoBasePrestacion = $vrIngresoBasePrestacion;
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
    public function getVrSaludAporte()
    {
        return $this->vrSaludAporte;
    }

    /**
     * @param mixed $vrSaludAporte
     */
    public function setVrSaludAporte($vrSaludAporte): void
    {
        $this->vrSaludAporte = $vrSaludAporte;
    }

    /**
     * @return mixed
     */
    public function getVrSaludDescuento()
    {
        return $this->vrSaludDescuento;
    }

    /**
     * @param mixed $vrSaludDescuento
     */
    public function setVrSaludDescuento($vrSaludDescuento): void
    {
        $this->vrSaludDescuento = $vrSaludDescuento;
    }

    /**
     * @return mixed
     */
    public function getVrSaludMayorDescuento()
    {
        return $this->vrSaludMayorDescuento;
    }

    /**
     * @param mixed $vrSaludMayorDescuento
     */
    public function setVrSaludMayorDescuento($vrSaludMayorDescuento): void
    {
        $this->vrSaludMayorDescuento = $vrSaludMayorDescuento;
    }

    /**
     * @return mixed
     */
    public function getVrPensionAporte()
    {
        return $this->vrPensionAporte;
    }

    /**
     * @param mixed $vrPensionAporte
     */
    public function setVrPensionAporte($vrPensionAporte): void
    {
        $this->vrPensionAporte = $vrPensionAporte;
    }

    /**
     * @return mixed
     */
    public function getVrPensionDescuento()
    {
        return $this->vrPensionDescuento;
    }

    /**
     * @param mixed $vrPensionDescuento
     */
    public function setVrPensionDescuento($vrPensionDescuento): void
    {
        $this->vrPensionDescuento = $vrPensionDescuento;
    }

    /**
     * @return mixed
     */
    public function getVrPensionMayorDescuento()
    {
        return $this->vrPensionMayorDescuento;
    }

    /**
     * @param mixed $vrPensionMayorDescuento
     */
    public function setVrPensionMayorDescuento($vrPensionMayorDescuento): void
    {
        $this->vrPensionMayorDescuento = $vrPensionMayorDescuento;
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
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }

    /**
     * @param mixed $estadoCerrado
     */
    public function setEstadoCerrado($estadoCerrado): void
    {
        $this->estadoCerrado = $estadoCerrado;
    }


}
