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
     * @var int
     *
     * @ORM\Column(name="codigo_item_fk", type="integer")
     */
    private $codigoItemFk;

    /**
     * @ORM\Column(name="codigo_solicitud_fk", type="integer", nullable=true)
     */
    private $codigoSolicitudFk;

    /**
     * @var int
     *
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="cantidad_restante", type="integer", nullable=true)
     */
    private $cantidadRestante;

    /**
     * @var float
     *
     * @ORM\Column(name="valor", type="float")
     */
    private $valor = 0;

    /**
     * @ORM\Column(name="vr_subtotal", type="float")
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="porcentaje_iva", type="integer")
     */
    private $porcentajeIva = 0;

    /**
     * @ORM\Column(name="vr_iva", type="float")
     */
    private $vrIva = 0;

    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;

}

