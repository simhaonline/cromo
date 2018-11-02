<?php

namespace App\Entity\Modulo;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Modulo\ModuloRepository")
 */
class Modulo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", type="string", length=80, nullable=false)
     */
    private $codigoModuloPk;

    /**
     * @return mixed
     */
    public function getCodigoModuloPk()
    {
        return $this->codigoModuloPk;
    }

    /**
     * @param mixed $codigoModuloPk
     */
    public function setCodigoModuloPk($codigoModuloPk)
    {
        $this->codigoModuloPk = $codigoModuloPk;
        return $this;
    }



}
