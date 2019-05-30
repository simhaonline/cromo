<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuEntidadExamenRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuEntidadExamen
{
    public $infoLog = [
        "primaryKey" => "codigoEntidadExamenPk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_entidad_examen_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEntidadExamenPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="nit", type="string", length=20, nullable=true)
     */
    private $nit;

    /**
     * @ORM\Column(name="direccion", type="string", length=120, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="telefono", type="string", length=80, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\OneToMany(targetEntity="RhuExamen", mappedBy="entidadExamenRel")
     */
    protected $examenesEntidadExamenRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuExamenListaPrecio", mappedBy="entidadExamenRel")
     */
    protected $examenListaPreciosEntidadExamenRel;

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
    public function getCodigoEntidadExamenPk()
    {
        return $this->codigoEntidadExamenPk;
    }

    /**
     * @param mixed $codigoEntidadExamenPk
     */
    public function setCodigoEntidadExamenPk($codigoEntidadExamenPk): void
    {
        $this->codigoEntidadExamenPk = $codigoEntidadExamenPk;
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
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * @param mixed $nit
     */
    public function setNit($nit): void
    {
        $this->nit = $nit;
    }

    /**
     * @return mixed
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param mixed $direccion
     */
    public function setDireccion($direccion): void
    {
        $this->direccion = $direccion;
    }

    /**
     * @return mixed
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param mixed $telefono
     */
    public function setTelefono($telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
    }

    /**
     * @return mixed
     */
    public function getExamenesEntidadExamenRel()
    {
        return $this->examenesEntidadExamenRel;
    }

    /**
     * @param mixed $examenesEntidadExamenRel
     */
    public function setExamenesEntidadExamenRel($examenesEntidadExamenRel): void
    {
        $this->examenesEntidadExamenRel = $examenesEntidadExamenRel;
    }

    /**
     * @return mixed
     */
    public function getExamenListaPreciosEntidadExamenRel()
    {
        return $this->examenListaPreciosEntidadExamenRel;
    }

    /**
     * @param mixed $examenListaPreciosEntidadExamenRel
     */
    public function setExamenListaPreciosEntidadExamenRel($examenListaPreciosEntidadExamenRel): void
    {
        $this->examenListaPreciosEntidadExamenRel = $examenListaPreciosEntidadExamenRel;
    }
}
