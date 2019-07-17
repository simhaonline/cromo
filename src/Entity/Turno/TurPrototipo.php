<?php

namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurPrototipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurPrototipo
{
    public $infoLog = [
        "primaryKey" => "codigoPrototipoPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_prototipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPrototipoPk;

    /**
     * @ORM\Column(name="codigo_contrato_detalle_fk", type="integer", nullable=true)
     */
    private $codigoContratoDetalleFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_secuencia_fk", type="string", length=5, nullable=true)
     */
    private $codigoSecuenciaFk;

    /**
     * @ORM\Column(name="fecha_inicio_secuencia", type="date", nullable=true)
     */
    protected $fechaInicioSecuencia;

    /**
     * @ORM\Column(name="inicio_secuencia", type="integer", nullable=true)
     */
    protected $inicioSecuencia;

    /**
     * @ORM\ManyToOne(targetEntity="TurContratoDetalle", inversedBy="prototiposContratoDetalleRel")
     * @ORM\JoinColumn(name="codigo_contrato_detalle_fk", referencedColumnName="codigo_contrato_detalle_pk")
     */
    protected $contratoDetalleRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", inversedBy="prototiposEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk",referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurSecuencia", inversedBy="prototiposSecuenciaRel")
     * @ORM\JoinColumn(name="codigo_secuencia_fk",referencedColumnName="codigo_secuencia_pk")
     */
    protected $secuenciaRel;

    /**
     * @return mixed
     */
    public function getCodigoPrototipoPk()
    {
        return $this->codigoPrototipoPk;
    }

    /**
     * @param mixed $codigoPrototipoPk
     */
    public function setCodigoPrototipoPk($codigoPrototipoPk): void
    {
        $this->codigoPrototipoPk = $codigoPrototipoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoContratoDetalleFk()
    {
        return $this->codigoContratoDetalleFk;
    }

    /**
     * @param mixed $codigoContratoDetalleFk
     */
    public function setCodigoContratoDetalleFk($codigoContratoDetalleFk): void
    {
        $this->codigoContratoDetalleFk = $codigoContratoDetalleFk;
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
    public function getFechaInicioSecuencia()
    {
        return $this->fechaInicioSecuencia;
    }

    /**
     * @param mixed $fechaInicioSecuencia
     */
    public function setFechaInicioSecuencia($fechaInicioSecuencia): void
    {
        $this->fechaInicioSecuencia = $fechaInicioSecuencia;
    }

    /**
     * @return mixed
     */
    public function getInicioSecuencia()
    {
        return $this->inicioSecuencia;
    }

    /**
     * @param mixed $inicioSecuencia
     */
    public function setInicioSecuencia($inicioSecuencia): void
    {
        $this->inicioSecuencia = $inicioSecuencia;
    }

    /**
     * @return mixed
     */
    public function getContratoDetalleRel()
    {
        return $this->contratoDetalleRel;
    }

    /**
     * @param mixed $contratoDetalleRel
     */
    public function setContratoDetalleRel($contratoDetalleRel): void
    {
        $this->contratoDetalleRel = $contratoDetalleRel;
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
    public function getCodigoSecuenciaFk()
    {
        return $this->codigoSecuenciaFk;
    }

    /**
     * @param mixed $codigoSecuenciaFk
     */
    public function setCodigoSecuenciaFk($codigoSecuenciaFk): void
    {
        $this->codigoSecuenciaFk = $codigoSecuenciaFk;
    }

    /**
     * @return mixed
     */
    public function getSecuenciaRel()
    {
        return $this->secuenciaRel;
    }

    /**
     * @param mixed $secuenciaRel
     */
    public function setSecuenciaRel($secuenciaRel): void
    {
        $this->secuenciaRel = $secuenciaRel;
    }



}
