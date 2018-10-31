<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenModeloRepository")
 */
class GenModelo
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=80, name="codigo_modelo_pk")
     */
    private $codigoModeloPk;

    /**
     * @return mixed
     */
    public function getCodigoModeloPk()
    {
        return $this->codigoModeloPk;
    }

    /**
     * @param mixed $codigoModeloPk
     */
    public function setCodigoModeloPk($codigoModeloPk): void
    {
        $this->codigoModeloPk = $codigoModeloPk;
    }



}

