<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClienteRepository")
 */
class Cliente
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoClientePk;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=100, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\OneToMany(targetEntity="Guia", mappedBy="clienteRel")
     */
    protected $guiasClienteRel;


}

