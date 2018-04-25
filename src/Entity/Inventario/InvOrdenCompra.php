<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvItemRepository")
 */
class InvOrdenCompra
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoOrdenCompraPk;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */
    private $codigoTerceroFk;

    /**
     * @ORM\Column(name="codigo_orden_compra_documento_fk", type="integer", nullable=true)
     */
    private $codigoOrdenCompraDocumentoFk;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaEntrega", type="date", , nullable=true)
     */
    private $fechaEntrega;

    /**
     * @ORM\Column(name="soporte", type="string", length=500, nullable=true)
     */
    private $soporte;

    /**
     * @ORM\Column(name="vr_subtotal", type="float")
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="vr_iva", type="float")
     */
    private $vrIva = 0;

    /**
     * @ORM\Column(name="vr_neto", type="float")
     */
    private $vrNeto = 0;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */
    private $estadoAutorizado = 0;

    /**
     * @ORM\Column(name="estado_impreso", type="boolean")
     */
    private $estadoImpreso = 0;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean",nullable=true)
     */
    private $estadoAprobado = 0;

    /**
     * @ORM\Column(name="estado_rechazado", type="boolean",nullable=true)
     */
    private $estadoRechazado = 0;

    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     * @Assert\Length(
     *     max=300,
     *     maxMessage="El comentario no puede contener mas de 500 caracteres"
     * )
     */
    private $comentarios;

}

