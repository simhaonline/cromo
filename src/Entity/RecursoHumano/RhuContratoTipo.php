<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuContratoTipoRepository")
 */

class RhuContratoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contrato_tipo_pk", type="string", length=10)
     */
    private $codigoContratoTipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=200, nullable=true)
     */    
    private $nombre;

    /**
     * @ORM\Column(name="indefinido", type="boolean", nullable=true)
     */
    private $indefinido = false;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato",mappedBy="contratoTipoRel")
     */
    protected $contratosContratoTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoContratoTipoPk()
    {
        return $this->codigoContratoTipoPk;
    }

    /**
     * @param mixed $codigoContratoTipoPk
     */
    public function setCodigoContratoTipoPk($codigoContratoTipoPk): void
    {
        $this->codigoContratoTipoPk = $codigoContratoTipoPk;
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
    public function getContratosContratoTipoRel()
    {
        return $this->contratosContratoTipoRel;
    }

    /**
     * @param mixed $contratosContratoTipoRel
     */
    public function setContratosContratoTipoRel($contratosContratoTipoRel): void
    {
        $this->contratosContratoTipoRel = $contratosContratoTipoRel;
    }
}
