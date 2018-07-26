<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteCumplidoTipoRepository")
 */
class TteCumplidoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoCumplidoTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo", type="integer", nullable=true)
     */
    private $consecutivo = 0;

    /**
     * @ORM\Column(name="entrega_mercancia", type="boolean", nullable=true, options={"default" : false})
     */
    private $entregaMercancia = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteCumplido", mappedBy="cumplidoTipoRel")
     */
    protected $cumplidosCumplidoTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoCumplidoTipoPk()
    {
        return $this->codigoCumplidoTipoPk;
    }

    /**
     * @param mixed $codigoCumplidoTipoPk
     */
    public function setCodigoCumplidoTipoPk($codigoCumplidoTipoPk): void
    {
        $this->codigoCumplidoTipoPk = $codigoCumplidoTipoPk;
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
    public function getEntregaMercancia()
    {
        return $this->entregaMercancia;
    }

    /**
     * @param mixed $entregaMercancia
     */
    public function setEntregaMercancia($entregaMercancia): void
    {
        $this->entregaMercancia = $entregaMercancia;
    }

    /**
     * @return mixed
     */
    public function getCumplidosCumplidoTipoRel()
    {
        return $this->cumplidosCumplidoTipoRel;
    }

    /**
     * @param mixed $cumplidosCumplidoTipoRel
     */
    public function setCumplidosCumplidoTipoRel($cumplidosCumplidoTipoRel): void
    {
        $this->cumplidosCumplidoTipoRel = $cumplidosCumplidoTipoRel;
    }




}

