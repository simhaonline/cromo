<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurCoordinadorRepository")
 */
class TurCoordinador
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_coordinador_pk", type="string", length=20)
     */
    private $codigoCoordinadorPk;

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
     * @ORM\OneToMany(targetEntity="TurPuesto", mappedBy="coordinadorRel")
     */
    protected $puestosCoordinadorRel;

    /**
     * @return mixed
     */
    public function getCodigoCoordinadorPk()
    {
        return $this->codigoCoordinadorPk;
    }

    /**
     * @param mixed $codigoCoordinadorPk
     */
    public function setCodigoCoordinadorPk($codigoCoordinadorPk): void
    {
        $this->codigoCoordinadorPk = $codigoCoordinadorPk;
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
    public function getPuestosCoordinadorRel()
    {
        return $this->puestosCoordinadorRel;
    }

    /**
     * @param mixed $puestosCoordinadorRel
     */
    public function setPuestosCoordinadorRel($puestosCoordinadorRel): void
    {
        $this->puestosCoordinadorRel = $puestosCoordinadorRel;
    }


}