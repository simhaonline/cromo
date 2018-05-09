<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\RhuSolicitudRepository")
 */
class RhuSolicitudMotivo
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_solicitud_motivo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoSolicitudMotivoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;
    

}
