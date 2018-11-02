<?php

namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenModuloRepository")
 */
class GenModulo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", name="codigo_modulo_pk",length=80, nullable=false)
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
