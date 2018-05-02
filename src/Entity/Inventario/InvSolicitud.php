<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvSolicitudRepository")
 */
class InvSolicitud
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoSolicitudPk;

    /**
     * @ORM\Column(name="codigo_solicitud_tipo_fk", type="integer", nullable=true)
     */
    private $codigoSolicitudTipoFk;

    /**
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(name="soporte", type="string", length=255, nullable=true)
     * @Assert\Length(
     *     max = 255,
     *     maxMessage="El campo no puede contener mas de 255 caracteres"
     * )
     */
    private $soporte;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean", nullable=true)
     */
    private $estadoAutorizado = 0;

    /**
     * @ORM\Column(name="estado_impreso", type="boolean", nullable=true)
     */
    private $estadoImpreso = 0;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean", nullable=true)
     */
    private $estadoAnulado = 0;

    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     * @Assert\Length(
     *     max=500,
     *     maxMessage="El campo no puede contener mas de 500 caracteres"
     * )
     */
    private $comentarios;

    /**
     * @ORM\Column(name="usuario", type="string", length=80, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\OneToMany(targetEntity="InvSolicitudDetalle", mappedBy="solicitudRel")
     */
    protected $solicitudSolicitudDetallesRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvSolicitudTipo", inversedBy="solicitudTipoSolicitudRel")
     * @ORM\JoinColumn(name="codigo_solicitud_tipo_fk", referencedColumnName="codigo_solicitud_tipo_pk")
     * @Assert\NotBlank(
     *     message="Debe de seleccionar una opción"
     * )
     */
    protected $solicitudTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoSolicitudPk()
    {
        return $this->codigoSolicitudPk;
    }

    /**
     * @param mixed $codigoSolicitudPk
     */
    public function setCodigoSolicitudPk($codigoSolicitudPk): void
    {
        $this->codigoSolicitudPk = $codigoSolicitudPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSolicitudTipoFk()
    {
        return $this->codigoSolicitudTipoFk;
    }

    /**
     * @param mixed $codigoSolicitudTipoFk
     */
    public function setCodigoSolicitudTipoFk($codigoSolicitudTipoFk): void
    {
        $this->codigoSolicitudTipoFk = $codigoSolicitudTipoFk;
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
    public function getSoporte()
    {
        return $this->soporte;
    }

    /**
     * @param mixed $soporte
     */
    public function setSoporte($soporte): void
    {
        $this->soporte = $soporte;
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
    public function getEstadoImpreso()
    {
        return $this->estadoImpreso;
    }

    /**
     * @param mixed $estadoImpreso
     */
    public function setEstadoImpreso($estadoImpreso): void
    {
        $this->estadoImpreso = $estadoImpreso;
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
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero): void
    {
        $this->numero = $numero;
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
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
    }

    /**
     * @return mixed
     */
    public function getSolicitudSolicitudDetallesRel()
    {
        return $this->solicitudSolicitudDetallesRel;
    }

    /**
     * @param mixed $solicitudSolicitudDetallesRel
     */
    public function setSolicitudSolicitudDetallesRel($solicitudSolicitudDetallesRel): void
    {
        $this->solicitudSolicitudDetallesRel = $solicitudSolicitudDetallesRel;
    }

    /**
     * @return mixed
     */
    public function getSolicitudTipoRel()
    {
        return $this->solicitudTipoRel;
    }

    /**
     * @param mixed $solicitudTipoRel
     */
    public function setSolicitudTipoRel($solicitudTipoRel): void
    {
        $this->solicitudTipoRel = $solicitudTipoRel;
    }

}

