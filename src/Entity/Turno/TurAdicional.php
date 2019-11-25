<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurAdicionalRepository")
 */
class TurAdicional
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_adicional_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAdicionalPK;

    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */
    private $codigoPuestoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_concepto_fk", type="string", length=10,nullable=true)
     */
    private $codigoConceptoFk;

    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */
    private $anio = 0;

    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */
    private $mes = 0;

    /**
     * @ORM\Column(name="desde", type="integer", nullable=true)
     */
    private $desde = 0;

    /**
     * @ORM\Column(name="hasta", type="integer", nullable=true)
     */
    private $hasta = 0;

    /**
     * @ORM\Column(name="numero_turnos", type="integer", nullable=true)
     */
    private $numeroTurnos = 0;

    /**
     * @ORM\Column(name="vr_turno", type="float", nullable=true)
     */
    private $vrTurno = 0;

    /**
     * @ORM\Column(name="vr_pago", type="float", options={"default" : 0})
     */
    private $vrPago = 0;

    /**
     * @ORM\ManyToOne(targetEntity="TurPuesto", inversedBy="puestosAdicionalesPuestoRel")
     * @ORM\JoinColumn(name="codigo_puesto_fk", referencedColumnName="codigo_puesto_pk")
     */
    protected $puestoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuConcepto", inversedBy="puestoAdicionalConceptoRel")
     * @ORM\JoinColumn(name="codigo_concepto_fk", referencedColumnName="codigo_concepto_pk")
     */
    protected $conceptoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", inversedBy="adicionalesTurnoEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk",referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @return mixed
     */
    public function getCodigoPuestoAdicionalPk()
    {
        return $this->codigoPuestoAdicionalPk;
    }

    /**
     * @param mixed $codigoPuestoAdicionalPk
     */
    public function setCodigoPuestoAdicionalPk($codigoPuestoAdicionalPk): void
    {
        $this->codigoPuestoAdicionalPk = $codigoPuestoAdicionalPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPuestoFk()
    {
        return $this->codigoPuestoFk;
    }

    /**
     * @param mixed $codigoPuestoFk
     */
    public function setCodigoPuestoFk($codigoPuestoFk): void
    {
        $this->codigoPuestoFk = $codigoPuestoFk;
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
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param mixed $valor
     */
    public function setValor($valor): void
    {
        $this->valor = $valor;
    }

    /**
     * @return mixed
     */
    public function getIncluirDescanso()
    {
        return $this->incluirDescanso;
    }

    /**
     * @param mixed $incluirDescanso
     */
    public function setIncluirDescanso($incluirDescanso): void
    {
        $this->incluirDescanso = $incluirDescanso;
    }

    /**
     * @return mixed
     */
    public function getPuestoRel()
    {
        return $this->puestoRel;
    }

    /**
     * @param mixed $puestoRel
     */
    public function setPuestoRel($puestoRel): void
    {
        $this->puestoRel = $puestoRel;
    }

    /**
     * @return mixed
     */
    public function getConceptoRel()
    {
        return $this->conceptoRel;
    }

    /**
     * @param mixed $conceptoRel
     */
    public function setConceptoRel($conceptoRel): void
    {
        $this->conceptoRel = $conceptoRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoAdicionalPK()
    {
        return $this->codigoAdicionalPK;
    }

    /**
     * @param mixed $codigoAdicionalPK
     */
    public function setCodigoAdicionalPK($codigoAdicionalPK): void
    {
        $this->codigoAdicionalPK = $codigoAdicionalPK;
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
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * @param mixed $anio
     */
    public function setAnio($anio): void
    {
        $this->anio = $anio;
    }

    /**
     * @return mixed
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * @param mixed $mes
     */
    public function setMes($mes): void
    {
        $this->mes = $mes;
    }

    /**
     * @return mixed
     */
    public function getDesde()
    {
        return $this->desde;
    }

    /**
     * @param mixed $desde
     */
    public function setDesde($desde): void
    {
        $this->desde = $desde;
    }

    /**
     * @return mixed
     */
    public function getHasta()
    {
        return $this->hasta;
    }

    /**
     * @param mixed $hasta
     */
    public function setHasta($hasta): void
    {
        $this->hasta = $hasta;
    }

    /**
     * @return mixed
     */
    public function getNumeroTurnos()
    {
        return $this->numeroTurnos;
    }

    /**
     * @param mixed $numeroTurnos
     */
    public function setNumeroTurnos($numeroTurnos): void
    {
        $this->numeroTurnos = $numeroTurnos;
    }

    /**
     * @return mixed
     */
    public function getVrTurno()
    {
        return $this->vrTurno;
    }

    /**
     * @param mixed $vrTurno
     */
    public function setVrTurno($vrTurno): void
    {
        $this->vrTurno = $vrTurno;
    }

    /**
     * @return mixed
     */
    public function getVrPago()
    {
        return $this->vrPago;
    }

    /**
     * @param mixed $vrPago
     */
    public function setVrPago($vrPago): void
    {
        $this->vrPago = $vrPago;
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

