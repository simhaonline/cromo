<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuRhRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuRh
{
    public $infoLog = [
        "primaryKey" => "codigoRhPk",
        "todos"     => true,
    ];
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
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteConductor", mappedBy="rhRel")
     */
    protected $tteConductorRhRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAspirante", mappedBy="rhRel")
     */
    protected $rhuAspirantesRhRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="rhRel")
     */
    protected $seleccionesRhRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", mappedBy="rhRel")
     */
    protected $empleadosRhRel;

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
    public function getTteConductorRhRel()
    {
        return $this->tteConductorRhRel;
    }

    /**
     * @param mixed $tteConductorRhRel
     */
    public function setTteConductorRhRel($tteConductorRhRel): void
    {
        $this->tteConductorRhRel = $tteConductorRhRel;
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
    public function getSeleccionesRhRel()
    {
        return $this->seleccionesRhRel;
    }

    /**
     * @param mixed $seleccionesRhRel
     */
    public function setSeleccionesRhRel($seleccionesRhRel): void
    {
        $this->seleccionesRhRel = $seleccionesRhRel;
    }

    /**
     * @return mixed
     */
    public function getEmpleadosRhRel()
    {
        return $this->empleadosRhRel;
    }

    /**
     * @param mixed $empleadosRhRel
     */
    public function setEmpleadosRhRel($empleadosRhRel): void
    {
        $this->empleadosRhRel = $empleadosRhRel;
    }



}
