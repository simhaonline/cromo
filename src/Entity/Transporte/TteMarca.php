<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteMarcaRepository")
 */
class TteMarca
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoMarcaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @return mixed
     */
    public function getCodigoMarcaPk()
    {
        return $this->codigoMarcaPk;
    }

    /**
     * @param mixed $codigoMarcaPk
     */
    public function setCodigoMarcaPk($codigoMarcaPk): void
    {
        $this->codigoMarcaPk = $codigoMarcaPk;
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

