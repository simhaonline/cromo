<?php

namespace App\Entity\Crm;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Crm\CrmVisitaTipoRepository")
 */
class CrmVisitaTipo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", name="codigo_visita_tipo_pk", length=20, nullable=false, unique=true)
     */
    private $codigoVisitaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=20, nullable=false)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Crm\CrmVisita", mappedBy="visitaTipoRel", cascade={"remove","persist"})
     */
    protected $visitaVisitaTipoRel;

    /**
     * CrmVisitaTipo constructor.
     * @param $codigoVisitaTipoPk
     */
    public function __construct()
    {
        $this->visitaVisitaTipoRel = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getCodigoVisitaTipoPk()
    {
        return $this->codigoVisitaTipoPk;
    }

    /**
     * @param mixed $codigoVisitaTipoPk
     */
    public function setCodigoVisitaTipoPk($codigoVisitaTipoPk)
    {
        $this->codigoVisitaTipoPk = $codigoVisitaTipoPk;
        return $this;
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
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVisitaVisitaTipoRel()
    {
        return $this->visitaVisitaTipoRel;
    }

    /**
     * @param mixed $visitaVisitaTipoRel
     */
    public function setVisitaVisitaTipoRel($visitaVisitaTipoRel)
    {
        $this->visitaVisitaTipoRel = $visitaVisitaTipoRel;
        return $this;
    }


}
