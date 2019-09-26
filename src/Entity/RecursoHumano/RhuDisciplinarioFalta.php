<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuDisciplinarioFaltaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuDisciplinarioFalta
{
    public $infoLog = [
        "primaryKey" => "codigoDisciplinarioFaltaPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_disciplinario_falta_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDisciplinarioFaltaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=false)
     */
    private $nombre;

    /**
     * @ORM\Column(name="falta", type="text", nullable=true)
     */
    private $falta;

    /**
     * @ORM\Column(name="articulo", type="text", nullable=true)
     */
    private $articulo;

    /**
     * @ORM\OneToMany(targetEntity="RhuDisciplinario", mappedBy="disciplinariosFaltaRel")
     */
    protected $disciplinarioRel;

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
    public function getCodigoDisciplinarioFaltaPk()
    {
        return $this->codigoDisciplinarioFaltaPk;
    }

    /**
     * @param mixed $codigoDisciplinarioFaltaPk
     */
    public function setCodigoDisciplinarioFaltaPk($codigoDisciplinarioFaltaPk): void
    {
        $this->codigoDisciplinarioFaltaPk = $codigoDisciplinarioFaltaPk;
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
    public function getFalta()
    {
        return $this->falta;
    }

    /**
     * @param mixed $falta
     */
    public function setFalta($falta): void
    {
        $this->falta = $falta;
    }

    /**
     * @return mixed
     */
    public function getArticulo()
    {
        return $this->articulo;
    }

    /**
     * @param mixed $articulo
     */
    public function setArticulo($articulo): void
    {
        $this->articulo = $articulo;
    }

    /**
     * @return mixed
     */
    public function getDisciplinarioRel()
    {
        return $this->disciplinarioRel;
    }

    /**
     * @param mixed $disciplinarioRel
     */
    public function setDisciplinarioRel($disciplinarioRel): void
    {
        $this->disciplinarioRel = $disciplinarioRel;
    }


}
