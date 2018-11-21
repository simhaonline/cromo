<?php

namespace App\Entity\Compra;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Compra\ComEgresoDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class ComEgresoDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoEgresoDetallePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $codigoEgresoDetallePk;

    /**
     * @ORM\Column(name="codigo_egreso_fk" , type="integer")
     */
    private $codigoEgresoFk;

    /**
     * @ORM\Column(name="codigo_cuenta_pagar_fk" , type="integer")
     */
    private $codigoCuentaPagarFk;

    /**
     * @ORM\Column(name="numero_factura", type="string", length=30, nullable=true)
     */
    private $numeroCompra;

    /**
     * @ORM\Column(name="numero_documento_aplicacion", type="integer", nullable=true)
     */
    private $numeroDocumentoAplicacion;

    /**
     * @ORM\Column(name="vr_descuento", type="float", nullable=true)
     */
    private $vrDescuento = 0;

    /**
     * @ORM\Column(name="vr_ajuste_peso", type="float", nullable=true)
     */
    private $vrAjustePeso = 0;

    /**
     * @ORM\Column(name="vr_retencion_ica", type="float", nullable=true)
     */
    private $vrRetencionIca = 0;

    /**
     * @ORM\Column(name="vr_retencion_iva", type="float", nullable=true)
     */
    private $vrRetencionIva = 0;

    /**
     * @ORM\Column(name="vr_retencion_fuente", type="float", nullable=true)
     */
    private $vrRetencionFuente = 0;

    /**
     * @ORM\Column(name="vr_pago", type="float", nullable=true)
     */
    private $vrPago = 0;

    /**
     * @ORM\Column(name="vr_pago_afectar", type="float", nullable=true)
     */
    private $vrPagoAfectar = 0;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="operacion", type="integer")
     */
    private $operacion = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compra\ComEgreso" , inversedBy="egresoDetallesEgresoRel")
     * @ORM\JoinColumn(name="codigo_egreso_fk" , referencedColumnName="codigo_egreso_pk")
     */
    private $egresoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compra\ComCuentaPagar" , inversedBy="egresosDetalleCuentasPagarRel")
     * @ORM\JoinColumn(name="codigo_cuenta_pagar_fk" , referencedColumnName="codigo_cuenta_pagar_pk")
     */
    private $cuentaPagarRel;

    /**
     * @return mixed
     */
    public function getCodigoEgresoDetallePk()
    {
        return $this->codigoEgresoDetallePk;
    }

    /**
     * @param mixed $codigoEgresoDetallePk
     */
    public function setCodigoEgresoDetallePk($codigoEgresoDetallePk): void
    {
        $this->codigoEgresoDetallePk = $codigoEgresoDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEgresoFk()
    {
        return $this->codigoEgresoFk;
    }

    /**
     * @param mixed $codigoEgresoFk
     */
    public function setCodigoEgresoFk($codigoEgresoFk): void
    {
        $this->codigoEgresoFk = $codigoEgresoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaPagarFk()
    {
        return $this->codigoCuentaPagarFk;
    }

    /**
     * @param mixed $codigoCuentaPagarFk
     */
    public function setCodigoCuentaPagarFk($codigoCuentaPagarFk): void
    {
        $this->codigoCuentaPagarFk = $codigoCuentaPagarFk;
    }

    /**
     * @return mixed
     */
    public function getNumeroCompra()
    {
        return $this->numeroCompra;
    }

    /**
     * @param mixed $numeroCompra
     */
    public function setNumeroCompra($numeroCompra): void
    {
        $this->numeroCompra = $numeroCompra;
    }

    /**
     * @return mixed
     */
    public function getNumeroDocumentoAplicacion()
    {
        return $this->numeroDocumentoAplicacion;
    }

    /**
     * @param mixed $numeroDocumentoAplicacion
     */
    public function setNumeroDocumentoAplicacion($numeroDocumentoAplicacion): void
    {
        $this->numeroDocumentoAplicacion = $numeroDocumentoAplicacion;
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
    public function getVrAjustePeso()
    {
        return $this->vrAjustePeso;
    }

    /**
     * @param mixed $vrAjustePeso
     */
    public function setVrAjustePeso($vrAjustePeso): void
    {
        $this->vrAjustePeso = $vrAjustePeso;
    }

    /**
     * @return mixed
     */
    public function getVrRetencionIca()
    {
        return $this->vrRetencionIca;
    }

    /**
     * @param mixed $vrRetencionIca
     */
    public function setVrRetencionIca($vrRetencionIca): void
    {
        $this->vrRetencionIca = $vrRetencionIca;
    }

    /**
     * @return mixed
     */
    public function getVrRetencionIva()
    {
        return $this->vrRetencionIva;
    }

    /**
     * @param mixed $vrRetencionIva
     */
    public function setVrRetencionIva($vrRetencionIva): void
    {
        $this->vrRetencionIva = $vrRetencionIva;
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
    public function getVrPagoAfectar()
    {
        return $this->vrPagoAfectar;
    }

    /**
     * @param mixed $vrPagoAfectar
     */
    public function setVrPagoAfectar($vrPagoAfectar): void
    {
        $this->vrPagoAfectar = $vrPagoAfectar;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
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
    public function getEgresoRel()
    {
        return $this->egresoRel;
    }

    /**
     * @param mixed $egresoRel
     */
    public function setEgresoRel($egresoRel): void
    {
        $this->egresoRel = $egresoRel;
    }

    /**
     * @return mixed
     */
    public function getCuentaPagarRel()
    {
        return $this->cuentaPagarRel;
    }

    /**
     * @param mixed $cuentaPagarRel
     */
    public function setCuentaPagarRel($cuentaPagarRel): void
    {
        $this->cuentaPagarRel = $cuentaPagarRel;
    }


}
