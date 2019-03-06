<?php

namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenTareaRepository")
 */
class GenTarea
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="codigo_tarea_pk", type="integer")
     */
    private $codigoTareaPk;

    /**
     * @ORM\Column(name="codigo_tarea_prioridad_fk", type="string", length=10, nullable=true)
     */
    private $codigoTareaPrioridadFk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="titulo", type="string", length=50, nullable=true)
     */
    private $titulo;

    /**
     * @ORM\Column(name="usuario_asigna", type="string", length=50, nullable=true)
     */
    private $usuarioAsigna;

    /**
     * @ORM\Column(name="usuario_recibe", type="string", length=50, nullable=true)
     */
    private $usuarioRecibe;

    /**
     * @ORM\Column(name="estado_terminado", type="boolean", options={"default":false}, nullable=true)
     */
    private $estadoTerminado = false;

    /**
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\ManyToOne(targetEntity="GenTareaPrioridad" ,inversedBy="tareasTareaPrioridadRel")
     * @ORM\JoinColumn(name="codigo_tarea_prioridad_fk", referencedColumnName="codigo_tarea_prioridad_pk")
     */
    protected $tareaPrioridadRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Seguridad\Usuario" ,inversedBy="genTareasUsuarioRecibeRel")
     * @ORM\JoinColumn(name="usuario_recibe", referencedColumnName="username")
     */
    protected $usuarioRecibeRel;

    /**
     * @return mixed
     */
    public function getCodigoTareaPk()
    {
        return $this->codigoTareaPk;
    }

    /**
     * @param mixed $codigoTareaPk
     */
    public function setCodigoTareaPk($codigoTareaPk): void
    {
        $this->codigoTareaPk = $codigoTareaPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoTareaPrioridadFk()
    {
        return $this->codigoTareaPrioridadFk;
    }

    /**
     * @param mixed $codigoTareaPrioridadFk
     */
    public function setCodigoTareaPrioridadFk($codigoTareaPrioridadFk): void
    {
        $this->codigoTareaPrioridadFk = $codigoTareaPrioridadFk;
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
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * @param mixed $titulo
     */
    public function setTitulo($titulo): void
    {
        $this->titulo = $titulo;
    }

    /**
     * @return mixed
     */
    public function getUsuarioAsigna()
    {
        return $this->usuarioAsigna;
    }

    /**
     * @param mixed $usuarioAsigna
     */
    public function setUsuarioAsigna($usuarioAsigna): void
    {
        $this->usuarioAsigna = $usuarioAsigna;
    }

    /**
     * @return mixed
     */
    public function getUsuarioRecibe()
    {
        return $this->usuarioRecibe;
    }

    /**
     * @param mixed $usuarioRecibe
     */
    public function setUsuarioRecibe($usuarioRecibe): void
    {
        $this->usuarioRecibe = $usuarioRecibe;
    }

    /**
     * @return mixed
     */
    public function getEstadoTerminado()
    {
        return $this->estadoTerminado;
    }

    /**
     * @param mixed $estadoTerminado
     */
    public function setEstadoTerminado($estadoTerminado): void
    {
        $this->estadoTerminado = $estadoTerminado;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return mixed
     */
    public function getTareaPrioridadRel()
    {
        return $this->tareaPrioridadRel;
    }

    /**
     * @param mixed $tareaPrioridadRel
     */
    public function setTareaPrioridadRel($tareaPrioridadRel): void
    {
        $this->tareaPrioridadRel = $tareaPrioridadRel;
    }

    /**
     * @return mixed
     */
    public function getUsuarioRecibeRel()
    {
        return $this->usuarioRecibeRel;
    }

    /**
     * @param mixed $usuarioRecibeRel
     */
    public function setUsuarioRecibeRel($usuarioRecibeRel): void
    {
        $this->usuarioRecibeRel = $usuarioRecibeRel;
    }



}
