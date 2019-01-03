<?php

namespace App\Entity\Crm;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Crm\CrmVistaRepository")
 */
class CrmVista
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="codigo_vista_pk", unique=true)
     */
    private $codigoVistaPk;

    /**
     * @ORM\Column(name="codigo_vista_tipo_fk", type="string", length=20, nullable=false)
     */
    private $codigoVistaTipoFk;

    /**
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(name="comentarios", type="string", length=100)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean", options={"default":false})
     */
    private $estadoAprobado=false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean", options={"default":false})
     */
    private $estadoAnulado=false;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean", options={"default":false})
     */
    private $estadoAutorizado=false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Crm\CrmVistaTipo", inversedBy="vistaVistaTipoRel")
     * @ORM\JoinColumn(name="codigo_vista_tipo_fk", referencedColumnName="codigo_vista_tipo_pk")
     */
    protected $vistaTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoVistaPk()
    {
        return $this->codigoVistaPk;
    }

    /**
     * @param mixed $codigoVistaPk
     */
    public function setCodigoVistaPk($codigoVistaPk)
    {
        $this->codigoVistaPk = $codigoVistaPk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoVistaTipoFk()
    {
        return $this->codigoVistaTipoFk;
    }

    /**
     * @param mixed $codigoVistaTipoFk
     */
    public function setCodigoVistaTipoFk($codigoVistaTipoFk)
    {
        $this->codigoVistaTipoFk = $codigoVistaTipoFk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * @param mixed $comentarios
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVistaTipoRel()
    {
        return $this->vistaTipoRel;
    }

    /**
     * @param mixed $vistaTipoRel
     */
    public function setVistaTipoRel($vistaTipoRel)
    {
        $this->vistaTipoRel = $vistaTipoRel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * @param mixed $estadoAprobado
     */
    public function setEstadoAprobado($estadoAprobado)
    {
        $this->estadoAprobado = $estadoAprobado;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * @param mixed $estadoAnulado
     */
    public function setEstadoAnulado($estadoAnulado)
    {
        $this->estadoAnulado = $estadoAnulado;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * @param mixed $estadoAutorizado
     */
    public function setEstadoAutorizado($estadoAutorizado)
    {
        $this->estadoAutorizado = $estadoAutorizado;
        return $this;
    }



}
