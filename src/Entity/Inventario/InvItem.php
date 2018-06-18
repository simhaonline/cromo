<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvItemRepository")
 */
class InvItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoItemPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     * @Assert\Length(
     *     max = 150,
     *     maxMessage = "El campo no puede contener mas de 150 caracteres"
     * )
     */
    private $nombre;

    /**
     * @ORM\Column(name="vr_costo_predeterminado", type="float", nullable=true)
     */
    private $vrCostoPredeterminado = 0;

    /**
     * @ORM\Column(name="vr_costo_promedio", type="float", nullable=true)
     */
    private $vrCostoPromedio = 0;

    /**
     * @ORM\Column(name="vr_costo_promedio_predeterminado", type="float", nullable=true)
     */
    private $vrPrecioPredeterminado = 0;

    /**
     * @ORM\Column(name="vr_costo_promedio_promedio", type="float", nullable=true)
     */
    private $vrPrecioPromedio = 0;

    /**
     * @ORM\Column(name="codigo_ean", type="string", length=80, nullable=true)
     */
    private $codigoEAN;

    /**
     * @ORM\Column(name="codigo_barras", type="string", length=150, nullable=true)
     */
    private $codigoBarras;

    /**
     * @ORM\Column(name="porcentaje_iva", type="integer", nullable=true)
     */
    private $porcentajeIva = 0;

    /**
     * @ORM\Column(name="cantidad_existencia", type="integer", nullable=true)
     */
    private $cantidadExistencia = 0;

    /**
     * @ORM\Column(name="cantidad_remisionada", type="integer", nullable=true)
     */
    private $cantidadRemisionada = 0;

    /**
     * @ORM\Column(name="cantidad_reservada", type="integer", nullable=true)
     */
    private $cantidadReservada = 0;

    /**
     * @ORM\Column(name="cantidad_disponible", type="integer", nullable=true)
     */
    private $cantidadDisponible = 0;

    /**
     * @ORM\Column(name="cantidad_orden_compra", type="integer", nullable=true)
     */
    private $cantidadOrdenCompra = 0;

    /**
     * @ORM\Column(name="cantidad_solicitud", type="integer", nullable=true)
     */
    private $cantidadSolicitud = 0;

    /**
     * @ORM\Column(name="codigo_unidad_medida_fk",  type="integer", nullable=true)
     */
    private $codigoUnidadMedidaFk;

    /**
     * @ORM\Column(name="afecta_inventario", type="boolean", nullable=true)
     */
    private $afectaInventario = true;

    /**
     * @ORM\Column(name="descripcion", type="string", length=200, nullable=true)
     * @Assert\Length(
     *     max="200",
     *     maxMessage="El campo no puede tener mas de 200 caracteres"
     * )
     */
    private $descripcion;

    /**
     * @ORM\Column(name="stockMinimo", type="integer", nullable=true)
     */
    private $stockMinimo = 0;

    /**
     * @ORM\Column(name="stockMaximo", type="integer", nullable=true)
     */
    private $stockMaximo = 0;

    /**
     * @ORM\OneToMany(targetEntity="InvSolicitudDetalle", mappedBy="itemRel")
     */
    private $itemsolicitudDetallesRel;

    /**
     * @ORM\OneToMany(targetEntity="InvOrdenCompraDetalle", mappedBy="itemRel")
     */
    protected $itemOrdenCompraDetallesRel;

    /**
     * @ORM\OneToMany(targetEntity="InvMovimientoDetalle", mappedBy="itemRel")
     */
    protected $itemMovimientosDetallesRel;

    /**
     * @return mixed
     */
    public function getCodigoItemPk()
    {
        return $this->codigoItemPk;
    }

    /**
     * @param mixed $codigoItemPk
     */
    public function setCodigoItemPk($codigoItemPk): void
    {
        $this->codigoItemPk = $codigoItemPk;
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
    public function getVrCostoPredeterminado()
    {
        return $this->vrCostoPredeterminado;
    }

    /**
     * @param mixed $vrCostoPredeterminado
     */
    public function setVrCostoPredeterminado($vrCostoPredeterminado): void
    {
        $this->vrCostoPredeterminado = $vrCostoPredeterminado;
    }

    /**
     * @return mixed
     */
    public function getVrCostoPromedio()
    {
        return $this->vrCostoPromedio;
    }

    /**
     * @param mixed $vrCostoPromedio
     */
    public function setVrCostoPromedio($vrCostoPromedio): void
    {
        $this->vrCostoPromedio = $vrCostoPromedio;
    }

    /**
     * @return mixed
     */
    public function getVrPrecioPredeterminado()
    {
        return $this->vrPrecioPredeterminado;
    }

    /**
     * @param mixed $vrPrecioPredeterminado
     */
    public function setVrPrecioPredeterminado($vrPrecioPredeterminado): void
    {
        $this->vrPrecioPredeterminado = $vrPrecioPredeterminado;
    }

    /**
     * @return mixed
     */
    public function getVrPrecioPromedio()
    {
        return $this->vrPrecioPromedio;
    }

    /**
     * @param mixed $vrPrecioPromedio
     */
    public function setVrPrecioPromedio($vrPrecioPromedio): void
    {
        $this->vrPrecioPromedio = $vrPrecioPromedio;
    }

    /**
     * @return mixed
     */
    public function getCodigoEAN()
    {
        return $this->codigoEAN;
    }

    /**
     * @param mixed $codigoEAN
     */
    public function setCodigoEAN($codigoEAN): void
    {
        $this->codigoEAN = $codigoEAN;
    }

    /**
     * @return mixed
     */
    public function getCodigoBarras()
    {
        return $this->codigoBarras;
    }

    /**
     * @param mixed $codigoBarras
     */
    public function setCodigoBarras($codigoBarras): void
    {
        $this->codigoBarras = $codigoBarras;
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
    public function getCantidadExistencia()
    {
        return $this->cantidadExistencia;
    }

    /**
     * @param mixed $cantidadExistencia
     */
    public function setCantidadExistencia($cantidadExistencia): void
    {
        $this->cantidadExistencia = $cantidadExistencia;
    }

    /**
     * @return mixed
     */
    public function getCantidadRemisionada()
    {
        return $this->cantidadRemisionada;
    }

    /**
     * @param mixed $cantidadRemisionada
     */
    public function setCantidadRemisionada($cantidadRemisionada): void
    {
        $this->cantidadRemisionada = $cantidadRemisionada;
    }

    /**
     * @return mixed
     */
    public function getCantidadReservada()
    {
        return $this->cantidadReservada;
    }

    /**
     * @param mixed $cantidadReservada
     */
    public function setCantidadReservada($cantidadReservada): void
    {
        $this->cantidadReservada = $cantidadReservada;
    }

    /**
     * @return mixed
     */
    public function getCantidadDisponible()
    {
        return $this->cantidadDisponible;
    }

    /**
     * @param mixed $cantidadDisponible
     */
    public function setCantidadDisponible($cantidadDisponible): void
    {
        $this->cantidadDisponible = $cantidadDisponible;
    }

    /**
     * @return mixed
     */
    public function getCantidadOrdenCompra()
    {
        return $this->cantidadOrdenCompra;
    }

    /**
     * @param mixed $cantidadOrdenCompra
     */
    public function setCantidadOrdenCompra($cantidadOrdenCompra): void
    {
        $this->cantidadOrdenCompra = $cantidadOrdenCompra;
    }

    /**
     * @return mixed
     */
    public function getCantidadSolicitud()
    {
        return $this->cantidadSolicitud;
    }

    /**
     * @param mixed $cantidadSolicitud
     */
    public function setCantidadSolicitud($cantidadSolicitud): void
    {
        $this->cantidadSolicitud = $cantidadSolicitud;
    }

    /**
     * @return mixed
     */
    public function getCodigoUnidadMedidaFk()
    {
        return $this->codigoUnidadMedidaFk;
    }

    /**
     * @param mixed $codigoUnidadMedidaFk
     */
    public function setCodigoUnidadMedidaFk($codigoUnidadMedidaFk): void
    {
        $this->codigoUnidadMedidaFk = $codigoUnidadMedidaFk;
    }

    /**
     * @return mixed
     */
    public function getAfectaInventario()
    {
        return $this->afectaInventario;
    }

    /**
     * @param mixed $afectaInventario
     */
    public function setAfectaInventario($afectaInventario): void
    {
        $this->afectaInventario = $afectaInventario;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return mixed
     */
    public function getStockMinimo()
    {
        return $this->stockMinimo;
    }

    /**
     * @param mixed $stockMinimo
     */
    public function setStockMinimo($stockMinimo): void
    {
        $this->stockMinimo = $stockMinimo;
    }

    /**
     * @return mixed
     */
    public function getStockMaximo()
    {
        return $this->stockMaximo;
    }

    /**
     * @param mixed $stockMaximo
     */
    public function setStockMaximo($stockMaximo): void
    {
        $this->stockMaximo = $stockMaximo;
    }

    /**
     * @return mixed
     */
    public function getItemsolicitudDetallesRel()
    {
        return $this->itemsolicitudDetallesRel;
    }

    /**
     * @param mixed $itemsolicitudDetallesRel
     */
    public function setItemsolicitudDetallesRel($itemsolicitudDetallesRel): void
    {
        $this->itemsolicitudDetallesRel = $itemsolicitudDetallesRel;
    }

    /**
     * @return mixed
     */
    public function getItemOrdenCompraDetallesRel()
    {
        return $this->itemOrdenCompraDetallesRel;
    }

    /**
     * @param mixed $itemOrdenCompraDetallesRel
     */
    public function setItemOrdenCompraDetallesRel($itemOrdenCompraDetallesRel): void
    {
        $this->itemOrdenCompraDetallesRel = $itemOrdenCompraDetallesRel;
    }

    /**
     * @return mixed
     */
    public function getItemMovimientosDetallesRel()
    {
        return $this->itemMovimientosDetallesRel;
    }

    /**
     * @param mixed $itemMovimientosDetallesRel
     */
    public function setItemMovimientosDetallesRel($itemMovimientosDetallesRel): void
    {
        $this->itemMovimientosDetallesRel = $itemMovimientosDetallesRel;
    }
}

