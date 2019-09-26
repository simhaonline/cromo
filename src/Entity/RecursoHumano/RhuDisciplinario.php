<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuDisciplinarioRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuDisciplinario
{
    public $infoLog = [
        "primaryKey" => "codigoDisciplinarioPk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_disciplinario_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDisciplinarioPk;

    /**
     * @ORM\Column(name="codigo_disciplinario_tipo_fk", type="integer")
     */
    private $codigoDisciplinarioTipoFk;

    /**
     * @ORM\Column(name="codigo_disciplinario_motivo_fk", type="integer", nullable=true)
     */
    private $codigoDisciplinarioMotivoFk;

    /**
     * @ORM\Column(name="codigo_disciplinario_falta_fk", type="integer", nullable=true)
     */
    private $codigoDisciplinarioFaltaFk;

    /**
     * @ORM\Column(name="codigo_identificacion_fk", type="string", length=3, nullable=true)
     */
    private $codigoIdentificacionFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_notificacion", type="datetime", nullable=true)
     */
    private $fechaNotificacion;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="asunto", type="string", length=500, nullable=true)
     */
    private $asunto;


    /**
     * @ORM\Column(name="fecha_incidente", type="date", nullable=true)
     */
    private $fechaIncidente;

    /**
     * @ORM\Column(name="fecha_desde_sancion", type="date", nullable=true)
     */
    private $fechaDesdeSancion;

    /**
     * @ORM\Column(name="fecha_hasta_sancion", type="date", nullable=true)
     */
    private $fechaHastaSancion;

    /**
     * @ORM\Column(name="fecha_ingreso_trabajo", type="date", nullable=true)
     */
    private $fechaIngresoTrabajo;

    /**
     * @ORM\Column(name="dias_suspencion", type="string", length=100, nullable=true)
     */
    private $diasSuspencion;

    /**
     * @ORM\Column(name="reentrenamiento", type="boolean",options={"default" : false}, nullable=true)
     */
    private $reentrenamiento = false;

    /**
     * @ORM\Column(name="estado_suspendido",type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoSuspendido = false;

    /**
     * @ORM\Column(name="estado_procede",type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoProcede = false;

    /**
     * @ORM\Column(name="estado_autorizado",type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="comentarios", type="text", nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="disciplinariosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuDisciplinarioTipo", inversedBy="disciplinariosDisciplinarioTipoRel")
     * @ORM\JoinColumn(name="codigo_disciplinario_tipo_fk", referencedColumnName="codigo_disciplinario_tipo_pk")
     */
    protected $disciplinarioTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuDisciplinarioMotivo", inversedBy="disciplinariosDisciplinarioMotivoRel")
     * @ORM\JoinColumn(name="codigo_disciplinario_motivo_fk", referencedColumnName="codigo_disciplinario_motivo_pk")
     */
    protected $disciplinarioMotivoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuDisciplinarioFalta", inversedBy="disciplinarioRel")
     * @ORM\JoinColumn(name="codigo_disciplinario_falta_fk", referencedColumnName="codigo_disciplinario_falta_pk")
     */
    protected $disciplinariosFaltaRel;

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
    public function getCodigoDisciplinarioPk()
    {
        return $this->codigoDisciplinarioPk;
    }

    /**
     * @param mixed $codigoDisciplinarioPk
     */
    public function setCodigoDisciplinarioPk($codigoDisciplinarioPk): void
    {
        $this->codigoDisciplinarioPk = $codigoDisciplinarioPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoDisciplinarioTipoFk()
    {
        return $this->codigoDisciplinarioTipoFk;
    }

    /**
     * @param mixed $codigoDisciplinarioTipoFk
     */
    public function setCodigoDisciplinarioTipoFk($codigoDisciplinarioTipoFk): void
    {
        $this->codigoDisciplinarioTipoFk = $codigoDisciplinarioTipoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoDisciplinarioMotivoFk()
    {
        return $this->codigoDisciplinarioMotivoFk;
    }

    /**
     * @param mixed $codigoDisciplinarioMotivoFk
     */
    public function setCodigoDisciplinarioMotivoFk($codigoDisciplinarioMotivoFk): void
    {
        $this->codigoDisciplinarioMotivoFk = $codigoDisciplinarioMotivoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoDisciplinarioFaltaFk()
    {
        return $this->codigoDisciplinarioFaltaFk;
    }

    /**
     * @param mixed $codigoDisciplinarioFaltaFk
     */
    public function setCodigoDisciplinarioFaltaFk($codigoDisciplinarioFaltaFk): void
    {
        $this->codigoDisciplinarioFaltaFk = $codigoDisciplinarioFaltaFk;
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
    public function getFechaNotificacion()
    {
        return $this->fechaNotificacion;
    }

    /**
     * @param mixed $fechaNotificacion
     */
    public function setFechaNotificacion($fechaNotificacion): void
    {
        $this->fechaNotificacion = $fechaNotificacion;
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
    public function getAsunto()
    {
        return $this->asunto;
    }

    /**
     * @param mixed $asunto
     */
    public function setAsunto($asunto): void
    {
        $this->asunto = $asunto;
    }

    /**
     * @return mixed
     */
    public function getFechaIncidente()
    {
        return $this->fechaIncidente;
    }

    /**
     * @param mixed $fechaIncidente
     */
    public function setFechaIncidente($fechaIncidente): void
    {
        $this->fechaIncidente = $fechaIncidente;
    }

    /**
     * @return mixed
     */
    public function getFechaDesdeSancion()
    {
        return $this->fechaDesdeSancion;
    }

    /**
     * @param mixed $fechaDesdeSancion
     */
    public function setFechaDesdeSancion($fechaDesdeSancion): void
    {
        $this->fechaDesdeSancion = $fechaDesdeSancion;
    }

    /**
     * @return mixed
     */
    public function getFechaHastaSancion()
    {
        return $this->fechaHastaSancion;
    }

    /**
     * @param mixed $fechaHastaSancion
     */
    public function setFechaHastaSancion($fechaHastaSancion): void
    {
        $this->fechaHastaSancion = $fechaHastaSancion;
    }

    /**
     * @return mixed
     */
    public function getFechaIngresoTrabajo()
    {
        return $this->fechaIngresoTrabajo;
    }

    /**
     * @param mixed $fechaIngresoTrabajo
     */
    public function setFechaIngresoTrabajo($fechaIngresoTrabajo): void
    {
        $this->fechaIngresoTrabajo = $fechaIngresoTrabajo;
    }

    /**
     * @return mixed
     */
    public function getDiasSuspencion()
    {
        return $this->diasSuspencion;
    }

    /**
     * @param mixed $diasSuspencion
     */
    public function setDiasSuspencion($diasSuspencion): void
    {
        $this->diasSuspencion = $diasSuspencion;
    }

    /**
     * @return mixed
     */
    public function getReentrenamiento()
    {
        return $this->reentrenamiento;
    }

    /**
     * @param mixed $reentrenamiento
     */
    public function setReentrenamiento($reentrenamiento): void
    {
        $this->reentrenamiento = $reentrenamiento;
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
    public function getDisciplinarioTipoRel()
    {
        return $this->disciplinarioTipoRel;
    }

    /**
     * @param mixed $disciplinarioTipoRel
     */
    public function setDisciplinarioTipoRel($disciplinarioTipoRel): void
    {
        $this->disciplinarioTipoRel = $disciplinarioTipoRel;
    }

    /**
     * @return mixed
     */
    public function getDisciplinarioMotivoRel()
    {
        return $this->disciplinarioMotivoRel;
    }

    /**
     * @param mixed $disciplinarioMotivoRel
     */
    public function setDisciplinarioMotivoRel($disciplinarioMotivoRel): void
    {
        $this->disciplinarioMotivoRel = $disciplinarioMotivoRel;
    }

    /**
     * @return mixed
     */
    public function getDisciplinariosFaltaRel()
    {
        return $this->disciplinariosFaltaRel;
    }

    /**
     * @param mixed $disciplinariosFaltaRel
     */
    public function setDisciplinariosFaltaRel($disciplinariosFaltaRel): void
    {
        $this->disciplinariosFaltaRel = $disciplinariosFaltaRel;
    }

    /**
     * @return mixed
     */
    public function getEstadoSuspendido()
    {
        return $this->estadoSuspendido;
    }

    /**
     * @param mixed $estadoSuspendido
     */
    public function setEstadoSuspendido($estadoSuspendido): void
    {
        $this->estadoSuspendido = $estadoSuspendido;
    }

    /**
     * @return mixed
     */
    public function getEstadoProcede()
    {
        return $this->estadoProcede;
    }

    /**
     * @param mixed $estadoProcede
     */
    public function setEstadoProcede($estadoProcede): void
    {
        $this->estadoProcede = $estadoProcede;
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



}