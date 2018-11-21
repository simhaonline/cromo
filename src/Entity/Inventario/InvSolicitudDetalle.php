<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvSolicitudDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class InvSolicitudDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoSolicitudDetallePk",
        "todos"     => true,
    ];
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
     * @ORM\Column(name="codigo_item_fk", type="integer")
     */
    private $codigoItemFk;

    /**
     * @ORM\Column(name="cantidad",options={"default" : 0}, type="integer")
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="cantidad_pendiente",options={"default" : 0}, type="integer", nullable=true)
     */
    private $cantidadPendiente = 0;

    /**
     * @ORM\Column(name="cantidad_afectada", options={"default" : 0}, type="integer", nullable=true)
     */
    private $cantidadAfectada = 0;

    /**
     * @ORM\ManyToOne(targetEntity="InvSolicitud", inversedBy="solicitudesDetallesSolicitudRel")
     * @ORM\JoinColumn(name="codigo_solicitud_fk", referencedColumnName="codigo_solicitud_pk")
     */
    protected $solicitudRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="solicitudesDetallesItemRel")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel;

    /**
     * @ORM\OneToMany(targetEntity="InvOrdenDetalle", mappedBy="solicitudDetalleRel")
     */
    protected $ordenesDetallesSolicitudDetalleRel;

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
    public function getCantidadPendiente()
    {
        return $this->cantidadPendiente;
    }

    /**
     * @param mixed $cantidadPendiente
     */
    public function setCantidadPendiente($cantidadPendiente): void
    {
        $this->cantidadPendiente = $cantidadPendiente;
    }

    /**
     * @return mixed
     */
    public function getCantidadAfectada()
    {
        return $this->cantidadAfectada;
    }

    /**
     * @param mixed $cantidadAfectada
     */
    public function setCantidadAfectada($cantidadAfectada): void
    {
        $this->cantidadAfectada = $cantidadAfectada;
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

    /**
     * @return mixed
     */
    public function getOrdenesDetallesSolicitudDetalleRel()
    {
        return $this->ordenesDetallesSolicitudDetalleRel;
    }

    /**
     * @param mixed $ordenesDetallesSolicitudDetalleRel
     */
    public function setOrdenesDetallesSolicitudDetalleRel($ordenesDetallesSolicitudDetalleRel): void
    {
        $this->ordenesDetallesSolicitudDetalleRel = $ordenesDetallesSolicitudDetalleRel;
    }



}

