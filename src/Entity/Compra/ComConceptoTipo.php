<?php

namespace App\Entity\Compra;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Compra\ComConceptoTipoRepository")
 */
class ComConceptoTipo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="codigo_concepto_tipo_pk", type="string" , length=10, nullable=true)
     */
    private $codigoConceptoTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string")
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compra\ComConcepto", mappedBy="conceptoTipoRel")
     */
    private $conceptosTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoConceptoTipoPk()
    {
        return $this->codigoConceptoTipoPk;
    }

    /**
     * @param mixed $codigoConceptoTipoPk
     */
    public function setCodigoConceptoTipoPk($codigoConceptoTipoPk): void
    {
        $this->codigoConceptoTipoPk = $codigoConceptoTipoPk;
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
    public function getConceptosTipoRel()
    {
        return $this->conceptosTipoRel;
    }

    /**
     * @param mixed $conceptosTipoRel
     */
    public function setConceptosTipoRel($conceptosTipoRel): void
    {
        $this->conceptosTipoRel = $conceptosTipoRel;
    }


}
