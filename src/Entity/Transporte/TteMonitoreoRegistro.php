<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteMonitoreoRegistroRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteMonitoreoRegistro
{
    public $infoLog = [
        "primaryKey" => "codigoMonitoreoRegistroPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoMonitoreoRegistroPk;

    /**
     * @ORM\Column(name="codigo_monitoreo_fk", type="integer", nullable=true)
     */
    private $codigoMonitoreoFk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="latitud", type="float", nullable=true, options={"default":0})
     */
    private $latitud = 0;

    /**
     * @ORM\Column(name="longitud", type="float", nullable=true, options={"default":0})
     */
    private $longitud = 0;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteMonitoreo", inversedBy="monitoreosRegistrosMonitoreoRel")
     * @ORM\JoinColumn(name="codigo_monitoreo_fk", referencedColumnName="codigo_monitoreo_pk")
     */
    private $monitoreoRel;

    /**
     * @return mixed
     */
    public function getCodigoMonitoreoRegistroPk()
    {
        return $this->codigoMonitoreoRegistroPk;
    }

    /**
     * @param mixed $codigoMonitoreoRegistroPk
     */
    public function setCodigoMonitoreoRegistroPk($codigoMonitoreoRegistroPk): void
    {
        $this->codigoMonitoreoRegistroPk = $codigoMonitoreoRegistroPk;
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
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * @param mixed $latitud
     */
    public function setLatitud($latitud): void
    {
        $this->latitud = $latitud;
    }

    /**
     * @return mixed
     */
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * @param mixed $longitud
     */
    public function setLongitud($longitud): void
    {
        $this->longitud = $longitud;
    }



}

