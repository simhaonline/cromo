<?php

namespace App\Entity\Inventario;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvMovimientoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class InvMovimiento
{
    public $infoLog = [
        "primaryKey" => "codigoMovimientoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_movimiento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoMovimientoPk;

    /**
     * @ORM\Column(name="codigo_documento_fk", type="string",length=10, nullable=true)
     */
    private $codigoDocumentoFk;

    /**
     * @ORM\Column(name="codigo_factura_tipo_fk", type="string",length=10, nullable=true)
     */
    private $codigoFacturaTipoFk;

    /**
     * @ORM\Column(name="codigo_asesor_fk", type="integer", nullable=true)
     */
    private $codigoAsesorFk;

    /**
     * @ORM\Column(name="codigo_documento_tipo_fk", type="string",length=10, nullable=true)
     */
    private $codigoDocumentoTipoFk;

    /**
     * @ORM\Column(name="codigo_sucursal_fk", type="string",length=10, nullable=true)
     */
    private $codigoSucursalFk;

    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="direccion", type="string",length=100, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_documento", type="date", nullable=true)
     */
    private $fechaDocumento;

    /**
     * @ORM\Column(name="fecha_vence", type="date", nullable=true)
     */
    private $fechaVence;

    /**
     * @ORM\Column(name="plazo_pago", type="integer", nullable=true)
     */
    private $plazoPago = 0;

    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer")
     */
    private $codigoTerceroFk;

    /**
     * @ORM\Column(name="codigo_contacto_fk", type="integer", nullable=true, options={"default":null})
     */
    private $codigoContactoFk;

    /**
     * @ORM\Column(name="soporte", type="string", length=300, nullable=true)
     */
    private $soporte;

    /**
     * @ORM\Column(name="guia", type="string", length=50, nullable=true)
     */
    private $guia;

    /**
     * @ORM\Column(name="ciudad_factura", type="string", length=50, nullable=true)
     */
    private $ciudadFactura;

    /**
     * @ORM\Column(name="vr_iva", type="float", nullable=true)
     */
    private $vrIva;

    /**
     * @ORM\Column(name="vr_subtotal", type="float")
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="vr_descuento", type="float")
     */
    private $vrDescuento = 0;

    /**
     * @ORM\Column(name="vr_neto", type="float")
     */
    private $vrNeto = 0;

    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="vr_retencion_fuente", type="float")
     */
    private $vrRetencionFuente = 0;

    /**
     * @ORM\Column(name="vr_retencion_iva", type="float")
     */
    private $vrRetencionIva = 0;

    /**
     * @ORM\Column(name="vr_autoretencion", type="float")
     */
    private $vrAutoretencion = 0;

    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="usuario", type="string", length=25, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean", options={"default":false})
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean", options={"default":false})
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean", options={"default":false})
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean", options={"default":false})
     */
    private $estadoContabilizado = false;

    /**
     * @ORM\Column(name="operacion_inventario", type="smallint", nullable=true, options={"default" : 0})
     */
    private $operacionInventario = 0;

    /**
     * @ORM\Column(name="operacion_comercial", type="smallint", nullable=true, options={"default" : 0})
     */
    private $operacionComercial = 0;

    /**
     * @internal Para saber si el documento genera costo promedio
     * @ORM\Column(name="genera_costo_promedio", type="boolean", options={"default":false})
     */
    private $generaCostoPromedio = false;

    /**
     * @ORM\ManyToOne(targetEntity="InvDocumento", inversedBy="movimientosDocumentoRel")
     * @ORM\JoinColumn(name="codigo_documento_fk", referencedColumnName="codigo_documento_pk")
     */
    protected $documentoRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvDocumentoTipo", inversedBy="movimientosDocumentoTipoRel")
     * @ORM\JoinColumn(name="codigo_documento_tipo_fk", referencedColumnName="codigo_documento_tipo_pk")
     */
    protected $documentoTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvTercero", inversedBy="movimientosTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvContacto", inversedBy="movimientosContactoRel")
     * @ORM\JoinColumn(name="codigo_contacto_fk", referencedColumnName="codigo_contacto_pk")
     */
    protected $contactoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenAsesor", inversedBy="movimientosAsesorRel")
     * @ORM\JoinColumn(name="codigo_asesor_fk", referencedColumnName="codigo_asesor_pk")

     */
    protected $asesorRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvFacturaTipo", inversedBy="movimientosFacturaTipoRel")
     * @ORM\JoinColumn(name="codigo_factura_tipo_fk", referencedColumnName="codigo_factura_tipo_pk")

