<?php

namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurFacturaDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurFacturaDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoFacturaDetallePk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaDetallePk;

    /**
     * @ORM\Column(name="codigo_factura_fk", type="integer", nullable=true)
     */
    private $codigoFacturaFk;

    /**
     * @ORM\Column(name="codigo_item_fk", type="integer", nullable=true)
     */
    private $codigoItemFk;

    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */
    private $codigoPuestoFk;

    /**
     * @ORM\Column(name="codigo_concepto_fk", type="integer", nullable=true)
     */
    private $codigoConceptoFk;

    /**
     * @ORM\Column(name="codigo_impuesto_retencion_fk", type="string", length=3, nullable=true)
     */
    private $codigoImpuestoRetencionFk;

    /**
     * @ORM\Column(name="codigo_impuesto_iva_fk", type="string", length=5, nullable=true)
     */
    private $codigoImpuestoIvaFk;

    /**
     * @ORM\Column(name="codigo_pedido_detalle_fk", type="integer", nullable=true)
     */
    private $codigoPedidoDetalleFk;

    /**
     * @ORM\Column(name="porcentaje_iva", type="float", nullable=true, options={"default" : 0})
     */
    private $porcentajeIva = 0;

    /**
     * @ORM\Column(name="porcentaje_base_iva", type="float", nullable=true, options={"default" : 0})
     */
    private $porcentajeBaseIva = 0;

    /**
     * @ORM\Column(name="vr_base_iva", type="float", nullable=true, options={"default" : 0})
     */
    private $vrBaseIva = 0;

    /**
     * @ORM\Column(name="vr_iva", type="float", nullable=true)
     */
    private $vrIva = 0;

    /**
     * @ORM\Column(name="cantidad", type="integer", options={"default" : 0})
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="vr_precio", type="float", options={"default" : 0})
     */
    private $vrPrecio = 0;

    /**
     * @ORM\Column(name="vr_subtotal", type="float", options={"default" : 0})
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="vr_total", type="float", options={"default" : 0})
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="vr_retencion_fuente", type="float", options={"default" : 0})
     */
    private $vrRetencionFuente = 0;

    /**
     * @ORM\Column(name="detalle", type="string", length=300, nullable=true)
     */
    private $detalle;

    /**
     * @ORM\ManyToOne(targetEntity="TurFactura", inversedBy="facturasDetallesFacturaRel")
     * @ORM\JoinColumn(name="codigo_factura_fk", referencedColumnName="codigo_factura_pk")
     */
    protected $facturaRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurItem", inversedBy="facturasDetallesItemRel")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurPedidoDetalle", inversedBy="facturasDetallesPedidoDetalleRel")
     * @ORM\JoinColumn(name="codigo_pedido_detalle_fk", referencedColumnName="codigo_pedido_detalle_pk")
     */
    protected $pedidoDetalleRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurPuesto", inversedBy="facturasDetallesPuestoRel")
     * @ORM\JoinColumn(name="codigo_puesto_fk", referencedColumnName="codigo_puesto_pk")
     */
    protected $puestoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurConcepto", inversedBy="facturasDetallesConceptoRel")
     * @ORM\JoinColumn(name="codigo_concepto_fk", referencedColumnName="codigo_concepto_pk")
     */
    protected $conceptoRel;

    /**
     * @return mixed
     */
    public function getCodigoFacturaDetallePk()
    {
        return $this->codigoFacturaDetallePk;
    }

    /**
     * @param mixed $codigoFacturaDetallePk
     */
    public function setCodigoFacturaDetallePk($codigoFacturaDetallePk): void
    {
        $this->codigoFacturaDetallePk = $codigoFacturaDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoFacturaFk()
    {
        return $this->codigoFacturaFk;
    }

    /**
     * @param mixed $codigoFacturaFk
     */
    public function setCodigoFacturaFk($codigoFacturaFk): void
    {
        $this->codigoFacturaFk = $codigoFacturaFk;
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
    public function getCodigoPuestoFk()
    {
        return $this->codigoPuestoFk;
    }

    /**
     * @param mixed $codigoPuestoFk
     */
    public function setCodigoPuestoFk($codigoPuestoFk): void
    {
        $this->codigoPuestoFk = $codigoPuestoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoConceptoFk()
    {
        return $this->codigoConceptoFk;
    }

    /**
     * @param mixed $codigoConceptoFk
     */
    public function setCodigoConceptoFk($codigoConceptoFk): void
    {
        $this->codigoConceptoFk = $codigoConceptoFk;
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
    public function setCodigoImpuestoRetencionFk($codigoImpuestoRetencionFk): void
    {
        $this->codigoImpuestoRetencionFk = $codigoImpuestoRetencionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoImpuestoIvaFk()
    {
        return $this->codigoImpuestoIvaFk;
    }

    /**
     * @param mixed $codigoImpuestoIvaFk
     */
    public function setCodigoImpuestoIvaFk($codigoImpuestoIvaFk): void
    {
        $this->codigoImpuestoIvaFk = $codigoImpuestoIvaFk;
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
    public function getPorcentajeBaseIva()
    {
        return $this->porcentajeBaseIva;
    }

    /**
     * @param mixed $porcentajeBaseIva
     */
    public function setPorcentajeBaseIva($porcentajeBaseIva): void
    {
        $this->porcentajeBaseIva = $porcentajeBaseIva;
    }

    /**
     * @return mixed
     */
    public function getVrBaseIva()
    {
        return $this->vrBaseIva;
    }

    /**
     * @param mixed $vrBaseIva
     */
    public function setVrBaseIva($vrBaseIva): void
    {
        $this->vrBaseIva = $vrBaseIva;
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
    public function getVrRetencionFuente()
    {
        return $this->vrRetencionFuente;
    }

    /**
     * @param mixed $vrRetencionFuente
     */
    public function setVrRetencionFuente($vrRetencionFuente): void
    {
        $this->vrRetencionFuente = $vrRetencionFuente;
    }

    /**
     * @return mixed
     */
    public function getFacturaRel()
    {
        return $this->facturaRel;
    }

    /**
     * @param mixed $facturaRel
     */
    public function setFacturaRel($facturaRel): void
    {
        $this->facturaRel = $facturaRel;
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
    public function getPuestoRel()
    {
        return $this->puestoRel;
    }

    /**
     * @param mixed $puestoRel
     */
    public function setPuestoRel($puestoRel): void
    {
        $this->puestoRel = $puestoRel;
    }

    /**
     * @return mixed
     */
    public function getConceptoRel()
    {
        return $this->conceptoRel;
    }

    /**
     * @param mixed $conceptoRel
     */
    public function setConceptoRel($conceptoRel): void
    {
        $this->conceptoRel = $conceptoRel;
    }

    /**
     * @return array
     */
    public function getInfoLog(): array
    {
        return $this->infoLog;
    }

    /**
     * @param array $infoLog
     */
    public function setInfoLog(array $infoLog): void
    {
        $this->infoLog = $infoLog;
    }

    /**
     * @return mixed
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * @param mixed $detalle
     */
    public function setDetalle($detalle): void
    {
        $this->detalle = $detalle;
    }



}
