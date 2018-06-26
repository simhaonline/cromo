<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvOrdenCompraRepository")
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
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */
    private $codigoTerceroFk;

    /**
     * @ORM\Column(name="codigo_orden_compra_tipo_fk", type="integer", nullable=true)
     */
    private $codigoOrdenCompraTipoFk;

    /**
     *
     * @ORM\Column(name="fechaEntrega", type="date", nullable=true)
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
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="estado_autorizado",options={"default" : false}, type="boolean")
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="usuario", type="text", nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     * @Assert\Length(
     *     max=300,
     *     maxMessage="El comentario no puede contener mas de 500 caracteres"
     * )
     */
    private $comentarios;

    /**
     * @ORM\ManyToOne(targetEntity="InvTercero", inversedBy="terceroOrdenCompraRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     * @Assert\NotNull(
     *     message="Debe seleccionar un tercero"
     * )
     */
    protected $terceroRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvOrdenCompraTipo", inversedBy="ordenCompraTipoOrdenesCompraRel")
     * @ORM\JoinColumn(name="codigo_orden_compra_tipo_fk", referencedColumnName="codigo_orden_compra_tipo_pk")
     * @Assert\NotNull(
     *     message="Debe seleccionar un tipo"
     * )
     */
    protected $ordenCompraTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="InvOrdenCompraDetalle", mappedBy="ordenCompraRel")
     */
    protected $ordenCompraOrdenCompraDetallesRel;

    /**
     * @return mixed
     */
    public function getCodigoOrdenCompraPk()
    {
        return $this->codigoOrdenCompraPk;
    }

    /**
     * @param mixed $codigoOrdenCompraPk
     */
    public function setCodigoOrdenCompraPk($codigoOrdenCompraPk): void
    {
        $this->codigoOrdenCompraPk = $codigoOrdenCompraPk;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getCodigoTerceroFk()
    {
        return $this->codigoTerceroFk;
    }

    /**
     * @param mixed $codigoTerceroFk
     */
    public function setCodigoTerceroFk($codigoTerceroFk): void
    {
        $this->codigoTerceroFk = $codigoTerceroFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoOrdenCompraTipoFk()
    {
        return $this->codigoOrdenCompraTipoFk;
    }

    /**
     * @param mixed $codigoOrdenCompraTipoFk
     */
    public function setCodigoOrdenCompraTipoFk($codigoOrdenCompraTipoFk): void
    {
        $this->codigoOrdenCompraTipoFk = $codigoOrdenCompraTipoFk;
    }

    /**
     * @return mixed
     */
    public function getFechaEntrega()
    {
        return $this->fechaEntrega;
    }

    /**
     * @param mixed $fechaEntrega
     */
    public function setFechaEntrega($fechaEntrega): void
    {
        $this->fechaEntrega = $fechaEntrega;
    }

    /**
     * @return mixed
     */
    public function getSoporte()
    {
        return $this->soporte;
    }

    /**
     * @param mixed $soporte
     */
    public function setSoporte($soporte): void
    {
        $this->soporte = $soporte;
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
    public function getVrIva()
    {
        return $this->vrIva;
    }

    /**
     * @param mixed $vrIva
     */
    public function setVrIva($vrIva): void
    {
        $this->vrIva = $vrIva;
    }

    /**
     * @return mixed
     */
    public function getVrNeto()
    {
        return $this->vrNeto;
    }

    /**
     * @param mixed $vrNeto
     */
    public function setVrNeto($vrNeto): void
    {
        $this->vrNeto = $vrNeto;
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
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * @param mixed $estadoAutorizado
     */
    public function setEstadoAutorizado($estadoAutorizado): void
    {
        $this->estadoAutorizado = $estadoAutorizado;
    }

    /**
     * @return mixed
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * @param mixed $estadoAprobado
     */
    public function setEstadoAprobado($estadoAprobado): void
    {
        $this->estadoAprobado = $estadoAprobado;
    }

    /**
     * @return mixed
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * @param mixed $estadoAnulado
     */
    public function setEstadoAnulado($estadoAnulado): void
    {
        $this->estadoAnulado = $estadoAnulado;
    }

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero): void
    {
        $this->numero = $numero;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
    }

    /**
     * @return mixed
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * @param mixed $comentarios
     */
    public function setComentarios($comentarios): void
    {
        $this->comentarios = $comentarios;
    }

    /**
     * @return mixed
     */
    public function getTerceroRel()
    {
        return $this->terceroRel;
    }

    /**
     * @param mixed $terceroRel
     */
    public function setTerceroRel($terceroRel): void
    {
        $this->terceroRel = $terceroRel;
    }

    /**
     * @return mixed
     */
    public function getOrdenCompraTipoRel()
    {
        return $this->ordenCompraTipoRel;
    }

    /**
     * @param mixed $ordenCompraTipoRel
     */
    public function setOrdenCompraTipoRel($ordenCompraTipoRel): void
    {
        $this->ordenCompraTipoRel = $ordenCompraTipoRel;
    }

    /**
     * @return mixed
     */
    public function getOrdenCompraOrdenCompraDetallesRel()
    {
        return $this->ordenCompraOrdenCompraDetallesRel;
    }

    /**
     * @param mixed $ordenCompraOrdenCompraDetallesRel
     */
    public function setOrdenCompraOrdenCompraDetallesRel($ordenCompraOrdenCompraDetallesRel): void
    {
        $this->ordenCompraOrdenCompraDetallesRel = $ordenCompraOrdenCompraDetallesRel;
    }
}

