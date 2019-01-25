<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvRemisionDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class InvRemisionDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoRemisionDetallePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoRemisionDetallePk;

    /**
     * @ORM\Column(name="codigo_remision_fk", type="integer", nullable=true)
     */
    private $codigoRemisionFk;

    /**
     * @ORM\Column(name="codigo_item_fk", type="integer")
     */
    private $codigoItemFk;

    /**
     * @ORM\Column(name="cantidad",options={"default" : 0}, type="integer")
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="codigo_bodega_fk", type="string", length=10, nullable=true)
     */
    private $codigoBodegaFk;

    /**
     * @ORM\Column(name="lote_fk", type="string", length=40, nullable=true)
     */
    private $loteFk;

    /**
     * @ORM\Column(name="fecha_vencimiento", type="date", nullable=true)
     */
    private $fechaVencimiento;

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
     * @ORM\Column(name="operacion_inventario", type="smallint", nullable=true, options={"default" : 0})
     */
    private $operacionInventario = 0;

    /**
     * @ORM\Column(name="cantidad_operada", type="integer", options={"default" : 0})
     */
    private $cantidadOperada = 0;

    /**
     * @ORM\Column(name="codigo_pedido_detalle_fk", type="integer", nullable=true)
     */
    private $codigoPedidoDetalleFk;

    /**
     * @ORM\Column(name="codigo_remision_detalle_fk", type="integer", nullable=true)
     */
    private $codigoRemisionDetalleFk;

    /**
     * @ORM\ManyToOne(targetEntity="InvRemision", inversedBy="remisionesDetallesPedidoRel")
     * @ORM\JoinColumn(name="codigo_remision_fk", referencedColumnName="codigo_remision_pk")
     */
    protected $remisionRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Inventario\InvPedidoDetalle", inversedBy="remisionesDetallesPedidoDetalleRel")
     * @ORM\JoinColumn(name="codigo_pedido_detalle_fk", referencedColumnName="codigo_pedido_detalle_pk")
     */
    protected $pedidoDetalleRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Inventario\InvRemisionDetalle", inversedBy="remisionesDetallesRemisionDetalleRel")
     * @ORM\JoinColumn(name="codigo_remision_detalle_fk", referencedColumnName="codigo_remision_detalle_pk")
     */
    protected $remisionDetalleRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="remisionesDetallesItemRel")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvMovimientoDetalle", mappedBy="remisionDetalleRel")
     */
    protected $movimientosDetallesRemisionDetalleRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvRemisionDetalle", mappedBy="remisionDetalleRel")
     */
    protected $remisionesDetallesRemisionDetalleRel;

    /**
     * @return mixed
     */
    public function getCodigoRemisionDetallePk()
    {
        return $this->codigoRemisionDetallePk;
    }

    /**
     * @param mixed $codigoRemisionDetallePk
     */
    public function setCodigoRemisionDetallePk($codigoRemisionDetallePk): void
    {
        $this->codigoRemisionDetallePk = $codigoRemisionDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoRemisionFk()
    {
        return $this->codigoRemisionFk;
    }

    /**
     * @param mixed $codigoRemisionFk
     */
    public function setCodigoRemisionFk($codigoRemisionFk): void
    {
        $this->codigoRemisionFk = $codigoRemisionFk;
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
    public function getOperacionInventario()
    {
        return $this->operacionInventario;
    }

    /**
     * @param mixed $operacionInventario
     */
    public function setOperacionInventario($operacionInventario): void
    {
        $this->operacionInventario = $operacionInventario;
    }

    /**
     * @return mixed
     */
    public function getCantidadOperada()
    {
        return $this->cantidadOperada;
    }

    /**
     * @param mixed $cantidadOperada
     */
    public function setCantidadOperada($cantidadOperada): void
    {
        $this->cantidadOperada = $cantidadOperada;
    }

    /**
     * @return mixed
     */
    public function getCodigoPedidoDetalleFk()
    {
        return $this->codigoPedidoDetalleFk;
    }

    /**
     * @param mixed $codigoPedidoDetalleFk
     */
    public function setCodigoPedidoDetalleFk($codigoPedidoDetalleFk): void
    {
        $this->codigoPedidoDetalleFk = $codigoPedidoDetalleFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoRemisionDetalleFk()
    {
        return $this->codigoRemisionDetalleFk;
    }

    /**
     * @param mixed $codigoRemisionDetalleFk
     */
    public function setCodigoRemisionDetalleFk($codigoRemisionDetalleFk): void
    {
        $this->codigoRemisionDetalleFk = $codigoRemisionDetalleFk;
    }

    /**
     * @return mixed
     */
    public function getRemisionRel()
    {
        return $this->remisionRel;
    }

    /**
     * @param mixed $remisionRel
     */
    public function setRemisionRel($remisionRel): void
    {
        $this->remisionRel = $remisionRel;
    }

    /**
     * @return mixed
     */
    public function getPedidoDetalleRel()
    {
        return $this->pedidoDetalleRel;
    }

    /**
     * @param mixed $pedidoDetalleRel
     */
    public function setPedidoDetalleRel($pedidoDetalleRel): void
    {
        $this->pedidoDetalleRel = $pedidoDetalleRel;
    }

    /**
     * @return mixed
     */
    public function getRemisionDetalleRel()
    {
        return $this->remisionDetalleRel;
    }

    /**
     * @param mixed $remisionDetalleRel
     */
    public function setRemisionDetalleRel($remisionDetalleRel): void
    {
        $this->remisionDetalleRel = $remisionDetalleRel;
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
    public function getMovimientosDetallesRemisionDetalleRel()
    {
        return $this->movimientosDetallesRemisionDetalleRel;
    }

    /**
     * @param mixed $movimientosDetallesRemisionDetalleRel
     */
    public function setMovimientosDetallesRemisionDetalleRel($movimientosDetallesRemisionDetalleRel): void
    {
        $this->movimientosDetallesRemisionDetalleRel = $movimientosDetallesRemisionDetalleRel;
    }

    /**
     * @return mixed
     */
    public function getRemisionesDetallesRemisionDetalleRel()
    {
        return $this->remisionesDetallesRemisionDetalleRel;
    }

    /**
     * @param mixed $remisionesDetallesRemisionDetalleRel
     */
    public function setRemisionesDetallesRemisionDetalleRel($remisionesDetallesRemisionDetalleRel): void
    {
        $this->remisionesDetallesRemisionDetalleRel = $remisionesDetallesRemisionDetalleRel;
    }

    /**
     * @return mixed
     */
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * @param mixed $fechaVencimiento
     */
    public function setFechaVencimiento( $fechaVencimiento ): void
    {
        $this->fechaVencimiento = $fechaVencimiento;
    }



}

