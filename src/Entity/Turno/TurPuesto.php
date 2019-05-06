<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurPuestoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurPuesto
{
    public $infoLog = [
        "primaryKey" => "codigoPuestoPk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_puesto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPuestoPk;

    /**
     * @ORM\Column(name="codigo_puesto_tipo_fk", type="integer", nullable=true)
     */
    private $codigoPuestoTipoFk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="nombre", type="string", length=300)
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     * @Assert\Length(
     *     max = 300,
     *     maxMessage="El campo no puede contener mas de 300 caracteres"
     * )
     */
    private $nombre;


    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     * @Assert\Length(
     *     max = 80,
     *     maxMessage="El campo no puede contener mas de 80 caracteres"
     * )
     */
    private $direccion;

    /**
     * @ORM\Column(name="telefono", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max = 30,
     *     maxMessage="El campo no puede contener mas de 30 caracteres"
     * )
     */
    private $telefono;

    /**
     * @ORM\Column(name="celular", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max = 30,
     *     maxMessage="El campo no puede contener mas de 30 caracteres"
     * )
     */
    private $celular;

    /**
     * @ORM\Column(name="otra_comunicacion", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max = 30,
     *     maxMessage="El campo no puede contener mas de 30 caracteres"
     * )
     */
    private $otraComunicacion;

    /**
     * @ORM\Column(name="contacto_principal", type="string", length=90, nullable=true)
     * @Assert\Length(
     *     max = 90,
     *     maxMessage="El campo no puede contener mas de 90 caracteres"
     * )
     */
    private $contactoPrincipal;

    /**
     * @ORM\Column(name="telefono_contacto", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max = 30,
     *     maxMessage="El campo no puede contener mas de 30 caracteres"
     * )
     */
    private $telefonoContacto;

    /**
     * @ORM\Column(name="celular_contacto", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max = 30,
     *     maxMessage="El campo no puede contener mas de 30 caracteres"
     * )
     */
    private $celularContacto;

    /**
     * @ORM\Column(name="codigo_programador_fk", type="integer", nullable=true)
     */
    private $codigoProgramadorFk;

    /**
     * @ORM\Column(name="codigo_zona_fk", type="integer", nullable=true)
     */
    private $codigoZonaFk;

    /**
     * @ORM\Column(name="codigo_supervisor_fk", type="string", length=20, nullable=true)
     */
    private $codigoSupervisorFk;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="comentario", type="text", nullable=true)
     */
    private $comentario;

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCentroCostoFk;

    /**
     * @ORM\Column(name="codigo_sucursal_fk", type="string", length=20, nullable=true)
     */
    private $codigoSucursalFk;

    /**
     * @ORM\Column(name="codigo_area_fk", type="string", length=20, nullable=true)
     */
    private $codigoAreaFk;

    /**
     * @ORM\Column(name="codigo_proyecto_fk", type="string", length=20, nullable=true)
     */
    private $codigoProyectoFk;

    /**
     * @ORM\Column(name="control_puesto", type="boolean")
     */
    private $controlPuesto = false;

    /**
     * @ORM\Column(name="edad_minima", type="integer", nullable=true)
     */
    private $edad_minima;

    /**
     * @ORM\Column(name="edad_maxima", type="integer", nullable=true)
     */
    private $edad_maxima;

    /**
     * @ORM\Column(name="estatura_minima", type="integer", nullable=true)
     */
    private $estaturaMinima;

    /**
     * @ORM\Column(name="estatura_maxima", type="integer", nullable=true)
     */
    private $estaturaMaxima;

    /**
     * @ORM\Column(name="peso_minimo", type="integer", nullable=true)
     */
    private $peso_minimo;

    /**
     * @ORM\Column(name="peso_maximo", type="integer", nullable=true)
     */
    private $peso_maximo;

    /**
     * @ORM\Column(name="codigo_sexo_fk", type="string", length=1, nullable=true)
     */
    private $codigoSexoFk;

    /**
     * @ORM\Column(name="codigo_estado_civil_fk", type="string", length=1, nullable=true)
     */
    private $codigoEstadoCivilFk;

    /**
     * @ORM\Column(name="moto", type="boolean", nullable=true)
     */
    private $moto = false;

    /**
     * @ORM\Column(name="validar_perfil", type="boolean", nullable=true)
     */
    private $validarPerfil = false;

    /**
     * @ORM\Column(name="carro", type="boolean", nullable=true)
     */
    private $carro = false;

    /**
     * @ORM\Column(name="visita_domiciliaria", type="boolean", nullable=true)
     */
    private $visitaDomiciliaria = false;

    /**
     * @ORM\Column(name="poligrafia", type="boolean", nullable=true)
     */
    private $poligrafia = false;

    /**
     * @ORM\Column(name="hora_inicio", type="time", nullable=true)
     */
    private $horaInicio;

    /**
     * @ORM\Column(name="hora_final", type="time", nullable=true)
     */
    private $horaFinal;

    /**
     * @ORM\Column(name="ubicacion_gps", type="string", length=100, nullable=true)
     */
    private $ubicacionGps;

    /**
     * @ORM\Column(name="estado_inactivo", type="boolean", nullable=true, options={"default":false})
     */
    private $estadoInactivo = false;

    /**
     * @ORM\Column(name="codigo_coordinador_fk", type="string", length=20, nullable=true)
     */
    private $codigoCoordinadorFk;

