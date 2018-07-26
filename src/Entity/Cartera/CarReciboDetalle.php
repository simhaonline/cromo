<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarReciboDetalleRepository")
 */
class CarReciboDetalle
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_recibo_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoReciboDetallePk;

    /**
     * @ORM\Column(name="codigo_recibo_fk", type="integer", nullable=true)
     */
    private $codigoReciboFk;

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_fk", type="integer", nullable=true)
     */
    private $codigoCuentaCobrarFk;

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_tipo_fk", type="integer", nullable=true)
     */
    private $codigoCuentaCobrarTipoFk;

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_aplicacion_fk", type="integer", nullable=true)
     */
    private $codigoCuentaCobrarAplicacionFk;

    /**
     * @ORM\Column(name="numero_factura", type="string", length=30, nullable=true)
     */
    private $numeroFactura;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Cartera\CarRecibo", inversedBy="recibosDetallesRecibosRel")
     * @ORM\JoinColumn(name="codigo_recibo_fk", referencedColumnName="codigo_recibo_pk")
     */
    protected $reciboRel;

    /**
     * @return mixed
     */
    public function getCodigoReciboDetallePk()
    {
        return $this->codigoReciboDetallePk;
    }

    /**
     * @param mixed $codigoReciboDetallePk
     */
    public function setCodigoReciboDetallePk($codigoReciboDetallePk): void
    {
        $this->codigoReciboDetallePk = $codigoReciboDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoReciboFk()
    {
        return $this->codigoReciboFk;
    }

    /**
     * @param mixed $codigoReciboFk
     */
    public function setCodigoReciboFk($codigoReciboFk): void
    {
        $this->codigoReciboFk = $codigoReciboFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaCobrarFk()
    {
        return $this->codigoCuentaCobrarFk;
    }

    /**
     * @param mixed $codigoCuentaCobrarFk
     */
    public function setCodigoCuentaCobrarFk($codigoCuentaCobrarFk): void
    {
        $this->codigoCuentaCobrarFk = $codigoCuentaCobrarFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaCobrarTipoFk()
    {
        return $this->codigoCuentaCobrarTipoFk;
    }

    /**
     * @param mixed $codigoCuentaCobrarTipoFk
     */
    public function setCodigoCuentaCobrarTipoFk($codigoCuentaCobrarTipoFk): void
    {
        $this->codigoCuentaCobrarTipoFk = $codigoCuentaCobrarTipoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaCobrarAplicacionFk()
    {
        return $this->codigoCuentaCobrarAplicacionFk;
    }

    /**
     * @param mixed $codigoCuentaCobrarAplicacionFk
     */
    public function setCodigoCuentaCobrarAplicacionFk($codigoCuentaCobrarAplicacionFk): void
    {
        $this->codigoCuentaCobrarAplicacionFk = $codigoCuentaCobrarAplicacionFk;
    }

    /**
     * @return mixed
     */
    public function getNumeroFactura()
    {
        return $this->numeroFactura;
    }

    /**
     * @param mixed $numeroFactura
     */
    public function setNumeroFactura($numeroFactura): void
    {
        $this->numeroFactura = $numeroFactura;
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
    public function getReciboRel()
    {
        return $this->reciboRel;
    }

    /**
     * @param mixed $reciboRel
     */
    public function setReciboRel($reciboRel): void
    {
        $this->reciboRel = $reciboRel;
    }



}
