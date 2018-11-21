<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuSolicitudExperienciaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuSolicitudExperiencia
{
    public $infoLog = [
        "primaryKey" => "codigoSolicitudExperienciaPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_solicitud_experiencia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoSolicitudExperienciaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", mappedBy="solicitudExperienciaRel")
     */
    protected $solicitudesExperienciasRel;

    /**
     * @return mixed
     */
    public function getCodigoSolicitudExperienciaPk()
    {
        return $this->codigoSolicitudExperienciaPk;
    }

    /**
     * @param mixed $codigoSolicitudExperienciaPk
     */
    public function setCodigoSolicitudExperienciaPk($codigoSolicitudExperienciaPk): void
    {
        $this->codigoSolicitudExperienciaPk = $codigoSolicitudExperienciaPk;
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
    public function getSolicitudesExperienciasRel()
    {
        return $this->solicitudesExperienciasRel;
    }

    /**
     * @param mixed $solicitudesExperienciasRel
     */
    public function setSolicitudesExperienciasRel($solicitudesExperienciasRel): void
    {
        $this->solicitudesExperienciasRel = $solicitudesExperienciasRel;
    }


}
