<?php

namespace App\Entity\RecursoHumano;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_centro_trabajo")
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCentroTrabajoRepository")
 */
class RhuCentroTrabajo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_centro_trabajo_pk", type="string", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCentroTrabajoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=160, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="centroTrabajoRel")
     */
    protected $contratoRel;
}
