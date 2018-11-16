<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurSectorRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoSectorPk"}, message="Ya existe el codigo del sector")
 */
class TurSector
{
    public $infoLog = [
        "primaryKey" => "codigoSectorPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_sector_pk", type="string", length=10)
     */
    private $codigoSectorPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="porcentaje", type="float", nullable=true)
     */
    private $porcentaje = 0;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurContrato", mappedBy="sectorRel")
     */
    protected $contratosSectorRel;

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
    public function getCodigoSectorPk()
    {
        return $this->codigoSectorPk;
    }

    /**
     * @param mixed $codigoSectorPk
     */
    public function setCodigoSectorPk($codigoSectorPk): void
    {
        $this->codigoSectorPk = $codigoSectorPk;
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
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * @param mixed $porcentaje
     */
    public function setPorcentaje($porcentaje): void
    {
        $this->porcentaje = $porcentaje;
    }

    /**
     * @return mixed
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * @param mixed $comentarios
     */
    public function setComentarios($comentarios): void
    {
        $this->comentarios = $comentarios;
    }

    /**
     * @return mixed
     */
    public function getContratosSectorRel()
    {
        return $this->contratosSectorRel;
    }

    /**
     * @param mixed $contratosSectorRel
     */
    public function setContratosSectorRel($contratosSectorRel): void
    {
        $this->contratosSectorRel = $contratosSectorRel;
    }



}

