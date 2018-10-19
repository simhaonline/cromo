<?php

namespace App\Entity\Compra;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Compra\ComCompraRepository")
 */
class ComCompra
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="codigo_compra_pk" , type="integer")
     */
    private $codigoCompraPk;

    /**
     * @ORM\Column(name="codigo_proveedor_fk" , type="integer")
     */
    private $codigoProveedorFk;

    /**
     * @ORM\Column(name="codigo_compra_tipo_fk", type="string" , length=10, nullable=true)
     */
    private $codigoCompraTipoFk;

    /**
     * @ORM\Column(name="numero" ,type="integer",nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="fecha" ,type="date" )
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_vencimiento", type="date" ,nullable=true)
     */
    private $fechaVencimiento;

    /**
     * @ORM\Column(name="comentarios" , type="string" ,nullable=true)
     *
     */
    private $comentarios;

    /**
     * @ORM\Column(name="vr_subtotal" , type="float")
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="vr_descuento", type="float")
     */
    private $vrDescuento = 0;

    /**
     * @ORM\Column(name="vr_subtotal_menos_descuento" , type="float")
     */
    private $vrSubtotalMenosDescuento = 0;

    /**
     * @ORM\Column(name="vr_iva" , type="float")
     */
    private $vrIva = 0;

    /**
     * @ORM\Column(name="vr_retencion", type="float")
     */
    private $vrRetencion = 0;

    /**
     * @ORM\Column(name="vr_total" ,type="float")
     */
    private $vrTotal = 0;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Compra\ComProveedor" , inversedBy="comprasProveedorRel")
     * @ORM\JoinColumn(name="codigo_proveedor_fk" , referencedColumnName="codigo_proveedor_pk")
     */
    private $proveedorRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compra\ComCompraTipo" , inversedBy="comprasCompraTipoRel")
     * @ORM\JoinColumn(name="codigo_compra_tipo_fk" , referencedColumnName="codigo_compra_tipo_pk")
     */
    private $compraTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoCompraPk()
    {
        return $this->codigoCompraPk;
    }

    /**
     * @param mixed $codigoCompraPk
     */
    public function setCodigoCompraPk($codigoCompraPk): void
    {
        $this->codigoCompraPk = $codigoCompraPk;
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
    public function getCodigoCompraTipoFk()
    {
        return $this->codigoCompraTipoFk;
    }

    /**
     * @param mixed $codigoCompraTipoFk
     */
    public function setCodigoCompraTipoFk($codigoCompraTipoFk): void
    {
        $this->codigoCompraTipoFk = $codigoCompraTipoFk;
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
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * @param mixed $fechaVencimiento
     */
    public function setFechaVencimiento($fechaVencimiento): void
    {
        $this->fechaVencimiento = $fechaVencimiento;
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
    public function getVrSubtotalMenosDescuento()
    {
        return $this->vrSubtotalMenosDescuento;
    }

    /**
     * @param mixed $vrSubtotalMenosDescuento
     */
    public function setVrSubtotalMenosDescuento($vrSubtotalMenosDescuento): void
    {
        $this->vrSubtotalMenosDescuento = $vrSubtotalMenosDescuento;
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
    public function getVrRetencion()
    {
        return $this->vrRetencion;
    }

    /**
     * @param mixed $vrRetencion
     */
    public function setVrRetencion($vrRetencion): void
    {
        $this->vrRetencion = $vrRetencion;
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
    public function getCompraTipoRel()
    {
        return $this->compraTipoRel;
    }

    /**
     * @param mixed $compraTipoRel
     */
    public function setCompraTipoRel($compraTipoRel): void
    {
        $this->compraTipoRel = $compraTipoRel;
    }


}
