<?php

namespace App\Entity\Compra;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Compra\ComCompraDetalleRepository")
 */
class ComCompraDetalle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="codigo_compra_detalle_pk",type="integer")
     */
    private $codigoCompraDetallePk;

    /**
     * @ORM\Column(name="codigo_compra_fk" , type="integer")
     */
    private $codigoCompraFk;

    /**
     * @ORM\Column(name="codigo_concepto_fk" , type="integer")
     */
    private $codigoConceptoFk;

    /**
     * @ORM\Column(name="codigo_retencion_tipo_fk" , type="integer", nullable=true)
     */
    private $codigoRetencionTipoFk;

    /**
     * @ORM\Column(name="cantidad" , type="integer")
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="vr_precio_unitario" , type="float", nullable=true)
     */
    private $vrPrecioUnitario = 0;

    /**
     * @ORM\Column(name="vr_subtotal" , type="float" ,nullable=true)
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="por_descuento", type="float", nullable=true)
     */
    private $porDescuento = 0;

    /**
     * @ORM\Column(name="vr_descuento", type="float", nullable=true)
     */
    private $vrDescuento = 0;

    /**
     * @ORM\Column(name="por_iva" ,type="float" ,nullable=true)
     */
    private $porIva = 0;

    /**
     * @ORM\Column(name="vr_iva" , type="float" ,nullable=true)
     */
    private $vrIva = 0;

    /**
     * @ORM\Column(name="vr_retencion", type="float" ,nullable=true)
     */
    private $vrRetencion = 0;

    /**
     * @ORM\Column(name="vr_total" ,type="float" ,nullable=true)
     */
    private $vrTotal = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compra\ComCompra" , inversedBy="compraDetallesCompraRel")
     * @ORM\JoinColumn(name="codigo_compra_fk" , referencedColumnName="codigo_compra_pk")
     */
    private $compraRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compra\ComConcepto", inversedBy="comprasDetallesConceptoRel")
     * @ORM\JoinColumn(name="codigo_concepto_fk" , referencedColumnName="codigo_concepto_pk")
     */
    private $conceptoRel;



    /**
     * @return mixed
     */
    public function getCodigoCompraDetallePk()
    {
        return $this->codigoCompraDetallePk;
    }

    /**
     * @param mixed $codigoCompraDetallePk
     */
    public function setCodigoCompraDetallePk($codigoCompraDetallePk): void
    {
        $this->codigoCompraDetallePk = $codigoCompraDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCompraFk()
    {
        return $this->codigoCompraFk;
    }

    /**
     * @param mixed $codigoCompraFk
     */
    public function setCodigoCompraFk($codigoCompraFk): void
    {
        $this->codigoCompraFk = $codigoCompraFk;
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
    public function getCodigoRetencionTipoFk()
    {
        return $this->codigoRetencionTipoFk;
    }

    /**
     * @param mixed $codigoRetencionTipoFk
     */
    public function setCodigoRetencionTipoFk($codigoRetencionTipoFk): void
    {
        $this->codigoRetencionTipoFk = $codigoRetencionTipoFk;
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
    public function getVrPrecioUnitario()
    {
        return $this->vrPrecioUnitario;
    }

    /**
     * @param mixed $vrPrecioUnitario
     */
    public function setVrPrecioUnitario($vrPrecioUnitario): void
    {
        $this->vrPrecioUnitario = $vrPrecioUnitario;
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
    public function getPorDescuento()
    {
        return $this->porDescuento;
    }

    /**
     * @param mixed $porDescuento
     */
    public function setPorDescuento($porDescuento): void
    {
        $this->porDescuento = $porDescuento;
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
    public function getPorIva()
    {
        return $this->porIva;
    }

    /**
     * @param mixed $porIva
     */
    public function setPorIva($porIva): void
    {
        $this->porIva = $porIva;
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
    public function getVrRetencion()
    {
        return $this->vrRetencion;
    }

    /**
     * @param mixed $vrRetencion
     */
    public function setVrRetencion($vrRetencion): void
    {
        $this->vrRetencion = $vrRetencion;
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
    public function getCompraRel()
    {
        return $this->compraRel;
    }

    /**
     * @param mixed $compraRel
     */
    public function setCompraRel($compraRel): void
    {
        $this->compraRel = $compraRel;
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


}
