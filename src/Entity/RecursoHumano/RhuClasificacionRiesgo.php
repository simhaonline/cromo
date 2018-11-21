<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuClasificacionRiesgoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuClasificacionRiesgo
{
    public $infoLog = [
        "primaryKey" => "codigoClasificacionRiesgoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_clasificacion_riesgo_pk", type="string", length=10)
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
     * @ORM\OneToMany(targetEntity="RhuSolicitud", mappedBy="clasificacionRiesgoRel")
     */
    protected $solicitudesClasificacionRiesgoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="clasificacionRiesgoRel")
     */
    protected $contratosClasificacionRiesgoRel;

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

    /**
     * @return mixed
     */
    public function getContratosClasificacionRiesgoRel()
    {
        return $this->contratosClasificacionRiesgoRel;
    }

    /**
     * @param mixed $contratosClasificacionRiesgoRel
     */
    public function setContratosClasificacionRiesgoRel($contratosClasificacionRiesgoRel): void
    {
        $this->contratosClasificacionRiesgoRel = $contratosClasificacionRiesgoRel;
    }
}
