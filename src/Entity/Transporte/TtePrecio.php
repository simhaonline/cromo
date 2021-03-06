<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TtePrecioRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TtePrecio
{
    public $infoLog = [
        "primaryKey" => "codigoPrecioPk",
        "todos"     => true,
    ];
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
     * @ORM\Column(name="fecha_vence", type="date", nullable=true)
     */
    private $fechaVence;

    /**
     * @ORM\Column(name="omitir_descuento", type="boolean", nullable=true, options={"default" : false})
     */
    private $omitirDescuento = false;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TtePrecioDetalle", mappedBy="precioRel")
     */
    protected $preciosDetallesPrecioRel;

    /**
     * @ORM\OneToMany(targetEntity="TteCondicion", mappedBy="precioRel")
     */
    protected $condicionesPrecioRel;

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
    public function getFechaVence()
    {
        return $this->fechaVence;
    }

    /**
     * @param mixed $fechaVence
     */
    public function setFechaVence($fechaVence): void
    {
        $this->fechaVence = $fechaVence;
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
    public function getCondicionesPrecioRel()
    {
        return $this->condicionesPrecioRel;
    }

    /**
     * @param mixed $condicionesPrecioRel
     */
    public function setCondicionesPrecioRel($condicionesPrecioRel): void
    {
        $this->condicionesPrecioRel = $condicionesPrecioRel;
    }

    /**
     * @return array
     */
    public function getInfoLog(): array
    {
        return $this->infoLog;
    }

    /**
     * @param array $infoLog
     */
    public function setInfoLog(array $infoLog): void
    {
        $this->infoLog = $infoLog;
    }

    /**
     * @return mixed
     */
    public function getOmitirDescuento()
    {
        return $this->omitirDescuento;
    }

    /**
     * @param mixed $omitirDescuento
     */
    public function setOmitirDescuento($omitirDescuento): void
    {
        $this->omitirDescuento = $omitirDescuento;
    }
}

