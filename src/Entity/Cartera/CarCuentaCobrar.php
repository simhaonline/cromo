<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarCuentaCobrarRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class CarCuentaCobrar
{
    public $infoLog = [
        "primaryKey" => "codigoCuentaCobrarPk",
        "todos"     => true,
    ];
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
     * @ORM\Column(name="codigo_asesor_fk", type="integer", nullable=true)
     */
    private $codigoAsesorFk;

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoCuentaCobrarTipoFk;

    /**
     * @ORM\Column(name="modulo", type="string", length=3, nullable=true)
     */
    private $modulo;

    /**
     * @ORM\Column(name="codigo_documento", type="integer", nullable=true)
     */
    private $codigoDocumento;

    /**
     * @ORM\Column(name="numero_documento", type="string", length=30, nullable=true)
     */
    private $numeroDocumento;

    /**
     * @ORM\Column(name="numero_referencia", type="string", length=30, nullable=true)
     */
    private $numeroReferencia;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_vence", type="date", nullable=true)
     */
    private $fechaVence;

    /**
     * @ORM\Column(name="soporte", type="string", length=300, nullable=true)
     */
    private $soporte;

    /**
     * @ORM\Column(name="plazo", type="integer", nullable=true, options={"default" : 0})
     */
    private $plazo = 0;

    /**
     * @ORM\Column(name="vr_subtotal", type="float", nullable=true, options={"default" : 0})
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="vr_iva", type="float", nullable=true, options={"default" : 0})
     */
    private $vrIva = 0;

    /**
     * @ORM\Column(name="vr_total", type="float", nullable=true, options={"default" : 0})
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="vr_abono", type="float", nullable=true, options={"default" : 0})
     */
    private $vrAbono = 0;

    /**
     * @ORM\Column(name="vr_saldo_original", type="float", nullable=true, options={"default" : 0})
     */
    private $vrSaldoOriginal = 0;

    /**
     * @ORM\Column(name="vr_saldo", type="float", nullable=true, options={"default" : 0})
     */
    private $vrSaldo = 0;

    /**
     * @ORM\Column(name="vr_saldo_operado", type="float", nullable=true, options={"default" : 0})
     */
    private $vrSaldoOperado = 0;

    /**
     * @ORM\Column(name="operacion", type="integer", nullable=true, options={"default" : 0})
     */
    private $operacion = 0;

    /**
     * @ORM\Column(name="vr_retencion_fuente", type="float", nullable=true, options={"default" : 0})
     */
    private $vrRetencionFuente = 0;

    /**
     * @ORM\Column(name="vr_retencion_iva", type="float", nullable=true, options={"default" : 0})
     */
    private $vrRetencionIva = 0;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="dias_vencimiento", type="integer", nullable=true, options={"default" : 0})
     */
    private $diasVencimiento = 0;

    /**
     * @ORM\Column(name="rango", type="integer", nullable=true, options={"default" : 0})
     */
    private $rango = 0;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cartera\CarCuentaCobrarTipo", inversedBy="cuentasCobrarCuentaCobrarTipoRel")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_tipo_fk", referencedColumnName="codigo_cuenta_cobrar_tipo_pk")
     */
    protected $cuentaCobrarTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="CarCliente", inversedBy="cuentaCobrarClientesRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * @ORM\OneToMany(targetEntity="CarReciboDetalle", mappedBy="cuentaCobrarRel")
     */
    protected $recibosDetallesCuentaCobrarRel;

    /**
     * @ORM\OneToMany(targetEntity="CarReciboDetalle", mappedBy="cuentaCobrarAplicacionRel")
     */
    protected $recibosDetallesCuentaCobrarAplicacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenAsesor", inversedBy="cuentasCobrarAsesorRel")
     * @ORM\JoinColumn(name="codigo_asesor_fk", referencedColumnName="codigo_asesor_pk")

     */
    protected $asesorRel;

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
    public function getCodigoDocumento()
    {
        return $this->codigoDocumento;
    }

    /**
     * @param mixed $codigoDocumento
     */
    public function setCodigoDocumento($codigoDocumento): void
    {
        $this->codigoDocumento = $codigoDocumento;
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
    public function getVrAbono()
    {
        return $this->vrAbono;
    }

    /**
     * @param mixed $vrAbono
     */
    public function setVrAbono($vrAbono): void
    {
        $this->vrAbono = $vrAbono;
    }

    /**
     * @return mixed
     */
    public function getVrSaldo()
    {
        return $this->vrSaldo;
    }

    /**
     * @param mixed $vrSaldo
     */
    public function setVrSaldo($vrSaldo): void
    {
        $this->vrSaldo = $vrSaldo;
    }

    /**
     * @return mixed
     */
    public function getVrSaldoOperado()
    {
        return $this->vrSaldoOperado;
    }

    /**
     * @param mixed $vrSaldoOperado
     */
    public function setVrSaldoOperado($vrSaldoOperado): void
    {
        $this->vrSaldoOperado = $vrSaldoOperado;
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

    /**
     * @return mixed
     */
    public function getRecibosDetallesCuentaCobrarRel()
    {
        return $this->recibosDetallesCuentaCobrarRel;
    }

    /**
     * @param mixed $recibosDetallesCuentaCobrarRel
     */
    public function setRecibosDetallesCuentaCobrarRel($recibosDetallesCuentaCobrarRel): void
    {
        $this->recibosDetallesCuentaCobrarRel = $recibosDetallesCuentaCobrarRel;
    }

    /**
     * @return mixed
     */
    public function getRecibosDetallesCuentaCobrarAplicacionRel()
    {
        return $this->recibosDetallesCuentaCobrarAplicacionRel;
    }

    /**
     * @param mixed $recibosDetallesCuentaCobrarAplicacionRel
     */
    public function setRecibosDetallesCuentaCobrarAplicacionRel($recibosDetallesCuentaCobrarAplicacionRel): void
    {
        $this->recibosDetallesCuentaCobrarAplicacionRel = $recibosDetallesCuentaCobrarAplicacionRel;
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
    public function setNumeroReferencia($numeroReferencia): void
    {
        $this->numeroReferencia = $numeroReferencia;
    }

    /**
     * @return mixed
     */
    public function getDiasVencimiento()
    {
        return $this->diasVencimiento;
    }

    /**
     * @param mixed $diasVencimiento
     */
    public function setDiasVencimiento($diasVencimiento): void
    {
        $this->diasVencimiento = $diasVencimiento;
    }

    /**
     * @return mixed
     */
    public function getRango()
    {
        return $this->rango;
    }

    /**
     * @param mixed $rango
     */
    public function setRango($rango): void
    {
        $this->rango = $rango;
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
    public function getCodigoAsesorFk()
    {
        return $this->codigoAsesorFk;
    }

    /**
     * @param mixed $codigoAsesorFk
     */
    public function setCodigoAsesorFk($codigoAsesorFk): void
    {
        $this->codigoAsesorFk = $codigoAsesorFk;
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
    public function setAsesorRel($asesorRel): void
    {
        $this->asesorRel = $asesorRel;
    }

    /**
     * @return mixed
     */
    public function getVrSaldoOriginal()
    {
        return $this->vrSaldoOriginal;
    }

    /**
     * @param mixed $vrSaldoOriginal
     */
    public function setVrSaldoOriginal( $vrSaldoOriginal ): void
    {
        $this->vrSaldoOriginal = $vrSaldoOriginal;
    }




}
