<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarMovimientoClaseRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoMovimientoClasePk"},message="Ya existe un registro con el mismo codigo")
 */
class CarMovimientoClase
{
    public $infoLog = [
        "primaryKey" => "codigoMovimientoClasePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\Column(name="codigo_movimiento_clase_pk", type="string", length=10)
     */
    private $codigoMovimientoClasePk;

    /**
     * @ORM\Column(name="nombre" ,type="string")
     */
    private $nombre;

    /**
     * @ORM\Column(name="naturaleza", type="string", length=1, nullable=true)
     */
    private $naturaleza;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarMovimientoTipo" ,mappedBy="movimientoClaseRel")
     */
    private $movimientosTiposMovimientoClaseRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarMovimiento" ,mappedBy="movimientoClaseRel")
     */
    private $movimientosMovimientoClaseRel;

    /**
     * @return mixed
     */
    public function getCodigoMovimientoClasePk()
    {
        return $this->codigoMovimientoClasePk;
    }

    /**
     * @param mixed $codigoMovimientoClasePk
     */
    public function setCodigoMovimientoClasePk($codigoMovimientoClasePk): void
    {
        $this->codigoMovimientoClasePk = $codigoMovimientoClasePk;
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
    public function getNaturaleza()
    {
        return $this->naturaleza;
    }

    /**
     * @param mixed $naturaleza
     */
    public function setNaturaleza($naturaleza): void
    {
        $this->naturaleza = $naturaleza;
    }

    /**
     * @return mixed
     */
    public function getMovimientosTiposMovimientoClaseRel()
    {
        return $this->movimientosTiposMovimientoClaseRel;
    }

    /**
     * @param mixed $movimientosTiposMovimientoClaseRel
     */
    public function setMovimientosTiposMovimientoClaseRel($movimientosTiposMovimientoClaseRel): void
    {
        $this->movimientosTiposMovimientoClaseRel = $movimientosTiposMovimientoClaseRel;
    }

    /**
     * @return mixed
     */
    public function getMovimientosMovimientoClaseRel()
    {
        return $this->movimientosMovimientoClaseRel;
    }

    /**
     * @param mixed $movimientosMovimientoClaseRel
     */
    public function setMovimientosMovimientoClaseRel($movimientosMovimientoClaseRel): void
    {
        $this->movimientosMovimientoClaseRel = $movimientosMovimientoClaseRel;
    }



}
