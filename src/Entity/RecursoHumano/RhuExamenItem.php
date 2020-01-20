<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuExamenItemRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuExamenItem
{
    public $infoLog = [
        "primaryKey" => "codigoExamenItemPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_item_pk", type="string", length=20)
     */
    private $codigoExamenItemPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuExamenDetalle", mappedBy="examenItemRel")
     */
    protected $examenItemsExamenDetalleRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuExamenListaPrecio", mappedBy="examenItemRel")
     */
    protected $examenItemsExamenListaPrecioRel;

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
    public function getCodigoExamenItemPk()
    {
        return $this->codigoExamenItemPk;
    }

    /**
     * @param mixed $codigoExamenItemPk
     */
    public function setCodigoExamenItemPk($codigoExamenItemPk): void
    {
        $this->codigoExamenItemPk = $codigoExamenItemPk;
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
    public function getExamenesExamenDetalleRel()
    {
        return $this->examenesExamenDetalleRel;
    }

    /**
     * @param mixed $examenesExamenDetalleRel
     */
    public function setExamenesExamenDetalleRel($examenesExamenDetalleRel): void
    {
        $this->examenesExamenDetalleRel = $examenesExamenDetalleRel;
    }

}
