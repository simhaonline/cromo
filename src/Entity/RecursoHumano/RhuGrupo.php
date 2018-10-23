<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuGrupoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoGrupoPk"},message="Ya existe el código del grupo")
 */
class RhuGrupo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_grupo_pk", type="string", length=10)
     */        
    private $codigoGrupoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="grupoRel")
     */
    protected $contratosGrupoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuProgramacion", mappedBy="grupoRel")
     */
    protected $programacionesGrupoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuNovedad", mappedBy="grupoRel")
     */
    protected $novedadesGrupoRel;

    /**
     * @return mixed
     */
    public function getCodigoGrupoPk()
    {
        return $this->codigoGrupoPk;
    }

    /**
     * @param mixed $codigoGrupoPk
     */
    public function setCodigoGrupoPk($codigoGrupoPk): void
    {
        $this->codigoGrupoPk = $codigoGrupoPk;
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
    public function getContratosGrupoRel()
    {
        return $this->contratosGrupoRel;
    }

    /**
     * @param mixed $contratosGrupoRel
     */
    public function setContratosGrupoRel($contratosGrupoRel): void
    {
        $this->contratosGrupoRel = $contratosGrupoRel;
    }

    /**
     * @return mixed
     */
    public function getProgramacionesGrupoRel()
    {
        return $this->programacionesGrupoRel;
    }

    /**
     * @param mixed $programacionesGrupoRel
     */
    public function setProgramacionesGrupoRel($programacionesGrupoRel): void
    {
        $this->programacionesGrupoRel = $programacionesGrupoRel;
    }

    /**
     * @return mixed
     */
    public function getNovedadesGrupoRel()
    {
        return $this->novedadesGrupoRel;
    }

    /**
     * @param mixed $novedadesGrupoRel
     */
    public function setNovedadesGrupoRel($novedadesGrupoRel): void
    {
        $this->novedadesGrupoRel = $novedadesGrupoRel;
    }
}
