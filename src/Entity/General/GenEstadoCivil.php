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
     * @ORM\Column(name="codigo_estado_civil_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEstadoCivilPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAspirante", mappedBy="genEstadoCivilRel")
     */
    protected $rhuAspirantesEstadoCivilRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", mappedBy="genEstadoCivilRel")
     */
    protected $rhuSolicitudesEstadoCivilRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="genEstadoCivilRel")
     */
    protected $rhuSeleccionEstadoCivilRel;

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
    public function getRhuSeleccionEstadoCivilRel()
    {
        return $this->rhuSeleccionEstadoCivilRel;
    }

    /**
     * @param mixed $rhuSeleccionEstadoCivilRel
     */
    public function setRhuSeleccionEstadoCivilRel($rhuSeleccionEstadoCivilRel): void
    {
        $this->rhuSeleccionEstadoCivilRel = $rhuSeleccionEstadoCivilRel;
    }


}

