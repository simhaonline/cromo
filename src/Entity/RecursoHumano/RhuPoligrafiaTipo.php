<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuPoligrafiaTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuPoligrafiaTipo
{
    public $infoLog = [
        "primaryKey" => "codigoPoligrafiaTipoPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_poligrafia_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPoligrafiaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuPoligrafia", mappedBy="poligrafiaTipoRel")
     */
    protected $poligrafiasPoligrafiaTipoRel;

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
    public function getCodigoPoligrafiaTipoPk()
    {
        return $this->codigoPoligrafiaTipoPk;
    }

    /**
     * @param mixed $codigoPoligrafiaTipoPk
     */
    public function setCodigoPoligrafiaTipoPk($codigoPoligrafiaTipoPk): void
    {
        $this->codigoPoligrafiaTipoPk = $codigoPoligrafiaTipoPk;
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
    public function getPoligrafiasPoligrafiaTipoRel()
    {
        return $this->poligrafiasPoligrafiaTipoRel;
    }

    /**
     * @param mixed $poligrafiasPoligrafiaTipoRel
     */
    public function setPoligrafiasPoligrafiaTipoRel($poligrafiasPoligrafiaTipoRel): void
    {
        $this->poligrafiasPoligrafiaTipoRel = $poligrafiasPoligrafiaTipoRel;
    }



}
