<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCreditoRepository")
 */
class RhuCredito
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_credito_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCreditoPk;

    /**
     * @ORM\Column(name="codigo_credito_tipo_fk", type="string",length=10, nullable=true)
     */
    private $codigoCreditoTipoFk;

    /**
     * @ORM\Column(name="codigo_credito_pago_fk", type="string", length=10, nullable=false)
     */
    private $codigoCreditoPagoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="codigo_grupo_fk", type="string", length=10, nullable=true)
     */
    private $codigoGrupoFk;

    /**
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_inicio", type="date")
     */
    private $fechaInicio;

    /**
     * @ORM\Column(name="fecha_finalizacion", type="date", nullable=true)
     */
    private $fechaFinalizacion;

    /**
     * @ORM\Column(name="fecha_credito", type="date", nullable=true)
     */
    private $fechaCredito;

    /**
     * @ORM\Column(name="vr_inicial",options={"default": 0}, type="float")
     */
    private $vrInicial = 0;

    /**
     * @ORM\Column(name="vr_pagar",options={"default": 0}, type="float")
     */
    private $vrPagar = 0;

    /**
     * @ORM\Column(name="vr_cuota",options={"default": 0}, type="float")
     */
    private $vrCuota = 0;

    /**
     * @ORM\Column(name="vr_cuota_prima",options={"default": 0}, type="float")
     */
    private $vrCuotaPrima = 0;

    /**
     * @ORM\Column(name="vr_cuota_temporal",options={"default": 0}, type="float")
     */
    private $vrCuotaTemporal = 0;

    /**
     * @ORM\Column(name="saldo",options={"default": 0}, type="float")
     */
    private $saldo = 0;

    /**
     * @ORM\Column(name="saldo_total",options={"default": 0}, type="float")
     */
    private $saldoTotal = 0;

    /**
     * @ORM\Column(name="numero_cuotas",options={"default": 0}, type="integer", nullable=true)
     */
    private $numeroCuotas = 0;

    /**
     * @ORM\Column(name="numero_libranza", type="string", length=10,nullable=true)
     */
    private $numeroLibranza;

    /**
     * @ORM\Column(name="numero_cuota_actual",options={"default": 0}, type="integer")
     */
    private $numeroCuotaActual = 0;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     * @Assert\Length(
     *     max=200,
     *     maxMessage="El comentario no puede contener mas de 200 caracteres"
     * )
     */
    private $comentarios;

    /**
     * @ORM\Column(name="vr_seguro",options={"default": 0}, type="integer")
     */
    private $vrSeguro = 0;

    /**
     * @ORM\Column(name="estado_suspendido",options={"default": false}, type="boolean", nullable=true)
     */
    private $estadoSuspendido = false;

    /**
     * @ORM\Column(name="inactivo_periodo",options={"default": false}, type="boolean", nullable=true)
     */
    private $inactivoPeriodo = false;

    /**
     * @ORM\Column(name="estado_pagado",options={"default": false}, type="boolean", nullable=true)
     */
    private $estadoPagado = false;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="vr_abonos",options={"default": 0}, type="float")
     */
    private $vrAbonos = 0;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="vr_total_pagos",options={"default": 0}, type="float")
     */
    private $vrTotalPagos = 0;

    /**
     * @ORM\Column(name="vr_valor_cuota",options={"default": 0}, type="float")
     */
    private $vrValorCuota = 0;

    /**
     * @ORM\Column(name="validar_cuotas",options={"default": false}, type="boolean")
     */
    private $validarCuotas = false;

    /**
     * @ORM\Column(name="aplicar_cuota_prima",options={"default": false}, type="boolean")
     */
    private $aplicarCuotaPrima = false;

    /**
     * @ORM\Column(name="aplicar_cuota_cesantia",options={"default": false}, type="boolean")
     */
    private $aplicarCuotaCesantia = false;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCreditoTipo", inversedBy="creditosCreditoTipoRel")
     * @ORM\JoinColumn(name="codigo_credito_tipo_fk", referencedColumnName="codigo_credito_tipo_pk")
     */
    protected $creditoTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="creditosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="creditosContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuGrupo", inversedBy="creditosGrupoRel")
     * @ORM\JoinColumn(name="codigo_grupo_fk", referencedColumnName="codigo_grupo_pk")
     */
    protected $grupoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCreditoPago", inversedBy="creditosCreditoPagoRel")
     * @ORM\JoinColumn(name="codigo_credito_pago_fk", referencedColumnName="codigo_credito_pago_pk")
     */
    protected $creditoPagoRel;

    /**
     * @return mixed
     */
    public function getCodigoCreditoPk()
    {
        return $this->codigoCreditoPk;
    }

    /**
     * @param mixed $codigoCreditoPk
     */
    public function setCodigoCreditoPk($codigoCreditoPk): void
    {
        $this->codigoCreditoPk = $codigoCreditoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCreditoTipoFk()
    {
        return $this->codigoCreditoTipoFk;
    }

    /**
     * @param mixed $codigoCreditoTipoFk
     */
    public function setCodigoCreditoTipoFk($codigoCreditoTipoFk): void
    {
        $this->codigoCreditoTipoFk = $codigoCreditoTipoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCreditoPagoFk()
    {
        return $this->codigoCreditoPagoFk;
    }

    /**
     * @param mixed $codigoCreditoPagoFk
     */
    public function setCodigoCreditoPagoFk($codigoCreditoPagoFk): void
    {
        $this->codigoCreditoPagoFk = $codigoCreditoPagoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEmpleadoFk()
    {
        return $this->codigoEmpleadoFk;
    }

    /**
     * @param mixed $codigoEmpleadoFk
     */
    public function setCodigoEmpleadoFk($codigoEmpleadoFk): void
    {
        $this->codigoEmpleadoFk = $codigoEmpleadoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoContratoFk()
    {
        return $this->codigoContratoFk;
    }

    /**
     * @param mixed $codigoContratoFk
     */
    public function setCodigoContratoFk($codigoContratoFk): void
    {
        $this->codigoContratoFk = $codigoContratoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoGrupoFk()
    {
        return $this->codigoGrupoFk;
    }

    /**
     * @param mixed $codigoGrupoFk
     */
    public function setCodigoGrupoFk($codigoGrupoFk): void
    {
        $this->codigoGrupoFk = $codigoGrupoFk;
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
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * @param mixed $fechaInicio
     */
    public function setFechaInicio($fechaInicio): void
    {
        $this->fechaInicio = $fechaInicio;
    }

    /**
     * @return mixed
     */
    public function getFechaFinalizacion()
    {
        return $this->fechaFinalizacion;
    }

    /**
     * @param mixed $fechaFinalizacion
     */
    public function setFechaFinalizacion($fechaFinalizacion): void
    {
        $this->fechaFinalizacion = $fechaFinalizacion;
    }

    /**
     * @return mixed
     */
    public function getFechaCredito()
    {
        return $this->fechaCredito;
    }

    /**
     * @param mixed $fechaCredito
     */
    public function setFechaCredito($fechaCredito): void
    {
        $this->fechaCredito = $fechaCredito;
    }

    /**
     * @return mixed
     */
    public function getVrInicial()
    {
        return $this->vrInicial;
    }

    /**
     * @param mixed $vrInicial
     */
    public function setVrInicial($vrInicial): void
    {
        $this->vrInicial = $vrInicial;
    }

    /**
     * @return mixed
     */
    public function getVrPagar()
    {
        return $this->vrPagar;
    }

    /**
     * @param mixed $vrPagar
     */
    public function setVrPagar($vrPagar): void
    {
        $this->vrPagar = $vrPagar;
    }

    /**
     * @return mixed
     */
    public function getVrCuota()
    {
        return $this->vrCuota;
    }

    /**
     * @param mixed $vrCuota
     */
    public function setVrCuota($vrCuota): void
    {
        $this->vrCuota = $vrCuota;
    }

    /**
     * @return mixed
     */
    public function getVrCuotaPrima()
    {
        return $this->vrCuotaPrima;
    }

    /**
     * @param mixed $vrCuotaPrima
     */
    public function setVrCuotaPrima($vrCuotaPrima): void
    {
        $this->vrCuotaPrima = $vrCuotaPrima;
    }

    /**
     * @return mixed
     */
    public function getVrCuotaTemporal()
    {
        return $this->vrCuotaTemporal;
    }

    /**
     * @param mixed $vrCuotaTemporal
     */
    public function setVrCuotaTemporal($vrCuotaTemporal): void
    {
        $this->vrCuotaTemporal = $vrCuotaTemporal;
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
    public function getSaldoTotal()
    {
        return $this->saldoTotal;
    }

    /**
     * @param mixed $saldoTotal
     */
    public function setSaldoTotal($saldoTotal): void
    {
        $this->saldoTotal = $saldoTotal;
    }

    /**
     * @return mixed
     */
    public function getNumeroCuotas()
    {
        return $this->numeroCuotas;
    }

    /**
     * @param mixed $numeroCuotas
     */
    public function setNumeroCuotas($numeroCuotas): void
    {
        $this->numeroCuotas = $numeroCuotas;
    }

    /**
     * @return mixed
     */
    public function getNumeroLibranza()
    {
        return $this->numeroLibranza;
    }

    /**
     * @param mixed $numeroLibranza
     */
    public function setNumeroLibranza($numeroLibranza): void
    {
        $this->numeroLibranza = $numeroLibranza;
    }

    /**
     * @return mixed
     */
    public function getNumeroCuotaActual()
    {
        return $this->numeroCuotaActual;
    }

    /**
     * @param mixed $numeroCuotaActual
     */
    public function setNumeroCuotaActual($numeroCuotaActual): void
    {
        $this->numeroCuotaActual = $numeroCuotaActual;
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
    public function getVrSeguro()
    {
        return $this->vrSeguro;
    }

    /**
     * @param mixed $vrSeguro
     */
    public function setVrSeguro($vrSeguro): void
    {
        $this->vrSeguro = $vrSeguro;
    }

    /**
     * @return mixed
     */
    public function getEstadoSuspendido()
    {
        return $this->estadoSuspendido;
    }

    /**
     * @param mixed $estadoSuspendido
     */
    public function setEstadoSuspendido($estadoSuspendido): void
    {
        $this->estadoSuspendido = $estadoSuspendido;
    }

    /**
     * @return mixed
     */
    public function getInactivoPeriodo()
    {
        return $this->inactivoPeriodo;
    }

    /**
     * @param mixed $inactivoPeriodo
     */
    public function setInactivoPeriodo($inactivoPeriodo): void
    {
        $this->inactivoPeriodo = $inactivoPeriodo;
    }

    /**
     * @return mixed
     */
    public function getEstadoPagado()
    {
        return $this->estadoPagado;
    }

    /**
     * @param mixed $estadoPagado
     */
    public function setEstadoPagado($estadoPagado): void
    {
        $this->estadoPagado = $estadoPagado;
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
    public function getVrAbonos()
    {
        return $this->vrAbonos;
    }

    /**
     * @param mixed $vrAbonos
     */
    public function setVrAbonos($vrAbonos): void
    {
        $this->vrAbonos = $vrAbonos;
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
    public function getVrTotalPagos()
    {
        return $this->vrTotalPagos;
    }

    /**
     * @param mixed $vrTotalPagos
     */
    public function setVrTotalPagos($vrTotalPagos): void
    {
        $this->vrTotalPagos = $vrTotalPagos;
    }

    /**
     * @return mixed
     */
    public function getVrValorCuota()
    {
        return $this->vrValorCuota;
    }

    /**
     * @param mixed $vrValorCuota
     */
    public function setVrValorCuota($vrValorCuota): void
    {
        $this->vrValorCuota = $vrValorCuota;
    }

    /**
     * @return mixed
     */
    public function getValidarCuotas()
    {
        return $this->validarCuotas;
    }

    /**
     * @param mixed $validarCuotas
     */
    public function setValidarCuotas($validarCuotas): void
    {
        $this->validarCuotas = $validarCuotas;
    }

    /**
     * @return mixed
     */
    public function getAplicarCuotaPrima()
    {
        return $this->aplicarCuotaPrima;
    }

    /**
     * @param mixed $aplicarCuotaPrima
     */
    public function setAplicarCuotaPrima($aplicarCuotaPrima): void
    {
        $this->aplicarCuotaPrima = $aplicarCuotaPrima;
    }

    /**
     * @return mixed
     */
    public function getAplicarCuotaCesantia()
    {
        return $this->aplicarCuotaCesantia;
    }

    /**
     * @param mixed $aplicarCuotaCesantia
     */
    public function setAplicarCuotaCesantia($aplicarCuotaCesantia): void
    {
        $this->aplicarCuotaCesantia = $aplicarCuotaCesantia;
    }

    /**
     * @return mixed
     */
    public function getCreditoTipoRel()
    {
        return $this->creditoTipoRel;
    }

    /**
     * @param mixed $creditoTipoRel
     */
    public function setCreditoTipoRel($creditoTipoRel): void
    {
        $this->creditoTipoRel = $creditoTipoRel;
    }

    /**
     * @return mixed
     */
    public function getEmpleadoRel()
    {
        return $this->empleadoRel;
    }

    /**
     * @param mixed $empleadoRel
     */
    public function setEmpleadoRel($empleadoRel): void
    {
        $this->empleadoRel = $empleadoRel;
    }

    /**
     * @return mixed
     */
    public function getContratoRel()
    {
        return $this->contratoRel;
    }

    /**
     * @param mixed $contratoRel
     */
    public function setContratoRel($contratoRel): void
    {
        $this->contratoRel = $contratoRel;
    }

    /**
     * @return mixed
     */
    public function getGrupoRel()
    {
        return $this->grupoRel;
    }

    /**
     * @param mixed $grupoRel
     */
    public function setGrupoRel($grupoRel): void
    {
        $this->grupoRel = $grupoRel;
    }

    /**
     * @return mixed
     */
    public function getCreditoPagoRel()
    {
        return $this->creditoPagoRel;
    }

    /**
     * @param mixed $creditoPagoRel
     */
    public function setCreditoPagoRel($creditoPagoRel): void
    {
        $this->creditoPagoRel = $creditoPagoRel;
    }
}
