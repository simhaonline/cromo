<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCapacitacionDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */

class RhuCapacitacionDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoCapacitacionDetallePk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_capacitacion_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCapacitacionDetallePk;

    /**
     * @ORM\Column(name="codigo_capacitacion_fk", type="integer", nullable=true)
     */
    private $codigoCapacitacionFk;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=50, nullable=true)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=80, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="asistencia", type="boolean",options={"default":false})
     */
    private $asistencia = false;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="evaluacion", type="string", length=80, nullable=true)
     */
    private $evaluacion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuCapacitacion", inversedBy="capacitacionesDetallesCapacitacionRel")
     * @ORM\JoinColumn(name="codigo_capacitacion_fk", referencedColumnName="codigo_capacitacion_pk")
     */
    protected $capacitacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="capacitacionesDetallesEmpleadoRel")
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
    public function getCodigoCapacitacionDetallePk()
    {
        return $this->codigoCapacitacionDetallePk;
    }

    /**
     * @param mixed $codigoCapacitacionDetallePk
     */
    public function setCodigoCapacitacionDetallePk($codigoCapacitacionDetallePk): void
    {
        $this->codigoCapacitacionDetallePk = $codigoCapacitacionDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCapacitacionFk()
    {
        return $this->codigoCapacitacionFk;
    }

    /**
     * @param mixed $codigoCapacitacionFk
     */
    public function setCodigoCapacitacionFk($codigoCapacitacionFk): void
    {
        $this->codigoCapacitacionFk = $codigoCapacitacionFk;
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
    public function getAsistencia()
    {
        return $this->asistencia;
    }

    /**
     * @param mixed $asistencia
     */
    public function setAsistencia($asistencia): void
    {
        $this->asistencia = $asistencia;
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
    public function getEvaluacion()
    {
        return $this->evaluacion;
    }

    /**
     * @param mixed $evaluacion
     */
    public function setEvaluacion($evaluacion): void
    {
        $this->evaluacion = $evaluacion;
    }

    /**
     * @return mixed
     */
    public function getCapacitacionRel()
    {
        return $this->capacitacionRel;
    }

    /**
     * @param mixed $capacitacionRel
     */
    public function setCapacitacionRel($capacitacionRel): void
    {
        $this->capacitacionRel = $capacitacionRel;
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