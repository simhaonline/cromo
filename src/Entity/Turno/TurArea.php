<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurAreaRepository")
 */
class TurArea
{

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_area_pk", type="string", length=20)
     */
    private $codigoAreaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurPuesto", mappedBy="areaRel")
     */
    protected $puestosAreaRel;

    /**
     * @return mixed
     */
    public function getCodigoAreaPk()
    {
        return $this->codigoAreaPk;
    }

    /**
     * @param mixed $codigoAreaPk
     */
    public function setCodigoAreaPk($codigoAreaPk): void
    {
        $this->codigoAreaPk = $codigoAreaPk;
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
    public function getPuestosAreaRel()
    {
        return $this->puestosAreaRel;
    }

    /**
     * @param mixed $puestosAreaRel
     */
    public function setPuestosAreaRel($puestosAreaRel): void
    {
        $this->puestosAreaRel = $puestosAreaRel;
    }



}