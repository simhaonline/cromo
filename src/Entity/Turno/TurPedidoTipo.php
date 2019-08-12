<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurPedidoTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurPedidoTipo
{
    public $infoLog = [
        "primaryKey" => "codigoPedidoTipoPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pedido_tipo_pk", type="string", length=20)
     */
    private $codigoPedidoTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo", type="integer", nullable=true, options={"default":0})
     */
    private $consecutivo = 0;

    /**
     * @ORM\OneToMany(targetEntity="TurPedido", mappedBy="pedidoTipoRel")
     */
    protected $pedidosPedidoTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoPedidoTipoPk()
    {
        return $this->codigoPedidoTipoPk;
    }

    /**
     * @param mixed $codigoPedidoTipoPk
     */
    public function setCodigoPedidoTipoPk($codigoPedidoTipoPk): void
    {
        $this->codigoPedidoTipoPk = $codigoPedidoTipoPk;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }

    /**
     * @param mixed $consecutivo
     */
    public function setConsecutivo($consecutivo): void
    {
        $this->consecutivo = $consecutivo;
    }

    /**
     * @return mixed
     */
    public function getPedidosPedidoTipoRel()
    {
        return $this->pedidosPedidoTipoRel;
    }

    /**
     * @param mixed $pedidosPedidoTipoRel
     */
    public function setPedidosPedidoTipoRel($pedidosPedidoTipoRel): void
    {
        $this->pedidosPedidoTipoRel = $pedidosPedidoTipoRel;
    }



}

