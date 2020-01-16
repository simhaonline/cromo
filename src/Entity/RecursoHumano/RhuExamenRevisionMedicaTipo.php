<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuExamenRevisionMedicaTipoRepository")
 */
class RhuExamenRevisionMedicaTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_revision_medica_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenRevisionMedicaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;            
       
    /**
     * @ORM\OneToMany(targetEntity="RhuExamenRestriccionMedica", mappedBy="examenRevisionMedicaTipoRel")
     */
    protected $examenesRestriccionesMedicasExamenRevisionMedicaTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoExamenRevisionMedicaTipoPk()
    {
        return $this->codigoExamenRevisionMedicaTipoPk;
    }

    /**
     * @param mixed $codigoExamenRevisionMedicaTipoPk
     */
    public function setCodigoExamenRevisionMedicaTipoPk($codigoExamenRevisionMedicaTipoPk): void
    {
        $this->codigoExamenRevisionMedicaTipoPk = $codigoExamenRevisionMedicaTipoPk;
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
    public function getExamenesRestriccionesMedicasExamenRevisionMedicaTipoRel()
    {
        return $this->examenesRestriccionesMedicasExamenRevisionMedicaTipoRel;
    }

    /**
     * @param mixed $examenesRestriccionesMedicasExamenRevisionMedicaTipoRel
     */
    public function setExamenesRestriccionesMedicasExamenRevisionMedicaTipoRel($examenesRestriccionesMedicasExamenRevisionMedicaTipoRel): void
    {
        $this->examenesRestriccionesMedicasExamenRevisionMedicaTipoRel = $examenesRestriccionesMedicasExamenRevisionMedicaTipoRel;
    }
    
    

}
