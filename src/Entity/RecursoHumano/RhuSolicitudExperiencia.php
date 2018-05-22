<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\RhuSolicitudExperienciaRepository")
 */
class RhuSolicitudExperiencia
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_solicitud_experiencia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoSolicitudExperienciaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", mappedBy="solicitudExperienciaRel")
     */
    protected $solicitudesMotivosRel;
}
