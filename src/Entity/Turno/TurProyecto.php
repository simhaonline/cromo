<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurProyectoRepository")
 */
class TurProyecto
{

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_proyecto_pk", type="string", length=20)
     */
    private $codigoProyectoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurPuesto", mappedBy="proyectoRel")
     */
    protected $puestosProyectoRel;

    /**
     * @return mixed
     */
    public function getCodigoProyectoPk()
    {
        return $this->codigoProyectoPk;
    }

    /**
     * @param mixed $codigoProyectoPk
     */
    public function setCodigoProyectoPk($codigoProyectoPk): void
    {
        $this->codigoProyectoPk = $codigoProyectoPk;
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
    public function getPuestosProyectoRel()
    {
        return $this->puestosProyectoRel;
    }

    /**
     * @param mixed $puestosProyectoRel
     */
    public function setPuestosProyectoRel($puestosProyectoRel): void
    {
        $this->puestosProyectoRel = $puestosProyectoRel;
    }



}