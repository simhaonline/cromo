<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuDisciplinarioTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuDisciplinarioTipo
{
    public $infoLog = [
        "primaryKey" => "codigoDisciplinarioTipoPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_disciplinario_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDisciplinarioTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=false)
     */
    private $nombre;

    /**
     * @ORM\Column(name="suspension", type="boolean",options={"default" : false}, nullable=true)
     */
    private $suspension = false;

    /**
     * @ORM\OneToMany(targetEntity="RhuDisciplinario", mappedBy="disciplinarioTipoRel")
     */
    protected $disciplinariosDisciplinarioTipoRel;

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
    public function getCodigoDisciplinarioTipoPk()
    {
        return $this->codigoDisciplinarioTipoPk;
    }

    /**
     * @param mixed $codigoDisciplinarioTipoPk
     */
    public function setCodigoDisciplinarioTipoPk($codigoDisciplinarioTipoPk): void
    {
        $this->codigoDisciplinarioTipoPk = $codigoDisciplinarioTipoPk;
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
    public function getSuspension()
    {
        return $this->suspension;
    }

    /**
     * @param mixed $suspension
     */
    public function setSuspension($suspension): void
    {
        $this->suspension = $suspension;
    }

    /**
     * @return mixed
     */
    public function getDisciplinariosDisciplinarioTipoRel()
    {
        return $this->disciplinariosDisciplinarioTipoRel;
    }

    /**
     * @param mixed $disciplinariosDisciplinarioTipoRel
     */
    public function setDisciplinariosDisciplinarioTipoRel($disciplinariosDisciplinarioTipoRel): void
    {
        $this->disciplinariosDisciplinarioTipoRel = $disciplinariosDisciplinarioTipoRel;
    }




}
