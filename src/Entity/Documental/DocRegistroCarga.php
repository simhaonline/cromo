<?php


namespace App\Entity\Documental;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Documental\DocRegistroCargaRepository")
 */
class DocRegistroCarga
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoRegistroCargaPk;

    /**
     * @ORM\Column(name="identificador", type="string", length=50, nullable=true)
     */
    private $identificador;

    /**
     * @return mixed
     */
    public function getCodigoRegistroCargaPk()
    {
        return $this->codigoRegistroCargaPk;
    }

    /**
     * @param mixed $codigoRegistroCargaPk
     */
    public function setCodigoRegistroCargaPk($codigoRegistroCargaPk): void
    {
        $this->codigoRegistroCargaPk = $codigoRegistroCargaPk;
    }

    /**
     * @return mixed
     */
    public function getIdentificador()
    {
        return $this->identificador;
    }

    /**
     * @param mixed $identificador
     */
    public function setIdentificador($identificador): void
    {
        $this->identificador = $identificador;
    }


}

