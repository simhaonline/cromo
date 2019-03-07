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
     * @ORM\Column(name="codigo_visita_tipo_fk", type="string", length=20, nullable=true)
     */
    private $codigoVisitaTipoFk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_contacto_fk", type="integer", nullable=true)
     */
    private $codigoContactoFk;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Crm\CrmCliente", inversedBy="visitaClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Crm\CrmContacto", inversedBy="visitaContactoRel")
     * @ORM\JoinColumn(name="codigo_contacto_fk", referencedColumnName="codigo_contacto_pk")
     */
    protected $contactoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Crm\CrmVisitaReporte", mappedBy="visitaRel", cascade={"remove","persist"})
     */
    protected $visitasReporteRel;

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
    public function setCodigoVisitaPk($codigoVisitaPk): void
    {
        $this->codigoVisitaPk = $codigoVisitaPk;
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
    public function setCodigoVisitaTipoFk($codigoVisitaTipoFk): void
    {
        $this->codigoVisitaTipoFk = $codigoVisitaTipoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * @param mixed $codigoClienteFk
     */
    public function setCodigoClienteFk($codigoClienteFk): void
    {
        $this->codigoClienteFk = $codigoClienteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoContactoFk()
    {
        return $this->codigoContactoFk;
    }

    /**
     * @param mixed $codigoContactoFk
     */
    public function setCodigoContactoFk($codigoContactoFk): void
    {
        $this->codigoContactoFk = $codigoContactoFk;
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
    public function setFecha($fecha): void
    {
        $this->fecha = $fecha;
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
    public function setComentarios($comentarios): void
    {
        $this->comentarios = $comentarios;
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
    public function setEstadoAprobado($estadoAprobado): void
    {
        $this->estadoAprobado = $estadoAprobado;
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
    public function setEstadoAnulado($estadoAnulado): void
    {
        $this->estadoAnulado = $estadoAnulado;
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
    public function setEstadoAutorizado($estadoAutorizado): void
    {
        $this->estadoAutorizado = $estadoAutorizado;
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
    public function setVisitaTipoRel($visitaTipoRel): void
    {
        $this->visitaTipoRel = $visitaTipoRel;
    }

    /**
     * @return mixed
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * @param mixed $clienteRel
     */
    public function setClienteRel($clienteRel): void
    {
        $this->clienteRel = $clienteRel;
    }

    /**
     * @return mixed
     */
    public function getContactoRel()
    {
        return $this->contactoRel;
    }

    /**
     * @param mixed $contactoRel
     */
    public function setContactoRel($contactoRel): void
    {
        $this->contactoRel = $contactoRel;
    }

    /**
     * @return mixed
     */
    public function getVisitasReporteRel()
    {
        return $this->visitasReporteRel;
    }

    /**
     * @param mixed $visitasReporteRel
     */
    public function setVisitasReporteRel($visitasReporteRel): void
    {
        $this->visitasReporteRel = $visitasReporteRel;
    }



}
