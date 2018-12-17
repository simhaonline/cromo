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
     * @ORM\Column(name="codigo_grupo_pago_fk", type="integer", nullable=true)
     */
    private $codigoGrupoPagoFk;

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
    protected $rhuSeleccionSolicitudRel;

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
     * @return mixed
     */
    public function getCodigoGrupoPagoFk()
    {
        return $this->codigoGrupoPagoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCargoFk()
    {
        return $this->codigoCargoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSolicitudMotivoFk()
    {
        return $this->codigoSolicitudMotivoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoExperienciaSolicitudFk()
    {
        return $this->codigoExperienciaSolicitudFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEstadoCivilFk()
    {
        return $this->codigoEstadoCivilFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCiudadFk()
    {
        return $this->codigoCiudadFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEstudioTipoFk()
    {
        return $this->codigoEstudioTipoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoClasificacionRiesgoFk()
    {
        return $this->codigoClasificacionRiesgoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSexoFk()
    {
        return $this->codigoSexoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoReligionFk()
    {
        return $this->codigoReligionFk;
    }

    /**
     * @return mixed
     */
    public function getDisponbilidad()
    {
        return $this->disponbilidad;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @return mixed
     */
    public function getFechaContratacion()
    {
        return $this->fechaContratacion;
    }

    /**
     * @return mixed
     */
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @return mixed
     */
    public function getCantidadSolicitada()
    {
        return $this->cantidadSolicitada;
    }

    /**
     * @return mixed
     */
    public function getVrSalario()
    {
        return $this->VrSalario;
    }

    /**
     * @return mixed
     */
    public function getVrNoSalarial()
    {
        return $this->VrNoSalarial;
    }

    /**
     * @return mixed
     */
    public function getSalarioFijo()
    {
        return $this->salarioFijo;
    }

    /**
     * @return mixed
     */
    public function getSalarioVariable()
    {
        return $this->salarioVariable;
    }

    /**
     * @return mixed
     */
    public function getFechaPruebas()
    {
        return $this->fechaPruebas;
    }

    /**
     * @return mixed
     */
    public function getEdadMinima()
    {
        return $this->edadMinima;
    }

    /**
     * @return mixed
     */
    public function getEdadMaxima()
    {
        return $this->edadMaxima;
    }

    /**
     * @return mixed
     */
    public function getNumeroHijos()
    {
        return $this->numeroHijos;
    }

    /**
     * @return mixed
     */
    public function getCodigoTipoVehiculoFk()
    {
        return $this->codigoTipoVehiculoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoLicenciaCarroFk()
    {
        return $this->codigoLicenciaCarroFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoLicenciaMotoFk()
    {
        return $this->codigoLicenciaMotoFk;
    }

    /**
     * @return mixed
     */
    public function getExperienciaSolicitud()
    {
        return $this->experienciaSolicitud;
    }

    /**
     * @return mixed
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * @return mixed
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * @return mixed
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * @return mixed
     */
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }

    /**
     * @return mixed
     */
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * @return mixed
     */
    public function getCargoRel()
    {
        return $this->cargoRel;
    }

    /**
     * @return mixed
     */
    public function getSolicitudMotivoRel()
    {
        return $this->solicitudMotivoRel;
    }

    /**
     * @return mixed
     */
    public function getSolicitudExperienciaRel()
    {
        return $this->solicitudExperienciaRel;
    }

    /**
     * @return mixed
     */
    public function getEstadoCivilRel()
    {
        return $this->estadoCivilRel;
    }

    /**
     * @return mixed
     */
    public function getCiudadRel()
    {
        return $this->ciudadRel;
    }

    /**
     * @return mixed
     */
    public function getEstudioTipoRel()
    {
        return $this->estudioTipoRel;
    }

    /**
     * @return mixed
     */
    public function getClasificacionRiesgoRel()
    {
        return $this->clasificacionRiesgoRel;
    }

    /**
     * @return mixed
     */
    public function getRhuSeleccionSolicitudRel()
    {
        return $this->rhuSeleccionSolicitudRel;
    }

    /**
     * @return mixed
     */
    public function getSexoRel()
    {
        return $this->sexoRel;
    }

    /**
     * @return mixed
     */
    public function getReligionRel()
    {
        return $this->religionRel;
    }
}
