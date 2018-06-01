<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuSeleccionRepository")
 */
class RhuSeleccion
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoSeleccionPk;

    /**
     * @ORM\Column(name="codigo_seleccion_tipo_fk", type="integer", nullable=true)
     */
    private $codigoSeleccionTipoFk;

    /**
     * @ORM\Column(name="codigo_identificacion_fk", type="string",length=3, nullable=true)
     */
    private $codigoIdentificacionFk;

    /**
     * @ORM\Column(name="codigo_estado_civil_fk", type="string", length=10, nullable=true)
     */
    private $codigoEstadoCivilFk;

    /**
     * @ORM\Column(name="codigo_grupo_pago_fk", type="integer", nullable=true)
     */
    private $codigoGrupoPagoFk;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="codigo_ciudad_nacimiento_fk", type="integer", nullable=true)
     */
    private $codigoCiudadNacimientoFk;

    /**
     * @ORM\Column(name="codigo_ciudad_expedicion_fk", type="integer", nullable=true)
     */
    private $codigoCiudadExpedicionFk;

    /**
     * @ORM\Column(name="codigo_rh_fk", type="integer", nullable=true)
     */
    private $codigoRhFk;

    /**
     * @ORM\Column(name="codigo_solicitud_fk", type="integer", nullable=true)
     */
    private $codigoSolicitudFk;

    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */
    private $codigoCargoFk;

    /**
     * @ORM\Column(name="codigo_cierre_seleccion_motivo_fk", type="integer", nullable=true)
     */
    private $codigoCierreSeleccionMotivoFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=false, unique=true)
     * @Assert\NotBlank(message="El campo no puede estar vacio")
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=120, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="nombre1", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max=30,
     *     maxMessage="El campo no puede contener mas de 30 caracteres")
     */
    private $nombre1;

    /**
     * @ORM\Column(name="nombre2", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max=30,
     *     maxMessage="El campo no puede contener mas de 30 caracteres")
     *
     */
    private $nombre2;

    /**
     * @ORM\Column(name="apellido1", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max=30,
     *     maxMessage="El campo no puede contener mas de 30 caracteres")
     */
    private $apellido1;

    /**
     * @ORM\Column(name="apellido2", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max=30,
     *     maxMessage="El campo no puede contener mas de 30 caracteres")
     */
    private $apellido2;

    /**
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     * @Assert\Length(
     *     max=15,
     *     maxMessage="El campo no puede contener mas de 15 caracteres")
     */
    private $telefono;

    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true)
     * @Assert\Length(
     *     max=20,
     *     maxMessage="El campo no puede contener mas de 20 caracteres")
     */
    private $celular;

    /**
     * @ORM\Column(name="direccion", type="string", length=60, nullable=true)
     * @Assert\Length(
     *     max=60,
     *     maxMessage="El campo no puede contener mas de 60 caracteres")
     */
    private $direccion;

    /**
     * @ORM\Column(name="barrio", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     max=100,
     *     maxMessage="El campo no puede contener mas de 100 caracteres")
     */
    private $barrio;

    /**
     * @ORM\Column(name="correo", type="string", length=80, nullable=true)
     * @Assert\Length(
     *     max=80,
     *     maxMessage="El campo no puede contener mas de 80 caracteres")
     */
    private $correo;

    /**
     * @ORM\Column(name="fecha_nacimiento", type="date", nullable=true)
     */
    private $fechaNacimiento;

    /**
     * @ORM\Column(name="comentarios", type="string", length=300, nullable=true)
     * @Assert\Length(
     *     max=300,
     *     maxMessage="El comentario no puede contener mas de 300 caracteres"
     * )
     */
    private $comentarios;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="presenta_pruebas", type="boolean")
     */
    private $presentaPruebas = false;

    /**
     * @ORM\Column(name="referencias_verificadas", type="boolean")
     */
    private $referenciasVerificadas = false;

    /**
     * @ORM\Column(name="fecha_entrevista", type="date", nullable=true)
     */
    private $fechaEntrevista;

    /**
     * @ORM\Column(name="fecha_prueba", type="date", nullable=true)
     */
    private $fechaPrueba;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="fecha_cierre", type="date", nullable=true)
     */
    private $fechaCierre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuSeleccionTipo", inversedBy="seleccionTipoRel")
     * @ORM\JoinColumn(name="codigo_seleccion_tipo_fk",referencedColumnName="codigo_seleccion_tipo_pk")
     */
    protected $seleccionTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenIdentificacion", inversedBy="rhuSeleccionIdentificacionRel")
     * @ORM\JoinColumn(name="codigo_identificacion_fk",referencedColumnName="codigo_identificacion_pk")
     */
    protected $genIdentificacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenEstadoCivil", inversedBy="rhuSeleccionEstadoCivilRel")
     * @ORM\JoinColumn(name="codigo_estado_civil_fk", referencedColumnName="codigo_estado_civil_pk")
     */
    protected $genEstadoCivilRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuGrupoPago", inversedBy="seleccionGrupoPagoRel")
     * @ORM\JoinColumn(name="codigo_grupo_pago_fk",referencedColumnName="codigo_grupo_pago_pk")
     */
    protected $grupoPagoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCiudad", inversedBy="rhuSeleccionCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $genCiudadRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCiudad", inversedBy="rhuSeleccionCiudadExpedicionRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $genCiudadExpedicionRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCiudad", inversedBy="rhuSeleccionCiudadNacimientoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $genCiudadNacimientoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuRh", inversedBy="rhuSeleccionRhRel")
     * @ORM\JoinColumn(name="codigo_rh_fk", referencedColumnName="codigo_rh_pk")
     */
    protected $rhRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", inversedBy="rhuSeleccionSolicitudRel")
     * @ORM\JoinColumn(name="codigo_solicitud_fk", referencedColumnName="codigo_solicitud_pk")
     */
    protected $solicitudRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuCargo", inversedBy="seleccionCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk",referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuCierreSeleccionMotivo", inversedBy="rhuSeleccionMotivoCierreRel")
     * @ORM\JoinColumn(name="codigo_cierre_seleccion_motivo_fk", referencedColumnName="codigo_cierre_seleccion_motivo_pk")
     */
    protected $cierreSeleccionMotivoRel;

    /**
     * @return mixed
     */
    public function getCodigoSeleccionPk()
    {
        return $this->codigoSeleccionPk;
    }

    /**
     * @param mixed $codigoSeleccionPk
     */
    public function setCodigoSeleccionPk($codigoSeleccionPk): void
    {
        $this->codigoSeleccionPk = $codigoSeleccionPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSeleccionTipoFk()
    {
        return $this->codigoSeleccionTipoFk;
    }

    /**
     * @param mixed $codigoSeleccionTipoFk
     */
    public function setCodigoSeleccionTipoFk($codigoSeleccionTipoFk): void
    {
        $this->codigoSeleccionTipoFk = $codigoSeleccionTipoFk;
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
    public function getCodigoGrupoPagoFk()
    {
        return $this->codigoGrupoPagoFk;
    }

    /**
     * @param mixed $codigoGrupoPagoFk
     */
    public function setCodigoGrupoPagoFk($codigoGrupoPagoFk): void
    {
        $this->codigoGrupoPagoFk = $codigoGrupoPagoFk;
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
    public function getCodigoSolicitudFk()
    {
        return $this->codigoSolicitudFk;
    }

    /**
     * @param mixed $codigoSolicitudFk
     */
    public function setCodigoSolicitudFk($codigoSolicitudFk): void
    {
        $this->codigoSolicitudFk = $codigoSolicitudFk;
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
    public function getCodigoCierreSeleccionMotivoFk()
    {
        return $this->codigoCierreSeleccionMotivoFk;
    }

    /**
     * @param mixed $codigoCierreSeleccionMotivoFk
     */
    public function setCodigoCierreSeleccionMotivoFk($codigoCierreSeleccionMotivoFk): void
    {
        $this->codigoCierreSeleccionMotivoFk = $codigoCierreSeleccionMotivoFk;
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
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * @param mixed $comentarios
     */
    public function setComentarios($comentarios): void
    {
        $this->comentarios = $comentarios;
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
    public function getPresentaPruebas()
    {
        return $this->presentaPruebas;
    }

    /**
     * @param mixed $presentaPruebas
     */
    public function setPresentaPruebas($presentaPruebas): void
    {
        $this->presentaPruebas = $presentaPruebas;
    }

    /**
     * @return mixed
     */
    public function getReferenciasVerificadas()
    {
        return $this->referenciasVerificadas;
    }

    /**
     * @param mixed $referenciasVerificadas
     */
    public function setReferenciasVerificadas($referenciasVerificadas): void
    {
        $this->referenciasVerificadas = $referenciasVerificadas;
    }

    /**
     * @return mixed
     */
    public function getFechaEntrevista()
    {
        return $this->fechaEntrevista;
    }

    /**
     * @param mixed $fechaEntrevista
     */
    public function setFechaEntrevista($fechaEntrevista): void
    {
        $this->fechaEntrevista = $fechaEntrevista;
    }

    /**
     * @return mixed
     */
    public function getFechaPrueba()
    {
        return $this->fechaPrueba;
    }

    /**
     * @param mixed $fechaPrueba
     */
    public function setFechaPrueba($fechaPrueba): void
    {
        $this->fechaPrueba = $fechaPrueba;
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
    public function getFechaCierre()
    {
        return $this->fechaCierre;
    }

    /**
     * @param mixed $fechaCierre
     */
    public function setFechaCierre($fechaCierre): void
    {
        $this->fechaCierre = $fechaCierre;
    }

    /**
     * @return mixed
     */
    public function getSeleccionTipoRel()
    {
        return $this->seleccionTipoRel;
    }

    /**
     * @param mixed $seleccionTipoRel
     */
    public function setSeleccionTipoRel($seleccionTipoRel): void
    {
        $this->seleccionTipoRel = $seleccionTipoRel;
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
    public function getGenEstadoCivilRel()
    {
        return $this->genEstadoCivilRel;
    }

    /**
     * @param mixed $genEstadoCivilRel
     */
    public function setGenEstadoCivilRel($genEstadoCivilRel): void
    {
        $this->genEstadoCivilRel = $genEstadoCivilRel;
    }

    /**
     * @return mixed
     */
    public function getGrupoPagoRel()
    {
        return $this->grupoPagoRel;
    }

    /**
     * @param mixed $grupoPagoRel
     */
    public function setGrupoPagoRel($grupoPagoRel): void
    {
        $this->grupoPagoRel = $grupoPagoRel;
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
    public function getGenCiudadNacimientoRel()
    {
        return $this->genCiudadNacimientoRel;
    }

    /**
     * @param mixed $genCiudadNacimientoRel
     */
    public function setGenCiudadNacimientoRel($genCiudadNacimientoRel): void
    {
        $this->genCiudadNacimientoRel = $genCiudadNacimientoRel;
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
    public function getSolicitudRel()
    {
        return $this->solicitudRel;
    }

    /**
     * @param mixed $solicitudRel
     */
    public function setSolicitudRel($solicitudRel): void
    {
        $this->solicitudRel = $solicitudRel;
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
    public function getCierreSeleccionMotivoRel()
    {
        return $this->cierreSeleccionMotivoRel;
    }

    /**
     * @param mixed $cierreSeleccionMotivoRel
     */
    public function setCierreSeleccionMotivoRel($cierreSeleccionMotivoRel): void
    {
        $this->cierreSeleccionMotivoRel = $cierreSeleccionMotivoRel;
    }


}
