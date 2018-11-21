<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\RhuSeleccionReferenciaTipoRepository")
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
     * @ORM\Column(name="codigo_seleccion_referencia_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoSeleccionReferenciaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     * * @Assert\NotBlank(message="El campo no puede estar vacio")
     */
    private $nombre;

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


}
