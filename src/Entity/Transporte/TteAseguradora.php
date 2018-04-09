<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteAseguradoraRepository")
 */
class TteAseguradora
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoAseguradoraPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @return mixed
     */
    public function getCodigoAseguradoraPk()
    {
        return $this->codigoAseguradoraPk;
    }

    /**
     * @param mixed $codigoAseguradoraPk
     */
    public function setCodigoAseguradoraPk($codigoAseguradoraPk): void
    {
        $this->codigoAseguradoraPk = $codigoAseguradoraPk;
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

