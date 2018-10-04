<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuPagoRepository")
 */
class RhuPago
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoPk;

    /**
     * @ORM\Column(name="codigo_pago_tipo_fk", type="integer", nullable=false)
     */
    private $codigoPagoTipoFk;

    /**
     * @ORM\Column(name="codigo_periodo_pago_fk", type="integer", nullable=true)
     */
    private $codigoPeriodoPagoFk;

    /**
     * @ORM\Column(name="numero", options={"default" : 0}, type="integer", nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="codigo_programacion_pago_fk", type="integer", nullable=true)
     */
    private $codigoProgramacionPagoFk;

    /**
     * @ORM\Column(name="codigo_programacion_pago_detalle_fk", type="integer", nullable=true)
     */
    private $codigoProgramacionPagoDetalleFk;

    /**
     * @ORM\Column(name="codigo_vacacion_fk", type="integer", nullable=true)
     */
    private $codigoVacacionFk;

    /**
     * @ORM\Column(name="codigo_liquidacion_fk", type="integer", nullable=true)
     */
    private $codigoLiquidacionFk;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="fecha_desde_pago", type="date", nullable=true)
     */
    private $fechaDesdePago;

    /**
     * @ORM\Column(name="fecha_hasta_pago", type="date", nullable=true)
     */
    private $fechaHastaPago;

    /**
     * @ORM\Column(name="vr_salario", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrSalario = 0;

    /**
     * Es el salario corerspondiente a los dias * VrDia
     * @ORM\Column(name="vr_salario_periodo", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrSalarioPeriodo = 0;

    /**
     * Es el salario que tenia el empleado cuando se genero el pago
     * @ORM\Column(name="vr_salario_empleado", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrSalarioEmpleado = 0;

    /**
     * @ORM\Column(name="vr_devengado", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrDevengado = 0;

    /**
     * @ORM\Column(name="vr_deducciones", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrDeducciones = 0;

    /**
     * @ORM\Column(name="vr_adicional_tiempo", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrAdicionalTiempo = 0;

    /**
     * @ORM\Column(name="vr_adicional_valor", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrAdicionalValor = 0;

    /**
     * @ORM\Column(name="vr_adicional_valor_no_prestacional", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrAdicionalValorNoPrestasional = 0;

    /**
     * @ORM\Column(name="vr_adicional_cotizacion", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrAdicionalCotizacion = 0;

    /**
     * @ORM\Column(name="vr_auxilio_transporte", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrAuxilioTransporte = 0;

    /**
     * @ORM\Column(name="vr_auxilio_transporte_cotizacion", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrAuxilioTransporteCotizacion = 0;

    /**
     * @ORM\Column(name="vr_neto", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrNeto = 0;

    /**
     * @ORM\Column(name="vr_bruto", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrBruto = 0;

    /**
     * @ORM\Column(name="vr_costo", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrCosto = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_cotizacion", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrIngresoBaseCotizacion = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrIngresoBasePrestacion = 0;

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */
    private $codigoCentroCostoFk;

    /**
     * @ORM\Column(name="estado_cobrado", options={"default" : false}, type="boolean", nullable=true)
     */
    private $estadoCobrado = false;

    /**
     * @ORM\Column(name="estado_pagado", options={"default" : false}, type="boolean", nullable=true)
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
     * @ORM\Column(name="estado_pagado_banco", options={"default" : false}, type="boolean", nullable=true)
     */
    private $estadoPagadoBanco = false;

    /**
     * @ORM\Column(name="estado_contabilizado", options={"default" : false}, type="boolean", nullable=true)
     */
    private $estadoContabilizado = false;

    /**
     * @ORM\Column(name="archivo_exportado_banco", options={"default" : false}, type="boolean", nullable=true)
     */
    private $archivoExportadoBanco = false;

    /**
     * @ORM\Column(name="dias_periodo", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasPeriodo = 0;

    /**
     * @ORM\Column(name="dias_laborados", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasLaborados = 0;

    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="dias_ausentismo", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasAusentismo = 0;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="codigo_soporte_pago_fk", type="integer", nullable=true)
     */
    private $codigoSoportePagoFk;

    /**
     * @ORM\Column(name="vr_extra", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrExtra = 0;

    /**
     * @ORM\Column(name="pago_nomina_manual_ajuste", options={"default" : false}, type="boolean", nullable=true)
     */
    private $pagoNominaManualAjuste = false;

    /**
     * @ORM\Column(name="vr_adicional_prestacional", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrAdicionalPrestacional = 0;

    /**
     * @ORM\Column(name="vr_adicional_no_prestacional", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrAdicionalNoPrestacional = 0;

    /**
     * @ORM\Column(name="codigo_entidad_salud_fk", type="integer", nullable=true)
     */
    private $codigoEntidadSaludFk;

    /**
     * @ORM\Column(name="codigo_entidad_pension_fk", type="integer", nullable=true)
     */
    private $codigoEntidadPensionFk;

    /**
     * @ORM\Column(name="fecha_pago", type="date", nullable=true)
     */
    private $fechaPago;

    /**
     * @return mixed
     */
    public function getCodigoPagoPk()
    {
        return $this->codigoPagoPk;
    }

    /**
     * @param mixed $codigoPagoPk
     */
    public function setCodigoPagoPk($codigoPagoPk): void
    {
        $this->codigoPagoPk = $codigoPagoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPagoTipoFk()
    {
        return $this->codigoPagoTipoFk;
    }

    /**
     * @param mixed $codigoPagoTipoFk
     */
    public function setCodigoPagoTipoFk($codigoPagoTipoFk): void
    {
        $this->codigoPagoTipoFk = $codigoPagoTipoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPeriodoPagoFk()
    {
        return $this->codigoPeriodoPagoFk;
    }

    /**
     * @param mixed $codigoPeriodoPagoFk
     */
    public function setCodigoPeriodoPagoFk($codigoPeriodoPagoFk): void
    {
        $this->codigoPeriodoPagoFk = $codigoPeriodoPagoFk;
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
    public function getCodigoProgramacionPagoFk()
    {
        return $this->codigoProgramacionPagoFk;
    }

    /**
     * @param mixed $codigoProgramacionPagoFk
     */
    public function setCodigoProgramacionPagoFk($codigoProgramacionPagoFk): void
    {
        $this->codigoProgramacionPagoFk = $codigoProgramacionPagoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoProgramacionPagoDetalleFk()
    {
        return $this->codigoProgramacionPagoDetalleFk;
    }

    /**
     * @param mixed $codigoProgramacionPagoDetalleFk
     */
    public function setCodigoProgramacionPagoDetalleFk($codigoProgramacionPagoDetalleFk): void
    {
        $this->codigoProgramacionPagoDetalleFk = $codigoProgramacionPagoDetalleFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoVacacionFk()
    {
        return $this->codigoVacacionFk;
    }

    /**
     * @param mixed $codigoVacacionFk
     */
    public function setCodigoVacacionFk($codigoVacacionFk): void
    {
        $this->codigoVacacionFk = $codigoVacacionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoLiquidacionFk()
    {
        return $this->codigoLiquidacionFk;
    }

    /**
     * @param mixed $codigoLiquidacionFk
     */
    public function setCodigoLiquidacionFk($codigoLiquidacionFk): void
    {
        $this->codigoLiquidacionFk = $codigoLiquidacionFk;
    }

    /**
     * @return mixed
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * @param mixed $fechaDesde
     */
    public function setFechaDesde($fechaDesde): void
    {
        $this->fechaDesde = $fechaDesde;
    }

    /**
     * @return mixed
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * @param mixed $fechaHasta
     */
    public function setFechaHasta($fechaHasta): void
    {
        $this->fechaHasta = $fechaHasta;
    }

    /**
     * @return mixed
     */
    public function getFechaDesdePago()
    {
        return $this->fechaDesdePago;
    }

    /**
     * @param mixed $fechaDesdePago
     */
    public function setFechaDesdePago($fechaDesdePago): void
    {
        $this->fechaDesdePago = $fechaDesdePago;
    }

    /**
     * @return mixed
     */
    public function getFechaHastaPago()
    {
        return $this->fechaHastaPago;
    }

    /**
     * @param mixed $fechaHastaPago
     */
    public function setFechaHastaPago($fechaHastaPago): void
    {
        $this->fechaHastaPago = $fechaHastaPago;
    }

    /**
     * @return mixed
     */
    public function getVrSalario()
    {
        return $this->vrSalario;
    }

    /**
     * @param mixed $vrSalario
     */
    public function setVrSalario($vrSalario): void
    {
        $this->vrSalario = $vrSalario;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioPeriodo()
    {
        return $this->vrSalarioPeriodo;
    }

    /**
     * @param mixed $vrSalarioPeriodo
     */
    public function setVrSalarioPeriodo($vrSalarioPeriodo): void
    {
        $this->vrSalarioPeriodo = $vrSalarioPeriodo;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioEmpleado()
    {
        return $this->vrSalarioEmpleado;
    }

    /**
     * @param mixed $vrSalarioEmpleado
     */
    public function setVrSalarioEmpleado($vrSalarioEmpleado): void
    {
        $this->vrSalarioEmpleado = $vrSalarioEmpleado;
    }

    /**
     * @return mixed
     */
    public function getVrDevengado()
    {
        return $this->vrDevengado;
    }

    /**
     * @param mixed $vrDevengado
     */
    public function setVrDevengado($vrDevengado): void
    {
        $this->vrDevengado = $vrDevengado;
    }

    /**
     * @return mixed
     */
    public function getVrDeducciones()
    {
        return $this->vrDeducciones;
    }

    /**
     * @param mixed $vrDeducciones
     */
    public function setVrDeducciones($vrDeducciones): void
    {
        $this->vrDeducciones = $vrDeducciones;
    }

    /**
     * @return mixed
     */
    public function getVrAdicionalTiempo()
    {
        return $this->vrAdicionalTiempo;
    }

    /**
     * @param mixed $vrAdicionalTiempo
     */
    public function setVrAdicionalTiempo($vrAdicionalTiempo): void
    {
        $this->vrAdicionalTiempo = $vrAdicionalTiempo;
    }

    /**
     * @return mixed
     */
    public function getVrAdicionalValor()
    {
        return $this->vrAdicionalValor;
    }

    /**
     * @param mixed $vrAdicionalValor
     */
    public function setVrAdicionalValor($vrAdicionalValor): void
    {
        $this->vrAdicionalValor = $vrAdicionalValor;
    }

    /**
     * @return mixed
     */
    public function getVrAdicionalValorNoPrestasional()
    {
        return $this->vrAdicionalValorNoPrestasional;
    }

    /**
     * @param mixed $vrAdicionalValorNoPrestasional
     */
    public function setVrAdicionalValorNoPrestasional($vrAdicionalValorNoPrestasional): void
    {
        $this->vrAdicionalValorNoPrestasional = $vrAdicionalValorNoPrestasional;
    }

    /**
     * @return mixed
     */
    public function getVrAdicionalCotizacion()
    {
        return $this->vrAdicionalCotizacion;
    }

    /**
     * @param mixed $vrAdicionalCotizacion
     */
    public function setVrAdicionalCotizacion($vrAdicionalCotizacion): void
    {
        $this->vrAdicionalCotizacion = $vrAdicionalCotizacion;
    }

    /**
     * @return mixed
     */
    public function getVrAuxilioTransporte()
    {
        return $this->vrAuxilioTransporte;
    }

    /**
     * @param mixed $vrAuxilioTransporte
     */
    public function setVrAuxilioTransporte($vrAuxilioTransporte): void
    {
        $this->vrAuxilioTransporte = $vrAuxilioTransporte;
    }

    /**
     * @return mixed
     */
    public function getVrAuxilioTransporteCotizacion()
    {
        return $this->vrAuxilioTransporteCotizacion;
    }

    /**
     * @param mixed $vrAuxilioTransporteCotizacion
     */
    public function setVrAuxilioTransporteCotizacion($vrAuxilioTransporteCotizacion): void
    {
        $this->vrAuxilioTransporteCotizacion = $vrAuxilioTransporteCotizacion;
    }

    /**
     * @return mixed
     */
    public function getVrNeto()
    {
        return $this->vrNeto;
    }

    /**
     * @param mixed $vrNeto
     */
    public function setVrNeto($vrNeto): void
    {
        $this->vrNeto = $vrNeto;
    }

    /**
     * @return mixed
     */
    public function getVrBruto()
    {
        return $this->vrBruto;
    }

    /**
     * @param mixed $vrBruto
     */
    public function setVrBruto($vrBruto): void
    {
        $this->vrBruto = $vrBruto;
    }

    /**
     * @return mixed
     */
    public function getVrCosto()
    {
        return $this->vrCosto;
    }

    /**
     * @param mixed $vrCosto
     */
    public function setVrCosto($vrCosto): void
    {
        $this->vrCosto = $vrCosto;
    }

    /**
     * @return mixed
     */
    public function getVrIngresoBaseCotizacion()
    {
        return $this->vrIngresoBaseCotizacion;
    }

    /**
     * @param mixed $vrIngresoBaseCotizacion
     */
    public function setVrIngresoBaseCotizacion($vrIngresoBaseCotizacion): void
    {
        $this->vrIngresoBaseCotizacion = $vrIngresoBaseCotizacion;
    }

    /**
     * @return mixed
     */
    public function getVrIngresoBasePrestacion()
    {
        return $this->vrIngresoBasePrestacion;
    }

    /**
     * @param mixed $vrIngresoBasePrestacion
     */
    public function setVrIngresoBasePrestacion($vrIngresoBasePrestacion): void
    {
        $this->vrIngresoBasePrestacion = $vrIngresoBasePrestacion;
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
    public function getEstadoCobrado()
    {
        return $this->estadoCobrado;
    }

    /**
     * @param mixed $estadoCobrado
     */
    public function setEstadoCobrado($estadoCobrado): void
    {
        $this->estadoCobrado = $estadoCobrado;
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
    public function getEstadoPagadoBanco()
    {
        return $this->estadoPagadoBanco;
    }

    /**
     * @param mixed $estadoPagadoBanco
     */
    public function setEstadoPagadoBanco($estadoPagadoBanco): void
    {
        $this->estadoPagadoBanco = $estadoPagadoBanco;
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
    public function getArchivoExportadoBanco()
    {
        return $this->archivoExportadoBanco;
    }

    /**
     * @param mixed $archivoExportadoBanco
     */
    public function setArchivoExportadoBanco($archivoExportadoBanco): void
    {
        $this->archivoExportadoBanco = $archivoExportadoBanco;
    }

    /**
     * @return mixed
     */
    public function getDiasPeriodo()
    {
        return $this->diasPeriodo;
    }

    /**
     * @param mixed $diasPeriodo
     */
    public function setDiasPeriodo($diasPeriodo): void
    {
        $this->diasPeriodo = $diasPeriodo;
    }

    /**
     * @return mixed
     */
    public function getDiasLaborados()
    {
        return $this->diasLaborados;
    }

    /**
     * @param mixed $diasLaborados
     */
    public function setDiasLaborados($diasLaborados): void
    {
        $this->diasLaborados = $diasLaborados;
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
    public function getDiasAusentismo()
    {
        return $this->diasAusentismo;
    }

    /**
     * @param mixed $diasAusentismo
     */
    public function setDiasAusentismo($diasAusentismo): void
    {
        $this->diasAusentismo = $diasAusentismo;
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
    public function getCodigoSoportePagoFk()
    {
        return $this->codigoSoportePagoFk;
    }

    /**
     * @param mixed $codigoSoportePagoFk
     */
    public function setCodigoSoportePagoFk($codigoSoportePagoFk): void
    {
        $this->codigoSoportePagoFk = $codigoSoportePagoFk;
    }

    /**
     * @return mixed
     */
    public function getVrExtra()
    {
        return $this->vrExtra;
    }

    /**
     * @param mixed $vrExtra
     */
    public function setVrExtra($vrExtra): void
    {
        $this->vrExtra = $vrExtra;
    }

    /**
     * @return mixed
     */
    public function getPagoNominaManualAjuste()
    {
        return $this->pagoNominaManualAjuste;
    }

    /**
     * @param mixed $pagoNominaManualAjuste
     */
    public function setPagoNominaManualAjuste($pagoNominaManualAjuste): void
    {
        $this->pagoNominaManualAjuste = $pagoNominaManualAjuste;
    }

    /**
     * @return mixed
     */
    public function getVrAdicionalPrestacional()
    {
        return $this->vrAdicionalPrestacional;
    }

    /**
     * @param mixed $vrAdicionalPrestacional
     */
    public function setVrAdicionalPrestacional($vrAdicionalPrestacional): void
    {
        $this->vrAdicionalPrestacional = $vrAdicionalPrestacional;
    }

    /**
     * @return mixed
     */
    public function getVrAdicionalNoPrestacional()
    {
        return $this->vrAdicionalNoPrestacional;
    }

    /**
     * @param mixed $vrAdicionalNoPrestacional
     */
    public function setVrAdicionalNoPrestacional($vrAdicionalNoPrestacional): void
    {
        $this->vrAdicionalNoPrestacional = $vrAdicionalNoPrestacional;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadSaludFk()
    {
        return $this->codigoEntidadSaludFk;
    }

    /**
     * @param mixed $codigoEntidadSaludFk
     */
    public function setCodigoEntidadSaludFk($codigoEntidadSaludFk): void
    {
        $this->codigoEntidadSaludFk = $codigoEntidadSaludFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadPensionFk()
    {
        return $this->codigoEntidadPensionFk;
    }

    /**
     * @param mixed $codigoEntidadPensionFk
     */
    public function setCodigoEntidadPensionFk($codigoEntidadPensionFk): void
    {
        $this->codigoEntidadPensionFk = $codigoEntidadPensionFk;
    }

    /**
     * @return mixed
     */
    public function getFechaPago()
    {
        return $this->fechaPago;
    }

    /**
     * @param mixed $fechaPago
     */
    public function setFechaPago($fechaPago): void
    {
        $this->fechaPago = $fechaPago;
    }
}
