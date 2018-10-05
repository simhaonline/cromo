<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenEstadoCivilRepository")
 */
class GenEstadoCivil
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_estado_civil_pk", type="string", length=10, nullable=true)
     */
    private $codigoEstadoCivilPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAspirante", mappedBy="estadoCivilRel")
     */
    protected $rhuAspirantesEstadoCivilRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", mappedBy="estadoCivilRel")
     */
    protected $rhuEmpleadosEstadoCivilRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", mappedBy="estadoCivilRel")
     */
    protected $rhuSolicitudesEstadoCivilRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="estadoCivilRel")
     */
    protected $rhuSeleccionesEstadoCivilRel;

    /**
     * @return mixed
     */
    public function getCodigoEstadoCivilPk()
    {
        return $this->codigoEstadoCivilPk;
    }

    /**
     * @param mixed $codigoEstadoCivilPk
     */
    public function setCodigoEstadoCivilPk($codigoEstadoCivilPk): void
    {
        $this->codigoEstadoCivilPk = $codigoEstadoCivilPk;
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
    public function getRhuAspirantesEstadoCivilRel()
    {
        return $this->rhuAspirantesEstadoCivilRel;
    }

    /**
     * @param mixed $rhuAspirantesEstadoCivilRel
     */
    public function setRhuAspirantesEstadoCivilRel($rhuAspirantesEstadoCivilRel): void
    {
        $this->rhuAspirantesEstadoCivilRel = $rhuAspirantesEstadoCivilRel;
    }

    /**
     * @return mixed
     */
    public function getRhuEmpleadosEstadoCivilRel()
    {
        return $this->rhuEmpleadosEstadoCivilRel;
    }

    /**
     * @param mixed $rhuEmpleadosEstadoCivilRel
     */
    public function setRhuEmpleadosEstadoCivilRel($rhuEmpleadosEstadoCivilRel): void
    {
        $this->rhuEmpleadosEstadoCivilRel = $rhuEmpleadosEstadoCivilRel;
    }

    /**
     * @return mixed
     */
    public function getRhuSolicitudesEstadoCivilRel()
    {
        return $this->rhuSolicitudesEstadoCivilRel;
    }

    /**
     * @param mixed $rhuSolicitudesEstadoCivilRel
     */
    public function setRhuSolicitudesEstadoCivilRel($rhuSolicitudesEstadoCivilRel): void
    {
        $this->rhuSolicitudesEstadoCivilRel = $rhuSolicitudesEstadoCivilRel;
    }

    /**
     * @return mixed
     */
    public function getRhuSeleccionesEstadoCivilRel()
    {
        return $this->rhuSeleccionesEstadoCivilRel;
    }

    /**
     * @param mixed $rhuSeleccionesEstadoCivilRel
     */
    public function setRhuSeleccionesEstadoCivilRel($rhuSeleccionesEstadoCivilRel): void
    {
        $this->rhuSeleccionesEstadoCivilRel = $rhuSeleccionesEstadoCivilRel;
    }
}

