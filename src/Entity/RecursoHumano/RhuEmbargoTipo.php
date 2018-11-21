<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuEmbargoTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoEmbargoTipoPk"},message="Ya existe el código del tipo")
 */
class RhuEmbargoTipo
{
    public $infoLog = [
        "primaryKey" => "codigoEmbargoTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_embargo_tipo_pk", type="string", length=10)
     */
    private $codigoEmbargoTipoPk;                              
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;                        
    
    /**
     * @ORM\Column(name="codigo_concepto_fk", type="string", length=10, nullable=true)
     */    
    private $codigoConceptoFk;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuConcepto", inversedBy="embargosTiposConceptoRel")
     * @ORM\JoinColumn(name="codigo_concepto_fk", referencedColumnName="codigo_concepto_pk")
     */
    protected $conceptoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmbargo", mappedBy="embargoTipoRel")
     */
    protected $embargosEmbargoTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoEmbargoTipoPk()
    {
        return $this->codigoEmbargoTipoPk;
    }

    /**
     * @param mixed $codigoEmbargoTipoPk
     */
    public function setCodigoEmbargoTipoPk($codigoEmbargoTipoPk): void
    {
        $this->codigoEmbargoTipoPk = $codigoEmbargoTipoPk;
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
    public function getCodigoConceptoFk()
    {
        return $this->codigoConceptoFk;
    }

    /**
     * @param mixed $codigoConceptoFk
     */
    public function setCodigoConceptoFk($codigoConceptoFk): void
    {
        $this->codigoConceptoFk = $codigoConceptoFk;
    }

    /**
     * @return mixed
     */
    public function getConceptoRel()
    {
        return $this->conceptoRel;
    }

    /**
     * @param mixed $conceptoRel
     */
    public function setConceptoRel($conceptoRel): void
    {
        $this->conceptoRel = $conceptoRel;
    }

    /**
     * @return mixed
     */
    public function getEmbargosEmbargoTipoRel()
    {
        return $this->embargosEmbargoTipoRel;
    }

    /**
     * @param mixed $embargosEmbargoTipoRel
     */
    public function setEmbargosEmbargoTipoRel($embargosEmbargoTipoRel): void
    {
        $this->embargosEmbargoTipoRel = $embargosEmbargoTipoRel;
    }
}
