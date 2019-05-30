<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurFestivoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurFestivo
{
    public $infoLog = [
        "primaryKey" => "codigoFestivoPk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_festivo_pk", type="string", length=8)
     */
    private $codigoFestivoPk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @return mixed
     */
    public function getCodigoFestivoPk()
    {
        return $this->codigoFestivoPk;
    }

    /**
     * @param mixed $codigoFestivoPk
     */
    public function setCodigoFestivoPk($codigoFestivoPk): void
    {
        $this->codigoFestivoPk = $codigoFestivoPk;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha): void
    {
        $this->fecha = $fecha;
    }



}

