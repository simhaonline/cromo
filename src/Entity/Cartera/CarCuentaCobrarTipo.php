<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarCuentaCobrarTipoRepository")
 */
class CarCuentaCobrarTipo
{

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=10, nullable=false, unique=true)
     */
    private $codigoCuentaCobrarTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="operacion", type="integer")
     */
    private $operacion = 0;

    /**
     * @ORM\Column(name="saldo_inicial", type="boolean")
     */
    private $saldoInicial = 0;

    /**
     * @ORM\Column(name="tipo_cuenta_cliente", type="integer", nullable=true)
     * 1-Debito
     * 2-Credito
     */
    private $tipoCuentaCliente = 1;

    /**
     * @ORM\Column(name="codigo_cuenta_cliente_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaClienteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_retencion_iva_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaRetencionIvaFk;

    /**
     * @ORM\Column(name="codigo_cuenta_retencion_ica_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaRetencionIcaFk;

    /**
     * @ORM\Column(name="codigo_cuenta_retencion_fuente_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaRetencionFuenteFk;

    /**
     * @return mixed
     */
    public function getCodigoCuentaCobrarTipoPk()
    {
        return $this->codigoCuentaCobrarTipoPk;
    }

    /**
     * @param mixed $codigoCuentaCobrarTipoPk
     */
    public function setCodigoCuentaCobrarTipoPk($codigoCuentaCobrarTipoPk): void
    {
        $this->codigoCuentaCobrarTipoPk = $codigoCuentaCobrarTipoPk;
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
    public function getOperacion()
    {
        return $this->operacion;
    }

    /**
     * @param mixed $operacion
     */
    public function setOperacion($operacion): void
    {
        $this->operacion = $operacion;
    }

    /**
     * @return mixed
     */
    public function getSaldoInicial()
    {
        return $this->saldoInicial;
    }

    /**
     * @param mixed $saldoInicial
     */
    public function setSaldoInicial($saldoInicial): void
    {
        $this->saldoInicial = $saldoInicial;
    }

    /**
     * @return mixed
     */
    public function getTipoCuentaCliente()
    {
        return $this->tipoCuentaCliente;
    }

    /**
     * @param mixed $tipoCuentaCliente
     */
    public function setTipoCuentaCliente($tipoCuentaCliente): void
    {
        $this->tipoCuentaCliente = $tipoCuentaCliente;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaClienteFk()
    {
        return $this->codigoCuentaClienteFk;
    }

    /**
     * @param mixed $codigoCuentaClienteFk
     */
    public function setCodigoCuentaClienteFk($codigoCuentaClienteFk): void
    {
        $this->codigoCuentaClienteFk = $codigoCuentaClienteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaRetencionIvaFk()
    {
        return $this->codigoCuentaRetencionIvaFk;
    }

    /**
     * @param mixed $codigoCuentaRetencionIvaFk
     */
    public function setCodigoCuentaRetencionIvaFk($codigoCuentaRetencionIvaFk): void
    {
        $this->codigoCuentaRetencionIvaFk = $codigoCuentaRetencionIvaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaRetencionIcaFk()
    {
        return $this->codigoCuentaRetencionIcaFk;
    }

    /**
     * @param mixed $codigoCuentaRetencionIcaFk
     */
    public function setCodigoCuentaRetencionIcaFk($codigoCuentaRetencionIcaFk): void
    {
        $this->codigoCuentaRetencionIcaFk = $codigoCuentaRetencionIcaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaRetencionFuenteFk()
    {
        return $this->codigoCuentaRetencionFuenteFk;
    }

    /**
     * @param mixed $codigoCuentaRetencionFuenteFk
     */
    public function setCodigoCuentaRetencionFuenteFk($codigoCuentaRetencionFuenteFk): void
    {
        $this->codigoCuentaRetencionFuenteFk = $codigoCuentaRetencionFuenteFk;
    }


}
