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
     * @ORM\Column(name="sub_tipo", type="string", length=1, nullable=true)
     */
    private $subTipo;

    /**
     * @ORM\Column(name="afecta_salud", type="boolean", nullable=true)
     */
    private $afectaSalud = false;

    /**
     * @ORM\Column(name="ausentismo", type="boolean", nullable=true)
     */
    private $ausentismo = false;

    /**
     * @ORM\Column(name="maternidad", type="boolean", nullable=true)
     */
    private $maternidad = false;

    /**
     * @ORM\Column(name="paternidad", type="boolean", nullable=true)
     */
    private $paternidad = false;

    /**
     * @ORM\Column(name="remunerada", type="boolean", nullable=true)
     */
    private $remunerada = false;

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
    public function getAfectaSalud()
    {
        return $this->afectaSalud;
    }

    /**
     * @param mixed $afectaSalud
     */
    public function setAfectaSalud($afectaSalud): void
    {
        $this->afectaSalud = $afectaSalud;
    }

    /**
     * @return mixed
     */
    public function getAusentismo()
    {
        return $this->ausentismo;
    }

    /**
     * @param mixed $ausentismo
     */
    public function setAusentismo($ausentismo): void
    {
        $this->ausentismo = $ausentismo;
    }

    /**
     * @return mixed
     */
    public function getMaternidad()
    {
        return $this->maternidad;
    }

    /**
     * @param mixed $maternidad
     */
    public function setMaternidad($maternidad): void
    {
        $this->maternidad = $maternidad;
    }

    /**
     * @return mixed
     */
    public function getPaternidad()
    {
        return $this->paternidad;
    }

    /**
     * @param mixed $paternidad
     */
    public function setPaternidad($paternidad): void
    {
        $this->paternidad = $paternidad;
    }

    /**
     * @return mixed
     */
    public function getRemunerada()
    {
        return $this->remunerada;
    }

    /**
     * @param mixed $remunerada
     */
    public function setRemunerada($remunerada): void
    {
        $this->remunerada = $remunerada;
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