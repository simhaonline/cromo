<?php

namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenImpuestoTipoRepository")
 */
class GenImpuestoTipo
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="codigo_impuesto_tipo_pk", type="string", length=3, nullable=false)
     */
    private $codigoImpuestoTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=10)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\General\GenImpuesto", mappedBy="impuestoTipoRel")
     */
    private $impuestosImpuestoTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoImpuestoTipoPk()
    {
        return $this->codigoImpuestoTipoPk;
    }

    /**
     * @param mixed $codigoImpuestoTipoPk
     */
    public function setCodigoImpuestoTipoPk($codigoImpuestoTipoPk): void
    {
        $this->codigoImpuestoTipoPk = $codigoImpuestoTipoPk;
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
    public function getImpuestosImpuestoTipoRel()
    {
        return $this->impuestosImpuestoTipoRel;
    }

    /**
     * @param mixed $impuestosImpuestoTipoRel
     */
    public function setImpuestosImpuestoTipoRel($impuestosImpuestoTipoRel): void
    {
        $this->impuestosImpuestoTipoRel = $impuestosImpuestoTipoRel;
    }



}
