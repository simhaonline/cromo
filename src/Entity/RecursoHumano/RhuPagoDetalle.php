<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_detalle")
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuPagoDetalleRepository")
 */
class RhuPagoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoDetallePk;

    /**
     * @ORM\Column(name="codigo_pago_fk", type="integer", nullable=true)
     */
    private $codigoPagoFk;

    /**
     * @ORM\Column(name="codigo_concepto_fk", type="string", length=10, nullable=true)
     */
    private $codigoConceptoFk;

    /**
     * @ORM\Column(name="vr_pago", type="float", nullable=true)
     */
    private $vrPago = 0;

    /**
     * @ORM\Column(name="operacion", type="integer")
     */
    private $operacion = 0;

    /**
     * @ORM\Column(name="vr_pago_operado", type="float")
     */
    private $vrPagoOperado = 0;

    /**
     * @ORM\Column(name="numero_horas", type="float")
     */
    private $numeroHoras = 0;

    /**
     * @ORM\Column(name="vr_hora", type="float")
     */
    private $vrHora = 0;

    /**
     * @ORM\Column(name="porcentaje_aplicado", type="float")
     */
    private $porcentajeAplicado = 0;

    /**
     * @ORM\Column(name="numero_dias", type="integer")
     */
    private $numeroDias = 0;

    /**
     * @ORM\Column(name="vr_dia", type="float")
     */
    private $vrDia = 0;

    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="detalle", type="string", length=250, nullable=true)
     */
    private $detalle;

    /**
     * @ORM\Column(name="vr_ingreso_base_cotizacion", type="float")
     */
    private $vrIngresoBaseCotizacion = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion", type="float")
     */
    private $vrIngresoBasePrestacion = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_cotizacion_salario", type="float")
     */
    private $vrIngresoBaseCotizacionSalario = 0;

    /**
     * @ORM\Column(name="vr_extra", type="float")
     */
    private $vrExtra = 0;

    /**
     * @ORM\Column(name="vr_suplementario", type="float", nullable=true)
     */
    private $vrSuplementario = 0;

    /**
     * @ORM\Column(name="vr_adicional_prestacional", type="float")
     */
    private $vrAdicionalPrestacional = 0;

    /**
     * @ORM\Column(name="vr_adicional_no_prestacional", type="float")
     */
    private $vrAdicionalNoPrestacional = 0;

    /**
     * @ORM\Column(name="dias_ausentismo", type="integer")
     */
    private $diasAusentismo = 0;

    /**
     * @ORM\Column(name="adicional", type="boolean")
     */
    private $adicional = 0;

    /**
     * @ORM\Column(name="prestacional", type="boolean")
     */
    private $prestacional = 0;

    /**
     * @ORM\Column(name="cotizacion", type="boolean")
     */
    private $cotizacion = 0;

    /**
     * @ORM\Column(name="salud", type="boolean")
     */
    private $salud = false;

    /**
     * @ORM\Column(name="pension", type="boolean")
     */
    private $pension = false;

    /**
     * @ORM\ManyToOne(targetEntity="RhuPago", inversedBy="pagosDetallesPagoRel")
     * @ORM\JoinColumn(name="codigo_pago_fk", referencedColumnName="codigo_pago_pk")
     */
    protected $pagoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuConcepto", inversedBy="pagosDetallesConceptoRel")
     * @ORM\JoinColumn(name="codigo_concepto_fk", referencedColumnName="codigo_concepto_pk")
     */
    protected $conceptoRel;

    /**
     * @return mixed
     */
    public function getCodigoPagoDetallePk()
    {
        return $this->codigoPagoDetallePk;
    }

    /**
     * @param mixed $codigoPagoDetallePk
     */
    public function setCodigoPagoDetallePk($codigoPagoDetallePk): void
    {
        $this->codigoPagoDetallePk = $codigoPagoDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPagoFk()
    {
        return $this->codigoPagoFk;
    }

    /**
     * @param mixed $codigoPagoFk
     */
    public function setCodigoPagoFk($codigoPagoFk): void
    {
        $this->codigoPagoFk = $codigoPagoFk;
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
    public function getVrPago()
    {
        return $this->vrPago;
    }

    /**
     * @param mixed $vrPago
     */
    public function setVrPago($vrPago): void
    {
        $this->vrPago = $vrPago;
    }

    /**
     * @return mixed
     */
    public function getOperacion()
    {
        return $this->operacion;
    }

    /**
     * @param mixed $operacion
     */
    public function setOperacion($operacion): void
    {
        $this->operacion = $operacion;
    }

    /**
     * @return mixed
     */
    public function getVrPagoOperado()
    {
        return $this->vrPagoOperado;
    }

    /**
     * @param mixed $vrPagoOperado
     */
    public function setVrPagoOperado($vrPagoOperado): void
    {
        $this->vrPagoOperado = $vrPagoOperado;
    }

    /**
     * @return mixed
     */
    public function getNumeroHoras()
    {
        return $this->numeroHoras;
    }

    /**
     * @param mixed $numeroHoras
     */
    public function setNumeroHoras($numeroHoras): void
    {
        $this->numeroHoras = $numeroHoras;
    }

    /**
     * @return mixed
     */
    public function getVrHora()
    {
        return $this->vrHora;
    }

    /**
     * @param mixed $vrHora
     */
    public function setVrHora($vrHora): void
    {
        $this->vrHora = $vrHora;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeAplicado()
    {
        return $this->porcentajeAplicado;
    }

    /**
     * @param mixed $porcentajeAplicado
     */
    public function setPorcentajeAplicado($porcentajeAplicado): void
    {
        $this->porcentajeAplicado = $porcentajeAplicado;
    }

    /**
     * @return mixed
     */
    public function getNumeroDias()
    {
        return $this->numeroDias;
    }

    /**
     * @param mixed $numeroDias
     */
    public function setNumeroDias($numeroDias): void
    {
        $this->numeroDias = $numeroDias;
    }

    /**
     * @return mixed
     */
    public function getVrDia()
    {
        return $this->vrDia;
    }

    /**
     * @param mixed $vrDia
     */
    public function setVrDia($vrDia): void
    {
        $this->vrDia = $vrDia;
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

    /**
     * @return mixed
     */
    public function getVrIngresoBaseCotizacion()
    {
        return $this->vrIngresoBaseCotizacion;
    }

    /**
     * @param mixed $vrIngresoBaseCotizacion
     */
    public function setVrIngresoBaseCotizacion($vrIngresoBaseCotizacion): void
    {
        $this->vrIngresoBaseCotizacion = $vrIngresoBaseCotizacion;
    }

    /**
     * @return mixed
     */
    public function getVrIngresoBasePrestacion()
    {
        return $this->vrIngresoBasePrestacion;
    }

    /**
     * @param mixed $vrIngresoBasePrestacion
     */
    public function setVrIngresoBasePrestacion($vrIngresoBasePrestacion): void
    {
        $this->vrIngresoBasePrestacion = $vrIngresoBasePrestacion;
    }

    /**
     * @return mixed
     */
    public function getVrIngresoBaseCotizacionSalario()
    {
        return $this->vrIngresoBaseCotizacionSalario;
    }

    /**
     * @param mixed $vrIngresoBaseCotizacionSalario
     */
    public function setVrIngresoBaseCotizacionSalario($vrIngresoBaseCotizacionSalario): void
    {
        $this->vrIngresoBaseCotizacionSalario = $vrIngresoBaseCotizacionSalario;
    }

    /**
     * @return mixed
     */
    public function getVrExtra()
    {
        return $this->vrExtra;
    }

    /**
     * @param mixed $vrExtra
     */
    public function setVrExtra($vrExtra): void
    {
        $this->vrExtra = $vrExtra;
    }

    /**
     * @return mixed
     */
    public function getVrSuplementario()
    {
        return $this->vrSuplementario;
    }

    /**
     * @param mixed $vrSuplementario
     */
    public function setVrSuplementario($vrSuplementario): void
    {
        $this->vrSuplementario = $vrSuplementario;
    }

    /**
     * @return mixed
     */
    public function getVrAdicionalPrestacional()
    {
        return $this->vrAdicionalPrestacional;
    }

    /**
     * @param mixed $vrAdicionalPrestacional
     */
    public function setVrAdicionalPrestacional($vrAdicionalPrestacional): void
    {
        $this->vrAdicionalPrestacional = $vrAdicionalPrestacional;
    }

    /**
     * @return mixed
     */
    public function getVrAdicionalNoPrestacional()
    {
        return $this->vrAdicionalNoPrestacional;
    }

    /**
     * @param mixed $vrAdicionalNoPrestacional
     */
    public function setVrAdicionalNoPrestacional($vrAdicionalNoPrestacional): void
    {
        $this->vrAdicionalNoPrestacional = $vrAdicionalNoPrestacional;
    }

    /**
     * @return mixed
     */
    public function getDiasAusentismo()
    {
        return $this->diasAusentismo;
    }

    /**
     * @param mixed $diasAusentismo
     */
    public function setDiasAusentismo($diasAusentismo): void
    {
        $this->diasAusentismo = $diasAusentismo;
    }

    /**
     * @return mixed
     */
    public function getAdicional()
    {
        return $this->adicional;
    }

    /**
     * @param mixed $adicional
     */
    public function setAdicional($adicional): void
    {
        $this->adicional = $adicional;
    }

    /**
     * @return mixed
     */
    public function getPrestacional()
    {
        return $this->prestacional;
    }

    /**
     * @param mixed $prestacional
     */
    public function setPrestacional($prestacional): void
    {
        $this->prestacional = $prestacional;
    }

    /**
     * @return mixed
     */
    public function getCotizacion()
    {
        return $this->cotizacion;
    }

    /**
     * @param mixed $cotizacion
     */
    public function setCotizacion($cotizacion): void
    {
        $this->cotizacion = $cotizacion;
    }

    /**
     * @return mixed
     */
    public function getSalud()
    {
        return $this->salud;
    }

    /**
     * @param mixed $salud
     */
    public function setSalud($salud): void
    {
        $this->salud = $salud;
    }

    /**
     * @return mixed
     */
    public function getPension()
    {
        return $this->pension;
    }

    /**
     * @param mixed $pension
     */
    public function setPension($pension): void
    {
        $this->pension = $pension;
    }

    /**
     * @return mixed
     */
    public function getPagoRel()
    {
        return $this->pagoRel;
    }

    /**
     * @param mixed $pagoRel
     */
    public function setPagoRel($pagoRel): void
    {
        $this->pagoRel = $pagoRel;
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
