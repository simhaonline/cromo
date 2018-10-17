<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteMonitoreoDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteMonitoreoDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoMonitoreoDetallePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoMonitoreoDetallePk;

    /**
     * @ORM\Column(name="codigo_monitoreo_fk", type="integer", nullable=true)
     */
    private $codigoMonitoreoFk;

    /**
     * @ORM\Column(name="fecha_registro", type="datetime", nullable=true)
     */
    private $fechaRegistro;

    /**
     * @ORM\Column(name="fecha_reporte", type="datetime", nullable=true)
     */
    private $fechaReporte;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteMonitoreo", inversedBy="monitoreosDetallesMonitoreoRel")
     * @ORM\JoinColumn(name="codigo_monitoreo_fk", referencedColumnName="codigo_monitoreo_pk")
     */
    private $monitoreoRel;

    /**
     * @return mixed
     */
    public function getCodigoMonitoreoDetallePk()
    {
        return $this->codigoMonitoreoDetallePk;
    }

    /**
     * @param mixed $codigoMonitoreoDetallePk
     */
    public function setCodigoMonitoreoDetallePk($codigoMonitoreoDetallePk): void
    {
        $this->codigoMonitoreoDetallePk = $codigoMonitoreoDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoMonitoreoFk()
    {
        return $this->codigoMonitoreoFk;
    }

    /**
     * @param mixed $codigoMonitoreoFk
     */
    public function setCodigoMonitoreoFk($codigoMonitoreoFk): void
    {
        $this->codigoMonitoreoFk = $codigoMonitoreoFk;
    }

    /**
     * @return mixed
     */
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }

    /**
     * @param mixed $fechaRegistro
     */
    public function setFechaRegistro($fechaRegistro): void
    {
        $this->fechaRegistro = $fechaRegistro;
    }

    /**
     * @return mixed
     */
    public function getFechaReporte()
    {
        return $this->fechaReporte;
    }

    /**
     * @param mixed $fechaReporte
     */
    public function setFechaReporte($fechaReporte): void
    {
        $this->fechaReporte = $fechaReporte;
    }

    /**
     * @return mixed
     */
    public function getMonitoreoRel()
    {
        return $this->monitoreoRel;
    }

    /**
     * @param mixed $monitoreoRel
     */
    public function setMonitoreoRel($monitoreoRel): void
    {
        $this->monitoreoRel = $monitoreoRel;
    }

    /**
     * @return mixed
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * @param mixed $comentario
     */
    public function setComentario($comentario): void
    {
        $this->comentario = $comentario;
    }



}

