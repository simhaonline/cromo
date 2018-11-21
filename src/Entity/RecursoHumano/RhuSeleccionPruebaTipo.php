<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\RhuSeleccionPruebaTipoRepository")
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
     * @ORM\Column(name="codigo_seleccion_prueba_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoSeleccionPruebaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     * * @Assert\NotBlank(message="El campo no puede estar vacio")
     */
    private $nombre;

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


}
