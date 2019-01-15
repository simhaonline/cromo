<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteFacturaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteFactura
{
    public $infoLog = [
        "primaryKey" => "codigoFacturaPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoFacturaPk;

    /**
     * @ORM\Column(name="codigo_factura_tipo_fk", type="string", length=20, nullable=true)
     */
    private $codigoFacturaTipoFk;

    /**
     * @ORM\Column(name="codigo_factura_clase_fk", type="string", length=2, nullable=true)
     */
    private $codigoFacturaClaseFk;

    /**
     * @ORM\Column(name="codigo_factura_concepto_fk", type="string", length=20, nullable=true)
     */
    private $codigoFacturaConceptoFk;

    /**
     * @ORM\Column(name="codigo_factura_referencia_fk", type="integer", nullable=true, options={"default" : null})
     */
    private $codigoFacturaReferenciaFk;

    /**
     * @ORM\Column(name="numero", type="float", nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_vence", type="date", nullable=true)
     */
    private $fechaVence;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="soporte", type="string", length=50, nullable=true)
     */
    private $soporte;

    /**
     * @ORM\Column(name="vr_flete", type="float", options={"default" : 0})
     */
    private $vrFlete = 0;

    /**
     * @ORM\Column(name="vr_manejo", type="float", options={"default" : 0})
     */
    private $vrManejo = 0;

    /**
     * @ORM\Column(name="vr_otros", type="float", options={"default" : 0})
     */
    private $vrOtros = 0;

    /**
     * @ORM\Column(name="vr_iva", type="float", options={"default" : 0})
     */
    private $vrIva = 0;

    /**
     * @ORM\Column(name="vr_subtotal", type="float", options={"default" : 0})
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="vr_retencion_fuente", type="float", options={"default" : 0})
     */
    private $vrRetencionFuente = 0;

    /**
     * @ORM\Column(name="vr_total", type="float", options={"default" : 0})
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="vr_total_neto", type="float", options={"default" : 0})
     */
    private $vrTotalNeto = 0;

    /**
     * @ORM\Column(name="vr_total_operado", type="float", options={"default" : 0})
     */
    private $vrTotalOperado = 0;

    /**
     * @ORM\Column(name="guias", type="integer", nullable=true, options={"default" : 0})
     */
    private $guias;

    /**
     * @ORM\Column(name="plazo_pago", type="float", options={"default" : 0})
     */
    private $plazoPago = 0;

    /**
     * @ORM\Column(name="estado_autorizado", options={"default" : false}, type="boolean")
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean", options={"default" : false}, nullable=true)
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoContabilizado = false;

    /**
     * @ORM\Column(name="codigo_operacion_fk", type="string", length=20, nullable=true)
     */
    private $codigoOperacionFk;

    /**
     * @ORM\Column(name="operacion_comercial", type="smallint", nullable=true, options={"default" : 0})
     */
    private $operacionComercial = 0;

    /**
     * @ORM\Column(name="usuario", type="string", length=25, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="TteFacturaTipo", inversedBy="facturasFacturaTipoRel")
     * @ORM\JoinColumn(name="codigo_factura_tipo_fk", referencedColumnName="codigo_factura_tipo_pk")
     */
    private $facturaTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteFacturaConcepto", inversedBy="facturasFacturaConceptoRel")
     * @ORM\JoinColumn(name="codigo_factura_concepto_fk", referencedColumnName="codigo_factura_concepto_pk")
     */
    private $facturaConceptoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCliente", inversedBy="facturasClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    private $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteOperacion", inversedBy="facturasOperacionRel")
     * @ORM\JoinColumn(name="codigo_operacion_fk", referencedColumnName="codigo_operacion_pk")
     */
    private $operacionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteFacturaDetalle", mappedBy="facturaRel")
     */
    protected $facturasDetallesFacturaRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="facturaRel")
     */
    protected $guiasFacturaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteFacturaPlanilla", mappedBy="facturaRel")
     */
    protected $facturasPlanillasFacturaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteFacturaOtro", mappedBy="facturaRel")
     */
    protected $facturasOtrosFacturaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteFacturaDetalleConcepto", mappedBy="facturaRel")
     */
    protected $facturasDetallesConcetosFacturaRel;

    /**
     * @return mixed
     */
    public function getCodigoFacturaPk()
    {
        return $this->codigoFacturaPk;
    }

    /**
     * @param mixed $codigoFacturaPk
     */
    public function setCodigoFacturaPk( $codigoFacturaPk ): void
    {
        $this->codigoFacturaPk = $codigoFacturaPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoFacturaTipoFk()
    {
        return $this->codigoFacturaTipoFk;
    }

    /**
     * @param mixed $codigoFacturaTipoFk
     */
    public function setCodigoFacturaTipoFk( $codigoFacturaTipoFk ): void
    {
        $this->codigoFacturaTipoFk = $codigoFacturaTipoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoFacturaClaseFk()
    {
        return $this->codigoFacturaClaseFk;
    }

    /**
     * @param mixed $codigoFacturaClaseFk
     */
    public function setCodigoFacturaClaseFk( $codigoFacturaClaseFk ): void
    {
        $this->codigoFacturaClaseFk = $codigoFacturaClaseFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoFacturaConceptoFk()
    {
        return $this->codigoFacturaConceptoFk;
    }

    /**
     * @param mixed $codigoFacturaConceptoFk
     */
    public function setCodigoFacturaConceptoFk( $codigoFacturaConceptoFk ): void
    {
        $this->codigoFacturaConceptoFk = $codigoFacturaConceptoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoFacturaReferenciaFk()
    {
        return $this->codigoFacturaReferenciaFk;
    }

    /**
     * @param mixed $codigoFacturaReferenciaFk
     */
    public function setCodigoFacturaReferenciaFk( $codigoFacturaReferenciaFk ): void
    {
        $this->codigoFacturaReferenciaFk = $codigoFacturaReferenciaFk;
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
    public function getFechaVence()
    {
        return $this->fechaVence;
    }

    /**
     * @param mixed $fechaVence
     */
    public function setFechaVence( $fechaVence ): void
    {
        $this->fechaVence = $fechaVence;
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
    public function setCodigoClienteFk( $codigoClienteFk ): void
    {
        $this->codigoClienteFk = $codigoClienteFk;
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
    public function getVrFlete()
    {
        return $this->vrFlete;
    }

    /**
     * @param mixed $vrFlete
     */
    public function setVrFlete( $vrFlete ): void
    {
        $this->vrFlete = $vrFlete;
    }

    /**
     * @return mixed
     */
    public function getVrManejo()
    {
        return $this->vrManejo;
    }

    /**
     * @param mixed $vrManejo
     */
    public function setVrManejo( $vrManejo ): void
    {
        $this->vrManejo = $vrManejo;
    }

    /**
     * @return mixed
     */
    public function getVrOtros()
    {
        return $this->vrOtros;
    }

    /**
     * @param mixed $vrOtros
     */
    public function setVrOtros( $vrOtros ): void
    {
        $this->vrOtros = $vrOtros;
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
    public function setVrIva( $vrIva ): void
    {
        $this->vrIva = $vrIva;
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
    public function setVrSubtotal( $vrSubtotal ): void
    {
        $this->vrSubtotal = $vrSubtotal;
    }

    /**
     * @return mixed
     */
    public function getVrRetencionFuente()
    {
        return $this->vrRetencionFuente;
    }

    /**
     * @param mixed $vrRetencionFuente
     */
    public function setVrRetencionFuente( $vrRetencionFuente ): void
    {
        $this->vrRetencionFuente = $vrRetencionFuente;
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
    public function setVrTotal( $vrTotal ): void
    {
        $this->vrTotal = $vrTotal;
    }

    /**
     * @return mixed
     */
    public function getVrTotalNeto()
    {
        return $this->vrTotalNeto;
    }

    /**
     * @param mixed $vrTotalNeto
     */
    public function setVrTotalNeto( $vrTotalNeto ): void
    {
        $this->vrTotalNeto = $vrTotalNeto;
    }

    /**
     * @return mixed
     */
    public function getVrTotalOperado()
    {
        return $this->vrTotalOperado;
    }

    /**
     * @param mixed $vrTotalOperado
     */
    public function setVrTotalOperado( $vrTotalOperado ): void
    {
        $this->vrTotalOperado = $vrTotalOperado;
    }

    /**
     * @return mixed
     */
    public function getGuias()
    {
        return $this->guias;
    }

    /**
     * @param mixed $guias
     */
    public function setGuias( $guias ): void
    {
        $this->guias = $guias;
    }

    /**
     * @return mixed
     */
    public function getPlazoPago()
    {
        return $this->plazoPago;
    }

    /**
     * @param mixed $plazoPago
     */
    public function setPlazoPago( $plazoPago ): void
    {
        $this->plazoPago = $plazoPago;
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
    public function getEstadoContabilizado()
    {
        return $this->estadoContabilizado;
    }

    /**
     * @param mixed $estadoContabilizado
     */
    public function setEstadoContabilizado( $estadoContabilizado ): void
    {
        $this->estadoContabilizado = $estadoContabilizado;
    }

    /**
     * @return mixed
     */
    public function getCodigoOperacionFk()
    {
        return $this->codigoOperacionFk;
    }

    /**
     * @param mixed $codigoOperacionFk
     */
    public function setCodigoOperacionFk( $codigoOperacionFk ): void
    {
        $this->codigoOperacionFk = $codigoOperacionFk;
    }

    /**
     * @return mixed
     */
    public function getOperacionComercial()
    {
        return $this->operacionComercial;
    }

    /**
     * @param mixed $operacionComercial
     */
    public function setOperacionComercial( $operacionComercial ): void
    {
        $this->operacionComercial = $operacionComercial;
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
    public function getFacturaTipoRel()
    {
        return $this->facturaTipoRel;
    }

    /**
     * @param mixed $facturaTipoRel
     */
    public function setFacturaTipoRel( $facturaTipoRel ): void
    {
        $this->facturaTipoRel = $facturaTipoRel;
    }

    /**
     * @return mixed
     */
    public function getFacturaConceptoRel()
    {
        return $this->facturaConceptoRel;
    }

    /**
     * @param mixed $facturaConceptoRel
     */
    public function setFacturaConceptoRel( $facturaConceptoRel ): void
    {
        $this->facturaConceptoRel = $facturaConceptoRel;
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
    public function setClienteRel( $clienteRel ): void
    {
        $this->clienteRel = $clienteRel;
    }

    /**
     * @return mixed
     */
    public function getOperacionRel()
    {
        return $this->operacionRel;
    }

    /**
     * @param mixed $operacionRel
     */
    public function setOperacionRel( $operacionRel ): void
    {
        $this->operacionRel = $operacionRel;
    }

    /**
     * @return mixed
     */
    public function getFacturasDetallesFacturaRel()
    {
        return $this->facturasDetallesFacturaRel;
    }

    /**
     * @param mixed $facturasDetallesFacturaRel
     */
    public function setFacturasDetallesFacturaRel( $facturasDetallesFacturaRel ): void
    {
        $this->facturasDetallesFacturaRel = $facturasDetallesFacturaRel;
    }

    /**
     * @return mixed
     */
    public function getGuiasFacturaRel()
    {
        return $this->guiasFacturaRel;
    }

    /**
     * @param mixed $guiasFacturaRel
     */
    public function setGuiasFacturaRel( $guiasFacturaRel ): void
    {
        $this->guiasFacturaRel = $guiasFacturaRel;
    }

    /**
     * @return mixed
     */
    public function getFacturasPlanillasFacturaRel()
    {
        return $this->facturasPlanillasFacturaRel;
    }

    /**
     * @param mixed $facturasPlanillasFacturaRel
     */
    public function setFacturasPlanillasFacturaRel( $facturasPlanillasFacturaRel ): void
    {
        $this->facturasPlanillasFacturaRel = $facturasPlanillasFacturaRel;
    }

    /**
     * @return mixed
     */
    public function getFacturasOtrosFacturaRel()
    {
        return $this->facturasOtrosFacturaRel;
    }

    /**
     * @param mixed $facturasOtrosFacturaRel
     */
    public function setFacturasOtrosFacturaRel( $facturasOtrosFacturaRel ): void
    {
        $this->facturasOtrosFacturaRel = $facturasOtrosFacturaRel;
    }

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
    public function getFacturasDetallesConcetosFacturaRel()
    {
        return $this->facturasDetallesConcetosFacturaRel;
    }

    /**
     * @param mixed $facturasDetallesConcetosFacturaRel
     */
    public function setFacturasDetallesConcetosFacturaRel($facturasDetallesConcetosFacturaRel): void
    {
        $this->facturasDetallesConcetosFacturaRel = $facturasDetallesConcetosFacturaRel;
    }



}

