<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvPedidoTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoPedidoTipoPk"},message="Ya existe el código del tipo")
 */
class InvPedidoTipo
{
    public $infoLog = [
        "primaryKey" => "codigoPedidoTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=10)
     */
    private $codigoPedidoTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo", type="integer",options={"default" : 0},nullable=true)
     */
    private $consecutivo = 0;

    /**
     * @ORM\OneToMany(targetEntity="InvPedido", mappedBy="pedidoTipoRel")
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

