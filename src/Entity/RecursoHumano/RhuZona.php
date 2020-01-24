<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuZonaRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoZonaPk"},message="Ya existe el código")
 */
class RhuZona
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_zona_pk", type="string", length=10)
     */        
    private $codigoZonaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", mappedBy="zonaRel")
     */
    protected $empleadosZonaRel;

    /**
     * @return mixed
     */
    public function getCodigoZonaPk()
    {
        return $this->codigoZonaPk;
    }

    /**
     * @param mixed $codigoZonaPk
     */
    public function setCodigoZonaPk($codigoZonaPk): void
    {
        $this->codigoZonaPk = $codigoZonaPk;
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
    public function getEmpleadosZonaRel()
    {
        return $this->empleadosZonaRel;
    }

    /**
     * @param mixed $empleadosZonaRel
     */
    public function setEmpleadosZonaRel($empleadosZonaRel): void
    {
        $this->empleadosZonaRel = $empleadosZonaRel;
    }



}
