<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuPruebaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuPrueba
{
    public $infoLog = [
        "primaryKey" => "codigoPruebaPk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_prueba_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPruebaPk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_vence", type="date", nullable=true)
     */
    private $fechaVence;

    /**
     * @ORM\Column(name="codigo_visita_tipo_fk", type="integer", nullable=true)
     */
    private $codigoPruebaTipoFk;

    /**
     * @ORM\Column(name="validar_vencimiento", type="boolean",options={"default" : false}, nullable=true)
     */
    private $validarVencimiento = false;

    /**
     * @ORM\Column(name="resultado", type="string", length=50, nullable=true)
     */
    private $resultado;

    /**
     * @ORM\Column(name="resultado_cuantitativo", type="integer", nullable=true)
     */
    private $resultadoCuantitativo;

    /**
     * @ORM\Column(name="nombre_quien_hace_prueba", type="string", length=100, nullable=true)
     */
    private $nombreQuienHacePrueba;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado",type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="vr_total", type="float", nullable=true)
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="comentarios", type="text", nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\ManyToOne(targetEntity="RhuPruebaTipo", inversedBy="pruebasPruebaTipoRel")
     * @ORM\JoinColumn(name="codigo_prueba_tipo_fk", referencedColumnName="codigo_prueba_tipo_pk")
     */
    protected $pruebaTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="pruebasEmpleadoRel")
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
    public function getCodigoPruebaPk()
    {
        return $this->codigoPruebaPk;
    }

    /**
     * @param mixed $codigoPruebaPk
     */
    public function setCodigoPruebaPk($codigoPruebaPk): void
    {
        $this->codigoPruebaPk = $codigoPruebaPk;
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
    public function getFechaVence()
    {
        return $this->fechaVence;
    }

    /**
     * @param mixed $fechaVence
     */
    public function setFechaVence($fechaVence): void
    {
        $this->fechaVence = $fechaVence;
    }

    /**
     * @return mixed
     */
    public function getCodigoPruebaTipoFk()
    {
        return $this->codigoPruebaTipoFk;
    }

    /**
     * @param mixed $codigoPruebaTipoFk
     */
    public function setCodigoPruebaTipoFk($codigoPruebaTipoFk): void
    {
        $this->codigoPruebaTipoFk = $codigoPruebaTipoFk;
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
    public function getResultado()
    {
        return $this->resultado;
    }

    /**
     * @param mixed $resultado
     */
    public function setResultado($resultado): void
    {
        $this->resultado = $resultado;
    }

    /**
     * @return mixed
     */
    public function getResultadoCuantitativo()
    {
        return $this->resultadoCuantitativo;
    }

    /**
     * @param mixed $resultadoCuantitativo
     */
    public function setResultadoCuantitativo($resultadoCuantitativo): void
    {
        $this->resultadoCuantitativo = $resultadoCuantitativo;
    }

    /**
     * @return mixed
     */
    public function getNombreQuienHacePrueba()
    {
        return $this->nombreQuienHacePrueba;
    }

    /**
     * @param mixed $nombreQuienHacePrueba
     */
    public function setNombreQuienHacePrueba($nombreQuienHacePrueba): void
    {
        $this->nombreQuienHacePrueba = $nombreQuienHacePrueba;
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
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * @param mixed $vrTotal
     */
    public function setVrTotal($vrTotal): void
    {
        $this->vrTotal = $vrTotal;
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
    public function getPruebaTipoRel()
    {
        return $this->pruebaTipoRel;
    }

    /**
     * @param mixed $pruebaTipoRel
     */
    public function setPruebaTipoRel($pruebaTipoRel): void
    {
        $this->pruebaTipoRel = $pruebaTipoRel;
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



}