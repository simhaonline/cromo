<?php

namespace App\Entity\RecursoHumano;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuEmbargoJuzgadoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoEmbargoJuzgadoPk"},message="Ya existe el codigo de juzgado")
 */
class RhuEmbargoJuzgado
{
    /**
     * @ORM\Column(name="codigo_embargo_juzgado_pk", type="string", length=30)
     * @ORM\Id
     */
    private $codigoEmbargoJuzgadoPk;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;    
    
    /**
     * @var string
     *
     * @ORM\Column(name="oficina", type="string", length=30, nullable=true)
     */
    private $oficina;

    /**
     * @var string
     *
     * @ORM\Column(name="cuenta", type="string", length=30, nullable=true)
     */
    private $cuenta;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmbargo", mappedBy="embargoJuzgadoRel")
     */
    protected $embargosEmbargoJuzgadoRel;
}
