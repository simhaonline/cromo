<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuSeleccionTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuSeleccionTipo
{
    public $infoLog = [
        "primaryKey" => "codigoSeleccionTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoSeleccionTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="seleccionTipoRel")
     */
    protected $seleccionesTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoSeleccionTipoPk()
    {
        return $this->codigoSeleccionTipoPk;
    }

    /**
     * @param mixed $codigoSeleccionTipoPk
     */
    public function setCodigoSeleccionTipoPk($codigoSeleccionTipoPk): void
    {
        $this->codigoSeleccionTipoPk = $codigoSeleccionTipoPk;
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
    public function getSeleccionTipoRel()
    {
        return $this->seleccionTipoRel;
    }

    /**
     * @param mixed $seleccionTipoRel
     */
    public function setSeleccionTipoRel($seleccionTipoRel): void
    {
        $this->seleccionTipoRel = $seleccionTipoRel;
    }


}
