<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Table(name="rhu_reclamo_concepto")
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuReclamoConceptoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoReclamoConceptoPk"},message="Ya existe el código del concepto")
 */
class RhuReclamoConcepto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_reclamo_concepto_pk", type="string", length=10)
     */
    private $codigoReclamoConceptoPk;                    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;        
    
    /**
     * @ORM\OneToMany(targetEntity="RhuReclamo", mappedBy="reclamoConceptoRel")
     */
    protected $reclamosReclamoConceptoRel;

    /**
     * @return mixed
     */
    public function getCodigoReclamoConceptoPk()
    {
        return $this->codigoReclamoConceptoPk;
    }

    /**
     * @param mixed $codigoReclamoConceptoPk
     */
    public function setCodigoReclamoConceptoPk($codigoReclamoConceptoPk): void
    {
        $this->codigoReclamoConceptoPk = $codigoReclamoConceptoPk;
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
    public function getReclamosReclamoConceptoRel()
    {
        return $this->reclamosReclamoConceptoRel;
    }

    /**
     * @param mixed $reclamosReclamoConceptoRel
     */
    public function setReclamosReclamoConceptoRel($reclamosReclamoConceptoRel): void
    {
        $this->reclamosReclamoConceptoRel = $reclamosReclamoConceptoRel;
    }
}
