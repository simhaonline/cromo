<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCierreSeleccionMotivoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuCierreSeleccionMotivo
{
    public $infoLog = [
        "primaryKey" => "codigoCierreSeleccionMotivoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cierre_seleccion_motivo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoCierreSeleccionMotivoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="cierreSeleccionMotivoRel")
     */
    protected $seleccionesMotivoCierreRel;

    /**
     * @return mixed
     */
    public function getCodigoCierreSeleccionMotivoPk()
    {
        return $this->codigoCierreSeleccionMotivoPk;
    }

    /**
     * @param mixed $codigoCierreSeleccionMotivoPk
     */
    public function setCodigoCierreSeleccionMotivoPk($codigoCierreSeleccionMotivoPk): void
    {
        $this->codigoCierreSeleccionMotivoPk = $codigoCierreSeleccionMotivoPk;
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
    public function getRhuSeleccionMotivoCierreRel()
    {
        return $this->rhuSeleccionMotivoCierreRel;
    }

    /**
     * @param mixed $rhuSeleccionMotivoCierreRel
     */
    public function setRhuSeleccionMotivoCierreRel($rhuSeleccionMotivoCierreRel): void
    {
        $this->rhuSeleccionMotivoCierreRel = $rhuSeleccionMotivoCierreRel;
    }


    

}
