<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAporteRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuAporte
{
    public $infoLog = [
        "primaryKey" => "codigoAportePk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_aporte_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAportePk;

    /**
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio = 0;

    /**
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes = 0;

    /**
     * @ORM\Column(name="fecha_desde", type="date")
     */
    private $fechaDesde = 0;

    /**
     * @ORM\Column(name="fecha_hasta", type="date")
     */
    private $fechaHasta = 0;

    /**
     * @ORM\Column(name="codigo_sucursal_fk", type="string", length=10, nullable=true)
     */
    private $codigoSucursalFk;

    /**
     * @ORM\Column(name="cantidad_contratos", type="integer", nullable=true, options={"default" : 0})
     */
    private $cantidadContratos = 0;

    /**
     * @ORM\Column(name="cantidad_empleados", type="integer", nullable=true, options={"default" : 0})
     */
    private $cantidadEmpleados = 0;

    /**
     * @ORM\Column(name="total_cotizacion", type="float" , nullable=true)
     */
    private $totalCotizacion = 0;

    /**
     * @ORM\Column(name="estado_autorizado", options={"default" : false}, type="boolean")
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean", options={"default" : false}, nullable=true)
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoContabilizado = false;

    /**
     * @ORM\ManyToOne(targetEntity="RhuSucursal", inversedBy="aportesSucursalRel")
     * @ORM\JoinColumn(name="codigo_sucursal_fk",referencedColumnName="codigo_sucursal_pk")
     */
    protected $sucursalRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAporteContrato", mappedBy="aporteRel")
     */
    protected $aportesContratosAporteRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAporteDetalle", mappedBy="aporteRel")
     */
    protected $aportesDetallesAporteRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAporteSoporte", mappedBy="aporteRel")
     */
    protected $aportesSoportesAporteRel;

    /**
     * @return mixed
     */
    public function getCodigoAportePk()
    {
        return $this->codigoAportePk;
    }

    /**
     * @param mixed $codigoAportePk
     */
    public function setCodigoAportePk($codigoAportePk): void
    {
        $this->codigoAportePk = $codigoAportePk;
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

    /**
     * @return mixed
     */
    public function getAportesContratosAporteRel()
    {
        return $this->aportesContratosAporteRel;
    }

    /**
     * @param mixed $aportesContratosAporteRel
     */
    public function setAportesContratosAporteRel($aportesContratosAporteRel): void
    {
        $this->aportesContratosAporteRel = $aportesContratosAporteRel;
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
    public function getAportesDetallesAporteRel()
    {
        return $this->aportesDetallesAporteRel;
    }

    /**
     * @param mixed $aportesDetallesAporteRel
     */
    public function setAportesDetallesAporteRel($aportesDetallesAporteRel): void
    {
        $this->aportesDetallesAporteRel = $aportesDetallesAporteRel;
    }

    /**
     * @return mixed
     */
    public function getCantidadContratos()
    {
        return $this->cantidadContratos;
    }

    /**
     * @param mixed $cantidadContratos
     */
    public function setCantidadContratos($cantidadContratos): void
    {
        $this->cantidadContratos = $cantidadContratos;
    }

    /**
     * @return mixed
     */
    public function getCantidadEmpleados()
    {
        return $this->cantidadEmpleados;
    }

    /**
     * @param mixed $cantidadEmpleados
     */
    public function setCantidadEmpleados($cantidadEmpleados): void
    {
        $this->cantidadEmpleados = $cantidadEmpleados;
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
    public function getAportesSoportesAporteRel()
    {
        return $this->aportesSoportesAporteRel;
    }

    /**
     * @param mixed $aportesSoportesAporteRel
     */
    public function setAportesSoportesAporteRel($aportesSoportesAporteRel): void
    {
        $this->aportesSoportesAporteRel = $aportesSoportesAporteRel;
    }



}
