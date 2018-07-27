<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteFacturaRepository")
 */
class TteFactura
{
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
     * @ORM\Column(name="numero", type="float")
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
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
     * @ORM\Column(name="vr_flete", type="float")
     */
    private $vrFlete = 0;

    /**
     * @ORM\Column(name="vr_manejo", type="float")
     */
    private $vrManejo = 0;

    /**
     * @ORM\Column(name="vr_otros", type="float")
     */
    private $vrOtros = 0;

    /**
     * @ORM\Column(name="vr_subtotal", type="float")
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="guias", type="integer", nullable=true)
     */
    private $guias;

    /**
     * @ORM\Column(name="plazo_pago", type="float")
     */
    private $plazoPago = 0;

    /**
     * @ORM\Column(name="estado_autorizado",options={"default" : false}, type="boolean")
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
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="TteFacturaTipo", inversedBy="facturasFacturaTipoRel")
     * @ORM\JoinColumn(name="codigo_factura_tipo_fk", referencedColumnName="codigo_factura_tipo_pk")
     */
    private $facturaTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCliente", inversedBy="facturasClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    private $clienteRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteFacturaDetalle", mappedBy="facturaRel")
     */
    protected $facturasDetallesDespachoRel;

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
     * @return mixed
     */
    public function getCodigoFacturaPk()
    {
        return $this->codigoFacturaPk;
    }

    /**
     * @param mixed $codigoFacturaPk
     */
    public function setCodigoFacturaPk($codigoFacturaPk): void
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
    public function setCodigoFacturaTipoFk($codigoFacturaTipoFk): void
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
    public function setCodigoFacturaClaseFk($codigoFacturaClaseFk): void
    {
        $this->codigoFacturaClaseFk = $codigoFacturaClaseFk;
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
    public function getFechaVence()
    {
        return $this->fechaVence;
    }

    /**
     * @param mixed $fechaVence
     */
    public function setFechaVence($fechaVence): void
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
    public function setCodigoClienteFk($codigoClienteFk): void
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
    public function setSoporte($soporte): void
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
    public function setVrFlete($vrFlete): void
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
    public function setVrManejo($vrManejo): void
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
    public function setVrOtros($vrOtros): void
    {
        $this->vrOtros = $vrOtros;
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
    public function getGuias()
    {
        return $this->guias;
    }

    /**
     * @param mixed $guias
     */
    public function setGuias($guias): void
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
    public function setPlazoPago($plazoPago): void
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
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * @param mixed $comentario
     */
    public function setComentario($comentario): void
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
    public function setFacturaTipoRel($facturaTipoRel): void
    {
        $this->facturaTipoRel = $facturaTipoRel;
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
    public function getFacturasDetallesDespachoRel()
    {
        return $this->facturasDetallesDespachoRel;
    }

    /**
     * @param mixed $facturasDetallesDespachoRel
     */
    public function setFacturasDetallesDespachoRel($facturasDetallesDespachoRel): void
    {
        $this->facturasDetallesDespachoRel = $facturasDetallesDespachoRel;
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
    public function setGuiasFacturaRel($guiasFacturaRel): void
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
    public function setFacturasPlanillasFacturaRel($facturasPlanillasFacturaRel): void
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
    public function setFacturasOtrosFacturaRel($facturasOtrosFacturaRel): void
    {
        $this->facturasOtrosFacturaRel = $facturasOtrosFacturaRel;
    }



}

