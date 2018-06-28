<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvPrecioDetalleRepository")
 */
class InvPrecioDetalle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoPrecioDetallePk;

    /**
     * @ORM\Column(name="codigo_precio_fk", type="integer", nullable=true)
     */
    private $codigoPrecioFk;

    /**
     * @ORM\Column(name="codigo_item_fk", type="integer")
     */
    private $codigoItemFk;

    /**
     * @ORM\Column(name="precio",options={"default" : 0}, type="float")
     */
    private $precio = 0;

    /**
     * @ORM\ManyToOne(targetEntity="InvPrecio", inversedBy="preciosDetallesPrecioRel")
     * @ORM\JoinColumn(name="codigo_precio_fk", referencedColumnName="codigo_precio_pk")
     */
    protected $precioRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="preciosDetallesItemRel")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel;

    /**
     * @return mixed
     */
    public function getCodigoPrecioDetallePk()
    {
        return $this->codigoPrecioDetallePk;
    }

    /**
     * @param mixed $codigoPrecioDetallePk
     */
    public function setCodigoPrecioDetallePk($codigoPrecioDetallePk): void
    {
        $this->codigoPrecioDetallePk = $codigoPrecioDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPrecioFk()
    {
        return $this->codigoPrecioFk;
    }

    /**
     * @param mixed $codigoPrecioFk
     */
    public function setCodigoPrecioFk($codigoPrecioFk): void
    {
        $this->codigoPrecioFk = $codigoPrecioFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoItemFk()
    {
        return $this->codigoItemFk;
    }

    /**
     * @param mixed $codigoItemFk
     */
    public function setCodigoItemFk($codigoItemFk): void
    {
        $this->codigoItemFk = $codigoItemFk;
    }

    /**
     * @return mixed
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * @param mixed $precio
     */
    public function setPrecio($precio): void
    {
        $this->precio = $precio;
    }

    /**
     * @return mixed
     */
    public function getPrecioRel()
    {
        return $this->precioRel;
    }

    /**
     * @param mixed $precioRel
     */
    public function setPrecioRel($precioRel): void
    {
        $this->precioRel = $precioRel;
    }

    /**
     * @return mixed
     */
    public function getItemRel()
    {
        return $this->itemRel;
    }

    /**
     * @param mixed $itemRel
     */
    public function setItemRel($itemRel): void
    {
        $this->itemRel = $itemRel;
    }



}

