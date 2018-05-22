<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\RhuSeleccionEntrevistaTipoRepository")
 */
class RhuSeleccionEntrevistaTipo
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_entrevista_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoSeleccionEntrevistaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     * * @Assert\NotBlank(message="El campo no puede estar vacio")
     */
    private $nombre;

}
