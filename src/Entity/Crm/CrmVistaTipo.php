<?php

namespace App\Entity\Crm;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Crm\CrmVistaTipoRepository")
 */
class CrmVistaTipo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", name="codigo_vista_tipo_pk", length=20, nullable=false, unique=true)
     */
    private $codigoVistaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=20, nullable=false)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Crm\CrmVista", mappedBy="vistaTipoRel", cascade={"remove","persist"})
     */
    protected $vistaVistaTipoRel;

    /**
     * CrmVistaTipo constructor.
     * @param $codigoVistaTipoPk
     */
    public function __construct()
    {
        $this->vistaVistaTipoRel = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getCodigoVistaTipoPk()
    {
        return $this->codigoVistaTipoPk;
    }

    /**
     * @param mixed $codigoVistaTipoPk
     */
    public function setCodigoVistaTipoPk($codigoVistaTipoPk)
    {
        $this->codigoVistaTipoPk = $codigoVistaTipoPk;
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
    public function getVistaVistaTipoRel()
    {
        return $this->vistaVistaTipoRel;
    }

    /**
     * @param mixed $vistaVistaTipoRel
     */
    public function setVistaVistaTipoRel($vistaVistaTipoRel)
    {
        $this->vistaVistaTipoRel = $vistaVistaTipoRel;
        return $this;
    }


}
