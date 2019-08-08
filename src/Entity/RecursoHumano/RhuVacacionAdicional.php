<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuVacacionAdicionalRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuVacacionAdicional
{
    public $infoLog = [
        "primaryKey" => "codigoVacacionAdicionalPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_vacacion_adicional_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoVacacionAdicionalPk;

    /**
     * @ORM\Column(name="codigo_vacacion_fk", type="integer", nullable=true)
     */
    private $codigoVacacionFk;

    /**
     * @ORM\Column(name="codigo_credito_fk", type="integer", nullable=true)
     */
    private $codigoCreditoFk;

    /**
     * @ORM\Column(name="codigo_embargo_fk", type="integer", nullable=true)
     */
    private $codigoEmbargoFk;

    /**
     * @ORM\Column(name="codigo_concepto_fk", type="string", length=10, nullable=true)
     */
    private $codigoConceptoFk;

    /**
     * @ORM\Column(name="vr_deduccion", type="float")
     */
    private $vrDeduccion = 0;

    /**
     * @ORM\Column(name="vr_bonificacion", type="float")
     */
    private $vrBonificacion = 0;

    /**
     * @ORM\Column(name="detalle", type="string", length=250, nullable=true)
     */
    private $detalle;

    /**
     * @ORM\ManyToOne(targetEntity="RhuVacacion", inversedBy="vacacionesAdicionalesVacacionRel")
     * @ORM\JoinColumn(name="codigo_vacacion_fk", referencedColumnName="codigo_vacacion_pk")
     */
    protected $vacacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuConcepto", inversedBy="vacacionesAdicionalesConceptoRel")
     * @ORM\JoinColumn(name="codigo_concepto_fk", referencedColumnName="codigo_concepto_pk")
     */
    protected $conceptoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCredito", inversedBy="vacacionesAdicionalesCreditoRel")
     * @ORM\JoinColumn(name="codigo_credito_fk", referencedColumnName="codigo_credito_pk")
     */
    protected $creditoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmbargo", inversedBy="vacacionesAdicionalesEmbargoRel")
     * @ORM\JoinColumn(name="codigo_embargo_fk", referencedColumnName="codigo_embargo_pk")
     */
    protected $embargoRel;

    /**
     * @return mixed
     */
    public function getCodigoVacacionAdicionalPk()
    {
        return $this->codigoVacacionAdicionalPk;
    }

    /**
     * @param mixed $codigoVacacionAdicionalPk
     */
    public function setCodigoVacacionAdicionalPk($codigoVacacionAdicionalPk): void
    {
        $this->codigoVacacionAdicionalPk = $codigoVacacionAdicionalPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoVacacionFk()
    {
        return $this->codigoVacacionFk;
    }

    /**
     * @param mixed $codigoVacacionFk
     */
    public function setCodigoVacacionFk($codigoVacacionFk): void
    {
        $this->codigoVacacionFk = $codigoVacacionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCreditoFk()
    {
        return $this->codigoCreditoFk;
    }

    /**
     * @param mixed $codigoCreditoFk
     */
    public function setCodigoCreditoFk($codigoCreditoFk): void
    {
        $this->codigoCreditoFk = $codigoCreditoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEmbargoFk()
    {
        return $this->codigoEmbargoFk;
    }

    /**
     * @param mixed $codigoEmbargoFk
     */
    public function setCodigoEmbargoFk($codigoEmbargoFk): void
    {
        $this->codigoEmbargoFk = $codigoEmbargoFk;
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
    public function getVrDeduccion()
    {
        return $this->vrDeduccion;
    }

    /**
     * @param mixed $vrDeduccion
     */
    public function setVrDeduccion($vrDeduccion): void
    {
        $this->vrDeduccion = $vrDeduccion;
    }

    /**
     * @return mixed
     */
    public function getVrBonificacion()
    {
        return $this->vrBonificacion;
    }

    /**
     * @param mixed $vrBonificacion
     */
    public function setVrBonificacion($vrBonificacion): void
    {
        $this->vrBonificacion = $vrBonificacion;
    }

    /**
     * @return mixed
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * @param mixed $detalle
     */
    public function setDetalle($detalle): void
    {
        $this->detalle = $detalle;
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
    public function getCreditoRel()
    {
        return $this->creditoRel;
    }

    /**
     * @param mixed $creditoRel
     */
    public function setCreditoRel($creditoRel): void
    {
        $this->creditoRel = $creditoRel;
    }

    /**
     * @return mixed
     */
    public function getEmbargoRel()
    {
        return $this->embargoRel;
    }

    /**
     * @param mixed $embargoRel
     */
    public function setEmbargoRel($embargoRel): void
    {
        $this->embargoRel = $embargoRel;
    }

    /**
     * @return mixed
     */
    public function getVacacionRel()
    {
        return $this->vacacionRel;
    }

    /**
     * @param mixed $vacacionRel
     */
    public function setVacacionRel($vacacionRel): void
    {
        $this->vacacionRel = $vacacionRel;
    }




}
