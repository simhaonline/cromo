<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvSolicitudDetalleRepository")
 */
class InvSolicitudDetalle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoSolitudDetallePk;

    /**
     * @ORM\Column(name="codigo_item_fk", type="integer")
     */
    private $codigoItemFk;

    /**
     * @ORM\Column(name="codigo_solicitud_fk", type="integer", nullable=true)
     */
    private $codigoSolicitudFk;

    /**
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="cantidad_restante", type="integer", nullable=true)
     */
    private $cantidadRestante;

    /**
     * @ORM\ManyToOne(targetEntity="InvSolicitud", inversedBy="solicitudSolicitudDetallesRel")
     * @ORM\JoinColumn(name="codigo_solicitud_fk", referencedColumnName="codigo_solicitud_pk")
     */
    private $solicitudRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="itemsolicitudDetalleRel")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    private $itemRel;

    /**
     * @return mixed
     */
    public function getCodigoSolitudDetallePk()
    {
        return $this->codigoSolitudDetallePk;
    }

    /**
     * @param mixed $codigoSolitudDetallePk
     */
    public function setCodigoSolitudDetallePk($codigoSolitudDetallePk): void
    {
        $this->codigoSolitudDetallePk = $codigoSolitudDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoItemFk()
    {
        return $this->codigoItemFk;
    }

    /**
     * @param mixed $codigoItemFk
     */
    public function setCodigoItemFk($codigoItemFk): void
    {
        $this->codigoItemFk = $codigoItemFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSolicitudFk()
    {
        return $this->codigoSolicitudFk;
    }

    /**
     * @param mixed $codigoSolicitudFk
     */
    public function setCodigoSolicitudFk($codigoSolicitudFk): void
    {
        $this->codigoSolicitudFk = $codigoSolicitudFk;
    }

    /**
     * @return mixed
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param mixed $cantidad
     */
    public function setCantidad($cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return mixed
     */
    public function getCantidadRestante()
    {
        return $this->cantidadRestante;
    }

    /**
     * @param mixed $cantidadRestante
     */
    public function setCantidadRestante($cantidadRestante): void
    {
        $this->cantidadRestante = $cantidadRestante;
    }

    /**
     * @return mixed
     */
    public function getSolicitudRel()
    {
        return $this->solicitudRel;
    }

    /**
     * @param mixed $solicitudRel
     */
    public function setSolicitudRel($solicitudRel): void
    {
        $this->solicitudRel = $solicitudRel;
    }

    /**
     * @return mixed
     */
    public function getItemRel()
    {
        return $this->itemRel;
    }

    /**
     * @param mixed $itemRel
     */
    public function setItemRel($itemRel): void
    {
        $this->itemRel = $itemRel;
    }


}

