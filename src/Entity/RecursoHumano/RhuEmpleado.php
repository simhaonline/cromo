<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuEmpleadoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuEmpleado
{
    public $infoLog = [
        "primaryKey" => "codigoEmpleadoPk",
        "todos" => true,
    ];
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
     * @ORM\Column(name="codigo_empleado_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoEmpleadoTipoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="codigo_cuenta_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoCuentaTipoFk;

    /**
     * @ORM\Column(name="codigo_contrato_ultimo_fk", type="integer", nullable=true)
     */
    private $codigoContratoUltimoFk;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20 ,nullable=false)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="digito_verificacion", type="string", length=1, nullable=true)
     */
    private $digitoVerificacion;

    /**
     * @ORM\Column(name="discapacidad", type="boolean", nullable=false,options={"default":false})
     */
    private $discapacidad = false;

    /**
     * @ORM\Column(name="carro", type="boolean",options={"default":false}, nullable=false)
     */
    private $carro = false;

    /**
     * @ORM\Column(name="moto", type="boolean",options={"default":false}, nullable=false)
     */
    private $moto = false;

    /**
     * @ORM\Column(name="padre_familia", type="boolean", nullable=false,options={"default":false})
     */
    private $padreFamilia = false;

    /**
     * @ORM\Column(name="cabeza_hogar", type="boolean", nullable=false,options={"default":false})
     */
    private $cabezaHogar = false;

    /**
     * @ORM\Column(name="barrio", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     max=100,
     *     maxMessage="El campo no puede tener mas de 100 caracteres"
     * )
     */
    private $barrio;

    /**
     * @ORM\Column(name="talla_camisa", type="string", length=10,  nullable=true)
     */
    private $tallaCamisa;
    /**
     * @ORM\Column(name="talla_pantalon", type="string", length=10,  nullable=true)
     */
    private $tallaPantalon;
    /**
     * @ORM\Column(name="talla_calzado", type="string", length=10,  nullable=true)
     */
    private $tallaCalzado;
    /**
     * @ORM\Column(name="estatura", type="integer", nullable=true)
     */
    private $estatura = 0;
    /**
     * @ORM\Column(name="peso", type="integer", nullable=true)
     */
    private $peso = 0;

    /**
     * @ORM\Column(name="estado_contrato", type="boolean", nullable=false,options={"default":false})
     */
    private $estadoContrato = false;

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
     * @ORM\Column(name="libreta_militar", type="string", length=30, nullable=true)
     */
    private $libretaMilitar;

    /**
     * @ORM\Column(name="numero_hijos", type="integer", nullable=true)
     */
    private $numeroHijos = 0;


    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="codigo_ciudad_expedicion_identificacion_fk", type="integer", nullable=true)
     */
    private $codigoCiudadExpedicionIdentificacionFk;

    /**
     * @ORM\Column(name="fecha_expedicion_identificacion", type="date", nullable=true)
     */
    private $fechaExpedicionIdentificacion;

    /**
     * @ORM\Column(name="codigo_rh_fk", type="string", length=10, nullable=true)
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
     * @ORM\Column(name="cueta_tipo", type="string", length=10, nullable=true)
     */
    private $cuetaTipo;

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
     * Tabla propia de bancos de recurso humano
     * @ORM\Column(name="codigo_banco_fk", type="string", length=10, nullable=true)
     */
    private $codigoBancoFk;

    /**
     * @ORM\Column(name="codigo_cargo_fk", type="string", length=10, nullable=true)
     */
    private $codigoCargoFk;

    /**
     * @ORM\Column(name="permiso_especial", type="string", length=15, nullable=true)
     * * @Assert\Length(
     *     max=15,
     *     maxMessage="El campo no puede tener mas de 15 caracteres"
     * )
     */
    private $permisoEspecial;

    /**
     * @ORM\Column(name="codigo_estudio_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoEstudioTipoFk;

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
     * @ORM\JoinColumn(name="codigo_ciudad_expedicion_identificacion_fk",referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadExpedicionRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCiudad", inversedBy="rhuEmpleadosCiudadNacimientoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_nacimiento_fk",referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadNacimientoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenSexo", inversedBy="rhuEmpleadosSexoRel")
     * @ORM\JoinColumn(name="codigo_sexo_fk",referencedColumnName="codigo_sexo_pk")
     */
    protected $sexoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenEstadoCivil", inversedBy="rhuEmpleadosEstadoCivilRel")
     * @ORM\JoinColumn(name="codigo_estado_civil_fk",referencedColumnName="codigo_estado_civil_pk")
     */
    protected $estadoCivilRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="empleadosCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk",referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuRh", inversedBy="empleadosRhRel")
     * @ORM\JoinColumn(name="codigo_rh_fk",referencedColumnName="codigo_rh_pk")
     */
    protected $rhRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenBanco", inversedBy="empleadosBancoRel")
     * @ORM\JoinColumn(name="codigo_banco_fk",referencedColumnName="codigo_banco_pk")
     */
    protected $bancoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="empleadosContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk",referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEstudioTipo", inversedBy="empleadosEmpleadoEstudioTipoRel")
     * @ORM\JoinColumn(name="codigo_empleado_estudio_tipo_fk", referencedColumnName="codigo_estudio_tipo_pk")
     */
    protected $empleadoEstudioTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="empleadoRel")
     */
    protected $contratosEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuNovedad", mappedBy="empleadoRel")
     */
    protected $novedadesEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuCredito", mappedBy="empleadoRel")
     */
    protected $creditosEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuEmbargo", mappedBy="empleadoRel")
     */
    protected $embargosEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuVacacion", mappedBy="empleadoRel")
     */
    protected $vacacionesEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuVisita", mappedBy="empleadoRel")
     */
    protected $visitasEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuPrueba", mappedBy="empleadoRel")
     */
    protected $pruebasEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuInduccion", mappedBy="empleadoRel")
     */
    protected $induccionesEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAcreditacion", mappedBy="empleadoRel")
     */
    protected $acreditacionesEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAdicional", mappedBy="empleadoRel")
     */
    protected $adicionalesEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuPago", mappedBy="empleadoRel")
     */
    protected $pagosEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuCosto", mappedBy="empleadoRel")
     */
    protected $costosEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuDisciplinario", mappedBy="empleadoRel")
     */
    protected $disciplinariosEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuProgramacionDetalle", mappedBy="empleadoRel")
     */
    protected $programacionesPagosDetallesEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuReclamo", mappedBy="empleadoRel")
     */
    protected $reclamosEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuIncidente", mappedBy="empleadoRel")
     */
    protected $incidentesEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuDotacion", mappedBy="empleadoRel")
     */
    protected $dotacionesEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuExamen", mappedBy="empleadoRel")
     */
    protected $examenesEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurProgramacion", mappedBy="empleadoRel")
     */
    protected $programacionesEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurAdicional", mappedBy="empleadoRel")
     */
    protected $adicionalesTurnoEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAporteContrato", mappedBy="empleadoRel")
     */
    protected $aportesContratosEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAporteDetalle", mappedBy="empleadoRel")
     */
    protected $aportesDetallesEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurSoporteContrato", mappedBy="empleadoRel")
     */
    protected $soportesContratosEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurPrototipo", mappedBy="empleadoRel")
     */
    protected $prototiposEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuLiquidacion", mappedBy="empleadoRel")
     */
    protected $liquidacionesEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuLicencia", mappedBy="empleadoRel")
     */
    protected $licenciasEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidad", mappedBy="empleadoRel")
     */
    protected $incapacidadesEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuTrasladoPension", mappedBy="empleadoRel")
     */
    protected $trasladosPensionesEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuTrasladoSalud", mappedBy="empleadoRel")
     */
    protected $trasladosSaludEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuPermiso", mappedBy="empleadoRel")
     */
    protected $permisosEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuEstudio", mappedBy="empleadoRel")
     */
    protected $estudiosEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuPoligrafia", mappedBy="empleadoRel")
     */
    protected $poligrafiasEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurDistribucionEmpleado", mappedBy="empleadoRel")
     */
    protected $distribucionesEmpleadosEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCostoEmpleado", mappedBy="empleadoRel")
     */
    protected $costosEmpleadosEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCostoEmpleadoServicio", mappedBy="empleadoRel")
     */
    protected $costosEmpleadosServiciosEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurNovedad", mappedBy="empleadoRel")
     */
    protected $turNovedadesEmpleadoRel; //ya existia la relacion de novedad

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurNovedad", mappedBy="empleadoReemplazoRel")
     */
    protected $novedadesEmpeladoReemplazoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuCambioSalario", mappedBy="empleadoRel")
     */
    protected $cambiosSalariosEmpleadoRel;


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
    public function getCodigoEmpleadoTipoFk()
    {
        return $this->codigoEmpleadoTipoFk;
    }

    /**
     * @param mixed $codigoEmpleadoTipoFk
     */
    public function setCodigoEmpleadoTipoFk($codigoEmpleadoTipoFk): void
    {
        $this->codigoEmpleadoTipoFk = $codigoEmpleadoTipoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoContratoFk()
    {
        return $this->codigoContratoFk;
    }

    /**
     * @param mixed $codigoContratoFk
     */
    public function setCodigoContratoFk($codigoContratoFk): void
    {
        $this->codigoContratoFk = $codigoContratoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaTipoFk()
    {
        return $this->codigoCuentaTipoFk;
    }

    /**
     * @param mixed $codigoCuentaTipoFk
     */
    public function setCodigoCuentaTipoFk($codigoCuentaTipoFk): void
    {
        $this->codigoCuentaTipoFk = $codigoCuentaTipoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoContratoUltimoFk()
    {
        return $this->codigoContratoUltimoFk;
    }

    /**
     * @param mixed $codigoContratoUltimoFk
     */
    public function setCodigoContratoUltimoFk($codigoContratoUltimoFk): void
    {
        $this->codigoContratoUltimoFk = $codigoContratoUltimoFk;
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
    public function getDiscapacidad()
    {
        return $this->discapacidad;
    }

    /**
     * @param mixed $discapacidad
     */
    public function setDiscapacidad($discapacidad): void
    {
        $this->discapacidad = $discapacidad;
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
    public function getPadreFamilia()
    {
        return $this->padreFamilia;
    }

    /**
     * @param mixed $padreFamilia
     */
    public function setPadreFamilia($padreFamilia): void
    {
        $this->padreFamilia = $padreFamilia;
    }

    /**
     * @return mixed
     */
    public function getCabezaHogar()
    {
        return $this->cabezaHogar;
    }

    /**
     * @param mixed $cabezaHogar
     */
    public function setCabezaHogar($cabezaHogar): void
    {
        $this->cabezaHogar = $cabezaHogar;
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
    public function getTallaCamisa()
    {
        return $this->tallaCamisa;
    }

    /**
     * @param mixed $tallaCamisa
     */
    public function setTallaCamisa($tallaCamisa): void
    {
        $this->tallaCamisa = $tallaCamisa;
    }

    /**
     * @return mixed
     */
    public function getTallaPantalon()
    {
        return $this->tallaPantalon;
    }

    /**
     * @param mixed $tallaPantalon
     */
    public function setTallaPantalon($tallaPantalon): void
    {
        $this->tallaPantalon = $tallaPantalon;
    }

    /**
     * @return mixed
     */
    public function getTallaCalzado()
    {
        return $this->tallaCalzado;
    }

    /**
     * @param mixed $tallaCalzado
     */
    public function setTallaCalzado($tallaCalzado): void
    {
        $this->tallaCalzado = $tallaCalzado;
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
    public function getEstadoContrato()
    {
        return $this->estadoContrato;
    }

    /**
     * @param mixed $estadoContrato
     */
    public function setEstadoContrato($estadoContrato): void
    {
        $this->estadoContrato = $estadoContrato;
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
    public function getCodigoCiudadExpedicionIdentificacionFk()
    {
        return $this->codigoCiudadExpedicionIdentificacionFk;
    }

    /**
     * @param mixed $codigoCiudadExpedicionIdentificacionFk
     */
    public function setCodigoCiudadExpedicionIdentificacionFk($codigoCiudadExpedicionIdentificacionFk): void
    {
        $this->codigoCiudadExpedicionIdentificacionFk = $codigoCiudadExpedicionIdentificacionFk;
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
    public function getCuetaTipo()
    {
        return $this->cuetaTipo;
    }

    /**
     * @param mixed $cuetaTipo
     */
    public function setCuetaTipo($cuetaTipo): void
    {
        $this->cuetaTipo = $cuetaTipo;
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
    public function getCiudadExpedicionRel()
    {
        return $this->ciudadExpedicionRel;
    }

    /**
     * @param mixed $ciudadExpedicionRel
     */
    public function setCiudadExpedicionRel($ciudadExpedicionRel): void
    {
        $this->ciudadExpedicionRel = $ciudadExpedicionRel;
    }

    /**
     * @return mixed
     */
    public function getCiudadNacimientoRel()
    {
        return $this->ciudadNacimientoRel;
    }

    /**
     * @param mixed $ciudadNacimientoRel
     */
    public function setCiudadNacimientoRel($ciudadNacimientoRel): void
    {
        $this->ciudadNacimientoRel = $ciudadNacimientoRel;
    }

    /**
     * @return mixed
     */
    public function getSexoRel()
    {
        return $this->sexoRel;
    }

    /**
     * @param mixed $sexoRel
     */
    public function setSexoRel($sexoRel): void
    {
        $this->sexoRel = $sexoRel;
    }

    /**
     * @return mixed
     */
    public function getEstadoCivilRel()
    {
        return $this->estadoCivilRel;
    }

    /**
     * @param mixed $estadoCivilRel
     */
    public function setEstadoCivilRel($estadoCivilRel): void
    {
        $this->estadoCivilRel = $estadoCivilRel;
    }

    /**
     * @return mixed
     */
    public function getCargoRel()
    {
        return $this->cargoRel;
    }

    /**
     * @param mixed $cargoRel
     */
    public function setCargoRel($cargoRel): void
    {
        $this->cargoRel = $cargoRel;
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
    public function getBancoRel()
    {
        return $this->bancoRel;
    }

    /**
     * @param mixed $bancoRel
     */
    public function setBancoRel($bancoRel): void
    {
        $this->bancoRel = $bancoRel;
    }

    /**
     * @return mixed
     */
    public function getContratoRel()
    {
        return $this->contratoRel;
    }

    /**
     * @param mixed $contratoRel
     */
    public function setContratoRel($contratoRel): void
    {
        $this->contratoRel = $contratoRel;
    }

    /**
     * @return mixed
     */
    public function getContratosEmpleadoRel()
    {
        return $this->contratosEmpleadoRel;
    }

    /**
     * @param mixed $contratosEmpleadoRel
     */
    public function setContratosEmpleadoRel($contratosEmpleadoRel): void
    {
        $this->contratosEmpleadoRel = $contratosEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getNovedadesEmpleadoRel()
    {
        return $this->novedadesEmpleadoRel;
    }

    /**
     * @param mixed $novedadesEmpleadoRel
     */
    public function setNovedadesEmpleadoRel($novedadesEmpleadoRel): void
    {
        $this->novedadesEmpleadoRel = $novedadesEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getCreditosEmpleadoRel()
    {
        return $this->creditosEmpleadoRel;
    }

    /**
     * @param mixed $creditosEmpleadoRel
     */
    public function setCreditosEmpleadoRel($creditosEmpleadoRel): void
    {
        $this->creditosEmpleadoRel = $creditosEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getEmbargosEmpleadoRel()
    {
        return $this->embargosEmpleadoRel;
    }

    /**
     * @param mixed $embargosEmpleadoRel
     */
    public function setEmbargosEmpleadoRel($embargosEmpleadoRel): void
    {
        $this->embargosEmpleadoRel = $embargosEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getVacacionesEmpleadoRel()
    {
        return $this->vacacionesEmpleadoRel;
    }

    /**
     * @param mixed $vacacionesEmpleadoRel
     */
    public function setVacacionesEmpleadoRel($vacacionesEmpleadoRel): void
    {
        $this->vacacionesEmpleadoRel = $vacacionesEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getVisitasEmpleadoRel()
    {
        return $this->visitasEmpleadoRel;
    }

    /**
     * @param mixed $visitasEmpleadoRel
     */
    public function setVisitasEmpleadoRel($visitasEmpleadoRel): void
    {
        $this->visitasEmpleadoRel = $visitasEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getPruebasEmpleadoRel()
    {
        return $this->pruebasEmpleadoRel;
    }

    /**
     * @param mixed $pruebasEmpleadoRel
     */
    public function setPruebasEmpleadoRel($pruebasEmpleadoRel): void
    {
        $this->pruebasEmpleadoRel = $pruebasEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getInduccionesEmpleadoRel()
    {
        return $this->induccionesEmpleadoRel;
    }

    /**
     * @param mixed $induccionesEmpleadoRel
     */
    public function setInduccionesEmpleadoRel($induccionesEmpleadoRel): void
    {
        $this->induccionesEmpleadoRel = $induccionesEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getAcreditacionesEmpleadoRel()
    {
        return $this->acreditacionesEmpleadoRel;
    }

    /**
     * @param mixed $acreditacionesEmpleadoRel
     */
    public function setAcreditacionesEmpleadoRel($acreditacionesEmpleadoRel): void
    {
        $this->acreditacionesEmpleadoRel = $acreditacionesEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getAdicionalesEmpleadoRel()
    {
        return $this->adicionalesEmpleadoRel;
    }

    /**
     * @param mixed $adicionalesEmpleadoRel
     */
    public function setAdicionalesEmpleadoRel($adicionalesEmpleadoRel): void
    {
        $this->adicionalesEmpleadoRel = $adicionalesEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getPagosEmpleadoRel()
    {
        return $this->pagosEmpleadoRel;
    }

    /**
     * @param mixed $pagosEmpleadoRel
     */
    public function setPagosEmpleadoRel($pagosEmpleadoRel): void
    {
        $this->pagosEmpleadoRel = $pagosEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getCostosEmpleadoRel()
    {
        return $this->costosEmpleadoRel;
    }

    /**
     * @param mixed $costosEmpleadoRel
     */
    public function setCostosEmpleadoRel($costosEmpleadoRel): void
    {
        $this->costosEmpleadoRel = $costosEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getDisciplinariosEmpleadoRel()
    {
        return $this->disciplinariosEmpleadoRel;
    }

    /**
     * @param mixed $disciplinariosEmpleadoRel
     */
    public function setDisciplinariosEmpleadoRel($disciplinariosEmpleadoRel): void
    {
        $this->disciplinariosEmpleadoRel = $disciplinariosEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getProgramacionesPagosDetallesEmpleadoRel()
    {
        return $this->programacionesPagosDetallesEmpleadoRel;
    }

    /**
     * @param mixed $programacionesPagosDetallesEmpleadoRel
     */
    public function setProgramacionesPagosDetallesEmpleadoRel($programacionesPagosDetallesEmpleadoRel): void
    {
        $this->programacionesPagosDetallesEmpleadoRel = $programacionesPagosDetallesEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getReclamosEmpleadoRel()
    {
        return $this->reclamosEmpleadoRel;
    }

    /**
     * @param mixed $reclamosEmpleadoRel
     */
    public function setReclamosEmpleadoRel($reclamosEmpleadoRel): void
    {
        $this->reclamosEmpleadoRel = $reclamosEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getIncidentesEmpleadoRel()
    {
        return $this->incidentesEmpleadoRel;
    }

    /**
     * @param mixed $incidentesEmpleadoRel
     */
    public function setIncidentesEmpleadoRel($incidentesEmpleadoRel): void
    {
        $this->incidentesEmpleadoRel = $incidentesEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getDotacionesEmpleadoRel()
    {
        return $this->dotacionesEmpleadoRel;
    }

    /**
     * @param mixed $dotacionesEmpleadoRel
     */
    public function setDotacionesEmpleadoRel($dotacionesEmpleadoRel): void
    {
        $this->dotacionesEmpleadoRel = $dotacionesEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getExamenesEmpleadoRel()
    {
        return $this->examenesEmpleadoRel;
    }

    /**
     * @param mixed $examenesEmpleadoRel
     */
    public function setExamenesEmpleadoRel($examenesEmpleadoRel): void
    {
        $this->examenesEmpleadoRel = $examenesEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getProgramacionesEmpleadoRel()
    {
        return $this->programacionesEmpleadoRel;
    }

    /**
     * @param mixed $programacionesEmpleadoRel
     */
    public function setProgramacionesEmpleadoRel($programacionesEmpleadoRel): void
    {
        $this->programacionesEmpleadoRel = $programacionesEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getAportesContratosEmpleadoRel()
    {
        return $this->aportesContratosEmpleadoRel;
    }

    /**
     * @param mixed $aportesContratosEmpleadoRel
     */
    public function setAportesContratosEmpleadoRel($aportesContratosEmpleadoRel): void
    {
        $this->aportesContratosEmpleadoRel = $aportesContratosEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getAportesDetallesEmpleadoRel()
    {
        return $this->aportesDetallesEmpleadoRel;
    }

    /**
     * @param mixed $aportesDetallesEmpleadoRel
     */
    public function setAportesDetallesEmpleadoRel($aportesDetallesEmpleadoRel): void
    {
        $this->aportesDetallesEmpleadoRel = $aportesDetallesEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getSoportesContratosEmpleadoRel()
    {
        return $this->soportesContratosEmpleadoRel;
    }

    /**
     * @param mixed $soportesContratosEmpleadoRel
     */
    public function setSoportesContratosEmpleadoRel($soportesContratosEmpleadoRel): void
    {
        $this->soportesContratosEmpleadoRel = $soportesContratosEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getPrototiposEmpleadoRel()
    {
        return $this->prototiposEmpleadoRel;
    }

    /**
     * @param mixed $prototiposEmpleadoRel
     */
    public function setPrototiposEmpleadoRel($prototiposEmpleadoRel): void
    {
        $this->prototiposEmpleadoRel = $prototiposEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getLiquidacionesEmpleadoRel()
    {
        return $this->liquidacionesEmpleadoRel;
    }

    /**
     * @param mixed $liquidacionesEmpleadoRel
     */
    public function setLiquidacionesEmpleadoRel($liquidacionesEmpleadoRel): void
    {
        $this->liquidacionesEmpleadoRel = $liquidacionesEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getLicenciasEmpleadoRel()
    {
        return $this->licenciasEmpleadoRel;
    }

    /**
     * @param mixed $licenciasEmpleadoRel
     */
    public function setLicenciasEmpleadoRel($licenciasEmpleadoRel): void
    {
        $this->licenciasEmpleadoRel = $licenciasEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getIncapacidadesEmpleadoRel()
    {
        return $this->incapacidadesEmpleadoRel;
    }

    /**
     * @param mixed $incapacidadesEmpleadoRel
     */
    public function setIncapacidadesEmpleadoRel($incapacidadesEmpleadoRel): void
    {
        $this->incapacidadesEmpleadoRel = $incapacidadesEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getTrasladosPensionesEmpleadoRel()
    {
        return $this->trasladosPensionesEmpleadoRel;
    }

    /**
     * @param mixed $trasladosPensionesEmpleadoRel
     */
    public function setTrasladosPensionesEmpleadoRel($trasladosPensionesEmpleadoRel): void
    {
        $this->trasladosPensionesEmpleadoRel = $trasladosPensionesEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getTrasladosSaludEmpleadoRel()
    {
        return $this->trasladosSaludEmpleadoRel;
    }

    /**
     * @param mixed $trasladosSaludEmpleadoRel
     */
    public function setTrasladosSaludEmpleadoRel($trasladosSaludEmpleadoRel): void
    {
        $this->trasladosSaludEmpleadoRel = $trasladosSaludEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getPermisosEmpleadoRel()
    {
        return $this->permisosEmpleadoRel;
    }

    /**
     * @param mixed $permisosEmpleadoRel
     */
    public function setPermisosEmpleadoRel($permisosEmpleadoRel): void
    {
        $this->permisosEmpleadoRel = $permisosEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getEstudiosEmpleadoRel()
    {
        return $this->estudiosEmpleadoRel;
    }

    /**
     * @param mixed $estudiosEmpleadoRel
     */
    public function setEstudiosEmpleadoRel($estudiosEmpleadoRel): void
    {
        $this->estudiosEmpleadoRel = $estudiosEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getPoligrafiasEmpleadoRel()
    {
        return $this->poligrafiasEmpleadoRel;
    }

    /**
     * @param mixed $poligrafiasEmpleadoRel
     */
    public function setPoligrafiasEmpleadoRel($poligrafiasEmpleadoRel): void
    {
        $this->poligrafiasEmpleadoRel = $poligrafiasEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getDistribucionesEmpleadosEmpleadoRel()
    {
        return $this->distribucionesEmpleadosEmpleadoRel;
    }

    /**
     * @param mixed $distribucionesEmpleadosEmpleadoRel
     */
    public function setDistribucionesEmpleadosEmpleadoRel($distribucionesEmpleadosEmpleadoRel): void
    {
        $this->distribucionesEmpleadosEmpleadoRel = $distribucionesEmpleadosEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getCostosEmpleadosEmpleadoRel()
    {
        return $this->costosEmpleadosEmpleadoRel;
    }

    /**
     * @param mixed $costosEmpleadosEmpleadoRel
     */
    public function setCostosEmpleadosEmpleadoRel($costosEmpleadosEmpleadoRel): void
    {
        $this->costosEmpleadosEmpleadoRel = $costosEmpleadosEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getCostosEmpleadosServiciosEmpleadoRel()
    {
        return $this->costosEmpleadosServiciosEmpleadoRel;
    }

    /**
     * @param mixed $costosEmpleadosServiciosEmpleadoRel
     */
    public function setCostosEmpleadosServiciosEmpleadoRel($costosEmpleadosServiciosEmpleadoRel): void
    {
        $this->costosEmpleadosServiciosEmpleadoRel = $costosEmpleadosServiciosEmpleadoRel;
    }

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
    public function getAdicionalesTurnoEmpleadoRel()
    {
        return $this->adicionalesTurnoEmpleadoRel;
    }

    /**
     * @param mixed $adicionalesTurnoEmpleadoRel
     */
    public function setAdicionalesTurnoEmpleadoRel($adicionalesTurnoEmpleadoRel): void
    {
        $this->adicionalesTurnoEmpleadoRel = $adicionalesTurnoEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getTurNovedadesEmpleadoRel()
    {
        return $this->turNovedadesEmpleadoRel;
    }

    /**
     * @param mixed $turNovedadesEmpleadoRel
     */
    public function setTurNovedadesEmpleadoRel($turNovedadesEmpleadoRel): void
    {
        $this->turNovedadesEmpleadoRel = $turNovedadesEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getNovedadesEmpeladoReemplazoRel()
    {
        return $this->novedadesEmpeladoReemplazoRel;
    }

    /**
     * @param mixed $novedadesEmpeladoReemplazoRel
     */
    public function setNovedadesEmpeladoReemplazoRel($novedadesEmpeladoReemplazoRel): void
    {
        $this->novedadesEmpeladoReemplazoRel = $novedadesEmpeladoReemplazoRel;
    }

    /**
     * @return mixed
     */
    public function getPermisoEspecial()
    {
        return $this->permisoEspecial;
    }

    /**
     * @param mixed $permisoEspecial
     */
    public function setPermisoEspecial($permisoEspecial): void
    {
        $this->permisoEspecial = $permisoEspecial;
    }

    /**
     * @return mixed
     */
    public function getLibretaMilitar()
    {
        return $this->libretaMilitar;
    }

    /**
     * @param mixed $libretaMilitar
     */
    public function setLibretaMilitar($libretaMilitar): void
    {
        $this->libretaMilitar = $libretaMilitar;
    }

    /**
     * @return mixed
     */
    public function getCambiosSalariosEmpleadoRel()
    {
        return $this->cambiosSalariosEmpleadoRel;
    }

    /**
     * @param mixed $cambiosSalariosEmpleadoRel
     */
    public function setCambiosSalariosEmpleadoRel($cambiosSalariosEmpleadoRel): void
    {
        $this->cambiosSalariosEmpleadoRel = $cambiosSalariosEmpleadoRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoEstudioTipoFk()
    {
        return $this->codigoEstudioTipoFk;
    }

    /**
     * @param mixed $codigoEstudioTipoFk
     */
    public function setCodigoEstudioTipoFk($codigoEstudioTipoFk): void
    {
        $this->codigoEstudioTipoFk = $codigoEstudioTipoFk;
    }

    /**
     * @return mixed
     */
    public function getEmpleadoEstudioTipoRel()
    {
        return $this->empleadoEstudioTipoRel;
    }

    /**
     * @param mixed $empleadoEstudioTipoRel
     */
    public function setEmpleadoEstudioTipoRel($empleadoEstudioTipoRel): void
    {
        $this->empleadoEstudioTipoRel = $empleadoEstudioTipoRel;
    }

    /**
     * @return int
     */
    public function getNumeroHijos()
    {
        return $this->numeroHijos;
    }

    /**
     * @param int $numeroHijos
     */
    public function setNumeroHijos(int $numeroHijos): void
    {
        $this->numeroHijos = $numeroHijos;
    }



}