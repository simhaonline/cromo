<?php


namespace App\Entity\RecursoHumano;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuDotacionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuDotacion
{
    public $infoLog = [
        "primaryKey" => "codigoDotacionPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_dotacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDotacionPk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="numero_interno_referencia", type="integer", nullable=true)
     */
    private $codigoInternoReferencia;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_entrega", type="date", nullable=true)
     */
    private $fechaEntrega;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="dotacionesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */
    private $estadoCerrado = 0;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */
    private $estadoAutorizado = 0;

    /**
     * @ORM\Column(name="estado_salida_inventario", type="boolean")
     */
    private $estadoSalidaInventario = 0;

    /**
     * @ORM\Column(name="afecta_inventario", type="boolean", nullable=true)
     */
    private $afectaInventario = false;

    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */
    private $codigoUsuario;

    /**
     * @ORM\Column(name="tipo_dotacion", type="string", length=50, nullable=true)
     */
    private $tipoDotacion;

    /**
     * @ORM\OneToMany(targetEntity="RhuDotacionDetalle", mappedBy="dotacionRel")
     */
    protected $dotacionesDetallesDotacionRel;

    /**
     * @return mixed
     */
    public function getCodigoDotacionPk()
    {
        return $this->codigoDotacionPk;
    }

    /**
     * @param mixed $codigoDotacionPk
     */
    public function setCodigoDotacionPk($codigoDotacionPk): void
    {
        $this->codigoDotacionPk = $codigoDotacionPk;
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
    public function getCodigoInternoReferencia()
    {
        return $this->codigoInternoReferencia;
    }

    /**
     * @param mixed $codigoInternoReferencia
     */
    public function setCodigoInternoReferencia($codigoInternoReferencia): void
    {
        $this->codigoInternoReferencia = $codigoInternoReferencia;
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
    public function getFechaEntrega()
    {
        return $this->fechaEntrega;
    }

    /**
     * @param mixed $fechaEntrega
     */
    public function setFechaEntrega($fechaEntrega): void
    {
        $this->fechaEntrega = $fechaEntrega;
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
    public function getEstadoSalidaInventario()
    {
        return $this->estadoSalidaInventario;
    }

    /**
     * @param mixed $estadoSalidaInventario
     */
    public function setEstadoSalidaInventario($estadoSalidaInventario): void
    {
        $this->estadoSalidaInventario = $estadoSalidaInventario;
    }

    /**
     * @return mixed
     */
    public function getAfectaInventario()
    {
        return $this->afectaInventario;
    }

    /**
     * @param mixed $afectaInventario
     */
    public function setAfectaInventario($afectaInventario): void
    {
        $this->afectaInventario = $afectaInventario;
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
    public function getTipoDotacion()
    {
        return $this->tipoDotacion;
    }

    /**
     * @param mixed $tipoDotacion
     */
    public function setTipoDotacion($tipoDotacion): void
    {
        $this->tipoDotacion = $tipoDotacion;
    }



}