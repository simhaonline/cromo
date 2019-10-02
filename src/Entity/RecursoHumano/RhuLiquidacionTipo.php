<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuLiquidacionTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoLiquidacionTipoPk"},message="Ya existe el código")
 *
 */
class RhuLiquidacionTipo
{
    public $infoLog = [
        "primaryKey" => "codigoLiquidacionTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_liquidacion_tipo_pk", type="string", length=10)
     */
    private $codigoLiquidacionTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo", type="integer", nullable=true, options={"default":1})
     */
    private $consecutivo;

    /**
     * @ORM\Column(name="codigo_concepto_cesantia_fk", type="string", length=10, nullable=true)
     */
    private $codigoConceptoCesantiaFk;

    /**
     * @ORM\Column(name="codigo_concepto_interes_fk", type="string", length=10, nullable=true)
     */
    private $codigoConceptoInteresFk;

    /**
     * @ORM\Column(name="codigo_concepto_cesantia_anterior_fk", type="string", length=10, nullable=true)
     */
    private $codigoConceptoCesantiaAnteriorFk;

    /**
     * @ORM\Column(name="codigo_concepto_interes_anterior_fk", type="string", length=10, nullable=true)
     */
    private $codigoConceptoInteresAnteriorFk;

    /**
     * @ORM\Column(name="codigo_concepto_prima_fk", type="string", length=10, nullable=true)
     */
    private $codigoConceptoPrimaFk;

    /**
     * @ORM\Column(name="codigo_concepto_vacacion_fk", type="string", length=10, nullable=true)
     */
    private $codigoConceptoVacacionFk;

    /**
     * @ORM\Column(name="codigo_concepto_indemnizacion_fk", type="string", length=10, nullable=true)
     */
    private $codigoConceptoIndemnizacionFk;

    /**
     * @ORM\OneToMany(targetEntity="RhuLiquidacion", mappedBy="liquidacionTipoRel")
     */
    protected $liquidacionesLiquidacionTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoLiquidacionTipoPk()
    {
        return $this->codigoLiquidacionTipoPk;
    }

    /**
     * @param mixed $codigoLiquidacionTipoPk
     */
    public function setCodigoLiquidacionTipoPk($codigoLiquidacionTipoPk): void
    {
        $this->codigoLiquidacionTipoPk = $codigoLiquidacionTipoPk;
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
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }

    /**
     * @param mixed $consecutivo
     */
    public function setConsecutivo($consecutivo): void
    {
        $this->consecutivo = $consecutivo;
    }

    /**
     * @return mixed
     */
    public function getCodigoConceptoCesantiaFk()
    {
        return $this->codigoConceptoCesantiaFk;
    }

    /**
     * @param mixed $codigoConceptoCesantiaFk
     */
    public function setCodigoConceptoCesantiaFk($codigoConceptoCesantiaFk): void
    {
        $this->codigoConceptoCesantiaFk = $codigoConceptoCesantiaFk;
    }

    /**
     * @return mixed
     */
    public function getLiquidacionesLiquidacionTipoRel()
    {
        return $this->liquidacionesLiquidacionTipoRel;
    }

    /**
     * @param mixed $liquidacionesLiquidacionTipoRel
     */
    public function setLiquidacionesLiquidacionTipoRel($liquidacionesLiquidacionTipoRel): void
    {
        $this->liquidacionesLiquidacionTipoRel = $liquidacionesLiquidacionTipoRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoConceptoInteresFk()
    {
        return $this->codigoConceptoInteresFk;
    }

    /**
     * @param mixed $codigoConceptoInteresFk
     */
    public function setCodigoConceptoInteresFk($codigoConceptoInteresFk): void
    {
        $this->codigoConceptoInteresFk = $codigoConceptoInteresFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoConceptoCesantiaAnteriorFk()
    {
        return $this->codigoConceptoCesantiaAnteriorFk;
    }

    /**
     * @param mixed $codigoConceptoCesantiaAnteriorFk
     */
    public function setCodigoConceptoCesantiaAnteriorFk($codigoConceptoCesantiaAnteriorFk): void
    {
        $this->codigoConceptoCesantiaAnteriorFk = $codigoConceptoCesantiaAnteriorFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoConceptoInteresAnteriorFk()
    {
        return $this->codigoConceptoInteresAnteriorFk;
    }

    /**
     * @param mixed $codigoConceptoInteresAnteriorFk
     */
    public function setCodigoConceptoInteresAnteriorFk($codigoConceptoInteresAnteriorFk): void
    {
        $this->codigoConceptoInteresAnteriorFk = $codigoConceptoInteresAnteriorFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoConceptoPrimaFk()
    {
        return $this->codigoConceptoPrimaFk;
    }

    /**
     * @param mixed $codigoConceptoPrimaFk
     */
    public function setCodigoConceptoPrimaFk($codigoConceptoPrimaFk): void
    {
        $this->codigoConceptoPrimaFk = $codigoConceptoPrimaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoConceptoVacacionFk()
    {
        return $this->codigoConceptoVacacionFk;
    }

    /**
     * @param mixed $codigoConceptoVacacionFk
     */
    public function setCodigoConceptoVacacionFk($codigoConceptoVacacionFk): void
    {
        $this->codigoConceptoVacacionFk = $codigoConceptoVacacionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoConceptoIndemnizacionFk()
    {
        return $this->codigoConceptoIndemnizacionFk;
    }

    /**
     * @param mixed $codigoConceptoIndemnizacionFk
     */
    public function setCodigoConceptoIndemnizacionFk($codigoConceptoIndemnizacionFk): void
    {
        $this->codigoConceptoIndemnizacionFk = $codigoConceptoIndemnizacionFk;
    }
    



}