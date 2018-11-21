<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCreditoTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoCreditoTipoPk"},message="Ya existe el código del tipo")
 */
class RhuCreditoTipo
{
    public $infoLog = [
        "primaryKey" => "codigoCreditoTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_credito_tipo_pk", type="string", length=10)
     */
    private $codigoCreditoTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="cupo_maximo", type="integer", nullable=true)
     */
    private $cupoMaximo;

    /**
     * @ORM\Column(name="codigo_concepto_fk", type="string", length=10, nullable=true)
     */
    private $codigoConceptoFk;

    /**
     * @ORM\ManyToOne(targetEntity="RhuConcepto", inversedBy="creditosTiposConceptoRel")
     * @ORM\JoinColumn(name="codigo_concepto_fk", referencedColumnName="codigo_concepto_pk")
     */
    protected $conceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuCredito", mappedBy="creditoTipoRel")
     */
    protected $creditosCreditoTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoCreditoTipoPk()
    {
        return $this->codigoCreditoTipoPk;
    }

    /**
     * @param mixed $codigoCreditoTipoPk
     */
    public function setCodigoCreditoTipoPk($codigoCreditoTipoPk): void
    {
        $this->codigoCreditoTipoPk = $codigoCreditoTipoPk;
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
    public function getCupoMaximo()
    {
        return $this->cupoMaximo;
    }

    /**
     * @param mixed $cupoMaximo
     */
    public function setCupoMaximo($cupoMaximo): void
    {
        $this->cupoMaximo = $cupoMaximo;
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
    public function getCreditosCreditoTipoRel()
    {
        return $this->creditosCreditoTipoRel;
    }

    /**
     * @param mixed $creditosCreditoTipoRel
     */
    public function setCreditosCreditoTipoRel($creditosCreditoTipoRel): void
    {
        $this->creditosCreditoTipoRel = $creditosCreditoTipoRel;
    }
}
