<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuDisciplinarioMotivoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuDisciplinarioMotivo
{
    public $infoLog = [
        "primaryKey" => "codigoDisciplinarioMotivoPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_disciplinario_motivo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDisciplinarioMotivoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=false)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuDisciplinario", mappedBy="disciplinarioMotivoRel")
     */
    protected $disciplinariosDisciplinarioMotivoRel;

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
    public function getCodigoDisciplinarioMotivoPk()
    {
        return $this->codigoDisciplinarioMotivoPk;
    }

    /**
     * @param mixed $codigoDisciplinarioMotivoPk
     */
    public function setCodigoDisciplinarioMotivoPk($codigoDisciplinarioMotivoPk): void
    {
        $this->codigoDisciplinarioMotivoPk = $codigoDisciplinarioMotivoPk;
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
    public function getDisciplinariosDisciplinarioMotivoRel()
    {
        return $this->disciplinariosDisciplinarioMotivoRel;
    }

    /**
     * @param mixed $disciplinariosDisciplinarioMotivoRel
     */
    public function setDisciplinariosDisciplinarioMotivoRel($disciplinariosDisciplinarioMotivoRel): void
    {
        $this->disciplinariosDisciplinarioMotivoRel = $disciplinariosDisciplinarioMotivoRel;
    }



}
