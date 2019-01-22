<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarReciboRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class CarRecibo
{
    public $infoLog = [
        "primaryKey" => "codigoReciboPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_recibo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoReciboPk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=10, nullable=true)
     */
    private $codigoCuentaFk;

    /**
     * @ORM\Column(name="codigo_asesor_fk", type="integer", nullable=true)
     */
    private $codigoAsesorFk;

    /**
     * @ORM\Column(name="codigo_recibo_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoReciboTipoFk;

    /**
     * @ORM\Column(name="numero", type="string", length=30, nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="soporte", type="string", length=20, nullable=true)
     * @Assert\Length(
     *     max=30,
     *     maxMessage="El campo no puede tener mas de 20 caracteres"
     * )
     */
    private $soporte;

    /**
     * @ORM\Column(name="fecha_pago", type="date", nullable=true)
     */
    private $fechaPago;

    /**
     * @ORM\Column(name="vr_total_descueto", type="float", options={"default":0})
     */
    private $vrTotalDescuento = 0;

    /**
     * @ORM\Column(name="vr_total_ajuste_peso", type="float", options={"default":0})
     */
    private $vrTotalAjustePeso = 0;

    /**
     * @ORM\Column(name="vr_total_rete_ica", type="float", options={"default":0})
     */
    private $vrTotalRetencionIca = 0;

    /**
     * @ORM\Column(name="vr_total_rete_iva", type="float", options={"default":0})
     */
    private $vrTotalRetencionIva = 0;

    /**
     * @ORM\Column(name="vr_total_rete_fuente", type="float", options={"default":0})
     */
    private $vrTotalRetencionFuente = 0;

    /**
     * @ORM\Column(name="vr_total_otro_descuento", type="float", options={"default":0})
     */
    private $vrTotalOtroDescuento = 0;

    /**
     * @ORM\Column(name="vr_total_otro_ingreso", type="float", options={"default":0})
     */
    private $vrTotalOtroIngreso = 0;

    /**
     * @ORM\Column(name="vr_pago", type="float")
     */
    private $vrPago = 0;

    /**
     * @ORM\Column(name="vr_pago_total", type="float")
     */
    private $vrPagoTotal = 0;

    /**
     * @ORM\Column(name="numero_referencia", type="integer", nullable=true)
     */
    private $numeroReferencia = 0;

    /**
     * @ORM\Column(name="numero_referencia_prefijo", type="string", length=20, nullable=true)
     */
    private $numeroReferenciaPrefijo;

    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */
    private $codigoTerceroFk;

    /**
     * @ORM\Column(name="estado_impreso", type="boolean", options={"default":false})
     */
    private $estadoImpreso = 0;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean", options={"default":false})
     */
    private $estadoAnulado = 0;

    /**
     * @ORM\Column(name="estado_exportado", type="boolean", options={"default":false})
     */
    private $estadoExportado = 0;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean", options={"default":false})
     */
    private $estadoAprobado = 0;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean", options={"default":false})
     */
    private $estadoAutorizado = 0;

    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean", options={"default":false})
     */
    private $estadoContabilizado = 0;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="CarCliente", inversedBy="recibosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="CarReciboTipo", inversedBy="recibosReciboTipoRel")
     * @ORM\JoinColumn(name="codigo_recibo_tipo_fk", referencedColumnName="codigo_recibo_tipo_pk")
     */
    protected $reciboTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCuenta", inversedBy="recibosCuentaRel")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Financiero\FinTercero", inversedBy="carRecibosTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenAsesor", inversedBy="reciboAsesorRel")
     * @ORM\JoinColumn(name="codigo_asesor_fk", referencedColumnName="codigo_asesor_pk")

     */
    protected $asesorRel;

    /**
     * @ORM\OneToMany(targetEntity="CarReciboDetalle", mappedBy="reciboRel")
     */
    protected $recibosDetallesRecibosRel;

    /**
     * @return mixed
     */
    public function getCodigoReciboPk()
    {
        return $this->codigoReciboPk;
    }

    /**
     * @param mixed $codigoReciboPk
     */
    public function setCodigoReciboPk( $codigoReciboPk ): void
    {
        $this->codigoReciboPk = $codigoReciboPk;
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
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * @param mixed $codigoCuentaFk
     */
    public function setCodigoCuentaFk( $codigoCuentaFk ): void
    {
        $this->codigoCuentaFk = $codigoCuentaFk;
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
    public function setCodigoAsesorFk( $codigoAsesorFk ): void
    {
        $this->codigoAsesorFk = $codigoAsesorFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoReciboTipoFk()
    {
        return $this->codigoReciboTipoFk;
    }

    /**
     * @param mixed $codigoReciboTipoFk
     */
    public function setCodigoReciboTipoFk( $codigoReciboTipoFk ): void
    {
        $this->codigoReciboTipoFk = $codigoReciboTipoFk;
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
    public function getFechaPago()
    {
        return $this->fechaPago;
    }

    /**
     * @param mixed $fechaPago
     */
    public function setFechaPago( $fechaPago ): void
    {
        $this->fechaPago = $fechaPago;
    }

    /**
     * @return mixed
     */
    public function getVrTotalDescuento()
    {
        return $this->vrTotalDescuento;
    }

    /**
     * @param mixed $vrTotalDescuento
     */
    public function setVrTotalDescuento( $vrTotalDescuento ): void
    {
        $this->vrTotalDescuento = $vrTotalDescuento;
    }

    /**
     * @return mixed
     */
    public function getVrTotalAjustePeso()
    {
        return $this->vrTotalAjustePeso;
    }

    /**
     * @param mixed $vrTotalAjustePeso
     */
    public function setVrTotalAjustePeso( $vrTotalAjustePeso ): void
    {
        $this->vrTotalAjustePeso = $vrTotalAjustePeso;
    }

    /**
     * @return mixed
     */
    public function getVrTotalRetencionIca()
    {
        return $this->vrTotalRetencionIca;
    }

    /**
     * @param mixed $vrTotalRetencionIca
     */
    public function setVrTotalRetencionIca( $vrTotalRetencionIca ): void
    {
        $this->vrTotalRetencionIca = $vrTotalRetencionIca;
    }

    /**
     * @return mixed
     */
    public function getVrTotalRetencionIva()
    {
        return $this->vrTotalRetencionIva;
    }

    /**
     * @param mixed $vrTotalRetencionIva
     */
    public function setVrTotalRetencionIva( $vrTotalRetencionIva ): void
    {
        $this->vrTotalRetencionIva = $vrTotalRetencionIva;
    }

    /**
     * @return mixed
     */
    public function getVrTotalRetencionFuente()
    {
        return $this->vrTotalRetencionFuente;
    }

    /**
     * @param mixed $vrTotalRetencionFuente
     */
    public function setVrTotalRetencionFuente( $vrTotalRetencionFuente ): void
    {
        $this->vrTotalRetencionFuente = $vrTotalRetencionFuente;
    }

    /**
     * @return mixed
     */
    public function getVrTotalOtroDescuento()
    {
        return $this->vrTotalOtroDescuento;
    }

    /**
     * @param mixed $vrTotalOtroDescuento
     */
    public function setVrTotalOtroDescuento( $vrTotalOtroDescuento ): void
    {
        $this->vrTotalOtroDescuento = $vrTotalOtroDescuento;
    }

    /**
     * @return mixed
     */
    public function getVrPago()
    {
        return $this->vrPago;
    }

    /**
     * @param mixed $vrPago
     */
    public function setVrPago( $vrPago ): void
    {
        $this->vrPago = $vrPago;
    }

    /**
     * @return mixed
     */
    public function getVrPagoTotal()
    {
        return $this->vrPagoTotal;
    }

    /**
     * @param mixed $vrPagoTotal
     */
    public function setVrPagoTotal( $vrPagoTotal ): void
    {
        $this->vrPagoTotal = $vrPagoTotal;
    }

    /**
     * @return mixed
     */
    public function getNumeroReferencia()
    {
        return $this->numeroReferencia;
    }

    /**
     * @param mixed $numeroReferencia
     */
    public function setNumeroReferencia( $numeroReferencia ): void
    {
        $this->numeroReferencia = $numeroReferencia;
    }

    /**
     * @return mixed
     */
    public function getNumeroReferenciaPrefijo()
    {
        return $this->numeroReferenciaPrefijo;
    }

    /**
     * @param mixed $numeroReferenciaPrefijo
     */
    public function setNumeroReferenciaPrefijo( $numeroReferenciaPrefijo ): void
    {
        $this->numeroReferenciaPrefijo = $numeroReferenciaPrefijo;
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
    public function getEstadoImpreso()
    {
        return $this->estadoImpreso;
    }

    /**
     * @param mixed $estadoImpreso
     */
    public function setEstadoImpreso( $estadoImpreso ): void
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
    public function setEstadoAnulado( $estadoAnulado ): void
    {
        $this->estadoAnulado = $estadoAnulado;
    }

    /**
     * @return mixed
     */
    public function getEstadoExportado()
    {
        return $this->estadoExportado;
    }

    /**
     * @param mixed $estadoExportado
     */
    public function setEstadoExportado( $estadoExportado ): void
    {
        $this->estadoExportado = $estadoExportado;
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
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * @param mixed $comentarios
     */
    public function setComentarios( $comentarios ): void
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
    public function setUsuario( $usuario ): void
    {
        $this->usuario = $usuario;
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
    public function getReciboTipoRel()
    {
        return $this->reciboTipoRel;
    }

    /**
     * @param mixed $reciboTipoRel
     */
    public function setReciboTipoRel( $reciboTipoRel ): void
    {
        $this->reciboTipoRel = $reciboTipoRel;
    }

    /**
     * @return mixed
     */
    public function getCuentaRel()
    {
        return $this->cuentaRel;
    }

    /**
     * @param mixed $cuentaRel
     */
    public function setCuentaRel( $cuentaRel ): void
    {
        $this->cuentaRel = $cuentaRel;
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
    public function getAsesorRel()
    {
        return $this->asesorRel;
    }

    /**
     * @param mixed $asesorRel
     */
    public function setAsesorRel( $asesorRel ): void
    {
        $this->asesorRel = $asesorRel;
    }

    /**
     * @return mixed
     */
    public function getRecibosDetallesRecibosRel()
    {
        return $this->recibosDetallesRecibosRel;
    }

    /**
     * @param mixed $recibosDetallesRecibosRel
     */
    public function setRecibosDetallesRecibosRel( $recibosDetallesRecibosRel ): void
    {
        $this->recibosDetallesRecibosRel = $recibosDetallesRecibosRel;
    }

    /**
     * @return mixed
     */
    public function getVrTotalOtroIngreso()
    {
        return $this->vrTotalOtroIngreso;
    }

    /**
     * @param mixed $vrTotalOtroIngreso
     */
    public function setVrTotalOtroIngreso( $vrTotalOtroIngreso ): void
    {
        $this->vrTotalOtroIngreso = $vrTotalOtroIngreso;
    }




}
