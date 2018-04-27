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
     * @ORM\Column(name="codigo_nota_debito_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoNotaDebitoConceptoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

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



}
