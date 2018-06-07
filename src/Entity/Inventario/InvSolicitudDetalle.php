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
    private $codigoSolicitudDetallePk;

    /**
     * @ORM\Column(name="codigo_solicitud_fk", type="integer", nullable=true)
     */
    private $codigoSolicitudFk;

    /**
     * @ORM\Column(name="codigo_orden_compra_detalle_fk", type="integer", nullable=true)
     */
    private $codigoOrdenCompraDetalleFk;

    /**
     * @ORM\Column(name="codigo_item_fk", type="integer")
     */
    private $codigoItemFk;

    /**
     * @ORM\Column(name="cantidad_solicitada",options={"default" : 0}, type="integer")
     */
    private $cantidadSolicitada = 0;

    /**
     * @ORM\Column(name="cantidad_restante",options={"default" : 0}, type="integer", nullable=true)
     */
    private $cantidadRestante = 0;

    /**
     * @ORM\ManyToOne(targetEntity="InvSolicitud", inversedBy="solicitudSolicitudDetallesRel")
     * @ORM\JoinColumn(name="codigo_solicitud_fk", referencedColumnName="codigo_solicitud_pk")
     */
    private $solicitudRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="itemsolicitudDetallesRel")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    private $itemRel;

    /**
     * @return mixed
     */
    public function getCodigoSolicitudDetallePk()
    {
        return $this->codigoSolicitudDetallePk;
    }

    /**
     * @param mixed $codigoSolicitudDetallePk
     */
    public function setCodigoSolicitudDetallePk($codigoSolicitudDetallePk): void
    {
        $this->codigoSolicitudDetallePk = $codigoSolicitudDetallePk;
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
    public function getCodigoOrdenCompraDetalleFk()
    {
        return $this->codigoOrdenCompraDetalleFk;
    }

    /**
     * @param mixed $codigoOrdenCompraDetalleFk
     */
    public function setCodigoOrdenCompraDetalleFk($codigoOrdenCompraDetalleFk): void
    {
        $this->codigoOrdenCompraDetalleFk = $codigoOrdenCompraDetalleFk;
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
    public function getCantidadSolicitada()
    {
        return $this->cantidadSolicitada;
    }

    /**
     * @param mixed $cantidadSolicitada
     */
    public function setCantidadSolicitada($cantidadSolicitada): void
    {
        $this->cantidadSolicitada = $cantidadSolicitada;
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

