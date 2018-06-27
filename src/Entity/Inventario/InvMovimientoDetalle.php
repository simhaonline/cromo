<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_movimiento_detalle")
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvMovimientoDetalleRepository")
 */
class InvMovimientoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_movimiento_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoMovimientoDetallePk;

    /**
     * @ORM\Column(name="codigo_movimiento_fk", type="integer", nullable=true)
     */
    private $codigoMovimientoFk;

    /**
     * @ORM\Column(name="codigo_orden_compra_detalle_fk", type="integer", nullable=true)
     */
    private $codigoOrdenCompraDetalleFk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_item_fk", type="integer", nullable=true)
     */
    private $codigoItemFk;

    /**
     * @ORM\Column(name="codigo_bodega_fk", type="string", length=10, nullable=true)
     */
    private $codigoBodegaFk;

    /**
     * @ORM\Column(name="lote_fk", type="string", length=40, nullable=true)
     */
    private $loteFk;

    /**
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="por_iva", type="float", nullable=true)
     */
    private $porIva = 0;

    /**
     * @ORM\Column(name="por_descuento", type="float", nullable=true)
     */
    private $porDescuento = 0;

    /**
     * @ORM\Column(name="vr_iva", type="float", nullable=true)
     */
    private $vrIva = 0;

    /**
     * @ORM\Column(name="vr_unitario", type="float")
     */
    private $vrUnitario = 0;

    /**
     * @ORM\Column(name="vr_subtotal", type="float")
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="vr_descuento", type="float")
     */
    private $vrDescuento = 0;

    /**
     * @ORM\Column(name="vr_neto", type="float")
     */
    private $vrNeto = 0;

    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;

    /**
     * @ORM\ManyToOne(targetEntity="InvMovimiento", inversedBy="movimientosDetallesMovimientoRel")
     * @ORM\JoinColumn(name="codigo_movimiento_fk", referencedColumnName="codigo_movimiento_pk")
     */
    protected $movimientoRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="movimientosDetallesItemRel")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvOrdenCompraDetalle", inversedBy="movimientosDetallesOrdenCompraDetalleRel")
     * @ORM\JoinColumn(name="codigo_orden_compra_detalle_fk", referencedColumnName="codigo_orden_compra_detalle_pk")
     */
    protected $ordenCompraDetalleRel;

    /**
     * @return mixed
     */
    public function getCodigoMovimientoDetallePk()
    {
        return $this->codigoMovimientoDetallePk;
    }

    /**
     * @param mixed $codigoMovimientoDetallePk
     */
    public function setCodigoMovimientoDetallePk($codigoMovimientoDetallePk): void
    {
        $this->codigoMovimientoDetallePk = $codigoMovimientoDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoMovimientoFk()
    {
        return $this->codigoMovimientoFk;
    }

    /**
     * @param mixed $codigoMovimientoFk
     */
    public function setCodigoMovimientoFk($codigoMovimientoFk): void
    {
        $this->codigoMovimientoFk = $codigoMovimientoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoOrdenCompraDetalleFk()
    {
        return $this->codigoOrdenCompraDetalleFk;
    }

    /**
     * @param mixed $codigoOrdenCompraDetalleFk
     */
    public function setCodigoOrdenCompraDetalleFk($codigoOrdenCompraDetalleFk): void
    {
        $this->codigoOrdenCompraDetalleFk = $codigoOrdenCompraDetalleFk;
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
    public function getCodigoBodegaFk()
    {
        return $this->codigoBodegaFk;
    }

    /**
     * @param mixed $codigoBodegaFk
     */
    public function setCodigoBodegaFk($codigoBodegaFk): void
    {
        $this->codigoBodegaFk = $codigoBodegaFk;
    }

    /**
     * @return mixed
     */
    public function getLoteFk()
    {
        return $this->loteFk;
    }

    /**
     * @param mixed $loteFk
     */
    public function setLoteFk($loteFk): void
    {
        $this->loteFk = $loteFk;
    }

    /**
     * @return mixed
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param mixed $cantidad
     */
    public function setCantidad($cantidad): void
    {
        $this->cantidad = $cantidad;
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
    public function getPorDescuento()
    {
        return $this->porDescuento;
    }

    /**
     * @param mixed $porDescuento
     */
    public function setPorDescuento($porDescuento): void
    {
        $this->porDescuento = $porDescuento;
    }

    /**
     * @return mixed
     */
    public function getVrIva()
    {
        return $this->vrIva;
    }

    /**
     * @param mixed $vrIva
     */
    public function setVrIva($vrIva): void
    {
        $this->vrIva = $vrIva;
    }

    /**
     * @return mixed
     */
    public function getVrUnitario()
    {
        return $this->vrUnitario;
    }

    /**
     * @param mixed $vrUnitario
     */
    public function setVrUnitario($vrUnitario): void
    {
        $this->vrUnitario = $vrUnitario;
    }

    /**
     * @return mixed
     */
    public function getVrSubtotal()
    {
        return $this->vrSubtotal;
    }

    /**
     * @param mixed $vrSubtotal
     */
    public function setVrSubtotal($vrSubtotal): void
    {
        $this->vrSubtotal = $vrSubtotal;
    }

    /**
     * @return mixed
     */
    public function getVrDescuento()
    {
        return $this->vrDescuento;
    }

    /**
     * @param mixed $vrDescuento
     */
    public function setVrDescuento($vrDescuento): void
    {
        $this->vrDescuento = $vrDescuento;
    }

    /**
     * @return mixed
     */
    public function getVrNeto()
    {
        return $this->vrNeto;
    }

    /**
     * @param mixed $vrNeto
     */
    public function setVrNeto($vrNeto): void
    {
        $this->vrNeto = $vrNeto;
    }

    /**
     * @return mixed
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * @param mixed $vrTotal
     */
    public function setVrTotal($vrTotal): void
    {
        $this->vrTotal = $vrTotal;
    }

    /**
     * @return mixed
     */
    public function getMovimientoRel()
    {
        return $this->movimientoRel;
    }

    /**
     * @param mixed $movimientoRel
     */
    public function setMovimientoRel($movimientoRel): void
    {
        $this->movimientoRel = $movimientoRel;
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

    /**
     * @return mixed
     */
    public function getOrdenCompraDetalleRel()
    {
        return $this->ordenCompraDetalleRel;
    }

    /**
     * @param mixed $ordenCompraDetalleRel
     */
    public function setOrdenCompraDetalleRel($ordenCompraDetalleRel): void
    {
        $this->ordenCompraDetalleRel = $ordenCompraDetalleRel;
    }
}
