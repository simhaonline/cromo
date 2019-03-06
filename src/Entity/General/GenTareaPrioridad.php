<?php

namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenTareaPrioridadRepository")
 */
class GenTareaPrioridad
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_tarea_prioridad_pk", type="string", length=10, nullable=true)
     */
    private $codigoTareaPrioridadPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="icono", type="string", length=50, nullable=true)
     */
    private $icono;

    /**
     * @ORM\Column(name="color", type="string", length=50, nullable=true)
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity="GenTarea", mappedBy="tareaPrioridadRel")
     */
    protected $tareasTareaPrioridadRel;

    /**
     * @return mixed
     */
    public function getCodigoTareaPrioridadPk()
    {
        return $this->codigoTareaPrioridadPk;
    }

    /**
     * @param mixed $codigoTareaPrioridadPk
     */
    public function setCodigoTareaPrioridadPk($codigoTareaPrioridadPk): void
    {
        $this->codigoTareaPrioridadPk = $codigoTareaPrioridadPk;
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
    public function getIcono()
    {
        return $this->icono;
    }

    /**
     * @param mixed $icono
     */
    public function setIcono($icono): void
    {
        $this->icono = $icono;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color): void
    {
        $this->color = $color;
    }

    /**
     * @return mixed
     */
    public function getTareasTareaPrioridadRel()
    {
        return $this->tareasTareaPrioridadRel;
    }

    /**
     * @param mixed $tareasTareaPrioridadRel
     */
    public function setTareasTareaPrioridadRel($tareasTareaPrioridadRel): void
    {
        $this->tareasTareaPrioridadRel = $tareasTareaPrioridadRel;
    }



}
