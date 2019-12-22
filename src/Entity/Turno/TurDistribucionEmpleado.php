<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurDistribucionEmpleadoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurDistribucionEmpleado
{
    public $infoLog = [
        "primaryKey" => "codigoDistribucionEmpleadoPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_distribucion_empleado_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDistribucionEmpleadoPk;

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
     * @ORM\Column(name="codigo_centro_costo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCentroCostoFk;

    /**
     * @ORM\Column(name="participacion", type="float")
     */
    private $participacion = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", inversedBy="distribucionesEmpleadosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Financiero\FinCentroCosto", inversedBy="distribucionesEmpleadosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Turno\TurCierre", inversedBy="distribucionesEmpleadosCierreRel")
     * @ORM\JoinColumn(name="codigo_cierre_fk", referencedColumnName="codigo_cierre_pk")
     */
    protected $cierreRel;

    /**
     * @return mixed
     */
    public function getCodigoDistribucionEmpleadoPk()
    {
        return $this->codigoDistribucionEmpleadoPk;
    }

    /**
     * @param mixed $codigoDistribucionEmpleadoPk
     */
    public function setCodigoDistribucionEmpleadoPk($codigoDistribucionEmpleadoPk): void
    {
        $this->codigoDistribucionEmpleadoPk = $codigoDistribucionEmpleadoPk;
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
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * @param mixed $codigoCentroCostoFk
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk): void
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;
    }

    /**
     * @return mixed
     */
    public function getParticipacion()
    {
        return $this->participacion;
    }

    /**
     * @param mixed $participacion
     */
    public function setParticipacion($participacion): void
    {
        $this->participacion = $participacion;
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
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }

    /**
     * @param mixed $centroCostoRel
     */
    public function setCentroCostoRel($centroCostoRel): void
    {
        $this->centroCostoRel = $centroCostoRel;
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

