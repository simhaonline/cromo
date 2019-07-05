<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAportePlanillaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuAportePlanilla
{
    public $infoLog = [
        "primaryKey" => "codigoAportePlanillaPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_aporte_planilla_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAportePlanillaPk;

    /**
     * @ORM\Column(name="codigo_aporte_fk", type="integer")
     */
    private $codigoAporteFk;

    /**
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio = 0;

    /**
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes = 0;

    /**
     * @ORM\Column(name="codigo_sucursal_fk", type="string", length=10, nullable=true)
     */
    private $codigoSucursalFk;

    /**
     * @ORM\Column(name="numero_contratos", type="integer", nullable=true)
     */
    private $numeroContratos = 0;

    /**
     * @ORM\Column(name="numero_empleados", type="integer", nullable=true)
     */
    private $numeroEmpleados = 0;

    /**
     * @ORM\Column(name="total", type="float" , nullable=true)
     */
    private $total = 0;

    /**
     * @ORM\ManyToOne(targetEntity="RhuAporte", inversedBy="aportesPlanillasAporteRel")
     * @ORM\JoinColumn(name="codigo_aporte_fk",referencedColumnName="codigo_aporte_pk")
     */
    protected $aporteRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuSucursal", inversedBy="aportesPlanillasSucursalRel")
     * @ORM\JoinColumn(name="codigo_sucursal_fk",referencedColumnName="codigo_sucursal_pk")
     */
    protected $sucursalRel;

    /**
     * @return mixed
     */
    public function getCodigoAportePlanillaPk()
    {
        return $this->codigoAportePlanillaPk;
    }

    /**
     * @param mixed $codigoAportePlanillaPk
     */
    public function setCodigoAportePlanillaPk($codigoAportePlanillaPk): void
    {
        $this->codigoAportePlanillaPk = $codigoAportePlanillaPk;
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
    public function getNumeroContratos()
    {
        return $this->numeroContratos;
    }

    /**
     * @param mixed $numeroContratos
     */
    public function setNumeroContratos($numeroContratos): void
    {
        $this->numeroContratos = $numeroContratos;
    }

    /**
     * @return mixed
     */
    public function getNumeroEmpleados()
    {
        return $this->numeroEmpleados;
    }

    /**
     * @param mixed $numeroEmpleados
     */
    public function setNumeroEmpleados($numeroEmpleados): void
    {
        $this->numeroEmpleados = $numeroEmpleados;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total): void
    {
        $this->total = $total;
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
