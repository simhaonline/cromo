<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvImportacionCostoRepository")
 */
class InvImportacionCosto
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="codigo_importacion_costo_pk" , type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoImportacionCostoPk;

    /**
     * @ORM\Column(name="codigo_importacion_costo_concepto_fk", type="string", length=10, nullable=true)
     */
    private $codigoImportacionCostoConceptoFk;

    /**
     * @ORM\Column(name="codigo_importacion_fk" ,type="integer", nullable=true)
     */
    private $codigoImportacionFk;

    /**
     * @ORM\Column(name="vr_valor" ,type="float", nullable=true)
     */
    private $vrValor = 0;

    /**
     * @ORM\ManyToOne(targetEntity="InvImportacionCostoConcepto", inversedBy="importacionesCostosImportacionCostoConceptoRel")
     * @ORM\JoinColumn(name="codigo_importacion_costo_concepto_fk", referencedColumnName="codigo_importacion_costo_concepto_pk")
     */
    protected $importacionCostoConceptoRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvImportacion", inversedBy="importacionesCostosImportacionRel")
     * @ORM\JoinColumn(name="codigo_importacion_fk", referencedColumnName="codigo_importacion_pk")
     */
    protected $importacionRel;

    /**
     * @return mixed
     */
    public function getCodigoImportacionCostoPk()
    {
        return $this->codigoImportacionCostoPk;
    }

    /**
     * @param mixed $codigoImportacionCostoPk
     */
    public function setCodigoImportacionCostoPk($codigoImportacionCostoPk): void
    {
        $this->codigoImportacionCostoPk = $codigoImportacionCostoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoImportacionCostoConceptoFk()
    {
        return $this->codigoImportacionCostoConceptoFk;
    }

    /**
     * @param mixed $codigoImportacionCostoConceptoFk
     */
    public function setCodigoImportacionCostoConceptoFk($codigoImportacionCostoConceptoFk): void
    {
        $this->codigoImportacionCostoConceptoFk = $codigoImportacionCostoConceptoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoImportacionFk()
    {
        return $this->codigoImportacionFk;
    }

    /**
     * @param mixed $codigoImportacionFk
     */
    public function setCodigoImportacionFk($codigoImportacionFk): void
    {
        $this->codigoImportacionFk = $codigoImportacionFk;
    }

    /**
     * @return mixed
     */
    public function getVrValor()
    {
        return $this->vrValor;
    }

    /**
     * @param mixed $vrValor
     */
    public function setVrValor($vrValor): void
    {
        $this->vrValor = $vrValor;
    }

    /**
     * @return mixed
     */
    public function getImportacionCostoConceptoRel()
    {
        return $this->importacionCostoConceptoRel;
    }

    /**
     * @param mixed $importacionCostoConceptoRel
     */
    public function setImportacionCostoConceptoRel($importacionCostoConceptoRel): void
    {
        $this->importacionCostoConceptoRel = $importacionCostoConceptoRel;
    }

    /**
     * @return mixed
     */
    public function getImportacionRel()
    {
        return $this->importacionRel;
    }

    /**
     * @param mixed $importacionRel
     */
    public function setImportacionRel($importacionRel): void
    {
        $this->importacionRel = $importacionRel;
    }
}
