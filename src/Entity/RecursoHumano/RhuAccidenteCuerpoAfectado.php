<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAccidenteCuerpoAfectadoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuAccidenteCuerpoAfectado
{
    public $infoLog = [
        "primaryKey" => "codigoAccidenteAgentePk",
        "todos" => true,
    ];

    /**
     * @var int
     *
     * @ORM\Column(name="codigo_accidente_cuerpo_afectado_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAccidenteCuerpoAfectadoPk;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @return int
     */
    public function getCodigoAccidenteCuerpoAfectadoPk(): int
    {
        return $this->codigoAccidenteCuerpoAfectadoPk;
    }

    /**
     * @param int $codigoAccidenteCuerpoAfectadoPk
     */
    public function setCodigoAccidenteCuerpoAfectadoPk(int $codigoAccidenteCuerpoAfectadoPk): void
    {
        $this->codigoAccidenteCuerpoAfectadoPk = $codigoAccidenteCuerpoAfectadoPk;
    }

    /**
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
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