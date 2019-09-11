<?php

namespace App\Entity\Tesoreria;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Tesoreria\TesCuentaPagarRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TesCuentaPagar
{
    public $infoLog = [
        "primaryKey" => "codigoCuentaPagarPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="codigo_cuenta_pagar_pk",type="integer")
     */
    private $codigoCuentaPagarPk;

    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */
    private $codigoTerceroFk;

    /**
     * @ORM\Column(name="codigo_cuenta_pagar_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoCuentaPagarTipoFk;

    /**
     * @ORM\Column(name="codigo_banco_fk", type="string", length=10, nullable=true)
     */
    private $codigoBancoFk;

    /**
     * @ORM\Column(name="cuenta", type="string", length=80, nullable=true)
     */
    private $cuenta;

    /**
     * @ORM\Column(name="codigo_documento", type="integer", nullable=true)
     */
    private $codigoDocumento;

    /**
     * @ORM\Column(name="numero_documento", type="string", length=30, nullable=true)
     */
    private $numeroDocumento;

    /**
     * @ORM\Column(name="modulo" ,type="string", length=3, nullable=true)
     */
    private $modulo;

    /**
     * @ORM\Column(name="modelo" ,type="string", length=80, nullable=true)
     */
    private $modelo;

    /**
     * @ORM\Column(name="numero_referencia", type="string", length=30, nullable=true)
     */
    private $numeroReferencia;

    /**
     * @ORM\Column(name="soporte" ,type="string" , nullable=true)
     */
    private $soporte;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_vence", type="date", nullable=true)
     */
    private $fechaVence;

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
     * @ORM\Column(name="estado_verificado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoVerificado = false;

    /**
     * @ORM\Column(name="saldo_inicial", type="boolean", nullable=true, options={"default" : false})
     */
    private $saldoInicial = false;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Tesoreria\TesTercero" ,inversedBy="cuentasPagarTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk" , referencedColumnName="codigo_tercero_pk")
     */
    private $terceroRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tesoreria\TesCuentaPagarTipo" , inversedBy="cuentasPagarCuentaPagarTipoRel")
     * @ORM\JoinColumn(name="codigo_cuenta_pagar_tipo_fk" , referencedColumnName="codigo_cuenta_pagar_tipo_pk")
     */
    private $cuentaPagarTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenBanco", inversedBy="cuentasPagarBancoRel")
     * @ORM\JoinColumn(name="codigo_banco_fk",referencedColumnName="codigo_banco_pk")
     */
    protected $bancoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tesoreria\TesEgresoDetalle" , mappedBy="cuentaPagarRel")
     */
    private $egresosDetalleCuentasPagarRel;

    /**
     * @return mixed
     */
    public function getCodigoCuentaPagarPk()
    {
        return $this->codigoCuentaPagarPk;
    }

    /**
     * @param mixed $codigoCuentaPagarPk
     */
    public function setCodigoCuentaPagarPk($codigoCuentaPagarPk): void
    {
        $this->codigoCuentaPagarPk = $codigoCuentaPagarPk;
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
    public function setCodigoTerceroFk($codigoTerceroFk): void
    {
        $this->codigoTerceroFk = $codigoTerceroFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaPagarTipoFk()
    {
        return $this->codigoCuentaPagarTipoFk;
    }

    /**
     * @param mixed $codigoCuentaPagarTipoFk
     */
    public function setCodigoCuentaPagarTipoFk($codigoCuentaPagarTipoFk): void
    {
        $this->codigoCuentaPagarTipoFk = $codigoCuentaPagarTipoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoBancoFk()
    {
        return $this->codigoBancoFk;
    }

    /**
     * @param mixed $codigoBancoFk
     */
    public function setCodigoBancoFk($codigoBancoFk): void
    {
        $this->codigoBancoFk = $codigoBancoFk;
    }

    /**
     * @return mixed
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * @param mixed $cuenta
     */
    public function setCuenta($cuenta): void
    {
        $this->cuenta = $cuenta;
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
    public function getModelo()
    {
        return $this->modelo;
    }

    /**
     * @param mixed $modelo
     */
    public function setModelo($modelo): void
    {
        $this->modelo = $modelo;
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
    public function getVrSaldoOriginal()
    {
        return $this->vrSaldoOriginal;
    }

    /**
     * @param mixed $vrSaldoOriginal
     */
    public function setVrSaldoOriginal($vrSaldoOriginal): void
    {
        $this->vrSaldoOriginal = $vrSaldoOriginal;
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
    public function getEstadoVerificado()
    {
        return $this->estadoVerificado;
    }

    /**
     * @param mixed $estadoVerificado
     */
    public function setEstadoVerificado($estadoVerificado): void
    {
        $this->estadoVerificado = $estadoVerificado;
    }

    /**
     * @return mixed
     */
    public function getSaldoInicial()
    {
        return $this->saldoInicial;
    }

    /**
     * @param mixed $saldoInicial
     */
    public function setSaldoInicial($saldoInicial): void
    {
        $this->saldoInicial = $saldoInicial;
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
    public function getTerceroRel()
    {
        return $this->terceroRel;
    }

    /**
     * @param mixed $terceroRel
     */
    public function setTerceroRel($terceroRel): void
    {
        $this->terceroRel = $terceroRel;
    }

    /**
     * @return mixed
     */
    public function getCuentaPagarTipoRel()
    {
        return $this->cuentaPagarTipoRel;
    }

    /**
     * @param mixed $cuentaPagarTipoRel
     */
    public function setCuentaPagarTipoRel($cuentaPagarTipoRel): void
    {
        $this->cuentaPagarTipoRel = $cuentaPagarTipoRel;
    }

    /**
     * @return mixed
     */
    public function getBancoRel()
    {
        return $this->bancoRel;
    }

    /**
     * @param mixed $bancoRel
     */
    public function setBancoRel($bancoRel): void
    {
        $this->bancoRel = $bancoRel;
    }

    /**
     * @return mixed
     */
    public function getEgresosDetalleCuentasPagarRel()
    {
        return $this->egresosDetalleCuentasPagarRel;
    }

    /**
     * @param mixed $egresosDetalleCuentasPagarRel
     */
    public function setEgresosDetalleCuentasPagarRel($egresosDetalleCuentasPagarRel): void
    {
        $this->egresosDetalleCuentasPagarRel = $egresosDetalleCuentasPagarRel;
    }





}
