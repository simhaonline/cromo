<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvImportacionRepository")
 */
class InvImportacion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="codigo_importacion_pk",type="integer")
     */
    private $codigoImportacionPk;

    /**
     * @ORM\Column(name="codigo_tercero_fk" , type="integer")
     */
    private $codigoTerceroFk;

    /**
     * @ORM\Column(name="numero" , type="string" , nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="fecha" , type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_llegada" ,type="date" , nullable=true)
     */
    private $fechaLlegada;

    /**
     * @ORM\Column(name="soporte", type="string", length=255, nullable=true)
     * @Assert\Length(
     *     max = 255,
     *     maxMessage="El campo no puede contener mas de 255 caracteres"
     * )
     */
    private $soporte;

    /**
     * @ORM\Column(name="tasa_representativa_mercado", type="float", nullable=true)
     */
    private $tasaRepresentativaMercado = 0;

    /**
     * @ORM\Column(name="codigo_moneda_fk", type="string",length=10, nullable=true)
     */
    private $codigoMonedaFk;

    /**
     * @ORM\Column(name="vr_subtotal_extranjero", type="float", nullable=true)
     */
    private $vrSubtotalExtranjero = 0;

    /**
     * @ORM\Column(name="vr_iva_extranjero", type="float", nullable=true)
     */
    private $vrIvaExtranjero = 0;

    /**
     * @ORM\Column(name="vr_neto_extranjero", type="float", nullable=true)
     */
    private $vrNetoExtranjero = 0;

    /**
     * @ORM\Column(name="vr_total_extranjero", type="float", nullable=true)
     */
    private $vrTotalExtranjero = 0;

    /**
     * @ORM\Column(name="vr_subtotal_local", type="float", nullable=true)
     */
    private $vrSubtotalLocal = 0;

    /**
     * @ORM\Column(name="vr_iva_local", type="float", nullable=true)
     */
    private $vrIvaLocal = 0;

    /**
     * @ORM\Column(name="vr_neto_local", type="float", nullable=true)
     */
    private $vrNetoLocal = 0;

    /**
     * @ORM\Column(name="vr_total_local", type="float", nullable=true)
     */
    private $vrTotalLocal = 0;

    /**
     * @ORM\Column(name="vr_total_costo", type="float", nullable=true)
     */
    private $vrTotalCosto = 0;

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
     * @ORM\Column(name="comentario", type="string", length=500, nullable=true)
     * @Assert\Length(
     *     max=500,
     *     maxMessage="El campo no puede contener mas de 500 caracteres"
     * )
     */
    private $comentario;

    /**
     * @ORM\Column(name="usuario", type="string", length=25, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Inventario\InvImportacionTipo", inversedBy="importacionesImportacionTipoRel")
     * @ORM\JoinColumn(name="codigo_importacion_tipo_fk", referencedColumnName="codigo_importacion_tipo_pk")
     */
    protected $importacionTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvTercero", inversedBy="importacionesTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenMoneda", inversedBy="invImportacionesMonedaRel")
     * @ORM\JoinColumn(name="codigo_moneda_fk", referencedColumnName="codigo_moneda_pk")
     */
    protected $monedaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvImportacionDetalle", mappedBy="importacionRel")
     */
    protected $importacionesDetallesImportacionRel;

    /**
     * @ORM\OneToMany(targetEntity="InvImportacionCosto", mappedBy="importacionRel")
     */
    protected $importacionesCostosImportacionRel;

    /**
     * @return mixed
     */
    public function getCodigoImportacionPk()
    {
        return $this->codigoImportacionPk;
    }

    /**
     * @param mixed $codigoImportacionPk
     */
    public function setCodigoImportacionPk( $codigoImportacionPk ): void
    {
        $this->codigoImportacionPk = $codigoImportacionPk;
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
    public function setCodigoTerceroFk( $codigoTerceroFk ): void
    {
        $this->codigoTerceroFk = $codigoTerceroFk;
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
    public function setNumero( $numero ): void
    {
        $this->numero = $numero;
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
    public function setFecha( $fecha ): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getFechaLlegada()
    {
        return $this->fechaLlegada;
    }

    /**
     * @param mixed $fechaLlegada
     */
    public function setFechaLlegada( $fechaLlegada ): void
    {
        $this->fechaLlegada = $fechaLlegada;
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
    public function setSoporte( $soporte ): void
    {
        $this->soporte = $soporte;
    }

    /**
     * @return mixed
     */
    public function getTasaRepresentativaMercado()
    {
        return $this->tasaRepresentativaMercado;
    }

    /**
     * @param mixed $tasaRepresentativaMercado
     */
    public function setTasaRepresentativaMercado( $tasaRepresentativaMercado ): void
    {
        $this->tasaRepresentativaMercado = $tasaRepresentativaMercado;
    }

    /**
     * @return mixed
     */
    public function getCodigoMonedaFk()
    {
        return $this->codigoMonedaFk;
    }

    /**
     * @param mixed $codigoMonedaFk
     */
    public function setCodigoMonedaFk( $codigoMonedaFk ): void
    {
        $this->codigoMonedaFk = $codigoMonedaFk;
    }

    /**
     * @return mixed
     */
    public function getVrSubtotalExtranjero()
    {
        return $this->vrSubtotalExtranjero;
    }

    /**
     * @param mixed $vrSubtotalExtranjero
     */
    public function setVrSubtotalExtranjero( $vrSubtotalExtranjero ): void
    {
        $this->vrSubtotalExtranjero = $vrSubtotalExtranjero;
    }

    /**
     * @return mixed
     */
    public function getVrIvaExtranjero()
    {
        return $this->vrIvaExtranjero;
    }

    /**
     * @param mixed $vrIvaExtranjero
     */
    public function setVrIvaExtranjero( $vrIvaExtranjero ): void
    {
        $this->vrIvaExtranjero = $vrIvaExtranjero;
    }

    /**
     * @return mixed
     */
    public function getVrNetoExtranjero()
    {
        return $this->vrNetoExtranjero;
    }

    /**
     * @param mixed $vrNetoExtranjero
     */
    public function setVrNetoExtranjero( $vrNetoExtranjero ): void
    {
        $this->vrNetoExtranjero = $vrNetoExtranjero;
    }

    /**
     * @return mixed
     */
    public function getVrTotalExtranjero()
    {
        return $this->vrTotalExtranjero;
    }

    /**
     * @param mixed $vrTotalExtranjero
     */
    public function setVrTotalExtranjero( $vrTotalExtranjero ): void
    {
        $this->vrTotalExtranjero = $vrTotalExtranjero;
    }

    /**
     * @return mixed
     */
    public function getVrSubtotalLocal()
    {
        return $this->vrSubtotalLocal;
    }

    /**
     * @param mixed $vrSubtotalLocal
     */
    public function setVrSubtotalLocal( $vrSubtotalLocal ): void
    {
        $this->vrSubtotalLocal = $vrSubtotalLocal;
    }

    /**
     * @return mixed
     */
    public function getVrIvaLocal()
    {
        return $this->vrIvaLocal;
    }

    /**
     * @param mixed $vrIvaLocal
     */
    public function setVrIvaLocal( $vrIvaLocal ): void
    {
        $this->vrIvaLocal = $vrIvaLocal;
    }

    /**
     * @return mixed
     */
    public function getVrNetoLocal()
    {
        return $this->vrNetoLocal;
    }

    /**
     * @param mixed $vrNetoLocal
     */
    public function setVrNetoLocal( $vrNetoLocal ): void
    {
        $this->vrNetoLocal = $vrNetoLocal;
    }

    /**
     * @return mixed
     */
    public function getVrTotalLocal()
    {
        return $this->vrTotalLocal;
    }

    /**
     * @param mixed $vrTotalLocal
     */
    public function setVrTotalLocal( $vrTotalLocal ): void
    {
        $this->vrTotalLocal = $vrTotalLocal;
    }

    /**
     * @return mixed
     */
    public function getVrTotalCosto()
    {
        return $this->vrTotalCosto;
    }

    /**
     * @param mixed $vrTotalCosto
     */
    public function setVrTotalCosto( $vrTotalCosto ): void
    {
        $this->vrTotalCosto = $vrTotalCosto;
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
    public function setEstadoAutorizado( $estadoAutorizado ): void
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
    public function setEstadoAprobado( $estadoAprobado ): void
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
    public function setEstadoAnulado( $estadoAnulado ): void
    {
        $this->estadoAnulado = $estadoAnulado;
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
    public function setComentario( $comentario ): void
    {
        $this->comentario = $comentario;
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
    public function setUsuario( $usuario ): void
    {
        $this->usuario = $usuario;
    }

    /**
     * @return mixed
     */
    public function getImportacionTipoRel()
    {
        return $this->importacionTipoRel;
    }

    /**
     * @param mixed $importacionTipoRel
     */
    public function setImportacionTipoRel( $importacionTipoRel ): void
    {
        $this->importacionTipoRel = $importacionTipoRel;
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
    public function setTerceroRel( $terceroRel ): void
    {
        $this->terceroRel = $terceroRel;
    }

    /**
     * @return mixed
     */
    public function getMonedaRel()
    {
        return $this->monedaRel;
    }

    /**
     * @param mixed $monedaRel
     */
    public function setMonedaRel( $monedaRel ): void
    {
        $this->monedaRel = $monedaRel;
    }

    /**
     * @return mixed
     */
    public function getImportacionesDetallesImportacionRel()
    {
        return $this->importacionesDetallesImportacionRel;
    }

    /**
     * @param mixed $importacionesDetallesImportacionRel
     */
    public function setImportacionesDetallesImportacionRel( $importacionesDetallesImportacionRel ): void
    {
        $this->importacionesDetallesImportacionRel = $importacionesDetallesImportacionRel;
    }

    /**
     * @return mixed
     */
    public function getImportacionesCostosImportacionRel()
    {
        return $this->importacionesCostosImportacionRel;
    }

    /**
     * @param mixed $importacionesCostosImportacionRel
     */
    public function setImportacionesCostosImportacionRel( $importacionesCostosImportacionRel ): void
    {
        $this->importacionesCostosImportacionRel = $importacionesCostosImportacionRel;
    }



}
