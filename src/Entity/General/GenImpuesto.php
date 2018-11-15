<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenImpuestoRepository")
 */
class GenImpuesto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_impuesto_pk", type="string", length=3, nullable=false)
     */
    private $codigoImpuestoPk;

    /**
     * @ORM\Column(name="codigo_impuesto_tipo_fk", type="string", length=3, nullable=true)
     */
    private $codigoImpuestoTipoFk;

    /**
     * @ORM\Column(name="nombre", type="string", length=30, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="porcentaje", type="float", nullable=true, options={"default" : 0})
     */
    private $porcentaje = 0;

    /**
     * @ORM\Column(name="base", type="float", nullable=true, options={"default" : 0})
     */
    private $base = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenImpuestoTipo", inversedBy="impuestosImpuestoTipoRel")
     * @ORM\JoinColumn(name="codigo_impuesto_tipo_fk", referencedColumnName="codigo_impuesto_tipo_pk")
     */
    protected $impuestoTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvItem", mappedBy="impuestoRetencionRel")
     */
    private $itemsImpuestoRetencionRel;

    /**
     * @return mixed
     */
    public function getCodigoImpuestoPk()
    {
        return $this->codigoImpuestoPk;
    }

    /**
     * @param mixed $codigoImpuestoPk
     */
    public function setCodigoImpuestoPk($codigoImpuestoPk): void
    {
        $this->codigoImpuestoPk = $codigoImpuestoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoImpuestoTipoFk()
    {
        return $this->codigoImpuestoTipoFk;
    }

    /**
     * @param mixed $codigoImpuestoTipoFk
     */
    public function setCodigoImpuestoTipoFk($codigoImpuestoTipoFk): void
    {
        $this->codigoImpuestoTipoFk = $codigoImpuestoTipoFk;
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
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * @param mixed $porcentaje
     */
    public function setPorcentaje($porcentaje): void
    {
        $this->porcentaje = $porcentaje;
    }

    /**
     * @return mixed
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * @param mixed $base
     */
    public function setBase($base): void
    {
        $this->base = $base;
    }

    /**
     * @return mixed
     */
    public function getImpuestoTipoRel()
    {
        return $this->impuestoTipoRel;
    }

    /**
     * @param mixed $impuestoTipoRel
     */
    public function setImpuestoTipoRel($impuestoTipoRel): void
    {
        $this->impuestoTipoRel = $impuestoTipoRel;
    }

    /**
     * @return mixed
     */
    public function getItemsImpuestoRetencionRel()
    {
        return $this->itemsImpuestoRetencionRel;
    }

    /**
     * @param mixed $itemsImpuestoRetencionRel
     */
    public function setItemsImpuestoRetencionRel($itemsImpuestoRetencionRel): void
    {
        $this->itemsImpuestoRetencionRel = $itemsImpuestoRetencionRel;
    }


}

