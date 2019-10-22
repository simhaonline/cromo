<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuDistribucionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoDistribucionPk"},message="Ya existe el código del distribucion")
 */
class RhuDistribucion
{
    public $infoLog = [
        "primaryKey" => "codigoDistribucionPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_distribucion_pk", type="string", length=10)
     */        
    private $codigoDistribucionPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\Column(name="estado_inactivo", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoInactivo= false;

    /**
     * @ORM\Column(name="orden", type="integer", nullable=true)
     */
    private $orden;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="distribucionRel")
     */
    protected $contratosDistribucionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurSoporteContrato", mappedBy="distribucionRel")
     */
    protected $soportesContratosDistribucionRel;

    /**
     * @return mixed
     */
    public function getCodigoDistribucionPk()
    {
        return $this->codigoDistribucionPk;
    }

    /**
     * @param mixed $codigoDistribucionPk
     */
    public function setCodigoDistribucionPk($codigoDistribucionPk): void
    {
        $this->codigoDistribucionPk = $codigoDistribucionPk;
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
    public function getEstadoInactivo()
    {
        return $this->estadoInactivo;
    }

    /**
     * @param mixed $estadoInactivo
     */
    public function setEstadoInactivo($estadoInactivo): void
    {
        $this->estadoInactivo = $estadoInactivo;
    }

    /**
     * @return mixed
     */
    public function getContratosDistribucionRel()
    {
        return $this->contratosDistribucionRel;
    }

    /**
     * @param mixed $contratosDistribucionRel
     */
    public function setContratosDistribucionRel($contratosDistribucionRel): void
    {
        $this->contratosDistribucionRel = $contratosDistribucionRel;
    }

    /**
     * @return mixed
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * @param mixed $orden
     */
    public function setOrden($orden): void
    {
        $this->orden = $orden;
    }

    /**
     * @return mixed
     */
    public function getSoportesContratosDistribucionRel()
    {
        return $this->soportesContratosDistribucionRel;
    }

    /**
     * @param mixed $soportesContratosDistribucionRel
     */
    public function setSoportesContratosDistribucionRel($soportesContratosDistribucionRel): void
    {
        $this->soportesContratosDistribucionRel = $soportesContratosDistribucionRel;
    }




}
