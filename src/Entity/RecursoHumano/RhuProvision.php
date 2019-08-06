<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuProvisionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuProvision
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_provision_periodo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProvisionPk;
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;

    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */    
    private $anio;    
    
    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */    
    private $mes;    
    
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
     * @ORM\Column(name="estado_autorizado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAnulado = false;

    /**
     * @return mixed
     */
    public function getCodigoProvisionPk()
    {
        return $this->codigoProvisionPk;
    }

    /**
     * @param mixed $codigoProvisionPk
     */
    public function setCodigoProvisionPk($codigoProvisionPk): void
    {
        $this->codigoProvisionPk = $codigoProvisionPk;
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
