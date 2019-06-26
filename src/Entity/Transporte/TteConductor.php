<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteConductorRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteConductor
{
    public $infoLog = [
        "primaryKey" => "codigoConductorPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoConductorPk;

    /**
     * @ORM\Column(name="codigo_identificacion_fk", type="string", length=3, nullable=true)
     */
    private $codigoIdentificacionFk;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=true)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=150, nullable=true)
     */
    private $nombreCorto;

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
     * @ORM\Column(name="codigo_rh_fk", type="string", length=10, nullable=true)
     */
    private $codigoRhFk;

    /**
     * @ORM\Column(name="direccion", type="string", length=200, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="fecha_registro", type="datetime", nullable=true)
     */
    private $fechaRegistro;

    /**
     * @ORM\Column(name="fecha_nacimiento", type="date", nullable=true)
     */
    private $fechaNacimiento;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="string", length=20, nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="telefono", type="string", length=30, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="movil", type="string", length=30, nullable=true)
     */
    private $movil;

    /**
     * @ORM\Column(name="numero_licencia", type="string", length=100, nullable=true)
     */
    private $numeroLicencia;

    /**
     * @ORM\Column(name="categoria_licencia", type="string", length=10, nullable=true)
     */
    private $categoriaLicencia;

    /**
     * @ORM\Column(name="fecha_vence_licencia", type="date", nullable=true)
     */
    private $fechaVenceLicencia;

    /**
     * @ORM\Column(name="barrio", type="string", length=150, nullable=true)
     */
    private $barrio;

    /**
     * @ORM\Column(name="alias", type="string", length=100, nullable=true)
     */
    private $alias;

    /**
     * @ORM\Column(name="arl", type="string", length=50, nullable=true)
     */
    private $arl;

    /**
     * @ORM\Column(name="fecha_vence_arl", type="date", nullable=true)
     */
    private $fechaVenceArl;


    /**
     * @ORM\Column(name="codigo_vehiculo", type="string", length=6, nullable=true)
     * @Assert\Length(
     *     max = 6,
     *     maxMessage="El campo no puede contener mas de 6 caracteres"
     * )
     */
    private $codigoVehiculo;

    /**
     * @ORM\Column(name="correo", type="string", length=200, nullable=true)
     */
    private $correo;

    /**
     * @ORM\Column(name="codigo_actividad_economica_fk", type="string", length=10, nullable=true)
     */
    private $codigoActividadEconomicaFk ;

    /**
     * @ORM\Column(name="estado_inactivo", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoInactivo = false;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="TteCiudad", inversedBy="conductoresCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenIdentificacion", inversedBy="tteConductoresIdentificacionRel")
     * @ORM\JoinColumn(name="codigo_identificacion_fk", referencedColumnName="codigo_identificacion_pk")
     */
    private $identificacionRel;

    /**
     * @ORM\OneToMany(targetEntity="TteDespacho", mappedBy="conductorRel")
     */
    protected $despachosConductorRel;

    /**
     * @ORM\OneToMany(targetEntity="TteRecogida", mappedBy="conductorRel")
     */
    protected $recogidasConductorRel;

    /**
     * @ORM\OneToMany(targetEntity="TteDespachoRecogida", mappedBy="conductorRel")
     */
    protected $despachosRecogidasConductorRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuRh", inversedBy="tteConductorRhRel")
     * @ORM\JoinColumn(name="codigo_rh_fk", referencedColumnName="codigo_rh_pk")
     */
    protected $rhRel;

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
    public function getCodigoConductorPk()
    {
        return $this->codigoConductorPk;
    }

    /**
     * @param mixed $codigoConductorPk
     */
    public function setCodigoConductorPk($codigoConductorPk): void
    {
        $this->codigoConductorPk = $codigoConductorPk;
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
    public function getCodigoRhFk()
    {
        return $this->codigoRhFk;
    }

    /**
     * @param mixed $codigoRhFk
     */
    public function setCodigoRhFk($codigoRhFk): void
    {
        $this->codigoRhFk = $codigoRhFk;
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
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * @param mixed $fechaNacimiento
     */
    public function setFechaNacimiento($fechaNacimiento): void
    {
        $this->fechaNacimiento = $fechaNacimiento;
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
    public function getNumeroLicencia()
    {
        return $this->numeroLicencia;
    }

    /**
     * @param mixed $numeroLicencia
     */
    public function setNumeroLicencia($numeroLicencia): void
    {
        $this->numeroLicencia = $numeroLicencia;
    }

    /**
     * @return mixed
     */
    public function getCategoriaLicencia()
    {
        return $this->categoriaLicencia;
    }

    /**
     * @param mixed $categoriaLicencia
     */
    public function setCategoriaLicencia($categoriaLicencia): void
    {
        $this->categoriaLicencia = $categoriaLicencia;
    }

    /**
     * @return mixed
     */
    public function getFechaVenceLicencia()
    {
        return $this->fechaVenceLicencia;
    }

    /**
     * @param mixed $fechaVenceLicencia
     */
    public function setFechaVenceLicencia($fechaVenceLicencia): void
    {
        $this->fechaVenceLicencia = $fechaVenceLicencia;
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
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param mixed $alias
     */
    public function setAlias($alias): void
    {
        $this->alias = $alias;
    }

    /**
     * @return mixed
     */
    public function getArl()
    {
        return $this->arl;
    }

    /**
     * @param mixed $arl
     */
    public function setArl($arl): void
    {
        $this->arl = $arl;
    }

    /**
     * @return mixed
     */
    public function getFechaVenceArl()
    {
        return $this->fechaVenceArl;
    }

    /**
     * @param mixed $fechaVenceArl
     */
    public function setFechaVenceArl($fechaVenceArl): void
    {
        $this->fechaVenceArl = $fechaVenceArl;
    }

    /**
     * @return mixed
     */
    public function getCodigoVehiculo()
    {
        return $this->codigoVehiculo;
    }

    /**
     * @param mixed $codigoVehiculo
     */
    public function setCodigoVehiculo($codigoVehiculo): void
    {
        $this->codigoVehiculo = $codigoVehiculo;
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
    public function getDespachosConductorRel()
    {
        return $this->despachosConductorRel;
    }

    /**
     * @param mixed $despachosConductorRel
     */
    public function setDespachosConductorRel($despachosConductorRel): void
    {
        $this->despachosConductorRel = $despachosConductorRel;
    }

    /**
     * @return mixed
     */
    public function getRecogidasConductorRel()
    {
        return $this->recogidasConductorRel;
    }

    /**
     * @param mixed $recogidasConductorRel
     */
    public function setRecogidasConductorRel($recogidasConductorRel): void
    {
        $this->recogidasConductorRel = $recogidasConductorRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosRecogidasConductorRel()
    {
        return $this->despachosRecogidasConductorRel;
    }

    /**
     * @param mixed $despachosRecogidasConductorRel
     */
    public function setDespachosRecogidasConductorRel($despachosRecogidasConductorRel): void
    {
        $this->despachosRecogidasConductorRel = $despachosRecogidasConductorRel;
    }

    /**
     * @return mixed
     */
    public function getRhRel()
    {
        return $this->rhRel;
    }

    /**
     * @param mixed $rhRel
     */
    public function setRhRel($rhRel): void
    {
        $this->rhRel = $rhRel;
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
    public function getCodigoActividadEconomicaFk()
    {
        return $this->codigoActividadEconomicaFk;
    }

    /**
     * @param mixed $codigoActividadEconomicaFk
     */
    public function setCodigoActividadEconomicaFk($codigoActividadEconomicaFk): void
    {
        $this->codigoActividadEconomicaFk = $codigoActividadEconomicaFk;
    }



}

