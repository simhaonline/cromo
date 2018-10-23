<?php

namespace App\Entity\Compra;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Compra\ComCuentaPagarTipoRepository")
 */
class ComCuentaPagarTipo
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="codigo_cuenta_pagar_tipo_pk",type="string", length=10)
     */
    private $codigoCuentaPagarTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="operacion", type="integer")
     */
    private $operacion = 0;

    /**
     * @ORM\Column(name="prefijo", type="string", length=5, nullable=true)
     */
    private $prefijo;

    /**
     * @ORM\Column(name="codigo_cuenta_proveedor_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaProveedorFk;

    /**
     * @ORM\Column(name="codigo_cuenta_retencion_iva_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaRetencionIvaFk;

    /**
     * @ORM\Column(name="codigo_cuenta_retencion_fuente_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaRetencionFuenteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_industria_comercio_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaIndustriaComercioFk;

    /**
     * @ORM\Column(name="codigo_cuenta_ajuste_peso_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaAjustePesoFk;

    /**
     * @ORM\Column(name="codigo_cuenta_descuento_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaDescuentoFk;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compra\ComCompraTipo" , mappedBy="cuentaPagarTipoRel")
     */
    private $comprasTipoCuentaPagarRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compra\ComCuentaPagar" , mappedBy="cuentaPagarTipoRel")
     */
    protected $cuentasPagarCuentaPagarTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoCuentaPagarTipoPk()
    {
        return $this->codigoCuentaPagarTipoPk;
    }

    /**
     * @param mixed $codigoCuentaPagarTipoPk
     */
    public function setCodigoCuentaPagarTipoPk($codigoCuentaPagarTipoPk): void
    {
        $this->codigoCuentaPagarTipoPk = $codigoCuentaPagarTipoPk;
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
    public function getPrefijo()
    {
        return $this->prefijo;
    }

    /**
     * @param mixed $prefijo
     */
    public function setPrefijo($prefijo): void
    {
        $this->prefijo = $prefijo;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaProveedorFk()
    {
        return $this->codigoCuentaProveedorFk;
    }

    /**
     * @param mixed $codigoCuentaProveedorFk
     */
    public function setCodigoCuentaProveedorFk($codigoCuentaProveedorFk): void
    {
        $this->codigoCuentaProveedorFk = $codigoCuentaProveedorFk;
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
    public function getCodigoCuentaIndustriaComercioFk()
    {
        return $this->codigoCuentaIndustriaComercioFk;
    }

    /**
     * @param mixed $codigoCuentaIndustriaComercioFk
     */
    public function setCodigoCuentaIndustriaComercioFk($codigoCuentaIndustriaComercioFk): void
    {
        $this->codigoCuentaIndustriaComercioFk = $codigoCuentaIndustriaComercioFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaAjustePesoFk()
    {
        return $this->codigoCuentaAjustePesoFk;
    }

    /**
     * @param mixed $codigoCuentaAjustePesoFk
     */
    public function setCodigoCuentaAjustePesoFk($codigoCuentaAjustePesoFk): void
    {
        $this->codigoCuentaAjustePesoFk = $codigoCuentaAjustePesoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaDescuentoFk()
    {
        return $this->codigoCuentaDescuentoFk;
    }

    /**
     * @param mixed $codigoCuentaDescuentoFk
     */
    public function setCodigoCuentaDescuentoFk($codigoCuentaDescuentoFk): void
    {
        $this->codigoCuentaDescuentoFk = $codigoCuentaDescuentoFk;
    }

    /**
     * @return mixed
     */
    public function getComprasTipoCuentaPagarRel()
    {
        return $this->comprasTipoCuentaPagarRel;
    }

    /**
     * @param mixed $comprasTipoCuentaPagarRel
     */
    public function setComprasTipoCuentaPagarRel($comprasTipoCuentaPagarRel): void
    {
        $this->comprasTipoCuentaPagarRel = $comprasTipoCuentaPagarRel;
    }


}
