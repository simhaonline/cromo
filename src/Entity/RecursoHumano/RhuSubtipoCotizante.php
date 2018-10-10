<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuSubtipoCotizanteRepository")
 */
class RhuSubtipoCotizante
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_subtipo_cotizante_pk", type="string", length=10)
     */
    private $codigoSubtipoCotizantePk;   
    
    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="ssoSubtipoCotizanteRel")
     */
    protected $empleadosSsoSubtipoCotizanteRel;     

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="subtipoCotizanteRel")
     */
    protected $contratosSubtipoCotizanteRel;
}
