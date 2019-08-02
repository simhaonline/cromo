<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuLiquidacionAdicionalRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuLiquidacionAdicional
{
    public $infoLog = [
        "primaryKey" => "codigoLiquidacionAdicionalPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_liquidacion_adicional_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLiquidacionAdicionalPk;

    /**
     * @ORM\Column(name="codigo_liquidacion_fk", type="integer", nullable=true)
     */
    private $codigoLiquidacionFk;

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
     * @ORM\Column(name="codigo_liquidacion_adicional_concepto_fk", type="integer", nullable=true)
     */
    private $codigoLiquidacionAdicionalConceptoFk;

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
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */
    private $codigoUsuario;

    /**
     * @ORM\ManyToOne(targetEntity="RhuLiquidacion", inversedBy="liquidacionesAdicionalesLiquidacionRel")
     * @ORM\JoinColumn(name="codigo_liquidacion_fk", referencedColumnName="codigo_liquidacion_pk")
     */
    protected $liquidacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCredito", inversedBy="liquidacionesAdicionalesCreditoRel")
     * @ORM\JoinColumn(name="codigo_credito_fk", referencedColumnName="codigo_credito_pk")
     */
    protected $creditoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmbargo", inversedBy="liquidacionesAdicionalesEmbargoRel")
     * @ORM\JoinColumn(name="codigo_embargo_fk", referencedColumnName="codigo_embargo_pk")
     */
    protected $embargoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuConcepto", inversedBy="liquidacionesAdicionalesConceptoRel")
     * @ORM\JoinColumn(name="codigo_concepto_fk", referencedColumnName="codigo_concepto_pk")
     */
    protected $conceptoRel;

    /**
     * @return array
     */
    public function getInfoLog(): array
    {
        return $this->infoLog;
    }

    /**
     * @param array $infoLog
     */
    public function setInfoLog(array $infoLog): void
    {
        $this->infoLog = $infoLog;
    }

    /**
     * @return mixed
     */
    public function getCodigoLiquidacionAdicionalPk()
    {
        return $this->codigoLiquidacionAdicionalPk;
    }

    /**
     * @param mixed $codigoLiquidacionAdicionalPk
     */
    public function setCodigoLiquidacionAdicionalPk($codigoLiquidacionAdicionalPk): void
    {
        $this->codigoLiquidacionAdicionalPk = $codigoLiquidacionAdicionalPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoLiquidacionFk()
    {
        return $this->codigoLiquidacionFk;
    }

    /**
     * @param mixed $codigoLiquidacionFk
     */
    public function setCodigoLiquidacionFk($codigoLiquidacionFk): void
    {
        $this->codigoLiquidacionFk = $codigoLiquidacionFk;
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
    public function getCodigoLiquidacionAdicionalConceptoFk()
    {
        return $this->codigoLiquidacionAdicionalConceptoFk;
    }

    /**
     * @param mixed $codigoLiquidacionAdicionalConceptoFk
     */
    public function setCodigoLiquidacionAdicionalConceptoFk($codigoLiquidacionAdicionalConceptoFk): void
    {
        $this->codigoLiquidacionAdicionalConceptoFk = $codigoLiquidacionAdicionalConceptoFk;
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
    public function getLiquidacionRel()
    {
        return $this->liquidacionRel;
    }

    /**
     * @param mixed $liquidacionRel
     */
    public function setLiquidacionRel($liquidacionRel): void
    {
        $this->liquidacionRel = $liquidacionRel;
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



}


