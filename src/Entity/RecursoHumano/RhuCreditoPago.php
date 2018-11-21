<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCreditoPagoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuCreditoPago
{
    public $infoLog = [
        "primaryKey" => "codigoCreditoPagoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_credito_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCreditoPagoPk;

    /**
     * @ORM\Column(name="codigo_credito_fk", type="integer")
     */
    private $codigoCreditoFk;

    /**
     * @ORM\Column(name="codigo_credito_pago_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoCreditoPagoTipoFk;

    /**
     * @ORM\Column(name="codigo_pago_detalle_fk", type="integer", nullable=true)
     */
    private $codigoPagoDetalleFk;

    /**
     * @ORM\Column(name="vr_pago", type="float")
     */
    private $vrPago = 0;

    /**
     * @ORM\Column(name="vr_saldo", type="float")
     */
    private $vrSaldo = 0;

    /**
     * @ORM\Column(name="numero_cuota_actual", type="integer")
     */
    private $numeroCuotaActual = 0;

    /**
     * @ORM\Column(name="fecha_pago", type="datetime")
     */
    private $fechaPago;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCredito", inversedBy="creditosPagosCreditoRel")
     * @ORM\JoinColumn(name="codigo_credito_fk", referencedColumnName="codigo_credito_pk")
     */
    protected $creditoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuCreditoPagoTipo", inversedBy="creditosPagosCreditoPagoTipoRel")
     * @ORM\JoinColumn(name="codigo_credito_pago_tipo_fk", referencedColumnName="codigo_credito_pago_tipo_pk")
     */
    protected $creditoPagoTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuPagoDetalle", inversedBy="creditosPagosPagoDetalleRel")
     * @ORM\JoinColumn(name="codigo_pago_detalle_fk", referencedColumnName="codigo_pago_detalle_pk")
     */
    protected $pagoDetalleRel;

    /**
     * @return mixed
     */
    public function getCodigoCreditoPagoPk()
    {
        return $this->codigoCreditoPagoPk;
    }

    /**
     * @param mixed $codigoCreditoPagoPk
     */
    public function setCodigoCreditoPagoPk( $codigoCreditoPagoPk ): void
    {
        $this->codigoCreditoPagoPk = $codigoCreditoPagoPk;
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
    public function getCodigoCreditoPagoTipoFk()
    {
        return $this->codigoCreditoPagoTipoFk;
    }

    /**
     * @param mixed $codigoCreditoPagoTipoFk
     */
    public function setCodigoCreditoPagoTipoFk( $codigoCreditoPagoTipoFk ): void
    {
        $this->codigoCreditoPagoTipoFk = $codigoCreditoPagoTipoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPagoDetalleFk()
    {
        return $this->codigoPagoDetalleFk;
    }

    /**
     * @param mixed $codigoPagoDetalleFk
     */
    public function setCodigoPagoDetalleFk( $codigoPagoDetalleFk ): void
    {
        $this->codigoPagoDetalleFk = $codigoPagoDetalleFk;
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
    public function setVrPago( $vrPago ): void
    {
        $this->vrPago = $vrPago;
    }

    /**
     * @return mixed
     */
    public function getVrSaldo()
    {
        return $this->vrSaldo;
    }

    /**
     * @param mixed $vrSaldo
     */
    public function setVrSaldo( $vrSaldo ): void
    {
        $this->vrSaldo = $vrSaldo;
    }

    /**
     * @return mixed
     */
    public function getNumeroCuotaActual()
    {
        return $this->numeroCuotaActual;
    }

    /**
     * @param mixed $numeroCuotaActual
     */
    public function setNumeroCuotaActual( $numeroCuotaActual ): void
    {
        $this->numeroCuotaActual = $numeroCuotaActual;
    }

    /**
     * @return mixed
     */
    public function getFechaPago()
    {
        return $this->fechaPago;
    }

    /**
     * @param mixed $fechaPago
     */
    public function setFechaPago( $fechaPago ): void
    {
        $this->fechaPago = $fechaPago;
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
    public function getCreditoPagoTipoRel()
    {
        return $this->creditoPagoTipoRel;
    }

    /**
     * @param mixed $creditoPagoTipoRel
     */
    public function setCreditoPagoTipoRel( $creditoPagoTipoRel ): void
    {
        $this->creditoPagoTipoRel = $creditoPagoTipoRel;
    }

    /**
     * @return mixed
     */
    public function getPagoDetalleRel()
    {
        return $this->pagoDetalleRel;
    }

    /**
     * @param mixed $pagoDetalleRel
     */
    public function setPagoDetalleRel( $pagoDetalleRel ): void
    {
        $this->pagoDetalleRel = $pagoDetalleRel;
    }



}
