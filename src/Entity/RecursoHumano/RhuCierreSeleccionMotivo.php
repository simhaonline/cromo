<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCierreSeleccionMotivoRepository")
 */
class RhuCierreSeleccionMotivo
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cierre_seleccion_motivo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoCierreSeleccionMotivoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="cierreSeleccionMotivoRel")
     */
    protected $rhuSeleccionMotivoCierreRel;
    

}
