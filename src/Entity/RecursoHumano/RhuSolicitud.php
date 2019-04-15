<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuSolicitudRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuSolicitud
{
//    public $infoLog = [
//        "primaryKey" => "codigoSolicitudPk",
//        "todos"     => true,
//    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_solicitud_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoSolicitudPk;

    /**
     * @ORM\Column(name="codigo_grupo_fk", type="string", length=10,  nullable=true)
     */
    private $codigoGrupoFk;

    /**
     * @ORM\Column(name="codigo_cargo_fk", type="string", length=10, nullable=true)
     */
    private $codigoCargoFk;

    /**
     * @ORM\Column(name="codigo_solicitud_motivo_fk", type="integer", nullable=true)
     */
    private $codigoSolicitudMotivoFk;

    /**
     * @ORM\Column(name="codigo_experiencia_solicitud_fk", type="integer", nullable=true)
     */
    private $codigoExperienciaSolicitudFk;

    /**
     * @ORM\Column(name="codigo_estado_civil_fk", type="string", length=10, nullable=true)
     */
    private $codigoEstadoCivilFk;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="codigo_estudio_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoEstudioTipoFk;

    /**
     * @ORM\Column(name="codigo_clasificacion_riesgo_fk", type="string", length=10, nullable=true)
     */
    private $codigoClasificacionRiesgoFk;

    /**
     * @ORM\Column(name="codigo_sexo_fk", type="string", length=1, nullable=true)
     */
    private $codigoSexoFk;

    /**
     * @ORM\Column(name="codigo_religion_fk", type="string", length=10, nullable=true)
     */
    private $codigoReligionFk;

    /**
     * @ORM\Column(name="disponibilidad", type="string", length=20, nullable=true)
     */
    private $disponbilidad;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_contratacion", type="date", nullable=true)
     */
    private $fechaContratacion;

    /**
     * @ORM\Column(name="fecha_vencimiento", type="date", nullable=true)
     */
    private $fechaVencimiento;

    /**
     * @ORM\Column(name="nombre", type="string", nullable=true)
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $nombre;

    /**
     * @ORM\Column(name="cantidad_solicitada", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $cantidadSolicitada;

    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $VrSalario = 0;

    /**
     * @ORM\Column(name="vr_no_salarial", type="float", nullable=true)
     */
    private $VrNoSalarial = 0;

    /**
     * @ORM\Column(name="salario_fijo", type="boolean",options={"default":false})
     */
    private $salarioFijo = false;

    /**
     * @ORM\Column(name="salario_variable", type="boolean",options={"default":false})
     */
    private $salarioVariable = false;

    /**
     * @ORM\Column(name="fecha_pruebas", type="datetime", nullable=true)
     */
    private $fechaPruebas;

    /**
     * @ORM\Column(name="edad_minima", type="string", length=20, nullable=true)
     */
    private $edadMinima;

    /**
     * @ORM\Column(name="edad_maxima", type="string", length=20, nullable=true)
     */
    private $edadMaxima;

    /**
     * @ORM\Column(name="numero_hijos", type="integer", nullable=true)
     */
    private $numeroHijos;

    /**
     * @ORM\Column(name="codigo_tipo_vehiculo_fk", type="string", length=30, nullable=true)
     */
    private $codigoTipoVehiculoFk;

    /**
     * @ORM\Column(name="codigo_licencia_carro_fk", type="string", length=30, nullable=true)
     */
    private $codigoLicenciaCarroFk;

    /**
     * @ORM\Column(name="codigo_licencia_moto_fk", type="string", length=30, nullable=true)
     */
    private $codigoLicenciaMotoFk;

    /**
     * @ORM\Column(name="experiencia_solicitud", type="string", nullable=true)
     */
    private $experienciaSolicitud;

    /**
     * @ORM\Column(name="comentarios", type="string", length=300, nullable=true)
     * @Assert\Length(
     *     max=300,
     *     maxMessage="El comentario no puede contener mas de 300 caracteres"
     * )
     */
    private $comentarios;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean",options={"default":false})
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean",options={"default":false})
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_cerrado", type="boolean",options={"default":false})
     */
    private $estadoCerrado = false;

    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */
    private $codigoUsuario;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="solicitudesCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk",referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuGrupo", inversedBy="solicitudesGrupoRel")
     * @ORM\JoinColumn(name="codigo_grupo_fk", referencedColumnName="codigo_grupo_pk")
     */
    protected $grupoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuSolicitudMotivo", inversedBy="solicitudesMotivosRel")
     * @ORM\JoinColumn(name="codigo_solicitud_motivo_fk",referencedColumnName="codigo_solicitud_motivo_pk")
     */
    protected $solicitudMotivoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuSolicitudExperiencia", inversedBy="solicitudesExperienciasRel")
     * @ORM\JoinColumn(name="codigo_solicitud_experiencia_fk",referencedColumnName="codigo_solicitud_experiencia_pk")
     */
    protected $solicitudExperienciaRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenEstadoCivil", inversedBy="rhuSolicitudesEstadoCivilRel")
     * @ORM\JoinColumn(name="codigo_estado_civil_fk", referencedColumnName="codigo_estado_civil_pk")
     */
    protected $estadoCivilRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCiudad", inversedBy="rhuSolicitudesCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenEstudioTipo", inversedBy="rhuSolicitudesEstudioTiposRel")
     * @ORM\JoinColumn(name="codigo_estudio_tipo_fk", referencedColumnName="codigo_estudio_tipo_pk")
     */
    protected $estudioTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuClasificacionRiesgo", inversedBy="solicitudesClasificacionRiesgoRel")
     * @ORM\JoinColumn(name="codigo_clasificacion_riesgo_fk",referencedColumnName="codigo_clasificacion_riesgo_pk")
     */
    protected $clasificacionRiesgoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccion", mappedBy="solicitudRel")
     */
    protected $seleccionSolicitudRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenSexo", inversedBy="rhuSolicitudesSexoRel")
     * @ORM\JoinColumn(name="codigo_sexo_fk", referencedColumnName="codigo_sexo_pk")
     */
    protected $sexoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenReligion", inversedBy="rhuSolicitudesReligicionRel")
     * @ORM\JoinColumn(name="codigo_religion_fk", referencedColumnName="codigo_religion_pk")
     */
    protected $religionRel;

    /**
     * @return mixed
     */
    public function getCodigoSolicitudPk()
    {
        return $this->codigoSolicitudPk;
    }

    /**
     * @param mixed $codigoSolicitudPk
     */
    public function setCodigoSolicitudPk($codigoSolicitudPk): void
    {
        $this->codigoSolicitudPk = $codigoSolicitudPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoGrupoFk()
    {
        return $this->codigoGrupoFk;
    }

    /**
     * @param mixed $codigoGrupoFk
     */
    public function setCodigoGrupoFk($codigoGrupoFk): void
    {
        $this->codigoGrupoFk = $codigoGrupoFk;
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
    public function getCodigoSolicitudMotivoFk()
    {
        return $this->codigoSolicitudMotivoFk;
    }

    /**
     * @param mixed $codigoSolicitudMotivoFk
     */
    public function setCodigoSolicitudMotivoFk($codigoSolicitudMotivoFk): void
    {
        $this->codigoSolicitudMotivoFk = $codigoSolicitudMotivoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoExperienciaSolicitudFk()
    {
        return $this->codigoExperienciaSolicitudFk;
    }

    /**
     * @param mixed $codigoExperienciaSolicitudFk
     */
    public function setCodigoExperienciaSolicitudFk($codigoExperienciaSolicitudFk): void
    {
        $this->codigoExperienciaSolicitudFk = $codigoExperienciaSolicitudFk;
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
    public function getCodigoReligionFk()
    {
        return $this->codigoReligionFk;
    }

    /**
     * @param mixed $codigoReligionFk
     */
    public function setCodigoReligionFk($codigoReligionFk): void
    {
        $this->codigoReligionFk = $codigoReligionFk;
    }

    /**
     * @return mixed
     */
    public function getDisponbilidad()
    {
        return $this->disponbilidad;
    }

    /**
     * @param mixed $disponbilidad
     */
    public function setDisponbilidad($disponbilidad): void
    {
        $this->disponbilidad = $disponbilidad;
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
    public function getFechaContratacion()
    {
        return $this->fechaContratacion;
    }

    /**
     * @param mixed $fechaContratacion
     */
    public function setFechaContratacion($fechaContratacion): void
    {
        $this->fechaContratacion = $fechaContratacion;
    }

    /**
     * @return mixed
     */
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * @param mixed $fechaVencimiento
     */
    public function setFechaVencimiento($fechaVencimiento): void
    {
        $this->fechaVencimiento = $fechaVencimiento;
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
    public function getCantidadSolicitada()
    {
        return $this->cantidadSolicitada;
    }

    /**
     * @param mixed $cantidadSolicitada
     */
    public function setCantidadSolicitada($cantidadSolicitada): void
    {
        $this->cantidadSolicitada = $cantidadSolicitada;
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
    public function getVrNoSalarial()
    {
        return $this->VrNoSalarial;
    }

    /**
     * @param mixed $VrNoSalarial
     */
    public function setVrNoSalarial($VrNoSalarial): void
    {
        $this->VrNoSalarial = $VrNoSalarial;
    }

    /**
     * @return mixed
     */
    public function getSalarioFijo()
    {
        return $this->salarioFijo;
    }

    /**
     * @param mixed $salarioFijo
     */
    public function setSalarioFijo($salarioFijo): void
    {
        $this->salarioFijo = $salarioFijo;
    }

    /**
     * @return mixed
     */
    public function getSalarioVariable()
    {
        return $this->salarioVariable;
    }

    /**
     * @param mixed $salarioVariable
     */
    public function setSalarioVariable($salarioVariable): void
    {
        $this->salarioVariable = $salarioVariable;
    }

    /**
     * @return mixed
     */
    public function getFechaPruebas()
    {
        return $this->fechaPruebas;
    }

    /**
     * @param mixed $fechaPruebas
     */
    public function setFechaPruebas($fechaPruebas): void
    {
        $this->fechaPruebas = $fechaPruebas;
    }

    /**
     * @return mixed
     */
    public function getEdadMinima()
    {
        return $this->edadMinima;
    }

    /**
     * @param mixed $edadMinima
     */
    public function setEdadMinima($edadMinima): void
    {
        $this->edadMinima = $edadMinima;
    }

    /**
     * @return mixed
     */
    public function getEdadMaxima()
    {
        return $this->edadMaxima;
    }

    /**
     * @param mixed $edadMaxima
     */
    public function setEdadMaxima($edadMaxima): void
    {
        $this->edadMaxima = $edadMaxima;
    }

    /**
     * @return mixed
     */
    public function getNumeroHijos()
    {
        return $this->numeroHijos;
    }

    /**
     * @param mixed $numeroHijos
     */
    public function setNumeroHijos($numeroHijos): void
    {
        $this->numeroHijos = $numeroHijos;
    }

    /**
     * @return mixed
     */
    public function getCodigoTipoVehiculoFk()
    {
        return $this->codigoTipoVehiculoFk;
    }

    /**
     * @param mixed $codigoTipoVehiculoFk
     */
    public function setCodigoTipoVehiculoFk($codigoTipoVehiculoFk): void
    {
        $this->codigoTipoVehiculoFk = $codigoTipoVehiculoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoLicenciaCarroFk()
    {
        return $this->codigoLicenciaCarroFk;
    }

    /**
     * @param mixed $codigoLicenciaCarroFk
     */
    public function setCodigoLicenciaCarroFk($codigoLicenciaCarroFk): void
    {
        $this->codigoLicenciaCarroFk = $codigoLicenciaCarroFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoLicenciaMotoFk()
    {
        return $this->codigoLicenciaMotoFk;
    }

    /**
     * @param mixed $codigoLicenciaMotoFk
     */
    public function setCodigoLicenciaMotoFk($codigoLicenciaMotoFk): void
    {
        $this->codigoLicenciaMotoFk = $codigoLicenciaMotoFk;
    }

    /**
     * @return mixed
     */
    public function getExperienciaSolicitud()
    {
        return $this->experienciaSolicitud;
    }

    /**
     * @param mixed $experienciaSolicitud
     */
    public function setExperienciaSolicitud($experienciaSolicitud): void
    {
        $this->experienciaSolicitud = $experienciaSolicitud;
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
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * @param mixed $codigoUsuario
     */
    public function setCodigoUsuario($codigoUsuario): void
    {
        $this->codigoUsuario = $codigoUsuario;
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
    public function getGrupoRel()
    {
        return $this->grupoRel;
    }

    /**
     * @param mixed $grupoRel
     */
    public function setGrupoRel($grupoRel): void
    {
        $this->grupoRel = $grupoRel;
    }

    /**
     * @return mixed
     */
    public function getSolicitudMotivoRel()
    {
        return $this->solicitudMotivoRel;
    }

    /**
     * @param mixed $solicitudMotivoRel
     */
    public function setSolicitudMotivoRel($solicitudMotivoRel): void
    {
        $this->solicitudMotivoRel = $solicitudMotivoRel;
    }

    /**
     * @return mixed
     */
    public function getSolicitudExperienciaRel()
    {
        return $this->solicitudExperienciaRel;
    }

    /**
     * @param mixed $solicitudExperienciaRel
     */
    public function setSolicitudExperienciaRel($solicitudExperienciaRel): void
    {
        $this->solicitudExperienciaRel = $solicitudExperienciaRel;
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
    public function getEstudioTipoRel()
    {
        return $this->estudioTipoRel;
    }

    /**
     * @param mixed $estudioTipoRel
     */
    public function setEstudioTipoRel($estudioTipoRel): void
    {
        $this->estudioTipoRel = $estudioTipoRel;
    }

    /**
     * @return mixed
     */
    public function getClasificacionRiesgoRel()
    {
        return $this->clasificacionRiesgoRel;
    }

    /**
     * @param mixed $clasificacionRiesgoRel
     */
    public function setClasificacionRiesgoRel($clasificacionRiesgoRel): void
    {
        $this->clasificacionRiesgoRel = $clasificacionRiesgoRel;
    }

    /**
     * @return mixed
     */
    public function getRhuSeleccionSolicitudRel()
    {
        return $this->rhuSeleccionSolicitudRel;
    }

    /**
     * @param mixed $rhuSeleccionSolicitudRel
     */
    public function setRhuSeleccionSolicitudRel($rhuSeleccionSolicitudRel): void
    {
        $this->rhuSeleccionSolicitudRel = $rhuSeleccionSolicitudRel;
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
    public function getReligionRel()
    {
        return $this->religionRel;
    }

    /**
     * @param mixed $religionRel
     */
    public function setReligionRel($religionRel): void
    {
        $this->religionRel = $religionRel;
    }



}
