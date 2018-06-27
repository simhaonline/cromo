<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuEmpleadoRepository")
 */
class RhuEmpleado
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_empleado_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoEmpleadoPk;

    /**
     * @ORM\Column(name="codigo_clasificacion_riesgo_fk", type="integer", nullable=true)
     */
    private $codigoClasificacionRiesgoFk;

    /**
     * @ORM\Column(name="codigo_identificacion_fk", type="string", length=3, nullable=true)
     */
    private $codigoIdentificacionFk;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=false, unique=true)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=80, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="nombre1", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max=30,
     *     maxMessage="El campo no puede tener mas de 30 caracteres"
     * )
     */
    private $nombre1;

    /**
     * @ORM\Column(name="nombre2", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max=30,
     *     maxMessage="El campo no puede tener mas de 30 caracteres"
     * )
     */
    private $nombre2;

    /**
     * @ORM\Column(name="apellido1", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max=30,
     *     maxMessage="El campo no puede tener mas de 30 caracteres"
     * )
     */
    private $apellido1;

    /**
     * @ORM\Column(name="apellido2", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max=30,
     *     maxMessage="El campo no puede tener mas de 30 caracteres"
     * )
     */
    private $apellido2;


    /**
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     * @Assert\Length(
     *     max=15,
     *     maxMessage="El campo no puede tener mas de 15 caracteres"
     * )
     */
    private $telefono;

    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true)
     * @Assert\Length(max=20, maxMessage="El campo celular no puede contener mas de 20 caracteres.")
     *
     */
    private $celular;

    /**
     * @ORM\Column(name="direccion", type="string", length=120, nullable=true)
     * @Assert\Length(
     *     max=120,
     *     maxMessage="El campo no puede tener mas de 120 caracteres"
     * )
     */
    private $direccion;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="codigo_ciudad_expedicion_fk", type="integer", nullable=true)
     */
    private $codigoCiudadExpedicionFk;

    /**
     * @ORM\Column(name="fecha_expedicion_identificacion", type="date", nullable=true)
     */
    private $fechaExpedicionIdentificacion;

    /**
     * @ORM\Column(name="barrio", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     max=100,
     *     maxMessage="El campo no puede tener mas de 100 caracteres"
     * )
     */
    private $barrio;

    /**
     * @ORM\Column(name="codigo_rh_fk", type="integer", nullable=true)
     */
    private $codigoRhFk;

    /**
     * @ORM\Column(name="codigo_sexo_fk", type="string", length=1, nullable=true)
     */
    private $codigoSexoFk;

    /**
     * @ORM\Column(name="correo", type="string", length=80, nullable=true)
     * @Assert\Length(
     *     max=80,
     *     maxMessage="El campo no puede tener mas de 80 caracteres"
     * )
     */
    private $correo;

    /**
     * @ORM\Column(name="fecha_nacimiento", type="date", nullable=true)
     */
    private $fechaNacimiento;

    /**
     * @ORM\Column(name="codigo_ciudad_nacimiento_fk", type="integer", nullable=true)
     */
    private $codigoCiudadNacimientoFk;

    /**
     * @ORM\Column(name="codigo_estado_civil_fk", type="string", length=1, nullable=true)
     */
    private $codigoEstadoCivilFk;

    /**
     * @ORM\Column(name="cuenta", type="string", length=80, nullable=true)
     * @Assert\Length(
     *     max=80,
     *     maxMessage="El campo no puede tener mas de 80 caracteres"
     * )
     */
    private $cuenta;

    /**
     * @ORM\Column(name="codigo_banco_fk", type="integer", nullable=true)
     */
    private $codigoBancoFk;

    /**
     * @ORM\Column(name="estatura", type="integer", nullable=true)
     */
    private $estatura = 0;

    /**
     * @ORM\Column(name="peso", type="integer", nullable=true)
     */
    private $peso = 0;

    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */
    private $codigoCargoFk;

    /**
     * @ORM\Column(name="cargo_descripcion", type="string", length=60, nullable=true)
     * @Assert\Length(
     *     max=60,
     *     maxMessage="El campo no puede tener mas de 60 caracteres"
     * )
     */
    private $cargoDescripcion;

    /**
     * @ORM\Column(name="auxilio_transporte", type="boolean")
     */
    private $auxilioTransporte = false;

    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $VrSalario = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenIdentificacion", inversedBy="rhuEmpleadosIdentificacionRel")
     * @ORM\JoinColumn(name="codigo_identificacion_fk",referencedColumnName="codigo_identificacion_pk")
     */
    protected $identificacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCiudad", inversedBy="rhuEmpleadosCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk",referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCiudad", inversedBy="rhuEmpleadosCiudadExpedicionRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk",referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadExpedicionRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenSexo", inversedBy="rhuEmpleadosSexoRel")
     * @ORM\JoinColumn(name="codigo_sexo_fk",referencedColumnName="codigo_sexo_pk")
     */
    protected $sexoRel;

    /**
     * @return mixed
     */
    public function getCodigoEmpleadoPk()
    {
        return $this->codigoEmpleadoPk;
    }

    /**
     * @param mixed $codigoEmpleadoPk
     */
    public function setCodigoEmpleadoPk($codigoEmpleadoPk): void
    {
        $this->codigoEmpleadoPk = $codigoEmpleadoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoClasificacionRiesgoFk()
    {
        return $this->codigoClasificacionRiesgoFk;
    }

    /**
     * @param mixed $codigoClasificacionRiesgoFk
     */
    public function setCodigoClasificacionRiesgoFk($codigoClasificacionRiesgoFk): void
    {
        $this->codigoClasificacionRiesgoFk = $codigoClasificacionRiesgoFk;
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
    public function getCodigoCiudadExpedicionFk()
    {
        return $this->codigoCiudadExpedicionFk;
    }

    /**
     * @param mixed $codigoCiudadExpedicionFk
     */
    public function setCodigoCiudadExpedicionFk($codigoCiudadExpedicionFk): void
    {
        $this->codigoCiudadExpedicionFk = $codigoCiudadExpedicionFk;
    }

    /**
     * @return mixed
     */
    public function getFechaExpedicionIdentificacion()
    {
        return $this->fechaExpedicionIdentificacion;
    }

    /**
     * @param mixed $fechaExpedicionIdentificacion
     */
    public function setFechaExpedicionIdentificacion($fechaExpedicionIdentificacion): void
    {
        $this->fechaExpedicionIdentificacion = $fechaExpedicionIdentificacion;
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
    public function getCodigoCiudadNacimientoFk()
    {
        return $this->codigoCiudadNacimientoFk;
    }

    /**
     * @param mixed $codigoCiudadNacimientoFk
     */
    public function setCodigoCiudadNacimientoFk($codigoCiudadNacimientoFk): void
    {
        $this->codigoCiudadNacimientoFk = $codigoCiudadNacimientoFk;
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
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * @param mixed $cuenta
     */
    public function setCuenta($cuenta): void
    {
        $this->cuenta = $cuenta;
    }

    /**
     * @return mixed
     */
    public function getCodigoBancoFk()
    {
        return $this->codigoBancoFk;
    }

    /**
     * @param mixed $codigoBancoFk
     */
    public function setCodigoBancoFk($codigoBancoFk): void
    {
        $this->codigoBancoFk = $codigoBancoFk;
    }

    /**
     * @return mixed
     */
    public function getEstatura()
    {
        return $this->estatura;
    }

    /**
     * @param mixed $estatura
     */
    public function setEstatura($estatura): void
    {
        $this->estatura = $estatura;
    }

    /**
     * @return mixed
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * @param mixed $peso
     */
    public function setPeso($peso): void
    {
        $this->peso = $peso;
    }

    /**
     * @return mixed
     */
    public function getCodigoCargoFk()
    {
        return $this->codigoCargoFk;
    }

    /**
     * @param mixed $codigoCargoFk
     */
    public function setCodigoCargoFk($codigoCargoFk): void
    {
        $this->codigoCargoFk = $codigoCargoFk;
    }

    /**
     * @return mixed
     */
    public function getCargoDescripcion()
    {
        return $this->cargoDescripcion;
    }

    /**
     * @param mixed $cargoDescripcion
     */
    public function setCargoDescripcion($cargoDescripcion): void
    {
        $this->cargoDescripcion = $cargoDescripcion;
    }

    /**
     * @return mixed
     */
    public function getAuxilioTransporte()
    {
        return $this->auxilioTransporte;
    }

    /**
     * @param mixed $auxilioTransporte
     */
    public function setAuxilioTransporte($auxilioTransporte): void
    {
        $this->auxilioTransporte = $auxilioTransporte;
    }

    /**
     * @return mixed
     */
    public function getVrSalario()
    {
        return $this->VrSalario;
    }

    /**
     * @param mixed $VrSalario
     */
    public function setVrSalario($VrSalario): void
    {
        $this->VrSalario = $VrSalario;
    }

    /**
     * @return mixed
     */
    public function getGenIdentificacionRel()
    {
        return $this->genIdentificacionRel;
    }

    /**
     * @param mixed $genIdentificacionRel
     */
    public function setGenIdentificacionRel($genIdentificacionRel): void
    {
        $this->genIdentificacionRel = $genIdentificacionRel;
    }

    /**
     * @return mixed
     */
    public function getGenCiudadRel()
    {
        return $this->genCiudadRel;
    }

    /**
     * @param mixed $genCiudadRel
     */
    public function setGenCiudadRel($genCiudadRel): void
    {
        $this->genCiudadRel = $genCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getGenCiudadExpedicionRel()
    {
        return $this->genCiudadExpedicionRel;
    }

    /**
     * @param mixed $genCiudadExpedicionRel
     */
    public function setGenCiudadExpedicionRel($genCiudadExpedicionRel): void
    {
        $this->genCiudadExpedicionRel = $genCiudadExpedicionRel;
    }

    /**
     * @return mixed
     */
    public function getGenSexoRel()
    {
        return $this->genSexoRel;
    }

    /**
     * @param mixed $genSexoRel
     */
    public function setGenSexoRel($genSexoRel): void
    {
        $this->genSexoRel = $genSexoRel;
    }



}
