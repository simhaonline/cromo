<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCostoGrupoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoCostoGrupoPk"},message="Ya existe el código del grupo")
 */
class RhuCostoGrupo
{
    public $infoLog = [
        "primaryKey" => "codigoCostoGrupoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_costo_grupo_pk", type="string", length=10)
     */        
    private $codigoCostoGrupoPk;

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="string", length=10, nullable=true)
     */
    private $codigoCentroCostoFk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="costoGrupoRel")
     */
    protected $contratosCostoGrupoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Financiero\FinCentroCosto",  inversedBy="rhuCostosGruposCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;

    /**
     * @return mixed
     */
    public function getCodigoCostoGrupoPk()
    {
        return $this->codigoCostoGrupoPk;
    }

    /**
     * @param mixed $codigoCostoGrupoPk
     */
    public function setCodigoCostoGrupoPk($codigoCostoGrupoPk): void
    {
        $this->codigoCostoGrupoPk = $codigoCostoGrupoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * @param mixed $codigoCentroCostoFk
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk): void
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;
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
    public function getContratosCostoGrupoRel()
    {
        return $this->contratosCostoGrupoRel;
    }

    /**
     * @param mixed $contratosCostoGrupoRel
     */
    public function setContratosCostoGrupoRel($contratosCostoGrupoRel): void
    {
        $this->contratosCostoGrupoRel = $contratosCostoGrupoRel;
    }

    /**
     * @return mixed
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }

    /**
     * @param mixed $centroCostoRel
     */
    public function setCentroCostoRel($centroCostoRel): void
    {
        $this->centroCostoRel = $centroCostoRel;
    }
}
