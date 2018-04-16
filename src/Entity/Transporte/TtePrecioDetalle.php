<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TtePrecioDetalleRepository")
 */
class TtePrecioDetalle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoPrecioDetallePk;

    /**
     * @ORM\Column(name="codigo_precio_fk", type="integer", nullable=true)
     */
    private $codigoPrecioFk;

    /**
     * @ORM\Column(name="codigo_ciudad_origen_fk", type="string", length=20, nullable=true)
     */
    private $codigoCiudadOrigenFk;

    /**
     * @ORM\Column(name="codigo_ciudad_destino_fk", type="string", length=20, nullable=true)
     */
    private $codigoCiudadDestinoFk;

    /**
     * @ORM\Column(name="codigo_producto_fk", type="string", length=20, nullable=true)
     */
    private $codigoProductoFk;

    /**
     * @ORM\Column(name="vr_kilo", type="float")
     */
    private $vrKilo = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TtePrecio", inversedBy="preciosDetallesPrecioRel")
     * @ORM\JoinColumn(name="codigo_precio_fk", referencedColumnName="codigo_precio_pk")
     */
    private $precioRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCiudad", inversedBy="preciosDetallesCiudadOrigenRel")
     * @ORM\JoinColumn(name="codigo_ciudad_origen_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadOrigenRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCiudad", inversedBy="preciosDetallesCiudadDestinoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_destino_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadDestinoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteProducto", inversedBy="preciosDetallesProductoRel")
     * @ORM\JoinColumn(name="codigo_producto_fk", referencedColumnName="codigo_producto_pk")
     */
    private $productoRel;

    /**
     * @return mixed
     */
    public function getCodigoPrecioDetallePk()
    {
        return $this->codigoPrecioDetallePk;
    }

    /**
     * @param mixed $codigoPrecioDetallePk
     */
    public function setCodigoPrecioDetallePk($codigoPrecioDetallePk): void
    {
        $this->codigoPrecioDetallePk = $codigoPrecioDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPrecioFk()
    {
        return $this->codigoPrecioFk;
    }

    /**
     * @param mixed $codigoPrecioFk
     */
    public function setCodigoPrecioFk($codigoPrecioFk): void
    {
        $this->codigoPrecioFk = $codigoPrecioFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCiudadOrigenFk()
    {
        return $this->codigoCiudadOrigenFk;
    }

    /**
     * @param mixed $codigoCiudadOrigenFk
     */
    public function setCodigoCiudadOrigenFk($codigoCiudadOrigenFk): void
    {
        $this->codigoCiudadOrigenFk = $codigoCiudadOrigenFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCiudadDestinoFk()
    {
        return $this->codigoCiudadDestinoFk;
    }

    /**
     * @param mixed $codigoCiudadDestinoFk
     */
    public function setCodigoCiudadDestinoFk($codigoCiudadDestinoFk): void
    {
        $this->codigoCiudadDestinoFk = $codigoCiudadDestinoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoProductoFk()
    {
        return $this->codigoProductoFk;
    }

    /**
     * @param mixed $codigoProductoFk
     */
    public function setCodigoProductoFk($codigoProductoFk): void
    {
        $this->codigoProductoFk = $codigoProductoFk;
    }

    /**
     * @return mixed
     */
    public function getVrKilo()
    {
        return $this->vrKilo;
    }

    /**
     * @param mixed $vrKilo
     */
    public function setVrKilo($vrKilo): void
    {
        $this->vrKilo = $vrKilo;
    }

    /**
     * @return mixed
     */
    public function getPrecioRel()
    {
        return $this->precioRel;
    }

    /**
     * @param mixed $precioRel
     */
    public function setPrecioRel($precioRel): void
    {
        $this->precioRel = $precioRel;
    }

    /**
     * @return mixed
     */
    public function getCiudadOrigenRel()
    {
        return $this->ciudadOrigenRel;
    }

    /**
     * @param mixed $ciudadOrigenRel
     */
    public function setCiudadOrigenRel($ciudadOrigenRel): void
    {
        $this->ciudadOrigenRel = $ciudadOrigenRel;
    }

    /**
     * @return mixed
     */
    public function getCiudadDestinoRel()
    {
        return $this->ciudadDestinoRel;
    }

    /**
     * @param mixed $ciudadDestinoRel
     */
    public function setCiudadDestinoRel($ciudadDestinoRel): void
    {
        $this->ciudadDestinoRel = $ciudadDestinoRel;
    }

    /**
     * @return mixed
     */
    public function getProductoRel()
    {
        return $this->productoRel;
    }

    /**
     * @param mixed $productoRel
     */
    public function setProductoRel($productoRel): void
    {
        $this->productoRel = $productoRel;
    }



}

