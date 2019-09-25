<?php


namespace App\Entity\RecursoHumano;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuDesempenoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */

class   RhuDesempeno
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_desempeno_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDesempenoPk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */
    private $codigoCargoFk;

    /**
     * @ORM\Column(name="dependencia_evaluado", type="string", length=80, nullable=true)
     * @Assert\Length(
     *     max = 80,
     *     maxMessage="El campo no puede contener mas de 80 caracteres"
     * )
     */
    private $dependenciaEvaluado;

    /**
     * @ORM\Column(name="jefe_evalua", type="string", length=80, nullable=true)
     * @Assert\Length(
     *     max = 80,
     *     maxMessage="El campo no puede contener mas de 80 caracteres"
     * )
     */
    private $jefeEvalua;

    /**
     * @ORM\Column(name="dependencia_evalua", type="string", length=80, nullable=true)
     * @Assert\Length(
     *     max = 80,
     *     maxMessage="El campo no puede contener mas de 80 caracteres"
     * )
     */
    private $dependenciaEvalua;

    /**
     * @ORM\Column(name="cargo_jefe_evalua", type="string", length=80, nullable=true)
     * @Assert\Length(
     *     max = 80,
     *     maxMessage="El campo no puede contener mas de 80 caracteres"
     * )
     */
    private $cargoJefeEvalua;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="observaciones", type="string", length=300, nullable=true)v
     * @Assert\Length(
     *     max = 300,
     *     maxMessage="El campo no puede contener mas de 300 caracteres"
     * )
     */
    private $observaciones;

    /**
     * @ORM\Column(name="aspectos_mejorar", type="string", length=300, nullable=true)
     * @Assert\Length(
     *     max = 300,
     *     maxMessage="El campo no puede contener mas de 300 caracteres"
     * )
     */
    private $aspectosMejorar;

    /**
     * @ORM\Column(name="area_profesional", type="float")
     */
    private $areaProfesional = 0;

    /**
     * @ORM\Column(name="compromiso", type="float")
     */
    private $compromiso = 0;

    /**
     * @ORM\Column(name="urbanidad", type="float")
     */
    private $urbanidad = 0;

    /**
     * @ORM\Column(name="valores", type="float")
     */
    private $valores = 0;

    /**
     * @ORM\Column(name="orientacion_cliente", type="float")
     */
    private $orientacionCliente = 0;

    /**
     * @ORM\Column(name="orientacion_resultados", type="float")
     */
    private $orientacionResultados = 0;

    /**
     * @ORM\Column(name="construccion_mantenimiento_relaciones", type="float")
     */
    private $construccionMantenimientoRelaciones = 0;

    /**
     * @ORM\Column(name="subtotal1", type="float")
     */
    private $subTotal1 = 0;

    /**
     * @ORM\Column(name="subtotal2", type="float")
     */
    private $subTotal2 = 0;

    /**
     * @ORM\Column(name="total_desempeno", type="float")
     */
    private $totalDesempeno = 0;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */
    private $estadoAutorizado = 0;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */
    private $estadoAprobado = 0;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean")
     */
    private $estadoAnulado = 0;

    /**
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */
    private $estadoCerrado = 0;

    /**
     * @ORM\Column(name="inconsistencia", type="boolean")
     */
    private $inconsistencia = 0;

    /**
     * @return mixed
     */
    public function getCodigoDesempenoPk()
    {
        return $this->codigoDesempenoPk;
    }

    /**
     * @param mixed $codigoDesempenoPk
     */
    public function setCodigoDesempenoPk($codigoDesempenoPk): void
    {
        $this->codigoDesempenoPk = $codigoDesempenoPk;
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
    public function getDependenciaEvaluado()
    {
        return $this->dependenciaEvaluado;
    }

    /**
     * @param mixed $dependenciaEvaluado
     */
    public function setDependenciaEvaluado($dependenciaEvaluado): void
    {
        $this->dependenciaEvaluado = $dependenciaEvaluado;
    }

    /**
     * @return mixed
     */
    public function getJefeEvalua()
    {
        return $this->jefeEvalua;
    }

    /**
     * @param mixed $jefeEvalua
     */
    public function setJefeEvalua($jefeEvalua): void
    {
        $this->jefeEvalua = $jefeEvalua;
    }

    /**
     * @return mixed
     */
    public function getDependenciaEvalua()
    {
        return $this->dependenciaEvalua;
    }

    /**
     * @param mixed $dependenciaEvalua
     */
    public function setDependenciaEvalua($dependenciaEvalua): void
    {
        $this->dependenciaEvalua = $dependenciaEvalua;
    }

    /**
     * @return mixed
     */
    public function getCargoJefeEvalua()
    {
        return $this->cargoJefeEvalua;
    }

    /**
     * @param mixed $cargoJefeEvalua
     */
    public function setCargoJefeEvalua($cargoJefeEvalua): void
    {
        $this->cargoJefeEvalua = $cargoJefeEvalua;
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
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * @param mixed $observaciones
     */
    public function setObservaciones($observaciones): void
    {
        $this->observaciones = $observaciones;
    }

    /**
     * @return mixed
     */
    public function getAspectosMejorar()
    {
        return $this->aspectosMejorar;
    }

    /**
     * @param mixed $aspectosMejorar
     */
    public function setAspectosMejorar($aspectosMejorar): void
    {
        $this->aspectosMejorar = $aspectosMejorar;
    }

    /**
     * @return mixed
     */
    public function getAreaProfesional()
    {
        return $this->areaProfesional;
    }

    /**
     * @param mixed $areaProfesional
     */
    public function setAreaProfesional($areaProfesional): void
    {
        $this->areaProfesional = $areaProfesional;
    }

    /**
     * @return mixed
     */
    public function getCompromiso()
    {
        return $this->compromiso;
    }

    /**
     * @param mixed $compromiso
     */
    public function setCompromiso($compromiso): void
    {
        $this->compromiso = $compromiso;
    }

    /**
     * @return mixed
     */
    public function getUrbanidad()
    {
        return $this->urbanidad;
    }

    /**
     * @param mixed $urbanidad
     */
    public function setUrbanidad($urbanidad): void
    {
        $this->urbanidad = $urbanidad;
    }

    /**
     * @return mixed
     */
    public function getValores()
    {
        return $this->valores;
    }

    /**
     * @param mixed $valores
     */
    public function setValores($valores): void
    {
        $this->valores = $valores;
    }

    /**
     * @return mixed
     */
    public function getOrientacionCliente()
    {
        return $this->orientacionCliente;
    }

    /**
     * @param mixed $orientacionCliente
     */
    public function setOrientacionCliente($orientacionCliente): void
    {
        $this->orientacionCliente = $orientacionCliente;
    }

    /**
     * @return mixed
     */
    public function getOrientacionResultados()
    {
        return $this->orientacionResultados;
    }

    /**
     * @param mixed $orientacionResultados
     */
    public function setOrientacionResultados($orientacionResultados): void
    {
        $this->orientacionResultados = $orientacionResultados;
    }

    /**
     * @return mixed
     */
    public function getConstruccionMantenimientoRelaciones()
    {
        return $this->construccionMantenimientoRelaciones;
    }

    /**
     * @param mixed $construccionMantenimientoRelaciones
     */
    public function setConstruccionMantenimientoRelaciones($construccionMantenimientoRelaciones): void
    {
        $this->construccionMantenimientoRelaciones = $construccionMantenimientoRelaciones;
    }

    /**
     * @return mixed
     */
    public function getSubTotal1()
    {
        return $this->subTotal1;
    }

    /**
     * @param mixed $subTotal1
     */
    public function setSubTotal1($subTotal1): void
    {
        $this->subTotal1 = $subTotal1;
    }

    /**
     * @return mixed
     */
    public function getSubTotal2()
    {
        return $this->subTotal2;
    }

    /**
     * @param mixed $subTotal2
     */
    public function setSubTotal2($subTotal2): void
    {
        $this->subTotal2 = $subTotal2;
    }

    /**
     * @return mixed
     */
    public function getTotalDesempeno()
    {
        return $this->totalDesempeno;
    }

    /**
     * @param mixed $totalDesempeno
     */
    public function setTotalDesempeno($totalDesempeno): void
    {
        $this->totalDesempeno = $totalDesempeno;
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
    public function getInconsistencia()
    {
        return $this->inconsistencia;
    }

    /**
     * @param mixed $inconsistencia
     */
    public function setInconsistencia($inconsistencia): void
    {
        $this->inconsistencia = $inconsistencia;
    }
}