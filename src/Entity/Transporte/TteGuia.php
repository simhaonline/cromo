<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteGuiaRepository")
 */
class TteGuia
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoGuiaPk;

    /**
     * @ORM\Column(name="numero", type="float", nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="codigo_guia_tipo_fk", type="string", length=20, nullable=true)
     */
    private $codigoGuiaTipoFk;

    /**
     * @ORM\Column(name="codigo_operacion_ingreso_fk", type="string", length=20, nullable=true)
     */
    private $codigoOperacionIngresoFk;

    /**
     * @ORM\Column(name="codigo_operacion_cargo_fk", type="string", length=20, nullable=true)
     */
    private $codigoOperacionCargoFk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_ciudad_origen_fk", type="string", length=20, nullable=true)
     */
    private $codigoCiudadOrigenFk;

    /**
     * @ORM\Column(name="codigo_ciudad_destino_fk", type="string", length=20, nullable=true)
     */
    private $codigoCiudadDestinoFk;

    /**
     * @ORM\Column(name="documento_cliente", type="string", length=80, nullable=true)
     */
    private $documentoCliente;

    /**
     * @ORM\Column(name="relacion_cliente", type="string", length=50, nullable=true)
     */
    private $relacionCliente;

    /**
     * @ORM\Column(name="remitente", type="string", length=80, nullable=true)
     */
    private $remitente;

    /**
     * @ORM\Column(name="nombre_destinatario", type="string", length=150, nullable=true)
     */
    private $nombreDestinatario;

    /**
     * @ORM\Column(name="direccion_destinatario", type="string", length=150, nullable=true)
     */
    private $direccionDestinatario;

    /**
     * @ORM\Column(name="telefono_destinatario", type="string", length=80, nullable=true)
     */
    private $telefonoDestinatario;

    /**
     * @ORM\Column(name="fecha_ingreso", type="datetime", nullable=true)
     */
    private $fechaIngreso;

    /**
     * @ORM\Column(name="fecha_despacho", type="datetime", nullable=true)
     */
    private $fechaDespacho;

    /**
     * @ORM\Column(name="fecha_entrega", type="datetime", nullable=true)
     */
    private $fechaEntrega;

    /**
     * @ORM\Column(name="fecha_cumplido", type="datetime", nullable=true)
     */
    private $fechaCumplido;

    /**
     * @ORM\Column(name="fecha_soporte", type="datetime", nullable=true)
     */
    private $fechaSoporte;

    /**
     * @ORM\Column(name="fecha_factura", type="datetime", nullable=true)
     */
    private $fechaFactura;

    /**
     * @ORM\Column(name="unidades", type="float", options={"default" : 0})
     */
    private $unidades = 0;

    /**
     * @ORM\Column(name="peso_real", type="float", options={"default" : 0})
     */
    private $pesoReal = 0;

    /**
     * @ORM\Column(name="peso_volumen", type="float", options={"default" : 0})
     */
    private $pesoVolumen = 0;

    /**
     * @ORM\Column(name="peso_facturado", type="float", options={"default" : 0})
     */
    private $pesoFacturado = 0;

    /**
     * @ORM\Column(name="vr_declara", type="float", options={"default" : 0})
     */
    private $vrDeclara = 0;

    /**
     * @ORM\Column(name="vr_flete", type="float", options={"default" : 0})
     */
    private $vrFlete = 0;

    /**
     * @ORM\Column(name="vr_manejo", type="float", options={"default" : 0})
     */
    private $vrManejo = 0;

    /**
     * @ORM\Column(name="vr_recaudo", type="float", options={"default" : 0})
     */
    private $vrRecaudo = 0;

    /**
     * @ORM\Column(name="vr_abono", type="float", options={"default" : 0})
     */
    private $vrAbono = 0;

    /**
     * @ORM\Column(name="vr_cobro_entrega", type="float", options={"default" : 0})
     */
    private $vrCobroEntrega = 0;

    /**
     * @ORM\Column(name="vr_costo_reexpedicion", type="float", options={"default" : 0})
     */
    private $vrCostoReexpedicion = 0;

    /**
     * @ORM\Column(name="estado_impreso", type="boolean", nullable=true, options={"default" : 0})
     */
    private $estadoImpreso = false;

    /**
     * @ORM\Column(name="estado_embarcado", type="boolean", nullable=true, options={"default" : 0})
     */
    private $estadoEmbarcado = false;

    /**
     * @ORM\Column(name="estado_despachado", type="boolean", nullable=true, options={"default" : 0})
     */
    private $estadoDespachado = false;

    /**
     * @ORM\Column(name="estado_entregado", type="boolean", nullable=true, options={"default" : 0})
     */
    private $estadoEntregado = false;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_soporte", type="boolean", nullable=true, options={"default" : 0})
     */
    private $estadoSoporte = false;

    /**
     * @ORM\Column(name="estado_cumplido", type="boolean", nullable=true, options={"default" : 0})
     */
    private $estadoCumplido = false;

    /**
     * @ORM\Column(name="estado_recaudo_devolucion", type="boolean", nullable=true, options={"default" : 0})
     */
    private $estadoRecaudoDevolucion = false;

    /**
     * @ORM\Column(name="estado_recaudo_cobro", type="boolean", nullable=true, options={"default" : 0})
     */
    private $estadoRecaudoCobro = false;

    /**
     * @ORM\Column(name="estado_facturado", type="boolean", nullable=true, options={"default" : 0})
     */
    private $estadoFacturado = false;

    /**
     * @ORM\Column(name="estado_factura_generada", type="boolean", nullable=true, options={"default" : 0})
     */
    private $estadoFacturaGenerada = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean", nullable=true, options={"default" : 0})
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="estado_novedad", type="boolean", nullable=true, options={"default" : 0})
     */
    private $estadoNovedad = false;

    /**
     * @ORM\Column(name="estado_novedad_solucion", type="boolean", nullable=true, options={"default" : 0})
     */
    private $estadoNovedadSolucion = false;

    /**
     * @ORM\Column(name="estado_factura_exportado", type="boolean", nullable=true, options={"default" : 0})
     */
    private $estadoFacturaExportado = false;

    /**
     * @ORM\Column(name="codigo_despacho_fk", type="integer", nullable=true)
     */
    private $codigoDespachoFk;

    /**
     * @ORM\Column(name="codigo_cumplido_fk", type="integer", nullable=true)
     */
    private $codigoCumplidoFk;

    /**
     * @ORM\Column(name="codigo_recaudo_devolucion_fk", type="integer", nullable=true)
     */
    private $codigoRecaudoDevolucionFk;

    /**
     * @ORM\Column(name="codigo_recaudo_cobro_fk", type="integer", nullable=true)
     */
    private $codigoRecaudoCobroFk;

    /**
     * @ORM\Column(name="codigo_factura_fk", type="integer", nullable=true)
     */
    private $codigoFacturaFk;

    /**
     * @ORM\Column(name="codigo_factura_planilla_fk", type="integer", nullable=true)
     */
    private $codigoFacturaPlanillaFk;

    /**
     * @ORM\Column(name="codigo_ruta_fk", type="string", length=20, nullable=true)
     */
    private $codigoRutaFk;

    /**
     * @ORM\Column(name="orden_ruta", type="integer", nullable=true, options={"default" : 0})
     */
    private $ordenRuta = 0;

    /**
     * @ORM\Column(name="factura", type="boolean", nullable=true, options={"default" : 0})
     */
    private $factura = false;

    /**
     * @ORM\Column(name="codigo_servicio_fk", type="string", length=20, nullable=true)
     */
    private $codigoServicioFk;

    /**
     * @ORM\Column(name="codigo_producto_fk", type="string", length=20, nullable=true)
     */
    private $codigoProductoFk;

    /**
     * @ORM\Column(name="codigo_empaque_fk", type="string", length=20, nullable=true)
     */
    private $codigoEmpaqueFk;

    /**
     * @ORM\Column(name="codigo_condicion_fk", type="integer", nullable=true)
     */
    private $codigoCondicionFk;

    /**
     * @ORM\Column(name="reexpedicion", type="boolean", nullable=true, options={"default" : 0})
     */
    private $reexpedicion = false;

    /**
     * @ORM\Column(name="usuario", type="string", length=25, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="empaque_referencia", type="string", length=80, nullable=true)
     */
    private $empaqueReferencia;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="TteGuiaTipo", inversedBy="guiasGuiaTipoRel")
     * @ORM\JoinColumn(name="codigo_guia_tipo_fk", referencedColumnName="codigo_guia_tipo_pk")
     * @Assert\NotBlank(
     *     message="Debe seleccionar un tipo"
     * )
     */
    private $guiaTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteOperacion", inversedBy="guiasOperacionIngresoRel")
     * @ORM\JoinColumn(name="codigo_operacion_ingreso_fk", referencedColumnName="codigo_operacion_pk")
     */
    private $operacionIngresoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteOperacion", inversedBy="guiasOperacionCargoRel")
     * @ORM\JoinColumn(name="codigo_operacion_cargo_fk", referencedColumnName="codigo_operacion_pk")
     */
    private $operacionCargoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCliente", inversedBy="guiasClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    private $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCiudad", inversedBy="guiasCiudadOrigenRel")
     * @ORM\JoinColumn(name="codigo_ciudad_origen_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadOrigenRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCiudad", inversedBy="guiasCiudadDestinoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_destino_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadDestinoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteDespacho", inversedBy="guiasDespachoRel")
     * @ORM\JoinColumn(name="codigo_despacho_fk", referencedColumnName="codigo_despacho_pk")
     */
    private $despachoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCumplido", inversedBy="guiasCumplidoRel")
     * @ORM\JoinColumn(name="codigo_cumplido_fk", referencedColumnName="codigo_cumplido_pk")
     */
    private $cumplidoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteRecaudo", inversedBy="guiasRecaudoRel")
     * @ORM\JoinColumn(name="codigo_recaudo_devolucion_fk", referencedColumnName="codigo_recaudo_pk")
     */
    private $recaudoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteRecaudoCobro", inversedBy="guiasRecaudoCobroRel")
     * @ORM\JoinColumn(name="codigo_recaudo_cobro_fk", referencedColumnName="codigo_recaudo_cobro_pk")
     */
    private $recaudoCobroRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteFactura", inversedBy="guiasFacturaRel")
     * @ORM\JoinColumn(name="codigo_factura_fk", referencedColumnName="codigo_factura_pk")
     */
    private $facturaRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteRuta", inversedBy="guiasRutaRel")
     * @ORM\JoinColumn(name="codigo_ruta_fk", referencedColumnName="codigo_ruta_pk")
     */
    private $rutaRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteFacturaPlanilla", inversedBy="guiasFacturaPlanillaRel")
     * @ORM\JoinColumn(name="codigo_factura_planilla_fk", referencedColumnName="codigo_factura_planilla_pk")
     */
    private $facturaPlanillaRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteServicio", inversedBy="guiasServicioRel")
     * @ORM\JoinColumn(name="codigo_servicio_fk", referencedColumnName="codigo_servicio_pk")
     * @Assert\NotBlank(
     *     message="Debe seleccionar un servicio"
     * )
     */
    private $servicioRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteProducto", inversedBy="guiasProductoRel")
     * @ORM\JoinColumn(name="codigo_producto_fk", referencedColumnName="codigo_producto_pk")
     */
    private $productoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteEmpaque", inversedBy="guiasEmpaqueRel")
     * @ORM\JoinColumn(name="codigo_empaque_fk", referencedColumnName="codigo_empaque_pk")
     * @Assert\NotBlank(
     *     message="Debe seleccionar un empaque"
     * )
     */
    private $empaqueRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCondicion", inversedBy="guiasCondicionRel")
     * @ORM\JoinColumn(name="codigo_condicion_fk", referencedColumnName="codigo_condicion_pk")
     */
    private $condicionRel;

    /**
     * @ORM\OneToMany(targetEntity="TteRecibo", mappedBy="guiaRel")
     */
    protected $recibosGuiaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteDespachoDetalle", mappedBy="guiaRel")
     */
    protected $despachosDetallesGuiaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteFacturaDetalle", mappedBy="guiaRel")
     */
    protected $facturasDetallesGuiaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteCosto", mappedBy="guiaRel")
     */
    protected $costosGuiaRel;

    /**
     * @ORM\OneToMany(targetEntity="TteNovedad", mappedBy="guiaRel")
     */
    protected $novedadesGuiaRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuiaDetalle", mappedBy="guiaRel")
     */
    protected $guiasDetallesGuiaRel;

}
