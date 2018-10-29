<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * RhuEmbargoPago
 *
 * @ORM\Table(name="rhu_embargo_pago")
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuEmbargoPagoRepository")
 */
class RhuEmbargoPago
{
    /**
     * @var int
     *
     * @ORM\Column(name="codigo_embargo_pago_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    
    private $codigoEmbargoPagoPk;

    /**
     * @var int
     *
     * @ORM\Column(name="codigo_embargo_fk", type="integer", nullable=true)
     */
    private $codigoEmbargoFk;
    
    /**
     * @var int
     *
     * @ORM\Column(name="codigo_pago_fk", type="integer", nullable=true)
     */
    private $codigoPagoFk;

    /**
     * @var float
     *
     * @ORM\Column(name="vr_cuota", type="float", nullable=true)
     */
    private $vrCuota;

    /**
     * @var float
     *
     * @ORM\Column(name="saldo", type="float", nullable=true)
     */
    private $saldo;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_cuota_actual", type="integer", nullable=true)
     */
    private $numeroCuotaActual;

    /**
     * @var \DateTime
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
     * @ORM\ManyToOne(targetEntity="RhuPago", inversedBy="embargoPagosPagoRel")
     * @ORM\JoinColumn(name="codigo_pago_fk", referencedColumnName="codigo_pago_pk")
     */
    protected $pagoRel;

    /**
     * Get codigoEmbargoPagoPk
     *
     * @return integer
     */
    public function getCodigoEmbargoPagoPk()
    {
        return $this->codigoEmbargoPagoPk;
    }

    /**
     * Set codigoEmbargoFk
     *
     * @param integer $codigoEmbargoFk
     *
     * @return RhuEmbargoPago
     */
    public function setCodigoEmbargoFk($codigoEmbargoFk)
    {
        $this->codigoEmbargoFk = $codigoEmbargoFk;

        return $this;
    }

    /**
     * Get codigoEmbargoFk
     *
     * @return integer
     */
    public function getCodigoEmbargoFk()
    {
        return $this->codigoEmbargoFk;
    }

    /**
     * Set codigoPagoFk
     *
     * @param integer $codigoPagoFk
     *
     * @return RhuEmbargoPago
     */
    public function setCodigoPagoFk($codigoPagoFk)
    {
        $this->codigoPagoFk = $codigoPagoFk;

        return $this;
    }

    /**
     * Get codigoPagoFk
     *
     * @return integer
     */
    public function getCodigoPagoFk()
    {
        return $this->codigoPagoFk;
    }

    /**
     * Set vrCuota
     *
     * @param float $vrCuota
     *
     * @return RhuEmbargoPago
     */
    public function setVrCuota($vrCuota)
    {
        $this->vrCuota = $vrCuota;

        return $this;
    }

    /**
     * Get vrCuota
     *
     * @return float
     */
    public function getVrCuota()
    {
        return $this->vrCuota;
    }

    /**
     * Set saldo
     *
     * @param float $saldo
     *
     * @return RhuEmbargoPago
     */
    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;

        return $this;
    }

    /**
     * Get saldo
     *
     * @return float
     */
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * Set numeroCuotaActual
     *
     * @param integer $numeroCuotaActual
     *
     * @return RhuEmbargoPago
     */
    public function setNumeroCuotaActual($numeroCuotaActual)
    {
        $this->numeroCuotaActual = $numeroCuotaActual;

        return $this;
    }

    /**
     * Get numeroCuotaActual
     *
     * @return integer
     */
    public function getNumeroCuotaActual()
    {
        return $this->numeroCuotaActual;
    }

    /**
     * Set fechaPago
     *
     * @param \DateTime $fechaPago
     *
     * @return RhuEmbargoPago
     */
    public function setFechaPago($fechaPago)
    {
        $this->fechaPago = $fechaPago;

        return $this;
    }

    /**
     * Get fechaPago
     *
     * @return \DateTime
     */
    public function getFechaPago()
    {
        return $this->fechaPago;
    }

    /**
     * Set embargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmbargo $embargoRel
     *
     * @return RhuEmbargoPago
     */
    public function setEmbargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmbargo $embargoRel = null)
    {
        $this->embargoRel = $embargoRel;

        return $this;
    }

    /**
     * Get embargoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmbargo
     */
    public function getEmbargoRel()
    {
        return $this->embargoRel;
    }

    /**
     * Set pagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagoRel
     *
     * @return RhuEmbargoPago
     */
    public function setPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagoRel = null)
    {
        $this->pagoRel = $pagoRel;

        return $this;
    }

    /**
     * Get pagoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPago
     */
    public function getPagoRel()
    {
        return $this->pagoRel;
    }
}
