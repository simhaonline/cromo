<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuExamenRestriccionMedicaRepository")
 */
class RhuExamenRestriccionMedica
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_restriccion_medica_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenRestriccionMedicaPk;

    /**
     * @ORM\Column(name="codigo_examen_fk", type="integer", nullable=true)
     */
    private $codigoExamenFk;

    /**
     * @ORM\Column(name="codigo_examen_revision_medica_tipo_fk", type="integer", nullable=true)
     */
    private $codigoExamenRevisionMedicaTipoFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */

    private $fecha;

    /**
     * @ORM\Column(name="dias", type="string", length=3, nullable=true)
     */
    private $dias;

    /**
     * @ORM\Column(name="fecha_vence", type="date", nullable=true)
     */

    private $fechaVence;


    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */
    private $codigoUsuario;

    /**
     * @ORM\ManyToOne(targetEntity="RhuExamen", inversedBy="examenesExamenRestriccionMedicaRel")
     * @ORM\JoinColumn(name="codigo_examen_fk", referencedColumnName="codigo_examen_pk")
     */
    protected $examenRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuExamenRevisionMedicaTipo", inversedBy="examenesRestriccionesMedicasExamenRevisionMedicaTipoRel")
     * @ORM\JoinColumn(name="codigo_examen_revision_medica_tipo_fk", referencedColumnName="codigo_examen_revision_medica_tipo_pk")
     */
    protected $examenRevisionMedicaTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuExamenRestriccionMedicaDetalle", mappedBy="examenRestriccionMedicaDetalleRel")
     */
    protected $examenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel;

    /**
     * @return mixed
     */
    public function getCodigoExamenRestriccionMedicaPk()
    {
        return $this->codigoExamenRestriccionMedicaPk;
    }

    /**
     * @param mixed $codigoExamenRestriccionMedicaPk
     */
    public function setCodigoExamenRestriccionMedicaPk($codigoExamenRestriccionMedicaPk): void
    {
        $this->codigoExamenRestriccionMedicaPk = $codigoExamenRestriccionMedicaPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoExamenFk()
    {
        return $this->codigoExamenFk;
    }

    /**
     * @param mixed $codigoExamenFk
     */
    public function setCodigoExamenFk($codigoExamenFk): void
    {
        $this->codigoExamenFk = $codigoExamenFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoExamenRevisionMedicaTipoFk()
    {
        return $this->codigoExamenRevisionMedicaTipoFk;
    }

    /**
     * @param mixed $codigoExamenRevisionMedicaTipoFk
     */
    public function setCodigoExamenRevisionMedicaTipoFk($codigoExamenRevisionMedicaTipoFk): void
    {
        $this->codigoExamenRevisionMedicaTipoFk = $codigoExamenRevisionMedicaTipoFk;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * @param mixed $dias
     */
    public function setDias($dias): void
    {
        $this->dias = $dias;
    }

    /**
     * @return mixed
     */
    public function getFechaVence()
    {
        return $this->fechaVence;
    }

    /**
     * @param mixed $fechaVence
     */
    public function setFechaVence($fechaVence): void
    {
        $this->fechaVence = $fechaVence;
    }

    /**
     * @return mixed
     */
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * @param mixed $codigoUsuario
     */
    public function setCodigoUsuario($codigoUsuario): void
    {
        $this->codigoUsuario = $codigoUsuario;
    }

    /**
     * @return mixed
     */
    public function getExamenRel()
    {
        return $this->examenRel;
    }

    /**
     * @param mixed $examenRel
     */
    public function setExamenRel($examenRel): void
    {
        $this->examenRel = $examenRel;
    }

    /**
     * @return mixed
     */
    public function getExamenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel()
    {
        return $this->examenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel;
    }

    /**
     * @param mixed $examenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel
     */
    public function setExamenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel($examenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel): void
    {
        $this->examenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel = $examenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel;
    }

    /**
     * @return mixed
     */
    public function getExamenRevisionMedicaTipoRel()
    {
        return $this->examenRevisionMedicaTipoRel;
    }

    /**
     * @param mixed $examenRevisionMedicaTipoRel
     */
    public function setExamenRevisionMedicaTipoRel($examenRevisionMedicaTipoRel): void
    {
        $this->examenRevisionMedicaTipoRel = $examenRevisionMedicaTipoRel;
    }


}
