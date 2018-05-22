<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\RhuGrupoPagoRepository")
 */
class RhuGrupoPago
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_grupo_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoGrupoPagoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", mappedBy="grupoPagoRel")
     */
    protected $solicitudesGrupoPagoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="grupoPagoRel")
     */
    protected $seleccionGrupoPagoRel;
}
