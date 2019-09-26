<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCapacitacionTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */

class RhuCapacitacionTipo
{
    public $infoLog = [
        "primaryKey" => "codigoCapacitacionTipoPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_capacitacion_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCapacitacionTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=200)
     */
    private $nombre;

    /**
     * @return mixed
     */
    public function getCodigoCapacitacionTipoPk()
    {
        return $this->codigoCapacitacionTipoPk;
    }

    /**
     * @param mixed $codigoCapacitacionTipoPk
     */
    public function setCodigoCapacitacionTipoPk($codigoCapacitacionTipoPk): void
    {
        $this->codigoCapacitacionTipoPk = $codigoCapacitacionTipoPk;
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