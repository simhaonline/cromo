<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RutaRepository")
 */
class Ruta
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
     * @ORM\OneToMany(targetEntity="Ciudad", mappedBy="rutaRel")
     */
    protected $ciudadesRutaRel;

    /**
     * @ORM\OneToMany(targetEntity="Guia", mappedBy="rutaRel")
     */
    protected $guiasRutaRel;

    /**
     * @ORM\OneToMany(targetEntity="Despacho", mappedBy="rutaRel")
     */
    protected $despachosRutaRel;
}

