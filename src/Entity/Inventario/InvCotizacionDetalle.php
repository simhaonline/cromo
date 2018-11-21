<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvCotizacionDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class InvCotizacionDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoCotizacionDetallePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="codigo_cotizacion_detalle_pk")
     */
    private $codigoCotizacionDetallePk;

    /**
     * @ORM\Column(name="codigo_cotizacion_fk", type="integer", nullable=true)
     */
    private $codigoCotizacionFk;

    /**
     * @ORM\Column(name="codigo_item_fk", type="integer")
     */
    private $codigoItemFk;

    /**
     * @ORM\Column(name="cantidad",options={"default" : 0}, type="integer")
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
     * @ORM\Column(name="cantidad_pendiente",options={"default" : 0}, type="integer", nullable=true)
     */
    private $cantidadPendiente = 0;

    /**
     * @ORM\ManyToOne(targetEntity="InvCotizacion", inversedBy="cotizacionesCotizacionDetalleRel")
     * @ORM\JoinColumn(name="codigo_cotizacion_fk", referencedColumnName="codigo_cotizacion_pk")
     */
    protected $cotizacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="cotizacionesDetallesItemRel")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel;

    /**
     * @ORM\OneToMany(targetEntity="InvPedidoDetalle", mappedBy="cotizacionDetalleRel")
     */
    protected $pedidosDetallesCotizacionDetalleRel;

    /**
     * @return mixed
     */
    public function getCodigoCotizacionDetallePk()
    {
        return $this->codigoCotizacionDetallePk;
    }

    /**
     * @param mixed $codigoCotizacionDetallePk
     */
    public function setCodigoCotizacionDetallePk($codigoCotizacionDetallePk): void
    {
        $this->codigoCotizacionDetallePk = $codigoCotizacionDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCotizacionFk()
    {
        return $this->codigoCotizacionFk;
    }

    /**
     * @param mixed $codigoCotizacionFk
     */
    public function setCodigoCotizacionFk($codigoCotizacionFk): void
    {
        $this->codigoCotizacionFk = $codigoCotizacionFk;
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
    public function getCotizacionRel()
    {
        return $this->cotizacionRel;
    }

    /**
     * @param mixed $cotizacionRel
     */
    public function setCotizacionRel($cotizacionRel): void
    {
        $this->cotizacionRel = $cotizacionRel;
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
    public function getPedidosDetallesCotizacionDetalleRel()
    {
        return $this->pedidosDetallesCotizacionDetalleRel;
    }

    /**
     * @param mixed $pedidosDetallesCotizacionDetalleRel
     */
    public function setPedidosDetallesCotizacionDetalleRel($pedidosDetallesCotizacionDetalleRel): void
    {
        $this->pedidosDetallesCotizacionDetalleRel = $pedidosDetallesCotizacionDetalleRel;
    }
}

