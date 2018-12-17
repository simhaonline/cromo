<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarAnticipoDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class CarAnticipoDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoAnticipoDetallePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_anticipo_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoAnticipoDetallePk;

    /**
     * @ORM\Column(name="codigo_anticipo_fk", type="integer", nullable=true)
     */
    private $codigoAnticipoFk;

    /**
     * @ORM\Column(name="vr_pago", type="float", nullable=true)
     */
    private $vrPago = 0;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="operacion", type="integer")
     */
    private $operacion = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cartera\CarAnticipo", inversedBy="anticiposDetallesRecibosRel")
     * @ORM\JoinColumn(name="codigo_anticipo_fk", referencedColumnName="codigo_anticipo_pk")
     */
    protected $anticipoRel;

    /**
     * @return mixed
     */
    public function getCodigoReciboDetallePk()
    {
        return $this->codigoReciboDetallePk;
    }

    /**
     * @param mixed $codigoReciboDetallePk
     */
    public function setCodigoReciboDetallePk($codigoReciboDetallePk): void
    {
        $this->codigoReciboDetallePk = $codigoReciboDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoReciboFk()
    {
        return $this->codigoReciboFk;
    }

    /**
     * @param mixed $codigoReciboFk
     */
    public function setCodigoReciboFk($codigoReciboFk): void
    {
        $this->codigoReciboFk = $codigoReciboFk;
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
    public function getCodigoCuentaCobrarAplicacionFk()
    {
        return $this->codigoCuentaCobrarAplicacionFk;
    }

    /**
     * @param mixed $codigoCuentaCobrarAplicacionFk
     */
    public function setCodigoCuentaCobrarAplicacionFk($codigoCuentaCobrarAplicacionFk): void
    {
        $this->codigoCuentaCobrarAplicacionFk = $codigoCuentaCobrarAplicacionFk;
    }

    /**
     * @return mixed
     */
    public function getNumeroFactura()
    {
        return $this->numeroFactura;
    }

    /**
     * @param mixed $numeroFactura
     */
    public function setNumeroFactura($numeroFactura): void
    {
        $this->numeroFactura = $numeroFactura;
    }

    /**
     * @return mixed
     */
    public function getNumeroDocumentoAplicacion()
    {
        return $this->numeroDocumentoAplicacion;
    }

    /**
     * @param mixed $numeroDocumentoAplicacion
     */
    public function setNumeroDocumentoAplicacion($numeroDocumentoAplicacion): void
    {
        $this->numeroDocumentoAplicacion = $numeroDocumentoAplicacion;
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
    public function getVrAjustePeso()
    {
        return $this->vrAjustePeso;
    }

    /**
     * @param mixed $vrAjustePeso
     */
    public function setVrAjustePeso($vrAjustePeso): void
    {
        $this->vrAjustePeso = $vrAjustePeso;
    }

    /**
     * @return mixed
     */
    public function getVrRetencionIca()
    {
        return $this->vrRetencionIca;
    }

    /**
     * @param mixed $vrRetencionIca
     */
    public function setVrRetencionIca($vrRetencionIca): void
    {
        $this->vrRetencionIca = $vrRetencionIca;
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
    public function getVrPagoAfectar()
    {
        return $this->vrPagoAfectar;
    }

    /**
     * @param mixed $vrPagoAfectar
     */
    public function setVrPagoAfectar($vrPagoAfectar): void
    {
        $this->vrPagoAfectar = $vrPagoAfectar;
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
    public function getOperacion()
    {
        return $this->operacion;
    }

    /**
     * @param mixed $operacion
     */
    public function setOperacion($operacion): void
    {
        $this->operacion = $operacion;
    }

    /**
     * @return mixed
     */
    public function getReciboRel()
    {
        return $this->reciboRel;
    }

    /**
     * @param mixed $reciboRel
     */
    public function setReciboRel($reciboRel): void
    {
        $this->reciboRel = $reciboRel;
    }

    /**
     * @return mixed
     */
    public function getCuentaCobrarRel()
    {
        return $this->cuentaCobrarRel;
    }

    /**
     * @param mixed $cuentaCobrarRel
     */
    public function setCuentaCobrarRel($cuentaCobrarRel): void
    {
        $this->cuentaCobrarRel = $cuentaCobrarRel;
    }

    /**
     * @return mixed
     */
    public function getCuentaCobrarTipoRel()
    {
        return $this->cuentaCobrarTipoRel;
    }

    /**
     * @param mixed $cuentaCobrarTipoRel
     */
    public function setCuentaCobrarTipoRel($cuentaCobrarTipoRel): void
    {
        $this->cuentaCobrarTipoRel = $cuentaCobrarTipoRel;
    }

    /**
     * @return mixed
     */
    public function getCuentaCobrarAplicacionRel()
    {
        return $this->cuentaCobrarAplicacionRel;
    }

    /**
     * @param mixed $cuentaCobrarAplicacionRel
     */
    public function setCuentaCobrarAplicacionRel($cuentaCobrarAplicacionRel): void
    {
        $this->cuentaCobrarAplicacionRel = $cuentaCobrarAplicacionRel;
    }

    /**
     * @return array
     */
    public function getInfoLog(): array
    {
        return $this->infoLog;
    }

    /**
     * @param array $infoLog
     */
    public function setInfoLog(array $infoLog): void
    {
        $this->infoLog = $infoLog;
    }

    /**
     * @return mixed
     */
    public function getCodigoAnticipoDetallePk()
    {
        return $this->codigoAnticipoDetallePk;
    }

    /**
     * @param mixed $codigoAnticipoDetallePk
     */
    public function setCodigoAnticipoDetallePk($codigoAnticipoDetallePk): void
    {
        $this->codigoAnticipoDetallePk = $codigoAnticipoDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoAnticipoFk()
    {
        return $this->codigoAnticipoFk;
    }

    /**
     * @param mixed $codigoAnticipoFk
     */
    public function setCodigoAnticipoFk($codigoAnticipoFk): void
    {
        $this->codigoAnticipoFk = $codigoAnticipoFk;
    }

    /**
     * @return mixed
     */
    public function getAnticipoRel()
    {
        return $this->anticipoRel;
    }

    /**
     * @param mixed $anticipoRel
     */
    public function setAnticipoRel($anticipoRel): void
    {
        $this->anticipoRel = $anticipoRel;
    }



}
