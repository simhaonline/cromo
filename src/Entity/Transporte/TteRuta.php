<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteRutaRepository")
 */
class TteRuta
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoRutaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="TteCiudad", mappedBy="rutaRel")
     */
    protected $ciudadesRutaRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="rutaRel")
     */
    protected $guiasRutaRel;

    /**
     * @ORM\OneToMany(targetEntity="TteDespacho", mappedBy="rutaRel")
     */
    protected $despachosRutaRel;

    /**
     * @return mixed
     */
    public function getCodigoRutaPk()
    {
        return $this->codigoRutaPk;
    }

    /**
     * @param mixed $codigoRutaPk
     */
    public function setCodigoRutaPk($codigoRutaPk): void
    {
        $this->codigoRutaPk = $codigoRutaPk;
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
    public function getCiudadesRutaRel()
    {
        return $this->ciudadesRutaRel;
    }

    /**
     * @param mixed $ciudadesRutaRel
     */
    public function setCiudadesRutaRel($ciudadesRutaRel): void
    {
        $this->ciudadesRutaRel = $ciudadesRutaRel;
    }

    /**
     * @return mixed
     */
    public function getGuiasRutaRel()
    {
        return $this->guiasRutaRel;
    }

    /**
     * @param mixed $guiasRutaRel
     */
    public function setGuiasRutaRel($guiasRutaRel): void
    {
        $this->guiasRutaRel = $guiasRutaRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosRutaRel()
    {
        return $this->despachosRutaRel;
    }

    /**
     * @param mixed $despachosRutaRel
     */
    public function setDespachosRutaRel($despachosRutaRel): void
    {
        $this->despachosRutaRel = $despachosRutaRel;
    }


}

