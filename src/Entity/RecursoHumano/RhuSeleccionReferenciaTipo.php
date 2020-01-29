<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuSeleccionReferenciaTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuSeleccionReferenciaTipo
{
    public $infoLog = [
        "primaryKey" => "codigoSeleccionReferenciaTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_referencia_tipo_pk", type="string", length=10)
     */        
    private $codigoSeleccionReferenciaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccionReferencia", mappedBy="seleccionReferenciaTipoRel")
     */
    protected $seleccionesReferenciasSelecionReferenciaTipoRel;

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
    public function getCodigoSeleccionReferenciaTipoPk()
    {
        return $this->codigoSeleccionReferenciaTipoPk;
    }

    /**
     * @param mixed $codigoSeleccionReferenciaTipoPk
     */
    public function setCodigoSeleccionReferenciaTipoPk($codigoSeleccionReferenciaTipoPk): void
    {
        $this->codigoSeleccionReferenciaTipoPk = $codigoSeleccionReferenciaTipoPk;
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
    public function getSeleccionesReferenciasSelecionReferenciaTipoRel()
    {
        return $this->seleccionesReferenciasSelecionReferenciaTipoRel;
    }

    /**
     * @param mixed $seleccionesReferenciasSelecionReferenciaTipoRel
     */
    public function setSeleccionesReferenciasSelecionReferenciaTipoRel($seleccionesReferenciasSelecionReferenciaTipoRel): void
    {
        $this->seleccionesReferenciasSelecionReferenciaTipoRel = $seleccionesReferenciasSelecionReferenciaTipoRel;
    }


}
