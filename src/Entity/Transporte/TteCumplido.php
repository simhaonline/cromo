<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteCumplidoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteCumplido
{
    public $infoLog = [
        "primaryKey" => "codigoCumplidoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoCumplidoPk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_cumplido_tipo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCumplidoTipoFk;

    /**
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="TteCliente", inversedBy="cumplidosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    private $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteCumplidoTipo", inversedBy="cumplidosCumplidoTipoRel")
     * @ORM\JoinColumn(name="codigo_cumplido_tipo_fk", referencedColumnName="codigo_cumplido_tipo_pk")
     */
    private $cumplidoTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="cumplidoRel")
     */
    protected $guiasCumplidoRel;

    /**
     * @return mixed
     */
    public function getCodigoCumplidoPk()
    {
        return $this->codigoCumplidoPk;
    }

    /**
     * @param mixed $codigoCumplidoPk
     */
    public function setCodigoCumplidoPk($codigoCumplidoPk): void
    {
        $this->codigoCumplidoPk = $codigoCumplidoPk;
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
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * @param mixed $codigoClienteFk
     */
    public function setCodigoClienteFk($codigoClienteFk): void
    {
        $this->codigoClienteFk = $codigoClienteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCumplidoTipoFk()
    {
        return $this->codigoCumplidoTipoFk;
    }

    /**
     * @param mixed $codigoCumplidoTipoFk
     */
    public function setCodigoCumplidoTipoFk($codigoCumplidoTipoFk): void
    {
        $this->codigoCumplidoTipoFk = $codigoCumplidoTipoFk;
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
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * @param mixed $comentario
     */
    public function setComentario($comentario): void
    {
        $this->comentario = $comentario;
    }

    /**
     * @return mixed
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * @param mixed $clienteRel
     */
    public function setClienteRel($clienteRel): void
    {
        $this->clienteRel = $clienteRel;
    }

    /**
     * @return mixed
     */
    public function getCumplidoTipoRel()
    {
        return $this->cumplidoTipoRel;
    }

    /**
     * @param mixed $cumplidoTipoRel
     */
    public function setCumplidoTipoRel($cumplidoTipoRel): void
    {
        $this->cumplidoTipoRel = $cumplidoTipoRel;
    }

    /**
     * @return mixed
     */
    public function getGuiasCumplidoRel()
    {
        return $this->guiasCumplidoRel;
    }

    /**
     * @param mixed $guiasCumplidoRel
     */
    public function setGuiasCumplidoRel($guiasCumplidoRel): void
    {
        $this->guiasCumplidoRel = $guiasCumplidoRel;
    }


}

