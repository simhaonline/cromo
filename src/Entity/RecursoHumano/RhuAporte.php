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



}
