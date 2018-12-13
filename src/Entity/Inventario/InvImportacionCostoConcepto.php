<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvImportacionCostoConceptoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoImportacionCostoConceptoPk"},message="Ya existe el código de la bodega")
 */
class InvImportacionCostoConcepto
{
    public $infoLog = [
        "primaryKey" => "codigoImportacionCostoConceptoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\Column(name="codigo_importacion_costo_concepto_pk" , type="string", length=10)
     */
    private $codigoImportacionCostoConceptoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="afecta_costo", type="boolean",options={"default" : false}, nullable=true)
     */
    private $afectaCosto = false;

    /**
     * @ORM\OneToMany(targetEntity="InvImportacionCosto", mappedBy="importacionCostoConceptoRel")
     */
    protected $importacionesCostosImportacionCostoConceptoRel;

    /**
     * @return mixed
     */
    public function getCodigoImportacionCostoConceptoPk()
    {
        return $this->codigoImportacionCostoConceptoPk;
    }

    /**
     * @param mixed $codigoImportacionCostoConceptoPk
     */
    public function setCodigoImportacionCostoConceptoPk($codigoImportacionCostoConceptoPk): void
    {
        $this->codigoImportacionCostoConceptoPk = $codigoImportacionCostoConceptoPk;
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
    public function getImportacionesCostosImportacionCostoConceptoRel()
    {
        return $this->importacionesCostosImportacionCostoConceptoRel;
    }

    /**
     * @param mixed $importacionesCostosImportacionCostoConceptoRel
     */
    public function setImportacionesCostosImportacionCostoConceptoRel($importacionesCostosImportacionCostoConceptoRel): void
    {
        $this->importacionesCostosImportacionCostoConceptoRel = $importacionesCostosImportacionCostoConceptoRel;
    }

    /**
     * @return mixed
     */
    public function getAfectaCosto()
    {
        return $this->afectaCosto;
    }

    /**
     * @param mixed $afectaCosto
     */
    public function setAfectaCosto($afectaCosto): void
    {
        $this->afectaCosto = $afectaCosto;
    }



}