<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarNotaDebitoConceptoRepository")
 */
class CarNotaDebitoConcepto
{

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=10, nullable=false, unique=true)
     */
    private $codigoNotaDebitoConceptoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo", type="integer", nullable=true)
     */
    private $consecutivo = 0;

    /**
     * @return mixed
     */
    public function getCodigoNotaDebitoConceptoPk()
    {
        return $this->codigoNotaDebitoConceptoPk;
    }

    /**
     * @param mixed $codigoNotaDebitoConceptoPk
     */
    public function setCodigoNotaDebitoConceptoPk($codigoNotaDebitoConceptoPk): void
    {
        $this->codigoNotaDebitoConceptoPk = $codigoNotaDebitoConceptoPk;
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
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }

    /**
     * @param mixed $consecutivo
     */
    public function setConsecutivo($consecutivo): void
    {
        $this->consecutivo = $consecutivo;
    }



}
