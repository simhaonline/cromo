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
     * @ORM\Column(name="codigo_credito_tipo_fk", type="integer", nullable=false)
     */
    private $codigoCreditoTipoFk;

    /**
     * @ORM\Column(name="codigo_credito_tipo_pago_fk", type="integer", nullable=false)
     */
    private $codigoCreditoTipoPagoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */
    private $codigoCentroCostoFk;

    /**
     * @ORM\Column(name="codigo_programacion_pago_detalle_fk", type="integer", nullable=true)
     */
    private $codigoProgramacionDetalleFk;

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
     * @ORM\Column(name="numero_cuotas",options={"default": 0}, type="integer")
     */
    private $numeroCuotas = 0;

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
     * @ORM\Column(name="seguro",options={"default": 0}, type="integer")
     */
    private $seguro = 0;

    /**
     * @ORM\Column(name="estado_suspendido",options={"default": false}, type="boolean", nullable=true)
     */
    private $estadoSuspendido = false;

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
     * @ORM\Column(name="nro_libranza", type="string", length=50, nullable=true)
     */
    private $numeroLibranza;

    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */
    private $codigoUsuario;

    /**
     * @ORM\Column(name="total_pagos",options={"default": 0}, type="float")
     */
    private $totalPagos = 0;

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
    public function getCodigoCreditoTipoPagoFk()
    {
        return $this->codigoCreditoTipoPagoFk;
    }

    /**
     * @param mixed $codigoCreditoTipoPagoFk
     */
    public function setCodigoCreditoTipoPagoFk($codigoCreditoTipoPagoFk): void
    {
        $this->codigoCreditoTipoPagoFk = $codigoCreditoTipoPagoFk;
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

    /**
     * @return mixed
     */
    public function getCodigoProgramacionDetalleFk()
    {
        return $this->codigoProgramacionDetalleFk;
    }

    /**
     * @param mixed $codigoProgramacionDetalleFk
     */
    public function setCodigoProgramacionDetalleFk($codigoProgramacionDetalleFk): void
    {
        $this->codigoProgramacionDetalleFk = $codigoProgramacionDetalleFk;
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
    public function getSeguro()
    {
        return $this->seguro;
    }

    /**
     * @param mixed $seguro
     */
    public function setSeguro($seguro): void
    {
        $this->seguro = $seguro;
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
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * @param mixed $codigoUsuario
     */
    public function setCodigoUsuario($codigoUsuario): void
    {
        $this->codigoUsuario = $codigoUsuario;
    }

    /**
     * @return mixed
     */
    public function getTotalPagos()
    {
        return $this->totalPagos;
    }

    /**
     * @param mixed $totalPagos
     */
    public function setTotalPagos($totalPagos): void
    {
        $this->totalPagos = $totalPagos;
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


}
