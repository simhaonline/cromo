<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCapacitacionMetodologiaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */

class RhuCapacitacionMetodologia
{
    public $infoLog = [
        "primaryKey" => "codigoCapacitacionMetodologiaPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_capacitacion_metodologia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCapacitacionMetodologiaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */
    private $nombre;

    /**
     * @return mixed
     */
    public function getCodigoCapacitacionMetodologiaPk()
    {
        return $this->codigoCapacitacionMetodologiaPk;
    }

    /**
     * @param mixed $codigoCapacitacionMetodologiaPk
     */
    public function setCodigoCapacitacionMetodologiaPk($codigoCapacitacionMetodologiaPk): void
    {
        $this->codigoCapacitacionMetodologiaPk = $codigoCapacitacionMetodologiaPk;
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