<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarCuentaCobrarRepository")
 */
class CarCuentaCobrar
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cuenta_cobrar_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoCuentaCobrarPk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_tipo_fk", type="integer", nullable=true)
     */
    private $codigoCuentaCobrarTipoFk;

    /**
     * @ORM\Column(name="codigo_factura", type="integer", nullable=true)
     */
    private $codigoFactura;

    /**
     * @ORM\Column(name="codigo_factura_inventario_fk", type="integer", nullable=true)
     */
    private $codigoFacturaInventarioFk;

    /**
     * @ORM\Column(name="numero_documento", type="string", length=30, nullable=true)
     */
    private $numeroDocumento;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_vence", type="date", nullable=true)
     */
    private $fechaVence;

    /**
     * @ORM\Column(name="soporte", type="string", length=30, nullable=true)
     */
    private $soporte;

    /**
     * @ORM\Column(name="plazo", type="string", length=10, nullable=true)
     */
    private $plazo;

    /**
     * @ORM\Column(name="valor_original", type="float")
     */
    private $valorOriginal = 0;

    /**
     * @ORM\Column(name="abono", type="float")
     */
    private $abono = 0;

    /**
     * @ORM\Column(name="saldo", type="float")
     */
    private $saldo = 0;

    /**
     * @ORM\Column(name="saldo_operado", type="float")
     */
    private $saldoOperado = 0;

    /**
     * @ORM\Column(name="afiliacion", type="boolean")
     */
    private $afiliacion = false;

    /**
     * @ORM\Column(name="operacion", type="integer")
     */
    private $operacion = 0;

    /**
     * @ORM\Column(name="subtotal", type="float")
     */
    private $subtotal = 0;

    /**
     * @ORM\Column(name="retencion_fuente", type="float")
     */
    private $retencion_fuente = 0;

    /**
     * @ORM\Column(name="retencion_iva", type="float")
     */
    private $retencion_iva = 0;

    /**
     * @ORM\Column(name="retencion_ica", type="float")
     */
    private $retencion_ica = 0;

    /**
     * @ORM\Column(name="total_neto", type="float")
     */
    private $total_neto = 0;

    /**
     * @ORM\Column(name="comentario", type="string", length=120, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="CarCliente", inversedBy="cuentaCobrarClientesRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * @return mixed
     */
    public function getCodigoCuentaCobrarPk()
    {
        return $this->codigoCuentaCobrarPk;
    }

    /**
     * @param mixed $codigoCuentaCobrarPk
     */
    public function setCodigoCuentaCobrarPk($codigoCuentaCobrarPk): void
    {
        $this->codigoCuentaCobrarPk = $codigoCuentaCobrarPk;
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
    public function getCodigoFactura()
    {
        return $this->codigoFactura;
    }

    /**
     * @param mixed $codigoFactura
     */
    public function setCodigoFactura($codigoFactura): void
    {
        $this->codigoFactura = $codigoFactura;
    }

    /**
     * @return mixed
     */
    public function getCodigoFacturaInventarioFk()
    {
        return $this->codigoFacturaInventarioFk;
    }

    /**
     * @param mixed $codigoFacturaInventarioFk
     */
    public function setCodigoFacturaInventarioFk($codigoFacturaInventarioFk): void
    {
        $this->codigoFacturaInventarioFk = $codigoFacturaInventarioFk;
    }

    /**
     * @return mixed
     */
    public function getNumeroDocumento()
    {
        return $this->numeroDocumento;
    }

    /**
     * @param mixed $numeroDocumento
     */
    public function setNumeroDocumento($numeroDocumento): void
    {
        $this->numeroDocumento = $numeroDocumento;
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
    public function getPlazo()
    {
        return $this->plazo;
    }

    /**
     * @param mixed $plazo
     */
    public function setPlazo($plazo): void
    {
        $this->plazo = $plazo;
    }

    /**
     * @return mixed
     */
    public function getValorOriginal()
    {
        return $this->valorOriginal;
    }

    /**
     * @param mixed $valorOriginal
     */
    public function setValorOriginal($valorOriginal): void
    {
        $this->valorOriginal = $valorOriginal;
    }

    /**
     * @return mixed
     */
    public function getAbono()
    {
        return $this->abono;
    }

    /**
     * @param mixed $abono
     */
    public function setAbono($abono): void
    {
        $this->abono = $abono;
    }

    /**
     * @return mixed
     */
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * @param mixed $saldo
     */
    public function setSaldo($saldo): void
    {
        $this->saldo = $saldo;
    }

    /**
     * @return mixed
     */
    public function getSaldoOperado()
    {
        return $this->saldoOperado;
    }

    /**
     * @param mixed $saldoOperado
     */
    public function setSaldoOperado($saldoOperado): void
    {
        $this->saldoOperado = $saldoOperado;
    }

    /**
     * @return mixed
     */
    public function getAfiliacion()
    {
        return $this->afiliacion;
    }

    /**
     * @param mixed $afiliacion
     */
    public function setAfiliacion($afiliacion): void
    {
        $this->afiliacion = $afiliacion;
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
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * @param mixed $subtotal
     */
    public function setSubtotal($subtotal): void
    {
        $this->subtotal = $subtotal;
    }

    /**
     * @return mixed
     */
    public function getRetencionFuente()
    {
        return $this->retencion_fuente;
    }

    /**
     * @param mixed $retencion_fuente
     */
    public function setRetencionFuente($retencion_fuente): void
    {
        $this->retencion_fuente = $retencion_fuente;
    }

    /**
     * @return mixed
     */
    public function getRetencionIva()
    {
        return $this->retencion_iva;
    }

    /**
     * @param mixed $retencion_iva
     */
    public function setRetencionIva($retencion_iva): void
    {
        $this->retencion_iva = $retencion_iva;
    }

    /**
     * @return mixed
     */
    public function getRetencionIca()
    {
        return $this->retencion_ica;
    }

    /**
     * @param mixed $retencion_ica
     */
    public function setRetencionIca($retencion_ica): void
    {
        $this->retencion_ica = $retencion_ica;
    }

    /**
     * @return mixed
     */
    public function getTotalNeto()
    {
        return $this->total_neto;
    }

    /**
     * @param mixed $total_neto
     */
    public function setTotalNeto($total_neto): void
    {
        $this->total_neto = $total_neto;
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
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * @param mixed $clienteRel
     */
    public function setClienteRel($clienteRel): void
    {
        $this->clienteRel = $clienteRel;
    }

}
