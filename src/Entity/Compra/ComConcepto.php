<?php

namespace App\Entity\Compra;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Compra\ComConceptoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoConceptoPk"},message="Ya existe un registro con el mismo codigo")
 */
class ComConcepto
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="codigo_concepto_pk", type="string" , length=10)
     */
    private $codigoConceptoPk;

    /**
     * @ORM\Column(name="nombre" , type="string")
     */
    private $nombre;

    /**
     * @ORM\Column(name="por_iva" ,type="float")
     */
    private $porIva;

    /**
     * @ORM\Column(name="codigo_concepto_tipo_fk", type="string" , length=10, nullable=true)
     */
    private $codigoConceptoTipoFk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compra\ComConceptoTipo", inversedBy="conceptosTipoRel")
     * @ORM\JoinColumn(name="codigo_concepto_tipo_fk", referencedColumnName="codigo_concepto_tipo_pk" )
     */
    private $conceptoTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compra\ComCompraDetalle" , mappedBy="conceptoRel")
     */
    private $comprasDetallesConceptoRel;

    /**
     * @return mixed
     */
    public function getCodigoConceptoPk()
    {
        return $this->codigoConceptoPk;
    }

    /**
     * @param mixed $codigoConceptoPk
     */
    public function setCodigoConceptoPk($codigoConceptoPk): void
    {
        $this->codigoConceptoPk = $codigoConceptoPk;
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
    public function getPorIva()
    {
        return $this->porIva;
    }

    /**
     * @param mixed $porIva
     */
    public function setPorIva($porIva): void
    {
        $this->porIva = $porIva;
    }

    /**
     * @return mixed
     */
    public function getCodigoConceptoTipoFk()
    {
        return $this->codigoConceptoTipoFk;
    }

    /**
     * @param mixed $codigoConceptoTipoFk
     */
    public function setCodigoConceptoTipoFk($codigoConceptoTipoFk): void
    {
        $this->codigoConceptoTipoFk = $codigoConceptoTipoFk;
    }

    /**
     * @return mixed
     */
    public function getConceptoTipoRel()
    {
        return $this->conceptoTipoRel;
    }

    /**
     * @param mixed $conceptoTipoRel
     */
    public function setConceptoTipoRel($conceptoTipoRel): void
    {
        $this->conceptoTipoRel = $conceptoTipoRel;
    }

    /**
     * @return mixed
     */
    public function getComprasDetallesConceptoRel()
    {
        return $this->comprasDetallesConceptoRel;
    }

    /**
     * @param mixed $comprasDetallesConceptoRel
     */
    public function setComprasDetallesConceptoRel($comprasDetallesConceptoRel): void
    {
        $this->comprasDetallesConceptoRel = $comprasDetallesConceptoRel;
    }

}
