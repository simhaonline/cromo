<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurCostoEmpleadoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurCostoEmpleado
{
    public $infoLog = [
        "primaryKey" => "codigoCostoEmpleadoPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_costo_empleado_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCostoEmpleadoPk;

    /**
     * @ORM\Column(name="codigo_cierre_fk", type="integer")
     */
    private $codigoCierreFk;

    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */
    private $anio;

    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */
    private $mes;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="vr_nomina", type="float")
     */
    private $vrNomina = 0;

    /**
     * @ORM\Column(name="vr_provision", type="float")
     */
    private $vrProvision = 0;

    /**
     * @ORM\Column(name="vr_aporte", type="float")
     */
    private $vrAporte = 0;

    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;

    /**
     * @ORM\ManyToOne(targetEntity="TurCierre", inversedBy="costosEmpleadosCierreRel")
     * @ORM\JoinColumn(name="codigo_cierre_fk", referencedColumnName="codigo_cierre_pk")
     */
    protected $cierreRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", inversedBy="costosEmpleadosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @return mixed
     */
    public function getCodigoCostoEmpleadoPk()
    {
        return $this->codigoCostoEmpleadoPk;
    }

    /**
     * @param mixed $codigoCostoEmpleadoPk
     */
    public function setCodigoCostoEmpleadoPk($codigoCostoEmpleadoPk): void
    {
        $this->codigoCostoEmpleadoPk = $codigoCostoEmpleadoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCierreFk()
    {
        return $this->codigoCierreFk;
    }

    /**
     * @param mixed $codigoCierreFk
     */
    public function setCodigoCierreFk($codigoCierreFk): void
    {
        $this->codigoCierreFk = $codigoCierreFk;
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
     * @return int
     */
    public function getVrNomina(): int
    {
        return $this->vrNomina;
    }

    /**
     * @param int $vrNomina
     */
    public function setVrNomina(int $vrNomina): void
    {
        $this->vrNomina = $vrNomina;
    }

    /**
     * @return int
     */
    public function getVrProvision(): int
    {
        return $this->vrProvision;
    }

    /**
     * @param int $vrProvision
     */
    public function setVrProvision(int $vrProvision): void
    {
        $this->vrProvision = $vrProvision;
    }

    /**
     * @return int
     */
    public function getVrAporte(): int
    {
        return $this->vrAporte;
    }

    /**
     * @param int $vrAporte
     */
    public function setVrAporte(int $vrAporte): void
    {
        $this->vrAporte = $vrAporte;
    }

    /**
     * @return int
     */
    public function getVrTotal(): int
    {
        return $this->vrTotal;
    }

    /**
     * @param int $vrTotal
     */
    public function setVrTotal(int $vrTotal): void
    {
        $this->vrTotal = $vrTotal;
    }

    /**
     * @return mixed
     */
    public function getCierreRel()
    {
        return $this->cierreRel;
    }

    /**
     * @param mixed $cierreRel
     */
    public function setCierreRel($cierreRel): void
    {
        $this->cierreRel = $cierreRel;
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

