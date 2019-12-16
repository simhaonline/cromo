<?php

namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(name="posicion", type="integer")
     */
    private $posicion = 0;

    /**
     *
     * @ORM\Column(name="turno_a", type="string", length=5, nullable=true)
     * @Assert\Length(
     *     max=5,
     *     maxMessage="El comentario no puede contener mas de 5 caracteres"
     * )
     */
    protected $turnoA;

    /**
     *
     * @ORM\Column(name="turno_b", type="string", length=5, nullable=true)
     * @Assert\Length(
     *     max=5,
     *     maxMessage="El comentario no puede contener mas de 5 caracteres"
     * )
     */
    protected $turnoB;

    /**
     *
     * @ORM\Column(name="turno_c", type="string", length=5, nullable=true)
     * @Assert\Length(
     *     max=5,
     *     maxMessage="El comentario no puede contener mas de 5 caracteres"
     * )
     */
    protected $turnoC;

    /**
     *
     * @ORM\Column(name="turno_d", type="string", length=5, nullable=true)
     * @Assert\Length(
     *     max=5,
     *     maxMessage="El comentario no puede contener mas de 5 caracteres"
     * )
     */
    protected $turnoD;

    /**
     *
     * @ORM\Column(name="turno_e", type="string", length=5, nullable=true)
     * @Assert\Length(
     *     max=5,
     *     maxMessage="El comentario no puede contener mas de 5 caracteres"
     * )
     */
    protected $turnoE;

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

    /**
     * @return int
     */
    public function getPosicion(): int
    {
        return $this->posicion;
    }

    /**
     * @param int $posicion
     */
    public function setPosicion(int $posicion): void
    {
        $this->posicion = $posicion;
    }

    /**
     * @return mixed
     */
    public function getTurnoA()
    {
        return $this->turnoA;
    }

    /**
     * @param mixed $turnoA
     */
    public function setTurnoA($turnoA): void
    {
        $this->turnoA = $turnoA;
    }

    /**
     * @return mixed
     */
    public function getTurnoB()
    {
        return $this->turnoB;
    }

    /**
     * @param mixed $turnoB
     */
    public function setTurnoB($turnoB): void
    {
        $this->turnoB = $turnoB;
    }

    /**
     * @return mixed
     */
    public function getTurnoC()
    {
        return $this->turnoC;
    }

    /**
     * @param mixed $turnoC
     */
    public function setTurnoC($turnoC): void
    {
        $this->turnoC = $turnoC;
    }

    /**
     * @return mixed
     */
    public function getTurnoD()
    {
        return $this->turnoD;
    }

    /**
     * @param mixed $turnoD
     */
    public function setTurnoD($turnoD): void
    {
        $this->turnoD = $turnoD;
    }

    /**
     * @return mixed
     */
    public function getTurnoE()
    {
        return $this->turnoE;
    }

    /**
     * @param mixed $turnoE
     */
    public function setTurnoE($turnoE): void
    {
        $this->turnoE = $turnoE;
    }



}
