<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuVisitaTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuVisitaTipo
{
    public $infoLog = [
        "primaryKey" => "codigoVisitaTipoPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_visita_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoVisitaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=false)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuVisita", mappedBy="visitaTipoRel")
     */
    protected $visitasVisitaTipoRel;

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
    public function getCodigoVisitaTipoPk()
    {
        return $this->codigoVisitaTipoPk;
    }

    /**
     * @param mixed $codigoVisitaTipoPk
     */
    public function setCodigoVisitaTipoPk($codigoVisitaTipoPk): void
    {
        $this->codigoVisitaTipoPk = $codigoVisitaTipoPk;
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
    public function getVisitasVisitaTipoRel()
    {
        return $this->visitasVisitaTipoRel;
    }

    /**
     * @param mixed $visitasVisitaTipoRel
     */
    public function setVisitasVisitaTipoRel($visitasVisitaTipoRel): void
    {
        $this->visitasVisitaTipoRel = $visitasVisitaTipoRel;
    }



}
