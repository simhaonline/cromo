<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteFacturaConceptoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteFacturaConcepto
{
    public $infoLog = [
        "primaryKey" => "codigoFacturaConceptoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoFacturaConceptoPk;

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
     * @ORM\OneToMany(targetEntity="TteFactura", mappedBy="facturaConceptoRel")
     */
    protected $facturasFacturaConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteFacturaDetalleConcepto", mappedBy="facturaConceptoRel")
     */
    protected $facturasDetallesConcetosRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenImpuesto", inversedBy="facturaConceptosImpuestoRetencionRel")
     * @ORM\JoinColumn(name="codigo_impuesto_retencion_fk",referencedColumnName="codigo_impuesto_pk")
     */
    protected $impuestoRetencionRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenImpuesto", inversedBy="facturaConceptosImpuestoIvaVentaRel")
     * @ORM\JoinColumn(name="codigo_impuesto_iva_venta_fk",referencedColumnName="codigo_impuesto_pk")
     */
    protected $impuestoIvaVentaRel;

    /**
     * @return mixed
     */
    public function getCodigoFacturaConceptoPk()
    {
        return $this->codigoFacturaConceptoPk;
    }

    /**
     * @param mixed $codigoFacturaConceptoPk
     */
    public function setCodigoFacturaConceptoPk( $codigoFacturaConceptoPk ): void
    {
        $this->codigoFacturaConceptoPk = $codigoFacturaConceptoPk;
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
    public function getFacturasFacturaConceptoRel()
    {
        return $this->facturasFacturaConceptoRel;
    }

    /**
     * @param mixed $facturasFacturaConceptoRel
     */
    public function setFacturasFacturaConceptoRel( $facturasFacturaConceptoRel ): void
    {
        $this->facturasFacturaConceptoRel = $facturasFacturaConceptoRel;
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
    public function getFacturasDetallesConcetosRel()
    {
        return $this->facturasDetallesConcetosRel;
    }

    /**
     * @param mixed $facturasDetallesConcetosRel
     */
    public function setFacturasDetallesConcetosRel($facturasDetallesConcetosRel): void
    {
        $this->facturasDetallesConcetosRel = $facturasDetallesConcetosRel;
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
    public function getCodigoImpuestoIvaVentaFk()
    {
        return $this->codigoImpuestoIvaVentaFk;
    }

    /**
     * @param mixed $codigoImpuestoIvaVentaFk
     */
    public function setCodigoImpuestoIvaVentaFk($codigoImpuestoIvaVentaFk): void
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
    public function setImpuestoRetencionRel($impuestoRetencionRel): void
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
    public function setImpuestoIvaVentaRel($impuestoIvaVentaRel): void
    {
        $this->impuestoIvaVentaRel = $impuestoIvaVentaRel;
    }



}

