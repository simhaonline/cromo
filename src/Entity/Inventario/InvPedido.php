<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvPedidoRepository")
 */
class InvPedido
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoPedidoPk;

    /**
     * @ORM\Column(name="codigo_pedido_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoPedidoTipoFk;

    /**
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(name="soporte", type="string", length=255, nullable=true)
     * @Assert\Length(
     *     max = 255,
     *     maxMessage="El campo no puede contener mas de 255 caracteres"
     * )
     */
    private $soporte;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean",options={"default" : false}, nullable=true)
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
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     * @Assert\Length(
     *     max=500,
     *     maxMessage="El campo no puede contener mas de 500 caracteres"
     * )
     */
    private $comentarios;

    /**
     * @ORM\Column(name="usuario", type="string", length=25, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="InvPedidoTipo", inversedBy="pedidosPedidoTipoRel")
     * @ORM\JoinColumn(name="codigo_pedido_tipo_fk", referencedColumnName="codigo_pedido_tipo_pk")
     * @Assert\NotBlank(
     *     message="Debe de seleccionar una opción"
     * )
     */
    protected $pedidoTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="InvPedidoDetalle", mappedBy="pedidoRel")
     */
    protected $pedidosDetallesPedidoRel;

    /**
     * @return mixed
     */
    public function getCodigoPedidoPk()
    {
        return $this->codigoPedidoPk;
    }

    /**
     * @param mixed $codigoPedidoPk
     */
    public function setCodigoPedidoPk($codigoPedidoPk): void
    {
        $this->codigoPedidoPk = $codigoPedidoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPedidoTipoFk()
    {
        return $this->codigoPedidoTipoFk;
    }

    /**
     * @param mixed $codigoPedidoTipoFk
     */
    public function setCodigoPedidoTipoFk($codigoPedidoTipoFk): void
    {
        $this->codigoPedidoTipoFk = $codigoPedidoTipoFk;
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
    public function getPedidoTipoRel()
    {
        return $this->pedidoTipoRel;
    }

    /**
     * @param mixed $pedidoTipoRel
     */
    public function setPedidoTipoRel($pedidoTipoRel): void
    {
        $this->pedidoTipoRel = $pedidoTipoRel;
    }

    /**
     * @return mixed
     */
    public function getPedidosDetallesPedidoRel()
    {
        return $this->pedidosDetallesPedidoRel;
    }

    /**
     * @param mixed $pedidosDetallesPedidoRel
     */
    public function setPedidosDetallesPedidoRel($pedidosDetallesPedidoRel): void
    {
        $this->pedidosDetallesPedidoRel = $pedidosDetallesPedidoRel;
    }




}

