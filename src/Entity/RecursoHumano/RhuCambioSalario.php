<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCambioSalarioRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuCambioSalario
{
    public $infoLog = [
        "primaryKey" => "codigoCambioSalarioPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cambio_salario_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCambioSalarioPk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_inicio", type="date", nullable=true)
     */
    private $fechaInicio;

    /**
     * @ORM\Column(name="vr_salario_anterior", type="float")
     */
    private $VrSalarioAnterior = 0;

    /**
     * @ORM\Column(name="vr_salario_nuevo", type="float")
     */
    private $VrSalarioNuevo = 0;

    /**
     * @ORM\Column(name="detalle", type="text", nullable=true)
     */
    private $detalle;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuContrato", inversedBy="cambiosSalariosContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", inversedBy="cambiosSalariosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @return mixed
     */
    public function getCodigoCambioSalarioPk()
    {
        return $this->codigoCambioSalarioPk;
    }

    /**
     * @param mixed $codigoCambioSalarioPk
     */
    public function setCodigoCambioSalarioPk($codigoCambioSalarioPk): void
    {
        $this->codigoCambioSalarioPk = $codigoCambioSalarioPk;
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
    public function getVrSalarioAnterior()
    {
        return $this->VrSalarioAnterior;
    }

    /**
     * @param mixed $VrSalarioAnterior
     */
    public function setVrSalarioAnterior($VrSalarioAnterior): void
    {
        $this->VrSalarioAnterior = $VrSalarioAnterior;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioNuevo()
    {
        return $this->VrSalarioNuevo;
    }

    /**
     * @param mixed $VrSalarioNuevo
     */
    public function setVrSalarioNuevo($VrSalarioNuevo): void
    {
        $this->VrSalarioNuevo = $VrSalarioNuevo;
    }

    /**
     * @return mixed
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * @param mixed $detalle
     */
    public function setDetalle($detalle): void
    {
        $this->detalle = $detalle;
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
    public function getContratoRel()
    {
        return $this->contratoRel;
    }

    /**
     * @param mixed $contratoRel
     */
    public function setContratoRel($contratoRel): void
    {
        $this->contratoRel = $contratoRel;
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