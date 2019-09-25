<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAccidenteNaturalezaLesionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuAccidenteNaturalezaLesion
{
    /**
     * @ORM\Column(name="codigo_accidente_naturaleza_lesion_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAccidenteNaturalezaLesionPk;

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
    public function getCodigoAccidenteNaturalezaLesionPk()
    {
        return $this->codigoAccidenteNaturalezaLesionPk;
    }

    /**
     * @param mixed $codigoAccidenteNaturalezaLesionPk
     */
    public function setCodigoAccidenteNaturalezaLesionPk($codigoAccidenteNaturalezaLesionPk): void
    {
        $this->codigoAccidenteNaturalezaLesionPk = $codigoAccidenteNaturalezaLesionPk;
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