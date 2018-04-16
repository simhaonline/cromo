<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TtePrecioRepository")
 */
class TtePrecio
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoPrecioPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TtePrecio", mappedBy="precioRel")
     */
    protected $preciosDetallesPrecioRel;

    /**
     * @return mixed
     */
    public function getCodigoPrecioPk()
    {
        return $this->codigoPrecioPk;
    }

    /**
     * @param mixed $codigoPrecioPk
     */
    public function setCodigoPrecioPk($codigoPrecioPk): void
    {
        $this->codigoPrecioPk = $codigoPrecioPk;
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
    public function getPreciosDetallesPrecioRel()
    {
        return $this->preciosDetallesPrecioRel;
    }

    /**
     * @param mixed $preciosDetallesPrecioRel
     */
    public function setPreciosDetallesPrecioRel($preciosDetallesPrecioRel): void
    {
        $this->preciosDetallesPrecioRel = $preciosDetallesPrecioRel;
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



}

