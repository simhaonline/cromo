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
     * @ORM\Column(name="codigo_cuenta_ingreso_flete_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaIngresoFleteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_cliente_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaClienteFk;

    /**
     * @ORM\Column(name="codigo_comprobante_fk", type="string", length=20, nullable=true)
     */
    private $codigoComprobanteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_ingreso_flete_intermediacion_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaIngresoFleteIntermediacionFk;

    /**
     * @ORM\Column(name="operacion_comercial", type="smallint", nullable=true, options={"default" : 0})
     */
    private $operacionComercial = 0;

    /**
     * Para imputar el ingreso a cuentas fijas para este tipo de factura no por CO
     * @ORM\Column(name="contabilizar_ingreso_inicial_fijo", type="boolean", nullable=true, options={"default" : false})
     */
    private $contabilizarIngresoInicialFijo = false;

    /**
     * @ORM\Column(name="codigo_cuenta_ingreso_inicial_fijo_flete_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaIngresoInicialFijoFleteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_ingreso_inicial_fijo_manejo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaIngresoInicialFijoManejoFk;

    /**
     * @ORM\Column(name="intermediacion", type="boolean", nullable=true, options={"default" : false})
     */
    private $intermediacion = false;

    /**
     * @ORM\OneToMany(targetEntity="TteFactura", mappedBy="facturaTipoRel")
     */
    protected $facturasFacturaTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuiaTipo", mappedBy="facturaTipoRel")
     */
    protected $guiasTiposFacturaTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteIntermediacionVenta", mappedBy="facturaTipoRel")
     */
    protected $intermediacionesVentasFacturaTipoRel;

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
    public function getResolucionFacturacion()
    {
        return $this->resolucionFacturacion;
    }

    /**
     * @param mixed $resolucionFacturacion
     */
    public function setResolucionFacturacion($resolucionFacturacion): void
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
    public function setGuiaFactura($guiaFactura): void
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
    public function setPrefijo($prefijo): void
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
    public function getCodigoCuentaIngresoFleteFk()
    {
        return $this->codigoCuentaIngresoFleteFk;
    }

    /**
     * @param mixed $codigoCuentaIngresoFleteFk
     */
    public function setCodigoCuentaIngresoFleteFk($codigoCuentaIngresoFleteFk): void
    {
        $this->codigoCuentaIngresoFleteFk = $codigoCuentaIngresoFleteFk;
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
    public function getCodigoCuentaIngresoFleteIntermediacionFk()
    {
        return $this->codigoCuentaIngresoFleteIntermediacionFk;
    }

    /**
     * @param mixed $codigoCuentaIngresoFleteIntermediacionFk
     */
    public function setCodigoCuentaIngresoFleteIntermediacionFk($codigoCuentaIngresoFleteIntermediacionFk): void
    {
        $this->codigoCuentaIngresoFleteIntermediacionFk = $codigoCuentaIngresoFleteIntermediacionFk;
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
    public function setOperacionComercial($operacionComercial): void
    {
        $this->operacionComercial = $operacionComercial;
    }

    /**
     * @return mixed
     */
    public function getContabilizarIngresoInicialFijo()
    {
        return $this->contabilizarIngresoInicialFijo;
    }

    /**
     * @param mixed $contabilizarIngresoInicialFijo
     */
    public function setContabilizarIngresoInicialFijo($contabilizarIngresoInicialFijo): void
    {
        $this->contabilizarIngresoInicialFijo = $contabilizarIngresoInicialFijo;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaIngresoInicialFijoFleteFk()
    {
        return $this->codigoCuentaIngresoInicialFijoFleteFk;
    }

    /**
     * @param mixed $codigoCuentaIngresoInicialFijoFleteFk
     */
    public function setCodigoCuentaIngresoInicialFijoFleteFk($codigoCuentaIngresoInicialFijoFleteFk): void
    {
        $this->codigoCuentaIngresoInicialFijoFleteFk = $codigoCuentaIngresoInicialFijoFleteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaIngresoInicialFijoManejoFk()
    {
        return $this->codigoCuentaIngresoInicialFijoManejoFk;
    }

    /**
     * @param mixed $codigoCuentaIngresoInicialFijoManejoFk
     */
    public function setCodigoCuentaIngresoInicialFijoManejoFk($codigoCuentaIngresoInicialFijoManejoFk): void
    {
        $this->codigoCuentaIngresoInicialFijoManejoFk = $codigoCuentaIngresoInicialFijoManejoFk;
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
    public function setGuiasTiposFacturaTipoRel($guiasTiposFacturaTipoRel): void
    {
        $this->guiasTiposFacturaTipoRel = $guiasTiposFacturaTipoRel;
    }

    /**
     * @return mixed
     */
    public function getIntermediacionesVentasFacturaTipoRel()
    {
        return $this->intermediacionesVentasFacturaTipoRel;
    }

    /**
     * @param mixed $intermediacionesVentasFacturaTipoRel
     */
    public function setIntermediacionesVentasFacturaTipoRel($intermediacionesVentasFacturaTipoRel): void
    {
        $this->intermediacionesVentasFacturaTipoRel = $intermediacionesVentasFacturaTipoRel;
    }

    /**
     * @return mixed
     */
    public function getIntermediacion()
    {
        return $this->intermediacion;
    }

    /**
     * @param mixed $intermediacion
     */
    public function setIntermediacion($intermediacion): void
    {
        $this->intermediacion = $intermediacion;
    }



}

