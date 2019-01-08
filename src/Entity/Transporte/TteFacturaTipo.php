<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteFacturaTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteFacturaTipo
{
    public $infoLog = [
        "primaryKey" => "codigoFacturaTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoFacturaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo", type="integer", nullable=true)
     */
    private $consecutivo = 0;

    /**
     * @ORM\Column(name="resolucion_facturacion", type="text", nullable=true)
     */
    private $resolucionFacturacion;

    /**
     * @ORM\Column(name="guia_factura", type="boolean", nullable=true, options={"default" : false})
     */
    private $guiaFactura = false;

    /**
     * @ORM\Column(name="prefijo", type="string", length=5, nullable=true)
     */
    private $prefijo;

    /**
     * @ORM\Column(name="codigo_factura_clase_fk", type="string", length=2, nullable=true)
     */
    private $codigoFacturaClaseFk;

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_tipo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaCobrarTipoFk;

    /**
     * @ORM\Column(name="codigo_cuenta_ingreso_tercero_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaIngresoTerceroFk;

    /**
     * @ORM\Column(name="codigo_cuenta_ingreso_flete_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaIngresoFleteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_ingreso_manejo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaIngresoManejoFk;

    /**
     * @ORM\Column(name="naturaleza_cuenta_ingreso_tercero", type="string", length=1, nullable=true)
     */
    private $naturalezaCuentaIngresoTercero = 0;

    /**
     * @ORM\Column(name="naturaleza_cuenta_ingreso", type="string", length=1, nullable=true)
     */
    private $naturalezaCuentaIngreso = 0;

    /**
     * @ORM\Column(name="codigo_cuenta_cliente_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaClienteFk;

    /**
     * @ORM\Column(name="naturaleza_cuenta_cliente", type="string", length=1, nullable=true)
     */
    private $naturalezaCuentaCliente = 0;

    /**
     * @ORM\Column(name="codigo_comprobante_fk", type="string", length=20, nullable=true)
     */
    private $codigoComprobanteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_ingreso_flete_intermediacion_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaIngresoFleteIntermediacionFk;

    /**
     * @ORM\Column(name="naturaleza_cuenta_ingreso_flete_intermediacion", type="string", length=1, nullable=true)
     */
    private $naturalezaCuentaIngresoFleteIntermediacion = 0;

    /**
     * @ORM\Column(name="operacion_comercial", type="smallint", nullable=true, options={"default" : 0})
     */
    private $operacionComercial = 0;

    /**
     * @ORM\OneToMany(targetEntity="TteFactura", mappedBy="facturaTipoRel")
     */
    protected $facturasFacturaTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuiaTipo", mappedBy="facturaTipoRel")
     */
    protected $guiasTiposFacturaTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteIntermediacionDetalle", mappedBy="facturaTipoRel")
     */
    protected $intermediacionesDetallesFacturaTipoRel;

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
    public function setCodigoFacturaTipoPk( $codigoFacturaTipoPk ): void
    {
        $this->codigoFacturaTipoPk = $codigoFacturaTipoPk;
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
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }

    /**
     * @param mixed $consecutivo
     */
    public function setConsecutivo( $consecutivo ): void
    {
        $this->consecutivo = $consecutivo;
    }

    /**
     * @return mixed
     */
    public function getResolucionFacturacion()
    {
        return $this->resolucionFacturacion;
    }

    /**
     * @param mixed $resolucionFacturacion
     */
    public function setResolucionFacturacion( $resolucionFacturacion ): void
    {
        $this->resolucionFacturacion = $resolucionFacturacion;
    }

    /**
     * @return mixed
     */
    public function getGuiaFactura()
    {
        return $this->guiaFactura;
    }

    /**
     * @param mixed $guiaFactura
     */
    public function setGuiaFactura( $guiaFactura ): void
    {
        $this->guiaFactura = $guiaFactura;
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
    public function setPrefijo( $prefijo ): void
    {
        $this->prefijo = $prefijo;
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
    public function setCodigoFacturaClaseFk( $codigoFacturaClaseFk ): void
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
    public function setCodigoCuentaCobrarTipoFk( $codigoCuentaCobrarTipoFk ): void
    {
        $this->codigoCuentaCobrarTipoFk = $codigoCuentaCobrarTipoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaIngresoTerceroFk()
    {
        return $this->codigoCuentaIngresoTerceroFk;
    }

    /**
     * @param mixed $codigoCuentaIngresoTerceroFk
     */
    public function setCodigoCuentaIngresoTerceroFk( $codigoCuentaIngresoTerceroFk ): void
    {
        $this->codigoCuentaIngresoTerceroFk = $codigoCuentaIngresoTerceroFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaIngresoFleteFk()
    {
        return $this->codigoCuentaIngresoFleteFk;
    }

    /**
     * @param mixed $codigoCuentaIngresoFleteFk
     */
    public function setCodigoCuentaIngresoFleteFk( $codigoCuentaIngresoFleteFk ): void
    {
        $this->codigoCuentaIngresoFleteFk = $codigoCuentaIngresoFleteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaIngresoManejoFk()
    {
        return $this->codigoCuentaIngresoManejoFk;
    }

    /**
     * @param mixed $codigoCuentaIngresoManejoFk
     */
    public function setCodigoCuentaIngresoManejoFk( $codigoCuentaIngresoManejoFk ): void
    {
        $this->codigoCuentaIngresoManejoFk = $codigoCuentaIngresoManejoFk;
    }

    /**
     * @return mixed
     */
    public function getNaturalezaCuentaIngresoTercero()
    {
        return $this->naturalezaCuentaIngresoTercero;
    }

    /**
     * @param mixed $naturalezaCuentaIngresoTercero
     */
    public function setNaturalezaCuentaIngresoTercero( $naturalezaCuentaIngresoTercero ): void
    {
        $this->naturalezaCuentaIngresoTercero = $naturalezaCuentaIngresoTercero;
    }

    /**
     * @return mixed
     */
    public function getNaturalezaCuentaIngreso()
    {
        return $this->naturalezaCuentaIngreso;
    }

    /**
     * @param mixed $naturalezaCuentaIngreso
     */
    public function setNaturalezaCuentaIngreso( $naturalezaCuentaIngreso ): void
    {
        $this->naturalezaCuentaIngreso = $naturalezaCuentaIngreso;
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
    public function setCodigoCuentaClienteFk( $codigoCuentaClienteFk ): void
    {
        $this->codigoCuentaClienteFk = $codigoCuentaClienteFk;
    }

    /**
     * @return mixed
     */
    public function getNaturalezaCuentaCliente()
    {
        return $this->naturalezaCuentaCliente;
    }

    /**
     * @param mixed $naturalezaCuentaCliente
     */
    public function setNaturalezaCuentaCliente( $naturalezaCuentaCliente ): void
    {
        $this->naturalezaCuentaCliente = $naturalezaCuentaCliente;
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
    public function setCodigoComprobanteFk( $codigoComprobanteFk ): void
    {
        $this->codigoComprobanteFk = $codigoComprobanteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaIngresoFleteIntermediacionFk()
    {
        return $this->codigoCuentaIngresoFleteIntermediacionFk;
    }

    /**
     * @param mixed $codigoCuentaIngresoFleteIntermediacionFk
     */
    public function setCodigoCuentaIngresoFleteIntermediacionFk( $codigoCuentaIngresoFleteIntermediacionFk ): void
    {
        $this->codigoCuentaIngresoFleteIntermediacionFk = $codigoCuentaIngresoFleteIntermediacionFk;
    }

    /**
     * @return mixed
     */
    public function getNaturalezaCuentaIngresoFleteIntermediacion()
    {
        return $this->naturalezaCuentaIngresoFleteIntermediacion;
    }

    /**
     * @param mixed $naturalezaCuentaIngresoFleteIntermediacion
     */
    public function setNaturalezaCuentaIngresoFleteIntermediacion( $naturalezaCuentaIngresoFleteIntermediacion ): void
    {
        $this->naturalezaCuentaIngresoFleteIntermediacion = $naturalezaCuentaIngresoFleteIntermediacion;
    }

    /**
     * @return mixed
     */
    public function getOperacionComercial()
    {
        return $this->operacionComercial;
    }

    /**
     * @param mixed $operacionComercial
     */
    public function setOperacionComercial( $operacionComercial ): void
    {
        $this->operacionComercial = $operacionComercial;
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
    public function setFacturasFacturaTipoRel( $facturasFacturaTipoRel ): void
    {
        $this->facturasFacturaTipoRel = $facturasFacturaTipoRel;
    }

    /**
     * @return mixed
     */
    public function getGuiasTiposFacturaTipoRel()
    {
        return $this->guiasTiposFacturaTipoRel;
    }

    /**
     * @param mixed $guiasTiposFacturaTipoRel
     */
    public function setGuiasTiposFacturaTipoRel( $guiasTiposFacturaTipoRel ): void
    {
        $this->guiasTiposFacturaTipoRel = $guiasTiposFacturaTipoRel;
    }

    /**
     * @return mixed
     */
    public function getIntermediacionesDetallesFacturaTipoRel()
    {
        return $this->intermediacionesDetallesFacturaTipoRel;
    }

    /**
     * @param mixed $intermediacionesDetallesFacturaTipoRel
     */
    public function setIntermediacionesDetallesFacturaTipoRel( $intermediacionesDetallesFacturaTipoRel ): void
    {
        $this->intermediacionesDetallesFacturaTipoRel = $intermediacionesDetallesFacturaTipoRel;
    }



}

