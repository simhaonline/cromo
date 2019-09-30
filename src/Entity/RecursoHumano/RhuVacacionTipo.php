<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuVacacionTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoVacacionTipoPk"},message="Ya existe el código")
 *
 */
class RhuVacacionTipo
{
    public $infoLog = [
        "primaryKey" => "codigoVacacionTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_vacacion_tipo_pk", type="string", length=10)
     */
    private $codigoVacacionTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo", type="integer", nullable=true, options={"default":1})
     */
    private $consecutivo;

    /**
     * @ORM\Column(name="codigo_concepto_disfrutada_fk", type="string", length=10, nullable=true)
     */
    private $codigoConceptoDisfrutadaFk;

    /**
     * @ORM\Column(name="codigo_concepto_dinero_fk", type="string", length=10, nullable=true)
     */
    private $codigoConceptoDineroFk;

    /**
     * @ORM\ManyToOne(targetEntity="RhuConcepto", inversedBy="vacacionesTiposConceptoDisfrutadaRel")
     * @ORM\JoinColumn(name="codigo_concepto_disfrutada_fk", referencedColumnName="codigo_concepto_pk")
     */
    protected $conceptoDisfrutadaRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuConcepto", inversedBy="vacacionesTiposConceptoDineroRel")
     * @ORM\JoinColumn(name="codigo_concepto_dinero_fk", referencedColumnName="codigo_concepto_pk")
     */
    protected $conceptoDineroRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuVacacion", mappedBy="vacacionTipoRel")
     */
    protected $vacacionesVacacionTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoVacacionTipoPk()
    {
        return $this->codigoVacacionTipoPk;
    }

    /**
     * @param mixed $codigoVacacionTipoPk
     */
    public function setCodigoVacacionTipoPk($codigoVacacionTipoPk): void
    {
        $this->codigoVacacionTipoPk = $codigoVacacionTipoPk;
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
    public function getCodigoConceptoDisfrutadaFk()
    {
        return $this->codigoConceptoDisfrutadaFk;
    }

    /**
     * @param mixed $codigoConceptoDisfrutadaFk
     */
    public function setCodigoConceptoDisfrutadaFk($codigoConceptoDisfrutadaFk): void
    {
        $this->codigoConceptoDisfrutadaFk = $codigoConceptoDisfrutadaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoConceptoDineroFk()
    {
        return $this->codigoConceptoDineroFk;
    }

    /**
     * @param mixed $codigoConceptoDineroFk
     */
    public function setCodigoConceptoDineroFk($codigoConceptoDineroFk): void
    {
        $this->codigoConceptoDineroFk = $codigoConceptoDineroFk;
    }

    /**
     * @return mixed
     */
    public function getConceptoDisfrutadaRel()
    {
        return $this->conceptoDisfrutadaRel;
    }

    /**
     * @param mixed $conceptoDisfrutadaRel
     */
    public function setConceptoDisfrutadaRel($conceptoDisfrutadaRel): void
    {
        $this->conceptoDisfrutadaRel = $conceptoDisfrutadaRel;
    }

    /**
     * @return mixed
     */
    public function getConceptoDineroRel()
    {
        return $this->conceptoDineroRel;
    }

    /**
     * @param mixed $conceptoDineroRel
     */
    public function setConceptoDineroRel($conceptoDineroRel): void
    {
        $this->conceptoDineroRel = $conceptoDineroRel;
    }

    /**
     * @return mixed
     */
    public function getVacacionesVacacionTipoRel()
    {
        return $this->vacacionesVacacionTipoRel;
    }

    /**
     * @param mixed $vacacionesVacacionTipoRel
     */
    public function setVacacionesVacacionTipoRel($vacacionesVacacionTipoRel): void
    {
        $this->vacacionesVacacionTipoRel = $vacacionesVacacionTipoRel;
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