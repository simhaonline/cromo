<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarNotaCreditoConceptoRepository")
 */
class CarNotaCreditoConcepto
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_nota_credito_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoNotaCreditoConceptoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @return mixed
     */
    public function getCodigoNotaCreditoConceptoPk()
    {
        return $this->codigoNotaCreditoConceptoPk;
    }

    /**
     * @param mixed $codigoNotaCreditoConceptoPk
     */
    public function setCodigoNotaCreditoConceptoPk($codigoNotaCreditoConceptoPk): void
    {
        $this->codigoNotaCreditoConceptoPk = $codigoNotaCreditoConceptoPk;
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
