<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenBancoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class GenBanco
{
    public $infoLog = [
        "primaryKey" => "codigoBancoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_banco_pk", type="string", length=10, nullable=false)
     */
    private $codigoBancoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=30, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_interface", type="string", length=10, nullable=true)
     */
    private $codigoInterface;

    /**
     * @ORM\Column(name="codigo_dian", type="string", length=10, nullable=true)
     */
    private $codigoDian;

    /**
     * @ORM\OneToMany(targetEntity="GenCuenta", mappedBy="bancoRel")
     */
    protected $cuentasBancoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", mappedBy="bancoRel")
     */
    protected $empleadosBancoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuPago", mappedBy="bancoRel")
     */
    protected $pagosBancoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tesoreria\TesCuentaPagar", mappedBy="bancoRel")
     */
    protected $cuentasPagarBancoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tesoreria\TesTercero", mappedBy="bancoRel")
     */
    protected $tercerosBancoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tesoreria\TesEgresoDetalle", mappedBy="bancoRel")
     */
    protected $egresosDetallesBancoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tesoreria\TesMovimientoDetalle", mappedBy="bancoRel")
     */
    protected $movimientosDetallesBancoRel;

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
    public function getCodigoBancoPk()
    {
        return $this->codigoBancoPk;
    }

    /**
     * @param mixed $codigoBancoPk
     */
    public function setCodigoBancoPk($codigoBancoPk): void
    {
        $this->codigoBancoPk = $codigoBancoPk;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getCodigoInterface()
    {
        return $this->codigoInterface;
    }

    /**
     * @param mixed $codigoInterface
     */
    public function setCodigoInterface($codigoInterface): void
    {
        $this->codigoInterface = $codigoInterface;
    }

    /**
     * @return mixed
     */
    public function getCodigoDian()
    {
        return $this->codigoDian;
    }

    /**
     * @param mixed $codigoDian
     */
    public function setCodigoDian($codigoDian): void
    {
        $this->codigoDian = $codigoDian;
    }

    /**
     * @return mixed
     */
    public function getCuentasBancoRel()
    {
        return $this->cuentasBancoRel;
    }

    /**
     * @param mixed $cuentasBancoRel
     */
    public function setCuentasBancoRel($cuentasBancoRel): void
    {
        $this->cuentasBancoRel = $cuentasBancoRel;
    }

    /**
     * @return mixed
     */
    public function getEmpleadosBancoRel()
    {
        return $this->empleadosBancoRel;
    }

    /**
     * @param mixed $empleadosBancoRel
     */
    public function setEmpleadosBancoRel($empleadosBancoRel): void
    {
        $this->empleadosBancoRel = $empleadosBancoRel;
    }

    /**
     * @return mixed
     */
    public function getCuentasPagarBancoRel()
    {
        return $this->cuentasPagarBancoRel;
    }

    /**
     * @param mixed $cuentasPagarBancoRel
     */
    public function setCuentasPagarBancoRel($cuentasPagarBancoRel): void
    {
        $this->cuentasPagarBancoRel = $cuentasPagarBancoRel;
    }

    /**
     * @return mixed
     */
    public function getTercerosBancoRel()
    {
        return $this->tercerosBancoRel;
    }

    /**
     * @param mixed $tercerosBancoRel
     */
    public function setTercerosBancoRel($tercerosBancoRel): void
    {
        $this->tercerosBancoRel = $tercerosBancoRel;
    }

    /**
     * @return mixed
     */
    public function getEgresosDetallesBancoRel()
    {
        return $this->egresosDetallesBancoRel;
    }

    /**
     * @param mixed $egresosDetallesBancoRel
     */
    public function setEgresosDetallesBancoRel($egresosDetallesBancoRel): void
    {
        $this->egresosDetallesBancoRel = $egresosDetallesBancoRel;
    }

    /**
     * @return mixed
     */
    public function getPagosBancoRel()
    {
        return $this->pagosBancoRel;
    }

    /**
     * @param mixed $pagosBancoRel
     */
    public function setPagosBancoRel($pagosBancoRel): void
    {
        $this->pagosBancoRel = $pagosBancoRel;
    }

    /**
     * @return mixed
     */
    public function getMovimientosDetallesBancoRel()
    {
        return $this->movimientosDetallesBancoRel;
    }

    /**
     * @param mixed $movimientosDetallesBancoRel
     */
    public function setMovimientosDetallesBancoRel($movimientosDetallesBancoRel): void
    {
        $this->movimientosDetallesBancoRel = $movimientosDetallesBancoRel;
    }



}

