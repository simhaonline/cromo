<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\RhuRhRepository")
 */
class RhuRh
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_rh_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoRhPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAspirante", mappedBy="rhRel")
     */
    protected $rhuAspirantesRhRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="rhRel")
     */
    protected $rhuSeleccionRhRel;

}
