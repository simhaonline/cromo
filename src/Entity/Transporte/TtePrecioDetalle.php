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
     * @ORM\Column(name="vr_peso", type="float", options={"default" : 0})
     */
    private $vrPeso = 0;

    /**
     * @ORM\Column(name="vr_unidad", type="float", options={"default" : 0})
     */
    private $vrUnidad = 0;

    /**
     * @ORM\Column(name="peso_tope", type="float", options={"default" : 0})
     */
    private $pesoTope = 0;

    /**
     * @ORM\Column(name="vr_peso_tope", type="float", options={"default" : 0})
     */
    private $vrPesoTope = 0;

    /**
     * @ORM\Column(name="vr_peso_tope_adicional", type="float", options={"default" : 0})
     */
    private $vrPesoTopeAdicional = 0;

    /**
     * @ORM\Column(name="minimo", type="integer", options={"default" : 0})
     */
    private $minimo = 0;


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
    public function getVrPeso()
    {
        return $this->vrPeso;
    }

    /**
     * @param mixed $vrPeso
     */
    public function setVrPeso($vrPeso): void
    {
        $this->vrPeso = $vrPeso;
    }

    /**
     * @return mixed
     */
    public function getVrUnidad()
    {
        return $this->vrUnidad;
    }

    /**
     * @param mixed $vrUnidad
     */
    public function setVrUnidad($vrUnidad): void
    {
        $this->vrUnidad = $vrUnidad;
    }

    /**
     * @return mixed
     */
    public function getPesoTope()
    {
        return $this->pesoTope;
    }

    /**
     * @param mixed $pesoTope
     */
    public function setPesoTope($pesoTope): void
    {
        $this->pesoTope = $pesoTope;
    }

    /**
     * @return mixed
     */
    public function getVrPesoTope()
    {
        return $this->vrPesoTope;
    }

    /**
     * @param mixed $vrPesoTope
     */
    public function setVrPesoTope($vrPesoTope): void
    {
        $this->vrPesoTope = $vrPesoTope;
    }

    /**
     * @return mixed
     */
    public function getVrPesoTopeAdicional()
    {
        return $this->vrPesoTopeAdicional;
    }

    /**
     * @param mixed $vrPesoTopeAdicional
     */
    public function setVrPesoTopeAdicional($vrPesoTopeAdicional): void
    {
        $this->vrPesoTopeAdicional = $vrPesoTopeAdicional;
    }

    /**
     * @return mixed
     */
    public function getMinimo()
    {
        return $this->minimo;
    }

    /**
     * @param mixed $minimo
     */
    public function setMinimo($minimo): void
    {
        $this->minimo = $minimo;
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

