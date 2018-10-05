<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuRhRepository")
 */
class RhuRh
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_rh_pk", type="string", length=10)
     */        
    private $codigoRhPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAspirante", mappedBy="rhRel")
     */
    protected $rhuAspirantesRhRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="rhRel")
     */
    protected $rhuSeleccionesRhRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", mappedBy="rhRel")
     */
    protected $rhuEmpleadosRhRel;

    /**
     * @return mixed
     */
    public function getCodigoRhPk()
    {
        return $this->codigoRhPk;
    }

    /**
     * @param mixed $codigoRhPk
     */
    public function setCodigoRhPk($codigoRhPk): void
    {
        $this->codigoRhPk = $codigoRhPk;
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
    public function getRhuAspirantesRhRel()
    {
        return $this->rhuAspirantesRhRel;
    }

    /**
     * @param mixed $rhuAspirantesRhRel
     */
    public function setRhuAspirantesRhRel($rhuAspirantesRhRel): void
    {
        $this->rhuAspirantesRhRel = $rhuAspirantesRhRel;
    }

    /**
     * @return mixed
     */
    public function getRhuSeleccionesRhRel()
    {
        return $this->rhuSeleccionesRhRel;
    }

    /**
     * @param mixed $rhuSeleccionesRhRel
     */
    public function setRhuSeleccionesRhRel($rhuSeleccionesRhRel): void
    {
        $this->rhuSeleccionesRhRel = $rhuSeleccionesRhRel;
    }

    /**
     * @return mixed
     */
    public function getRhuEmpleadosRhRel()
    {
        return $this->rhuEmpleadosRhRel;
    }

    /**
     * @param mixed $rhuEmpleadosRhRel
     */
    public function setRhuEmpleadosRhRel($rhuEmpleadosRhRel): void
    {
        $this->rhuEmpleadosRhRel = $rhuEmpleadosRhRel;
    }
}
