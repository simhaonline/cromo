<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvServicioRepository")
 */
class InvServicio
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="codigo_servicio_pk", unique=true)
     */
    private $codigoServicioPk;

    /**
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_servicio_tipo_fk", type="string", length=10)
     */
    private $codigoServicioTipoFk;

    /**
     * @ORM\Column(name="comentario", type="string", length=250)
     */
    private $comentario;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Inventario\InvServicioTipo", inversedBy="servicioServicioTipoRel")
     * @ORM\JoinColumn(name="codigo_servicio_tipo_fk", referencedColumnName="codigo_servicio_tipo_pk")
     */
    protected $servicioTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoServicioPk()
    {
        return $this->codigoServicioPk;
    }

    /**
     * @param mixed $codigoServicioPk
     */
    public function setCodigoServicioPk($codigoServicioPk)
    {
        $this->codigoServicioPk = $codigoServicioPk;
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
    public function getCodigoServicioTipoFk()
    {
        return $this->codigoServicioTipoFk;
    }

    /**
     * @param mixed $codigoServicioTipoFk
     */
    public function setCodigoServicioTipoFk($codigoServicioTipoFk)
    {
        $this->codigoServicioTipoFk = $codigoServicioTipoFk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * @param mixed $comentario
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;
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

    /**
     * @return mixed
     */
    public function getServicioTipoRel()
    {
        return $this->servicioTipoRel;
    }

    /**
     * @param mixed $servicioTipoRel
     */
    public function setServicioTipoRel($servicioTipoRel)
    {
        $this->servicioTipoRel = $servicioTipoRel;
        return $this;
    }



}
