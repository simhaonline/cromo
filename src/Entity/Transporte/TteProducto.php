<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteProductoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteProducto
{
    public $infoLog = [
        "primaryKey" => "codigoProductoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoProductoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_transporte", type="string", length=50, nullable=true)
     */
    private $codigoTransporte;

    /**
     * @ORM\Column(name="orden", type="integer", nullable=true, options={"default" : 0})
     */
    private $orden = 0;

    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="productoRel")
     */
    protected $guiasProductoRel;

    /**
     * @ORM\OneToMany(targetEntity="TtePrecioDetalle", mappedBy="productoRel")
     */
    protected $preciosDetallesProductoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuiaDetalle", mappedBy="productoRel")
     */
    protected $guiasDetallesProductoRel;

    /**
     * @return mixed
     */
    public function getCodigoProductoPk()
    {
        return $this->codigoProductoPk;
    }

    /**
     * @param mixed $codigoProductoPk
     */
    public function setCodigoProductoPk($codigoProductoPk): void
    {
        $this->codigoProductoPk = $codigoProductoPk;
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
    public function getPreciosDetallesProductoRel()
    {
        return $this->preciosDetallesProductoRel;
    }

    /**
     * @param mixed $preciosDetallesProductoRel
     */
    public function setPreciosDetallesProductoRel($preciosDetallesProductoRel): void
    {
        $this->preciosDetallesProductoRel = $preciosDetallesProductoRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoTransporte()
    {
        return $this->codigoTransporte;
    }

    /**
     * @param mixed $codigoTransporte
     */
    public function setCodigoTransporte($codigoTransporte): void
    {
        $this->codigoTransporte = $codigoTransporte;
    }

    /**
     * @return mixed
     */
    public function getGuiasProductoRel()
    {
        return $this->guiasProductoRel;
    }

    /**
     * @param mixed $guiasProductoRel
     */
    public function setGuiasProductoRel($guiasProductoRel): void
    {
        $this->guiasProductoRel = $guiasProductoRel;
    }

    /**
     * @return mixed
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * @param mixed $orden
     */
    public function setOrden($orden): void
    {
        $this->orden = $orden;
    }

    /**
     * @return mixed
     */
    public function getGuiasDetallesProductoRel()
    {
        return $this->guiasDetallesProductoRel;
    }

    /**
     * @param mixed $guiasDetallesProductoRel
     */
    public function setGuiasDetallesProductoRel($guiasDetallesProductoRel): void
    {
        $this->guiasDetallesProductoRel = $guiasDetallesProductoRel;
    }



}

