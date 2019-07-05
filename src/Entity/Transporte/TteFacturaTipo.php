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



}

