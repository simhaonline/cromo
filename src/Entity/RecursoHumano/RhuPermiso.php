<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuPermisoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuPermiso
{
    public $infoLog = [
        "primaryKey" => "codigoPermisoPk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_permiso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPermisoPk;

    /**
     * @ORM\Column(name="codigo_permiso_tipo_fk", type="integer", nullable=true)
     */
    private $codigoPermisoTipoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */
    private $codigoCargoFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="hora_salida", type="time", nullable=true)
     */
    private $horaSalida;

    /**
     * @ORM\Column(name="hora_llegada", type="time", nullable=true)
     */
    private $horaLlegada;

    /**
     * @ORM\Column(name="horas", type="float", nullable=true)
     */
    private $horas = 0;

    /**
     * @ORM\Column(name="motivo", type="text", nullable=true)
     */
    private $motivo;

    /**
     * @ORM\Column(name="constancia", type="boolean", options={"default" : false}, nullable=true)
     */
    private $constancia = false;

    /**
     * @ORM\Column(name="jefe_autoriza", type="string", length=120, nullable=true)
     */
    private $jefeAutoriza;

    /**
     * @ORM\Column(name="afecta_horario", type="boolean", options={"default" : false}, nullable=true)
     */
    private $afectaHorario = false;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean",options={"default" : false}, nullable=true)
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
     * @ORM\Column(name="comentario", type="string", length=1500, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="comentarios", type="string", length=1000, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\ManyToOne(targetEntity="RhuPermisoTipo", inversedBy="permisosPermisoTipoRel")
     * @ORM\JoinColumn(name="codigo_permiso_tipo_fk", referencedColumnName="codigo_permiso_tipo_pk")
     */
    protected $permisoTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="permisosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

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
    public function getCodigoPermisoPk()
    {
        return $this->codigoPermisoPk;
    }

    /**
     * @param mixed $codigoPermisoPk
     */
    public function setCodigoPermisoPk($codigoPermisoPk): void
    {
        $this->codigoPermisoPk = $codigoPermisoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPermisoTipoFk()
    {
        return $this->codigoPermisoTipoFk;
    }

    /**
     * @param mixed $codigoPermisoTipoFk
     */
    public function setCodigoPermisoTipoFk($codigoPermisoTipoFk): void
    {
        $this->codigoPermisoTipoFk = $codigoPermisoTipoFk;
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
    public function getHoraSalida()
    {
        return $this->horaSalida;
    }

    /**
     * @param mixed $horaSalida
     */
    public function setHoraSalida($horaSalida): void
    {
        $this->horaSalida = $horaSalida;
    }

    /**
     * @return mixed
     */
    public function getHoraLlegada()
    {
        return $this->horaLlegada;
    }

    /**
     * @param mixed $horaLlegada
     */
    public function setHoraLlegada($horaLlegada): void
    {
        $this->horaLlegada = $horaLlegada;
    }

    /**
     * @return mixed
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * @param mixed $horas
     */
    public function setHoras($horas): void
    {
        $this->horas = $horas;
    }

    /**
     * @return mixed
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * @param mixed $motivo
     */
    public function setMotivo($motivo): void
    {
        $this->motivo = $motivo;
    }

    /**
     * @return mixed
     */
    public function getConstancia()
    {
        return $this->constancia;
    }

    /**
     * @param mixed $constancia
     */
    public function setConstancia($constancia): void
    {
        $this->constancia = $constancia;
    }

    /**
     * @return mixed
     */
    public function getJefeAutoriza()
    {
        return $this->jefeAutoriza;
    }

    /**
     * @param mixed $jefeAutoriza
     */
    public function setJefeAutoriza($jefeAutoriza): void
    {
        $this->jefeAutoriza = $jefeAutoriza;
    }

    /**
     * @return mixed
     */
    public function getAfectaHorario()
    {
        return $this->afectaHorario;
    }

    /**
     * @param mixed $afectaHorario
     */
    public function setAfectaHorario($afectaHorario): void
    {
        $this->afectaHorario = $afectaHorario;
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
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
    }

    /**
     * @return mixed
     */
    public function getPermisoTipoRel()
    {
        return $this->permisoTipoRel;
    }

    /**
     * @param mixed $permisoTipoRel
     */
    public function setPermisoTipoRel($permisoTipoRel): void
    {
        $this->permisoTipoRel = $permisoTipoRel;
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