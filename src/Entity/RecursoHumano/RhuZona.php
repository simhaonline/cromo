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
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAspirante", mappedBy="zonaRel")
     */
    protected $aspirantesZonaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", mappedBy="zonaRel")
     */
    protected $solicitudesZonaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="zonaRel")
     */
    protected $seleccionesZonaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuCapacitacion", mappedBy="zonaRel")
     */
    protected $capacitacionesZonaRel;

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

    /**
     * @return mixed
     */
    public function getAspirantesZonaRel()
    {
        return $this->aspirantesZonaRel;
    }

    /**
     * @param mixed $aspirantesZonaRel
     */
    public function setAspirantesZonaRel($aspirantesZonaRel): void
    {
        $this->aspirantesZonaRel = $aspirantesZonaRel;
    }

    /**
     * @return mixed
     */
    public function getSolicitudesZonaRel()
    {
        return $this->solicitudesZonaRel;
    }

    /**
     * @param mixed $solicitudesZonaRel
     */
    public function setSolicitudesZonaRel($solicitudesZonaRel): void
    {
        $this->solicitudesZonaRel = $solicitudesZonaRel;
    }

    /**
     * @return mixed
     */
    public function getSeleccionesZonaRel()
    {
        return $this->seleccionesZonaRel;
    }

    /**
     * @param mixed $seleccionesZonaRel
     */
    public function setSeleccionesZonaRel($seleccionesZonaRel): void
    {
        $this->seleccionesZonaRel = $seleccionesZonaRel;
    }

    /**
     * @return mixed
     */
    public function getCapacitacionesZonaRel()
    {
        return $this->capacitacionesZonaRel;
    }

    /**
     * @param mixed $capacitacionesZonaRel
     */
    public function setCapacitacionesZonaRel($capacitacionesZonaRel): void
    {
        $this->capacitacionesZonaRel = $capacitacionesZonaRel;
    }



}
