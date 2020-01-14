<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuInformeVacacionPendienteRepository")
 */
class RhuInformeVacacionPendiente
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_informe_vacacion_pendiente_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoInformeVacacionPendientePk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="tipo_contrato", type="string", length=200, nullable=true)
     */
    private $tipoContrato;

    /**
     * @ORM\Column(name="fecha_ingreso", type="date", nullable=true)
     */
    private $fechaIngreso;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=true)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="empleado", type="string", length=80, nullable=true)
     */
    private $empleado;

    /**
     * @ORM\Column(name="grupo", type="string", length=80, nullable=true)
     */
    private $grupo;

    /**
     * @ORM\Column(name="zona", type="string", length=80, nullable=true)
     */
    private $zona;

    /**
     * @ORM\Column(name="fecha_ultimo_pago", type="date", nullable=true)
     */
    private $fechaUltimoPago;

    /**
     * @ORM\Column(name="fecha_ultimo_pago_vacaciones", type="date", nullable=true)
     */
    private $fechaUltimoPagoVacaciones;

    /**
     * @ORM\Column(name="estado_terminado", type="boolean",options={"default":false}, nullable=true)
     */
    private $estadoTerminado = false;

    /**
     * @ORM\Column(name="vr_salario", options={"default":0},type="float", nullable=true)
     */
    private $vrSalario = 0;

    /**
     * @ORM\Column(name="dias", type="integer", nullable=true)
     */
    private $dias = 0;

    /**
     * @ORM\Column(name="dias_ausentismo", type="integer", nullable=true)
     */
    private $diasAusentismo = 0;

    /**
     * @ORM\Column(name="vr_vacacion", options={"default":0},type="float", nullable=true)
     */
    private $vrVacacion = 0;

    /**
     * @return mixed
     */
    public function getCodigoInformeVacacionPendientePk()
    {
        return $this->codigoInformeVacacionPendientePk;
    }

    /**
     * @param mixed $codigoInformeVacacionPendientePk
     */
    public function setCodigoInformeVacacionPendientePk($codigoInformeVacacionPendientePk): void
    {
        $this->codigoInformeVacacionPendientePk = $codigoInformeVacacionPendientePk;
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
    public function getTipoContrato()
    {
        return $this->tipoContrato;
    }

    /**
     * @param mixed $tipoContrato
     */
    public function setTipoContrato($tipoContrato): void
    {
        $this->tipoContrato = $tipoContrato;
    }

    /**
     * @return mixed
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * @param mixed $fechaIngreso
     */
    public function setFechaIngreso($fechaIngreso): void
    {
        $this->fechaIngreso = $fechaIngreso;
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
    public function getEmpleado()
    {
        return $this->empleado;
    }

    /**
     * @param mixed $empleado
     */
    public function setEmpleado($empleado): void
    {
        $this->empleado = $empleado;
    }

    /**
     * @return mixed
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * @param mixed $grupo
     */
    public function setGrupo($grupo): void
    {
        $this->grupo = $grupo;
    }

    /**
     * @return mixed
     */
    public function getZona()
    {
        return $this->zona;
    }

    /**
     * @param mixed $zona
     */
    public function setZona($zona): void
    {
        $this->zona = $zona;
    }

    /**
     * @return mixed
     */
    public function getFechaUltimoPago()
    {
        return $this->fechaUltimoPago;
    }

    /**
     * @param mixed $fechaUltimoPago
     */
    public function setFechaUltimoPago($fechaUltimoPago): void
    {
        $this->fechaUltimoPago = $fechaUltimoPago;
    }

    /**
     * @return mixed
     */
    public function getFechaUltimoPagoVacaciones()
    {
        return $this->fechaUltimoPagoVacaciones;
    }

    /**
     * @param mixed $fechaUltimoPagoVacaciones
     */
    public function setFechaUltimoPagoVacaciones($fechaUltimoPagoVacaciones): void
    {
        $this->fechaUltimoPagoVacaciones = $fechaUltimoPagoVacaciones;
    }

    /**
     * @return mixed
     */
    public function getEstadoTerminado()
    {
        return $this->estadoTerminado;
    }

    /**
     * @param mixed $estadoTerminado
     */
    public function setEstadoTerminado($estadoTerminado): void
    {
        $this->estadoTerminado = $estadoTerminado;
    }

    /**
     * @return mixed
     */
    public function getVrSalario()
    {
        return $this->vrSalario;
    }

    /**
     * @param mixed $vrSalario
     */
    public function setVrSalario($vrSalario): void
    {
        $this->vrSalario = $vrSalario;
    }

    /**
     * @return mixed
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * @param mixed $dias
     */
    public function setDias($dias): void
    {
        $this->dias = $dias;
    }

    /**
     * @return mixed
     */
    public function getDiasAusentismo()
    {
        return $this->diasAusentismo;
    }

    /**
     * @param mixed $diasAusentismo
     */
    public function setDiasAusentismo($diasAusentismo): void
    {
        $this->diasAusentismo = $diasAusentismo;
    }

    /**
     * @return mixed
     */
    public function getVrVacacion()
    {
        return $this->vrVacacion;
    }

    /**
     * @param mixed $vrVacacion
     */
    public function setVrVacacion($vrVacacion): void
    {
        $this->vrVacacion = $vrVacacion;
    }



}