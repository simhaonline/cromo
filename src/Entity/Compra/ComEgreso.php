<?php

namespace App\Entity\Compra;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Compra\ComEgresoRepository")
 */
class ComEgreso
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="codigo_egreso_pk",type="integer")
     */
    private $codigoEgresoPk;

    /**
     * @ORM\Column(name="codigo_egreso_tipo_fk" , type="string" , length=10, nullable=true)
     */
    private $codigoEgresoTipoFk;

    /**
     * @ORM\Column(name="codigo_proveedor_fk" , type="integer")
     */
    private $codigoProveedorFk;

    /**
     * @ORM\Column(name="fecha" ,type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(name="numero" , type="integer")
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="comentarios" , type="string" ,nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="vr_pago" , type="float")
     */
    private $vrPago = 0;

    /**
     * @ORM\Column(name="vr_descuento", type="float")
     */
    private $vrDescuento = 0;

    /**
     * @ORM\Column(name="vr_pago_total" ,type="float")
     */
    private $vrPagoTotal = 0;

    /**
     * @ORM\Column(name="vr_total_descuento" ,type="float")
     */
    private $vrTotalDescuento = 0;

    /**
     * @ORM\Column(name="vr_total_ajuste_peso" ,type="float")
     */
    private $vrTotalAjustePeso = 0;

    /**
     * @ORM\Column(name="vr_total_retencion_ica" ,type="float")
     */
    private $vrTotalRetencionIca = 0;

    /**
     * @ORM\Column(name="vr_total_retencion_iva" ,type="float")
     */
    private $vrTotalRetencionIva = 0;

    /**
     * @ORM\Column(name="vr_total_retencion_fuente" ,type="float")
     */
    private $vrTotalRetencionFuente = 0;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoContabilizado = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compra\ComProveedor" , inversedBy="egresosProveedorRel")
     * @ORM\JoinColumn(name="codigo_proveedor_fk" , referencedColumnName="codigo_proveedor_pk")
     */
    private $proveedorRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compra\ComEgresoTipo" , inversedBy="egresosEgresoTipoRel")
     * @ORM\JoinColumn(name="codigo_egreso_tipo_fk" , referencedColumnName="codigo_egreso_tipo_pk")
     */
    private $egresoTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compra\ComEgresoDetalle", mappedBy="egresoRel")
     */
    private $egresoDetallesEgresoRel;

    /**
     * @return mixed
     */
    public function getCodigoEgresoPk()
    {
        return $this->codigoEgresoPk;
    }

    /**
     * @param mixed $codigoEgresoPk
     */
    public function setCodigoEgresoPk($codigoEgresoPk): void
    {
        $this->codigoEgresoPk = $codigoEgresoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEgresoTipoFk()
    {
        return $this->codigoEgresoTipoFk;
    }

    /**
     * @param mixed $codigoEgresoTipoFk
     */
    public function setCodigoEgresoTipoFk($codigoEgresoTipoFk): void
    {
        $this->codigoEgresoTipoFk = $codigoEgresoTipoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoProveedorFk()
    {
        return $this->codigoProveedorFk;
    }

    /**
     * @param mixed $codigoProveedorFk
     */
    public function setCodigoProveedorFk($codigoProveedorFk): void
    {
        $this->codigoProveedorFk = $codigoProveedorFk;
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
    public function getVrPago()
    {
        return $this->vrPago;
    }

    /**
     * @param mixed $vrPago
     */
    public function setVrPago($vrPago): void
    {
        $this->vrPago = $vrPago;
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
    public function getVrPagoTotal()
    {
        return $this->vrPagoTotal;
    }

    /**
     * @param mixed $vrPagoTotal
     */
    public function setVrPagoTotal($vrPagoTotal): void
    {
        $this->vrPagoTotal = $vrPagoTotal;
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
    public function setVrTotalDescuento($vrTotalDescuento): void
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
    public function setVrTotalAjustePeso($vrTotalAjustePeso): void
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
    public function setVrTotalRetencionIca($vrTotalRetencionIca): void
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
    public function setVrTotalRetencionIva($vrTotalRetencionIva): void
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
    public function setVrTotalRetencionFuente($vrTotalRetencionFuente): void
    {
        $this->vrTotalRetencionFuente = $vrTotalRetencionFuente;
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
    public function getProveedorRel()
    {
        return $this->proveedorRel;
    }

    /**
     * @param mixed $proveedorRel
     */
    public function setProveedorRel($proveedorRel): void
    {
        $this->proveedorRel = $proveedorRel;
    }

    /**
     * @return mixed
     */
    public function getEgresoTipoRel()
    {
        return $this->egresoTipoRel;
    }

    /**
     * @param mixed $egresoTipoRel
     */
    public function setEgresoTipoRel($egresoTipoRel): void
    {
        $this->egresoTipoRel = $egresoTipoRel;
    }

    /**
     * @return mixed
     */
    public function getEgresoDetallesEgresoRel()
    {
        return $this->egresoDetallesEgresoRel;
    }

    /**
     * @param mixed $egresoDetallesEgresoRel
     */
    public function setEgresoDetallesEgresoRel($egresoDetallesEgresoRel): void
    {
        $this->egresoDetallesEgresoRel = $egresoDetallesEgresoRel;
    }

}
