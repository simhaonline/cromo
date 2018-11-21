<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_detalle")
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuPagoDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuPagoDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoPagoDetallePk",
        "todos"     => true,
    ];
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
     * @ORM\Column(name="codigo_credito_fk", type="integer", nullable=true)
     */
    private $codigoCreditoFk;

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
     * @ORM\Column(name="horas", type="float")
     */
    private $horas = 0;

    /**
     * @ORM\Column(name="vr_hora", type="float")
     */
    private $vrHora = 0;

    /**
     * @ORM\Column(name="porcentaje", type="float")
     */
    private $porcentaje = 0;

    /**
     * @ORM\Column(name="dias", type="integer")
     */
    private $dias = 0;

    /**
     * @ORM\Column(name="detalle", type="string", length=250, nullable=true)
     */
    private $detalle;

    /**
     * @ORM\Column(name="vr_deduccion",options={"default":0}, type="float", nullable=true)
     */
    private $vrDeduccion = 0;

    /**
     * @ORM\Column(name="vr_devengado",options={"default":0}, type="float", nullable=true)
     */
    private $vrDevengado = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_cotizacion", type="float")
     */
    private $vrIngresoBaseCotizacion = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion", type="float")
     */
    private $vrIngresoBasePrestacion = 0;

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
     * @ORM\ManyToOne(targetEntity="RhuCredito", inversedBy="pagosDetallesCreditoRel")
     * @ORM\JoinColumn(name="codigo_credito_fk", referencedColumnName="codigo_credito_pk")
     */
    protected $creditoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuCreditoPago", mappedBy="pagoDetalleRel")
     */
    protected $creditosPagosPagoDetalleRel;

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
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * @param mixed $horas
     */
    public function setHoras($horas): void
    {
        $this->horas = $horas;
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
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * @param mixed $porcentaje
     */
    public function setPorcentaje($porcentaje): void
    {
        $this->porcentaje = $porcentaje;
    }

    /**
     * @return mixed
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * @param mixed $dias
     */
    public function setDias($dias): void
    {
        $this->dias = $dias;
    }

    /**
     * @return mixed
     */
    public function getVrDeduccion()
    {
        return $this->vrDeduccion;
    }

    /**
     * @param mixed $vrDeduccion
     */
    public function setVrDeduccion($vrDeduccion): void
    {
        $this->vrDeduccion = $vrDeduccion;
    }

    /**
     * @return mixed
     */
    public function getVrDevengado()
    {
        return $this->vrDevengado;
    }

    /**
     * @param mixed $vrDevengado
     */
    public function setVrDevengado($vrDevengado): void
    {
        $this->vrDevengado = $vrDevengado;
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

    /**
     * @return mixed
     */
    public function getCodigoCreditoFk()
    {
        return $this->codigoCreditoFk;
    }

    /**
     * @param mixed $codigoCreditoFk
     */
    public function setCodigoCreditoFk( $codigoCreditoFk ): void
    {
        $this->codigoCreditoFk = $codigoCreditoFk;
    }

    /**
     * @return mixed
     */
    public function getCreditoRel()
    {
        return $this->creditoRel;
    }

    /**
     * @param mixed $creditoRel
     */
    public function setCreditoRel( $creditoRel ): void
    {
        $this->creditoRel = $creditoRel;
    }

    /**
     * @return mixed
     */
    public function getCreditosPagosPagoDetalleRel()
    {
        return $this->creditosPagosPagoDetalleRel;
    }

    /**
     * @param mixed $creditosPagosPagoDetalleRel
     */
    public function setCreditosPagosPagoDetalleRel( $creditosPagosPagoDetalleRel ): void
    {
        $this->creditosPagosPagoDetalleRel = $creditosPagosPagoDetalleRel;
    }



}
