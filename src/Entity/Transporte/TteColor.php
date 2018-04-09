<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteColorRepository")
 */
class TteColor
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoColorPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @return mixed
     */
    public function getCodigoColorPk()
    {
        return $this->codigoColorPk;
    }

    /**
     * @param mixed $codigoColorPk
     */
    public function setCodigoColorPk($codigoColorPk): void
    {
        $this->codigoColorPk = $codigoColorPk;
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

