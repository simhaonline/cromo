<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuExamenRestriccionMedicaTipoRepository")
 */
class RhuExamenRestriccionMedicaTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_restriccion_medica_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenRestriccionMedicaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;            
       
    /**
     * @ORM\OneToMany(targetEntity="RhuExamenRestriccionMedicaDetalle", mappedBy="examenRestriccionMedicaTipoRel")
     */
    protected $examenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoExamenRestriccionMedicaTipoPk()
    {
        return $this->codigoExamenRestriccionMedicaTipoPk;
    }

    /**
     * @param mixed $codigoExamenRestriccionMedicaTipoPk
     */
    public function setCodigoExamenRestriccionMedicaTipoPk($codigoExamenRestriccionMedicaTipoPk): void
    {
        $this->codigoExamenRestriccionMedicaTipoPk = $codigoExamenRestriccionMedicaTipoPk;
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
    public function getExamenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel()
    {
        return $this->examenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel;
    }

    /**
     * @param mixed $examenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel
     */
    public function setExamenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel($examenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel): void
    {
        $this->examenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel = $examenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel;
    }

}
