<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCostoTipoRepository")
 */

class RhuCostoTipo
{

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_costo_tipo_pk", type="string", length=10)
     */
    private $codigoCostoTipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=200, nullable=true)
     */    
    private $nombre;

    /**
     * @return mixed
     */
    public function getCodigoCostoTipoPk()
    {
        return $this->codigoCostoTipoPk;
    }

    /**
     * @param mixed $codigoCostoTipoPk
     */
    public function setCodigoCostoTipoPk($codigoCostoTipoPk): void
    {
        $this->codigoCostoTipoPk = $codigoCostoTipoPk;
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
