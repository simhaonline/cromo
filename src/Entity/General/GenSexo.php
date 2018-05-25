<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenSexoRepository")
 */
class GenSexo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_sexo_pk", type="integer")
     */
    private $codigoSexoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=30, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", mappedBy="genSexoRel")
     */
    protected $rhuSolicitudSexoRel;
}

