<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GuiaRepository")
 */
class Guia
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoGuiaPk;

    /**
     * @return mixed
     */
    public function getCodigoGuiaPk()
    {
        return $this->codigoGuiaPk;
    }

    /**
     * @param mixed $codigoGuiaPk
     */
    public function setCodigoGuiaPk($codigoGuiaPk): void
    {
        $this->codigoGuiaPk = $codigoGuiaPk;
    }
    
}

