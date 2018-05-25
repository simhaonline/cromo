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
     * @ORM\Column(name="codigo_clasificacion_riesgo_pk", type="integer")
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
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", mappedBy="clasificacionRiesgoRel")
     */
    protected $solicitudesClasificacionRiesgoRel;

    /**
     * @return mixed
     */
    public function getCodigoClasificacionRiesgoPk()
    {
        return $this->codigoClasificacionRiesgoPk;
    }

    /**
     * @param mixed $codigoClasificacionRiesgoPk
     */
    public function setCodigoClasificacionRiesgoPk($codigoClasificacionRiesgoPk): void
    {
        $this->codigoClasificacionRiesgoPk = $codigoClasificacionRiesgoPk;
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
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * @param mixed $porcentaje
     */
    public function setPorcentaje($porcentaje): void
    {
        $this->porcentaje = $porcentaje;
    }

    /**
     * @return mixed
     */
    public function getSolicitudesClasificacionRiesgoRel()
    {
        return $this->solicitudesClasificacionRiesgoRel;
    }

    /**
     * @param mixed $solicitudesClasificacionRiesgoRel
     */
    public function setSolicitudesClasificacionRiesgoRel($solicitudesClasificacionRiesgoRel): void
    {
        $this->solicitudesClasificacionRiesgoRel = $solicitudesClasificacionRiesgoRel;
    }


}
