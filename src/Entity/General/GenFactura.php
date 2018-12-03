<?php

namespace App\Entity\General;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenFacturaRepository")
 */
class GenFactura
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaPk;

    /**
     * @ORM\Column(name="factura_tipo", type="string",length=10, nullable=true)
     */
    private $facturaTipo;

    /**
     * @ORM\Column(name="modulo", type="string",length=20, nullable=true)
     */
    private $modulo;

    /**
     * @ORM\Column(name="tipo_identificacion", type="string",length=3, nullable=true)
     */
    private $tipoIdentificacion;

    /**
     * @ORM\Column(name="identificacion", type="string",length=80, nullable=true)
     */
    private $identificacion;

    /**
     * @ORM\Column(name="tercero", type="string",length=150, nullable=true)
     */
    private $tercero;

    /**
     * @ORM\Column(name="prefijo", type="string",length=20, nullable=true)
     */
    private $prefijo;

    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="numeracion_desde_resolucion", type="integer", nullable=true)
     */
    private $numeracionDesdeResolucion = 0;

    /**
     * @ORM\Column(name="numeracion_hasta_resolucion", type="integer", nullable=true)
     */
    private $numeracionHastaResolucion = 0;

    /**
     * @ORM\Column(name="direccion", type="string",length=100, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="telefono", type="string",length=100, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_desde_vigencia_resolucion", type="datetime", nullable=true)
     */
    private $fechaDesdeVigenciaResolucion;

    /**
     * @ORM\Column(name="fecha_hasta_vigencia_resolucion", type="datetime", nullable=true)
     */
    private $fechaHastaVigenciaResolucion;

    /**
     * @ORM\Column(name="fecha_vence", type="date", nullable=true)
     */
    private $fechaVence;

    /**
     * @ORM\Column(name="plazo_pago", type="integer", nullable=true)
     */
    private $plazoPago = 0;

    /**
     * @ORM\Column(name="soporte", type="string", length=50, nullable=true)
     */
    private $soporte;

    /**
     * @ORM\Column(name="numero_resolucion_dian_factura", type="string", length=50, nullable=true)
     */
    private $numeroResolucionDianFactura;

    /**
     * @ORM\Column(name="ciudad_factura", type="string", length=50, nullable=true)
     */
    private $ciudadFactura;

    /**
     * @ORM\Column(name="vr_iva", type="float", nullable=true)
     */
    private $vrIva;

    /**
     * @ORM\Column(name="vr_base_aiu", type="float", nullable=true)
     */
    private $vrBaseAIU;

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
     * @ORM\Column(name="comentario", type="string", length=500, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\Column(name="exportado", type="boolean", options={"default":false}, nullable=true)
     */
    private $exportado = false;

    /**
     * @ORM\Column(name="usuario", type="string", length=25, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\OneToMany(targetEntity="GenFacturaDetalle", mappedBy="facturaRel")
     */
    protected $facturasDetallesFacturaRel;

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
    public function getFacturaTipo()
    {
        return $this->facturaTipo;
    }

    /**
     * @param mixed $facturaTipo
     */
    public function setFacturaTipo($facturaTipo): void
    {
        $this->facturaTipo = $facturaTipo;
    }

    /**
     * @return mixed
     */
    public function getVrBaseAIU()
    {
        return $this->vrBaseAIU;
    }

    /**
     * @param mixed $vrBaseAIU
     */
    public function setVrBaseAIU($vrBaseAIU): void
    {
        $this->vrBaseAIU = $vrBaseAIU;
    }

    /**
     * @return mixed
     */
    public function getModulo()
    {
        return $this->modulo;
    }

    /**
     * @param mixed $modulo
     */
    public function setModulo($modulo): void
    {
        $this->modulo = $modulo;
    }

    /**
     * @return mixed
     */
    public function getTipoIdentificacion()
    {
        return $this->tipoIdentificacion;
    }

    /**
     * @param mixed $tipoIdentificacion
     */
    public function setTipoIdentificacion($tipoIdentificacion): void
    {
        $this->tipoIdentificacion = $tipoIdentificacion;
    }

    /**
     * @return mixed
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param mixed $telefono
     */
    public function setTelefono($telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * @return mixed
     */
    public function getIdentificacion()
    {
        return $this->identificacion;
    }

    /**
     * @param mixed $identificacion
     */
    public function setIdentificacion($identificacion): void
    {
        $this->identificacion = $identificacion;
    }

    /**
     * @return mixed
     */
    public function getTercero()
    {
        return $this->tercero;
    }

    /**
     * @param mixed $tercero
     */
    public function setTercero($tercero): void
    {
        $this->tercero = $tercero;
    }

    /**
     * @return mixed
     */
    public function getPrefijo()
    {
        return $this->prefijo;
    }

    /**
     * @param mixed $prefijo
     */
    public function setPrefijo($prefijo): void
    {
        $this->prefijo = $prefijo;
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
    public function getNumeracionDesdeResolucion()
    {
        return $this->numeracionDesdeResolucion;
    }

    /**
     * @param mixed $numeracionDesdeResolucion
     */
    public function setNumeracionDesdeResolucion($numeracionDesdeResolucion): void
    {
        $this->numeracionDesdeResolucion = $numeracionDesdeResolucion;
    }

    /**
     * @return mixed
     */
    public function getNumeracionHastaResolucion()
    {
        return $this->numeracionHastaResolucion;
    }

    /**
     * @param mixed $numeracionHastaResolucion
     */
    public function setNumeracionHastaResolucion($numeracionHastaResolucion): void
    {
        $this->numeracionHastaResolucion = $numeracionHastaResolucion;
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
    public function getFechaDesdeVigenciaResolucion()
    {
        return $this->fechaDesdeVigenciaResolucion;
    }

    /**
     * @param mixed $fechaDesdeVigenciaResolucion
     */
    public function setFechaDesdeVigenciaResolucion($fechaDesdeVigenciaResolucion): void
    {
        $this->fechaDesdeVigenciaResolucion = $fechaDesdeVigenciaResolucion;
    }

    /**
     * @return mixed
     */
    public function getFechaHastaVigenciaResolucion()
    {
        return $this->fechaHastaVigenciaResolucion;
    }

    /**
     * @param mixed $fechaHastaVigenciaResolucion
     */
    public function setFechaHastaVigenciaResolucion($fechaHastaVigenciaResolucion): void
    {
        $this->fechaHastaVigenciaResolucion = $fechaHastaVigenciaResolucion;
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
    public function getNumeroResolucionDianFactura()
    {
        return $this->numeroResolucionDianFactura;
    }

    /**
     * @param mixed $numeroResolucionDianFactura
     */
    public function setNumeroResolucionDianFactura($numeroResolucionDianFactura): void
    {
        $this->numeroResolucionDianFactura = $numeroResolucionDianFactura;
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
    public function getExportado()
    {
        return $this->exportado;
    }

    /**
     * @param mixed $exportado
     */
    public function setExportado($exportado): void
    {
        $this->exportado = $exportado;
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
    public function getFacturasDetallesFacturaRel()
    {
        return $this->facturasDetallesFacturaRel;
    }

    /**
     * @param mixed $facturasDetallesFacturaRel
     */
    public function setFacturasDetallesFacturaRel($facturasDetallesFacturaRel): void
    {
        $this->facturasDetallesFacturaRel = $facturasDetallesFacturaRel;
    }
}

