<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarReciboTipoRepository")
 */
class CarReciboTipo
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_recibo_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoReciboTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_cuenta_cliente_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaClienteFk;

    /**
     * @ORM\Column(name="tipo_cuenta_cliente", type="bigint")
     */
    private $tipoCuentaCliente = 1;

    /**
     * @ORM\Column(name="codigo_cuenta_retencion_iva_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaRetencionIvaFk;

    /**
     * @ORM\Column(name="tipo_cuenta_retencion_iva", type="bigint")
     */
    private $tipoCuentaRetencionIva = 1;

    /**
     * @ORM\Column(name="codigo_cuenta_retencion_ica_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaRetencionIcaFk;

    /**
     * @ORM\Column(name="tipo_cuenta_retencion_ica", type="bigint")
     */
    private $tipoCuentaRetencionIca = 1;

    /**
     * @ORM\Column(name="codigo_cuenta_retencion_fuente_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaRetencionFuenteFk;

    /**
     * @ORM\Column(name="tipo_cuenta_retencion_fuente", type="bigint")
     */
    private $tipoCuentaRetencionFuente = 1;

    /**
     * @ORM\OneToMany(targetEntity="CarRecibo", mappedBy="reciboTipoRel")
     */
    protected $recibosReciboTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoReciboTipoPk()
    {
        return $this->codigoReciboTipoPk;
    }

    /**
     * @param mixed $codigoReciboTipoPk
     */
    public function setCodigoReciboTipoPk($codigoReciboTipoPk): void
    {
        $this->codigoReciboTipoPk = $codigoReciboTipoPk;
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
    public function getTipoCuentaRetencionIva()
    {
        return $this->tipoCuentaRetencionIva;
    }

    /**
     * @param mixed $tipoCuentaRetencionIva
     */
    public function setTipoCuentaRetencionIva($tipoCuentaRetencionIva): void
    {
        $this->tipoCuentaRetencionIva = $tipoCuentaRetencionIva;
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
    public function getTipoCuentaRetencionIca()
    {
        return $this->tipoCuentaRetencionIca;
    }

    /**
     * @param mixed $tipoCuentaRetencionIca
     */
    public function setTipoCuentaRetencionIca($tipoCuentaRetencionIca): void
    {
        $this->tipoCuentaRetencionIca = $tipoCuentaRetencionIca;
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

    /**
     * @return mixed
     */
    public function getTipoCuentaRetencionFuente()
    {
        return $this->tipoCuentaRetencionFuente;
    }

    /**
     * @param mixed $tipoCuentaRetencionFuente
     */
    public function setTipoCuentaRetencionFuente($tipoCuentaRetencionFuente): void
    {
        $this->tipoCuentaRetencionFuente = $tipoCuentaRetencionFuente;
    }

    /**
     * @return mixed
     */
    public function getRecibosReciboTipoRel()
    {
        return $this->recibosReciboTipoRel;
    }

    /**
     * @param mixed $recibosReciboTipoRel
     */
    public function setRecibosReciboTipoRel($recibosReciboTipoRel): void
    {
        $this->recibosReciboTipoRel = $recibosReciboTipoRel;
    }


}
