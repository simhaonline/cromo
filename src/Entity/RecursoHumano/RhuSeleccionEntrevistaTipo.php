<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuSeleccionEntrevistaTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuSeleccionEntrevistaTipo
{
    public $infoLog = [
        "primaryKey" => "codigoSeleccionEntrevistaTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_entrevista_tipo_pk", type="string", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoSeleccionEntrevistaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccionEntrevista", mappedBy="seleccionEntrevistaTipoRel")
     */
    protected $seleccionesEntrevistasSelecionEntrevistaTipoRel;

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
    public function getCodigoSeleccionEntrevistaTipoPk()
    {
        return $this->codigoSeleccionEntrevistaTipoPk;
    }

    /**
     * @param mixed $codigoSeleccionEntrevistaTipoPk
     */
    public function setCodigoSeleccionEntrevistaTipoPk($codigoSeleccionEntrevistaTipoPk): void
    {
        $this->codigoSeleccionEntrevistaTipoPk = $codigoSeleccionEntrevistaTipoPk;
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
    public function getSeleccionesEntrevistasSelecionEntrevistaTipoRel()
    {
        return $this->seleccionesEntrevistasSelecionEntrevistaTipoRel;
    }

    /**
     * @param mixed $seleccionesEntrevistasSelecionEntrevistaTipoRel
     */
    public function setSeleccionesEntrevistasSelecionEntrevistaTipoRel($seleccionesEntrevistasSelecionEntrevistaTipoRel): void
    {
        $this->seleccionesEntrevistasSelecionEntrevistaTipoRel = $seleccionesEntrevistasSelecionEntrevistaTipoRel;
    }



}
