<?php


namespace App\Entity\Financiero;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Financiero\FinSaldoRepository")
 */
class FinSaldo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_saldo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSaldoPk;

    /**
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio = 0;

    /**
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes = 0;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaFk;

    /**
     * @ORM\Column(name="vr_debito", type="float", nullable=true, options={"default" : 0})
     */
    private $vrDebito = 0;

    /**
     * @ORM\Column(name="vr_credito", type="float", nullable=true, options={"default" : 0})
     */
    private $vrCredito = 0;

    /**
     * @ORM\Column(name="vr_saldo_anterior", type="float", nullable=true, options={"default" : 0})
     */
    private $vrSaldoAnterior = 0;

    /**
     * @ORM\Column(name="vr_saldo_final", type="float", nullable=true, options={"default" : 0})
     */
    private $vrSaldoFinal = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Financiero\FinCuenta", inversedBy="saldosCuentaRel")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;

    /**
     * @return mixed
     */
    public function getCodigoSaldoPk()
    {
        return $this->codigoSaldoPk;
    }

    /**
     * @param mixed $codigoSaldoPk
     */
    public function setCodigoSaldoPk( $codigoSaldoPk ): void
    {
        $this->codigoSaldoPk = $codigoSaldoPk;
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
    public function setCodigoCuentaFk( $codigoCuentaFk ): void
    {
        $this->codigoCuentaFk = $codigoCuentaFk;
    }

    /**
     * @return mixed
     */
    public function getVrDebito()
    {
        return $this->vrDebito;
    }

    /**
     * @param mixed $vrDebito
     */
    public function setVrDebito( $vrDebito ): void
    {
        $this->vrDebito = $vrDebito;
    }

    /**
     * @return mixed
     */
    public function getVrCredito()
    {
        return $this->vrCredito;
    }

    /**
     * @param mixed $vrCredito
     */
    public function setVrCredito( $vrCredito ): void
    {
        $this->vrCredito = $vrCredito;
    }

    /**
     * @return mixed
     */
    public function getVrSaldoAnterior()
    {
        return $this->vrSaldoAnterior;
    }

    /**
     * @param mixed $vrSaldoAnterior
     */
    public function setVrSaldoAnterior( $vrSaldoAnterior ): void
    {
        $this->vrSaldoAnterior = $vrSaldoAnterior;
    }

    /**
     * @return mixed
     */
    public function getVrSaldoFinal()
    {
        return $this->vrSaldoFinal;
    }

    /**
     * @param mixed $vrSaldoFinal
     */
    public function setVrSaldoFinal( $vrSaldoFinal ): void
    {
        $this->vrSaldoFinal = $vrSaldoFinal;
    }

    /**
     * @return mixed
     */
    public function getCuentaRel()
    {
        return $this->cuentaRel;
    }

    /**
     * @param mixed $cuentaRel
     */
    public function setCuentaRel( $cuentaRel ): void
    {
        $this->cuentaRel = $cuentaRel;
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
    public function setAnio( $anio ): void
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
    public function setMes( $mes ): void
    {
        $this->mes = $mes;
    }



}

