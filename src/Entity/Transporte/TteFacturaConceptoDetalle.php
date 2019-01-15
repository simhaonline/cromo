<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteFacturaConceptoDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteFacturaConceptoDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoFacturaConceptoDetallePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoFacturaConceptoDetallePk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_impuesto_retencion_fk", type="string", length=3, nullable=true)
     */
    private $codigoImpuestoRetencionFk;

    /**
     * @ORM\Column(name="codigo_impuesto_iva_venta_fk", type="string", length=3, nullable=true)
     */
    private $codigoImpuestoIvaVentaFk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenImpuesto", inversedBy="facturasConceptosDetallesImpuestoRetencionRel")
     * @ORM\JoinColumn(name="codigo_impuesto_retencion_fk",referencedColumnName="codigo_impuesto_pk")
     */
    protected $impuestoRetencionRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenImpuesto", inversedBy="facturasConceptosDetallesImpuestoIvaVentaRel")
     * @ORM\JoinColumn(name="codigo_impuesto_iva_venta_fk",referencedColumnName="codigo_impuesto_pk")
     */
    protected $impuestoIvaVentaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteFacturaDetalleConcepto", mappedBy="facturaConceptoDetalleRel")
     */
    protected $facturasDetallesConcetosFacturaConceptoDetalleRel;

    /**
     * @return mixed
     */
    public function getCodigoFacturaConceptoDetallePk()
    {
        return $this->codigoFacturaConceptoDetallePk;
    }

    /**
     * @param mixed $codigoFacturaConceptoDetallePk
     */
    public function setCodigoFacturaConceptoDetallePk( $codigoFacturaConceptoDetallePk ): void
    {
        $this->codigoFacturaConceptoDetallePk = $codigoFacturaConceptoDetallePk;
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
    public function getFacturasDetallesConcetosFacturaConceptoDetalleRel()
    {
        return $this->facturasDetallesConcetosFacturaConceptoDetalleRel;
    }

    /**
     * @param mixed $facturasDetallesConcetosFacturaConceptoDetalleRel
     */
    public function setFacturasDetallesConcetosFacturaConceptoDetalleRel( $facturasDetallesConcetosFacturaConceptoDetalleRel ): void
    {
        $this->facturasDetallesConcetosFacturaConceptoDetalleRel = $facturasDetallesConcetosFacturaConceptoDetalleRel;
    }


}

