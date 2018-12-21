<?php

namespace App\Entity\Inventario;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvServicioTipoRepository")
 */
class InvServicioTipo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string",name="codigo_servicio_tipo_pk", unique=true, length=10)
     */
    private $codigoServicioTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=false)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvServicio", mappedBy="servicioTipoRel", cascade={"persist","remove"})
     */
    protected $servicioServicioTipoRel;

    /**
     * InvServicioTipo constructor.
     * @param $servicioServicioTipoRel
     */
    public function __construct()
    {
        $this->servicioServicioTipoRel = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getCodigoServicioTipoPk()
    {
        return $this->codigoServicioTipoPk;
    }

    /**
     * @param mixed $codigoServicioTipoPk
     */
    public function setCodigoServicioTipoPk($codigoServicioTipoPk)
    {
        $this->codigoServicioTipoPk = $codigoServicioTipoPk;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getServicioServicioTipoRel()
    {
        return $this->servicioServicioTipoRel;
    }

    /**
     * @param mixed $servicioServicioTipoRel
     */
    public function setServicioServicioTipoRel($servicioServicioTipoRel)
    {
        $this->servicioServicioTipoRel = $servicioServicioTipoRel;
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


}
