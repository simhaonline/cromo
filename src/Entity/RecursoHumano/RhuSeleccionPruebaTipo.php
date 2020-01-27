<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuSeleccionPruebaTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuSeleccionPruebaTipo
{
    public $infoLog = [
        "primaryKey" => "codigoSeleccionPruebaTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_prueba_tipo_pk", type="string", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoSeleccionPruebaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccionPrueba", mappedBy="seleccionPruebaTipoRel")
     */
    protected $seleccionesPruebasSelecionPruebaTipoRel;

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
    public function getCodigoSeleccionPruebaTipoPk()
    {
        return $this->codigoSeleccionPruebaTipoPk;
    }

    /**
     * @param mixed $codigoSeleccionPruebaTipoPk
     */
    public function setCodigoSeleccionPruebaTipoPk($codigoSeleccionPruebaTipoPk): void
    {
        $this->codigoSeleccionPruebaTipoPk = $codigoSeleccionPruebaTipoPk;
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
    public function getSeleccionesPruebasSelecionPruebaTipoRel()
    {
        return $this->seleccionesPruebasSelecionPruebaTipoRel;
    }

    /**
     * @param mixed $seleccionesPruebasSelecionPruebaTipoRel
     */
    public function setSeleccionesPruebasSelecionPruebaTipoRel($seleccionesPruebasSelecionPruebaTipoRel): void
    {
        $this->seleccionesPruebasSelecionPruebaTipoRel = $seleccionesPruebasSelecionPruebaTipoRel;
    }



}
