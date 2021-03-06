<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteClienteRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteCliente
{
    public $infoLog = [
        "primaryKey" => "codigoClientePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="codigo_cliente_pk")
     */
    private $codigoClientePk;


    /**
     * @ORM\Column(name="codigo_asesor_fk", type="integer", nullable=true)
     */
    private $codigoAsesorFk;

    /**
     * @ORM\Column(name="codigo_identificacion_fk", type="string", length=3, nullable=true)
     */
    private $codigoIdentificacionFk;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=true)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="digito_verificacion", type="string", length=1, nullable=true)
     */
    private $digitoVerificacion;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=150, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="nombre_extendido", type="string", length=500, nullable=true)
     */
    private $nombreExtendido;

    /**
     * @ORM\Column(name="nombre1", type="string", length=50, nullable=true)
     */
    private $nombre1;

    /**
     * @ORM\Column(name="nombre2", type="string", length=50, nullable=true)
     */
    private $nombre2;

    /**
     * @ORM\Column(name="apellido1", type="string", length=50, nullable=true)
     */
    private $apellido1;

    /**
     * @ORM\Column(name="apellido2", type="string", length=50, nullable=true)
     */
    private $apellido2;

    /**
     * @ORM\Column(name="direccion", type="string", length=200, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="barrio", type="string", length=200, nullable=true)
     */
    private $barrio;

    /**
     * @ORM\Column(name="codigo_postal", type="string", length=20, nullable=true)
     */
    private $codigoPostal;

    /**
     * @ORM\Column(name="telefono", type="string", length=30, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="movil", type="string", length=30, nullable=true)
     */
    private $movil;

    /**
     * @ORM\Column(name="plazo_pago", type="integer", options={"default" : 0})
     */
    private $plazoPago = 0;

    /**
     * @ORM\Column(name="correo", type="string", length=1000, nullable=true)
     */
    private $correo;

    /**
     * @ORM\Column(name="estado_inactivo", type="boolean", nullable=true,options={"default":false})
     */
    private $estadoInactivo = false;

    /**
     * @ORM\Column(name="codigo_condicion_fk", type="integer", nullable=true)
     */
    private $codigoCondicionFk;

    /**
     * @ORM\Column(name="codigo_forma_pago_fk", type="string", length=10, nullable=true)
     */
    private $codigoFormaPagoFk;

    /**
     * @ORM\Column(name="retencion_fuente_sin_base", type="boolean",options={"default" : false}, nullable=true)
     */
    private $retencionFuenteSinBase = false;

    /**
     * @ORM\Column(name="guia_pago_contado", type="boolean", nullable=true,options={"default":false})
     */
    private $guiaPagoContado = false;

    /**
     * @ORM\Column(name="guia_pago_destino", type="boolean", nullable=true,options={"default":false})
     */
    private $guiaPagoDestino = false;

    /**
     * @ORM\Column(name="guia_pago_credito", type="boolean", nullable=true,options={"default":false})
     */
    private $guiaPagoCredito = false;

    /**
     * @ORM\Column(name="guia_pago_cortesia", type="boolean", nullable=true,options={"default":false})
     */
    private $guiaPagoCortesia = false;

    /**
     * @ORM\Column(name="guia_pago_recogida", type="boolean", nullable=true,options={"default":false})
     */
    private $guiaPagoRecogida = false;

    /**
     * @ORM\Column(name="requiere_estado_soporte_factura", type="boolean", nullable=true,options={"default":false})
     */
    private $requiereEstadoSoporteFactura = false;

    /**
     * @ORM\Column(name="codigo_operacion_fk", type="string", length=20, nullable=true)
     */
    private $codigoOperacionFk;

    /**
     * @ORM\Column(name="codigo_tipo_persona_fk", type="string", length=3, nullable=true)
     */
    private $codigoTipoPersonaFk;

    /**
     * @ORM\Column(name="codigo_regimen_fk", type="string", length=3, nullable=true)
     */
    private $codigoRegimenFk;

    /**
     * @ORM\Column(name="codigo_responsabilidad_fiscal_fk", type="string", length=10, nullable=true)
     */
    private $codigoResponsabilidadFiscalFk;

    /**
     * @ORM\Column(name="codigo_ciuu", type="string", length=20, nullable=true)
     */
    private $codigoCIUU;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\Column(name="factura_agrupada_destino", type="boolean", nullable=true,options={"default":false})
     */
    private $facturaAgrupadaDestino = false;

    /**
     * @ORM\Column(name="ordenar_impresion_alfabeticamente_destino", type="boolean", nullable=true,options={"default":false})
     */
    private $ordenarImpresionAlfabeticamenteDestino = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenIdentificacion", inversedBy="tteClientesIdentificacionRel")
     * @ORM\JoinColumn(name="codigo_identificacion_fk", referencedColumnName="codigo_identificacion_pk")
     */
    private $identificacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenTipoPersona", inversedBy="tteClientesTipoPersonaRel")
     * @ORM\JoinColumn(name="codigo_tipo_persona_fk", referencedColumnName="codigo_tipo_persona_pk")
     */
    private $tipoPersonaRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenRegimen", inversedBy="tteClientesRegimenRel")
     * @ORM\JoinColumn(name="codigo_regimen_fk", referencedColumnName="codigo_regimen_pk")
     */
    private $regimenRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenResponsabilidadFiscal", inversedBy="tteClientesResponsabilidadFiscalRel")
     * @ORM\JoinColumn(name="codigo_responsabilidad_fiscal_fk", referencedColumnName="codigo_responsabilidad_fiscal_pk")
     */
    private $responsabilidadFiscalRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCondicion", inversedBy="clientesCondicionRel")
     * @ORM\JoinColumn(name="codigo_condicion_fk", referencedColumnName="codigo_condicion_pk")
     */
    private $condicionRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenFormaPago", inversedBy="tteClientesFormaPagoRel")
     * @ORM\JoinColumn(name="codigo_forma_pago_fk", referencedColumnName="codigo_forma_pago_pk")
     */
    private $formaPagoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCiudad", inversedBy="tteClientesCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteOperacion", inversedBy="clientesOperacionRel")
     * @ORM\JoinColumn(name="codigo_operacion_fk", referencedColumnName="codigo_operacion_pk")
     */
    private $operacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenAsesor", inversedBy="asesorAsesorRel")
     * @ORM\JoinColumn(name="codigo_asesor_fk",referencedColumnName="codigo_asesor_pk")
     */
    protected $asesorRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="clienteRel")
     */
    protected $guiasClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuiaCliente", mappedBy="clienteRel")
     */
    protected $guiasClientesClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="TteRecogida", mappedBy="clienteRel")
     */
    protected $recogidasClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="TteRecogidaProgramada", mappedBy="clienteRel")
     */
    protected $recogidasProgramadasClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="TteCumplido", mappedBy="clienteRel")
     */
    protected $cumplidosClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="TteRecaudoDevolucion", mappedBy="clienteRel")
     */
    protected $recaudosClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="TteFactura", mappedBy="clienteRel")
     */
    protected $facturasClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="TteRecibo", mappedBy="clienteRel")
     */
    protected $recibosClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="TteClienteCondicion", mappedBy="clienteRel")
     */
    protected $clientesCondicionesClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteIntermediacionVenta", mappedBy="clienteRel")
     */
    protected $intermediacionesVentasClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuiaTemporal", mappedBy="clienteRel")
     */
    protected $guiasTemporalesClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="TteCondicionFlete", mappedBy="clienteRel")
     */
    protected $condicionesFletesClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="TteCondicionManejo", mappedBy="clienteRel")
     */
    protected $condicionesManejosClienteRel;

    /**
     * @return mixed
     */
    public function getCodigoClientePk()
    {
        return $this->codigoClientePk;
    }

    /**
     * @param mixed $codigoClientePk
     */
    public function setCodigoClientePk($codigoClientePk): void
    {
        $this->codigoClientePk = $codigoClientePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoAsesorFk()
    {
        return $this->codigoAsesorFk;
    }

    /**
     * @param mixed $codigoAsesorFk
     */
    public function setCodigoAsesorFk($codigoAsesorFk): void
    {
        $this->codigoAsesorFk = $codigoAsesorFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoIdentificacionFk()
    {
        return $this->codigoIdentificacionFk;
    }

    /**
     * @param mixed $codigoIdentificacionFk
     */
    public function setCodigoIdentificacionFk($codigoIdentificacionFk): void
    {
        $this->codigoIdentificacionFk = $codigoIdentificacionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCiudadFk()
    {
        return $this->codigoCiudadFk;
    }

    /**
     * @param mixed $codigoCiudadFk
     */
    public function setCodigoCiudadFk($codigoCiudadFk): void
    {
        $this->codigoCiudadFk = $codigoCiudadFk;
    }

    /**
     * @return mixed
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * @param mixed $numeroIdentificacion
     */
    public function setNumeroIdentificacion($numeroIdentificacion): void
    {
        $this->numeroIdentificacion = $numeroIdentificacion;
    }

    /**
     * @return mixed
     */
    public function getDigitoVerificacion()
    {
        return $this->digitoVerificacion;
    }

    /**
     * @param mixed $digitoVerificacion
     */
    public function setDigitoVerificacion($digitoVerificacion): void
    {
        $this->digitoVerificacion = $digitoVerificacion;
    }

    /**
     * @return mixed
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * @param mixed $nombreCorto
     */
    public function setNombreCorto($nombreCorto): void
    {
        $this->nombreCorto = $nombreCorto;
    }

    /**
     * @return mixed
     */
    public function getNombreExtendido()
    {
        return $this->nombreExtendido;
    }

    /**
     * @param mixed $nombreExtendido
     */
    public function setNombreExtendido($nombreExtendido): void
    {
        $this->nombreExtendido = $nombreExtendido;
    }

    /**
     * @return mixed
     */
    public function getNombre1()
    {
        return $this->nombre1;
    }

    /**
     * @param mixed $nombre1
     */
    public function setNombre1($nombre1): void
    {
        $this->nombre1 = $nombre1;
    }

    /**
     * @return mixed
     */
    public function getNombre2()
    {
        return $this->nombre2;
    }

    /**
     * @param mixed $nombre2
     */
    public function setNombre2($nombre2): void
    {
        $this->nombre2 = $nombre2;
    }

    /**
     * @return mixed
     */
    public function getApellido1()
    {
        return $this->apellido1;
    }

    /**
     * @param mixed $apellido1
     */
    public function setApellido1($apellido1): void
    {
        $this->apellido1 = $apellido1;
    }

    /**
     * @return mixed
     */
    public function getApellido2()
    {
        return $this->apellido2;
    }

    /**
     * @param mixed $apellido2
     */
    public function setApellido2($apellido2): void
    {
        $this->apellido2 = $apellido2;
    }

    /**
     * @return mixed
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param mixed $direccion
     */
    public function setDireccion($direccion): void
    {
        $this->direccion = $direccion;
    }

    /**
     * @return mixed
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param mixed $telefono
     */
    public function setTelefono($telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * @return mixed
     */
    public function getMovil()
    {
        return $this->movil;
    }

    /**
     * @param mixed $movil
     */
    public function setMovil($movil): void
    {
        $this->movil = $movil;
    }

    /**
     * @return mixed
     */
    public function getPlazoPago()
    {
        return $this->plazoPago;
    }

    /**
     * @param mixed $plazoPago
     */
    public function setPlazoPago($plazoPago): void
    {
        $this->plazoPago = $plazoPago;
    }

    /**
     * @return mixed
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * @param mixed $correo
     */
    public function setCorreo($correo): void
    {
        $this->correo = $correo;
    }

    /**
     * @return mixed
     */
    public function getEstadoInactivo()
    {
        return $this->estadoInactivo;
    }

    /**
     * @param mixed $estadoInactivo
     */
    public function setEstadoInactivo($estadoInactivo): void
    {
        $this->estadoInactivo = $estadoInactivo;
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
    public function getCodigoFormaPagoFk()
    {
        return $this->codigoFormaPagoFk;
    }

    /**
     * @param mixed $codigoFormaPagoFk
     */
    public function setCodigoFormaPagoFk($codigoFormaPagoFk): void
    {
        $this->codigoFormaPagoFk = $codigoFormaPagoFk;
    }

    /**
     * @return mixed
     */
    public function getRetencionFuenteSinBase()
    {
        return $this->retencionFuenteSinBase;
    }

    /**
     * @param mixed $retencionFuenteSinBase
     */
    public function setRetencionFuenteSinBase($retencionFuenteSinBase): void
    {
        $this->retencionFuenteSinBase = $retencionFuenteSinBase;
    }

    /**
     * @return mixed
     */
    public function getGuiaPagoContado()
    {
        return $this->guiaPagoContado;
    }

    /**
     * @param mixed $guiaPagoContado
     */
    public function setGuiaPagoContado($guiaPagoContado): void
    {
        $this->guiaPagoContado = $guiaPagoContado;
    }

    /**
     * @return mixed
     */
    public function getGuiaPagoDestino()
    {
        return $this->guiaPagoDestino;
    }

    /**
     * @param mixed $guiaPagoDestino
     */
    public function setGuiaPagoDestino($guiaPagoDestino): void
    {
        $this->guiaPagoDestino = $guiaPagoDestino;
    }

    /**
     * @return mixed
     */
    public function getGuiaPagoCredito()
    {
        return $this->guiaPagoCredito;
    }

    /**
     * @param mixed $guiaPagoCredito
     */
    public function setGuiaPagoCredito($guiaPagoCredito): void
    {
        $this->guiaPagoCredito = $guiaPagoCredito;
    }

    /**
     * @return mixed
     */
    public function getGuiaPagoCortesia()
    {
        return $this->guiaPagoCortesia;
    }

    /**
     * @param mixed $guiaPagoCortesia
     */
    public function setGuiaPagoCortesia($guiaPagoCortesia): void
    {
        $this->guiaPagoCortesia = $guiaPagoCortesia;
    }

    /**
     * @return mixed
     */
    public function getGuiaPagoRecogida()
    {
        return $this->guiaPagoRecogida;
    }

    /**
     * @param mixed $guiaPagoRecogida
     */
    public function setGuiaPagoRecogida($guiaPagoRecogida): void
    {
        $this->guiaPagoRecogida = $guiaPagoRecogida;
    }

    /**
     * @return mixed
     */
    public function getRequiereEstadoSoporteFactura()
    {
        return $this->requiereEstadoSoporteFactura;
    }

    /**
     * @param mixed $requiereEstadoSoporteFactura
     */
    public function setRequiereEstadoSoporteFactura($requiereEstadoSoporteFactura): void
    {
        $this->requiereEstadoSoporteFactura = $requiereEstadoSoporteFactura;
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
    public function getFacturaAgrupadaDestino()
    {
        return $this->facturaAgrupadaDestino;
    }

    /**
     * @param mixed $facturaAgrupadaDestino
     */
    public function setFacturaAgrupadaDestino($facturaAgrupadaDestino): void
    {
        $this->facturaAgrupadaDestino = $facturaAgrupadaDestino;
    }

    /**
     * @return mixed
     */
    public function getIdentificacionRel()
    {
        return $this->identificacionRel;
    }

    /**
     * @param mixed $identificacionRel
     */
    public function setIdentificacionRel($identificacionRel): void
    {
        $this->identificacionRel = $identificacionRel;
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
    public function getFormaPagoRel()
    {
        return $this->formaPagoRel;
    }

    /**
     * @param mixed $formaPagoRel
     */
    public function setFormaPagoRel($formaPagoRel): void
    {
        $this->formaPagoRel = $formaPagoRel;
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
    public function getAsesorRel()
    {
        return $this->asesorRel;
    }

    /**
     * @param mixed $asesorRel
     */
    public function setAsesorRel($asesorRel): void
    {
        $this->asesorRel = $asesorRel;
    }

    /**
     * @return mixed
     */
    public function getGuiasClienteRel()
    {
        return $this->guiasClienteRel;
    }

    /**
     * @param mixed $guiasClienteRel
     */
    public function setGuiasClienteRel($guiasClienteRel): void
    {
        $this->guiasClienteRel = $guiasClienteRel;
    }

    /**
     * @return mixed
     */
    public function getGuiasClientesClienteRel()
    {
        return $this->guiasClientesClienteRel;
    }

    /**
     * @param mixed $guiasClientesClienteRel
     */
    public function setGuiasClientesClienteRel($guiasClientesClienteRel): void
    {
        $this->guiasClientesClienteRel = $guiasClientesClienteRel;
    }

    /**
     * @return mixed
     */
    public function getRecogidasClienteRel()
    {
        return $this->recogidasClienteRel;
    }

    /**
     * @param mixed $recogidasClienteRel
     */
    public function setRecogidasClienteRel($recogidasClienteRel): void
    {
        $this->recogidasClienteRel = $recogidasClienteRel;
    }

    /**
     * @return mixed
     */
    public function getRecogidasProgramadasClienteRel()
    {
        return $this->recogidasProgramadasClienteRel;
    }

    /**
     * @param mixed $recogidasProgramadasClienteRel
     */
    public function setRecogidasProgramadasClienteRel($recogidasProgramadasClienteRel): void
    {
        $this->recogidasProgramadasClienteRel = $recogidasProgramadasClienteRel;
    }

    /**
     * @return mixed
     */
    public function getCumplidosClienteRel()
    {
        return $this->cumplidosClienteRel;
    }

    /**
     * @param mixed $cumplidosClienteRel
     */
    public function setCumplidosClienteRel($cumplidosClienteRel): void
    {
        $this->cumplidosClienteRel = $cumplidosClienteRel;
    }

    /**
     * @return mixed
     */
    public function getRecaudosClienteRel()
    {
        return $this->recaudosClienteRel;
    }

    /**
     * @param mixed $recaudosClienteRel
     */
    public function setRecaudosClienteRel($recaudosClienteRel): void
    {
        $this->recaudosClienteRel = $recaudosClienteRel;
    }

    /**
     * @return mixed
     */
    public function getFacturasClienteRel()
    {
        return $this->facturasClienteRel;
    }

    /**
     * @param mixed $facturasClienteRel
     */
    public function setFacturasClienteRel($facturasClienteRel): void
    {
        $this->facturasClienteRel = $facturasClienteRel;
    }

    /**
     * @return mixed
     */
    public function getRecibosClienteRel()
    {
        return $this->recibosClienteRel;
    }

    /**
     * @param mixed $recibosClienteRel
     */
    public function setRecibosClienteRel($recibosClienteRel): void
    {
        $this->recibosClienteRel = $recibosClienteRel;
    }

    /**
     * @return mixed
     */
    public function getClientesCondicionesClienteRel()
    {
        return $this->clientesCondicionesClienteRel;
    }

    /**
     * @param mixed $clientesCondicionesClienteRel
     */
    public function setClientesCondicionesClienteRel($clientesCondicionesClienteRel): void
    {
        $this->clientesCondicionesClienteRel = $clientesCondicionesClienteRel;
    }

    /**
     * @return mixed
     */
    public function getIntermediacionesVentasClienteRel()
    {
        return $this->intermediacionesVentasClienteRel;
    }

    /**
     * @param mixed $intermediacionesVentasClienteRel
     */
    public function setIntermediacionesVentasClienteRel($intermediacionesVentasClienteRel): void
    {
        $this->intermediacionesVentasClienteRel = $intermediacionesVentasClienteRel;
    }

    /**
     * @return mixed
     */
    public function getGuiasTemporalesClienteRel()
    {
        return $this->guiasTemporalesClienteRel;
    }

    /**
     * @param mixed $guiasTemporalesClienteRel
     */
    public function setGuiasTemporalesClienteRel($guiasTemporalesClienteRel): void
    {
        $this->guiasTemporalesClienteRel = $guiasTemporalesClienteRel;
    }

    /**
     * @return mixed
     */
    public function getCondicionesFletesClienteRel()
    {
        return $this->condicionesFletesClienteRel;
    }

    /**
     * @param mixed $condicionesFletesClienteRel
     */
    public function setCondicionesFletesClienteRel($condicionesFletesClienteRel): void
    {
        $this->condicionesFletesClienteRel = $condicionesFletesClienteRel;
    }

    /**
     * @return mixed
     */
    public function getCondicionesManejosClienteRel()
    {
        return $this->condicionesManejosClienteRel;
    }

    /**
     * @param mixed $condicionesManejosClienteRel
     */
    public function setCondicionesManejosClienteRel($condicionesManejosClienteRel): void
    {
        $this->condicionesManejosClienteRel = $condicionesManejosClienteRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoTipoPersonaFk()
    {
        return $this->codigoTipoPersonaFk;
    }

    /**
     * @param mixed $codigoTipoPersonaFk
     */
    public function setCodigoTipoPersonaFk($codigoTipoPersonaFk): void
    {
        $this->codigoTipoPersonaFk = $codigoTipoPersonaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoRegimenFk()
    {
        return $this->codigoRegimenFk;
    }

    /**
     * @param mixed $codigoRegimenFk
     */
    public function setCodigoRegimenFk($codigoRegimenFk): void
    {
        $this->codigoRegimenFk = $codigoRegimenFk;
    }

    /**
     * @return mixed
     */
    public function getTipoPersonaRel()
    {
        return $this->tipoPersonaRel;
    }

    /**
     * @param mixed $tipoPersonaRel
     */
    public function setTipoPersonaRel($tipoPersonaRel): void
    {
        $this->tipoPersonaRel = $tipoPersonaRel;
    }

    /**
     * @return mixed
     */
    public function getRegimenRel()
    {
        return $this->regimenRel;
    }

    /**
     * @param mixed $regimenRel
     */
    public function setRegimenRel($regimenRel): void
    {
        $this->regimenRel = $regimenRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoResponsabilidadFiscalFk()
    {
        return $this->codigoResponsabilidadFiscalFk;
    }

    /**
     * @param mixed $codigoResponsabilidadFiscalFk
     */
    public function setCodigoResponsabilidadFiscalFk($codigoResponsabilidadFiscalFk): void
    {
        $this->codigoResponsabilidadFiscalFk = $codigoResponsabilidadFiscalFk;
    }

    /**
     * @return mixed
     */
    public function getResponsabilidadFiscalRel()
    {
        return $this->responsabilidadFiscalRel;
    }

    /**
     * @param mixed $responsabilidadFiscalRel
     */
    public function setResponsabilidadFiscalRel($responsabilidadFiscalRel): void
    {
        $this->responsabilidadFiscalRel = $responsabilidadFiscalRel;
    }

    /**
     * @return mixed
     */
    public function getBarrio()
    {
        return $this->barrio;
    }

    /**
     * @param mixed $barrio
     */
    public function setBarrio($barrio): void
    {
        $this->barrio = $barrio;
    }

    /**
     * @return mixed
     */
    public function getCodigoPostal()
    {
        return $this->codigoPostal;
    }

    /**
     * @param mixed $codigoPostal
     */
    public function setCodigoPostal($codigoPostal): void
    {
        $this->codigoPostal = $codigoPostal;
    }

    /**
     * @return mixed
     */
    public function getCodigoCIUU()
    {
        return $this->codigoCIUU;
    }

    /**
     * @param mixed $codigoCIUU
     */
    public function setCodigoCIUU($codigoCIUU): void
    {
        $this->codigoCIUU = $codigoCIUU;
    }

    /**
     * @return mixed
     */
    public function getOrdenarImpresionAlfabeticamenteDestino()
    {
        return $this->ordenarImpresionAlfabeticamenteDestino;
    }

    /**
     * @param mixed $ordenarImpresionAlfabeticamenteDestino
     */
    public function setOrdenarImpresionAlfabeticamenteDestino($ordenarImpresionAlfabeticamenteDestino): void
    {
        $this->ordenarImpresionAlfabeticamenteDestino = $ordenarImpresionAlfabeticamenteDestino;
    }



}

