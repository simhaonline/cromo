<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuEstudioRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuEstudio
{
    public $infoLog = [
        "primaryKey" => "codigoEstudioPk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_estudio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEstudioPk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;


    /**
     * @ORM\Column(name="codigo_estudio_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoEstudioTipoFk;

    /**
     * @ORM\Column(name="institucion", type="string", length=150, nullable=true)
     */
    private $institucion;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="titulo", type="string", length=120, nullable=true)
     */
    private $titulo;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_inicio", type="date", nullable=true)
     */
    private $fechaInicio;

    /**
     * @ORM\Column(name="fecha_terminacion", type="date", nullable=true)
     */
    private $fechaTerminacion;

    /**
     * @ORM\Column(name="fecha_vencimiento_curso", type="date", nullable=true)
     */

    private $fechaVencimientoCurso;

    /**
     * @ORM\Column(name="fecha_inicio_acreditacion", type="date", nullable=true)
     */
    private $fechaInicioAcreditacion;

    /**
     * @ORM\Column(name="fecha_vencimiento_acreditacion", type="date", nullable=true)
     */
    private $fechaVencimientoAcreditacion;

    /**
     * @ORM\Column(name="validar_vencimiento", type="boolean",options={"default" : false}, nullable=true)
     */
    private $validarVencimiento = false;

    /**
     * @ORM\Column(name="codigo_grado_bachiller_fk", type="integer", nullable=true)
     */
    private $codigoGradoBachillerFk;

    /**
     * @ORM\Column(name="codigo_academia_fk", type="integer", nullable=true)
     */
    private $codigoAcademiaFk;

    /**
     * @ORM\Column(name="graduado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $graduado = false;

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
     * @ORM\Column(name="numero_registro", type="string", length=60, nullable=true)
     */
    private $numeroRegistro;

    /**
     * @ORM\Column(name="numero_acreditacion", type="string", length=60, nullable=true)
     */
    private $numeroAcreditacion;

    /**
     * @ORM\Column(name="codigo_estudio_tipo_acreditacion_fk", type="integer", nullable=true)
     */
    private $codigoEstudioTipoAcreditacionFk;

    /**
     * @ORM\Column(name="codigo_estudio_estado_fk", type="integer", nullable=true)
     */
    private $codigoEstudioEstadoFk;

    /**
     * @ORM\Column(name="codigo_estudio_estado_invalido_fk", type="integer", nullable=true)
     */
    private $codigoEstudioEstadoInvalidoFk;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="fecha_estado", type="date", nullable=true)
     */

    private $fechaEstado;

    /**
     * @ORM\Column(name="fecha_estado_invalido", type="date", nullable=true)
     */

    private $fechaEstadoInvalido;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="estudiosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEstudioTipo", inversedBy="estudiosEstudioTipoRel")
     * @ORM\JoinColumn(name="codigo_estudio_tipo_fk", referencedColumnName="codigo_estudio_tipo_pk")
     */
    protected $estudioTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCiudad", inversedBy="rhuEstudiosCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;

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
    public function getCodigoEstudioPk()
    {
        return $this->codigoEstudioPk;
    }

    /**
     * @param mixed $codigoEstudioPk
     */
    public function setCodigoEstudioPk($codigoEstudioPk): void
    {
        $this->codigoEstudioPk = $codigoEstudioPk;
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
    public function getInstitucion()
    {
        return $this->institucion;
    }

    /**
     * @param mixed $institucion
     */
    public function setInstitucion($institucion): void
    {
        $this->institucion = $institucion;
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
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * @param mixed $titulo
     */
    public function setTitulo($titulo): void
    {
        $this->titulo = $titulo;
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
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * @param mixed $fechaInicio
     */
    public function setFechaInicio($fechaInicio): void
    {
        $this->fechaInicio = $fechaInicio;
    }

    /**
     * @return mixed
     */
    public function getFechaTerminacion()
    {
        return $this->fechaTerminacion;
    }

    /**
     * @param mixed $fechaTerminacion
     */
    public function setFechaTerminacion($fechaTerminacion): void
    {
        $this->fechaTerminacion = $fechaTerminacion;
    }

    /**
     * @return mixed
     */
    public function getFechaVencimientoCurso()
    {
        return $this->fechaVencimientoCurso;
    }

    /**
     * @param mixed $fechaVencimientoCurso
     */
    public function setFechaVencimientoCurso($fechaVencimientoCurso): void
    {
        $this->fechaVencimientoCurso = $fechaVencimientoCurso;
    }

    /**
     * @return mixed
     */
    public function getFechaInicioAcreditacion()
    {
        return $this->fechaInicioAcreditacion;
    }

    /**
     * @param mixed $fechaInicioAcreditacion
     */
    public function setFechaInicioAcreditacion($fechaInicioAcreditacion): void
    {
        $this->fechaInicioAcreditacion = $fechaInicioAcreditacion;
    }

    /**
     * @return mixed
     */
    public function getFechaVencimientoAcreditacion()
    {
        return $this->fechaVencimientoAcreditacion;
    }

    /**
     * @param mixed $fechaVencimientoAcreditacion
     */
    public function setFechaVencimientoAcreditacion($fechaVencimientoAcreditacion): void
    {
        $this->fechaVencimientoAcreditacion = $fechaVencimientoAcreditacion;
    }

    /**
     * @return mixed
     */
    public function getValidarVencimiento()
    {
        return $this->validarVencimiento;
    }

    /**
     * @param mixed $validarVencimiento
     */
    public function setValidarVencimiento($validarVencimiento): void
    {
        $this->validarVencimiento = $validarVencimiento;
    }

    /**
     * @return mixed
     */
    public function getCodigoGradoBachillerFk()
    {
        return $this->codigoGradoBachillerFk;
    }

    /**
     * @param mixed $codigoGradoBachillerFk
     */
    public function setCodigoGradoBachillerFk($codigoGradoBachillerFk): void
    {
        $this->codigoGradoBachillerFk = $codigoGradoBachillerFk;
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
    public function getGraduado()
    {
        return $this->graduado;
    }

    /**
     * @param mixed $graduado
     */
    public function setGraduado($graduado): void
    {
        $this->graduado = $graduado;
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
    public function getCodigoEstudioTipoAcreditacionFk()
    {
        return $this->codigoEstudioTipoAcreditacionFk;
    }

    /**
     * @param mixed $codigoEstudioTipoAcreditacionFk
     */
    public function setCodigoEstudioTipoAcreditacionFk($codigoEstudioTipoAcreditacionFk): void
    {
        $this->codigoEstudioTipoAcreditacionFk = $codigoEstudioTipoAcreditacionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEstudioEstadoFk()
    {
        return $this->codigoEstudioEstadoFk;
    }

    /**
     * @param mixed $codigoEstudioEstadoFk
     */
    public function setCodigoEstudioEstadoFk($codigoEstudioEstadoFk): void
    {
        $this->codigoEstudioEstadoFk = $codigoEstudioEstadoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEstudioEstadoInvalidoFk()
    {
        return $this->codigoEstudioEstadoInvalidoFk;
    }

    /**
     * @param mixed $codigoEstudioEstadoInvalidoFk
     */
    public function setCodigoEstudioEstadoInvalidoFk($codigoEstudioEstadoInvalidoFk): void
    {
        $this->codigoEstudioEstadoInvalidoFk = $codigoEstudioEstadoInvalidoFk;
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
    public function getFechaEstado()
    {
        return $this->fechaEstado;
    }

    /**
     * @param mixed $fechaEstado
     */
    public function setFechaEstado($fechaEstado): void
    {
        $this->fechaEstado = $fechaEstado;
    }

    /**
     * @return mixed
     */
    public function getFechaEstadoInvalido()
    {
        return $this->fechaEstadoInvalido;
    }

    /**
     * @param mixed $fechaEstadoInvalido
     */
    public function setFechaEstadoInvalido($fechaEstadoInvalido): void
    {
        $this->fechaEstadoInvalido = $fechaEstadoInvalido;
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



}
