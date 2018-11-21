<?php

namespace App\Entity\Compra;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Compra\ComConceptoTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoConceptoTipoPk"},message="Ya existe un registro con el mismo codigo")
 */
class ComConceptoTipo
{
    public $infoLog = [
        "primaryKey" => "codigoConceptoTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
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
