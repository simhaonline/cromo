<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\RhuCargoRepository")
 */
class RhuCargo
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cargo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoCargoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;


}
