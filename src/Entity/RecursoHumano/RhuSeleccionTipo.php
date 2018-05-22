<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\RhuSeleccionTipoRepository")
 */
class RhuSeleccionTipo
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoSeleccionTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="seleccionTipoRel")
     */
    protected $seleccionTipoRel;
}
