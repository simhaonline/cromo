<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarIngresoDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class CarIngresoDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoIngresoDetallePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $codigoIngresoDetallePk;

    /**
     * @ORM\Column(name="codigo_ingreso_fk" , type="integer")
     */
    private $codigoIngresoFk;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaFk;

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_fk" , type="integer", nullable=true)
     */
    private $codigoCuentaCobrarFk;

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoCuentaCobrarTipoFk;

    /**
     * @ORM\Column(name="codigo_cliente_fk" , type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="numero", type="string", length=30, nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="vr_retencion", type="float", nullable=true)
     */
    private $vrRetencion = 0;

    /**
     * @ORM\Column(name="vr_base", type="float", nullable=true, options={"default":0})
     */
    private $vrBase = 0;

    /**
     * @ORM\Column(name="vr_pago", type="float", nullable=true)
     */
    private $vrPago = 0;

    /**
     * @ORM\Column(name="codigo_impuesto_retencion_fk", type="string", length=5, nullable=true)
     */
    private $codigoImpuestoRetencionFk;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="naturaleza", type="string", length=1, nullable=true)
     */
    private $naturaleza = 0;

    /**
     * @ORM\Column(name="detalle", type="string", length=800, nullable=true)
     */
    private $detalle;

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCentroCostoFk;

    /**
     * @return mixed
     */
    public function getCodigoIngresoDetallePk()
    {
        return $this->codigoIngresoDetallePk;
    }

    /**
     * @param mixed $codigoIngresoDetallePk
     */
    public function setCodigoIngresoDetallePk($codigoIngresoDetallePk): void
    {
        $this->codigoIngresoDetallePk = $codigoIngresoDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoIngresoFk()
    {
        return $this->codigoIngresoFk;
    }

    /**
     * @param mixed $codigoIngresoFk
     */
    public function setCodigoIngresoFk($codigoIngresoFk): void
    {
        $this->codigoIngresoFk = $codigoIngresoFk;
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
    public function setCodigoCuentaFk($codigoCuentaFk): void
    {
        $this->codigoCuentaFk = $codigoCuentaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaCobrarFk()
    {
        return $this->codigoCuentaCobrarFk;
    }

    /**
     * @param mixed $codigoCuentaCobrarFk
     */
    public function setCodigoCuentaCobrarFk($codigoCuentaCobrarFk): void
    {
        $this->codigoCuentaCobrarFk = $codigoCuentaCobrarFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaCobrarTipoFk()
    {
        return $this->codigoCuentaCobrarTipoFk;
    }

    /**
     * @param mixed $codigoCuentaCobrarTipoFk
     */
    public function setCodigoCuentaCobrarTipoFk($codigoCuentaCobrarTipoFk): void
    {
        $this->codigoCuentaCobrarTipoFk = $codigoCuentaCobrarTipoFk;
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
     * @return int
     */
    public function getVrRetencion(): int
    {
        return $this->vrRetencion;
    }

    /**
     * @param int $vrRetencion
     */
    public function setVrRetencion(int $vrRetencion): void
    {
        $this->vrRetencion = $vrRetencion;
    }

    /**
     * @return int
     */
    public function getVrBase(): int
    {
        return $this->vrBase;
    }

    /**
     * @param int $vrBase
     */
    public function setVrBase(int $vrBase): void
    {
        $this->vrBase = $vrBase;
    }

    /**
     * @return int
     */
    public function getVrPago(): int
    {
        return $this->vrPago;
    }

    /**
     * @param int $vrPago
     */
    public function setVrPago(int $vrPago): void
    {
        $this->vrPago = $vrPago;
    }

    /**
     * @return mixed
     */
    public function getCodigoImpuestoRetencionFk()
    {
        return $this->codigoImpuestoRetencionFk;
    }

    /**
     * @param mixed $codigoImpuestoRetencionFk
     */
    public function setCodigoImpuestoRetencionFk($codigoImpuestoRetencionFk): void
    {
        $this->codigoImpuestoRetencionFk = $codigoImpuestoRetencionFk;
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
     * @return int
     */
    public function getNaturaleza(): int
    {
        return $this->naturaleza;
    }

    /**
     * @param int $naturaleza
     */
    public function setNaturaleza(int $naturaleza): void
    {
        $this->naturaleza = $naturaleza;
    }

    /**
     * @return mixed
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * @param mixed $detalle
     */
    public function setDetalle($detalle): void
    {
        $this->detalle = $detalle;
    }

    /**
     * @return mixed
     */
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * @param mixed $codigoCentroCostoFk
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk): void
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;
    }


}
