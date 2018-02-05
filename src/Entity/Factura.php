<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FacturaRepository")
 */
class Factura
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoFacturaPk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="vr_flete", type="float")
     */
    private $vrFlete = 0;

    /**
     * @ORM\Column(name="vr_manejo", type="float")
     */
    private $vrManejo = 0;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cliente", inversedBy="facturasClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    private $clienteRel;

    /**
     * @ORM\OneToMany(targetEntity="Guia", mappedBy="facturaRel")
     */
    protected $guiasFacturaRel;

    /**
     * @return mixed
     */
    public function getCodigoFacturaPk()
    {
        return $this->codigoFacturaPk;
    }

    /**
     * @param mixed $codigoFacturaPk
     */
    public function setCodigoFacturaPk($codigoFacturaPk): void
    {
        $this->codigoFacturaPk = $codigoFacturaPk;
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
    public function getGuiasFacturaRel()
    {
        return $this->guiasFacturaRel;
    }

    /**
     * @param mixed $guiasFacturaRel
     */
    public function setGuiasFacturaRel($guiasFacturaRel): void
    {
        $this->guiasFacturaRel = $guiasFacturaRel;
    }

    /**
     * @return mixed
     */
    public function getVrFlete()
    {
        return $this->vrFlete;
    }

    /**
     * @param mixed $vrFlete
     */
    public function setVrFlete($vrFlete): void
    {
        $this->vrFlete = $vrFlete;
    }

    /**
     * @return mixed
     */
    public function getVrManejo()
    {
        return $this->vrManejo;
    }

    /**
     * @param mixed $vrManejo
     */
    public function setVrManejo($vrManejo): void
    {
        $this->vrManejo = $vrManejo;
    }


}

