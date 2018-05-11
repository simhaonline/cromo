<?php


namespace App\Entity\Contabilidad;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Contabilidad\CtbRegistroRepository")
 */
class CtbRegistro
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_registro_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRegistroPk;

    /**
     * @ORM\Column(name="codigo_comprobante_fk", type="string", length=20, nullable=true)
     */
    private $codigoComprobanteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20)
     */
    private $codigoCuentaFk;

    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */
    private $codigoTerceroFk;

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCentroCostoFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="debito", type="float")
     */
    private $debito = 0;

    /**
     * @ORM\Column(name="credito", type="float")
     */
    private $credito = 0;

    /**
     * @ORM\Column(name="base", type="float")
     */
    private $base = 0;

    /**
     * @ORM\Column(name="descripcion_contable", type="text", nullable=true)
     */
    private $descripcionContable;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contabilidad\CtbCuenta", inversedBy="ctbRegistrosCuentaRel")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contabilidad\CtbTercero", inversedBy="ctbRegistrosTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contabilidad\CtbComprobante", inversedBy="ctbRegistrosComprobanteRel")
     * @ORM\JoinColumn(name="codigo_comprobante_fk", referencedColumnName="codigo_comprobante_pk")
     */
    protected $comprobanteRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contabilidad\CtbCentroCosto", inversedBy="ctbRegistrosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    private $centroCostoRel;

    /**
     * @return mixed
     */
    public function getCodigoRegistroPk()
    {
        return $this->codigoRegistroPk;
    }

    /**
     * @param mixed $codigoRegistroPk
     */
    public function setCodigoRegistroPk($codigoRegistroPk): void
    {
        $this->codigoRegistroPk = $codigoRegistroPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoComprobanteFk()
    {
        return $this->codigoComprobanteFk;
    }

    /**
     * @param mixed $codigoComprobanteFk
     */
    public function setCodigoComprobanteFk($codigoComprobanteFk): void
    {
        $this->codigoComprobanteFk = $codigoComprobanteFk;
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
    public function setCodigoCuentaFk($codigoCuentaFk): void
    {
        $this->codigoCuentaFk = $codigoCuentaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoTerceroFk()
    {
        return $this->codigoTerceroFk;
    }

    /**
     * @param mixed $codigoTerceroFk
     */
    public function setCodigoTerceroFk($codigoTerceroFk): void
    {
        $this->codigoTerceroFk = $codigoTerceroFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * @param mixed $codigoCentroCostoFk
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk): void
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha): void
    {
        $this->fecha = $fecha;
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
    public function getDebito()
    {
        return $this->debito;
    }

    /**
     * @param mixed $debito
     */
    public function setDebito($debito): void
    {
        $this->debito = $debito;
    }

    /**
     * @return mixed
     */
    public function getCredito()
    {
        return $this->credito;
    }

    /**
     * @param mixed $credito
     */
    public function setCredito($credito): void
    {
        $this->credito = $credito;
    }

    /**
     * @return mixed
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * @param mixed $base
     */
    public function setBase($base): void
    {
        $this->base = $base;
    }

    /**
     * @return mixed
     */
    public function getDescripcionContable()
    {
        return $this->descripcionContable;
    }

    /**
     * @param mixed $descripcionContable
     */
    public function setDescripcionContable($descripcionContable): void
    {
        $this->descripcionContable = $descripcionContable;
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
    public function setCuentaRel($cuentaRel): void
    {
        $this->cuentaRel = $cuentaRel;
    }

    /**
     * @return mixed
     */
    public function getTerceroRel()
    {
        return $this->terceroRel;
    }

    /**
     * @param mixed $terceroRel
     */
    public function setTerceroRel($terceroRel): void
    {
        $this->terceroRel = $terceroRel;
    }

    /**
     * @return mixed
     */
    public function getComprobanteRel()
    {
        return $this->comprobanteRel;
    }

    /**
     * @param mixed $comprobanteRel
     */
    public function setComprobanteRel($comprobanteRel): void
    {
        $this->comprobanteRel = $comprobanteRel;
    }

    /**
     * @return mixed
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }

    /**
     * @param mixed $centroCostoRel
     */
    public function setCentroCostoRel($centroCostoRel): void
    {
        $this->centroCostoRel = $centroCostoRel;
    }

}

