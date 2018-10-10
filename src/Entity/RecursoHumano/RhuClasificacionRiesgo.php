<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuClasificacionRiesgoRepository")
 */
class RhuClasificacionRiesgo
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_clasificacion_riesgo_pk", type="string", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoClasificacionRiesgoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\Column(name="porcentaje", type="float")
     */
    private $porcentaje = 0;

    /**
     * @ORM\OneToMany(targetEntity="RhuSolicitud", mappedBy="clasificacionRiesgoRel")
     */
    protected $solicitudesClasificacionRiesgoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="clasificacionRiesgoRel")
     */
    protected $contratosClasificacionRiesgoRel;
}
