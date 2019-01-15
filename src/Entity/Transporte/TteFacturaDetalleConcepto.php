<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteFacturaDetalleConceptoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteFacturaDetalleConcepto
{
    public $infoLog = [
        "primaryKey" => "codigoFacturaDetalleConceptoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoFacturaDetalleConceptoPk;

    /**
     * @ORM\Column(name="codigo_factura_fk", type="integer", nullable=true)
     */
    private $codigoFacturaFk;

    /**
     * @ORM\Column(name="codigo_factura_concepto_fk", type="string", length=20, nullable=true)
     */
    private $codigoFacturaConceptoFk;

    /**
     * @ORM\Column(name="cantidad", type="float", options={"default" : 0})
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
     * @ORM\Column(name="vr_iva", type="float", options={"default" : 0})
     */
    private $vrIva = 0;

    /**
     * @ORM\Column(name="vr_total", type="float", options={"default" : 0})
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="porcentaje_iva", type="float", options={"default" : 0})
     */
    private $porcentajeIva = 0;

    /**
     * @ORM\Column(name="codigo_impuesto_retencion_fk", type="string", length=3, nullable=true)
     */
    private $codigoImpuestoRetencionFk;

    /**
     * @ORM\Column(name="codigo_impuesto_iva_fk", type="string", length=3, nullable=true)
     */
    private $codigoImpuestoIvaFk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteFactura", inversedBy="facturasDetallesConcetosFacturaRel")
     * @ORM\JoinColumn(name="codigo_factura_fk", referencedColumnName="codigo_factura_pk")
     */
    private $facturaRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteFacturaConceptoDetalle", inversedBy="facturasDetallesConcetosFacturaConceptoDetalleRel")
     * @ORM\JoinColumn(name="codigo_factura_concepto_detalle_fk", referencedColumnName="codigo_factura_concepto_detalle_pk")
     */
    private $facturaConceptoDetalleRel;

    /**
     * @return mixed
     */
    public function getCodigoFacturaDetalleConceptoPk()
    {
        return $this->codigoFacturaDetalleConceptoPk;
    }

    /**
     * @param mixed $codigoFacturaDetalleConceptoPk
     */
    public function setCodigoFacturaDetalleConceptoPk( $codigoFacturaDetalleConceptoPk ): void
    {
        $this->codigoFacturaDetalleConceptoPk = $codigoFacturaDetalleConceptoPk;
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
    public function setCodigoFacturaFk( $codigoFacturaFk ): void
    {
        $this->codigoFacturaFk = $codigoFacturaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoFacturaConceptoFk()
    {
        return $this->codigoFacturaConceptoFk;
    }

    /**
     * @param mixed $codigoFacturaConceptoFk
     */
    public function setCodigoFacturaConceptoFk( $codigoFacturaConceptoFk ): void
    {
        $this->codigoFacturaConceptoFk = $codigoFacturaConceptoFk;
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
    public function getVrPrecio()
    {
        return $this->vrPrecio;
    }

    /**
     * @param mixed $vrPrecio
     */
    public function setVrPrecio( $vrPrecio ): void
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
    public function setVrSubtotal( $vrSubtotal ): void
    {
        $this->vrSubtotal = $vrSubtotal;
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
    public function setVrIva( $vrIva ): void
    {
        $this->vrIva = $vrIva;
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
    public function setVrTotal( $vrTotal ): void
    {
        $this->vrTotal = $vrTotal;
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
    public function getFacturaRel()
    {
        return $this->facturaRel;
    }

    /**
     * @param mixed $facturaRel
     */
    public function setFacturaRel( $facturaRel ): void
    {
        $this->facturaRel = $facturaRel;
    }

    /**
     * @return mixed
     */
    public function getFacturaConceptoDetalleRel()
    {
        return $this->facturaConceptoDetalleRel;
    }

    /**
     * @param mixed $facturaConceptoDetalleRel
     */
    public function setFacturaConceptoDetalleRel( $facturaConceptoDetalleRel ): void
    {
        $this->facturaConceptoDetalleRel = $facturaConceptoDetalleRel;
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
    public function getCodigoImpuestoIvaFk()
    {
        return $this->codigoImpuestoIvaFk;
    }

    /**
     * @param mixed $codigoImpuestoIvaFk
     */
    public function setCodigoImpuestoIvaFk( $codigoImpuestoIvaFk ): void
    {
        $this->codigoImpuestoIvaFk = $codigoImpuestoIvaFk;
    }


}

