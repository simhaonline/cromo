<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAdicionalRepository")
 */
class RhuAdicional
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_adicional_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAdicionalPk;

    /**
     * @ORM\Column(name="codigo_concepto_fk", type="string", length=10, nullable=true)
     */
    private $codigoConceptoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="vr_valor", options={"default":0}, type="float")
     */
    private $vrValor = 0;

    /**
     * @ORM\Column(name="permanente", options={"default":false}, type="boolean")
     */
    private $permanente = false;

    /**
     * @ORM\Column(name="aplica_dia_laborado", options={"default":false}, type="boolean")
     */
    private $aplicaDiaLaborado = false;

    /**
     * @ORM\Column(name="aplica_prima", options={"default":false}, options={"default":false}, type="boolean", nullable=true)
     */
    private $aplicaPrima = false;

    /**
     * @ORM\Column(name="aplica_cesantia", options={"default":false}, type="boolean", nullable=true)
     */
    private $aplicaCesantia = false;

    /**
     * @ORM\Column(name="detalle", type="string", length=250, nullable=true)
     * @Assert\Length(
     *     max=250,
     *     maxMessage="El campo no puede contener mas de 250 caracteres"
     * )
     */
    private $detalle;

    /**
     * @ORM\Column(name="estado_inactivo", options={"default":false}, type="boolean")
     */
    private $estadoInactivo = false;

    /**
     * @ORM\Column(name="estado_inactivo_periodo", options={"default":false}, type="boolean")
     */
    private $estadoInactivoPeriodo = false;

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
     * @return mixed
     */
    public function getCodigoAdicionalPk()
    {
        return $this->codigoAdicionalPk;
    }

    /**
     * @param mixed $codigoAdicionalPk
     */
    public function setCodigoAdicionalPk($codigoAdicionalPk): void
    {
        $this->codigoAdicionalPk = $codigoAdicionalPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoConceptoFk()
    {
        return $this->codigoConceptoFk;
    }

    /**
     * @param mixed $codigoConceptoFk
     */
    public function setCodigoConceptoFk($codigoConceptoFk): void
    {
        $this->codigoConceptoFk = $codigoConceptoFk;
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
    public function getVrValor()
    {
        return $this->vrValor;
    }

    /**
     * @param mixed $vrValor
     */
    public function setVrValor($vrValor): void
    {
        $this->vrValor = $vrValor;
    }

    /**
     * @return mixed
     */
    public function getPermanente()
    {
        return $this->permanente;
    }

    /**
     * @param mixed $permanente
     */
    public function setPermanente($permanente): void
    {
        $this->permanente = $permanente;
    }

    /**
     * @return mixed
     */
    public function getAplicaDiaLaborado()
    {
        return $this->aplicaDiaLaborado;
    }

    /**
     * @param mixed $aplicaDiaLaborado
     */
    public function setAplicaDiaLaborado($aplicaDiaLaborado): void
    {
        $this->aplicaDiaLaborado = $aplicaDiaLaborado;
    }

    /**
     * @return mixed
     */
    public function getAplicaPrima()
    {
        return $this->aplicaPrima;
    }

    /**
     * @param mixed $aplicaPrima
     */
    public function setAplicaPrima($aplicaPrima): void
    {
        $this->aplicaPrima = $aplicaPrima;
    }

    /**
     * @return mixed
     */
    public function getAplicaCesantia()
    {
        return $this->aplicaCesantia;
    }

    /**
     * @param mixed $aplicaCesantia
     */
    public function setAplicaCesantia($aplicaCesantia): void
    {
        $this->aplicaCesantia = $aplicaCesantia;
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
    public function getEstadoInactivo()
    {
        return $this->estadoInactivo;
    }

    /**
     * @param mixed $estadoInactivo
     */
    public function setEstadoInactivo($estadoInactivo): void
    {
        $this->estadoInactivo = $estadoInactivo;
    }

    /**
     * @return mixed
     */
    public function getEstadoInactivoPeriodo()
    {
        return $this->estadoInactivoPeriodo;
    }

    /**
     * @param mixed $estadoInactivoPeriodo
     */
    public function setEstadoInactivoPeriodo($estadoInactivoPeriodo): void
    {
        $this->estadoInactivoPeriodo = $estadoInactivoPeriodo;
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
}
