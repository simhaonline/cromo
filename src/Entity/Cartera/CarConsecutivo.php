<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarConsecutivoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class CarConsecutivo
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cartera_consecutivo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoCarteraConsecutivoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;

    /**
     * @var int
     *
     * @ORM\Column(name="consecutivo", type="integer",nullable=true)
     */
    private $consecutivo;

    /**
     * @return mixed
     */
    public function getCodigoCarteraConsecutivoPk()
    {
        return $this->codigoCarteraConsecutivoPk;
    }

    /**
     * @param mixed $codigoCarteraConsecutivoPk
     */
    public function setCodigoCarteraConsecutivoPk($codigoCarteraConsecutivoPk): void
    {
        $this->codigoCarteraConsecutivoPk = $codigoCarteraConsecutivoPk;
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
     * @return int
     */
    public function getConsecutivo(): int
    {
        return $this->consecutivo;
    }

    /**
     * @param int $consecutivo
     */
    public function setConsecutivo(int $consecutivo): void
    {
        $this->consecutivo = $consecutivo;
    }



}
