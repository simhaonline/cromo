<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvFacturaTipoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoFacturaTipoPk"},message="Ya existe el código del tipo ya existe")
 */
class InvFacturaTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_tipo_pk",type="string",length=10)
     */
    private $codigoFacturaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo", type="integer",nullable=true)
     */
    private $consecutivo;

    /**
     * @ORM\Column(name="prefijo", type="string", length=5, nullable=true)
     */
    private $prefijo;

    /**
     * @ORM\Column(name="fecha_desde_vigencia", type="date", nullable=true)
     */
    private $fechaDesdeVigencia;

    /**
     * @ORM\Column(name="fecha_hasta_vigencia", type="date", nullable=true)
     */
    private $fechaHastaVigencia;

    /**
     * @ORM\Column(name="numeracion_desde", type="string", length=20, nullable=true)
     */
    private $numeracionDesde;

    /**
     * @ORM\Column(name="numeracion_hasta", type="string", length=20, nullable=true)
     */
    private $numeracionHasta;

    /**
     * @ORM\Column(name="numero_resolucion_dian_factura", type="string", length=1000, nullable=true)
     */
    private $numeroResolucionDianFactura;

    /**
     * @ORM\Column(name="informacion_cuenta_pago", type="string", length=1000, nullable=true)
     */
    private $informacionCuentaPago;

    /**
     * @ORM\OneToMany(targetEntity="InvMovimiento",mappedBy="facturaTipoRel")
     */
    protected $movimientosFacturaTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoFacturaTipoPk()
    {
        return $this->codigoFacturaTipoPk;
    }

    /**
     * @param mixed $codigoFacturaTipoPk
     */
    public function setCodigoFacturaTipoPk($codigoFacturaTipoPk): void
    {
        $this->codigoFacturaTipoPk = $codigoFacturaTipoPk;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }

    /**
     * @param mixed $consecutivo
     */
    public function setConsecutivo($consecutivo): void
    {
        $this->consecutivo = $consecutivo;
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
    public function getFechaDesdeVigencia()
    {
        return $this->fechaDesdeVigencia;
    }

    /**
     * @param mixed $fechaDesdeVigencia
     */
    public function setFechaDesdeVigencia($fechaDesdeVigencia): void
    {
        $this->fechaDesdeVigencia = $fechaDesdeVigencia;
    }

    /**
     * @return mixed
     */
    public function getFechaHastaVigencia()
    {
        return $this->fechaHastaVigencia;
    }

    /**
     * @param mixed $fechaHastaVigencia
     */
    public function setFechaHastaVigencia($fechaHastaVigencia): void
    {
        $this->fechaHastaVigencia = $fechaHastaVigencia;
    }

    /**
     * @return mixed
     */
    public function getNumeracionDesde()
    {
        return $this->numeracionDesde;
    }

    /**
     * @param mixed $numeracionDesde
     */
    public function setNumeracionDesde($numeracionDesde): void
    {
        $this->numeracionDesde = $numeracionDesde;
    }

    /**
     * @return mixed
     */
    public function getNumeracionHasta()
    {
        return $this->numeracionHasta;
    }

    /**
     * @param mixed $numeracionHasta
     */
    public function setNumeracionHasta($numeracionHasta): void
    {
        $this->numeracionHasta = $numeracionHasta;
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
    public function getInformacionCuentaPago()
    {
        return $this->informacionCuentaPago;
    }

    /**
     * @param mixed $informacionCuentaPago
     */
    public function setInformacionCuentaPago($informacionCuentaPago): void
    {
        $this->informacionCuentaPago = $informacionCuentaPago;
    }

    /**
     * @return mixed
     */
    public function getMovimientosFacturaTipoRel()
    {
        return $this->movimientosFacturaTipoRel;
    }

    /**
     * @param mixed $movimientosFacturaTipoRel
     */
    public function setMovimientosFacturaTipoRel($movimientosFacturaTipoRel): void
    {
        $this->movimientosFacturaTipoRel = $movimientosFacturaTipoRel;
    }



}

