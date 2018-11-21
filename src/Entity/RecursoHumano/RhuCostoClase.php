<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCostoClaseRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoCostoClasePk"},message="Ya existe el código del grupo")
 */

class RhuCostoClase
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_costo_clase_pk", type="string", length=10)
     */
    private $codigoCostoClasePk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=200, nullable=true)
     */    
    private $nombre;

    /**
     * @ORM\Column(name="operativo", type="boolean", nullable=true)
     */
    private $operativo = false;

    /**
     * @ORM\Column(name="orden", type="integer", nullable=true)
     */
    private $orden = 0;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato",mappedBy="costoClaseRel")
     */
    protected $contratosCostoClaseRel;

    /**
     * @return mixed
     */
    public function getCodigoCostoClasePk()
    {
        return $this->codigoCostoClasePk;
    }

    /**
     * @param mixed $codigoCostoClasePk
     */
    public function setCodigoCostoClasePk($codigoCostoClasePk): void
    {
        $this->codigoCostoClasePk = $codigoCostoClasePk;
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
    public function getOperativo()
    {
        return $this->operativo;
    }

    /**
     * @param mixed $operativo
     */
    public function setOperativo($operativo): void
    {
        $this->operativo = $operativo;
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
    public function getContratosCostoClaseRel()
    {
        return $this->contratosCostoClaseRel;
    }

    /**
     * @param mixed $contratosCostoClaseRel
     */
    public function setContratosCostoClaseRel($contratosCostoClaseRel): void
    {
        $this->contratosCostoClaseRel = $contratosCostoClaseRel;
    }
}
