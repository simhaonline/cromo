<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCostoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuCosto
{
    public $infoLog = [
        "primaryKey" => "codigoCostoPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_costo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCostoPk;

    /**
     * @ORM\Column(name="codigo_cierre_fk", type="integer", nullable=false)
     */
    private $codigoCierreFk;

    /**
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio = 0;

    /**
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes = 0;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="vr_nomina", type="float")
     */
    private $vrNomina = 0;

    /**
     * @ORM\Column(name="vr_aporte", type="float")
     */
    private $vrAporte = 0;

    /**
     * @ORM\Column(name="vr_provision", type="float")
     */
    private $vrProvision = 0;

    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="costosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCierre", inversedBy="costosCierreRel")
     * @ORM\JoinColumn(name="codigo_cierre_fk", referencedColumnName="codigo_cierre_pk")
     */
    protected $cierreRel;

    /**
     * @return mixed
     */
    public function getCodigoCostoPk()
    {
        return $this->codigoCostoPk;
    }

    /**
     * @param mixed $codigoCostoPk
     */
    public function setCodigoCostoPk($codigoCostoPk): void
    {
        $this->codigoCostoPk = $codigoCostoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCierreMesFk()
    {
        return $this->codigoCierreMesFk;
    }

    /**
     * @param mixed $codigoCierreMesFk
     */
    public function setCodigoCierreMesFk($codigoCierreMesFk): void
    {
        $this->codigoCierreMesFk = $codigoCierreMesFk;
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
     * @return mixed
     */
    public function getVrNomina()
    {
        return $this->vrNomina;
    }

    /**
     * @param mixed $vrNomina
     */
    public function setVrNomina($vrNomina): void
    {
        $this->vrNomina = $vrNomina;
    }

    /**
     * @return mixed
     */
    public function getVrAporte()
    {
        return $this->vrAporte;
    }

    /**
     * @param mixed $vrAporte
     */
    public function setVrAporte($vrAporte): void
    {
        $this->vrAporte = $vrAporte;
    }

    /**
     * @return mixed
     */
    public function getVrProvision()
    {
        return $this->vrProvision;
    }

    /**
     * @param mixed $vrProvision
     */
    public function setVrProvision($vrProvision): void
    {
        $this->vrProvision = $vrProvision;
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



}
