<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteServicioRepository")
 */
class TteServicio
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoServicioPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="orden", type="integer", nullable=true, options={"default" : 0})
     */
    private $orden = 0;

    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="servicioRel")
     */
    protected $guiasServicioRel;

    /**
     * @return mixed
     */
    public function getCodigoServicioPk()
    {
        return $this->codigoServicioPk;
    }

    /**
     * @param mixed $codigoServicioPk
     */
    public function setCodigoServicioPk($codigoServicioPk): void
    {
        $this->codigoServicioPk = $codigoServicioPk;
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
    public function getGuiasServicioRel()
    {
        return $this->guiasServicioRel;
    }

    /**
     * @param mixed $guiasServicioRel
     */
    public function setGuiasServicioRel($guiasServicioRel): void
    {
        $this->guiasServicioRel = $guiasServicioRel;
    }

    /**
     * @return mixed
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * @param mixed $orden
     */
    public function setOrden($orden): void
    {
        $this->orden = $orden;
    }




}

