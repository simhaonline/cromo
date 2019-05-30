<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuExamenRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuExamen
{
    public $infoLog = [
        "primaryKey" => "codigoExamenPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenPk;

    /**
     * @ORM\Column(name="codigo_examen_clase_fk", type="integer", nullable=false)
     */
    private $codigoExamenClaseFk;

    /**
     * @ORM\Column(name="codigo_entidad_examen_fk", type="integer", nullable=false)
     */
    private $codigoEntidadExamenFk;

    /**
     * @ORM\Column(name="codigo_cargo_fk", type="string", length=10)
     */
    private $codigoCargoFk;

    /**
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean", options={"default":false})
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="nombreCorto", type="string")
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=true)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="codigo_sexo_fk", type="string", length=1, nullable=true)
     */
    private $codigoSexoFk;

    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="fecha_nacimiento", type="date", nullable=true)
     */

    private $fechaNacimiento;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="comentario", type="string", length=1500, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="cobro", type="string", length=1, nullable=true)
     */
    private $cobro;

    /**
     * @ORM\ManyToOne(targetEntity="RhuExamenClase", inversedBy="examenesExamenClaseRel")
     * @ORM\JoinColumn(name="codigo_examen_clase_fk", referencedColumnName="codigo_examen_clase_pk")
     */
    protected $examenClaseRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadExamen", inversedBy="examenesEntidadExamenRel")
     * @ORM\JoinColumn(name="codigo_entidad_examen_fk", referencedColumnName="codigo_entidad_examen_pk")
     */
    protected $entidadExamenRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="examenesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="examenesCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCiudad", inversedBy="examenesCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuExamenDetalle", mappedBy="examenRel")
     */
    protected $examenesExamenDetalleRel;

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
    public function getCodigoExamenPk()
    {
        return $this->codigoExamenPk;
    }

    /**
     * @param mixed $codigoExamenPk
     */
    public function setCodigoExamenPk($codigoExamenPk): void
    {
        $this->codigoExamenPk = $codigoExamenPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoExamenClaseFk()
    {
        return $this->codigoExamenClaseFk;
    }

    /**
     * @param mixed $codigoExamenClaseFk
     */
    public function setCodigoExamenClaseFk($codigoExamenClaseFk): void
    {
        $this->codigoExamenClaseFk = $codigoExamenClaseFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadExamenFk()
    {
        return $this->codigoEntidadExamenFk;
    }

    /**
     * @param mixed $codigoEntidadExamenFk
     */
    public function setCodigoEntidadExamenFk($codigoEntidadExamenFk): void
    {
        $this->codigoEntidadExamenFk = $codigoEntidadExamenFk;
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
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * @param mixed $nombreCorto
     */
    public function setNombreCorto($nombreCorto): void
    {
        $this->nombreCorto = $nombreCorto;
    }

    /**
     * @return mixed
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * @param mixed $numeroIdentificacion
     */
    public function setNumeroIdentificacion($numeroIdentificacion): void
    {
        $this->numeroIdentificacion = $numeroIdentificacion;
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
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * @param mixed $fechaNacimiento
     */
    public function setFechaNacimiento($fechaNacimiento): void
    {
        $this->fechaNacimiento = $fechaNacimiento;
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
    public function getCobro()
    {
        return $this->cobro;
    }

    /**
     * @param mixed $cobro
     */
    public function setCobro($cobro): void
    {
        $this->cobro = $cobro;
    }

    /**
     * @return mixed
     */
    public function getExamenClaseRel()
    {
        return $this->examenClaseRel;
    }

    /**
     * @param mixed $examenClaseRel
     */
    public function setExamenClaseRel($examenClaseRel): void
    {
        $this->examenClaseRel = $examenClaseRel;
    }

    /**
     * @return mixed
     */
    public function getEntidadExamenRel()
    {
        return $this->entidadExamenRel;
    }

    /**
     * @param mixed $entidadExamenRel
     */
    public function setEntidadExamenRel($entidadExamenRel): void
    {
        $this->entidadExamenRel = $entidadExamenRel;
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
