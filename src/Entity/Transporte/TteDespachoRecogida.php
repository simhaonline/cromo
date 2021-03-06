<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteDespachoRecogidaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteDespachoRecogida
{
    public $infoLog = [
        "primaryKey" => "codigoDespachoRecogidaPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoDespachoRecogidaPk;

    /**
     * @ORM\Column(name="codigo_despacho_recogida_tipo_fk", type="string", length=20, nullable=true)
     */
    private $codigoDespachoRecogidaTipoFk;

    /**
     * @ORM\Column(name="codigo_operacion_fk", type="string", length=20, nullable=true)
     */
    private $codigoOperacionFk;

    /**
     * @ORM\Column(name="codigo_ruta_recogida_fk", type="string", length=20, nullable=true)
     */
    private $codigoRutaRecogidaFk;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="string", length=20, nullable=true)
     */
    private $codigoCiudadnFk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="numero", type="float", nullable=true)
     */
    private $numero;

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
     * @ORM\Column(name="vr_retencion_ica", type="float", options={"default" : 0})
     */
    private $vrRetencionIca = 0;

    /**
     * @ORM\Column(name="vr_total", type="float", options={"default" : 0})
     */
    private $vrTotal = 0;

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
     * @ORM\Column(name="vr_declara", type="float")
     */
    private $vrDeclara = 0;

    /**
     * @ORM\Column(name="vr_saldo", type="float", options={"default" : 0})
     */
    private $vrSaldo = 0;

    /**
     * @ORM\Column(name="vr_costo_pago", type="float", options={"default" : 0})
     */
    private $vrCostoPago = 0;

    /**
     * @ORM\Column(name="estado_descargado", type="boolean", nullable=true, options={"default": 0})
     */
    private $estadoDescargado = false;

    /**
     * @ORM\Column(name="estado_monitoreo", type="boolean", nullable=true, options={"default": 0})
     */
    private $estadoMonitoreo = false;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean", nullable=true, options={"default": 0})
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean", nullable=true, options={"default": 0})
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean", nullable=true, options={"default": 0})
     */
    private $estadoAnulado= false;

    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoContabilizado = false;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\Column(name="usuario", type="string", length=25, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="TteCiudad", inversedBy="despachosRecogidasCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteDespachoRecogidaTipo", inversedBy="despachosRecogidasDespachoRecogidaTipoRel")
     * @ORM\JoinColumn(name="codigo_despacho_recogida_tipo_fk", referencedColumnName="codigo_despacho_recogida_tipo_pk")
     */
    private $despachoRecogidaTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteOperacion", inversedBy="despachosRecogidasOperacionRel")
     * @ORM\JoinColumn(name="codigo_operacion_fk", referencedColumnName="codigo_operacion_pk")
     */
    private $operacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteVehiculo", inversedBy="despachosRecogidasVehiculoRel")
     * @ORM\JoinColumn(name="codigo_vehiculo_fk", referencedColumnName="codigo_vehiculo_pk")
     */
    private $vehiculoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteRutaRecogida", inversedBy="despachosRecogidasRutaRecogidaRel")
     * @ORM\JoinColumn(name="codigo_ruta_recogida_fk", referencedColumnName="codigo_ruta_recogida_pk")
     */
    private $rutaRecogidaRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteConductor", inversedBy="despachosRecogidasConductorRel")
     * @ORM\JoinColumn(name="codigo_conductor_fk", referencedColumnName="codigo_conductor_pk")
     */
    private $conductorRel;

    /**
     * @ORM\ManyToOne(targetEntity="TtePoseedor", inversedBy="despachosRecogidasPoseedorRel")
     * @ORM\JoinColumn(name="codigo_poseedor_fk", referencedColumnName="codigo_poseedor_pk")
     */
    private $poseedorRel;

    /**
     * @ORM\OneToMany(targetEntity="TteRecogida", mappedBy="despachoRecogidaRel")
     */
    protected $recogidasDespachoRecogidaRel;

    /**
     * @ORM\OneToMany(targetEntity="TteDespachoRecogidaAuxiliar", mappedBy="despachoRecogidaRel")
     */
    protected $despachosRecogidasAuxiliaresDespachoRecogidaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteMonitoreo", mappedBy="despachoRecogidaRel")
     */
    protected $monitoreosDespachoRecogidaRel;

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
    public function getCodigoDespachoRecogidaPk()
    {
        return $this->codigoDespachoRecogidaPk;
    }

    /**
     * @param mixed $codigoDespachoRecogidaPk
     */
    public function setCodigoDespachoRecogidaPk($codigoDespachoRecogidaPk): void
    {
        $this->codigoDespachoRecogidaPk = $codigoDespachoRecogidaPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoDespachoRecogidaTipoFk()
    {
        return $this->codigoDespachoRecogidaTipoFk;
    }

    /**
     * @param mixed $codigoDespachoRecogidaTipoFk
     */
    public function setCodigoDespachoRecogidaTipoFk($codigoDespachoRecogidaTipoFk): void
    {
        $this->codigoDespachoRecogidaTipoFk = $codigoDespachoRecogidaTipoFk;
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
    public function getCodigoRutaRecogidaFk()
    {
        return $this->codigoRutaRecogidaFk;
    }

    /**
     * @param mixed $codigoRutaRecogidaFk
     */
    public function setCodigoRutaRecogidaFk($codigoRutaRecogidaFk): void
    {
        $this->codigoRutaRecogidaFk = $codigoRutaRecogidaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCiudadnFk()
    {
        return $this->codigoCiudadnFk;
    }

    /**
     * @param mixed $codigoCiudadnFk
     */
    public function setCodigoCiudadnFk($codigoCiudadnFk): void
    {
        $this->codigoCiudadnFk = $codigoCiudadnFk;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha): void
    {
        $this->fecha = $fecha;
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
    public function getEstadoDescargado()
    {
        return $this->estadoDescargado;
    }

    /**
     * @param mixed $estadoDescargado
     */
    public function setEstadoDescargado($estadoDescargado): void
    {
        $this->estadoDescargado = $estadoDescargado;
    }

    /**
     * @return mixed
     */
    public function getEstadoMonitoreo()
    {
        return $this->estadoMonitoreo;
    }

    /**
     * @param mixed $estadoMonitoreo
     */
    public function setEstadoMonitoreo($estadoMonitoreo): void
    {
        $this->estadoMonitoreo = $estadoMonitoreo;
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
    public function getCiudadRel()
    {
        return $this->ciudadRel;
    }

    /**
     * @param mixed $ciudadRel
     */
    public function setCiudadRel($ciudadRel): void
    {
        $this->ciudadRel = $ciudadRel;
    }

    /**
     * @return mixed
     */
    public function getDespachoRecogidaTipoRel()
    {
        return $this->despachoRecogidaTipoRel;
    }

    /**
     * @param mixed $despachoRecogidaTipoRel
     */
    public function setDespachoRecogidaTipoRel($despachoRecogidaTipoRel): void
    {
        $this->despachoRecogidaTipoRel = $despachoRecogidaTipoRel;
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
    public function getRutaRecogidaRel()
    {
        return $this->rutaRecogidaRel;
    }

    /**
     * @param mixed $rutaRecogidaRel
     */
    public function setRutaRecogidaRel($rutaRecogidaRel): void
    {
        $this->rutaRecogidaRel = $rutaRecogidaRel;
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
    public function getRecogidasDespachoRecogidaRel()
    {
        return $this->recogidasDespachoRecogidaRel;
    }

    /**
     * @param mixed $recogidasDespachoRecogidaRel
     */
    public function setRecogidasDespachoRecogidaRel($recogidasDespachoRecogidaRel): void
    {
        $this->recogidasDespachoRecogidaRel = $recogidasDespachoRecogidaRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosRecogidasAuxiliaresDespachoRecogidaRel()
    {
        return $this->despachosRecogidasAuxiliaresDespachoRecogidaRel;
    }

    /**
     * @param mixed $despachosRecogidasAuxiliaresDespachoRecogidaRel
     */
    public function setDespachosRecogidasAuxiliaresDespachoRecogidaRel($despachosRecogidasAuxiliaresDespachoRecogidaRel): void
    {
        $this->despachosRecogidasAuxiliaresDespachoRecogidaRel = $despachosRecogidasAuxiliaresDespachoRecogidaRel;
    }

    /**
     * @return mixed
     */
    public function getMonitoreosDespachoRecogidaRel()
    {
        return $this->monitoreosDespachoRecogidaRel;
    }

    /**
     * @param mixed $monitoreosDespachoRecogidaRel
     */
    public function setMonitoreosDespachoRecogidaRel($monitoreosDespachoRecogidaRel): void
    {
        $this->monitoreosDespachoRecogidaRel = $monitoreosDespachoRecogidaRel;
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



}

