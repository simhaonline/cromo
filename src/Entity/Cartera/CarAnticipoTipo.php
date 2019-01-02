<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarAnticipoTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"orden"},message="El orden ingresado ya existe")
 */
class CarAnticipoTipo
{
    public $infoLog = [
        "primaryKey" => "codigoAnticipoTipoPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=10, nullable=false, unique=true)
     */
    private $codigoAnticipoTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo", type="integer", nullable=true)
     */
    private $consecutivo = 0;

    /**
     * @ORM\Column(name="orden", type="integer", nullable=true, unique=true)
     */
    private $orden = 0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarAnticipo", mappedBy="anticipoTipoRel")
     */
    protected $anticiposAnticipoTipoRel;

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
    public function getCodigoAnticipoTipoPk()
    {
        return $this->codigoAnticipoTipoPk;
    }

    /**
     * @param mixed $codigoAnticipoTipoPk
     */
    public function setCodigoAnticipoTipoPk($codigoAnticipoTipoPk): void
    {
        $this->codigoAnticipoTipoPk = $codigoAnticipoTipoPk;
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
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }

    /**
     * @param mixed $consecutivo
     */
    public function setConsecutivo($consecutivo): void
    {
        $this->consecutivo = $consecutivo;
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

    /**
     * @return mixed
     */
    public function getAnticiposAnticipoTipoRel()
    {
        return $this->anticiposAnticipoTipoRel;
    }

    /**
     * @param mixed $anticiposAnticipoTipoRel
     */
    public function setAnticiposAnticipoTipoRel($anticiposAnticipoTipoRel): void
    {
        $this->anticiposAnticipoTipoRel = $anticiposAnticipoTipoRel;
    }



}
