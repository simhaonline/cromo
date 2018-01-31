<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DepartamentoRepository")
 */
class Departamento
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=2, nullable=false, unique=true)
     */
    private $codigoDepartamentoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

}

