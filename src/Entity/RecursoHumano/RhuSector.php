<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuSectorRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoSectorPk"},message="Ya existe el código")
 */
class RhuSector
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_sector_pk", type="string", length=10)
     */        
    private $codigoSectorPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", mappedBy="sectorRel")
     */
    protected $empleadosSectorRel;

    /**
     * @return mixed
     */
    public function getCodigoSectorPk()
    {
        return $this->codigoSectorPk;
    }

    /**
     * @param mixed $codigoSectorPk
     */
    public function setCodigoSectorPk($codigoSectorPk): void
    {
        $this->codigoSectorPk = $codigoSectorPk;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getEmpleadosSectorRel()
    {
        return $this->empleadosSectorRel;
    }

    /**
     * @param mixed $empleadosSectorRel
     */
    public function setEmpleadosSectorRel($empleadosSectorRel): void
    {
        $this->empleadosSectorRel = $empleadosSectorRel;
    }



}
