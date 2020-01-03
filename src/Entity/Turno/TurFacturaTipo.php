<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurFacturaTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurFacturaTipo
{
    public $infoLog = [
        "primaryKey" => "codigoFacturaTipoPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_tipo_pk", type="string", length=10)
     */
    private $codigoFacturaTipoPk;

    /**
     * @ORM\Column(name="codigo_factura_clase_fk", type="string", length=10)
     */
    private $codigoFacturaClaseFk;

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_tipo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaCobrarTipoFk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\Column(name="operacion", type="integer")
     */
    private $operacion = 0;

    /**
     * @ORM\Column(name="consecutivo", type="integer")
     */
    private $consecutivo = 0;

    /**
     * @ORM\Column(name="prefijo", type="string",length=10, nullable=true)
     */
    private $prefijo;

    /**
     * @ORM\Column(name="documento_cartera", type="integer", nullable=true)
     */
    private $documentoCartera;

    /**
     * @ORM\Column(name="abreviatura", type="string", length=10)
     */
    private $abreviatura;

    /**
     * @ORM\Column(name="codigo_centro_costo_contabilidad", type="integer", nullable=true)
     */
    private $codigoCentroCostoContabilidad;

    /**
     * @ORM\Column(name="codigo_comprobante_fk", type="integer", nullable=true)
     */
    private $codigoComprobanteFk;

    /**
     * @ORM\Column(name="tipo_cuenta_cartera", type="bigint")
     */
    private $tipoCuentaCartera = 1;

    /**
     * @ORM\Column(name="tipo_cuenta_retencion_fuente", type="bigint")
     */
    private $tipoCuentaRetencionFuente = 1;

    /**
     * @ORM\Column(name="tipo_cuenta_retencion_iva", type="bigint")
     */
    private $tipoCuentaRetencionIva = 1;

    /**
     * @ORM\Column(name="tipo_cuenta_retencion_renta", type="bigint")
     */
    private $tipoCuentaRetencionRenta = 1;

    /**
     * @ORM\Column(name="tipo_cuenta_autoretencion_renta", type="bigint")
     */
    private $tipoCuentaAutoretencionRenta = 1;

    /**
     * @ORM\Column(name="tipo_cuenta_iva", type="bigint")
     */
    private $tipoCuentaIva = 1;

    /**
     * @ORM\Column(name="tipo_cuenta_ingreso", type="bigint")
     */
    private $tipoCuentaIngreso = 1;

    /**
     * @ORM\Column(name="genera_cartera", type="boolean", options={"default":false})
     */
    private $generaCartera = false;

    /**
     * @ORM\Column(name="codigo_interfaz_dian", type="string", length=10, nullable=true)
     */
    private $codigoInterfazDian;

    /**
     * @ORM\Column(name="codigo_documento_cartera", type="integer", nullable=true)
     */
    private $codigoDocumentoCartera;

    /**
     * @ORM\Column(name="informacion_legal_factura", type="text", nullable=true)
     */
    private $informacionLegalFactura;

    /**
     * @ORM\Column(name="informacion_pago_factura", type="text", nullable=true)
     */
    private $informacionPagoFactura;

    /**
     * @ORM\Column(name="informacion_contacto_factura", type="text", nullable=true)
     */
    private $informacionContactoFactura;

    /**
     * @ORM\Column(name="informacion_resolucion_dian_factura", type="text", nullable=true)
     */
    private $informacionResolucionDianFactura;

    /**
     * @ORM\Column(name="informacion_resolucion_supervigilancia_factura", type="text", nullable=true)
     */
    private $informacionResolucionSupervigilanciaFactura;

    /**
     * @ORM\Column(name="informacion_sucursal_factura", type="text", nullable=true)
     */
    private $informacionSucursalFactura;

    /**
     * @ORM\Column(name="codigo_comprobante_factura_pedido", type="integer", nullable=true)
     */
    private $codigoComprobanteFacturaPedido;

    /**
     * @ORM\Column(name="tipo_peracion_seven", type="integer", nullable=true)
     */
    private $tipoOperacionSeven = 0;

    /**
     * @ORM\Column(name="punto_de_venta_dian", type="integer", nullable=true)
     */
    private $puntoDeVentaDian;

    /**
     * @ORM\Column(name="fecha_desde_vigencia", type="date", nullable=true)
     */
    private $fechaDesdeVigencia;

    /**
     * @ORM\Column(name="fecha_hasta_vigencia", type="date", nullable=true)
     */
    private $fechaHastaVigencia;

    /**
     * @ORM\Column(name="numeracion_desde", type="integer", nullable=true)
     */
    private $numeracionDesde;

    /**
     * @ORM\Column(name="numeracion_hasta", type="integer", nullable=true)
     */
    private $numeracionHasta;

    /**
     * @ORM\Column(name="nota_credito", type="boolean", nullable=true, options={"default":false})
     */
    private $notaCredito = 0;

    /**
     * @ORM\Column(name="numero_resolucion_dian_factura", type="bigint", nullable=true)
     */
    private $numeroResolucionDianFactura;

    /**
     * @ORM\Column(name="codigo_cuenta_proveedor_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaProveedorFk;

    /**
     * @ORM\Column(name="codigo_cuenta_cliente_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaClienteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_ingreso_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaIngresoFk;

    /**
     * @ORM\Column(name="codigo_cuenta_aiu_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaAiuFk;

    /**
     * @ORM\OneToMany(targetEntity="TurFactura", mappedBy="facturaTipoRel")
     */
    protected $facturasFacturaTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoFacturaTipoPk()
    {
        return $this->codigoFacturaTipoPk;
    }

    /**
     * @param mixed $codigoFacturaTipoPk
     */
    public function setCodigoFacturaTipoPk($codigoFacturaTipoPk): void
    {
        $this->codigoFacturaTipoPk = $codigoFacturaTipoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoFacturaClaseFk()
    {
        return $this->codigoFacturaClaseFk;
    }

    /**
     * @param mixed $codigoFacturaClaseFk
     */
    public function setCodigoFacturaClaseFk($codigoFacturaClaseFk): void
    {
        $this->codigoFacturaClaseFk = $codigoFacturaClaseFk;
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
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
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
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }

    /**
     * @param mixed $consecutivo
     */
    public function setConsecutivo($consecutivo): void
    {
        $this->consecutivo = $consecutivo;
    }

    /**
     * @return mixed
     */
    public function getPrefijo()
    {
        return $this->prefijo;
    }

    /**
     * @param mixed $prefijo
     */
    public function setPrefijo($prefijo): void
    {
        $this->prefijo = $prefijo;
    }

    /**
     * @return mixed
     */
    public function getDocumentoCartera()
    {
        return $this->documentoCartera;
    }

    /**
     * @param mixed $documentoCartera
     */
    public function setDocumentoCartera($documentoCartera): void
    {
        $this->documentoCartera = $documentoCartera;
    }

    /**
     * @return mixed
     */
    public function getAbreviatura()
    {
        return $this->abreviatura;
    }

    /**
     * @param mixed $abreviatura
     */
    public function setAbreviatura($abreviatura): void
    {
        $this->abreviatura = $abreviatura;
    }

    /**
     * @return mixed
     */
    public function getCodigoCentroCostoContabilidad()
    {
        return $this->codigoCentroCostoContabilidad;
    }

    /**
     * @param mixed $codigoCentroCostoContabilidad
     */
    public function setCodigoCentroCostoContabilidad($codigoCentroCostoContabilidad): void
    {
        $this->codigoCentroCostoContabilidad = $codigoCentroCostoContabilidad;
    }

    /**
     * @return mixed
     */
    public function getCodigoComprobanteFk()
    {
        return $this->codigoComprobanteFk;
    }

    /**
     * @param mixed $codigoComprobanteFk
     */
    public function setCodigoComprobanteFk($codigoComprobanteFk): void
    {
        $this->codigoComprobanteFk = $codigoComprobanteFk;
    }

    /**
     * @return mixed
     */
    public function getTipoCuentaCartera()
    {
        return $this->tipoCuentaCartera;
    }

    /**
     * @param mixed $tipoCuentaCartera
     */
    public function setTipoCuentaCartera($tipoCuentaCartera): void
    {
        $this->tipoCuentaCartera = $tipoCuentaCartera;
    }

    /**
     * @return mixed
     */
    public function getTipoCuentaRetencionFuente()
    {
        return $this->tipoCuentaRetencionFuente;
    }

    /**
     * @param mixed $tipoCuentaRetencionFuente
     */
    public function setTipoCuentaRetencionFuente($tipoCuentaRetencionFuente): void
    {
        $this->tipoCuentaRetencionFuente = $tipoCuentaRetencionFuente;
    }

    /**
     * @return mixed
     */
    public function getTipoCuentaRetencionIva()
    {
        return $this->tipoCuentaRetencionIva;
    }

    /**
     * @param mixed $tipoCuentaRetencionIva
     */
    public function setTipoCuentaRetencionIva($tipoCuentaRetencionIva): void
    {
        $this->tipoCuentaRetencionIva = $tipoCuentaRetencionIva;
    }

    /**
     * @return mixed
     */
    public function getTipoCuentaRetencionRenta()
    {
        return $this->tipoCuentaRetencionRenta;
    }

    /**
     * @param mixed $tipoCuentaRetencionRenta
     */
    public function setTipoCuentaRetencionRenta($tipoCuentaRetencionRenta): void
    {
        $this->tipoCuentaRetencionRenta = $tipoCuentaRetencionRenta;
    }

    /**
     * @return mixed
     */
    public function getTipoCuentaAutoretencionRenta()
    {
        return $this->tipoCuentaAutoretencionRenta;
    }

    /**
     * @param mixed $tipoCuentaAutoretencionRenta
     */
    public function setTipoCuentaAutoretencionRenta($tipoCuentaAutoretencionRenta): void
    {
        $this->tipoCuentaAutoretencionRenta = $tipoCuentaAutoretencionRenta;
    }

    /**
     * @return mixed
     */
    public function getTipoCuentaIva()
    {
        return $this->tipoCuentaIva;
    }

    /**
     * @param mixed $tipoCuentaIva
     */
    public function setTipoCuentaIva($tipoCuentaIva): void
    {
        $this->tipoCuentaIva = $tipoCuentaIva;
    }

    /**
     * @return mixed
     */
    public function getTipoCuentaIngreso()
    {
        return $this->tipoCuentaIngreso;
    }

    /**
     * @param mixed $tipoCuentaIngreso
     */
    public function setTipoCuentaIngreso($tipoCuentaIngreso): void
    {
        $this->tipoCuentaIngreso = $tipoCuentaIngreso;
    }

    /**
     * @return mixed
     */
    public function getGeneraCartera()
    {
        return $this->generaCartera;
    }

    /**
     * @param mixed $generaCartera
     */
    public function setGeneraCartera($generaCartera): void
    {
        $this->generaCartera = $generaCartera;
    }

    /**
     * @return mixed
     */
    public function getCodigoInterfazDian()
    {
        return $this->codigoInterfazDian;
    }

    /**
     * @param mixed $codigoInterfazDian
     */
    public function setCodigoInterfazDian($codigoInterfazDian): void
    {
        $this->codigoInterfazDian = $codigoInterfazDian;
    }

    /**
     * @return mixed
     */
    public function getCodigoDocumentoCartera()
    {
        return $this->codigoDocumentoCartera;
    }

    /**
     * @param mixed $codigoDocumentoCartera
     */
    public function setCodigoDocumentoCartera($codigoDocumentoCartera): void
    {
        $this->codigoDocumentoCartera = $codigoDocumentoCartera;
    }

    /**
     * @return mixed
     */
    public function getInformacionLegalFactura()
    {
        return $this->informacionLegalFactura;
    }

    /**
     * @param mixed $informacionLegalFactura
     */
    public function setInformacionLegalFactura($informacionLegalFactura): void
    {
        $this->informacionLegalFactura = $informacionLegalFactura;
    }

    /**
     * @return mixed
     */
    public function getInformacionPagoFactura()
    {
        return $this->informacionPagoFactura;
    }

    /**
     * @param mixed $informacionPagoFactura
     */
    public function setInformacionPagoFactura($informacionPagoFactura): void
    {
        $this->informacionPagoFactura = $informacionPagoFactura;
    }

    /**
     * @return mixed
     */
    public function getInformacionContactoFactura()
    {
        return $this->informacionContactoFactura;
    }

    /**
     * @param mixed $informacionContactoFactura
     */
    public function setInformacionContactoFactura($informacionContactoFactura): void
    {
        $this->informacionContactoFactura = $informacionContactoFactura;
    }

    /**
     * @return mixed
     */
    public function getInformacionResolucionDianFactura()
    {
        return $this->informacionResolucionDianFactura;
    }

    /**
     * @param mixed $informacionResolucionDianFactura
     */
    public function setInformacionResolucionDianFactura($informacionResolucionDianFactura): void
    {
        $this->informacionResolucionDianFactura = $informacionResolucionDianFactura;
    }

    /**
     * @return mixed
     */
    public function getInformacionResolucionSupervigilanciaFactura()
    {
        return $this->informacionResolucionSupervigilanciaFactura;
    }

    /**
     * @param mixed $informacionResolucionSupervigilanciaFactura
     */
    public function setInformacionResolucionSupervigilanciaFactura($informacionResolucionSupervigilanciaFactura): void
    {
        $this->informacionResolucionSupervigilanciaFactura = $informacionResolucionSupervigilanciaFactura;
    }

    /**
     * @return mixed
     */
    public function getInformacionSucursalFactura()
    {
        return $this->informacionSucursalFactura;
    }

    /**
     * @param mixed $informacionSucursalFactura
     */
    public function setInformacionSucursalFactura($informacionSucursalFactura): void
    {
        $this->informacionSucursalFactura = $informacionSucursalFactura;
    }

    /**
     * @return mixed
     */
    public function getCodigoComprobanteFacturaPedido()
    {
        return $this->codigoComprobanteFacturaPedido;
    }

    /**
     * @param mixed $codigoComprobanteFacturaPedido
     */
    public function setCodigoComprobanteFacturaPedido($codigoComprobanteFacturaPedido): void
    {
        $this->codigoComprobanteFacturaPedido = $codigoComprobanteFacturaPedido;
    }

    /**
     * @return mixed
     */
    public function getTipoOperacionSeven()
    {
        return $this->tipoOperacionSeven;
    }

    /**
     * @param mixed $tipoOperacionSeven
     */
    public function setTipoOperacionSeven($tipoOperacionSeven): void
    {
        $this->tipoOperacionSeven = $tipoOperacionSeven;
    }

    /**
     * @return mixed
     */
    public function getPuntoDeVentaDian()
    {
        return $this->puntoDeVentaDian;
    }

    /**
     * @param mixed $puntoDeVentaDian
     */
    public function setPuntoDeVentaDian($puntoDeVentaDian): void
    {
        $this->puntoDeVentaDian = $puntoDeVentaDian;
    }

    /**
     * @return mixed
     */
    public function getFechaDesdeVigencia()
    {
        return $this->fechaDesdeVigencia;
    }

    /**
     * @param mixed $fechaDesdeVigencia
     */
    public function setFechaDesdeVigencia($fechaDesdeVigencia): void
    {
        $this->fechaDesdeVigencia = $fechaDesdeVigencia;
    }

    /**
     * @return mixed
     */
    public function getFechaHastaVigencia()
    {
        return $this->fechaHastaVigencia;
    }

    /**
     * @param mixed $fechaHastaVigencia
     */
    public function setFechaHastaVigencia($fechaHastaVigencia): void
    {
        $this->fechaHastaVigencia = $fechaHastaVigencia;
    }

    /**
     * @return mixed
     */
    public function getNumeracionDesde()
    {
        return $this->numeracionDesde;
    }

    /**
     * @param mixed $numeracionDesde
     */
    public function setNumeracionDesde($numeracionDesde): void
    {
        $this->numeracionDesde = $numeracionDesde;
    }

    /**
     * @return mixed
     */
    public function getNumeracionHasta()
    {
        return $this->numeracionHasta;
    }

    /**
     * @param mixed $numeracionHasta
     */
    public function setNumeracionHasta($numeracionHasta): void
    {
        $this->numeracionHasta = $numeracionHasta;
    }

    /**
     * @return mixed
     */
    public function getNotaCredito()
    {
        return $this->notaCredito;
    }

    /**
     * @param mixed $notaCredito
     */
    public function setNotaCredito($notaCredito): void
    {
        $this->notaCredito = $notaCredito;
    }

    /**
     * @return mixed
     */
    public function getNumeroResolucionDianFactura()
    {
        return $this->numeroResolucionDianFactura;
    }

    /**
     * @param mixed $numeroResolucionDianFactura
     */
    public function setNumeroResolucionDianFactura($numeroResolucionDianFactura): void
    {
        $this->numeroResolucionDianFactura = $numeroResolucionDianFactura;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaProveedorFk()
    {
        return $this->codigoCuentaProveedorFk;
    }

    /**
     * @param mixed $codigoCuentaProveedorFk
     */
    public function setCodigoCuentaProveedorFk($codigoCuentaProveedorFk): void
    {
        $this->codigoCuentaProveedorFk = $codigoCuentaProveedorFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaClienteFk()
    {
        return $this->codigoCuentaClienteFk;
    }

    /**
     * @param mixed $codigoCuentaClienteFk
     */
    public function setCodigoCuentaClienteFk($codigoCuentaClienteFk): void
    {
        $this->codigoCuentaClienteFk = $codigoCuentaClienteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaIngresoFk()
    {
        return $this->codigoCuentaIngresoFk;
    }

    /**
     * @param mixed $codigoCuentaIngresoFk
     */
    public function setCodigoCuentaIngresoFk($codigoCuentaIngresoFk): void
    {
        $this->codigoCuentaIngresoFk = $codigoCuentaIngresoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaAiuFk()
    {
        return $this->codigoCuentaAiuFk;
    }

    /**
     * @param mixed $codigoCuentaAiuFk
     */
    public function setCodigoCuentaAiuFk($codigoCuentaAiuFk): void
    {
        $this->codigoCuentaAiuFk = $codigoCuentaAiuFk;
    }

    /**
     * @return mixed
     */
    public function getFacturasFacturaTipoRel()
    {
        return $this->facturasFacturaTipoRel;
    }

    /**
     * @param mixed $facturasFacturaTipoRel
     */
    public function setFacturasFacturaTipoRel($facturasFacturaTipoRel): void
    {
        $this->facturasFacturaTipoRel = $facturasFacturaTipoRel;
    }



}

