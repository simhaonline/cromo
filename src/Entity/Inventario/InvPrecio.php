<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvPrecioRepository")
 */
class InvPrecio
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoPrecioPk;

    /**
     * @ORM\Column(name="fecha_vence", type="date")
     */
    private $fechaVence;

    /**
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     * @Assert\Length( max = 255, maxMessage="El campo no puede contener mas de 255 caracteres")
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="InvPrecioDetalle", mappedBy="precioRel")
     */
    protected $preciosDetallesPrecioRel;

    /**
     * @ORM\OneToMany(targetEntity="InvTercero",mappedBy="precioVentaRel")
     */
    protected $tercerosPrecioVentaRel;

    /**
     * @ORM\OneToMany(targetEntity="InvTercero",mappedBy="precioCompraRel")
     */
    protected $tercerosPrecioCompraRel;

    /**
     * @ORM\Column(name="compra", type="boolean", nullable=true)
     */
    private $compra = false;

    /**
     * @ORM\Column(name="venta", type="boolean", nullable=true)
     */
    private $venta = false;

    /**
     * @return mixed
     */
    public function getCodigoPrecioPk()
    {
        return $this->codigoPrecioPk;
    }

    /**
     * @param mixed $codigoPrecioPk
     */
    public function setCodigoPrecioPk($codigoPrecioPk): void
    {
        $this->codigoPrecioPk = $codigoPrecioPk;
    }

    /**
     * @return mixed
     */
    public function getFechaVence()
    {
        return $this->fechaVence;
    }

    /**
     * @param mixed $fechaVence
     */
    public function setFechaVence($fechaVence): void
    {
        $this->fechaVence = $fechaVence;
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
    public function getPreciosDetallesPrecioRel()
    {
        return $this->preciosDetallesPrecioRel;
    }

    /**
     * @param mixed $preciosDetallesPrecioRel
     */
    public function setPreciosDetallesPrecioRel($preciosDetallesPrecioRel): void
    {
        $this->preciosDetallesPrecioRel = $preciosDetallesPrecioRel;
    }

    /**
     * @return mixed
     */
    public function getTercerosPrecioVentaRel()
    {
        return $this->tercerosPrecioVentaRel;
    }

    /**
     * @param mixed $tercerosPrecioVentaRel
     */
    public function setTercerosPrecioVentaRel($tercerosPrecioVentaRel): void
    {
        $this->tercerosPrecioVentaRel = $tercerosPrecioVentaRel;
    }

    /**
     * @return mixed
     */
    public function getCompra()
    {
        return $this->compra;
    }

    /**
     * @param mixed $compra
     */
    public function setCompra($compra): void
    {
        $this->compra = $compra;
    }

    /**
     * @return mixed
     */
    public function getVenta()
    {
        return $this->venta;
    }

    /**
     * @param mixed $venta
     */
    public function setVenta($venta): void
    {
        $this->venta = $venta;
    }

    /**
     * @return mixed
     */
    public function getTercerosPrecioCompraRel()
    {
        return $this->tercerosPrecioCompraRel;
    }

    /**
     * @param mixed $tercerosPrecioCompraRel
     */
    public function setTercerosPrecioCompraRel($tercerosPrecioCompraRel): void
    {
        $this->tercerosPrecioCompraRel = $tercerosPrecioCompraRel;
    }



}

