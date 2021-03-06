<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvImportacionDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class InvImportacionDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoImportacionDetallePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="codigo_importacion_detalle_pk" , type="integer")
     */
    private $codigoImportacionDetallePk;

    /**
     * @ORM\Column(name="codigo_importacion_fk" , type="integer")
     */
    private $codigoImportacionFk;

    /**
     * @ORM\Column(name="codigo_item_fk" , type="integer")
     */
    private $codigoItemFk;

    /**
     * @ORM\Column(name="cantidad" , type="float")
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="cantidad_afectada",options={"default" : 0}, type="integer", nullable=true)
     */
    private $cantidadAfectada = 0;

    /**
     * @ORM\Column(name="cantidad_pendiente",options={"default" : 0}, type="integer", nullable=true)
     */
    private $cantidadPendiente = 0;

    /**
     * @ORM\Column(name="vr_precio_extranjero",type="float")
     */
    private $vrPrecioExtranjero = 0;

    /**
     * @ORM\Column(name="vr_subtotal_extranjero", type="float", options={"default" : 0}, nullable=true)
     */
    private $vrSubtotalExtranjero = 0;

    /**
     * @ORM\Column(name="porcentaje_iva_extranjero", type="float", options={"default" : 0}, nullable=true)
     */
    private $porcentajeIvaExtranjero = 0;

    /**
     * @ORM\Column(name="vr_iva_extranjero", type="float", options={"default" : 0}, nullable=true)
     */
    private $vrIvaExtranjero = 0;

    /**
     * @ORM\Column(name="vr_neto_extranjero", type="float", options={"default" : 0},)
     */
    private $vrNetoExtranjero = 0;

    /**
     * @ORM\Column(name="vr_total_extranjero", type="float", options={"default" : 0}, nullable=true)
     */
    private $vrTotalExtranjero = 0;

    /**
     * @ORM\Column(name="vr_precio_local_total" ,type="float")
     */
    private $vrPrecioLocalTotal = 0;

    /**
     * Precio bruto
     * @ORM\Column(name="vr_precio_local" ,type="float")
     */
    private $vrPrecioLocal = 0;

    /**
     * @ORM\Column(name="vr_subtotal_local", type="float", options={"default" : 0}, nullable=true)
     */
    private $vrSubtotalLocal = 0;

    /**
     * @ORM\Column(name="vr_subtotal_local_bruto", type="float", options={"default" : 0}, nullable=true)
     */
    private $vrSubtotalLocalBruto = 0;

    /**
     * @ORM\Column(name="porcentaje_iva_local", type="float", options={"default" : 0}, nullable=true)
     */
    private $porcentajeIvaLocal = 0;

    /**
     * @ORM\Column(name="vr_iva_local", type="float", options={"default" : 0}, nullable=true)
     */
    private $vrIvaLocal = 0;

    /**
     * @ORM\Column(name="vr_neto_local", type="float", options={"default" : 0},)
     */
    private $vrNetoLocal = 0;

    /**
     * @ORM\Column(name="vr_total_local", type="float", options={"default" : 0}, nullable=true)
     */
    private $vrTotalLocal = 0;

    /**
     * @ORM\Column(name="porcentaje_participa_costo", type="float", options={"default" : 0}, nullable=true)
     */
    private $porcentajeParticipaCosto = 0;

    /**
     * @ORM\Column(name="vr_costo_participa", type="float", options={"default" : 0}, nullable=true)
     */
    private $vrCostoParticipa = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Inventario\InvImportacion", inversedBy="importacionesDetallesImportacionRel")
     * @ORM\JoinColumn(name="codigo_importacion_fk", referencedColumnName="codigo_importacion_pk")
     */
    protected $importacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="importacionesDetallesItemRel")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvMovimientoDetalle", mappedBy="importacionDetalleRel")
     */
    protected $movimientosDetallesImportacionDetalleRel;

    /**
     * @return mixed
     */
    public function getCodigoImportacionDetallePk()
    {
        return $this->codigoImportacionDetallePk;
    }

    /**
     * @param mixed $codigoImportacionDetallePk
     */
    public function setCodigoImportacionDetallePk( $codigoImportacionDetallePk ): void
    {
        $this->codigoImportacionDetallePk = $codigoImportacionDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoImportacionFk()
    {
        return $this->codigoImportacionFk;
    }

    /**
     * @param mixed $codigoImportacionFk
     */
    public function setCodigoImportacionFk( $codigoImportacionFk ): void
    {
        $this->codigoImportacionFk = $codigoImportacionFk;
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
    public function setCodigoItemFk( $codigoItemFk ): void
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
    public function setCantidad( $cantidad ): void
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return mixed
     */
    public function getVrPrecioExtranjero()
    {
        return $this->vrPrecioExtranjero;
    }

    /**
     * @param mixed $vrPrecioExtranjero
     */
    public function setVrPrecioExtranjero( $vrPrecioExtranjero ): void
    {
        $this->vrPrecioExtranjero = $vrPrecioExtranjero;
    }

    /**
     * @return mixed
     */
    public function getVrSubtotalExtranjero()
    {
        return $this->vrSubtotalExtranjero;
    }

    /**
     * @param mixed $vrSubtotalExtranjero
     */
    public function setVrSubtotalExtranjero( $vrSubtotalExtranjero ): void
    {
        $this->vrSubtotalExtranjero = $vrSubtotalExtranjero;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeIvaExtranjero()
    {
        return $this->porcentajeIvaExtranjero;
    }

    /**
     * @param mixed $porcentajeIvaExtranjero
     */
    public function setPorcentajeIvaExtranjero( $porcentajeIvaExtranjero ): void
    {
        $this->porcentajeIvaExtranjero = $porcentajeIvaExtranjero;
    }

    /**
     * @return mixed
     */
    public function getVrIvaExtranjero()
    {
        return $this->vrIvaExtranjero;
    }

    /**
     * @param mixed $vrIvaExtranjero
     */
    public function setVrIvaExtranjero( $vrIvaExtranjero ): void
    {
        $this->vrIvaExtranjero = $vrIvaExtranjero;
    }

    /**
     * @return mixed
     */
    public function getVrNetoExtranjero()
    {
        return $this->vrNetoExtranjero;
    }

    /**
     * @param mixed $vrNetoExtranjero
     */
    public function setVrNetoExtranjero( $vrNetoExtranjero ): void
    {
        $this->vrNetoExtranjero = $vrNetoExtranjero;
    }

    /**
     * @return mixed
     */
    public function getVrTotalExtranjero()
    {
        return $this->vrTotalExtranjero;
    }

    /**
     * @param mixed $vrTotalExtranjero
     */
    public function setVrTotalExtranjero( $vrTotalExtranjero ): void
    {
        $this->vrTotalExtranjero = $vrTotalExtranjero;
    }

    /**
     * @return mixed
     */
    public function getVrPrecioLocal()
    {
        return $this->vrPrecioLocal;
    }

    /**
     * @param mixed $vrPrecioLocal
     */
    public function setVrPrecioLocal( $vrPrecioLocal ): void
    {
        $this->vrPrecioLocal = $vrPrecioLocal;
    }

    /**
     * @return mixed
     */
    public function getVrSubtotalLocal()
    {
        return $this->vrSubtotalLocal;
    }

    /**
     * @param mixed $vrSubtotalLocal
     */
    public function setVrSubtotalLocal( $vrSubtotalLocal ): void
    {
        $this->vrSubtotalLocal = $vrSubtotalLocal;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeIvaLocal()
    {
        return $this->porcentajeIvaLocal;
    }

    /**
     * @param mixed $porcentajeIvaLocal
     */
    public function setPorcentajeIvaLocal( $porcentajeIvaLocal ): void
    {
        $this->porcentajeIvaLocal = $porcentajeIvaLocal;
    }

    /**
     * @return mixed
     */
    public function getVrIvaLocal()
    {
        return $this->vrIvaLocal;
    }

    /**
     * @param mixed $vrIvaLocal
     */
    public function setVrIvaLocal( $vrIvaLocal ): void
    {
        $this->vrIvaLocal = $vrIvaLocal;
    }

    /**
     * @return mixed
     */
    public function getVrNetoLocal()
    {
        return $this->vrNetoLocal;
    }

    /**
     * @param mixed $vrNetoLocal
     */
    public function setVrNetoLocal( $vrNetoLocal ): void
    {
        $this->vrNetoLocal = $vrNetoLocal;
    }

    /**
     * @return mixed
     */
    public function getVrTotalLocal()
    {
        return $this->vrTotalLocal;
    }

    /**
     * @param mixed $vrTotalLocal
     */
    public function setVrTotalLocal( $vrTotalLocal ): void
    {
        $this->vrTotalLocal = $vrTotalLocal;
    }

    /**
     * @return mixed
     */
    public function getImportacionRel()
    {
        return $this->importacionRel;
    }

    /**
     * @param mixed $importacionRel
     */
    public function setImportacionRel( $importacionRel ): void
    {
        $this->importacionRel = $importacionRel;
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
    public function setItemRel( $itemRel ): void
    {
        $this->itemRel = $itemRel;
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
    public function setCantidadAfectada( $cantidadAfectada ): void
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
    public function setCantidadPendiente( $cantidadPendiente ): void
    {
        $this->cantidadPendiente = $cantidadPendiente;
    }

    /**
     * @return mixed
     */
    public function getMovimientosDetallesImportacionDetalleRel()
    {
        return $this->movimientosDetallesImportacionDetalleRel;
    }

    /**
     * @param mixed $movimientosDetallesImportacionDetalleRel
     */
    public function setMovimientosDetallesImportacionDetalleRel($movimientosDetallesImportacionDetalleRel): void
    {
        $this->movimientosDetallesImportacionDetalleRel = $movimientosDetallesImportacionDetalleRel;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeParticipaCosto()
    {
        return $this->porcentajeParticipaCosto;
    }

    /**
     * @param mixed $porcentajeParticipaCosto
     */
    public function setPorcentajeParticipaCosto($porcentajeParticipaCosto): void
    {
        $this->porcentajeParticipaCosto = $porcentajeParticipaCosto;
    }

    /**
     * @return mixed
     */
    public function getVrCostoParticipa()
    {
        return $this->vrCostoParticipa;
    }

    /**
     * @param mixed $vrCostoParticipa
     */
    public function setVrCostoParticipa($vrCostoParticipa): void
    {
        $this->vrCostoParticipa = $vrCostoParticipa;
    }

    /**
     * @return mixed
     */
    public function getVrPrecioLocalTotal()
    {
        return $this->vrPrecioLocalTotal;
    }

    /**
     * @param mixed $vrPrecioLocalTotal
     */
    public function setVrPrecioLocalTotal( $vrPrecioLocalTotal ): void
    {
        $this->vrPrecioLocalTotal = $vrPrecioLocalTotal;
    }

    /**
     * @return mixed
     */
    public function getVrSubtotalLocalBruto()
    {
        return $this->vrSubtotalLocalBruto;
    }

    /**
     * @param mixed $vrSubtotalLocalBruto
     */
    public function setVrSubtotalLocalBruto($vrSubtotalLocalBruto): void
    {
        $this->vrSubtotalLocalBruto = $vrSubtotalLocalBruto;
    }



}
