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
    private $vrValor = 0;

    /**
     * @ORM\Column(name="codigo_impuesto_retencion_fk", type="string", length=3, nullable=true)
     */
    private $codigoImpuestoRetencionFk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteFactura", inversedBy="facturasDetallesConcetosFacturaRel")
     * @ORM\JoinColumn(name="codigo_factura_fk", referencedColumnName="codigo_factura_pk")
     */
    private $facturaRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteFacturaConcepto", inversedBy="facturasDetallesConcetosRel")
     * @ORM\JoinColumn(name="codigo_factura_concepto_fk", referencedColumnName="codigo_factura_concepto_pk")
     */
    private $facturaConceptoRel;

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
    public function getCodigoFacturaDetalleConceptoPk()
    {
        return $this->codigoFacturaDetalleConceptoPk;
    }

    /**
     * @param mixed $codigoFacturaDetalleConceptoPk
     */
    public function setCodigoFacturaDetalleConceptoPk($codigoFacturaDetalleConceptoPk): void
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
    public function setCodigoFacturaFk($codigoFacturaFk): void
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
    public function setCodigoFacturaConceptoFk($codigoFacturaConceptoFk): void
    {
        $this->codigoFacturaConceptoFk = $codigoFacturaConceptoFk;
    }

    /**
     * @return mixed
     */
    public function getVrValor()
    {
        return $this->vrValor;
    }

    /**
     * @param mixed $vrValor
     */
    public function setVrValor($vrValor): void
    {
        $this->vrValor = $vrValor;
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
    public function getFacturaConceptoRel()
    {
        return $this->facturaConceptoRel;
    }

    /**
     * @param mixed $facturaConceptoRel
     */
    public function setFacturaConceptoRel($facturaConceptoRel): void
    {
        $this->facturaConceptoRel = $facturaConceptoRel;
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



}

