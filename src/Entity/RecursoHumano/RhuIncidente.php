<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuIncidenteRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuIncidente
{
    public $infoLog = [
        "primaryKey" => "codigoIncidentePk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_incidente_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoIncidentePk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_incidente_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoIncidenteTipoFk;

    /**
     * @ORM\Column(name="reporta_supervisor", type="boolean",options={"default":false}, nullable=true)
     */
    private $reportaSupervisor = false;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean",options={"default":false}, nullable=true)
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean",options={"default":false}, nullable=true)
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean",options={"default":false}, nullable=true)
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="elementos_adjuntos", type="boolean",options={"default":false}, nullable=true)
     */
    private $elementosAdjuntos = false;

    /**
     * @ORM\Column(name="falta_reglamento", type="boolean",options={"default":false}, nullable=true)
     */
    private $faltaReglamento = false;

    /**
     * @ORM\Column(name="reitera_falta", type="boolean",options={"default":false}, nullable=true)
     */
    private $reiteraFalta = false;

    /**
     * @ORM\Column(name="capacitacion", type="boolean",options={"default":false}, nullable=true)
     */
    private $capacitacion = false;

    /**
     * @ORM\Column(name="impacta_empresa", type="boolean",options={"default":false}, nullable=true)
     */
    private $impactaEmpresa = false;

    /**
     * @ORM\Column(name="impacta_cliente", type="boolean",options={"default":false}, nullable=true)
     */
    private $impactaCliente = false;

    /**
     * @ORM\Column(name="estado_cerrado", type="boolean",options={"default":false}, nullable=true)
     */
    private $estadoCerrado = false;

    /**
     * @ORM\Column(name="estado_presenta_descargo", type="boolean",options={"default":false}, nullable=true)
     */
    private $estadoPresentaDescargo = false;

    /**
     * @ORM\Column(name="estado_citado", type="boolean",options={"default":false}, nullable=true)
     */
    private $estadoCitado = false;

    /**
     * @ORM\Column(name="fecha_hora_notificacion", type="datetime", nullable=true)
     */
    private $fechaHoraNotificacion;

    /**
     * @ORM\Column(name="fecha_hora_citacion_descargo", type="datetime", nullable=true)
     */
    private $fechaHoraCitacionDescargo;

    /**
     * @ORM\Column(name="fecha_novedad", type="date", nullable=true)
     */
    private $fechaNovedad;

    /**
     * @ORM\Column(name="nombre_reporta", type="string", length=255, nullable=true)
     */
    private $nombreReporta;

    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */
    private $codigoUsuario;

    /**
     * @ORM\Column(name="comentarios", type="text", nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(name="codigo_disciplinario_fk", type="integer", nullable=true)
     */
    private $codigoDisciplinarioFk;

    /**
     * @ORM\Column(name="estado_atendido", type="boolean", nullable=true)
     */
    private $estadoAtendido = false;

    /**
     * @ORM\Column(name="cargo_reporta", type="string", length=255, nullable=true)
     */
    private $cargoReporta;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="incidentesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuIncidenteTipo", inversedBy="incidentesIncidenteTipoRel")
     * @ORM\JoinColumn(name="codigo_incidente_tipo_fk", referencedColumnName="codigo_incidente_tipo_pk")
     */
    protected $incidenteTipoRel;

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
    public function getCodigoIncidentePk()
    {
        return $this->codigoIncidentePk;
    }

    /**
     * @param mixed $codigoIncidentePk
     */
    public function setCodigoIncidentePk($codigoIncidentePk): void
    {
        $this->codigoIncidentePk = $codigoIncidentePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEmpleadoFk()
    {
        return $this->codigoEmpleadoFk;
    }

    /**
     * @param mixed $codigoEmpleadoFk
     */
    public function setCodigoEmpleadoFk($codigoEmpleadoFk): void
    {
        $this->codigoEmpleadoFk = $codigoEmpleadoFk;
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
    public function getCodigoIncidenteTipoFk()
    {
        return $this->codigoIncidenteTipoFk;
    }

    /**
     * @param mixed $codigoIncidenteTipoFk
     */
    public function setCodigoIncidenteTipoFk($codigoIncidenteTipoFk): void
    {
        $this->codigoIncidenteTipoFk = $codigoIncidenteTipoFk;
    }

    /**
     * @return mixed
     */
    public function getReportaSupervisor()
    {
        return $this->reportaSupervisor;
    }

    /**
     * @param mixed $reportaSupervisor
     */
    public function setReportaSupervisor($reportaSupervisor): void
    {
        $this->reportaSupervisor = $reportaSupervisor;
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
    public function getElementosAdjuntos()
    {
        return $this->elementosAdjuntos;
    }

    /**
     * @param mixed $elementosAdjuntos
     */
    public function setElementosAdjuntos($elementosAdjuntos): void
    {
        $this->elementosAdjuntos = $elementosAdjuntos;
    }

    /**
     * @return mixed
     */
    public function getFaltaReglamento()
    {
        return $this->faltaReglamento;
    }

    /**
     * @param mixed $faltaReglamento
     */
    public function setFaltaReglamento($faltaReglamento): void
    {
        $this->faltaReglamento = $faltaReglamento;
    }

    /**
     * @return mixed
     */
    public function getReiteraFalta()
    {
        return $this->reiteraFalta;
    }

    /**
     * @param mixed $reiteraFalta
     */
    public function setReiteraFalta($reiteraFalta): void
    {
        $this->reiteraFalta = $reiteraFalta;
    }

    /**
     * @return mixed
     */
    public function getCapacitacion()
    {
        return $this->capacitacion;
    }

    /**
     * @param mixed $capacitacion
     */
    public function setCapacitacion($capacitacion): void
    {
        $this->capacitacion = $capacitacion;
    }

    /**
     * @return mixed
     */
    public function getImpactaEmpresa()
    {
        return $this->impactaEmpresa;
    }

    /**
     * @param mixed $impactaEmpresa
     */
    public function setImpactaEmpresa($impactaEmpresa): void
    {
        $this->impactaEmpresa = $impactaEmpresa;
    }

    /**
     * @return mixed
     */
    public function getImpactaCliente()
    {
        return $this->impactaCliente;
    }

    /**
     * @param mixed $impactaCliente
     */
    public function setImpactaCliente($impactaCliente): void
    {
        $this->impactaCliente = $impactaCliente;
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
    public function getEstadoPresentaDescargo()
    {
        return $this->estadoPresentaDescargo;
    }

    /**
     * @param mixed $estadoPresentaDescargo
     */
    public function setEstadoPresentaDescargo($estadoPresentaDescargo): void
    {
        $this->estadoPresentaDescargo = $estadoPresentaDescargo;
    }

    /**
     * @return mixed
     */
    public function getEstadoCitado()
    {
        return $this->estadoCitado;
    }

    /**
     * @param mixed $estadoCitado
     */
    public function setEstadoCitado($estadoCitado): void
    {
        $this->estadoCitado = $estadoCitado;
    }

    /**
     * @return mixed
     */
    public function getFechaHoraNotificacion()
    {
        return $this->fechaHoraNotificacion;
    }

    /**
     * @param mixed $fechaHoraNotificacion
     */
    public function setFechaHoraNotificacion($fechaHoraNotificacion): void
    {
        $this->fechaHoraNotificacion = $fechaHoraNotificacion;
    }

    /**
     * @return mixed
     */
    public function getFechaHoraCitacionDescargo()
    {
        return $this->fechaHoraCitacionDescargo;
    }

    /**
     * @param mixed $fechaHoraCitacionDescargo
     */
    public function setFechaHoraCitacionDescargo($fechaHoraCitacionDescargo): void
    {
        $this->fechaHoraCitacionDescargo = $fechaHoraCitacionDescargo;
    }

    /**
     * @return mixed
     */
    public function getFechaNovedad()
    {
        return $this->fechaNovedad;
    }

    /**
     * @param mixed $fechaNovedad
     */
    public function setFechaNovedad($fechaNovedad): void
    {
        $this->fechaNovedad = $fechaNovedad;
    }

    /**
     * @return mixed
     */
    public function getNombreReporta()
    {
        return $this->nombreReporta;
    }

    /**
     * @param mixed $nombreReporta
     */
    public function setNombreReporta($nombreReporta): void
    {
        $this->nombreReporta = $nombreReporta;
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
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return mixed
     */
    public function getCodigoDisciplinarioFk()
    {
        return $this->codigoDisciplinarioFk;
    }

    /**
     * @param mixed $codigoDisciplinarioFk
     */
    public function setCodigoDisciplinarioFk($codigoDisciplinarioFk): void
    {
        $this->codigoDisciplinarioFk = $codigoDisciplinarioFk;
    }

    /**
     * @return mixed
     */
    public function getEstadoAtendido()
    {
        return $this->estadoAtendido;
    }

    /**
     * @param mixed $estadoAtendido
     */
    public function setEstadoAtendido($estadoAtendido): void
    {
        $this->estadoAtendido = $estadoAtendido;
    }

    /**
     * @return mixed
     */
    public function getCargoReporta()
    {
        return $this->cargoReporta;
    }

    /**
     * @param mixed $cargoReporta
     */
    public function setCargoReporta($cargoReporta): void
    {
        $this->cargoReporta = $cargoReporta;
    }

    /**
     * @return mixed
     */
    public function getEmpleadoRel()
    {
        return $this->empleadoRel;
    }

    /**
     * @param mixed $empleadoRel
     */
    public function setEmpleadoRel($empleadoRel): void
    {
        $this->empleadoRel = $empleadoRel;
    }

    /**
     * @return mixed
     */
    public function getIncidenteTipoRel()
    {
        return $this->incidenteTipoRel;
    }

    /**
     * @param mixed $incidenteTipoRel
     */
    public function setIncidenteTipoRel($incidenteTipoRel): void
    {
        $this->incidenteTipoRel = $incidenteTipoRel;
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



}