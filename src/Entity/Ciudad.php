<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CiudadRepository")
 */
class Ciudad
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoCiudadPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Guia", mappedBy="ciudadOrigenRel")
     */
    protected $guiasCiudadOrigenRel;

    /**
     * @ORM\OneToMany(targetEntity="Guia", mappedBy="ciudadDestinoRel")
     */
    protected $guiasCiudadDestinoRel;

    /**
     * @ORM\OneToMany(targetEntity="Despacho", mappedBy="ciudadOrigenRel")
     */
    protected $despachosCiudadOrigenRel;

    /**
     * @ORM\OneToMany(targetEntity="Despacho", mappedBy="ciudadDestinoRel")
     */
    protected $despachosCiudadDestinoRel;

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
    public function getCodigoCiudadPk()
    {
        return $this->codigoCiudadPk;
    }

    /**
     * @param mixed $codigoCiudadPk
     */
    public function setCodigoCiudadPk($codigoCiudadPk): void
    {
        $this->codigoCiudadPk = $codigoCiudadPk;
    }

    /**
     * @return mixed
     */
    public function getGuiasCiudadOrigenRel()
    {
        return $this->guiasCiudadOrigenRel;
    }

    /**
     * @param mixed $guiasCiudadOrigenRel
     */
    public function setGuiasCiudadOrigenRel($guiasCiudadOrigenRel): void
    {
        $this->guiasCiudadOrigenRel = $guiasCiudadOrigenRel;
    }

    /**
     * @return mixed
     */
    public function getGuiasCiudadDestinoRel()
    {
        return $this->guiasCiudadDestinoRel;
    }

    /**
     * @param mixed $guiasCiudadDestinoRel
     */
    public function setGuiasCiudadDestinoRel($guiasCiudadDestinoRel): void
    {
        $this->guiasCiudadDestinoRel = $guiasCiudadDestinoRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosCiudadOrigenRel()
    {
        return $this->despachosCiudadOrigenRel;
    }

    /**
     * @param mixed $despachosCiudadOrigenRel
     */
    public function setDespachosCiudadOrigenRel($despachosCiudadOrigenRel): void
    {
        $this->despachosCiudadOrigenRel = $despachosCiudadOrigenRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosCiudadDestinoRel()
    {
        return $this->despachosCiudadDestinoRel;
    }

    /**
     * @param mixed $despachosCiudadDestinoRel
     */
    public function setDespachosCiudadDestinoRel($despachosCiudadDestinoRel): void
    {
        $this->despachosCiudadDestinoRel = $despachosCiudadDestinoRel;
    }


}

