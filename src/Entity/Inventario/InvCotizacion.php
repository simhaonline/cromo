<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvCotizacionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class InvCotizacion
{
    public $infoLog = [
        "primaryKey" => "codigoCotizacionPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoCotizacionPk;

    /**
     * @ORM\Column(name="codigo_cotizacion_tipo_fk", type="string",length=10, nullable=true)
     */
    private $codigoCotizacionTipoFk;

    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */
    private $codigoTerceroFk;

    /**
     * @ORM\Column(name="codigo_asesor_fk", type="integer", nullable=true)
     */
    private $codigoAsesorFk;

    /**
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(name="dias_entrega", type="string", nullable=true)
     */
    private $diasEntrega;

    /**
     * @ORM\Column(name="soporte", type="string", length=255, nullable=true)
     * @Assert\Length(
     *     max = 255,
     *     maxMessage="El campo no puede contener mas de 255 caracteres"
     * )
     */
    private $soporte;

    /**
     * @ORM\Column(name="vr_subtotal", type="float")
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="vr_descuento", type="float")
     */
    private $vrDescuento = 0;

    /**
     * @ORM\Column(name="vr_iva", type="float")
     */
    private $vrIva = 0;

    /**
     * @ORM\Column(name="vr_neto", type="float")
     */
    private $vrNeto = 0;

    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAnulado = false;

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
     * @ORM\Column(name="usuario", type="string", length=25, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="InvCotizacionTipo", inversedBy="cotizacionesCotizacionTipoRel")
     * @ORM\JoinColumn(name="codigo_cotizacion_tipo_fk", referencedColumnName="codigo_cotizacion_tipo_pk")
     * @Assert\NotBlank(
     *     message="Debe de seleccionar una opción"
     * )
     */
    protected $cotizacionTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="InvCotizacionDetalle", mappedBy="cotizacionRel")
     */
    protected $cotizacionesCotizacionDetalleRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvTercero", inversedBy="cotizacionesTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenAsesor", inversedBy="cotizacionesAsesorRel")
     * @ORM\JoinColumn(name="codigo_asesor_fk", referencedColumnName="codigo_asesor_pk")

     */
    protected $asesorRel;

    /**
     * @return array
     */
    public function getInfoLog(): array
    {
        return $this->infoLog;
    }

    /**
     * @param array $infoLog
     */
    public function setInfoLog(array $infoLog): void
    {
        $this->infoLog = $infoLog;
    }

    /**
     * @return mixed
     */
    public function getCodigoCotizacionPk()
    {
        return $this->codigoCotizacionPk;
    }

    /**
     * @param mixed $codigoCotizacionPk
     */
    public function setCodigoCotizacionPk($codigoCotizacionPk): void
    {
        $this->codigoCotizacionPk = $codigoCotizacionPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCotizacionTipoFk()
    {
        return $this->codigoCotizacionTipoFk;
    }

    /**
     * @param mixed $codigoCotizacionTipoFk
     */
    public function setCodigoCotizacionTipoFk($codigoCotizacionTipoFk): void
    {
        $this->codigoCotizacionTipoFk = $codigoCotizacionTipoFk;
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
    public function getCodigoAsesorFk()
    {
        return $this->codigoAsesorFk;
    }

    /**
     * @param mixed $codigoAsesorFk
     */
    public function setCodigoAsesorFk($codigoAsesorFk): void
    {
        $this->codigoAsesorFk = $codigoAsesorFk;
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
    public function getDiasEntrega()
    {
        return $this->diasEntrega;
    }

    /**
     * @param mixed $diasEntrega
     */
    public function setDiasEntrega($diasEntrega): void
    {
        $this->diasEntrega = $diasEntrega;
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
    public function getVrDescuento()
    {
        return $this->vrDescuento;
    }

    /**
     * @param mixed $vrDescuento
     */
    public function setVrDescuento($vrDescuento): void
    {
        $this->vrDescuento = $vrDescuento;
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
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * @param mixed $vrTotal
     */
    public function setVrTotal($vrTotal): void
    {
        $this->vrTotal = $vrTotal;
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
    public function getCotizacionTipoRel()
    {
        return $this->cotizacionTipoRel;
    }

    /**
     * @param mixed $cotizacionTipoRel
     */
    public function setCotizacionTipoRel($cotizacionTipoRel): void
    {
        $this->cotizacionTipoRel = $cotizacionTipoRel;
    }

    /**
     * @return mixed
     */
    public function getCotizacionesCotizacionDetalleRel()
    {
        return $this->cotizacionesCotizacionDetalleRel;
    }

    /**
     * @param mixed $cotizacionesCotizacionDetalleRel
     */
    public function setCotizacionesCotizacionDetalleRel($cotizacionesCotizacionDetalleRel): void
    {
        $this->cotizacionesCotizacionDetalleRel = $cotizacionesCotizacionDetalleRel;
    }

    /**
     * @return mixed
     */
    public function getTerceroRel()
    {
        return $this->terceroRel;
    }

    /**
     * @param mixed $terceroRel
     */
    public function setTerceroRel($terceroRel): void
    {
        $this->terceroRel = $terceroRel;
    }

    /**
     * @return mixed
     */
    public function getAsesorRel()
    {
        return $this->asesorRel;
    }

    /**
     * @param mixed $asesorRel
     */
    public function setAsesorRel($asesorRel): void
    {
        $this->asesorRel = $asesorRel;
    }



}

