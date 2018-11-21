<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuSolicitudMotivoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuSolicitudMotivo
{
    public $infoLog = [
        "primaryKey" => "codigoSolicitudMotivoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_solicitud_motivo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoSolicitudMotivoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", mappedBy="solicitudMotivoRel")
     */
    protected $solicitudesMotivosRel;

    /**
     * @return mixed
     */
    public function getCodigoSolicitudMotivoPk()
    {
        return $this->codigoSolicitudMotivoPk;
    }

    /**
     * @param mixed $codigoSolicitudMotivoPk
     */
    public function setCodigoSolicitudMotivoPk($codigoSolicitudMotivoPk): void
    {
        $this->codigoSolicitudMotivoPk = $codigoSolicitudMotivoPk;
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
    public function getSolicitudesMotivosRel()
    {
        return $this->solicitudesMotivosRel;
    }

    /**
     * @param mixed $solicitudesMotivosRel
     */
    public function setSolicitudesMotivosRel($solicitudesMotivosRel): void
    {
        $this->solicitudesMotivosRel = $solicitudesMotivosRel;
    }


}
