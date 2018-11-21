<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuEmbargoPagoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuEmbargoPago
{
    public $infoLog = [
        "primaryKey" => "codigoEmbargoPagoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Column(name="codigo_embargo_pago_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEmbargoPagoPk;

    /**
     * @ORM\Column(name="codigo_embargo_fk", type="integer", nullable=true)
     */
    private $codigoEmbargoFk;
    
    /**
     * @ORM\Column(name="codigo_pago_fk", type="integer", nullable=true)
     */
    private $codigoPagoFk;

    /**
     * @ORM\Column(name="vr_cuota", type="float", nullable=true)
     */
    private $vrCuota;

    /**
     * @ORM\Column(name="saldo", type="float", nullable=true)
     */
    private $saldo;

    /**
     * @ORM\Column(name="numero_cuota_actual", type="integer", nullable=true)
     */
    private $numeroCuotaActual;

    /**
     *
     * @ORM\Column(name="fecha_pago", type="date", nullable=true)
     */
    private $fechaPago;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmbargo", inversedBy="embargoPagoRel")
     * @ORM\JoinColumn(name="codigo_embargo_fk", referencedColumnName="codigo_embargo_pk")
     */
    protected $embargoRel;

    /**
     * @return mixed
     */
    public function getCodigoEmbargoPagoPk()
    {
        return $this->codigoEmbargoPagoPk;
    }

    /**
     * @param mixed $codigoEmbargoPagoPk
     */
    public function setCodigoEmbargoPagoPk($codigoEmbargoPagoPk): void
    {
        $this->codigoEmbargoPagoPk = $codigoEmbargoPagoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEmbargoFk()
    {
        return $this->codigoEmbargoFk;
    }

    /**
     * @param mixed $codigoEmbargoFk
     */
    public function setCodigoEmbargoFk($codigoEmbargoFk): void
    {
        $this->codigoEmbargoFk = $codigoEmbargoFk;
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
    public function getVrCuota()
    {
        return $this->vrCuota;
    }

    /**
     * @param mixed $vrCuota
     */
    public function setVrCuota($vrCuota): void
    {
        $this->vrCuota = $vrCuota;
    }

    /**
     * @return mixed
     */
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * @param mixed $saldo
     */
    public function setSaldo($saldo): void
    {
        $this->saldo = $saldo;
    }

    /**
     * @return mixed
     */
    public function getNumeroCuotaActual()
    {
        return $this->numeroCuotaActual;
    }

    /**
     * @param mixed $numeroCuotaActual
     */
    public function setNumeroCuotaActual($numeroCuotaActual): void
    {
        $this->numeroCuotaActual = $numeroCuotaActual;
    }

    /**
     * @return mixed
     */
    public function getFechaPago()
    {
        return $this->fechaPago;
    }

    /**
     * @param mixed $fechaPago
     */
    public function setFechaPago($fechaPago): void
    {
        $this->fechaPago = $fechaPago;
    }

    /**
     * @return mixed
     */
    public function getEmbargoRel()
    {
        return $this->embargoRel;
    }

    /**
     * @param mixed $embargoRel
     */
    public function setEmbargoRel($embargoRel): void
    {
        $this->embargoRel = $embargoRel;
    }
}
