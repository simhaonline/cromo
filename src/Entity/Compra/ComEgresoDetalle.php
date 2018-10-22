<?php

namespace App\Entity\Compra;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Compra\ComEgresoDetalleRepository")
 */
class ComEgresoDetalle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $codigoEgresoDetallePk;

    /**
     * @ORM\Column(name="codigo_egreso_fk" , type="integer")
     */
    private $codigoEgresoFk;

    /**
     * @ORM\Column(name="vr_subtotal" , type="float" ,nullable=true)
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="por_descuento", type="float", nullable=true)
     */
    private $porDescuento = 0;

    /**
     * @ORM\Column(name="vr_descuento", type="float", nullable=true)
     */
    private $vrDescuento = 0;

    /**
     * @ORM\Column(name="vr_retencion", type="float" ,nullable=true)
     */
    private $vrRetencion = 0;

    /**
     * @ORM\Column(name="vr_total" ,type="float" ,nullable=true)
     */
    private $vrTotal = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compra\ComEgreso" , inversedBy="egresoDetallesEgresoRel")
     * @ORM\JoinColumn(name="codigo_egreso_fk" , referencedColumnName="codigo_egreso_pk")
     */
    private $egresoRel;

    /**
     * @return mixed
     */
    public function getCodigoEgresoDetallePk()
    {
        return $this->codigoEgresoDetallePk;
    }

    /**
     * @param mixed $codigoEgresoDetallePk
     */
    public function setCodigoEgresoDetallePk($codigoEgresoDetallePk): void
    {
        $this->codigoEgresoDetallePk = $codigoEgresoDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEgresoFk()
    {
        return $this->codigoEgresoFk;
    }

    /**
     * @param mixed $codigoEgresoFk
     */
    public function setCodigoEgresoFk($codigoEgresoFk): void
    {
        $this->codigoEgresoFk = $codigoEgresoFk;
    }

    /**
     * @return mixed
     */
    public function getVrSubtotal()
    {
        return $this->vrSubtotal;
    }

    /**
     * @param mixed $vrSubtotal
     */
    public function setVrSubtotal($vrSubtotal): void
    {
        $this->vrSubtotal = $vrSubtotal;
    }

    /**
     * @return mixed
     */
    public function getPorDescuento()
    {
        return $this->porDescuento;
    }

    /**
     * @param mixed $porDescuento
     */
    public function setPorDescuento($porDescuento): void
    {
        $this->porDescuento = $porDescuento;
    }

    /**
     * @return mixed
     */
    public function getVrDescuento()
    {
        return $this->vrDescuento;
    }

    /**
     * @param mixed $vrDescuento
     */
    public function setVrDescuento($vrDescuento): void
    {
        $this->vrDescuento = $vrDescuento;
    }

    /**
     * @return mixed
     */
    public function getVrRetencion()
    {
        return $this->vrRetencion;
    }

    /**
     * @param mixed $vrRetencion
     */
    public function setVrRetencion($vrRetencion): void
    {
        $this->vrRetencion = $vrRetencion;
    }

    /**
     * @return mixed
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * @param mixed $vrTotal
     */
    public function setVrTotal($vrTotal): void
    {
        $this->vrTotal = $vrTotal;
    }

    /**
     * @return mixed
     */
    public function getEgresoRel()
    {
        return $this->egresoRel;
    }

    /**
     * @param mixed $egresoRel
     */
    public function setEgresoRel($egresoRel): void
    {
        $this->egresoRel = $egresoRel;
    }


}
