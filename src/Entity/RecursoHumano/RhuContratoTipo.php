<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuContratoTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */

class RhuContratoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contrato_tipo_pk", type="string", length=10)
     */
    private $codigoContratoTipoPk;

    /**
     * @ORM\Column(name="codigo_contrato_clase_fk", type="string", length=10, nullable=true)
     */
    private $codigoContratoClaseFk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=200, nullable=true)
     */    
    private $nombre;

    /**
     * @ORM\Column(name="orden", type="integer", nullable=true)
     */
    private $orden;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato",mappedBy="contratoTipoRel")
     */
    protected $contratosContratoTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuContratoClase", inversedBy="contratosTiposContratoClaseRel")
     * @ORM\JoinColumn(name="codigo_contrato_clase_fk", referencedColumnName="codigo_contrato_clase_pk")
     */
    protected $contratoClaseRel;

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
    public function getCodigoContratoClaseFk()
    {
        return $this->codigoContratoClaseFk;
    }

    /**
     * @param mixed $codigoContratoClaseFk
     */
    public function setCodigoContratoClaseFk($codigoContratoClaseFk): void
    {
        $this->codigoContratoClaseFk = $codigoContratoClaseFk;
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
    public function getContratoClaseRel()
    {
        return $this->contratoClaseRel;
    }

    /**
     * @param mixed $contratoClaseRel
     */
    public function setContratoClaseRel($contratoClaseRel): void
    {
        $this->contratoClaseRel = $contratoClaseRel;
    }
}