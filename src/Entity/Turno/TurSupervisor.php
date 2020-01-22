<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurSupervisorRepository")
 */
class TurSupervisor
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_supervisor_pk", type="string", length=20)
     */
    private $codigoSupervisorPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=true)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="correo", type="string", length=120, nullable=true)
     */
    private $correo;

    /**
     * @ORM\Column(name="celular", type="string", length=120, nullable=true)
     */
    private $celular;

    /**
     * @ORM\OneToMany(targetEntity="TurPuesto", mappedBy="supervisorRel")
     */
    protected $puestosSupervisorRel;

    /**
     * @return mixed
     */
    public function getCodigoSupervisorPk()
    {
        return $this->codigoSupervisorPk;
    }

    /**
     * @param mixed $codigoSupervisorPk
     */
    public function setCodigoSupervisorPk($codigoSupervisorPk): void
    {
        $this->codigoSupervisorPk = $codigoSupervisorPk;
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
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * @param mixed $numeroIdentificacion
     */
    public function setNumeroIdentificacion($numeroIdentificacion): void
    {
        $this->numeroIdentificacion = $numeroIdentificacion;
    }

    /**
     * @return mixed
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * @param mixed $correo
     */
    public function setCorreo($correo): void
    {
        $this->correo = $correo;
    }

    /**
     * @return mixed
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * @param mixed $celular
     */
    public function setCelular($celular): void
    {
        $this->celular = $celular;
    }

    /**
     * @return mixed
     */
    public function getPuestosSupervisorRel()
    {
        return $this->puestosSupervisorRel;
    }

    /**
     * @param mixed $puestosSupervisorRel
     */
    public function setPuestosSupervisorRel($puestosSupervisorRel): void
    {
        $this->puestosSupervisorRel = $puestosSupervisorRel;
    }



}