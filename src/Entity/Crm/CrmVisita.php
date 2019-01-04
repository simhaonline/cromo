<?php

namespace App\Entity\Crm;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Crm\CrmVisitaRepository")
 */
class CrmVisita
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="codigo_visita_pk", unique=true)
     */
    private $codigoVisitaPk;

    /**
     * @ORM\Column(name="codigo_visita_tipo_fk", type="string", length=20, nullable=false)
     */
    private $codigoVisitaTipoFk;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Crm\CrmVisitaTipo", inversedBy="visitaVisitaTipoRel")
     * @ORM\JoinColumn(name="codigo_visita_tipo_fk", referencedColumnName="codigo_visita_tipo_pk")
     */
    protected $visitaTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoVisitaPk()
    {
        return $this->codigoVisitaPk;
    }

    /**
     * @param mixed $codigoVisitaPk
     */
    public function setCodigoVisitaPk($codigoVisitaPk)
    {
        $this->codigoVisitaPk = $codigoVisitaPk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoVisitaTipoFk()
    {
        return $this->codigoVisitaTipoFk;
    }

    /**
     * @param mixed $codigoVisitaTipoFk
     */
    public function setCodigoVisitaTipoFk($codigoVisitaTipoFk)
    {
        $this->codigoVisitaTipoFk = $codigoVisitaTipoFk;
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
    public function getVisitaTipoRel()
    {
        return $this->visitaTipoRel;
    }

    /**
     * @param mixed $visitaTipoRel
     */
    public function setVisitaTipoRel($visitaTipoRel)
    {
        $this->visitaTipoRel = $visitaTipoRel;
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
