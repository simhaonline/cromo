<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteDespachoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteDespacho
{
    public $infoLog = [
        "primaryKey" => "codigoDespachoPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="codigo_despacho_pk")
     */
    private $codigoDespachoPk;

    /**
     * @ORM\Column(name="numero", type="float", nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="numero_rndc", type="string", length=40, nullable=true)
     */
    private $numeroRndc;

    /**
     * @ORM\Column(name="fecha_registro", type="datetime", nullable=true)
     */
    private $fechaRegistro;

    /**
     * @ORM\Column(name="fecha_salida", type="datetime", nullable=true)
     */
    private $fechaSalida;

    /**
     * @ORM\Column(name="fecha_llegada", type="datetime", nullable=true)
     */
    private $fechaLlegada;

    /**
     * @ORM\Column(name="fecha_soporte", type="datetime", nullable=true)
     */
    private $fechaSoporte;

    /**
     * @ORM\Column(name="codigo_operacion_fk", type="string", length=20, nullable=true)
     */
    private $codigoOperacionFk;

    /**
     * @ORM\Column(name="codigo_ciudad_origen_fk", type="string", length=20, nullable=true)
     */
    private $codigoCiudadOrigenFk;

    /**
     * @ORM\Column(name="codigo_ciudad_destino_fk", type="string", length=20, nullable=true)
     */
    private $codigoCiudadDestinoFk;

    /**
     * @ORM\Column(name="codigo_vehiculo_fk", type="string", length=20, nullable=true)
     */
    private $codigoVehiculoFk;

    /**
     * @ORM\Column(name="codigo_conductor_fk", type="integer", nullable=true)
     */
    private $codigoConductorFk;

    /**
     * @ORM\Column(name="codigo_poseedor_fk", type="integer", nullable=true)
     */
    private $codigoPoseedorFk;

    /**
     * @ORM\Column(name="codigo_ruta_fk", type="string", length=20, nullable=true)
     */
    private $codigoRutaFk;

    /**
     * @ORM\Column(name="codigo_despacho_clase_fk", type="string", length=5, nullable=true)
     */
    private $codigoDespachoClaseFk;

    /**
     * @ORM\Column(name="cantidad", type="float", options={"default" : 0})
     */
    private $cantidad = 0;

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
     * @ORM\Column(name="peso_costo", type="float", options={"default" : 0})
     */
    private $pesoCosto = 0;

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
     * @ORM\Column(name="vr_flete_pago", type="float", options={"default" : 0})
     */
    private $vrFletePago = 0;

    /**
     * @ORM\Column(name="vr_anticipo", type="float", options={"default" : 0})
     */
    private $vrAnticipo = 0;

    /**
     * @ORM\Column(name="vr_industria_comercio", type="float", options={"default" : 0})
     */
    private $vrIndustriaComercio = 0;

    /**
     * @ORM\Column(name="vr_retencion_fuente", type="float", options={"default" : 0})
     */
    private $vrRetencionFuente = 0;

    /**
     * @ORM\Column(name="vr_total", type="float", options={"default" : 0})
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="vr_total_neto", type="float", options={"default" : 0})
     */
    private $vrTotalNeto = 0;

    /**
     * @ORM\Column(name="vr_descuento_papeleria", type="float", options={"default" : 0})
     */
    private $vrDescuentoPapeleria = 0;

    /**
     * @ORM\Column(name="vr_descuento_seguridad", type="float", options={"default" : 0})
     */
    private $vrDescuentoSeguridad = 0;

    /**
     * @ORM\Column(name="vr_descuento_cargue", type="float", options={"default" : 0})
     */
    private $vrDescuentoCargue = 0;

    /**
     * @ORM\Column(name="vr_descuento_estampilla", type="float", options={"default" : 0})
     */
    private $vrDescuentoEstampilla = 0;

    /**
     * @ORM\Column(name="vr_cobro_entrega", type="float", options={"default" : 0})
     */
    private $vrCobroEntrega = 0;

    /**
     * @ORM\Column(name="vr_cobro_entrega_rechazado", type="float", options={"default" : 0})
     */
    private $vrCobroEntregaRechazado = 0;

    /**
     * @ORM\Column(name="vr_saldo", type="float", options={"default" : 0})
     */
    private $vrSaldo = 0;

    /**
     * @ORM\Column(name="vr_costo", type="float", nullable=true, options={"default" : 0})
     */
    private $vrCosto = 0;

    /**
     * @ORM\Column(name="vr_costo_base", type="float", nullable=true, options={"default" : 0})
     */
    private $vrCostoBase = 0;

    /**
     * @ORM\Column(name="vr_costo_pago", type="float", options={"default" : 0})
     */
    private $vrCostoPago = 0;

    /**
     * @ORM\Column(name="porcentaje_rentabilidad", type="float", nullable=true, options={"default" : 0})
     */
    private $porcentajeRentabilidad = 0;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_cerrado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoCerrado = false;

    /**
     * @ORM\Column(name="estado_soporte", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoSoporte = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoContabilizado = false;

    /**
     * @ORM\Column(name="estado_cumplir_rndc", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoCumplirRndc = false;

    /**
     * @ORM\Column(name="estado_novedad", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoNovedad = false;

    /**
     * @ORM\Column(name="estado_novedad_solucion", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoNovedadSolucion = false;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\Column(name="codigo_despacho_tipo_fk", type="string", length=20, nullable=true)
     */
    private $codigoDespachoTipoFk;

    /**
     * @ORM\Column(name="usuario", type="string", length=25, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteDespachoTipo", inversedBy="despachosDespachoTipoRel")
     * @ORM\JoinColumn(name="codigo_despacho_tipo_fk", referencedColumnName="codigo_despacho_tipo_pk")
     */
    private $despachoTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCiudad", inversedBy="despachosCiudadOrigenRel")
     * @ORM\JoinColumn(name="codigo_ciudad_origen_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadOrigenRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCiudad", inversedBy="despachosCiudadDestinoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_destino_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadDestinoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteOperacion", inversedBy="despachosOperacionRel")
     * @ORM\JoinColumn(name="codigo_operacion_fk", referencedColumnName="codigo_operacion_pk")
     */
    private $operacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteVehiculo", inversedBy="despachosVehiculoRel")
     * @ORM\JoinColumn(name="codigo_vehiculo_fk", referencedColumnName="codigo_vehiculo_pk")
     */
    private $vehiculoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteConductor", inversedBy="despachosConductorRel")
     * @ORM\JoinColumn(name="codigo_conductor_fk", referencedColumnName="codigo_conductor_pk")
     */
    private $conductorRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteRuta", inversedBy="despachosRutaRel")
     * @ORM\JoinColumn(name="codigo_ruta_fk", referencedColumnName="codigo_ruta_pk")
     */
    private $rutaRel;

    /**
     * @ORM\ManyToOne(targetEntity="TtePoseedor", inversedBy="despachosPoseedorRel")
     * @ORM\JoinColumn(name="codigo_poseedor_fk", referencedColumnName="codigo_poseedor_pk")
     */
    private $poseedorRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="despachoRel")
     */
    protected $guiasDespachoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteDespachoDetalle", mappedBy="despachoRel")
     */
    protected $despachosDetallesDespachoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteDespachoAuxiliar", mappedBy="despachoRel")
     */
    protected $despachoAuxiliarRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteDespachoAdicional", mappedBy="despachoRel")
     */
    protected $despachosAdicionalesDespachoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteMonitoreo", mappedBy="despachoRel")
     */
    protected $monitoreosDespachoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteNovedad", mappedBy="despachoRel")
     */
    protected $novedadesDespachoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteRedespacho", mappedBy="redespachoDespachoRel")
     */
    protected $redespachosDespachoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteDesembarco", mappedBy="despachoRel")
     */
    protected $desembarcosDespachoRel;

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
    public function getCodigoDespachoPk()
    {
        return $this->codigoDespachoPk;
    }

    /**
     * @param mixed $codigoDespachoPk
     */
    public function setCodigoDespachoPk($codigoDespachoPk): void
    {
        $this->codigoDespachoPk = $codigoDespachoPk;
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
    public function getNumeroRndc()
    {
        return $this->numeroRndc;
    }

    /**
     * @param mixed $numeroRndc
     */
    public function setNumeroRndc($numeroRndc): void
    {
        $this->numeroRndc = $numeroRndc;
    }

    /**
     * @return mixed
     */
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }

    /**
     * @param mixed $fechaRegistro
     */
    public function setFechaRegistro($fechaRegistro): void
    {
        $this->fechaRegistro = $fechaRegistro;
    }

    /**
     * @return mixed
     */
    public function getFechaSalida()
    {
        return $this->fechaSalida;
    }

    /**
     * @param mixed $fechaSalida
     */
    public function setFechaSalida($fechaSalida): void
    {
        $this->fechaSalida = $fechaSalida;
    }

    /**
     * @return mixed
     */
    public function getFechaLlegada()
    {
        return $this->fechaLlegada;
    }

    /**
     * @param mixed $fechaLlegada
     */
    public function setFechaLlegada($fechaLlegada): void
    {
        $this->fechaLlegada = $fechaLlegada;
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
    public function getCodigoOperacionFk()
    {
        return $this->codigoOperacionFk;
    }

    /**
     * @param mixed $codigoOperacionFk
     */
    public function setCodigoOperacionFk($codigoOperacionFk): void
    {
        $this->codigoOperacionFk = $codigoOperacionFk;
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
    public function getCodigoVehiculoFk()
    {
        return $this->codigoVehiculoFk;
    }

    /**
     * @param mixed $codigoVehiculoFk
     */
    public function setCodigoVehiculoFk($codigoVehiculoFk): void
    {
        $this->codigoVehiculoFk = $codigoVehiculoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoConductorFk()
    {
        return $this->codigoConductorFk;
    }

    /**
     * @param mixed $codigoConductorFk
     */
    public function setCodigoConductorFk($codigoConductorFk): void
    {
        $this->codigoConductorFk = $codigoConductorFk;
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
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param mixed $cantidad
     */
    public function setCantidad($cantidad): void
    {
        $this->cantidad = $cantidad;
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
    public function getPesoCosto()
    {
        return $this->pesoCosto;
    }

    /**
     * @param mixed $pesoCosto
     */
    public function setPesoCosto($pesoCosto): void
    {
        $this->pesoCosto = $pesoCosto;
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
    public function getVrFletePago()
    {
        return $this->vrFletePago;
    }

    /**
     * @param mixed $vrFletePago
     */
    public function setVrFletePago($vrFletePago): void
    {
        $this->vrFletePago = $vrFletePago;
    }

    /**
     * @return mixed
     */
    public function getVrAnticipo()
    {
        return $this->vrAnticipo;
    }

    /**
     * @param mixed $vrAnticipo
     */
    public function setVrAnticipo($vrAnticipo): void
    {
        $this->vrAnticipo = $vrAnticipo;
    }

    /**
     * @return mixed
     */
    public function getVrIndustriaComercio()
    {
        return $this->vrIndustriaComercio;
    }

    /**
     * @param mixed $vrIndustriaComercio
     */
    public function setVrIndustriaComercio($vrIndustriaComercio): void
    {
        $this->vrIndustriaComercio = $vrIndustriaComercio;
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
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * @param mixed $vrTotal
     */
    public function setVrTotal($vrTotal): void
    {
        $this->vrTotal = $vrTotal;
    }

    /**
     * @return mixed
     */
    public function getVrTotalNeto()
    {
        return $this->vrTotalNeto;
    }

    /**
     * @param mixed $vrTotalNeto
     */
    public function setVrTotalNeto($vrTotalNeto): void
    {
        $this->vrTotalNeto = $vrTotalNeto;
    }

    /**
     * @return mixed
     */
    public function getVrDescuentoPapeleria()
    {
        return $this->vrDescuentoPapeleria;
    }

    /**
     * @param mixed $vrDescuentoPapeleria
     */
    public function setVrDescuentoPapeleria($vrDescuentoPapeleria): void
    {
        $this->vrDescuentoPapeleria = $vrDescuentoPapeleria;
    }

    /**
     * @return mixed
     */
    public function getVrDescuentoSeguridad()
    {
        return $this->vrDescuentoSeguridad;
    }

    /**
     * @param mixed $vrDescuentoSeguridad
     */
    public function setVrDescuentoSeguridad($vrDescuentoSeguridad): void
    {
        $this->vrDescuentoSeguridad = $vrDescuentoSeguridad;
    }

    /**
     * @return mixed
     */
    public function getVrDescuentoCargue()
    {
        return $this->vrDescuentoCargue;
    }

    /**
     * @param mixed $vrDescuentoCargue
     */
    public function setVrDescuentoCargue($vrDescuentoCargue): void
    {
        $this->vrDescuentoCargue = $vrDescuentoCargue;
    }

    /**
     * @return mixed
     */
    public function getVrDescuentoEstampilla()
    {
        return $this->vrDescuentoEstampilla;
    }

    /**
     * @param mixed $vrDescuentoEstampilla
     */
    public function setVrDescuentoEstampilla($vrDescuentoEstampilla): void
    {
        $this->vrDescuentoEstampilla = $vrDescuentoEstampilla;
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
    public function getVrCobroEntregaRechazado()
    {
        return $this->vrCobroEntregaRechazado;
    }

    /**
     * @param mixed $vrCobroEntregaRechazado
     */
    public function setVrCobroEntregaRechazado($vrCobroEntregaRechazado): void
    {
        $this->vrCobroEntregaRechazado = $vrCobroEntregaRechazado;
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
    public function setVrSaldo($vrSaldo): void
    {
        $this->vrSaldo = $vrSaldo;
    }

    /**
     * @return mixed
     */
    public function getVrCosto()
    {
        return $this->vrCosto;
    }

    /**
     * @param mixed $vrCosto
     */
    public function setVrCosto($vrCosto): void
    {
        $this->vrCosto = $vrCosto;
    }

    /**
     * @return mixed
     */
    public function getVrCostoBase()
    {
        return $this->vrCostoBase;
    }

    /**
     * @param mixed $vrCostoBase
     */
    public function setVrCostoBase($vrCostoBase): void
    {
        $this->vrCostoBase = $vrCostoBase;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeRentabilidad()
    {
        return $this->porcentajeRentabilidad;
    }

    /**
     * @param mixed $porcentajeRentabilidad
     */
    public function setPorcentajeRentabilidad($porcentajeRentabilidad): void
    {
        $this->porcentajeRentabilidad = $porcentajeRentabilidad;
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
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }

    /**
     * @param mixed $estadoCerrado
     */
    public function setEstadoCerrado($estadoCerrado): void
    {
        $this->estadoCerrado = $estadoCerrado;
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
    public function getEstadoContabilizado()
    {
        return $this->estadoContabilizado;
    }

    /**
     * @param mixed $estadoContabilizado
     */
    public function setEstadoContabilizado($estadoContabilizado): void
    {
        $this->estadoContabilizado = $estadoContabilizado;
    }

    /**
     * @return mixed
     */
    public function getEstadoCumplirRndc()
    {
        return $this->estadoCumplirRndc;
    }

    /**
     * @param mixed $estadoCumplirRndc
     */
    public function setEstadoCumplirRndc($estadoCumplirRndc): void
    {
        $this->estadoCumplirRndc = $estadoCumplirRndc;
    }

    /**
     * @return mixed
     */
    public function getEstadoNovedad()
    {
        return $this->estadoNovedad;
    }

    /**
     * @param mixed $estadoNovedad
     */
    public function setEstadoNovedad($estadoNovedad): void
    {
        $this->estadoNovedad = $estadoNovedad;
    }

    /**
     * @return mixed
     */
    public function getEstadoNovedadSolucion()
    {
        return $this->estadoNovedadSolucion;
    }

    /**
     * @param mixed $estadoNovedadSolucion
     */
    public function setEstadoNovedadSolucion($estadoNovedadSolucion): void
    {
        $this->estadoNovedadSolucion = $estadoNovedadSolucion;
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
    public function getCodigoDespachoTipoFk()
    {
        return $this->codigoDespachoTipoFk;
    }

    /**
     * @param mixed $codigoDespachoTipoFk
     */
    public function setCodigoDespachoTipoFk($codigoDespachoTipoFk): void
    {
        $this->codigoDespachoTipoFk = $codigoDespachoTipoFk;
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
    public function getDespachoTipoRel()
    {
        return $this->despachoTipoRel;
    }

    /**
     * @param mixed $despachoTipoRel
     */
    public function setDespachoTipoRel($despachoTipoRel): void
    {
        $this->despachoTipoRel = $despachoTipoRel;
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
    public function getOperacionRel()
    {
        return $this->operacionRel;
    }

    /**
     * @param mixed $operacionRel
     */
    public function setOperacionRel($operacionRel): void
    {
        $this->operacionRel = $operacionRel;
    }

    /**
     * @return mixed
     */
    public function getVehiculoRel()
    {
        return $this->vehiculoRel;
    }

    /**
     * @param mixed $vehiculoRel
     */
    public function setVehiculoRel($vehiculoRel): void
    {
        $this->vehiculoRel = $vehiculoRel;
    }

    /**
     * @return mixed
     */
    public function getConductorRel()
    {
        return $this->conductorRel;
    }

    /**
     * @param mixed $conductorRel
     */
    public function setConductorRel($conductorRel): void
    {
        $this->conductorRel = $conductorRel;
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
    public function getGuiasDespachoRel()
    {
        return $this->guiasDespachoRel;
    }

    /**
     * @param mixed $guiasDespachoRel
     */
    public function setGuiasDespachoRel($guiasDespachoRel): void
    {
        $this->guiasDespachoRel = $guiasDespachoRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosDetallesDespachoRel()
    {
        return $this->despachosDetallesDespachoRel;
    }

    /**
     * @param mixed $despachosDetallesDespachoRel
     */
    public function setDespachosDetallesDespachoRel($despachosDetallesDespachoRel): void
    {
        $this->despachosDetallesDespachoRel = $despachosDetallesDespachoRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosAdicionalesDespachoRel()
    {
        return $this->despachosAdicionalesDespachoRel;
    }

    /**
     * @param mixed $despachosAdicionalesDespachoRel
     */
    public function setDespachosAdicionalesDespachoRel($despachosAdicionalesDespachoRel): void
    {
        $this->despachosAdicionalesDespachoRel = $despachosAdicionalesDespachoRel;
    }

    /**
     * @return mixed
     */
    public function getMonitoreosDespachoRel()
    {
        return $this->monitoreosDespachoRel;
    }

    /**
     * @param mixed $monitoreosDespachoRel
     */
    public function setMonitoreosDespachoRel($monitoreosDespachoRel): void
    {
        $this->monitoreosDespachoRel = $monitoreosDespachoRel;
    }

    /**
     * @return mixed
     */
    public function getNovedadesDespachoRel()
    {
        return $this->novedadesDespachoRel;
    }

    /**
     * @param mixed $novedadesDespachoRel
     */
    public function setNovedadesDespachoRel($novedadesDespachoRel): void
    {
        $this->novedadesDespachoRel = $novedadesDespachoRel;
    }

    /**
     * @return mixed
     */
    public function getRedespachosDespachoRel()
    {
        return $this->redespachosDespachoRel;
    }

    /**
     * @param mixed $redespachosDespachoRel
     */
    public function setRedespachosDespachoRel($redespachosDespachoRel): void
    {
        $this->redespachosDespachoRel = $redespachosDespachoRel;
    }

    /**
     * @return mixed
     */
    public function getDesembarcosDespachoRel()
    {
        return $this->desembarcosDespachoRel;
    }

    /**
     * @param mixed $desembarcosDespachoRel
     */
    public function setDesembarcosDespachoRel($desembarcosDespachoRel): void
    {
        $this->desembarcosDespachoRel = $desembarcosDespachoRel;
    }

    /**
     * @return mixed
     */
    public function getDespachoAuxiliarRel()
    {
        return $this->despachoAuxiliarRel;
    }

    /**
     * @param mixed $despachoAuxiliarRel
     */
    public function setDespachoAuxiliarRel($despachoAuxiliarRel): void
    {
        $this->despachoAuxiliarRel = $despachoAuxiliarRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoPoseedorFk()
    {
        return $this->codigoPoseedorFk;
    }

    /**
     * @param mixed $codigoPoseedorFk
     */
    public function setCodigoPoseedorFk($codigoPoseedorFk): void
    {
        $this->codigoPoseedorFk = $codigoPoseedorFk;
    }

    /**
     * @return mixed
     */
    public function getPoseedorRel()
    {
        return $this->poseedorRel;
    }

    /**
     * @param mixed $poseedorRel
     */
    public function setPoseedorRel($poseedorRel): void
    {
        $this->poseedorRel = $poseedorRel;
    }

    /**
     * @return mixed
     */
    public function getVrCostoPago()
    {
        return $this->vrCostoPago;
    }

    /**
     * @param mixed $vrCostoPago
     */
    public function setVrCostoPago($vrCostoPago): void
    {
        $this->vrCostoPago = $vrCostoPago;
    }

    /**
     * @return mixed
     */
    public function getCodigoDespachoClaseFk()
    {
        return $this->codigoDespachoClaseFk;
    }

    /**
     * @param mixed $codigoDespachoClaseFk
     */
    public function setCodigoDespachoClaseFk($codigoDespachoClaseFk): void
    {
        $this->codigoDespachoClaseFk = $codigoDespachoClaseFk;
    }



}

