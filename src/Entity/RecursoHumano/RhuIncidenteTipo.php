<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuIncidenteTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuIncidenteTipo
{
    public $infoLog = [
        "primaryKey" => "codigoIncidenteTipoPk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_incidente_tipo_pk", type="string", length=10)
     */
    private $codigoIncidenteTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=false)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuIncidente", mappedBy="incidenteTipoRel")
     */
    protected $incidentesIncidenteTipoRel;

    /**
     * @return array
     */
    public function getInfoLog(): array
    {
        return $this->infoLog;
    }

    /**
     * @param array $infoLog
     */
    public function setInfoLog(array $infoLog): void
    {
        $this->infoLog = $infoLog;
    }

    /**
     * @return mixed
     */
    public function getCodigoIncidenteTipoPk()
    {
        return $this->codigoIncidenteTipoPk;
    }

    /**
     * @param mixed $codigoIncidenteTipoPk
     */
    public function setCodigoIncidenteTipoPk($codigoIncidenteTipoPk): void
    {
        $this->codigoIncidenteTipoPk = $codigoIncidenteTipoPk;
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
    public function getIncidentesIncidenteTipoRel()
    {
        return $this->incidentesIncidenteTipoRel;
    }

    /**
     * @param mixed $incidentesIncidenteTipoRel
     */
    public function setIncidentesIncidenteTipoRel($incidentesIncidenteTipoRel): void
    {
        $this->incidentesIncidenteTipoRel = $incidentesIncidenteTipoRel;
    }


}