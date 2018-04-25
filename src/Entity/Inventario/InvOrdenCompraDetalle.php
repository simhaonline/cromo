<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvItemRepository")
 */
class InvOrdenCompraDetalle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoOrdenCompraDetallePk;

    /**
     * @var int
     *
     * @ORM\Column(name="codigo_solicitud_detalle_fk", type="integer", nullable=true)
     */
    private $codigoSolicitudDetalleFk;

    /**
     * @ORM\Column(name="codigo_orden_compra_fk", type="integer", nullable=true)
     */
    private $codigoOrdenCompraFk;

    /**
     * @var int
     *
     * @ORM\Column(name="codigo_item_fk", type="integer", nullable=true)
     */
    private $codigoItemFk;

    /**
     * @var int
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=true)
     */
    private $cantidad = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="valor", type="float", nullable=true)
     */
    private $valor = 0;

    /**
     * @ORM\Column(name="vr_subtotal", type="float", nullable=true)
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="porcentaje_iva", type="integer", nullable=true)
     */
    private $porcentajeIva = 0;

    /**
     * @ORM\Column(name="vr_iva", type="float", nullable=true)
     */
    private $vrIva = 0;

    /**
     * @ORM\Column(name="vr_total", type="float", nullable=true)
     */
    private $vrTotal = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="cantidad_pendiente", type="integer", nullable=true)
     */
    private $cantidadPendiente;

}

