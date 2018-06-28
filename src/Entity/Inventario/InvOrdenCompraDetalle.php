<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvOrdenCompraDetalleRepository")
 */
class InvOrdenCompraDetalle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="codigo_orden_compra_detalle_pk",type="integer")
     */
    private $codigoOrdenCompraDetallePk;

    /**
     * @ORM\Column(name="codigo_solicitud_detalle_fk", type="integer", nullable=true)
     */
    private $codigoSolicitudDetalleFk;

    /**
     * @ORM\Column(name="codigo_orden_compra_fk", type="integer", nullable=true)
     */
    private $codigoOrdenCompraFk;

    /**
     * @ORM\Column(name="codigo_item_fk", type="integer", nullable=true)
     */
    private $codigoItemFk;

    /**
     * @ORM\Column(name="cantidad", type="integer", nullable=true)
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="vr_precio", type="float", nullable=true)
     */
    private $vrPrecio = 0;

    /**
     * @ORM\Column(name="porcentaje_descuento", type="float", nullable=true)
     */
    private $porcentajeDescuento = 0;

    /**
     * @ORM\Column(name="vr_descuento", type="float", nullable=true)
     */
    private $vrDescuento = 0;

    /**
     * @ORM\Column(name="vr_subtotal", type="float", nullable=true)
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="porcentaje_iva", type="integer", nullable=true)
     */
    private $porcentajeIva = 0;

    /**
     * @ORM\Column(name="vr_iva", type="float", nullable=true)
     */
    private $vrIva = 0;

    /**
     * @ORM\Column(name="vr_neto", type="float")
     */
    private $vrNeto = 0;

    /**
     * @ORM\Column(name="vr_total", type="float", nullable=true)
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="cantidad_pendiente", type="integer", nullable=true)
     */
    private $cantidadPendiente = 0;

    /**
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="ordenesComprasDetallesItemRel")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvOrdenCompra", inversedBy="ordenesComprasDetallesOrdenCompraRel")
     * @ORM\JoinColumn(name="codigo_orden_compra_fk", referencedColumnName="codigo_orden_compra_pk")
     */
    protected $ordenCompraRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvSolicitudDetalle", inversedBy="ordenesComprasDetallesSolicitudDetalleRel")
     * @ORM\JoinColumn(name="codigo_solicitud_detalle_fk", referencedColumnName="codigo_solicitud_detalle_pk")
     */
    protected $solicitudDetalleRel;

    /**
     * @ORM\OneToMany(targetEntity="InvMovimientoDetalle", mappedBy="ordenCompraDetalleRel")
     */
    protected $movimientosDetallesOrdenCompraDetalleRel;

    /**
     * @return mixed
     */
    public function getCodigoOrdenCompraDetallePk()
    {
        return $this->codigoOrdenCompraDetallePk;
    }

    /**
     * @param mixed $codigoOrdenCompraDetallePk
     */
    public function setCodigoOrdenCompraDetallePk($codigoOrdenCompraDetallePk): void
    {
        $this->codigoOrdenCompraDetallePk = $codigoOrdenCompraDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSolicitudDetalleFk()
    {
        return $this->codigoSolicitudDetalleFk;
    }

    /**
     * @param mixed $codigoSolicitudDetalleFk
     */
    public function setCodigoSolicitudDetalleFk($codigoSolicitudDetalleFk): void
    {
        $this->codigoSolicitudDetalleFk = $codigoSolicitudDetalleFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoOrdenCompraFk()
    {
        return $this->codigoOrdenCompraFk;
    }

    /**
     * @param mixed $codigoOrdenCompraFk
     */
    public function setCodigoOrdenCompraFk($codigoOrdenCompraFk): void
    {
        $this->codigoOrdenCompraFk = $codigoOrdenCompraFk;
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
    public function getVrPrecio()
    {
        return $this->vrPrecio;
    }

    /**
     * @param mixed $vrPrecio
     */
    public function setVrPrecio($vrPrecio): void
    {
        $this->vrPrecio = $vrPrecio;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeDescuento()
    {
        return $this->porcentajeDescuento;
    }

    /**
     * @param mixed $porcentajeDescuento
     */
    public function setPorcentajeDescuento($porcentajeDescuento): void
    {
        $this->porcentajeDescuento = $porcentajeDescuento;
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
    public function getPorcentajeIva()
    {
        return $this->porcentajeIva;
    }

    /**
     * @param mixed $porcentajeIva
     */
    public function setPorcentajeIva($porcentajeIva): void
    {
        $this->porcentajeIva = $porcentajeIva;
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
    public function getCantidadPendiente()
    {
        return $this->cantidadPendiente;
    }

    /**
     * @param mixed $cantidadPendiente
     */
    public function setCantidadPendiente($cantidadPendiente): void
    {
        $this->cantidadPendiente = $cantidadPendiente;
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
    public function getOrdenCompraRel()
    {
        return $this->ordenCompraRel;
    }

    /**
     * @param mixed $ordenCompraRel
     */
    public function setOrdenCompraRel($ordenCompraRel): void
    {
        $this->ordenCompraRel = $ordenCompraRel;
    }

    /**
     * @return mixed
     */
    public function getSolicitudDetalleRel()
    {
        return $this->solicitudDetalleRel;
    }

    /**
     * @param mixed $solicitudDetalleRel
     */
    public function setSolicitudDetalleRel($solicitudDetalleRel): void
    {
        $this->solicitudDetalleRel = $solicitudDetalleRel;
    }

    /**
     * @return mixed
     */
    public function getMovimientosDetallesOrdenCompraDetalleRel()
    {
        return $this->movimientosDetallesOrdenCompraDetalleRel;
    }

    /**
     * @param mixed $movimientosDetallesOrdenCompraDetalleRel
     */
    public function setMovimientosDetallesOrdenCompraDetalleRel($movimientosDetallesOrdenCompraDetalleRel): void
    {
        $this->movimientosDetallesOrdenCompraDetalleRel = $movimientosDetallesOrdenCompraDetalleRel;
    }
}

