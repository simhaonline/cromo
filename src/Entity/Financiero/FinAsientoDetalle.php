<?php


namespace App\Entity\Financiero;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Financiero\FinAsientoDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class FinAsientoDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoAsientoDetallePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_asiento_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAsientoDetallePk;

    /**
     * @ORM\Column(name="codigo_asiento_fk", type="integer", nullable=true)
     */
    private $codigoAsientoFk;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaFk;

    /**
     * @ORM\Column(name="codigo_comprobante_fk", type="string", length=20, nullable=true)
     */
    private $codigoComprobanteFk;

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCentroCostoFk;

    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */
    private $codigoTerceroFk;

    /**
     * @ORM\Column(name="vr_debito", type="float", nullable=true, options={"default" : 0})
     */
    private $vrDebito = 0;

    /**
     * @ORM\Column(name="vr_credito", type="float", nullable=true, options={"default" : 0})
     */
    private $vrCredito = 0;

    /**
     * @ORM\Column(name="vr_base", type="float", nullable=true, options={"default" : 0})
     */
    private $vrBase = 0;

    /**
     * @ORM\Column(name="naturaleza", type="string", length=1, nullable=true)
     */
    private $naturaleza = 0;

    /**
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Financiero\FinAsiento", inversedBy="asientosDetallesAsientoRel")
     * @ORM\JoinColumn(name="codigo_asiento_fk", referencedColumnName="codigo_asiento_pk")
     */
    protected $asientoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Financiero\FinComprobante", inversedBy="asientosDetallesComprobanteRel")
     * @ORM\JoinColumn(name="codigo_comprobante_fk", referencedColumnName="codigo_comprobante_pk")
     */
    protected $comprobanteRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Financiero\FinCentroCosto", inversedBy="asientosDetallesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    private $centroCostoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Financiero\FinCuenta", inversedBy="asientosDetallesCuentaRel")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Financiero\FinTercero", inversedBy="asientosDetallesTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;

    /**
     * @return mixed
     */
    public function getCodigoAsientoDetallePk()
    {
        return $this->codigoAsientoDetallePk;
    }

    /**
     * @param mixed $codigoAsientoDetallePk
     */
    public function setCodigoAsientoDetallePk($codigoAsientoDetallePk): void
    {
        $this->codigoAsientoDetallePk = $codigoAsientoDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoAsientoFk()
    {
        return $this->codigoAsientoFk;
    }

    /**
     * @param mixed $codigoAsientoFk
     */
    public function setCodigoAsientoFk($codigoAsientoFk): void
    {
        $this->codigoAsientoFk = $codigoAsientoFk;
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
    public function getVrDebito()
    {
        return $this->vrDebito;
    }

    /**
     * @param mixed $vrDebito
     */
    public function setVrDebito($vrDebito): void
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
    public function setVrCredito($vrCredito): void
    {
        $this->vrCredito = $vrCredito;
    }

    /**
     * @return mixed
     */
    public function getVrBase()
    {
        return $this->vrBase;
    }

    /**
     * @param mixed $vrBase
     */
    public function setVrBase($vrBase): void
    {
        $this->vrBase = $vrBase;
    }

    /**
     * @return mixed
     */
    public function getNaturaleza()
    {
        return $this->naturaleza;
    }

    /**
     * @param mixed $naturaleza
     */
    public function setNaturaleza($naturaleza): void
    {
        $this->naturaleza = $naturaleza;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion): void
    {
        $this->descripcion = $descripcion;
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
    public function getAsientoRel()
    {
        return $this->asientoRel;
    }

    /**
     * @param mixed $asientoRel
     */
    public function setAsientoRel($asientoRel): void
    {
        $this->asientoRel = $asientoRel;
    }



}

