<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuNovedadTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoNovedadTipoPk"},message="Ya existe el código del tipo")
 */
class RhuNovedadTipo
{
    public $infoLog = [
        "primaryKey" => "codigoNovedadTipoPk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_novedad_tipo_pk", type="string", length=10)
     */
    private $codigoNovedadTipoPk;

    /**
     * @ORM\Column(name="codigo_concepto_fk", type="string",length=10, nullable=true)
     */
    private $codigoConceptoFk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="abreviatura", type="string", length=5, nullable=true)
     */
    private $abreviatura;

    /**
     * @ORM\Column(name="sub_tipo", type="string", length=1, nullable=true)
     */
    private $subTipo;

    /**
     * @ORM\OneToMany(targetEntity="RhuNovedad", mappedBy="novedadTipoRel")
     */
    protected $novedadesNovedadTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuConcepto", inversedBy="novedadesConceptoRel")
     * @ORM\JoinColumn(name="codigo_concepto_fk", referencedColumnName="codigo_concepto_pk")
     */
    protected $conceptoRel;

    /**
     * @return mixed
     */
    public function getCodigoNovedadTipoPk()
    {
        return $this->codigoNovedadTipoPk;
    }

    /**
     * @param mixed $codigoNovedadTipoPk
     */
    public function setCodigoNovedadTipoPk($codigoNovedadTipoPk): void
    {
        $this->codigoNovedadTipoPk = $codigoNovedadTipoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoConceptoFk()
    {
        return $this->codigoConceptoFk;
    }

    /**
     * @param mixed $codigoConceptoFk
     */
    public function setCodigoConceptoFk($codigoConceptoFk): void
    {
        $this->codigoConceptoFk = $codigoConceptoFk;
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
    public function getAbreviatura()
    {
        return $this->abreviatura;
    }

    /**
     * @param mixed $abreviatura
     */
    public function setAbreviatura($abreviatura): void
    {
        $this->abreviatura = $abreviatura;
    }

    /**
     * @return mixed
     */
    public function getSubTipo()
    {
        return $this->subTipo;
    }

    /**
     * @param mixed $subTipo
     */
    public function setSubTipo($subTipo): void
    {
        $this->subTipo = $subTipo;
    }

    /**
     * @return mixed
     */
    public function getNovedadesNovedadTipoRel()
    {
        return $this->novedadesNovedadTipoRel;
    }

    /**
     * @param mixed $novedadesNovedadTipoRel
     */
    public function setNovedadesNovedadTipoRel($novedadesNovedadTipoRel): void
    {
        $this->novedadesNovedadTipoRel = $novedadesNovedadTipoRel;
    }

    /**
     * @return mixed
     */
    public function getConceptoRel()
    {
        return $this->conceptoRel;
    }

    /**
     * @param mixed $conceptoRel
     */
    public function setConceptoRel($conceptoRel): void
    {
        $this->conceptoRel = $conceptoRel;
    }
}