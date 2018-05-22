<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\RhuSeleccionReferenciaTipoRepository")
 */
class RhuSeleccionReferenciaTipo
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_referencia_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoSeleccionReferenciaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     * * @Assert\NotBlank(message="El campo no puede estar vacio")
     */
    private $nombre;

}
