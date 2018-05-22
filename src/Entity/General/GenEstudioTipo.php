<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenEstudioTipoRepository")
 */
class GenEstudioTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_estudio_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEstudioTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", mappedBy="genEstudioTipoRel")
     */
    protected $rhuSolicitudesEstudioTiposlRel;

}

