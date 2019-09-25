<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAcreditacionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuAcreditacion
{
    public $infoLog = [
        "primaryKey" => "codigoAcreditacionPk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_acreditacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAcreditacionPk;

    /**
     * @ORM\Column(name="codigo_acreditacion_tipo_fk", type="integer", nullable=true)
     */
    private $codigoAcreditacionTipoFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_vence_curso", type="date", nullable=true)
     */
    private $fechaVenceCurso;

    /**
     * @ORM\Column(name="fecha_validacion", type="date", nullable=true)
     */
    private $fechaValidacion;

    /**
     * @ORM\Column(name="fecha_acreditacion", type="date", nullable=true)
     */
    private $fechaAcreditacion;

    /**
     * @ORM\Column(name="fecha_vencimiento", type="date", nullable=true)
     */
    private $fechaVencimiento;

    /**
     * @ORM\Column(name="codigo_academia_fk", type="integer", nullable=true)
     */
    private $codigoAcademiaFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="numero_registro", type="string", length=20, nullable=true)
     */
    private $numeroRegistro;

    /**
     * @ORM\Column(name="numero_validacion", type="string", length=20, nullable=true)
     */
    private $numeroValidacion;

    /**
     * @ORM\Column(name="numero_acreditacion", type="string", length=20, nullable=true)
     */
    private $numeroAcreditacion;

    /**
     * @ORM\Column(name="codigo_acreditacion_rechazo_fk", type="integer", nullable=true)
     */
    private $codigoAcreditacionRechazoFk;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="motivo_rechazo", type="string", length=200, nullable=true)
     */
    private $motivoRechazo;

    /**
     * @ORM\Column(name="detalle_validacion", type="string", length=150, nullable=true)
     */
    private $detalleValidacion;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="estado_validado", type="boolean")
     */
    private $estadoValidado = false;

    /**
     * @ORM\Column(name="estado_acreditado", type="boolean")
     */
    private $estadoAcreditado = false;

    /**
     * @ORM\Column(name="estado_rechazado", type="boolean")
     */
    private $estadoRechazado = false;

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
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="acreditacionesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuAcademia", inversedBy="acreditacionesAcademiaRel")
     * @ORM\JoinColumn(name="codigo_academia_fk", referencedColumnName="codigo_academia_pk")
     */
    protected $academiaRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuAcreditacionTipo", inversedBy="acreditacionesAcreditacionTipoRel")
     * @ORM\JoinColumn(name="codigo_acreditacion_tipo_fk", referencedColumnName="codigo_acreditacion_tipo_pk")
     */
    protected $acreditacionTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoAcreditacionPk()
    {
        return $this->codigoAcreditacionPk;
    }

    /**
     * @param mixed $codigoAcreditacionPk
     */
    public function setCodigoAcreditacionPk($codigoAcreditacionPk): void
    {
        $this->codigoAcreditacionPk = $codigoAcreditacionPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoAcreditacionTipoFk()
    {
        return $this->codigoAcreditacionTipoFk;
    }

    /**
     * @param mixed $codigoAcreditacionTipoFk
     */
    public function setCodigoAcreditacionTipoFk($codigoAcreditacionTipoFk): void
    {
        $this->codigoAcreditacionTipoFk = $codigoAcreditacionTipoFk;
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
    public function getFechaVenceCurso()
    {
        return $this->fechaVenceCurso;
    }

    /**
     * @param mixed $fechaVenceCurso
     */
    public function setFechaVenceCurso($fechaVenceCurso): void
    {
        $this->fechaVenceCurso = $fechaVenceCurso;
    }

    /**
     * @return mixed
     */
    public function getFechaValidacion()
    {
        return $this->fechaValidacion;
    }

    /**
     * @param mixed $fechaValidacion
     */
    public function setFechaValidacion($fechaValidacion): void
    {
        $this->fechaValidacion = $fechaValidacion;
    }

    /**
     * @return mixed
     */
    public function getFechaAcreditacion()
    {
        return $this->fechaAcreditacion;
    }

    /**
     * @param mixed $fechaAcreditacion
     */
    public function setFechaAcreditacion($fechaAcreditacion): void
    {
        $this->fechaAcreditacion = $fechaAcreditacion;
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
    public function getCodigoAcademiaFk()
    {
        return $this->codigoAcademiaFk;
    }

    /**
     * @param mixed $codigoAcademiaFk
     */
    public function setCodigoAcademiaFk($codigoAcademiaFk): void
    {
        $this->codigoAcademiaFk = $codigoAcademiaFk;
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
    public function getNumeroRegistro()
    {
        return $this->numeroRegistro;
    }

    /**
     * @param mixed $numeroRegistro
     */
    public function setNumeroRegistro($numeroRegistro): void
    {
        $this->numeroRegistro = $numeroRegistro;
    }

    /**
     * @return mixed
     */
    public function getNumeroValidacion()
    {
        return $this->numeroValidacion;
    }

    /**
     * @param mixed $numeroValidacion
     */
    public function setNumeroValidacion($numeroValidacion): void
    {
        $this->numeroValidacion = $numeroValidacion;
    }

    /**
     * @return mixed
     */
    public function getNumeroAcreditacion()
    {
        return $this->numeroAcreditacion;
    }

    /**
     * @param mixed $numeroAcreditacion
     */
    public function setNumeroAcreditacion($numeroAcreditacion): void
    {
        $this->numeroAcreditacion = $numeroAcreditacion;
    }

    /**
     * @return mixed
     */
    public function getCodigoAcreditacionRechazoFk()
    {
        return $this->codigoAcreditacionRechazoFk;
    }

    /**
     * @param mixed $codigoAcreditacionRechazoFk
     */
    public function setCodigoAcreditacionRechazoFk($codigoAcreditacionRechazoFk): void
    {
        $this->codigoAcreditacionRechazoFk = $codigoAcreditacionRechazoFk;
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
    public function getDetalleValidacion()
    {
        return $this->detalleValidacion;
    }

    /**
     * @param mixed $detalleValidacion
     */
    public function setDetalleValidacion($detalleValidacion): void
    {
        $this->detalleValidacion = $detalleValidacion;
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
    public function getEstadoValidado()
    {
        return $this->estadoValidado;
    }

    /**
     * @param mixed $estadoValidado
     */
    public function setEstadoValidado($estadoValidado): void
    {
        $this->estadoValidado = $estadoValidado;
    }

    /**
     * @return mixed
     */
    public function getEstadoAcreditado()
    {
        return $this->estadoAcreditado;
    }

    /**
     * @param mixed $estadoAcreditado
     */
    public function setEstadoAcreditado($estadoAcreditado): void
    {
        $this->estadoAcreditado = $estadoAcreditado;
    }

    /**
     * @return mixed
     */
    public function getEstadoRechazado()
    {
        return $this->estadoRechazado;
    }

    /**
     * @param mixed $estadoRechazado
     */
    public function setEstadoRechazado($estadoRechazado): void
    {
        $this->estadoRechazado = $estadoRechazado;
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
    public function getAcademiaRel()
    {
        return $this->academiaRel;
    }

    /**
     * @param mixed $academiaRel
     */
    public function setAcademiaRel($academiaRel): void
    {
        $this->academiaRel = $academiaRel;
    }

    /**
     * @return mixed
     */
    public function getAcreditacionTipoRel()
    {
        return $this->acreditacionTipoRel;
    }

    /**
     * @param mixed $acreditacionTipoRel
     */
    public function setAcreditacionTipoRel($acreditacionTipoRel): void
    {
        $this->acreditacionTipoRel = $acreditacionTipoRel;
    }

    /**
     * @return mixed
     */
    public function getMotivoRechazo()
    {
        return $this->motivoRechazo;
    }

    /**
     * @param mixed $motivoRechazo
     */
    public function setMotivoRechazo($motivoRechazo): void
    {
        $this->motivoRechazo = $motivoRechazo;
    }


}