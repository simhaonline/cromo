<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAccidenteMecanismoAccidenteRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuAccidenteMecanismoAccidente
{
    /**
     * @ORM\Column(name="codigo_accidente_mecanismo_accidente_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAccidenteMecanismoAccidentePk;

    /**
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @return mixed
     */
    public function getCodigoAccidenteMecanismoAccidentePk()
    {
        return $this->codigoAccidenteMecanismoAccidentePk;
    }

    /**
     * @param mixed $codigoAccidenteMecanismoAccidentePk
     */
    public function setCodigoAccidenteMecanismoAccidentePk($codigoAccidenteMecanismoAccidentePk): void
    {
        $this->codigoAccidenteMecanismoAccidentePk = $codigoAccidenteMecanismoAccidentePk;
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
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion): void
    {
        $this->descripcion = $descripcion;
    }


}