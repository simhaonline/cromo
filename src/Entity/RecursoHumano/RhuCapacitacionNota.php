<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCapacitacionNotaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */

class RhuCapacitacionNota
{
    public $infoLog = [
        "primaryKey" => "codigoCapacitacionNotaPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_capacitacion_nota_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCapacitacionNotaPk;

    /**
     * @ORM\Column(name="codigo_capacitacion_fk", type="integer", nullable=true)
     */
    private $codigoCapacitacionFk;

    /**
     * @ORM\Column(name="nota", type="string", length=500, nullable=true)
     */
    private $nota;

    /**
     * @return mixed
     */
    public function getCodigoCapacitacionNotaPk()
    {
        return $this->codigoCapacitacionNotaPk;
    }

    /**
     * @param mixed $codigoCapacitacionNotaPk
     */
    public function setCodigoCapacitacionNotaPk($codigoCapacitacionNotaPk): void
    {
        $this->codigoCapacitacionNotaPk = $codigoCapacitacionNotaPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCapacitacionFk()
    {
        return $this->codigoCapacitacionFk;
    }

    /**
     * @param mixed $codigoCapacitacionFk
     */
    public function setCodigoCapacitacionFk($codigoCapacitacionFk): void
    {
        $this->codigoCapacitacionFk = $codigoCapacitacionFk;
    }

    /**
     * @return mixed
     */
    public function getNota()
    {
        return $this->nota;
    }

    /**
     * @param mixed $nota
     */
    public function setNota($nota): void
    {
        $this->nota = $nota;
    }


}