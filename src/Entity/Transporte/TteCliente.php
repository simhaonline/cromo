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
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenIdentificacion", inversedBy="tteClientesIdentificacionRel")
     * @ORM\JoinColumn(name="codigo_identificacion_fk", referencedColumnName="codigo_identificacion_pk")
     */
    private $identificacionRel;

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
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="clienteRel")
     */
    protected $guiasClienteRel;

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
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteIntermediacionDetalle", mappedBy="clienteRel")
     */
    protected $intermediacionesDetallesClienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenAsesor", inversedBy="asesorAsesorRel")
     * @ORM\JoinColumn(name="codigo_asesor_fk",referencedColumnName="codigo_asesor_pk")
     */
    protected $asesorRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuiaTemporal", mappedBy="clienteRel")
     */
    protected $guiasTemporalesClienteRel;

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
    public function getIntermediacionesDetallesClienteRel()
    {
        return $this->intermediacionesDetallesClienteRel;
    }

    /**
     * @param mixed $intermediacionesDetallesClienteRel
     */
    public function setIntermediacionesDetallesClienteRel($intermediacionesDetallesClienteRel): void
    {
        $this->intermediacionesDetallesClienteRel = $intermediacionesDetallesClienteRel;
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
}

