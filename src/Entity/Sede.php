<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SedeRepository")
 */
class Sede
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoSedePk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Guia", mappedBy="sedeIngresoRel")
     */
    protected $guiasSedeIngresoRel;

    /**
     * @ORM\OneToMany(targetEntity="Guia", mappedBy="sedeCargoRel")
     */
    protected $guiasSedeCargoRel;



}

