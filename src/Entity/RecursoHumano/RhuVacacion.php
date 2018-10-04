<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuVacacionRepository")
 */
class RhuVacacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_vacacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoVacacionPk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=false)
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_contabilidad", type="date", nullable=true)
     */
    private $fechaContabilidad;

    /**
     * @ORM\Column(name="numero",options={"default": 0},type="integer")
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="fecha_desde_periodo", type="date")
     */
    private $fechaDesdePeriodo;

    /**
     * @ORM\Column(name="fecha_hasta_periodo", type="date")
     */
    private $fechaHastaPeriodo;

    /**
     * @ORM\Column(name="fecha_desde_disfrute", type="date")
     */
    private $fechaDesdeDisfrute;

    /**
     * @ORM\Column(name="fecha_hasta_disfrute", type="date")
     */
    private $fechaHastaDisfrute;

    /**
     * @ORM\Column(name="fecha_inicio_labor", type="date", nullable=true)
     */
    private $fechaInicioLabor;

    /**
     * @ORM\Column(name="vr_salud",options={"default": 0}, type="float")
     */
    private $vrSalud = 0;

    /**
     * @ORM\Column(name="vr_pension",options={"default": 0}, type="float")
     */
    private $vrPension = 0;

    /**
     * @ORM\Column(name="vr_fondo_solidaridad",options={"default": 0}, type="float", nullable=true)
     */
    private $vrFondoSolidaridad = 0;

    /**
     * @ORM\Column(name="vr_ibc", options={"default": 0}, type="float")
     */
    private $vrIbc = 0;

    /**
     * @ORM\Column(name="vr_deduccion",options={"default": 0}, type="float")
     */
    private $vrDeduccion = 0;

    /**
     * @ORM\Column(name="vr_bonificacion",options={"default": 0}, type="float")
     */
    private $vrBonificacion = 0;

    /**
     * @ORM\Column(name="vr_vacacion",options={"default": 0}, type="float")
     */
    private $vrVacacion = 0;

    /**
     * @ORM\Column(name="vr_vacacion_disfrute",options={"default": 0}, type="float", nullable=true)
     */
    private $vrVacacionDisfrute = 0;

    /**
     * @ORM\Column(name="vr_vacacion_dinero",options={"default": 0}, type="float", nullable=true)
     */
    private $vrVacacionDinero = 0;

    /**
     * @ORM\Column(name="vr_vacacion_total",options={"default": 0}, type="float", nullable=true)
     */
    private $vrVacacionTotal = 0;

    /**
     * @ORM\Column(name="dias_vacaciones",options={"default": 0}, type="integer")
     */
    private $diasVacaciones = 0;

    /**
     * @ORM\Column(name="dias_disfrutados", options={"default": 0}, type="integer")
     */
    private $diasDisfrutados = 0;

    /**
     * @ORM\Column(name="dias_ausentismo", options={"default": 0}, type="integer", nullable=true)
     */
    private $diasAusentismo = 0;

    /**
     * @ORM\Column(name="dias_pagados", options={"default": 0}, type="integer")
     */
    private $diasPagados = 0;

    /**
     * @ORM\Column(name="dias_disfrutados_reales", options={"default": 0}, type="integer")
     */
    private $diasDisfrutadosReales = 0;

    /**
     * @ORM\Column(name="dias_periodo", options={"default": 0}, type="integer")
     */
    private $diasPeriodo = 0;

    /**
     * @ORM\Column(name="meses_periodo", options={"default": 0}, type="float")
     */
    private $mesesPeriodo = 0;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */
    private $codigoCentroCostoFk;

    /**
     * @ORM\Column(name="vr_salario_actual", options={"default": 0}, type="float")
     */
    private $vrSalarioActual = 0;

    /**
     * @ORM\Column(name="vr_salario_promedio", options={"default": 0}, type="float")
     */
    private $vrSalarioPromedio = 0;

    /**
     * @ORM\Column(name="vr_salario_promedio_propuesto", options={"default": 0}, type="float")
     * @Assert\NotBlank(
     *     message="El campo no puede estar vacio"
     * )
     */
    private $vrSalarioPromedioPropuesto = 0;

    /**
     * @ORM\Column(name="vr_vacacion_disfrute_propuesto", options={"default": 0}, type="float", nullable=true)
     */
    private $vrVacacionDisfrutePropuesto = 0;


    /**
     * @ORM\Column(name="vr_salario_promedio_propuesto_pagado", options={"default": 0}, type="float", nullable=true)
     */
    private $vrSalarioPromedioPropuestoPagado = 0;

    /**
     * @ORM\Column(name="vr_salud_propuesto", options={"default": 0}, type="float", nullable=true)
     */
    private $vrSaludPropuesto = 0;

    /**
     * @ORM\Column(name="vr_pension_propuesto", options={"default": 0}, type="float", nullable=true)
     */
    private $vrPensionPropuesto = 0;

    /**
     * @ORM\Column(name="dias_ausentismo_propuesto", options={"default": 0}, type="integer", nullable=true)
     */
    private $diasAusentismoPropuesto = 0;

    /**
     * @ORM\Column(name="vr_vacacion_bruto", options={"default": 0}, type="float")
     */
    private $vrVacacionBruto = 0;

    /**
     * @ORM\Column(name="estado_pago_generado", options={"default": false}, type="boolean")
     */
    private $estadoPagoGenerado = false;

    /**
     * @ORM\Column(name="estado_pago_banco", options={"default": false}, type="boolean")
     */
    private $estadoPagoBanco = false;

    /**
     * @ORM\Column(name="estado_contabilizado", options={"default": false}, type="boolean")
     */
    private $estadoContabilizado = false;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", options={"default": false}, type="boolean")
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="estado_pagado", options={"default": false}, type="boolean")
     */
    private $estadoPagado = false;

    /**
     * @ORM\Column(name="estado_liquidado", options={"default": false}, type="boolean", nullable=true)
     */
    private $estadoLiquidado = false;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="vr_recargo_nocturno_inicial", options={"default": 0}, type="float")
     */
    private $vrRecargoNocturnoInicial = 0;

    /**
     * @ORM\Column(name="vr_recargo_nocturno", options={"default": 0}, type="float")
     */
    private $vrRecargoNocturno = 0;

    /**
     * @ORM\Column(name="vr_promedio_recargo_nocturno", options={"default": 0}, type="float")
     */
    private $vrPromedioRecargoNocturno = 0;

    /**
     * @ORM\Column(name="vr_ibc_promedio", options={"default": 0}, type="float")
     */
    private $vrIbcPromedio = 0;

    /**
     * @return mixed
     */
    public function getCodigoVacacionPk()
    {
        return $this->codigoVacacionPk;
    }

    /**
     * @param mixed $codigoVacacionPk
     */
    public function setCodigoVacacionPk($codigoVacacionPk): void
    {
        $this->codigoVacacionPk = $codigoVacacionPk;
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
    public function getFechaContabilidad()
    {
        return $this->fechaContabilidad;
    }

    /**
     * @param mixed $fechaContabilidad
     */
    public function setFechaContabilidad($fechaContabilidad): void
    {
        $this->fechaContabilidad = $fechaContabilidad;
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
    public function getFechaDesdePeriodo()
    {
        return $this->fechaDesdePeriodo;
    }

    /**
     * @param mixed $fechaDesdePeriodo
     */
    public function setFechaDesdePeriodo($fechaDesdePeriodo): void
    {
        $this->fechaDesdePeriodo = $fechaDesdePeriodo;
    }

    /**
     * @return mixed
     */
    public function getFechaHastaPeriodo()
    {
        return $this->fechaHastaPeriodo;
    }

    /**
     * @param mixed $fechaHastaPeriodo
     */
    public function setFechaHastaPeriodo($fechaHastaPeriodo): void
    {
        $this->fechaHastaPeriodo = $fechaHastaPeriodo;
    }

    /**
     * @return mixed
     */
    public function getFechaDesdeDisfrute()
    {
        return $this->fechaDesdeDisfrute;
    }

    /**
     * @param mixed $fechaDesdeDisfrute
     */
    public function setFechaDesdeDisfrute($fechaDesdeDisfrute): void
    {
        $this->fechaDesdeDisfrute = $fechaDesdeDisfrute;
    }

    /**
     * @return mixed
     */
    public function getFechaHastaDisfrute()
    {
        return $this->fechaHastaDisfrute;
    }

    /**
     * @param mixed $fechaHastaDisfrute
     */
    public function setFechaHastaDisfrute($fechaHastaDisfrute): void
    {
        $this->fechaHastaDisfrute = $fechaHastaDisfrute;
    }

    /**
     * @return mixed
     */
    public function getFechaInicioLabor()
    {
        return $this->fechaInicioLabor;
    }

    /**
     * @param mixed $fechaInicioLabor
     */
    public function setFechaInicioLabor($fechaInicioLabor): void
    {
        $this->fechaInicioLabor = $fechaInicioLabor;
    }

    /**
     * @return mixed
     */
    public function getVrSalud()
    {
        return $this->vrSalud;
    }

    /**
     * @param mixed $vrSalud
     */
    public function setVrSalud($vrSalud): void
    {
        $this->vrSalud = $vrSalud;
    }

    /**
     * @return mixed
     */
    public function getVrPension()
    {
        return $this->vrPension;
    }

    /**
     * @param mixed $vrPension
     */
    public function setVrPension($vrPension): void
    {
        $this->vrPension = $vrPension;
    }

    /**
     * @return mixed
     */
    public function getVrFondoSolidaridad()
    {
        return $this->vrFondoSolidaridad;
    }

    /**
     * @param mixed $vrFondoSolidaridad
     */
    public function setVrFondoSolidaridad($vrFondoSolidaridad): void
    {
        $this->vrFondoSolidaridad = $vrFondoSolidaridad;
    }

    /**
     * @return mixed
     */
    public function getVrIbc()
    {
        return $this->vrIbc;
    }

    /**
     * @param mixed $vrIbc
     */
    public function setVrIbc($vrIbc): void
    {
        $this->vrIbc = $vrIbc;
    }

    /**
     * @return mixed
     */
    public function getVrDeduccion()
    {
        return $this->vrDeduccion;
    }

    /**
     * @param mixed $vrDeduccion
     */
    public function setVrDeduccion($vrDeduccion): void
    {
        $this->vrDeduccion = $vrDeduccion;
    }

    /**
     * @return mixed
     */
    public function getVrBonificacion()
    {
        return $this->vrBonificacion;
    }

    /**
     * @param mixed $vrBonificacion
     */
    public function setVrBonificacion($vrBonificacion): void
    {
        $this->vrBonificacion = $vrBonificacion;
    }

    /**
     * @return mixed
     */
    public function getVrVacacion()
    {
        return $this->vrVacacion;
    }

    /**
     * @param mixed $vrVacacion
     */
    public function setVrVacacion($vrVacacion): void
    {
        $this->vrVacacion = $vrVacacion;
    }

    /**
     * @return mixed
     */
    public function getVrVacacionDisfrute()
    {
        return $this->vrVacacionDisfrute;
    }

    /**
     * @param mixed $vrVacacionDisfrute
     */
    public function setVrVacacionDisfrute($vrVacacionDisfrute): void
    {
        $this->vrVacacionDisfrute = $vrVacacionDisfrute;
    }

    /**
     * @return mixed
     */
    public function getVrVacacionDinero()
    {
        return $this->vrVacacionDinero;
    }

    /**
     * @param mixed $vrVacacionDinero
     */
    public function setVrVacacionDinero($vrVacacionDinero): void
    {
        $this->vrVacacionDinero = $vrVacacionDinero;
    }

    /**
     * @return mixed
     */
    public function getVrVacacionTotal()
    {
        return $this->vrVacacionTotal;
    }

    /**
     * @param mixed $vrVacacionTotal
     */
    public function setVrVacacionTotal($vrVacacionTotal): void
    {
        $this->vrVacacionTotal = $vrVacacionTotal;
    }

    /**
     * @return mixed
     */
    public function getDiasVacaciones()
    {
        return $this->diasVacaciones;
    }

    /**
     * @param mixed $diasVacaciones
     */
    public function setDiasVacaciones($diasVacaciones): void
    {
        $this->diasVacaciones = $diasVacaciones;
    }

    /**
     * @return mixed
     */
    public function getDiasDisfrutados()
    {
        return $this->diasDisfrutados;
    }

    /**
     * @param mixed $diasDisfrutados
     */
    public function setDiasDisfrutados($diasDisfrutados): void
    {
        $this->diasDisfrutados = $diasDisfrutados;
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
    public function getDiasPagados()
    {
        return $this->diasPagados;
    }

    /**
     * @param mixed $diasPagados
     */
    public function setDiasPagados($diasPagados): void
    {
        $this->diasPagados = $diasPagados;
    }

    /**
     * @return mixed
     */
    public function getDiasDisfrutadosReales()
    {
        return $this->diasDisfrutadosReales;
    }

    /**
     * @param mixed $diasDisfrutadosReales
     */
    public function setDiasDisfrutadosReales($diasDisfrutadosReales): void
    {
        $this->diasDisfrutadosReales = $diasDisfrutadosReales;
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
    public function getMesesPeriodo()
    {
        return $this->mesesPeriodo;
    }

    /**
     * @param mixed $mesesPeriodo
     */
    public function setMesesPeriodo($mesesPeriodo): void
    {
        $this->mesesPeriodo = $mesesPeriodo;
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
    public function getVrSalarioActual()
    {
        return $this->vrSalarioActual;
    }

    /**
     * @param mixed $vrSalarioActual
     */
    public function setVrSalarioActual($vrSalarioActual): void
    {
        $this->vrSalarioActual = $vrSalarioActual;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioPromedio()
    {
        return $this->vrSalarioPromedio;
    }

    /**
     * @param mixed $vrSalarioPromedio
     */
    public function setVrSalarioPromedio($vrSalarioPromedio): void
    {
        $this->vrSalarioPromedio = $vrSalarioPromedio;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioPromedioPropuesto()
    {
        return $this->vrSalarioPromedioPropuesto;
    }

    /**
     * @param mixed $vrSalarioPromedioPropuesto
     */
    public function setVrSalarioPromedioPropuesto($vrSalarioPromedioPropuesto): void
    {
        $this->vrSalarioPromedioPropuesto = $vrSalarioPromedioPropuesto;
    }

    /**
     * @return mixed
     */
    public function getVrVacacionDisfrutePropuesto()
    {
        return $this->vrVacacionDisfrutePropuesto;
    }

    /**
     * @param mixed $vrVacacionDisfrutePropuesto
     */
    public function setVrVacacionDisfrutePropuesto($vrVacacionDisfrutePropuesto): void
    {
        $this->vrVacacionDisfrutePropuesto = $vrVacacionDisfrutePropuesto;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioPromedioPropuestoPagado()
    {
        return $this->vrSalarioPromedioPropuestoPagado;
    }

    /**
     * @param mixed $vrSalarioPromedioPropuestoPagado
     */
    public function setVrSalarioPromedioPropuestoPagado($vrSalarioPromedioPropuestoPagado): void
    {
        $this->vrSalarioPromedioPropuestoPagado = $vrSalarioPromedioPropuestoPagado;
    }

    /**
     * @return mixed
     */
    public function getVrSaludPropuesto()
    {
        return $this->vrSaludPropuesto;
    }

    /**
     * @param mixed $vrSaludPropuesto
     */
    public function setVrSaludPropuesto($vrSaludPropuesto): void
    {
        $this->vrSaludPropuesto = $vrSaludPropuesto;
    }

    /**
     * @return mixed
     */
    public function getVrPensionPropuesto()
    {
        return $this->vrPensionPropuesto;
    }

    /**
     * @param mixed $vrPensionPropuesto
     */
    public function setVrPensionPropuesto($vrPensionPropuesto): void
    {
        $this->vrPensionPropuesto = $vrPensionPropuesto;
    }

    /**
     * @return mixed
     */
    public function getDiasAusentismoPropuesto()
    {
        return $this->diasAusentismoPropuesto;
    }

    /**
     * @param mixed $diasAusentismoPropuesto
     */
    public function setDiasAusentismoPropuesto($diasAusentismoPropuesto): void
    {
        $this->diasAusentismoPropuesto = $diasAusentismoPropuesto;
    }

    /**
     * @return mixed
     */
    public function getVrVacacionBruto()
    {
        return $this->vrVacacionBruto;
    }

    /**
     * @param mixed $vrVacacionBruto
     */
    public function setVrVacacionBruto($vrVacacionBruto): void
    {
        $this->vrVacacionBruto = $vrVacacionBruto;
    }

    /**
     * @return mixed
     */
    public function getEstadoPagoGenerado()
    {
        return $this->estadoPagoGenerado;
    }

    /**
     * @param mixed $estadoPagoGenerado
     */
    public function setEstadoPagoGenerado($estadoPagoGenerado): void
    {
        $this->estadoPagoGenerado = $estadoPagoGenerado;
    }

    /**
     * @return mixed
     */
    public function getEstadoPagoBanco()
    {
        return $this->estadoPagoBanco;
    }

    /**
     * @param mixed $estadoPagoBanco
     */
    public function setEstadoPagoBanco($estadoPagoBanco): void
    {
        $this->estadoPagoBanco = $estadoPagoBanco;
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
    public function getEstadoLiquidado()
    {
        return $this->estadoLiquidado;
    }

    /**
     * @param mixed $estadoLiquidado
     */
    public function setEstadoLiquidado($estadoLiquidado): void
    {
        $this->estadoLiquidado = $estadoLiquidado;
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
    public function getVrRecargoNocturnoInicial()
    {
        return $this->vrRecargoNocturnoInicial;
    }

    /**
     * @param mixed $vrRecargoNocturnoInicial
     */
    public function setVrRecargoNocturnoInicial($vrRecargoNocturnoInicial): void
    {
        $this->vrRecargoNocturnoInicial = $vrRecargoNocturnoInicial;
    }

    /**
     * @return mixed
     */
    public function getVrRecargoNocturno()
    {
        return $this->vrRecargoNocturno;
    }

    /**
     * @param mixed $vrRecargoNocturno
     */
    public function setVrRecargoNocturno($vrRecargoNocturno): void
    {
        $this->vrRecargoNocturno = $vrRecargoNocturno;
    }

    /**
     * @return mixed
     */
    public function getVrPromedioRecargoNocturno()
    {
        return $this->vrPromedioRecargoNocturno;
    }

    /**
     * @param mixed $vrPromedioRecargoNocturno
     */
    public function setVrPromedioRecargoNocturno($vrPromedioRecargoNocturno): void
    {
        $this->vrPromedioRecargoNocturno = $vrPromedioRecargoNocturno;
    }

    /**
     * @return mixed
     */
    public function getVrIbcPromedio()
    {
        return $this->vrIbcPromedio;
    }

    /**
     * @param mixed $vrIbcPromedio
     */
    public function setVrIbcPromedio($vrIbcPromedio): void
    {
        $this->vrIbcPromedio = $vrIbcPromedio;
    }
}