//    /**
//     * @ORM\OneToMany(targetEntity="TurProgramacion", mappedBy="puestoRel")
//     */
//    protected $programacionesPuestoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Turno\TurCliente", inversedBy="PuestosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCiudad", inversedBy="turClientePuestoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Turno\TurProgramador", inversedBy="turProgramadorPuestosRel")
     * @ORM\JoinColumn(name="codigo_programador_fk", referencedColumnName="codigo_programador_pk")
     */
    protected $programadorRel;

    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="puestoRel")
     */
    protected $pedidosDetallesPuestoRel;

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
    public function getCodigoPuestoPk()
    {
        return $this->codigoPuestoPk;
    }

    /**
     * @param mixed $codigoPuestoPk
     */
    public function setCodigoPuestoPk($codigoPuestoPk): void
    {
        $this->codigoPuestoPk = $codigoPuestoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPuestoTipoFk()
    {
        return $this->codigoPuestoTipoFk;
    }

    /**
     * @param mixed $codigoPuestoTipoFk
     */
    public function setCodigoPuestoTipoFk($codigoPuestoTipoFk): void
    {
        $this->codigoPuestoTipoFk = $codigoPuestoTipoFk;
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
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * @param mixed $celular
     */
    public function setCelular($celular): void
    {
        $this->celular = $celular;
    }

    /**
     * @return mixed
     */
    public function getOtraComunicacion()
    {
        return $this->otraComunicacion;
    }

    /**
     * @param mixed $otraComunicacion
     */
    public function setOtraComunicacion($otraComunicacion): void
    {
        $this->otraComunicacion = $otraComunicacion;
    }

    /**
     * @return mixed
     */
    public function getContactoPrincipal()
    {
        return $this->contactoPrincipal;
    }

    /**
     * @param mixed $contactoPrincipal
     */
    public function setContactoPrincipal($contactoPrincipal): void
    {
        $this->contactoPrincipal = $contactoPrincipal;
    }

    /**
     * @return mixed
     */
    public function getTelefonoContacto()
    {
        return $this->telefonoContacto;
    }

    /**
     * @param mixed $telefonoContacto
     */
    public function setTelefonoContacto($telefonoContacto): void
    {
        $this->telefonoContacto = $telefonoContacto;
    }

    /**
     * @return mixed
     */
    public function getCelularContacto()
    {
        return $this->celularContacto;
    }

    /**
     * @param mixed $celularContacto
     */
    public function setCelularContacto($celularContacto): void
    {
        $this->celularContacto = $celularContacto;
    }

    /**
     * @return mixed
     */
    public function getCodigoProgramadorFk()
    {
        return $this->codigoProgramadorFk;
    }

    /**
     * @param mixed $codigoProgramadorFk
     */
    public function setCodigoProgramadorFk($codigoProgramadorFk): void
    {
        $this->codigoProgramadorFk = $codigoProgramadorFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoZonaFk()
    {
        return $this->codigoZonaFk;
    }

    /**
     * @param mixed $codigoZonaFk
     */
    public function setCodigoZonaFk($codigoZonaFk): void
    {
        $this->codigoZonaFk = $codigoZonaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSupervisorFk()
    {
        return $this->codigoSupervisorFk;
    }

    /**
     * @param mixed $codigoSupervisorFk
     */
    public function setCodigoSupervisorFk($codigoSupervisorFk): void
    {
        $this->codigoSupervisorFk = $codigoSupervisorFk;
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
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * @param mixed $codigoCentroCostoFk
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk): void
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSucursalFk()
    {
        return $this->codigoSucursalFk;
    }

    /**
     * @param mixed $codigoSucursalFk
     */
    public function setCodigoSucursalFk($codigoSucursalFk): void
    {
        $this->codigoSucursalFk = $codigoSucursalFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoAreaFk()
    {
        return $this->codigoAreaFk;
    }

    /**
     * @param mixed $codigoAreaFk
     */
    public function setCodigoAreaFk($codigoAreaFk): void
    {
        $this->codigoAreaFk = $codigoAreaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoProyectoFk()
    {
        return $this->codigoProyectoFk;
    }

    /**
     * @param mixed $codigoProyectoFk
     */
    public function setCodigoProyectoFk($codigoProyectoFk): void
    {
        $this->codigoProyectoFk = $codigoProyectoFk;
    }

    /**
     * @return mixed
     */
    public function getControlPuesto()
    {
        return $this->controlPuesto;
    }

    /**
     * @param mixed $controlPuesto
     */
    public function setControlPuesto($controlPuesto): void
    {
        $this->controlPuesto = $controlPuesto;
    }

    /**
     * @return mixed
     */
    public function getEdadMinima()
    {
        return $this->edad_minima;
    }

    /**
     * @param mixed $edad_minima
     */
    public function setEdadMinima($edad_minima): void
    {
        $this->edad_minima = $edad_minima;
    }

    /**
     * @return mixed
     */
    public function getEdadMaxima()
    {
        return $this->edad_maxima;
    }

    /**
     * @param mixed $edad_maxima
     */
    public function setEdadMaxima($edad_maxima): void
    {
        $this->edad_maxima = $edad_maxima;
    }

    /**
     * @return mixed
     */
    public function getEstaturaMinima()
    {
        return $this->estaturaMinima;
    }

    /**
     * @param mixed $estaturaMinima
     */
    public function setEstaturaMinima($estaturaMinima): void
    {
        $this->estaturaMinima = $estaturaMinima;
    }

    /**
     * @return mixed
     */
    public function getEstaturaMaxima()
    {
        return $this->estaturaMaxima;
    }

    /**
     * @param mixed $estaturaMaxima
     */
    public function setEstaturaMaxima($estaturaMaxima): void
    {
        $this->estaturaMaxima = $estaturaMaxima;
    }

    /**
     * @return mixed
     */
    public function getPesoMinimo()
    {
        return $this->peso_minimo;
    }

    /**
     * @param mixed $peso_minimo
     */
    public function setPesoMinimo($peso_minimo): void
    {
        $this->peso_minimo = $peso_minimo;
    }

    /**
     * @return mixed
     */
    public function getPesoMaximo()
    {
        return $this->peso_maximo;
    }

    /**
     * @param mixed $peso_maximo
     */
    public function setPesoMaximo($peso_maximo): void
    {
        $this->peso_maximo = $peso_maximo;
    }

    /**
     * @return mixed
     */
    public function getCodigoSexoFk()
    {
        return $this->codigoSexoFk;
    }

    /**
     * @param mixed $codigoSexoFk
     */
    public function setCodigoSexoFk($codigoSexoFk): void
    {
        $this->codigoSexoFk = $codigoSexoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEstadoCivilFk()
    {
        return $this->codigoEstadoCivilFk;
    }

    /**
     * @param mixed $codigoEstadoCivilFk
     */
    public function setCodigoEstadoCivilFk($codigoEstadoCivilFk): void
    {
        $this->codigoEstadoCivilFk = $codigoEstadoCivilFk;
    }

    /**
     * @return mixed
     */
    public function getMoto()
    {
        return $this->moto;
    }

    /**
     * @param mixed $moto
     */
    public function setMoto($moto): void
    {
        $this->moto = $moto;
    }

    /**
     * @return mixed
     */
    public function getValidarPerfil()
    {
        return $this->validarPerfil;
    }

    /**
     * @param mixed $validarPerfil
     */
    public function setValidarPerfil($validarPerfil): void
    {
        $this->validarPerfil = $validarPerfil;
    }

    /**
     * @return mixed
     */
    public function getCarro()
    {
        return $this->carro;
    }

    /**
     * @param mixed $carro
     */
    public function setCarro($carro): void
    {
        $this->carro = $carro;
    }

    /**
     * @return mixed
     */
    public function getVisitaDomiciliaria()
    {
        return $this->visitaDomiciliaria;
    }

    /**
     * @param mixed $visitaDomiciliaria
     */
    public function setVisitaDomiciliaria($visitaDomiciliaria): void
    {
        $this->visitaDomiciliaria = $visitaDomiciliaria;
    }

    /**
     * @return mixed
     */
    public function getPoligrafia()
    {
        return $this->poligrafia;
    }

    /**
     * @param mixed $poligrafia
     */
    public function setPoligrafia($poligrafia): void
    {
        $this->poligrafia = $poligrafia;
    }

    /**
     * @return mixed
     */
    public function getHoraInicio()
    {
        return $this->horaInicio;
    }

    /**
     * @param mixed $horaInicio
     */
    public function setHoraInicio($horaInicio): void
    {
        $this->horaInicio = $horaInicio;
    }

    /**
     * @return mixed
     */
    public function getHoraFinal()
    {
        return $this->horaFinal;
    }

    /**
     * @param mixed $horaFinal
     */
    public function setHoraFinal($horaFinal): void
    {
        $this->horaFinal = $horaFinal;
    }

    /**
     * @return mixed
     */
    public function getUbicacionGps()
    {
        return $this->ubicacionGps;
    }

    /**
     * @param mixed $ubicacionGps
     */
    public function setUbicacionGps($ubicacionGps): void
    {
        $this->ubicacionGps = $ubicacionGps;
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
    public function getCodigoCoordinadorFk()
    {
        return $this->codigoCoordinadorFk;
    }

    /**
     * @param mixed $codigoCoordinadorFk
     */
    public function setCodigoCoordinadorFk($codigoCoordinadorFk): void
    {
        $this->codigoCoordinadorFk = $codigoCoordinadorFk;
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
    public function getProgramadorRel()
    {
        return $this->programadorRel;
    }

    /**
     * @param mixed $programadorRel
     */
    public function setProgramadorRel($programadorRel): void
    {
        $this->programadorRel = $programadorRel;
    }

    /**
     * @return mixed
     */
    public function getPedidosDetallesPuestoRel()
    {
        return $this->pedidosDetallesPuestoRel;
    }

    /**
     * @param mixed $pedidosDetallesPuestoRel
     */
    public function setPedidosDetallesPuestoRel($pedidosDetallesPuestoRel): void
    {
        $this->pedidosDetallesPuestoRel = $pedidosDetallesPuestoRel;
    }

}

