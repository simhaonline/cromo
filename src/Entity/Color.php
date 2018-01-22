<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ColorRepository")
 */
class Color
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoColorPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

}

