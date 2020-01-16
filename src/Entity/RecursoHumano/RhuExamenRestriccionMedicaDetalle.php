<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuExamenRestriccionMedicaDetalleRepository")
 */
class RhuExamenRestriccionMedicaDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_restriccion_medica_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenRestriccionMedicaDetallePk;
    
    /**
     * @ORM\Column(name="codigo_examen_restriccion_medica_fk", type="integer", nullable=true)
     */    
    private $codigoExamenRestriccionMedicaFk;
    
    /**
     * @ORM\Column(name="codigo_examen_restriccion_medica_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoExamenRestriccionMedicaTipoFk;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuExamenRestriccionMedicaTipo", inversedBy="examenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel")
     * @ORM\JoinColumn(name="codigo_examen_restriccion_medica_tipo_fk", referencedColumnName="codigo_examen_restriccion_medica_tipo_pk")
     */
    protected $examenRestriccionMedicaTipoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuExamenRestriccionMedica", inversedBy="examenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel")
     * @ORM\JoinColumn(name="codigo_examen_restriccion_medica_fk", referencedColumnName="codigo_examen_restriccion_medica_pk")
     */
    protected $examenRestriccionMedicaDetalleRel;

    /**
     * @return mixed
     */
    public function getCodigoExamenRestriccionMedicaDetallePk()
    {
        return $this->codigoExamenRestriccionMedicaDetallePk;
    }

    /**
     * @param mixed $codigoExamenRestriccionMedicaDetallePk
     */
    public function setCodigoExamenRestriccionMedicaDetallePk($codigoExamenRestriccionMedicaDetallePk): void
    {
        $this->codigoExamenRestriccionMedicaDetallePk = $codigoExamenRestriccionMedicaDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoExamenRestriccionMedicaFk()
    {
        return $this->codigoExamenRestriccionMedicaFk;
    }

    /**
     * @param mixed $codigoExamenRestriccionMedicaFk
     */
    public function setCodigoExamenRestriccionMedicaFk($codigoExamenRestriccionMedicaFk): void
    {
        $this->codigoExamenRestriccionMedicaFk = $codigoExamenRestriccionMedicaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoExamenRestriccionMedicaTipoFk()
    {
        return $this->codigoExamenRestriccionMedicaTipoFk;
    }

    /**
     * @param mixed $codigoExamenRestriccionMedicaTipoFk
     */
    public function setCodigoExamenRestriccionMedicaTipoFk($codigoExamenRestriccionMedicaTipoFk): void
    {
        $this->codigoExamenRestriccionMedicaTipoFk = $codigoExamenRestriccionMedicaTipoFk;
    }

    /**
     * @return mixed
     */
    public function getExamenRestriccionMedicaTipoRel()
    {
        return $this->examenRestriccionMedicaTipoRel;
    }

    /**
     * @param mixed $examenRestriccionMedicaTipoRel
     */
    public function setExamenRestriccionMedicaTipoRel($examenRestriccionMedicaTipoRel): void
    {
        $this->examenRestriccionMedicaTipoRel = $examenRestriccionMedicaTipoRel;
    }

    /**
     * @return mixed
     */
    public function getExamenRestriccionMedicaDetalleRel()
    {
        return $this->examenRestriccionMedicaDetalleRel;
    }

    /**
     * @param mixed $examenRestriccionMedicaDetalleRel
     */
    public function setExamenRestriccionMedicaDetalleRel($examenRestriccionMedicaDetalleRel): void
    {
        $this->examenRestriccionMedicaDetalleRel = $examenRestriccionMedicaDetalleRel;
    }

}
