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
     * @ORM\Column(type="integer",name="codigo_item_pk")
     */
    private $codigoItemPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=400, nullable=true)
     * @Assert\Length(
     *     max = 400,
     *     maxMessage = "El campo no puede contener mas de 400 caracteres"
     * )
     */
    private $nombre;

    /**
     * @ORM\Column(name="nombre_tenico", type="string", length=400, nullable=true)
     * @Assert\Length(
     *     max = 400,
     *     maxMessage = "El campo no puede contener mas de 400 caracteres"
     * )
     */
    private $nombreTecnico;

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
     * @ORM\Column(name="codigo_linea_fk", type="string",length=10, nullable=true)
     */
    private $codigoLineaFk;

    /**
     * @ORM\Column(name="codigo_grupo_fk", type="string",length=10, nullable=true)
     */
    private $codigoGrupoFk;

    /**
     * @ORM\Column(name="codigo_subgrupo_fk", type="string",length=10, nullable=true)
     */
    private $codigoSubgrupoFk;

    /**
     * @ORM\Column(name="codigo_unidad_medida_fk",  type="string",length=10, nullable=true)
     */
    private $codigoUnidadMedidaFk;

    /**
     * @ORM\Column(name="codigo_marca_fk", type="string",length=10, nullable=true)
     */
    private $codigoMarcaFk;

    /**
     * @ORM\Column(name="codigo_impuesto_retencion_fk", type="string", length=3, nullable=true)
     */
    private $codigoImpuestoRetencionFk;

    /**
     * @ORM\Column(name="codigo_impuesto_iva_venta_fk", type="string", length=3, nullable=true)
     */
    private $codigoImpuestoIvaVentaFk;

    /**
     * @ORM\Column(name="modelo", type="string",length=50, nullable=true)
     */
    private $modelo;

    /**
     * @ORM\Column(name="referencia", type="string",length=50, nullable=true)
     */
    private $referencia;

    /**
     * @ORM\Column(name="cantidad_existencia", type="integer", nullable=true, options={"default" : 0})
     */
    private $cantidadExistencia = 0;

    /**
     * @ORM\Column(name="cantidad_remisionada", type="integer", nullable=true, options={"default" : 0})
     */
    private $cantidadRemisionada = 0;

    /**
     * @ORM\Column(name="cantidad_reservada", type="integer", nullable=true, options={"default" : 0})
     */
    private $cantidadReservada = 0;

    /**
     * @ORM\Column(name="cantidad_disponible", type="integer", nullable=true, options={"default" : 0})
     */
    private $cantidadDisponible = 0;

    /**
     * @ORM\Column(name="cantidad_orden", type="integer", nullable=true, options={"default" : 0})
     */
    private $cantidadOrden = 0;

    /**
     * @ORM\Column(name="cantidad_solicitud", type="integer", nullable=true, options={"default" : 0})
     */
    private $cantidadSolicitud = 0;

    /**
     * @ORM\Column(name="cantidad_pedido", type="integer", nullable=true, options={"default" : 0})
     */
    private $cantidadPedido = 0;

    /**
     * @ORM\Column(name="afecta_inventario", type="boolean", nullable=true, options={"default":false})
     */
    private $afectaInventario = true;

    /**
     * @ORM\Column(name="descripcion", type="string", length=600, nullable=true)
     * @Assert\Length(
     *     max="600",
     *     maxMessage="El campo no puede tener mas de 600 caracteres"
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
     * @ORM\Column(name="codigo_cuenta_venta_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaVentaFk;

    /**
     * @ORM\Column(name="codigo_cuenta_venta_devolucion_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaVentaDevolucionFk;

    /**
     * @ORM\Column(name="codigo_cuenta_compra_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaCompraFk;

    /**
     * @ORM\Column(name="codigo_cuenta_compra_devolucion_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaCompraDevolucionFk;

    /**
     * @ORM\Column(name="codigo_cuenta_costo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaCostoFk;

    /**
     * @ORM\Column(name="codigo_cuenta_inventario_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaInventarioFk;

    /**
     * @ORM\Column(name="codigo_cuenta_inventario_transito_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaInventarioTransitoFk;

    /**
     * @ORM\Column(name="producto", type="boolean", nullable=true, options={"default":false})
     */
    private $producto = false;

    /**
     * @ORM\Column(name="servicio", type="boolean", nullable=true, options={"default":false})
     */
    private $servicio = false;

    /**
     * @ORM\Column(name="registro_invima", type="string",length=80, nullable=true)
     */
    private $registroInvima;

    /**
     * @ORM\ManyToOne(targetEntity="InvMarca", inversedBy="itemsMarcaRel")
     * @ORM\JoinColumn(name="codigo_marca_fk",referencedColumnName="codigo_marca_pk")
     * @Assert\NotBlank(
     *     message="El campo no puede estar vacio"
     * )
     */
    protected $marcaRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvGrupo", inversedBy="itemsGrupoRel")
     * @ORM\JoinColumn(name="codigo_grupo_fk",referencedColumnName="codigo_grupo_pk")
     */
    protected $grupoRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvSubgrupo", inversedBy="subgrupoItemsRel")
     * @ORM\JoinColumn(name="codigo_subgrupo_fk",referencedColumnName="codigo_subgrupo_pk")
     */
    protected $subgrupoRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvLinea", inversedBy="itemsLineaRel")
     * @ORM\JoinColumn(name="codigo_linea_fk",referencedColumnName="codigo_linea_pk")
     */
    protected $lineaRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenImpuesto", inversedBy="itemsImpuestoRetencionRel")
     * @ORM\JoinColumn(name="codigo_impuesto_retencion_fk",referencedColumnName="codigo_impuesto_pk")
     */
    protected $impuestoRetencionRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenImpuesto", inversedBy="itemsImpuestoIvaVentaRel")
     * @ORM\JoinColumn(name="codigo_impuesto_iva_venta_fk",referencedColumnName="codigo_impuesto_pk")
     */
    protected $impuestoIvaVentaRel;

    /**
     * @ORM\OneToMany(targetEntity="InvSolicitudDetalle", mappedBy="itemRel")
     */
    private $solicitudesDetallesItemRel;

    /**
     * @ORM\OneToMany(targetEntity="InvOrdenDetalle", mappedBy="itemRel")
     */
    protected $ordenesDetallesItemRel;

    /**
     * @ORM\OneToMany(targetEntity="InvMovimientoDetalle", mappedBy="itemRel")
     */
    protected $movimientosDetallesItemRel;

    /**
     * @ORM\OneToMany(targetEntity="InvLote", mappedBy="itemRel")
     */
    protected $lotesItemRel;

    /**
     * @ORM\OneToMany(targetEntity="InvPedidoDetalle", mappedBy="itemRel")
     */
    private $pedidosDetallesItemRel;

    /**
     * @ORM\OneToMany(targetEntity="InvRemisionDetalle", mappedBy="itemRel")
     */
    private $remisionesDetallesItemRel;

    /**
     * @ORM\OneToMany(targetEntity="InvPrecioDetalle", mappedBy="itemRel")
     */
    private $preciosDetallesItemRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvInventarioValorizado", mappedBy="itemRel")
     */
    private $inventariosValorizadosItemRel;

    /**
     * @ORM\OneToMany(targetEntity="InvCotizacionDetalle", mappedBy="itemRel")
     */
    protected $cotizacionesDetallesItemRel;

    /**
     * @ORM\OneToMany(targetEntity="InvImportacionDetalle", mappedBy="itemRel")
     */
    protected $importacionesDetallesItemRel;

    /**
     * @ORM\OneToMany(targetEntity="InvCostoDetalle", mappedBy="itemRel")
     */
    protected $costosDetallesItemRel;

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
    public function setCodigoItemPk( $codigoItemPk ): void
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
    public function setNombre( $nombre ): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getNombreTecnico()
    {
        return $this->nombreTecnico;
    }

    /**
     * @param mixed $nombreTecnico
     */
    public function setNombreTecnico( $nombreTecnico ): void
    {
        $this->nombreTecnico = $nombreTecnico;
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
    public function setVrCostoPredeterminado( $vrCostoPredeterminado ): void
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
    public function setVrCostoPromedio( $vrCostoPromedio ): void
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
    public function setVrPrecioPredeterminado( $vrPrecioPredeterminado ): void
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
    public function setVrPrecioPromedio( $vrPrecioPromedio ): void
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
    public function setCodigoEAN( $codigoEAN ): void
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
    public function setCodigoBarras( $codigoBarras ): void
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
    public function setPorcentajeIva( $porcentajeIva ): void
    {
        $this->porcentajeIva = $porcentajeIva;
    }

    /**
     * @return mixed
     */
    public function getCodigoLineaFk()
    {
        return $this->codigoLineaFk;
    }

    /**
     * @param mixed $codigoLineaFk
     */
    public function setCodigoLineaFk( $codigoLineaFk ): void
    {
        $this->codigoLineaFk = $codigoLineaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoGrupoFk()
    {
        return $this->codigoGrupoFk;
    }

    /**
     * @param mixed $codigoGrupoFk
     */
    public function setCodigoGrupoFk( $codigoGrupoFk ): void
    {
        $this->codigoGrupoFk = $codigoGrupoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSubgrupoFk()
    {
        return $this->codigoSubgrupoFk;
    }

    /**
     * @param mixed $codigoSubgrupoFk
     */
    public function setCodigoSubgrupoFk( $codigoSubgrupoFk ): void
    {
        $this->codigoSubgrupoFk = $codigoSubgrupoFk;
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
    public function setCodigoUnidadMedidaFk( $codigoUnidadMedidaFk ): void
    {
        $this->codigoUnidadMedidaFk = $codigoUnidadMedidaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoMarcaFk()
    {
        return $this->codigoMarcaFk;
    }

    /**
     * @param mixed $codigoMarcaFk
     */
    public function setCodigoMarcaFk( $codigoMarcaFk ): void
    {
        $this->codigoMarcaFk = $codigoMarcaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoImpuestoRetencionFk()
    {
        return $this->codigoImpuestoRetencionFk;
    }

    /**
     * @param mixed $codigoImpuestoRetencionFk
     */
    public function setCodigoImpuestoRetencionFk( $codigoImpuestoRetencionFk ): void
    {
        $this->codigoImpuestoRetencionFk = $codigoImpuestoRetencionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoImpuestoIvaVentaFk()
    {
        return $this->codigoImpuestoIvaVentaFk;
    }

    /**
     * @param mixed $codigoImpuestoIvaVentaFk
     */
    public function setCodigoImpuestoIvaVentaFk( $codigoImpuestoIvaVentaFk ): void
    {
        $this->codigoImpuestoIvaVentaFk = $codigoImpuestoIvaVentaFk;
    }

    /**
     * @return mixed
     */
    public function getModelo()
    {
        return $this->modelo;
    }

    /**
     * @param mixed $modelo
     */
    public function setModelo( $modelo ): void
    {
        $this->modelo = $modelo;
    }

    /**
     * @return mixed
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * @param mixed $referencia
     */
    public function setReferencia( $referencia ): void
    {
        $this->referencia = $referencia;
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
    public function setCantidadExistencia( $cantidadExistencia ): void
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
    public function setCantidadRemisionada( $cantidadRemisionada ): void
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
    public function setCantidadReservada( $cantidadReservada ): void
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
    public function setCantidadDisponible( $cantidadDisponible ): void
    {
        $this->cantidadDisponible = $cantidadDisponible;
    }

    /**
     * @return mixed
     */
    public function getCantidadOrden()
    {
        return $this->cantidadOrden;
    }

    /**
     * @param mixed $cantidadOrden
     */
    public function setCantidadOrden( $cantidadOrden ): void
    {
        $this->cantidadOrden = $cantidadOrden;
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
    public function setCantidadSolicitud( $cantidadSolicitud ): void
    {
        $this->cantidadSolicitud = $cantidadSolicitud;
    }

    /**
     * @return mixed
     */
    public function getCantidadPedido()
    {
        return $this->cantidadPedido;
    }

    /**
     * @param mixed $cantidadPedido
     */
    public function setCantidadPedido( $cantidadPedido ): void
    {
        $this->cantidadPedido = $cantidadPedido;
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
    public function setAfectaInventario( $afectaInventario ): void
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
    public function setDescripcion( $descripcion ): void
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
    public function setStockMinimo( $stockMinimo ): void
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
    public function setStockMaximo( $stockMaximo ): void
    {
        $this->stockMaximo = $stockMaximo;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaVentaFk()
    {
        return $this->codigoCuentaVentaFk;
    }

    /**
     * @param mixed $codigoCuentaVentaFk
     */
    public function setCodigoCuentaVentaFk( $codigoCuentaVentaFk ): void
    {
        $this->codigoCuentaVentaFk = $codigoCuentaVentaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaVentaDevolucionFk()
    {
        return $this->codigoCuentaVentaDevolucionFk;
    }

    /**
     * @param mixed $codigoCuentaVentaDevolucionFk
     */
    public function setCodigoCuentaVentaDevolucionFk( $codigoCuentaVentaDevolucionFk ): void
    {
        $this->codigoCuentaVentaDevolucionFk = $codigoCuentaVentaDevolucionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaCompraFk()
    {
        return $this->codigoCuentaCompraFk;
    }

    /**
     * @param mixed $codigoCuentaCompraFk
     */
    public function setCodigoCuentaCompraFk( $codigoCuentaCompraFk ): void
    {
        $this->codigoCuentaCompraFk = $codigoCuentaCompraFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaCompraDevolucionFk()
    {
        return $this->codigoCuentaCompraDevolucionFk;
    }

    /**
     * @param mixed $codigoCuentaCompraDevolucionFk
     */
    public function setCodigoCuentaCompraDevolucionFk( $codigoCuentaCompraDevolucionFk ): void
    {
        $this->codigoCuentaCompraDevolucionFk = $codigoCuentaCompraDevolucionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaCostoFk()
    {
        return $this->codigoCuentaCostoFk;
    }

    /**
     * @param mixed $codigoCuentaCostoFk
     */
    public function setCodigoCuentaCostoFk( $codigoCuentaCostoFk ): void
    {
        $this->codigoCuentaCostoFk = $codigoCuentaCostoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaInventarioFk()
    {
        return $this->codigoCuentaInventarioFk;
    }

    /**
     * @param mixed $codigoCuentaInventarioFk
     */
    public function setCodigoCuentaInventarioFk( $codigoCuentaInventarioFk ): void
    {
        $this->codigoCuentaInventarioFk = $codigoCuentaInventarioFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaInventarioTransitoFk()
    {
        return $this->codigoCuentaInventarioTransitoFk;
    }

    /**
     * @param mixed $codigoCuentaInventarioTransitoFk
     */
    public function setCodigoCuentaInventarioTransitoFk( $codigoCuentaInventarioTransitoFk ): void
    {
        $this->codigoCuentaInventarioTransitoFk = $codigoCuentaInventarioTransitoFk;
    }

    /**
     * @return mixed
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * @param mixed $producto
     */
    public function setProducto( $producto ): void
    {
        $this->producto = $producto;
    }

    /**
     * @return mixed
     */
    public function getServicio()
    {
        return $this->servicio;
    }

    /**
     * @param mixed $servicio
     */
    public function setServicio( $servicio ): void
    {
        $this->servicio = $servicio;
    }

    /**
     * @return mixed
     */
    public function getRegistroInvima()
    {
        return $this->registroInvima;
    }

    /**
     * @param mixed $registroInvima
     */
    public function setRegistroInvima( $registroInvima ): void
    {
        $this->registroInvima = $registroInvima;
    }

    /**
     * @return mixed
     */
    public function getMarcaRel()
    {
        return $this->marcaRel;
    }

    /**
     * @param mixed $marcaRel
     */
    public function setMarcaRel( $marcaRel ): void
    {
        $this->marcaRel = $marcaRel;
    }

    /**
     * @return mixed
     */
    public function getGrupoRel()
    {
        return $this->grupoRel;
    }

    /**
     * @param mixed $grupoRel
     */
    public function setGrupoRel( $grupoRel ): void
    {
        $this->grupoRel = $grupoRel;
    }

    /**
     * @return mixed
     */
    public function getSubgrupoRel()
    {
        return $this->subgrupoRel;
    }

    /**
     * @param mixed $subgrupoRel
     */
    public function setSubgrupoRel( $subgrupoRel ): void
    {
        $this->subgrupoRel = $subgrupoRel;
    }

    /**
     * @return mixed
     */
    public function getLineaRel()
    {
        return $this->lineaRel;
    }

    /**
     * @param mixed $lineaRel
     */
    public function setLineaRel( $lineaRel ): void
    {
        $this->lineaRel = $lineaRel;
    }

    /**
     * @return mixed
     */
    public function getImpuestoRetencionRel()
    {
        return $this->impuestoRetencionRel;
    }

    /**
     * @param mixed $impuestoRetencionRel
     */
    public function setImpuestoRetencionRel( $impuestoRetencionRel ): void
    {
        $this->impuestoRetencionRel = $impuestoRetencionRel;
    }

    /**
     * @return mixed
     */
    public function getImpuestoIvaVentaRel()
    {
        return $this->impuestoIvaVentaRel;
    }

    /**
     * @param mixed $impuestoIvaVentaRel
     */
    public function setImpuestoIvaVentaRel( $impuestoIvaVentaRel ): void
    {
        $this->impuestoIvaVentaRel = $impuestoIvaVentaRel;
    }

    /**
     * @return mixed
     */
    public function getSolicitudesDetallesItemRel()
    {
        return $this->solicitudesDetallesItemRel;
    }

    /**
     * @param mixed $solicitudesDetallesItemRel
     */
    public function setSolicitudesDetallesItemRel( $solicitudesDetallesItemRel ): void
    {
        $this->solicitudesDetallesItemRel = $solicitudesDetallesItemRel;
    }

    /**
     * @return mixed
     */
    public function getOrdenesDetallesItemRel()
    {
        return $this->ordenesDetallesItemRel;
    }

    /**
     * @param mixed $ordenesDetallesItemRel
     */
    public function setOrdenesDetallesItemRel( $ordenesDetallesItemRel ): void
    {
        $this->ordenesDetallesItemRel = $ordenesDetallesItemRel;
    }

    /**
     * @return mixed
     */
    public function getMovimientosDetallesItemRel()
    {
        return $this->movimientosDetallesItemRel;
    }

    /**
     * @param mixed $movimientosDetallesItemRel
     */
    public function setMovimientosDetallesItemRel( $movimientosDetallesItemRel ): void
    {
        $this->movimientosDetallesItemRel = $movimientosDetallesItemRel;
    }

    /**
     * @return mixed
     */
    public function getLotesItemRel()
    {
        return $this->lotesItemRel;
    }

    /**
     * @param mixed $lotesItemRel
     */
    public function setLotesItemRel( $lotesItemRel ): void
    {
        $this->lotesItemRel = $lotesItemRel;
    }

    /**
     * @return mixed
     */
    public function getPedidosDetallesItemRel()
    {
        return $this->pedidosDetallesItemRel;
    }

    /**
     * @param mixed $pedidosDetallesItemRel
     */
    public function setPedidosDetallesItemRel( $pedidosDetallesItemRel ): void
    {
        $this->pedidosDetallesItemRel = $pedidosDetallesItemRel;
    }

    /**
     * @return mixed
     */
    public function getRemisionesDetallesItemRel()
    {
        return $this->remisionesDetallesItemRel;
    }

    /**
     * @param mixed $remisionesDetallesItemRel
     */
    public function setRemisionesDetallesItemRel( $remisionesDetallesItemRel ): void
    {
        $this->remisionesDetallesItemRel = $remisionesDetallesItemRel;
    }

    /**
     * @return mixed
     */
    public function getPreciosDetallesItemRel()
    {
        return $this->preciosDetallesItemRel;
    }

    /**
     * @param mixed $preciosDetallesItemRel
     */
    public function setPreciosDetallesItemRel( $preciosDetallesItemRel ): void
    {
        $this->preciosDetallesItemRel = $preciosDetallesItemRel;
    }

    /**
     * @return mixed
     */
    public function getInventariosValorizadosItemRel()
    {
        return $this->inventariosValorizadosItemRel;
    }

    /**
     * @param mixed $inventariosValorizadosItemRel
     */
    public function setInventariosValorizadosItemRel( $inventariosValorizadosItemRel ): void
    {
        $this->inventariosValorizadosItemRel = $inventariosValorizadosItemRel;
    }

    /**
     * @return mixed
     */
    public function getCotizacionesDetallesItemRel()
    {
        return $this->cotizacionesDetallesItemRel;
    }

    /**
     * @param mixed $cotizacionesDetallesItemRel
     */
    public function setCotizacionesDetallesItemRel( $cotizacionesDetallesItemRel ): void
    {
        $this->cotizacionesDetallesItemRel = $cotizacionesDetallesItemRel;
    }

    /**
     * @return mixed
     */
    public function getImportacionesDetallesItemRel()
    {
        return $this->importacionesDetallesItemRel;
    }

    /**
     * @param mixed $importacionesDetallesItemRel
     */
    public function setImportacionesDetallesItemRel( $importacionesDetallesItemRel ): void
    {
        $this->importacionesDetallesItemRel = $importacionesDetallesItemRel;
    }



}

