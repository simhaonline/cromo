<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_configuracion")
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvConfiguracionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class InvConfiguracion
{
    public $infoLog = [
        "primaryKey" => "codigoConfiguracionPk",
        "todos"     => true,
    ];
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_pk", type="integer")
     */
    private $codigoConfiguracionPk;
    
    /**
     * @ORM\Column(name="informacion_legal_movimiento", type="text", nullable=true)
     */    
    private $informacionLegalMovimiento; 

    /**
     * @ORM\Column(name="informacion_pago_movimiento", type="text", nullable=true)
     */    
    private $informacionPagoMovimiento;     
    
    /**
     * @ORM\Column(name="informacion_contacto_movimiento", type="text", nullable=true)
     */    
    private $informacionContactoMovimiento;    
    
    /**
     * @ORM\Column(name="informacion_resolucion_dian_movimiento", type="text", nullable=true)
     */    
    private $informacionResolucionDianMovimiento;                              
    
    /**
     * @ORM\Column(name="codigo_formato_movimiento", type="integer")
     */    
    private $codigoFormatoMovimiento = 0;

    /**
     * @ORM\Column(name="codigo_formato_remision", type="integer", nullable=true, options={"default" : 0})
     */
    private $codigoFormatoRemision = 0;

    /**
     * @ORM\Column(name="codigo_documento_movimientos_salida_bodega", type="integer", nullable=true, options={"default" : 0})
     */
    private $codigoDocumentoMovimientosSalidaBodega = 0;

    /**
     * @ORM\Column(name="codigo_documento_movimientos_entrada_bodega", type="integer", nullable=true, options={"default" : 0})
     */
    private $codigoDocumentoMovimientosEntradaBodega = 0;

    /**
     * @ORM\Column(name="vr_base_retencion_iva_venta", type="float", nullable=true, options={"default" : 0})
     */
    private $vrBaseRetencionIvaVenta = 0;

    /**
     * @ORM\Column(name="porcentaje_retencion_iva", type="float", nullable=true, options={"default" : 0})
     */
    private $porcentajeRetencionIva = 0;

    /**
     * @ORM\Column(name="validar_bodega_usuario", type="boolean", options={"default" : false})
     */
    private $validarBodegaUsuario = false;

    /**
     * Se usa para mandar el impuesto al recaudo
     * @ORM\Column(name="impuesto_recaudo", type="boolean", options={"default" : false})
     */
    private $impuestoRecaudo = false;

    /**
     * @return mixed
     */
    public function getCodigoConfiguracionPk()
    {
        return $this->codigoConfiguracionPk;
    }

    /**
     * @param mixed $codigoConfiguracionPk
     */
    public function setCodigoConfiguracionPk( $codigoConfiguracionPk ): void
    {
        $this->codigoConfiguracionPk = $codigoConfiguracionPk;
    }

    /**
     * @return mixed
     */
    public function getInformacionLegalMovimiento()
    {
        return $this->informacionLegalMovimiento;
    }

    /**
     * @param mixed $informacionLegalMovimiento
     */
    public function setInformacionLegalMovimiento( $informacionLegalMovimiento ): void
    {
        $this->informacionLegalMovimiento = $informacionLegalMovimiento;
    }

    /**
     * @return mixed
     */
    public function getInformacionPagoMovimiento()
    {
        return $this->informacionPagoMovimiento;
    }

    /**
     * @param mixed $informacionPagoMovimiento
     */
    public function setInformacionPagoMovimiento( $informacionPagoMovimiento ): void
    {
        $this->informacionPagoMovimiento = $informacionPagoMovimiento;
    }

    /**
     * @return mixed
     */
    public function getInformacionContactoMovimiento()
    {
        return $this->informacionContactoMovimiento;
    }

    /**
     * @param mixed $informacionContactoMovimiento
     */
    public function setInformacionContactoMovimiento( $informacionContactoMovimiento ): void
    {
        $this->informacionContactoMovimiento = $informacionContactoMovimiento;
    }

    /**
     * @return mixed
     */
    public function getInformacionResolucionDianMovimiento()
    {
        return $this->informacionResolucionDianMovimiento;
    }

    /**
     * @param mixed $informacionResolucionDianMovimiento
     */
    public function setInformacionResolucionDianMovimiento( $informacionResolucionDianMovimiento ): void
    {
        $this->informacionResolucionDianMovimiento = $informacionResolucionDianMovimiento;
    }

    /**
     * @return mixed
     */
    public function getCodigoFormatoMovimiento()
    {
        return $this->codigoFormatoMovimiento;
    }

    /**
     * @param mixed $codigoFormatoMovimiento
     */
    public function setCodigoFormatoMovimiento( $codigoFormatoMovimiento ): void
    {
        $this->codigoFormatoMovimiento = $codigoFormatoMovimiento;
    }

    /**
     * @return mixed
     */
    public function getCodigoFormatoRemision()
    {
        return $this->codigoFormatoRemision;
    }

    /**
     * @param mixed $codigoFormatoRemision
     */
    public function setCodigoFormatoRemision( $codigoFormatoRemision ): void
    {
        $this->codigoFormatoRemision = $codigoFormatoRemision;
    }

    /**
     * @return mixed
     */
    public function getCodigoDocumentoMovimientosSalidaBodega()
    {
        return $this->codigoDocumentoMovimientosSalidaBodega;
    }

    /**
     * @param mixed $codigoDocumentoMovimientosSalidaBodega
     */
    public function setCodigoDocumentoMovimientosSalidaBodega( $codigoDocumentoMovimientosSalidaBodega ): void
    {
        $this->codigoDocumentoMovimientosSalidaBodega = $codigoDocumentoMovimientosSalidaBodega;
    }

    /**
     * @return mixed
     */
    public function getCodigoDocumentoMovimientosEntradaBodega()
    {
        return $this->codigoDocumentoMovimientosEntradaBodega;
    }

    /**
     * @param mixed $codigoDocumentoMovimientosEntradaBodega
     */
    public function setCodigoDocumentoMovimientosEntradaBodega( $codigoDocumentoMovimientosEntradaBodega ): void
    {
        $this->codigoDocumentoMovimientosEntradaBodega = $codigoDocumentoMovimientosEntradaBodega;
    }

    /**
     * @return mixed
     */
    public function getVrBaseRetencionIvaVenta()
    {
        return $this->vrBaseRetencionIvaVenta;
    }

    /**
     * @param mixed $vrBaseRetencionIvaVenta
     */
    public function setVrBaseRetencionIvaVenta( $vrBaseRetencionIvaVenta ): void
    {
        $this->vrBaseRetencionIvaVenta = $vrBaseRetencionIvaVenta;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeRetencionIva()
    {
        return $this->porcentajeRetencionIva;
    }

    /**
     * @param mixed $porcentajeRetencionIva
     */
    public function setPorcentajeRetencionIva( $porcentajeRetencionIva ): void
    {
        $this->porcentajeRetencionIva = $porcentajeRetencionIva;
    }

    /**
     * @return mixed
     */
    public function getValidarBodegaUsuario()
    {
        return $this->validarBodegaUsuario;
    }

    /**
     * @param mixed $validarBodegaUsuario
     */
    public function setValidarBodegaUsuario( $validarBodegaUsuario ): void
    {
        $this->validarBodegaUsuario = $validarBodegaUsuario;
    }

    /**
     * @return mixed
     */
    public function getImpuestoRecaudo()
    {
        return $this->impuestoRecaudo;
    }

    /**
     * @param mixed $impuestoRecaudo
     */
    public function setImpuestoRecaudo( $impuestoRecaudo ): void
    {
        $this->impuestoRecaudo = $impuestoRecaudo;
    }



}
