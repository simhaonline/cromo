<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCapacitacionTemaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */

class RhuCapacitacionTema
{
    public $infoLog = [
        "primaryKey" => "codigoCapacitacionTipoPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_capacitacion_tema_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCapacitacionTemaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=200)
     */
    private $nombre;

    /**
     * @ORM\Column(name="objetivo", type="text", nullable=true)
     */
    private $objetivo;

    /**
     * @ORM\Column(name="contenido", type="text", nullable=true)
     */
    private $contenido;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuCapacitacion", mappedBy="capacitacionTemaRel")
     */
    protected $capacitacionesCapacitacionTemaRel;

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
    public function getCodigoCapacitacionTemaPk()
    {
        return $this->codigoCapacitacionTemaPk;
    }

    /**
     * @param mixed $codigoCapacitacionTemaPk
     */
    public function setCodigoCapacitacionTemaPk($codigoCapacitacionTemaPk): void
    {
        $this->codigoCapacitacionTemaPk = $codigoCapacitacionTemaPk;
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
    public function getObjetivo()
    {
        return $this->objetivo;
    }

    /**
     * @param mixed $objetivo
     */
    public function setObjetivo($objetivo): void
    {
        $this->objetivo = $objetivo;
    }

    /**
     * @return mixed
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * @param mixed $contenido
     */
    public function setContenido($contenido): void
    {
        $this->contenido = $contenido;
    }

    /**
     * @return mixed
     */
    public function getCapacitacionesCapacitacionTemaRel()
    {
        return $this->capacitacionesCapacitacionTemaRel;
    }

    /**
     * @param mixed $capacitacionesCapacitacionTemaRel
     */
    public function setCapacitacionesCapacitacionTemaRel($capacitacionesCapacitacionTemaRel): void
    {
        $this->capacitacionesCapacitacionTemaRel = $capacitacionesCapacitacionTemaRel;
    }



}