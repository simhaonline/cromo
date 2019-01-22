<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarReciboDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class CarReciboDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoReciboDetallePk",
        "todos"     => true,
    ];
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
     * @ORM\Column(name="codigo_cuenta_cobrar_tipo_fk", type="string", length=10, nullable=true)
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
     * @ORM\Column(name="vr_descuento", type="float", nullable=true, options={"default":0})
     */
    private $vrDescuento = 0;

    /**
     * @ORM\Column(name="vr_ajuste_peso", type="float", nullable=true, options={"default":0})
     */
    private $vrAjustePeso = 0;

    /**
     * @ORM\Column(name="vr_retencion_ica", type="float", nullable=true, options={"default":0})
     */
    private $vrRetencionIca = 0;

    /**
     * @ORM\Column(name="vr_retencion_iva", type="float", nullable=true, options={"default":0})
     */
    private $vrRetencionIva = 0;

    /**
     * @ORM\Column(name="vr_retencion_fuente", type="float", nullable=true, options={"default":0})
     */
    private $vrRetencionFuente = 0;

    /**
     * @ORM\Column(name="vr_otro_descuento", type="float", options={"default":0})
     */
    private $vrOtroDescuento = 0;

    /**
     * @ORM\Column(name="vr_otro_ingreso", type="float", options={"default":0})
     */
    private $vrOtroIngreso = 0;

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
     * @ORM\Column(name="codigo_descuento_concepto_fk", type="string", length=10, nullable=true)
     */
    private $codigoDescuentoConceptoFk;

    /**
     * @ORM\Column(name="codigo_ingreso_concepto_fk", type="string", length=10, nullable=true)
     */
    private $codigoIngresoConceptoFk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cartera\CarRecibo", inversedBy="recibosDetallesRecibosRel")
     * @ORM\JoinColumn(name="codigo_recibo_fk", referencedColumnName="codigo_recibo_pk")
     */
    protected $reciboRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cartera\CarCuentaCobrar", inversedBy="recibosDetallesCuentaCobrarRel")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_fk", referencedColumnName="codigo_cuenta_cobrar_pk")
     */
    protected $cuentaCobrarRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cartera\CarCuentaCobrarTipo", inversedBy="recibosDetallesCuentaCobrarTipoRel")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_tipo_fk", referencedColumnName="codigo_cuenta_cobrar_tipo_pk")
     */
    protected $cuentaCobrarTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="CarCuentaCobrar", inversedBy="recibosDetallesCuentaCobrarAplicacionRel")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_aplicacion_fk", referencedColumnName="codigo_cuenta_cobrar_pk")
     */
    protected $cuentaCobrarAplicacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cartera\CarDescuentoConcepto", inversedBy="recibosDetallesDescuentoConceptoRel")
     * @ORM\JoinColumn(name="codigo_descuento_concepto_fk", referencedColumnName="codigo_descuento_concepto_pk")
     */
    protected $descuentoConceptoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cartera\CarIngresoConcepto", inversedBy="recibosDetallesIngresoConceptoRel")
     * @ORM\JoinColumn(name="codigo_ingreso_concepto_fk", referencedColumnName="codigo_ingreso_concepto_pk")
     */
    protected $ingresoConceptoRel;

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
    public function setCodigoReciboDetallePk( $codigoReciboDetallePk ): void
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
    public function setCodigoReciboFk( $codigoReciboFk ): void
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
    public function setCodigoCuentaCobrarFk( $codigoCuentaCobrarFk ): void
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
    public function setCodigoCuentaCobrarTipoFk( $codigoCuentaCobrarTipoFk ): void
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
    public function setCodigoCuentaCobrarAplicacionFk( $codigoCuentaCobrarAplicacionFk ): void
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
    public function setNumeroFactura( $numeroFactura ): void
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
    public function setNumeroDocumentoAplicacion( $numeroDocumentoAplicacion ): void
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
    public function setVrDescuento( $vrDescuento ): void
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
    public function setVrAjustePeso( $vrAjustePeso ): void
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
    public function setVrRetencionIca( $vrRetencionIca ): void
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
    public function setVrRetencionIva( $vrRetencionIva ): void
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
    public function setVrRetencionFuente( $vrRetencionFuente ): void
    {
        $this->vrRetencionFuente = $vrRetencionFuente;
    }

    /**
     * @return mixed
     */
    public function getVrOtroDescuento()
    {
        return $this->vrOtroDescuento;
    }

    /**
     * @param mixed $vrOtroDescuento
     */
    public function setVrOtroDescuento( $vrOtroDescuento ): void
    {
        $this->vrOtroDescuento = $vrOtroDescuento;
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
    public function getVrPagoAfectar()
    {
        return $this->vrPagoAfectar;
    }

    /**
     * @param mixed $vrPagoAfectar
     */
    public function setVrPagoAfectar( $vrPagoAfectar ): void
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
    public function setUsuario( $usuario ): void
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
    public function setOperacion( $operacion ): void
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
    public function setReciboRel( $reciboRel ): void
    {
        $this->reciboRel = $reciboRel;
    }

    /**
     * @return mixed
     */
    public function getCuentaCobrarRel()
    {
        return $this->cuentaCobrarRel;
    }

    /**
     * @param mixed $cuentaCobrarRel
     */
    public function setCuentaCobrarRel( $cuentaCobrarRel ): void
    {
        $this->cuentaCobrarRel = $cuentaCobrarRel;
    }

    /**
     * @return mixed
     */
    public function getCuentaCobrarTipoRel()
    {
        return $this->cuentaCobrarTipoRel;
    }

    /**
     * @param mixed $cuentaCobrarTipoRel
     */
    public function setCuentaCobrarTipoRel( $cuentaCobrarTipoRel ): void
    {
        $this->cuentaCobrarTipoRel = $cuentaCobrarTipoRel;
    }

    /**
     * @return mixed
     */
    public function getCuentaCobrarAplicacionRel()
    {
        return $this->cuentaCobrarAplicacionRel;
    }

    /**
     * @param mixed $cuentaCobrarAplicacionRel
     */
    public function setCuentaCobrarAplicacionRel( $cuentaCobrarAplicacionRel ): void
    {
        $this->cuentaCobrarAplicacionRel = $cuentaCobrarAplicacionRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoDescuentoConceptoFk()
    {
        return $this->codigoDescuentoConceptoFk;
    }

    /**
     * @param mixed $codigoDescuentoConceptoFk
     */
    public function setCodigoDescuentoConceptoFk( $codigoDescuentoConceptoFk ): void
    {
        $this->codigoDescuentoConceptoFk = $codigoDescuentoConceptoFk;
    }

    /**
     * @return mixed
     */
    public function getDescuentoConceptoRel()
    {
        return $this->descuentoConceptoRel;
    }

    /**
     * @param mixed $descuentoConceptoRel
     */
    public function setDescuentoConceptoRel( $descuentoConceptoRel ): void
    {
        $this->descuentoConceptoRel = $descuentoConceptoRel;
    }

    /**
     * @return mixed
     */
    public function getVrOtroIngreso()
    {
        return $this->vrOtroIngreso;
    }

    /**
     * @param mixed $vrOtroIngreso
     */
    public function setVrOtroIngreso( $vrOtroIngreso ): void
    {
        $this->vrOtroIngreso = $vrOtroIngreso;
    }

    /**
     * @return mixed
     */
    public function getCodigoIngresoConceptoFk()
    {
        return $this->codigoIngresoConceptoFk;
    }

    /**
     * @param mixed $codigoIngresoConceptoFk
     */
    public function setCodigoIngresoConceptoFk( $codigoIngresoConceptoFk ): void
    {
        $this->codigoIngresoConceptoFk = $codigoIngresoConceptoFk;
    }

    /**
     * @return mixed
     */
    public function getIngresoConceptoRel()
    {
        return $this->ingresoConceptoRel;
    }

    /**
     * @param mixed $ingresoConceptoRel
     */
    public function setIngresoConceptoRel( $ingresoConceptoRel ): void
    {
        $this->ingresoConceptoRel = $ingresoConceptoRel;
    }



}
