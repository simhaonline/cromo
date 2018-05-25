<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvOrdenCompraRepository")
 */
class InvOrdenCompra
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoOrdenCompraPk;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */
    private $codigoTerceroFk;

    /**
     * @ORM\Column(name="codigo_orden_compra_documento_fk", type="integer", nullable=true)
     */
    private $codigoOrdenCompraDocumentoFk;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaEntrega", type="date", nullable=true)
     */
    private $fechaEntrega;

    /**
     * @ORM\Column(name="soporte", type="string", length=500, nullable=true)
     */
    private $soporte;

    /**
     * @ORM\Column(name="vr_subtotal", type="float")
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="vr_iva", type="float")
     */
    private $vrIva = 0;

    /**
     * @ORM\Column(name="vr_neto", type="float")
     */
    private $vrNeto = 0;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */
    private $estadoAutorizado = 0;

    /**
     * @ORM\Column(name="estado_impreso", type="boolean")
     */
    private $estadoImpreso = 0;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean", nullable=true)
     */
    private $estadoAprobado = 0;

    /**
     * @ORM\Column(name="estado_rechazado", type="boolean", nullable=true)
     */
    private $estadoRechazado = 0;

    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     * @Assert\Length(
     *     max=300,
     *     maxMessage="El comentario no puede contener mas de 500 caracteres"
     * )
     */
    private $comentarios;

    /**
     * @return mixed
     */
    public function getCodigoOrdenCompraPk()
    {
        return $this->codigoOrdenCompraPk;
    }

    /**
     * @param mixed $codigoOrdenCompraPk
     */
    public function setCodigoOrdenCompraPk($codigoOrdenCompraPk): void
    {
        $this->codigoOrdenCompraPk = $codigoOrdenCompraPk;
    }

    /**
     * @return \DateTime
     */
    public function getFecha(): \DateTime
    {
        return $this->fecha;
    }

    /**
     * @param \DateTime $fecha
     */
    public function setFecha(\DateTime $fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getCodigoTerceroFk()
    {
        return $this->codigoTerceroFk;
    }

    /**
     * @param mixed $codigoTerceroFk
     */
    public function setCodigoTerceroFk($codigoTerceroFk): void
    {
        $this->codigoTerceroFk = $codigoTerceroFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoOrdenCompraDocumentoFk()
    {
        return $this->codigoOrdenCompraDocumentoFk;
    }

    /**
     * @param mixed $codigoOrdenCompraDocumentoFk
     */
    public function setCodigoOrdenCompraDocumentoFk($codigoOrdenCompraDocumentoFk): void
    {
        $this->codigoOrdenCompraDocumentoFk = $codigoOrdenCompraDocumentoFk;
    }

    /**
     * @return \DateTime
     */
    public function getFechaEntrega(): \DateTime
    {
        return $this->fechaEntrega;
    }

    /**
     * @param \DateTime $fechaEntrega
     */
    public function setFechaEntrega(\DateTime $fechaEntrega): void
    {
        $this->fechaEntrega = $fechaEntrega;
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
    public function getVrSubtotal()
    {
        return $this->vrSubtotal;
    }

    /**
     * @param mixed $vrSubtotal
     */
    public function setVrSubtotal($vrSubtotal): void
    {
        $this->vrSubtotal = $vrSubtotal;
    }

    /**
     * @return mixed
     */
    public function getVrIva()
    {
        return $this->vrIva;
    }

    /**
     * @param mixed $vrIva
     */
    public function setVrIva($vrIva): void
    {
        $this->vrIva = $vrIva;
    }

    /**
     * @return mixed
     */
    public function getVrNeto()
    {
        return $this->vrNeto;
    }

    /**
     * @param mixed $vrNeto
     */
    public function setVrNeto($vrNeto): void
    {
        $this->vrNeto = $vrNeto;
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
    public function getEstadoRechazado()
    {
        return $this->estadoRechazado;
    }

    /**
     * @param mixed $estadoRechazado
     */
    public function setEstadoRechazado($estadoRechazado): void
    {
        $this->estadoRechazado = $estadoRechazado;
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



}

