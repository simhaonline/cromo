<?php

namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenFacturaDetalleRepository")
 */
class GenFacturaDetalle
{
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
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_item", type="integer", nullable=true)
     */
    private $codigoItem;

    /**
     * @ORM\Column(name="nombre_item", type="string", length=10, nullable=true)
     */
    private $nombreItem;

    /**
     * @ORM\Column(name="fecha_vencimiento", type="date", nullable=true)
     */
    private $fechaVencimiento;

    /**
     * @ORM\Column(name="cantidad", type="integer", options={"default" : 0})
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="cantidad_operada", type="integer", options={"default" : 0})
     */
    private $cantidadOperada = 0;

    /**
     * @ORM\Column(name="porcentaje_iva", type="float", nullable=true, options={"default" : 0})
     */
    private $porcentajeIva = 0;

    /**
     * @ORM\Column(name="porcentaje_descuento", type="float", nullable=true, options={"default" : 0})
     */
    private $porcentajeDescuento = 0;

    /**
     * @ORM\Column(name="vr_iva", type="float", nullable=true)
     */
    private $vrIva = 0;

    /**
     * @ORM\Column(name="vr_precio", type="float", options={"default" : 0})
     */
    private $vrPrecio = 0;

    /**
     * @ORM\Column(name="vr_subtotal", type="float", options={"default" : 0})
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="vr_descuento", type="float", options={"default" : 0})
     */
    private $vrDescuento = 0;

    /**
     * @ORM\Column(name="vr_neto", type="float", options={"default" : 0})
     */
    private $vrNeto = 0;

    /**
     * @ORM\Column(name="vr_total", type="float", options={"default" : 0})
     */
    private $vrTotal = 0;

    /**
     * @ORM\ManyToOne(targetEntity="GenFactura", inversedBy="facturasDetallesFacturaRel")
     * @ORM\JoinColumn(name="codigo_factura_fk", referencedColumnName="codigo_factura_pk")
     */
    protected $facturaRel;

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
    public function getCodigoItem()
    {
        return $this->codigoItem;
    }

    /**
     * @param mixed $codigoItem
     */
    public function setCodigoItem($codigoItem): void
    {
        $this->codigoItem = $codigoItem;
    }

    /**
     * @return mixed
     */
    public function getNombreItem()
    {
        return $this->nombreItem;
    }

    /**
     * @param mixed $nombreItem
     */
    public function setNombreItem($nombreItem): void
    {
        $this->nombreItem = $nombreItem;
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
    public function setFechaVencimiento($fechaVencimiento): void
    {
        $this->fechaVencimiento = $fechaVencimiento;
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
}
