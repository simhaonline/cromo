<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvPedidoDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class InvPedidoDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoPedidoDetallePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoPedidoDetallePk;

    /**
     * @ORM\Column(name="codigo_pedido_fk", type="integer", nullable=true)
     */
    private $codigoPedidoFk;

    /**
     * @ORM\Column(name="codigo_cotizacion_detalle_fk", type="integer", nullable=true)
     */
    private $codigoCotizacionDetalleFK;

    /**
     * @ORM\Column(name="codigo_item_fk", type="integer")
     */
    private $codigoItemFk;

    /**
     * @ORM\Column(name="cantidad",options={"default" : 0}, type="integer")
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="vr_precio",options={"default" : 0}, type="float")
     */
    private $vrPrecio = 0;

    /**
     * @ORM\Column(name="vr_subtotal", type="float", options={"default" : 0}, nullable=true)
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="porcentaje_iva", type="float", options={"default" : 0}, nullable=true)
     */
    private $porcentajeIva = 0;

    /**
     * @ORM\Column(name="vr_iva", type="float", options={"default" : 0}, nullable=true)
     */
    private $vrIva = 0;

    /**
     * @ORM\Column(name="vr_neto", type="float", options={"default" : 0},)
     */
    private $vrNeto = 0;

    /**
     * @ORM\Column(name="vr_total", type="float", options={"default" : 0}, nullable=true)
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="cantidad_afectada",options={"default" : 0}, type="integer", nullable=true)
     */
    private $cantidadAfectada = 0;

    /**
     * @ORM\Column(name="cantidad_pendiente",options={"default" : 0}, type="integer", nullable=true)
     */
    private $cantidadPendiente = 0;

    /**
     * @ORM\ManyToOne(targetEntity="InvPedido", inversedBy="pedidosDetallesPedidoRel")
     * @ORM\JoinColumn(name="codigo_pedido_fk", referencedColumnName="codigo_pedido_pk")
     */
    protected $pedidoRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="pedidosDetallesItemRel")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvCotizacionDetalle", inversedBy="pedidosDetallesCotizacionDetalleRel")
     * @ORM\JoinColumn(name="codigo_cotizacion_detalle_fk", referencedColumnName="codigo_cotizacion_detalle_pk")
     */
    protected $cotizacionDetalleRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvMovimientoDetalle", mappedBy="pedidoDetalleRel")
     */
    protected $movimientosDetallesPedidoDetalleRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvRemisionDetalle", mappedBy="pedidoDetalleRel")
     */
    protected $remisionesDetallesPedidoDetalleRel;

    /**
     * @return mixed
     */
    public function getCodigoPedidoDetallePk()
    {
        return $this->codigoPedidoDetallePk;
    }

    /**
     * @param mixed $codigoPedidoDetallePk
     */
    public function setCodigoPedidoDetallePk($codigoPedidoDetallePk): void
    {
        $this->codigoPedidoDetallePk = $codigoPedidoDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPedidoFk()
    {
        return $this->codigoPedidoFk;
    }

    /**
     * @param mixed $codigoPedidoFk
     */
    public function setCodigoPedidoFk($codigoPedidoFk): void
    {
        $this->codigoPedidoFk = $codigoPedidoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCotizacionDetalleFK()
    {
        return $this->codigoCotizacionDetalleFK;
    }

    /**
     * @param mixed $codigoCotizacionDetalleFK
     */
    public function setCodigoCotizacionDetalleFK($codigoCotizacionDetalleFK): void
    {
        $this->codigoCotizacionDetalleFK = $codigoCotizacionDetalleFK;
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
    public function getPedidoRel()
    {
        return $this->pedidoRel;
    }

    /**
     * @param mixed $pedidoRel
     */
    public function setPedidoRel($pedidoRel): void
    {
        $this->pedidoRel = $pedidoRel;
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
    public function getCotizacionDetalleRel()
    {
        return $this->cotizacionDetalleRel;
    }

    /**
     * @param mixed $cotizacionDetalleRel
     */
    public function setCotizacionDetalleRel($cotizacionDetalleRel): void
    {
        $this->cotizacionDetalleRel = $cotizacionDetalleRel;
    }

    /**
     * @return mixed
     */
    public function getMovimientosDetallesPedidoDetalleRel()
    {
        return $this->movimientosDetallesPedidoDetalleRel;
    }

    /**
     * @param mixed $movimientosDetallesPedidoDetalleRel
     */
    public function setMovimientosDetallesPedidoDetalleRel($movimientosDetallesPedidoDetalleRel): void
    {
        $this->movimientosDetallesPedidoDetalleRel = $movimientosDetallesPedidoDetalleRel;
    }

    /**
     * @return mixed
     */
    public function getCantidadAfectada()
    {
        return $this->cantidadAfectada;
    }

    /**
     * @param mixed $cantidadAfectada
     */
    public function setCantidadAfectada($cantidadAfectada): void
    {
        $this->cantidadAfectada = $cantidadAfectada;
    }

    /**
     * @return mixed
     */
    public function getRemisionesDetallesPedidoDetalleRel()
    {
        return $this->remisionesDetallesPedidoDetalleRel;
    }

    /**
     * @param mixed $remisionesDetallesPedidoDetalleRel
     */
    public function setRemisionesDetallesPedidoDetalleRel( $remisionesDetallesPedidoDetalleRel ): void
    {
        $this->remisionesDetallesPedidoDetalleRel = $remisionesDetallesPedidoDetalleRel;
    }



}

