<?php

namespace App\Entity\Tesoreria;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Tesoreria\TesCompraDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TesCompraDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoCompraDetallePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $codigoCompraDetallePk;

    /**
     * @ORM\Column(name="codigo_compra_fk" , type="integer")
     */
    private $codigoCompraFk;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaFk;

    /**
     * @ORM\Column(name="codigo_tercero_fk" , type="integer", nullable=true)
     */
    private $codigoTerceroFk;

    /**
     * @ORM\Column(name="numero", type="string", length=30, nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="vr_pago", type="float", nullable=true)
     */
    private $vrPrecio = 0;

    /**
     * @ORM\Column(name="naturaleza", type="string", length=1, nullable=true)
     */
    private $naturaleza = 0;

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCentroCostoFk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tesoreria\TesCompra" , inversedBy="comprasDetallesCompraRel")
     * @ORM\JoinColumn(name="codigo_compra_fk" , referencedColumnName="codigo_compra_pk")
     */
    private $compraRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Financiero\FinCuenta", inversedBy="comprasDetallesCuentaRel")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;

    /**
     * @ORM\ManyToOne(targetEntity="TesTercero", inversedBy="comprasDetallesTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Financiero\FinCentroCosto", inversedBy="comprasDetallesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;

    /**
     * @return mixed
     */
    public function getCodigoCompraDetallePk()
    {
        return $this->codigoCompraDetallePk;
    }

    /**
     * @param mixed $codigoCompraDetallePk
     */
    public function setCodigoCompraDetallePk($codigoCompraDetallePk): void
    {
        $this->codigoCompraDetallePk = $codigoCompraDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCompraFk()
    {
        return $this->codigoCompraFk;
    }

    /**
     * @param mixed $codigoCompraFk
     */
    public function setCodigoCompraFk($codigoCompraFk): void
    {
        $this->codigoCompraFk = $codigoCompraFk;
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
    public function getVrPrecio()
    {
        return $this->vrPrecio;
    }

    /**
     * @param mixed $vrPrecio
     */
    public function setVrPrecio($vrPrecio): void
    {
        $this->vrPrecio = $vrPrecio;
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
    public function getCompraRel()
    {
        return $this->compraRel;
    }

    /**
     * @param mixed $compraRel
     */
    public function setCompraRel($compraRel): void
    {
        $this->compraRel = $compraRel;
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
