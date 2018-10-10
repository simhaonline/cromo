<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuContratoClaseRepository")
 */
class RhuContratoClase
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contrato_clase_pk", type="string", length=10)
     */
    private $codigoContratoClasePk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=200, nullable=true)
     */    
    private $nombre;                      
    
    /**     
     * @ORM\Column(name="indefinido", type="boolean")
     */    
    private $indefinido = false;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="contratoClaseRel")
     */
    protected $contratosContratoClaseRel;

    /**
     * @return mixed
     */
    public function getCodigoContratoClasePk()
    {
        return $this->codigoContratoClasePk;
    }

    /**
     * @param mixed $codigoContratoClasePk
     */
    public function setCodigoContratoClasePk($codigoContratoClasePk): void
    {
        $this->codigoContratoClasePk = $codigoContratoClasePk;
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
    public function getIndefinido()
    {
        return $this->indefinido;
    }

    /**
     * @param mixed $indefinido
     */
    public function setIndefinido($indefinido): void
    {
        $this->indefinido = $indefinido;
    }

    /**
     * @return mixed
     */
    public function getContratosContratoClaseRel()
    {
        return $this->contratosContratoClaseRel;
    }

    /**
     * @param mixed $contratosContratoClaseRel
     */
    public function setContratosContratoClaseRel($contratosContratoClaseRel): void
    {
        $this->contratosContratoClaseRel = $contratosContratoClaseRel;
    }

}
