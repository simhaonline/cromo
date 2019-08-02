<?php

namespace App\Entity\Tesoreria;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Tesoreria\TesEgresoDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TesEgresoDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoEgresoDetallePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $codigoEgresoDetallePk;

    /**
     * @ORM\Column(name="codigo_egreso_fk" , type="integer")
     */
    private $codigoEgresoFk;

    /**
     * @ORM\Column(name="codigo_cuenta_pagar_fk" , type="integer")
     */
    private $codigoCuentaPagarFk;

    /**
     * @ORM\Column(name="numero", type="string", length=30, nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="vr_pago", type="float", nullable=true)
     */
    private $vrPago = 0;

    /**
     * @ORM\Column(name="vr_pago_afectar", type="float", nullable=true)
     */
    private $vrPagoAfectar = 0;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tesoreria\TesEgreso" , inversedBy="egresoDetallesEgresoRel")
     * @ORM\JoinColumn(name="codigo_egreso_fk" , referencedColumnName="codigo_egreso_pk")
     */
    private $egresoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tesoreria\TesCuentaPagar" , inversedBy="egresosDetalleCuentasPagarRel")
     * @ORM\JoinColumn(name="codigo_cuenta_pagar_fk" , referencedColumnName="codigo_cuenta_pagar_pk")
     */
    private $cuentaPagarRel;

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
    public function getCodigoCuentaPagarFk()
    {
        return $this->codigoCuentaPagarFk;
    }

    /**
     * @param mixed $codigoCuentaPagarFk
     */
    public function setCodigoCuentaPagarFk($codigoCuentaPagarFk): void
    {
        $this->codigoCuentaPagarFk = $codigoCuentaPagarFk;
    }

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero): void
    {
        $this->numero = $numero;
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
    public function getVrPagoAfectar()
    {
        return $this->vrPagoAfectar;
    }

    /**
     * @param mixed $vrPagoAfectar
     */
    public function setVrPagoAfectar($vrPagoAfectar): void
    {
        $this->vrPagoAfectar = $vrPagoAfectar;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
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
    public function getCuentaPagarRel()
    {
        return $this->cuentaPagarRel;
    }

    /**
     * @param mixed $cuentaPagarRel
     */
    public function setCuentaPagarRel($cuentaPagarRel): void
    {
        $this->cuentaPagarRel = $cuentaPagarRel;
    }



}