     */
    protected $facturaTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="InvMovimientoDetalle", mappedBy="movimientoRel")
     */
    protected $movimientosDetallesMovimientoRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvSucursal", inversedBy="movimientosSucursalRel")
     * @ORM\JoinColumn(name="codigo_sucursal_fk", referencedColumnName="codigo_sucursal_pk")
     */
    protected $sucursalRel;

    /**
     * @return mixed
     */
    public function getCodigoMovimientoPk()
    {
        return $this->codigoMovimientoPk;
    }

    /**
     * @param mixed $codigoMovimientoPk
     */
    public function setCodigoMovimientoPk($codigoMovimientoPk): void
    {
        $this->codigoMovimientoPk = $codigoMovimientoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoDocumentoFk()
    {
        return $this->codigoDocumentoFk;
    }

    /**
     * @param mixed $codigoDocumentoFk
     */
    public function setCodigoDocumentoFk($codigoDocumentoFk): void
    {
        $this->codigoDocumentoFk = $codigoDocumentoFk;
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
    public function getCodigoDocumentoTipoFk()
    {
        return $this->codigoDocumentoTipoFk;
    }

    /**
     * @param mixed $codigoDocumentoTipoFk
     */
    public function setCodigoDocumentoTipoFk($codigoDocumentoTipoFk): void
    {
        $this->codigoDocumentoTipoFk = $codigoDocumentoTipoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSucursalFk()
    {
        return $this->codigoSucursalFk;
    }

    /**
     * @param mixed $codigoSucursalFk
     */
    public function setCodigoSucursalFk($codigoSucursalFk): void
    {
        $this->codigoSucursalFk = $codigoSucursalFk;
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
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param mixed $direccion
     */
    public function setDireccion($direccion): void
    {
        $this->direccion = $direccion;
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
    public function getGuia()
    {
        return $this->guia;
    }

    /**
     * @param mixed $guia
     */
    public function setGuia($guia): void
    {
        $this->guia = $guia;
    }

    /**
     * @return mixed
     */
    public function getCiudadFactura()
    {
        return $this->ciudadFactura;
    }

    /**
     * @param mixed $ciudadFactura
     */
    public function setCiudadFactura($ciudadFactura): void
    {
        $this->ciudadFactura = $ciudadFactura;
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
    public function getVrRetencionFuente()
    {
        return $this->vrRetencionFuente;
    }

    /**
     * @param mixed $vrRetencionFuente
     */
    public function setVrRetencionFuente($vrRetencionFuente): void
    {
        $this->vrRetencionFuente = $vrRetencionFuente;
    }

    /**
     * @return mixed
     */
    public function getVrRetencionIva()
    {
        return $this->vrRetencionIva;
    }

    /**
     * @param mixed $vrRetencionIva
     */
    public function setVrRetencionIva($vrRetencionIva): void
    {
        $this->vrRetencionIva = $vrRetencionIva;
    }

    /**
     * @return mixed
     */
    public function getVrAutoretencion()
    {
        return $this->vrAutoretencion;
    }

    /**
     * @param mixed $vrAutoretencion
     */
    public function setVrAutoretencion($vrAutoretencion): void
    {
        $this->vrAutoretencion = $vrAutoretencion;
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
    public function getEstadoContabilizado()
    {
        return $this->estadoContabilizado;
    }

    /**
     * @param mixed $estadoContabilizado
     */
    public function setEstadoContabilizado($estadoContabilizado): void
    {
        $this->estadoContabilizado = $estadoContabilizado;
    }

    /**
     * @return mixed
     */
    public function getOperacionInventario()
    {
        return $this->operacionInventario;
    }

    /**
     * @param mixed $operacionInventario
     */
    public function setOperacionInventario($operacionInventario): void
    {
        $this->operacionInventario = $operacionInventario;
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
    public function setOperacionComercial($operacionComercial): void
    {
        $this->operacionComercial = $operacionComercial;
    }

    /**
     * @return mixed
     */
    public function getGeneraCostoPromedio()
    {
        return $this->generaCostoPromedio;
    }

    /**
     * @param mixed $generaCostoPromedio
     */
    public function setGeneraCostoPromedio($generaCostoPromedio): void
    {
        $this->generaCostoPromedio = $generaCostoPromedio;
    }

    /**
     * @return mixed
     */
    public function getDocumentoRel()
    {
        return $this->documentoRel;
    }

    /**
     * @param mixed $documentoRel
     */
    public function setDocumentoRel($documentoRel): void
    {
        $this->documentoRel = $documentoRel;
    }

    /**
     * @return mixed
     */
    public function getDocumentoTipoRel()
    {
        return $this->documentoTipoRel;
    }

    /**
     * @param mixed $documentoTipoRel
     */
    public function setDocumentoTipoRel($documentoTipoRel): void
    {
        $this->documentoTipoRel = $documentoTipoRel;
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
    public function getMovimientosDetallesMovimientoRel()
    {
        return $this->movimientosDetallesMovimientoRel;
    }

    /**
     * @param mixed $movimientosDetallesMovimientoRel
     */
    public function setMovimientosDetallesMovimientoRel($movimientosDetallesMovimientoRel): void
    {
        $this->movimientosDetallesMovimientoRel = $movimientosDetallesMovimientoRel;
    }

    /**
     * @return mixed
     */
    public function getSucursalRel()
    {
        return $this->sucursalRel;
    }

    /**
     * @param mixed $sucursalRel
     */
    public function setSucursalRel($sucursalRel): void
    {
        $this->sucursalRel = $sucursalRel;
    }

    /**
     * @return mixed
     */
    public function getFechaDocumento()
    {
        return $this->fechaDocumento;
    }

    /**
     * @param mixed $fechaDocumento
     */
    public function setFechaDocumento($fechaDocumento): void
    {
        $this->fechaDocumento = $fechaDocumento;
    }



}

