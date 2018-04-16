<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteDespachoRepository")
 */
class TteDespacho
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
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
     * @ORM\Column(name="codigo_ruta_fk", type="string", length=20, nullable=true)
     */
    private $codigoRutaFk;

    /**
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="unidades", type="float")
     */
    private $unidades = 0;

    /**
     * @ORM\Column(name="peso_real", type="float")
     */
    private $pesoReal = 0;

    /**
     * @ORM\Column(name="peso_volumen", type="float")
     */
    private $pesoVolumen = 0;

    /**
     * @ORM\Column(name="vr_declara", type="float")
     */
    private $vrDeclara = 0;

    /**
     * @ORM\Column(name="vr_flete", type="float")
     */
    private $vrFlete = 0;

    /**
     * @ORM\Column(name="vr_manejo", type="float")
     */
    private $vrManejo = 0;

    /**
     * @ORM\Column(name="vr_recaudo", type="float")
     */
    private $vrRecaudo = 0;

    /**
     * @ORM\Column(name="vr_flete_pago", type="float")
     */
    private $vrFletePago = 0;

    /**
     * @ORM\Column(name="vr_anticipo", type="float")
     */
    private $vrAnticipo = 0;

    /**
     * @ORM\Column(name="vr_industria_comercio", type="float")
     */
    private $vrIndustriaComercio = 0;

    /**
     * @ORM\Column(name="vr_retencion_fuente", type="float")
     */
    private $vrRetencionFuente = 0;

    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="estado_generado", type="boolean", nullable=true)
     */
    private $estadoGenerado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean", nullable=true)
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\Column(name="codigo_despacho_tipo_fk", type="string", length=20, nullable=true)
     */
    private $codigoDespachoTipoFk;

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
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="despachoRel")
     */
    protected $guiasDespachoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteDespachoDetalle", mappedBy="despachoRel")
     */
    protected $despachosDetallesDespachoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteDespachoAdicional", mappedBy="despachoRel")
     */
    protected $despachosAdicionalesDespachoRel;


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
    public function getEstadoGenerado()
    {
        return $this->estadoGenerado;
    }

    /**
     * @param mixed $estadoGenerado
     */
    public function setEstadoGenerado($estadoGenerado): void
    {
        $this->estadoGenerado = $estadoGenerado;
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



}

