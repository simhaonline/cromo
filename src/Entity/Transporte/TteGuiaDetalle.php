<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteGuiaDetalleRepository")
 */
class TteGuiaDetalle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoGuiaDetallePk;

    /**
     * @ORM\Column(name="codigo_guia_fk", type="integer", nullable=true)
     */
    private $codigoGuiaFk;

    /**
     * @ORM\Column(name="codigo_producto_fk", type="string", length=20, nullable=true)
     */
    private $codigoProductoFk;

    /**
     * @ORM\Column(name="unidades", type="float", options={"default" : 0})
     */
    private $unidades = 0;

    /**
     * @ORM\Column(name="peso_real", type="float", options={"default" : 0})
     */
    private $pesoReal = 0;

    /**
     * @ORM\Column(name="peso_volumen", type="float", options={"default" : 0})
     */
    private $pesoVolumen = 0;

    /**
     * @ORM\Column(name="peso_facturado", type="float", options={"default" : 0})
     */
    private $pesoFacturado = 0;

    /**
     * @ORM\Column(name="vr_flete", type="float", options={"default" : 0})
     */
    private $vrFlete = 0;

    /**
     * @ORM\ManyToOne(targetEntity="TteGuia", inversedBy="guiasDetallesGuiaRel")
     * @ORM\JoinColumn(name="codigo_guia_fk", referencedColumnName="codigo_guia_pk")
     */
    private $guiaRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteProducto", inversedBy="guiasDetallesProductoRel")
     * @ORM\JoinColumn(name="codigo_producto_fk", referencedColumnName="codigo_producto_pk")
     */
    private $productoRel;

    /**
     * @return mixed
     */
    public function getCodigoGuiaDetallePk()
    {
        return $this->codigoGuiaDetallePk;
    }

    /**
     * @param mixed $codigoGuiaDetallePk
     */
    public function setCodigoGuiaDetallePk($codigoGuiaDetallePk): void
    {
        $this->codigoGuiaDetallePk = $codigoGuiaDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoGuiaFk()
    {
        return $this->codigoGuiaFk;
    }

    /**
     * @param mixed $codigoGuiaFk
     */
    public function setCodigoGuiaFk($codigoGuiaFk): void
    {
        $this->codigoGuiaFk = $codigoGuiaFk;
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
    public function getUnidades()
    {
        return $this->unidades;
    }

    /**
     * @param mixed $unidades
     */
    public function setUnidades($unidades): void
    {
        $this->unidades = $unidades;
    }

    /**
     * @return mixed
     */
    public function getPesoReal()
    {
        return $this->pesoReal;
    }

    /**
     * @param mixed $pesoReal
     */
    public function setPesoReal($pesoReal): void
    {
        $this->pesoReal = $pesoReal;
    }

    /**
     * @return mixed
     */
    public function getPesoVolumen()
    {
        return $this->pesoVolumen;
    }

    /**
     * @param mixed $pesoVolumen
     */
    public function setPesoVolumen($pesoVolumen): void
    {
        $this->pesoVolumen = $pesoVolumen;
    }

    /**
     * @return mixed
     */
    public function getPesoFacturado()
    {
        return $this->pesoFacturado;
    }

    /**
     * @param mixed $pesoFacturado
     */
    public function setPesoFacturado($pesoFacturado): void
    {
        $this->pesoFacturado = $pesoFacturado;
    }

    /**
     * @return mixed
     */
    public function getVrFlete()
    {
        return $this->vrFlete;
    }

    /**
     * @param mixed $vrFlete
     */
    public function setVrFlete($vrFlete): void
    {
        $this->vrFlete = $vrFlete;
    }

    /**
     * @return mixed
     */
    public function getGuiaRel()
    {
        return $this->guiaRel;
    }

    /**
     * @param mixed $guiaRel
     */
    public function setGuiaRel($guiaRel): void
    {
        $this->guiaRel = $guiaRel;
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
