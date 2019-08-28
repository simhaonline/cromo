<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuConceptoCuentaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuConceptoCuenta
{
    public $infoLog = [
        "primaryKey" => "codigoConceptoCuentaPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_concepto_cuenta_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoConceptoCuentaPk;

    /**
     * @ORM\Column(name="codigo_concepto_fk", type="string", length=10)
     */
    private $codigoConceptoFk;

    /**
     * @ORM\Column(name="codigo_costo_clase_fk", type="string", length=10)
     */
    private $codigoCostoClaseFk;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20)
     */
    private $codigoCuentaFk;

    /**
     * @ORM\Column(name="codigo_empleado_tipo_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoTipoFk;

    /**
     * @ORM\ManyToOne(targetEntity="RhuConcepto", inversedBy="conceptosCuentasConceptoRel")
     * @ORM\JoinColumn(name="codigo_concepto_fk", referencedColumnName="codigo_concepto_pk")
     */
    protected $conceptoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCostoClase", inversedBy="conceptosCuentasCostoClaseRel")
     * @ORM\JoinColumn(name="codigo_costo_clase_fk",referencedColumnName="codigo_costo_clase_pk")
     */
    protected $costoClaseRel;

    /**
     * @return mixed
     */
    public function getCodigoConceptoCuentaPk()
    {
        return $this->codigoConceptoCuentaPk;
    }

    /**
     * @param mixed $codigoConceptoCuentaPk
     */
    public function setCodigoConceptoCuentaPk($codigoConceptoCuentaPk): void
    {
        $this->codigoConceptoCuentaPk = $codigoConceptoCuentaPk;
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
    public function getCodigoCostoClaseFk()
    {
        return $this->codigoCostoClaseFk;
    }

    /**
     * @param mixed $codigoCostoClaseFk
     */
    public function setCodigoCostoClaseFk($codigoCostoClaseFk): void
    {
        $this->codigoCostoClaseFk = $codigoCostoClaseFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * @param mixed $codigoCuentaFk
     */
    public function setCodigoCuentaFk($codigoCuentaFk): void
    {
        $this->codigoCuentaFk = $codigoCuentaFk;
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
    public function getCostoClaseRel()
    {
        return $this->costoClaseRel;
    }

    /**
     * @param mixed $costoClaseRel
     */
    public function setCostoClaseRel($costoClaseRel): void
    {
        $this->costoClaseRel = $costoClaseRel;
    }

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
    public function getCodigoEmpleadoTipoFk()
    {
        return $this->codigoEmpleadoTipoFk;
    }

    /**
     * @param mixed $codigoEmpleadoTipoFk
     */
    public function setCodigoEmpleadoTipoFk($codigoEmpleadoTipoFk): void
    {
        $this->codigoEmpleadoTipoFk = $codigoEmpleadoTipoFk;
    }



}
