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
     * @ORM\OneToMany(targetEntity="GenCuenta", mappedBy="bancoRel")
     */
    protected $cuentasBancoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", mappedBy="bancoRel")
     */
    protected $empleadosBancoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tesoreria\TesCuentaPagar", mappedBy="bancoRel")
     */
    protected $cuentasPagarBancoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tesoreria\TesTercero", mappedBy="bancoRel")
     */
    protected $tercerosBancoRel;

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


}

