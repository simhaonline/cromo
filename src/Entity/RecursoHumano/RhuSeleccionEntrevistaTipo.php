<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\RhuSeleccionEntrevistaTipoRepository")
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
     * @ORM\Column(name="codigo_seleccion_entrevista_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoSeleccionEntrevistaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     * * @Assert\NotBlank(message="El campo no puede estar vacio")
     */
    private $nombre;

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


}
