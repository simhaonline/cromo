<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteFacturaDetalleReliquidarRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteFacturaDetalleReliquidar
{
    public $infoLog = [
        "primaryKey" => "codigoFacturaDetallePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoFacturaDetalleReliquidarPk;

    /**
     * @ORM\Column(name="codigo_factura_fk", type="integer", nullable=true)
     */
    private $codigoFacturaFk;

    /**
     * @ORM\Column(name="codigo_factura_detalle_fk", type="integer", nullable=true)
     */
    private $codigoFacturaDetalleFk;

    /**
     * @ORM\Column(name="codigo_guia_fk", type="integer", nullable=true)
     */
    private $codigoGuiaFk;

    /**
     * @ORM\Column(name="fecha_ingreso", type="datetime", nullable=true)
     */
    private $fechaIngreso;

    /**
     * @ORM\Column(name="vr_flete", type="float", options={"default" : 0})
     */
    private $vrFlete = 0;

    /**
     * @ORM\Column(name="vr_manejo", type="float", options={"default" : 0})
     */
    private $vrManejo = 0;

    /**
     * @ORM\Column(name="peso_facturado", type="float", options={"default" : 0})
     */
    private $pesoFacturado = 0;

    /**
     * @ORM\Column(name="vr_flete_nuevo", type="float", options={"default" : 0})
     */
    private $vrFleteNuevo = 0;

    /**
     * @ORM\Column(name="vr_manejo_nuevo", type="float", options={"default" : 0})
     */
    private $vrManejoNuevo = 0;

    /**
     * @ORM\Column(name="peso_facturado_nuevo", type="float", options={"default" : 0})
     */
    private $pesoFacturadoNuevo = 0;

    /**
         * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteFacturaDetalle", inversedBy="facturasDetallesReliquidarFacturaDetalleRel")
     * @ORM\JoinColumn(name="codigo_factura_detalle_fk", referencedColumnName="codigo_factura_detalle_pk")
     */
    private $facturaDetalleRel;

    /**
     * @return mixed
     */
    public function getCodigoFacturaDetalleReliquidarPk()
    {
        return $this->codigoFacturaDetalleReliquidarPk;
    }

    /**
     * @param mixed $codigoFacturaDetalleReliquidarPk
     */
    public function setCodigoFacturaDetalleReliquidarPk($codigoFacturaDetalleReliquidarPk): void
    {
        $this->codigoFacturaDetalleReliquidarPk = $codigoFacturaDetalleReliquidarPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoFacturaFk()
    {
        return $this->codigoFacturaFk;
    }

    /**
     * @param mixed $codigoFacturaFk
     */
    public function setCodigoFacturaFk($codigoFacturaFk): void
    {
        $this->codigoFacturaFk = $codigoFacturaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoFacturaDetalleFk()
    {
        return $this->codigoFacturaDetalleFk;
    }

    /**
     * @param mixed $codigoFacturaDetalleFk
     */
    public function setCodigoFacturaDetalleFk($codigoFacturaDetalleFk): void
    {
        $this->codigoFacturaDetalleFk = $codigoFacturaDetalleFk;
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
    public function getVrManejo()
    {
        return $this->vrManejo;
    }

    /**
     * @param mixed $vrManejo
     */
    public function setVrManejo($vrManejo): void
    {
        $this->vrManejo = $vrManejo;
    }

    /**
     * @return mixed
     */
    public function getVrFleteNuevo()
    {
        return $this->vrFleteNuevo;
    }

    /**
     * @param mixed $vrFleteNuevo
     */
    public function setVrFleteNuevo($vrFleteNuevo): void
    {
        $this->vrFleteNuevo = $vrFleteNuevo;
    }

    /**
     * @return mixed
     */
    public function getVrManejoNuevo()
    {
        return $this->vrManejoNuevo;
    }

    /**
     * @param mixed $vrManejoNuevo
     */
    public function setVrManejoNuevo($vrManejoNuevo): void
    {
        $this->vrManejoNuevo = $vrManejoNuevo;
    }

    /**
     * @return mixed
     */
    public function getFacturaDetalleRel()
    {
        return $this->facturaDetalleRel;
    }

    /**
     * @param mixed $facturaDetalleRel
     */
    public function setFacturaDetalleRel($facturaDetalleRel): void
    {
        $this->facturaDetalleRel = $facturaDetalleRel;
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
    public function getPesoFacturadoNuevo()
    {
        return $this->pesoFacturadoNuevo;
    }

    /**
     * @param mixed $pesoFacturadoNuevo
     */
    public function setPesoFacturadoNuevo($pesoFacturadoNuevo): void
    {
        $this->pesoFacturadoNuevo = $pesoFacturadoNuevo;
    }

    /**
     * @return mixed
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * @param mixed $fechaIngreso
     */
    public function setFechaIngreso($fechaIngreso): void
    {
        $this->fechaIngreso = $fechaIngreso;
    }


}

