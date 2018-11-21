<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuTiempoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuTiempo
{
    public $infoLog = [
        "primaryKey" => "codigoTiempoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_tiempo_pk", type="string", length=10)
     */
    private $codigoTiempoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;      

    /**
     * @ORM\Column(name="factor", type="integer", nullable=true)
     */    
    private $factor = 0;    
    
    /**
     * @ORM\Column(name="factor_horas_dia", type="integer", nullable=true)
     */    
    private $factorHorasDia = 8;

    /**
     * @ORM\Column(name="orden", type="integer", nullable=true)
     */
    private $orden;
    
    /**
     * @ORM\Column(name="abreviatura", type="string", length=1, nullable=true)
     */    
    private $abreviatura;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="tiempoRel")
     */
    protected $contratosTiempoRel;

    /**
     * @return mixed
     */
    public function getCodigoTiempoPk()
    {
        return $this->codigoTiempoPk;
    }

    /**
     * @param mixed $codigoTiempoPk
     */
    public function setCodigoTiempoPk($codigoTiempoPk): void
    {
        $this->codigoTiempoPk = $codigoTiempoPk;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getFactor()
    {
        return $this->factor;
    }

    /**
     * @param mixed $factor
     */
    public function setFactor($factor): void
    {
        $this->factor = $factor;
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
    public function getAbreviatura()
    {
        return $this->abreviatura;
    }

    /**
     * @param mixed $abreviatura
     */
    public function setAbreviatura($abreviatura): void
    {
        $this->abreviatura = $abreviatura;
    }

    /**
     * @return mixed
     */
    public function getContratosTiempoRel()
    {
        return $this->contratosTiempoRel;
    }

    /**
     * @param mixed $contratosTiempoRel
     */
    public function setContratosTiempoRel($contratosTiempoRel): void
    {
        $this->contratosTiempoRel = $contratosTiempoRel;
    }

    /**
     * @return mixed
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * @param mixed $orden
     */
    public function setOrden($orden): void
    {
        $this->orden = $orden;
    }
}
