<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvSolicitudRepository")
 */
class InvSolicitud
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoSolicitudPk;

    /**
     * @ORM\Column(name="codigo_solicitud_tipo_fk", type="integer", nullable=true)
     */
    private $codigoSolicitudTipoFk;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_entrega", type="date")
     */
    private $fechaEntrega;

    /**
     * @var string
     *
     * @ORM\Column(name="soporte", type="string", length=255, nullable=true)
     */
    private $soporte;

    /**
     * @var float
     *
     * @ORM\Column(name="vr_subtotal", type="float", nullable=true)
     */
    private $vrSubtotal = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="vr_iva", type="float", nullable=true)
     */
    private $vrIva = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="vr_neto", type="float", nullable=true)
     */
    private $vrNeto = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="estado_autorizado", type="boolean", nullable=true)
     */
    private $estadoAutorizado = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="estado_impreso", type="boolean", nullable=true)
     */
    private $estadoImpreso = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="estado_anulado", type="boolean", nullable=true)
     */
    private $estadoAnulado = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     * @Assert\Length(
     *     max=500,
     *     maxMessage="El comentario no puede contener mas de 300 caracteres"
     * )
     */
    private $comentarios;


}

