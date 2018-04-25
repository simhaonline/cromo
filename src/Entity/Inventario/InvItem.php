<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_lote_fk,  type="integer", nullable=true)
     */
    private $codigoLoteFk;

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
     * @ORM\Column(name="cantidad_orden_compra, type="integer", nullable=true)
     */
    private $cantidadOrdenCompra = 0;

    /**
     * @ORM\Column(name="codigo_unidad_medida_fk,  type="integer", nullable=true)
     */
    private $codigoUnidadMedidaFk;

    /**
     * @ORM\Column(name="afecta_inventario", type="boolean", nullable=true)
     */
    private $afectaInventario = true;

    /**
     * @ORM\Column(name="descripcion", type="string", length=200, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(name="stockMinimo", type="integer", nullable=true)
     */
    private $stockMinimo;

    /**
     * @ORM\Column(name="stockMaximo", type="integer", nullable=true)
     */
    private $stockMaximo;

}

