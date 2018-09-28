<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_configuracion")
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvConfiguracionRepository")
 */
class InvConfiguracion
{
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
     * @ORM\Column(name="codigo_documento_movimientos_salida_bodega", type="integer", nullable=true, options={"default" : 0})
     */
    private $codigoDocumentoMovimientosSalidaBodega = 0;

    /**
     * @ORM\Column(name="codigo_documento_movimientos_entrada_bodega", type="integer", nullable=true, options={"default" : 0})
     */
    private $codigoDocumentoMovimientosEntradaBodega = 0;

    /**
     * @ORM\Column(name="vr_base_retencion_fuente_venta", type="float", nullable=true, options={"default" : 0})
     */
    private $vrBaseRetencionFuenteVenta = 0;

    /**
     * @ORM\Column(name="porcentaje_retencion_fuente", type="float", nullable=true, options={"default" : 0})
     */
    private $porcentajeRetencionFuente = 0;

    /**
     * @ORM\Column(name="vr_base_retencion_iva_venta", type="float", nullable=true, options={"default" : 0})
     */
    private $vrBaseRetencionIvaVenta = 0;

    /**
     * @ORM\Column(name="porcentaje_retencion_iva", type="float", nullable=true, options={"default" : 0})
     */
    private $porcentajeRetencionIva = 0;


    /**
     * Set codigoConfiguracionPk.
     *
     * @param int $codigoConfiguracionPk
     *
     * @return InvConfiguracion
     */
    public function setCodigoConfiguracionPk($codigoConfiguracionPk)
    {
        $this->codigoConfiguracionPk = $codigoConfiguracionPk;

        return $this;
    }

    /**
     * Get codigoConfiguracionPk.
     *
     * @return int
     */
    public function getCodigoConfiguracionPk()
    {
        return $this->codigoConfiguracionPk;
    }

    /**
     * Set informacionLegalMovimiento.
     *
     * @param string|null $informacionLegalMovimiento
     *
     * @return InvConfiguracion
     */
    public function setInformacionLegalMovimiento($informacionLegalMovimiento = null)
    {
        $this->informacionLegalMovimiento = $informacionLegalMovimiento;

        return $this;
    }

    /**
     * Get informacionLegalMovimiento.
     *
     * @return string|null
     */
    public function getInformacionLegalMovimiento()
    {
        return $this->informacionLegalMovimiento;
    }

    /**
     * Set informacionPagoMovimiento.
     *
     * @param string|null $informacionPagoMovimiento
     *
     * @return InvConfiguracion
     */
    public function setInformacionPagoMovimiento($informacionPagoMovimiento = null)
    {
        $this->informacionPagoMovimiento = $informacionPagoMovimiento;

        return $this;
    }

    /**
     * Get informacionPagoMovimiento.
     *
     * @return string|null
     */
    public function getInformacionPagoMovimiento()
    {
        return $this->informacionPagoMovimiento;
    }

    /**
     * Set informacionContactoMovimiento.
     *
     * @param string|null $informacionContactoMovimiento
     *
     * @return InvConfiguracion
     */
    public function setInformacionContactoMovimiento($informacionContactoMovimiento = null)
    {
        $this->informacionContactoMovimiento = $informacionContactoMovimiento;

        return $this;
    }

    /**
     * Get informacionContactoMovimiento.
     *
     * @return string|null
     */
    public function getInformacionContactoMovimiento()
    {
        return $this->informacionContactoMovimiento;
    }

    /**
     * Set informacionResolucionDianMovimiento.
     *
     * @param string|null $informacionResolucionDianMovimiento
     *
     * @return InvConfiguracion
     */
    public function setInformacionResolucionDianMovimiento($informacionResolucionDianMovimiento = null)
    {
        $this->informacionResolucionDianMovimiento = $informacionResolucionDianMovimiento;

        return $this;
    }

    /**
     * Get informacionResolucionDianMovimiento.
     *
     * @return string|null
     */
    public function getInformacionResolucionDianMovimiento()
    {
        return $this->informacionResolucionDianMovimiento;
    }

    /**
     * Set codigoFormatoMovimiento.
     *
     * @param int $codigoFormatoMovimiento
     *
     * @return InvConfiguracion
     */
    public function setCodigoFormatoMovimiento($codigoFormatoMovimiento)
    {
        $this->codigoFormatoMovimiento = $codigoFormatoMovimiento;

        return $this;
    }

    /**
     * Get codigoFormatoMovimiento.
     *
     * @return int
     */
    public function getCodigoFormatoMovimiento()
    {
        return $this->codigoFormatoMovimiento;
    }

    /**
     * Set codigoDocumentoMovimientosSalidaBodega.
     *
     * @param int|null $codigoDocumentoMovimientosSalidaBodega
     *
     * @return InvConfiguracion
     */
    public function setCodigoDocumentoMovimientosSalidaBodega($codigoDocumentoMovimientosSalidaBodega = null)
    {
        $this->codigoDocumentoMovimientosSalidaBodega = $codigoDocumentoMovimientosSalidaBodega;

        return $this;
    }

    /**
     * Get codigoDocumentoMovimientosSalidaBodega.
     *
     * @return int|null
     */
    public function getCodigoDocumentoMovimientosSalidaBodega()
    {
        return $this->codigoDocumentoMovimientosSalidaBodega;
    }

    /**
     * Set codigoDocumentoMovimientosEntradaBodega.
     *
     * @param int|null $codigoDocumentoMovimientosEntradaBodega
     *
     * @return InvConfiguracion
     */
    public function setCodigoDocumentoMovimientosEntradaBodega($codigoDocumentoMovimientosEntradaBodega = null)
    {
        $this->codigoDocumentoMovimientosEntradaBodega = $codigoDocumentoMovimientosEntradaBodega;

        return $this;
    }

    /**
     * Get codigoDocumentoMovimientosEntradaBodega.
     *
     * @return int|null
     */
    public function getCodigoDocumentoMovimientosEntradaBodega()
    {
        return $this->codigoDocumentoMovimientosEntradaBodega;
    }

    /**
     * @return mixed
     */
    public function getVrBaseRetencionFuenteVenta()
    {
        return $this->vrBaseRetencionFuenteVenta;
    }

    /**
     * @param mixed $vrBaseRetencionFuenteVenta
     */
    public function setVrBaseRetencionFuenteVenta($vrBaseRetencionFuenteVenta): void
    {
        $this->vrBaseRetencionFuenteVenta = $vrBaseRetencionFuenteVenta;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeRetencionFuente()
    {
        return $this->porcentajeRetencionFuente;
    }

    /**
     * @param mixed $porcentajeRetencionFuente
     */
    public function setPorcentajeRetencionFuente($porcentajeRetencionFuente): void
    {
        $this->porcentajeRetencionFuente = $porcentajeRetencionFuente;
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
    public function setVrBaseRetencionIvaVenta($vrBaseRetencionIvaVenta): void
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
    public function setPorcentajeRetencionIva($porcentajeRetencionIva): void
    {
        $this->porcentajeRetencionIva = $porcentajeRetencionIva;
    }



}
