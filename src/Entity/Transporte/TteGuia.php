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
     * @ORM\Column(name="codigo_despacho_fk", type="integer", nullable=true)
     */
    private $codigoDespachoFk;

    /**
     * @ORM\Column(name="codigo_cumplido_fk", type="integer", nullable=true)
     */
    private $codigoCumplidoFk;

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

    /**
     * @return mixed
     */
    public function getCodigoGuiaPk()
    {
        return $this->codigoGuiaPk;
    }

    /**
     * @param mixed $codigoGuiaPk
     */
    public function setCodigoGuiaPk($codigoGuiaPk): void
    {
        $this->codigoGuiaPk = $codigoGuiaPk;
    }

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero): void
    {
        $this->numero = $numero;
    }

    /**
     * @return mixed
     */
    public function getCodigoGuiaTipoFk()
    {
        return $this->codigoGuiaTipoFk;
    }

    /**
     * @param mixed $codigoGuiaTipoFk
     */
    public function setCodigoGuiaTipoFk($codigoGuiaTipoFk): void
    {
        $this->codigoGuiaTipoFk = $codigoGuiaTipoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoOperacionIngresoFk()
    {
        return $this->codigoOperacionIngresoFk;
    }

    /**
     * @param mixed $codigoOperacionIngresoFk
     */
    public function setCodigoOperacionIngresoFk($codigoOperacionIngresoFk): void
    {
        $this->codigoOperacionIngresoFk = $codigoOperacionIngresoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoOperacionCargoFk()
    {
        return $this->codigoOperacionCargoFk;
    }

    /**
     * @param mixed $codigoOperacionCargoFk
     */
    public function setCodigoOperacionCargoFk($codigoOperacionCargoFk): void
    {
        $this->codigoOperacionCargoFk = $codigoOperacionCargoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * @param mixed $codigoClienteFk
     */
    public function setCodigoClienteFk($codigoClienteFk): void
    {
        $this->codigoClienteFk = $codigoClienteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCiudadOrigenFk()
    {
        return $this->codigoCiudadOrigenFk;
    }

    /**
     * @param mixed $codigoCiudadOrigenFk
     */
    public function setCodigoCiudadOrigenFk($codigoCiudadOrigenFk): void
    {
        $this->codigoCiudadOrigenFk = $codigoCiudadOrigenFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCiudadDestinoFk()
    {
        return $this->codigoCiudadDestinoFk;
    }

    /**
     * @param mixed $codigoCiudadDestinoFk
     */
    public function setCodigoCiudadDestinoFk($codigoCiudadDestinoFk): void
    {
        $this->codigoCiudadDestinoFk = $codigoCiudadDestinoFk;
    }

    /**
     * @return mixed
     */
    public function getDocumentoCliente()
    {
        return $this->documentoCliente;
    }

    /**
     * @param mixed $documentoCliente
     */
    public function setDocumentoCliente($documentoCliente): void
    {
        $this->documentoCliente = $documentoCliente;
    }

    /**
     * @return mixed
     */
    public function getRelacionCliente()
    {
        return $this->relacionCliente;
    }

    /**
     * @param mixed $relacionCliente
     */
    public function setRelacionCliente($relacionCliente): void
    {
        $this->relacionCliente = $relacionCliente;
    }

    /**
     * @return mixed
     */
    public function getRemitente()
    {
        return $this->remitente;
    }

    /**
     * @param mixed $remitente
     */
    public function setRemitente($remitente): void
    {
        $this->remitente = $remitente;
    }

    /**
     * @return mixed
     */
    public function getNombreDestinatario()
    {
        return $this->nombreDestinatario;
    }

    /**
     * @param mixed $nombreDestinatario
     */
    public function setNombreDestinatario($nombreDestinatario): void
    {
        $this->nombreDestinatario = $nombreDestinatario;
    }

    /**
     * @return mixed
     */
    public function getDireccionDestinatario()
    {
        return $this->direccionDestinatario;
    }

    /**
     * @param mixed $direccionDestinatario
     */
    public function setDireccionDestinatario($direccionDestinatario): void
    {
        $this->direccionDestinatario = $direccionDestinatario;
    }

    /**
     * @return mixed
     */
    public function getTelefonoDestinatario()
    {
        return $this->telefonoDestinatario;
    }

    /**
     * @param mixed $telefonoDestinatario
     */
    public function setTelefonoDestinatario($telefonoDestinatario): void
    {
        $this->telefonoDestinatario = $telefonoDestinatario;
    }

    /**
     * @return mixed
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * @param mixed $fechaIngreso
     */
    public function setFechaIngreso($fechaIngreso): void
    {
        $this->fechaIngreso = $fechaIngreso;
    }

    /**
     * @return mixed
     */
    public function getFechaDespacho()
    {
        return $this->fechaDespacho;
    }

    /**
     * @param mixed $fechaDespacho
     */
    public function setFechaDespacho($fechaDespacho): void
    {
        $this->fechaDespacho = $fechaDespacho;
    }

    /**
     * @return mixed
     */
    public function getFechaEntrega()
    {
        return $this->fechaEntrega;
    }

    /**
     * @param mixed $fechaEntrega
     */
    public function setFechaEntrega($fechaEntrega): void
    {
        $this->fechaEntrega = $fechaEntrega;
    }

    /**
     * @return mixed
     */
    public function getFechaCumplido()
    {
        return $this->fechaCumplido;
    }

    /**
     * @param mixed $fechaCumplido
     */
    public function setFechaCumplido($fechaCumplido): void
    {
        $this->fechaCumplido = $fechaCumplido;
    }

    /**
     * @return mixed
     */
    public function getFechaSoporte()
    {
        return $this->fechaSoporte;
    }

    /**
     * @param mixed $fechaSoporte
     */
    public function setFechaSoporte($fechaSoporte): void
    {
        $this->fechaSoporte = $fechaSoporte;
    }

    /**
     * @return mixed
     */
    public function getUnidades()
    {
        return $this->unidades;
    }

    /**
     * @param mixed $unidades
     */
    public function setUnidades($unidades): void
    {
        $this->unidades = $unidades;
    }

    /**
     * @return mixed
     */
    public function getPesoReal()
    {
        return $this->pesoReal;
    }

    /**
     * @param mixed $pesoReal
     */
    public function setPesoReal($pesoReal): void
    {
        $this->pesoReal = $pesoReal;
    }

    /**
     * @return mixed
     */
    public function getPesoVolumen()
    {
        return $this->pesoVolumen;
    }

    /**
     * @param mixed $pesoVolumen
     */
    public function setPesoVolumen($pesoVolumen): void
    {
        $this->pesoVolumen = $pesoVolumen;
    }

    /**
     * @return mixed
     */
    public function getPesoFacturado()
    {
        return $this->pesoFacturado;
    }

    /**
     * @param mixed $pesoFacturado
     */
    public function setPesoFacturado($pesoFacturado): void
    {
        $this->pesoFacturado = $pesoFacturado;
    }

    /**
     * @return mixed
     */
    public function getVrDeclara()
    {
        return $this->vrDeclara;
    }

    /**
     * @param mixed $vrDeclara
     */
    public function setVrDeclara($vrDeclara): void
    {
        $this->vrDeclara = $vrDeclara;
    }

    /**
     * @return mixed
     */
    public function getVrFlete()
    {
        return $this->vrFlete;
    }

    /**
     * @param mixed $vrFlete
     */
    public function setVrFlete($vrFlete): void
    {
        $this->vrFlete = $vrFlete;
    }

    /**
     * @return mixed
     */
    public function getVrManejo()
    {
        return $this->vrManejo;
    }

    /**
     * @param mixed $vrManejo
     */
    public function setVrManejo($vrManejo): void
    {
        $this->vrManejo = $vrManejo;
    }

    /**
     * @return mixed
     */
    public function getVrRecaudo()
    {
        return $this->vrRecaudo;
    }

    /**
     * @param mixed $vrRecaudo
     */
    public function setVrRecaudo($vrRecaudo): void
    {
        $this->vrRecaudo = $vrRecaudo;
    }

    /**
     * @return mixed
     */
    public function getVrAbono()
    {
        return $this->vrAbono;
    }

    /**
     * @param mixed $vrAbono
     */
    public function setVrAbono($vrAbono): void
    {
        $this->vrAbono = $vrAbono;
    }

    /**
     * @return mixed
     */
    public function getVrCobroEntrega()
    {
        return $this->vrCobroEntrega;
    }

    /**
     * @param mixed $vrCobroEntrega
     */
    public function setVrCobroEntrega($vrCobroEntrega): void
    {
        $this->vrCobroEntrega = $vrCobroEntrega;
    }

    /**
     * @return mixed
     */
    public function getEstadoImpreso()
    {
        return $this->estadoImpreso;
    }

    /**
     * @param mixed $estadoImpreso
     */
    public function setEstadoImpreso($estadoImpreso): void
    {
        $this->estadoImpreso = $estadoImpreso;
    }

    /**
     * @return mixed
     */
    public function getEstadoEmbarcado()
    {
        return $this->estadoEmbarcado;
    }

    /**
     * @param mixed $estadoEmbarcado
     */
    public function setEstadoEmbarcado($estadoEmbarcado): void
    {
        $this->estadoEmbarcado = $estadoEmbarcado;
    }

    /**
     * @return mixed
     */
    public function getEstadoDespachado()
    {
        return $this->estadoDespachado;
    }

    /**
     * @param mixed $estadoDespachado
     */
    public function setEstadoDespachado($estadoDespachado): void
    {
        $this->estadoDespachado = $estadoDespachado;
    }

    /**
     * @return mixed
     */
    public function getEstadoEntregado()
    {
        return $this->estadoEntregado;
    }

    /**
     * @param mixed $estadoEntregado
     */
    public function setEstadoEntregado($estadoEntregado): void
    {
        $this->estadoEntregado = $estadoEntregado;
    }

    /**
     * @return mixed
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * @param mixed $estadoAutorizado
     */
    public function setEstadoAutorizado($estadoAutorizado): void
    {
        $this->estadoAutorizado = $estadoAutorizado;
    }

    /**
     * @return mixed
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * @param mixed $estadoAprobado
     */
    public function setEstadoAprobado($estadoAprobado): void
    {
        $this->estadoAprobado = $estadoAprobado;
    }

    /**
     * @return mixed
     */
    public function getEstadoSoporte()
    {
        return $this->estadoSoporte;
    }

    /**
     * @param mixed $estadoSoporte
     */
    public function setEstadoSoporte($estadoSoporte): void
    {
        $this->estadoSoporte = $estadoSoporte;
    }

    /**
     * @return mixed
     */
    public function getEstadoCumplido()
    {
        return $this->estadoCumplido;
    }

    /**
     * @param mixed $estadoCumplido
     */
    public function setEstadoCumplido($estadoCumplido): void
    {
        $this->estadoCumplido = $estadoCumplido;
    }

    /**
     * @return mixed
     */
    public function getEstadoFacturado()
    {
        return $this->estadoFacturado;
    }

    /**
     * @param mixed $estadoFacturado
     */
    public function setEstadoFacturado($estadoFacturado): void
    {
        $this->estadoFacturado = $estadoFacturado;
    }

    /**
     * @return mixed
     */
    public function getEstadoFacturaGenerada()
    {
        return $this->estadoFacturaGenerada;
    }

    /**
     * @param mixed $estadoFacturaGenerada
     */
    public function setEstadoFacturaGenerada($estadoFacturaGenerada): void
    {
        $this->estadoFacturaGenerada = $estadoFacturaGenerada;
    }

    /**
     * @return mixed
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * @param mixed $estadoAnulado
     */
    public function setEstadoAnulado($estadoAnulado): void
    {
        $this->estadoAnulado = $estadoAnulado;
    }

    /**
     * @return mixed
     */
    public function getCodigoDespachoFk()
    {
        return $this->codigoDespachoFk;
    }

    /**
     * @param mixed $codigoDespachoFk
     */
    public function setCodigoDespachoFk($codigoDespachoFk): void
    {
        $this->codigoDespachoFk = $codigoDespachoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCumplidoFk()
    {
        return $this->codigoCumplidoFk;
    }

    /**
     * @param mixed $codigoCumplidoFk
     */
    public function setCodigoCumplidoFk($codigoCumplidoFk): void
    {
        $this->codigoCumplidoFk = $codigoCumplidoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoFacturaFk()
    {
        return $this->codigoFacturaFk;
    }

    /**
     * @param mixed $codigoFacturaFk
     */
    public function setCodigoFacturaFk($codigoFacturaFk): void
    {
        $this->codigoFacturaFk = $codigoFacturaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoFacturaPlanillaFk()
    {
        return $this->codigoFacturaPlanillaFk;
    }

    /**
     * @param mixed $codigoFacturaPlanillaFk
     */
    public function setCodigoFacturaPlanillaFk($codigoFacturaPlanillaFk): void
    {
        $this->codigoFacturaPlanillaFk = $codigoFacturaPlanillaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoRutaFk()
    {
        return $this->codigoRutaFk;
    }

    /**
     * @param mixed $codigoRutaFk
     */
    public function setCodigoRutaFk($codigoRutaFk): void
    {
        $this->codigoRutaFk = $codigoRutaFk;
    }

    /**
     * @return mixed
     */
    public function getOrdenRuta()
    {
        return $this->ordenRuta;
    }

    /**
     * @param mixed $ordenRuta
     */
    public function setOrdenRuta($ordenRuta): void
    {
        $this->ordenRuta = $ordenRuta;
    }

    /**
     * @return mixed
     */
    public function getFactura()
    {
        return $this->factura;
    }

    /**
     * @param mixed $factura
     */
    public function setFactura($factura): void
    {
        $this->factura = $factura;
    }

    /**
     * @return mixed
     */
    public function getCodigoServicioFk()
    {
        return $this->codigoServicioFk;
    }

    /**
     * @param mixed $codigoServicioFk
     */
    public function setCodigoServicioFk($codigoServicioFk): void
    {
        $this->codigoServicioFk = $codigoServicioFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoProductoFk()
    {
        return $this->codigoProductoFk;
    }

    /**
     * @param mixed $codigoProductoFk
     */
    public function setCodigoProductoFk($codigoProductoFk): void
    {
        $this->codigoProductoFk = $codigoProductoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEmpaqueFk()
    {
        return $this->codigoEmpaqueFk;
    }

    /**
     * @param mixed $codigoEmpaqueFk
     */
    public function setCodigoEmpaqueFk($codigoEmpaqueFk): void
    {
        $this->codigoEmpaqueFk = $codigoEmpaqueFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCondicionFk()
    {
        return $this->codigoCondicionFk;
    }

    /**
     * @param mixed $codigoCondicionFk
     */
    public function setCodigoCondicionFk($codigoCondicionFk): void
    {
        $this->codigoCondicionFk = $codigoCondicionFk;
    }

    /**
     * @return mixed
     */
    public function getReexpedicion()
    {
        return $this->reexpedicion;
    }

    /**
     * @param mixed $reexpedicion
     */
    public function setReexpedicion($reexpedicion): void
    {
        $this->reexpedicion = $reexpedicion;
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
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * @param mixed $comentario
     */
    public function setComentario($comentario): void
    {
        $this->comentario = $comentario;
    }

    /**
     * @return mixed
     */
    public function getGuiaTipoRel()
    {
        return $this->guiaTipoRel;
    }

    /**
     * @param mixed $guiaTipoRel
     */
    public function setGuiaTipoRel($guiaTipoRel): void
    {
        $this->guiaTipoRel = $guiaTipoRel;
    }

    /**
     * @return mixed
     */
    public function getOperacionIngresoRel()
    {
        return $this->operacionIngresoRel;
    }

    /**
     * @param mixed $operacionIngresoRel
     */
    public function setOperacionIngresoRel($operacionIngresoRel): void
    {
        $this->operacionIngresoRel = $operacionIngresoRel;
    }

    /**
     * @return mixed
     */
    public function getOperacionCargoRel()
    {
        return $this->operacionCargoRel;
    }

    /**
     * @param mixed $operacionCargoRel
     */
    public function setOperacionCargoRel($operacionCargoRel): void
    {
        $this->operacionCargoRel = $operacionCargoRel;
    }

    /**
     * @return mixed
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * @param mixed $clienteRel
     */
    public function setClienteRel($clienteRel): void
    {
        $this->clienteRel = $clienteRel;
    }

    /**
     * @return mixed
     */
    public function getCiudadOrigenRel()
    {
        return $this->ciudadOrigenRel;
    }

    /**
     * @param mixed $ciudadOrigenRel
     */
    public function setCiudadOrigenRel($ciudadOrigenRel): void
    {
        $this->ciudadOrigenRel = $ciudadOrigenRel;
    }

    /**
     * @return mixed
     */
    public function getCiudadDestinoRel()
    {
        return $this->ciudadDestinoRel;
    }

    /**
     * @param mixed $ciudadDestinoRel
     */
    public function setCiudadDestinoRel($ciudadDestinoRel): void
    {
        $this->ciudadDestinoRel = $ciudadDestinoRel;
    }

    /**
     * @return mixed
     */
    public function getDespachoRel()
    {
        return $this->despachoRel;
    }

    /**
     * @param mixed $despachoRel
     */
    public function setDespachoRel($despachoRel): void
    {
        $this->despachoRel = $despachoRel;
    }

    /**
     * @return mixed
     */
    public function getCumplidoRel()
    {
        return $this->cumplidoRel;
    }

    /**
     * @param mixed $cumplidoRel
     */
    public function setCumplidoRel($cumplidoRel): void
    {
        $this->cumplidoRel = $cumplidoRel;
    }

    /**
     * @return mixed
     */
    public function getFacturaRel()
    {
        return $this->facturaRel;
    }

    /**
     * @param mixed $facturaRel
     */
    public function setFacturaRel($facturaRel): void
    {
        $this->facturaRel = $facturaRel;
    }

    /**
     * @return mixed
     */
    public function getRutaRel()
    {
        return $this->rutaRel;
    }

    /**
     * @param mixed $rutaRel
     */
    public function setRutaRel($rutaRel): void
    {
        $this->rutaRel = $rutaRel;
    }

    /**
     * @return mixed
     */
    public function getFacturaPlanillaRel()
    {
        return $this->facturaPlanillaRel;
    }

    /**
     * @param mixed $facturaPlanillaRel
     */
    public function setFacturaPlanillaRel($facturaPlanillaRel): void
    {
        $this->facturaPlanillaRel = $facturaPlanillaRel;
    }

    /**
     * @return mixed
     */
    public function getServicioRel()
    {
        return $this->servicioRel;
    }

    /**
     * @param mixed $servicioRel
     */
    public function setServicioRel($servicioRel): void
    {
        $this->servicioRel = $servicioRel;
    }

    /**
     * @return mixed
     */
    public function getProductoRel()
    {
        return $this->productoRel;
    }

    /**
     * @param mixed $productoRel
     */
    public function setProductoRel($productoRel): void
    {
        $this->productoRel = $productoRel;
    }

    /**
     * @return mixed
     */
    public function getEmpaqueRel()
    {
        return $this->empaqueRel;
    }

    /**
     * @param mixed $empaqueRel
     */
    public function setEmpaqueRel($empaqueRel): void
    {
        $this->empaqueRel = $empaqueRel;
    }

    /**
     * @return mixed
     */
    public function getCondicionRel()
    {
        return $this->condicionRel;
    }

    /**
     * @param mixed $condicionRel
     */
    public function setCondicionRel($condicionRel): void
    {
        $this->condicionRel = $condicionRel;
    }

    /**
     * @return mixed
     */
    public function getRecibosGuiaRel()
    {
        return $this->recibosGuiaRel;
    }

    /**
     * @param mixed $recibosGuiaRel
     */
    public function setRecibosGuiaRel($recibosGuiaRel): void
    {
        $this->recibosGuiaRel = $recibosGuiaRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosDetallesGuiaRel()
    {
        return $this->despachosDetallesGuiaRel;
    }

    /**
     * @param mixed $despachosDetallesGuiaRel
     */
    public function setDespachosDetallesGuiaRel($despachosDetallesGuiaRel): void
    {
        $this->despachosDetallesGuiaRel = $despachosDetallesGuiaRel;
    }

    /**
     * @return mixed
     */
    public function getCostosGuiaRel()
    {
        return $this->costosGuiaRel;
    }

    /**
     * @param mixed $costosGuiaRel
     */
    public function setCostosGuiaRel($costosGuiaRel): void
    {
        $this->costosGuiaRel = $costosGuiaRel;
    }

    /**
     * @return mixed
     */
    public function getNovedadesGuiaRel()
    {
        return $this->novedadesGuiaRel;
    }

    /**
     * @param mixed $novedadesGuiaRel
     */
    public function setNovedadesGuiaRel($novedadesGuiaRel): void
    {
        $this->novedadesGuiaRel = $novedadesGuiaRel;
    }

    /**
     * @return mixed
     */
    public function getGuiasDetallesGuiaRel()
    {
        return $this->guiasDetallesGuiaRel;
    }

    /**
     * @param mixed $guiasDetallesGuiaRel
     */
    public function setGuiasDetallesGuiaRel($guiasDetallesGuiaRel): void
    {
        $this->guiasDetallesGuiaRel = $guiasDetallesGuiaRel;
    }

    /**
     * @return mixed
     */
    public function getEmpaqueReferencia()
    {
        return $this->empaqueReferencia;
    }

    /**
     * @param mixed $empaqueReferencia
     */
    public function setEmpaqueReferencia($empaqueReferencia): void
    {
        $this->empaqueReferencia = $empaqueReferencia;
    }

    /**
     * @return mixed
     */
    public function getVrCostoReexpedicion()
    {
        return $this->vrCostoReexpedicion;
    }

    /**
     * @param mixed $vrCostoReexpedicion
     */
    public function setVrCostoReexpedicion($vrCostoReexpedicion): void
    {
        $this->vrCostoReexpedicion = $vrCostoReexpedicion;
    }

    /**
     * @return mixed
     */
    public function getFacturasDetallesGuiaRel()
    {
        return $this->facturasDetallesGuiaRel;
    }

    /**
     * @param mixed $facturasDetallesGuiaRel
     */
    public function setFacturasDetallesGuiaRel($facturasDetallesGuiaRel): void
    {
        $this->facturasDetallesGuiaRel = $facturasDetallesGuiaRel;
    }

    /**
     * @return mixed
     */
    public function getFechaFactura()
    {
        return $this->fechaFactura;
    }

    /**
     * @param mixed $fechaFactura
     */
    public function setFechaFactura($fechaFactura): void
    {
        $this->fechaFactura = $fechaFactura;
    }



}
