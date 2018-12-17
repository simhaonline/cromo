<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuEgresoDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuEgresoDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoEgresoDetallePk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_egreso_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEgresoDetallePk;

    /**
     * @ORM\Column(name="codigo_egreso_fk", type="integer", nullable=true)
     */
    private $codigoEgresoFk;

    /**
     * @ORM\Column(name="codigo_pago_fk", type="integer", nullable=true)
     */
    private $codigoPagoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_banco_fk", type="string",length=10, nullable=true)
     */
    private $codigoBancoFk;

    /**
     * @ORM\Column(name="vr_pago", options={"default":0},type="float")
     */
    private $vrPago = 0;

    /**
     * @ORM\Column(name="cuenta",type="string", length=80, nullable=true)
     */
    private $cuenta;

    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean",options={"default":false})
     */
    private $estadoContabilizado = false;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEgreso", inversedBy="egresosDetallesEgresoRel")
     * @ORM\JoinColumn(name="codigo_egreso_fk", referencedColumnName="codigo_egreso_pk")
     */
    protected $egresoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuPago", inversedBy="egresosDetallesPagoRel")
     * @ORM\JoinColumn(name="codigo_pago_fk", referencedColumnName="codigo_pago_pk")
     */
    protected $pagoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuBanco", inversedBy="egresosDetallesBancoRel")
     * @ORM\JoinColumn(name="codigo_banco_fk", referencedColumnName="codigo_banco_pk")
     */
    protected $bancoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="egresosDetallesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @return mixed
     */
    public function getCodigoEgresoDetallePk()
    {
        return $this->codigoEgresoDetallePk;
    }

    /**
     * @param mixed $codigoEgresoDetallePk
     */
    public function setCodigoEgresoDetallePk($codigoEgresoDetallePk): void
    {
        $this->codigoEgresoDetallePk = $codigoEgresoDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEgresoFk()
    {
        return $this->codigoEgresoFk;
    }

    /**
     * @param mixed $codigoEgresoFk
     */
    public function setCodigoEgresoFk($codigoEgresoFk): void
    {
        $this->codigoEgresoFk = $codigoEgresoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPagoFk()
    {
        return $this->codigoPagoFk;
    }

    /**
     * @param mixed $codigoPagoFk
     */
    public function setCodigoPagoFk($codigoPagoFk): void
    {
        $this->codigoPagoFk = $codigoPagoFk;
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
    public function getCodigoBancoFk()
    {
        return $this->codigoBancoFk;
    }

    /**
     * @param mixed $codigoBancoFk
     */
    public function setCodigoBancoFk($codigoBancoFk): void
    {
        $this->codigoBancoFk = $codigoBancoFk;
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
    public function getEstadoContabilizado()
    {
        return $this->estadoContabilizado;
    }

    /**
     * @param mixed $estadoContabilizado
     */
    public function setEstadoContabilizado($estadoContabilizado): void
    {
        $this->estadoContabilizado = $estadoContabilizado;
    }

    /**
     * @return mixed
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * @param mixed $cuenta
     */
    public function setCuenta($cuenta): void
    {
        $this->cuenta = $cuenta;
    }

    /**
     * @return mixed
     */
    public function getEgresoRel()
    {
        return $this->egresoRel;
    }

    /**
     * @param mixed $egresoRel
     */
    public function setEgresoRel($egresoRel): void
    {
        $this->egresoRel = $egresoRel;
    }

    /**
     * @return mixed
     */
    public function getPagoRel()
    {
        return $this->pagoRel;
    }

    /**
     * @param mixed $pagoRel
     */
    public function setPagoRel($pagoRel): void
    {
        $this->pagoRel = $pagoRel;
    }

    /**
     * @return mixed
     */
    public function getBancoRel()
    {
        return $this->bancoRel;
    }

    /**
     * @param mixed $bancoRel
     */
    public function setBancoRel($bancoRel): void
    {
        $this->bancoRel = $bancoRel;
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
