<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CumplidoRepository")
 */
class Cumplido
{
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
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="estado_generado", type="boolean", nullable=true)
     */
    private $estadoGenerado = false;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cliente", inversedBy="cumplidosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    private $clienteRel;

    /**
     * @ORM\OneToMany(targetEntity="Guia", mappedBy="cumplidoRel")
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
    public function getEstadoGenerado()
    {
        return $this->estadoGenerado;
    }

    /**
     * @param mixed $estadoGenerado
     */
    public function setEstadoGenerado($estadoGenerado): void
    {
        $this->estadoGenerado = $estadoGenerado;
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

