<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteRelacionCajaRepository")
 */
class TteRelacionCaja
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoRelacionCajaPk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteRecibo", mappedBy="relacionCajaRel")
     */
    protected $recibosRelacionCajaRel;

    /**
     * @return mixed
     */
    public function getCodigoRelacionCajaPk()
    {
        return $this->codigoRelacionCajaPk;
    }

    /**
     * @param mixed $codigoRelacionCajaPk
     */
    public function setCodigoRelacionCajaPk($codigoRelacionCajaPk): void
    {
        $this->codigoRelacionCajaPk = $codigoRelacionCajaPk;
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
    public function getRecibosRelacionCajaRel()
    {
        return $this->recibosRelacionCajaRel;
    }

    /**
     * @param mixed $recibosRelacionCajaRel
     */
    public function setRecibosRelacionCajaRel($recibosRelacionCajaRel): void
    {
        $this->recibosRelacionCajaRel = $recibosRelacionCajaRel;
    }


}

