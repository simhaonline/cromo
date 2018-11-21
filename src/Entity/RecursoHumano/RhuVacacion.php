<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuVacacionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuVacacion
{
    public $infoLog = [
        "primaryKey" => "codigoVacacionPk",
        "todos"     => true,
    ];
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
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="codigo_grupo_fk", type="string", length=10, nullable=true)
     */
    private $codigoGrupoFk;

    /**
     * @ORM\Column(name="fecha", type="date",nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_contabilidad", type="date", nullable=true)
     */
    private $fechaContabilidad;

    /**
     * @ORM\Column(name="numero", options={"default": 0},type="integer",nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="fecha_desde_periodo", type="date",nullable=true)
     */
    private $fechaDesdePeriodo;

    /**
     * @ORM\Column(name="fecha_hasta_periodo", type="date",nullable=true)
     */
    private $fechaHastaPeriodo;

    /**
     * @ORM\Column(name="fecha_desde_disfrute", type="date",nullable=true)
     */
    private $fechaDesdeDisfrute;

    /**
     * @ORM\Column(name="fecha_hasta_disfrute", type="date",nullable=true)
     */
    private $fechaHastaDisfrute;

    /**
     * @ORM\Column(name="fecha_inicio_labor", type="date", nullable=true)
     */
    private $fechaInicioLabor;

    /**
     * @ORM\Column(name="vr_salud",options={"default": 0}, type="float",nullable=true)
     */
    private $vrSalud = 0;

    /**
     * @ORM\Column(name="vr_pension",options={"default": 0}, type="float",nullable=true)
     */
    private $vrPension = 0;

    /**
     * @ORM\Column(name="vr_fondo_solidaridad",options={"default": 0}, type="float", nullable=true)
     */
    private $vrFondoSolidaridad = 0;

    /**
     * @ORM\Column(name="vr_ibc", options={"default": 0}, type="float",nullable=true)
     */
    private $vrIbc = 0;

    /**
     * @ORM\Column(name="vr_deduccion",options={"default": 0}, type="float",nullable=true)
     */
    private $vrDeduccion = 0;

    /**
     * @ORM\Column(name="vr_bonificacion",options={"default": 0}, type="float",nullable=true)
     */
    private $vrBonificacion = 0;

    /**
     * @ORM\Column(name="vr_valor",options={"default": 0}, type="float",nullable=true)
     */
    private $vrValor = 0;

    /**
     * @ORM\Column(name="vr_disfrute",options={"default": 0}, type="float", nullable=true)
     */
    private $vrDisfrute = 0;

    /**
     * @ORM\Column(name="vr_dinero",options={"default": 0}, type="float", nullable=true)
     */
    private $vrDinero = 0;

    /**
     * @ORM\Column(name="vr_total",options={"default": 0}, type="float", nullable=true)
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="dias",options={"default": 0}, type="integer",nullable=true)
     */
    private $dias = 0;

    /**
     * @ORM\Column(name="dias_disfrutados", options={"default": 0}, type="integer",nullable=true)
     */
    private $diasDisfrutados = 0;

    /**
     * @ORM\Column(name="dias_ausentismo", options={"default": 0}, type="integer", nullable=true)
     */
    private $diasAusentismo = 0;

    /**
     * @ORM\Column(name="dias_pagados", options={"default": 0}, type="integer",nullable=true)
     */
    private $diasPagados = 0;

    /**
     * @ORM\Column(name="dias_disfrutados_reales", options={"default": 0}, type="integer",nullable=true)
     */
    private $diasDisfrutadosReales = 0;

    /**
     * @ORM\Column(name="dias_periodo", options={"default": 0}, type="integer",nullable=true)
     */
    private $diasPeriodo = 0;

    /**
     * @ORM\Column(name="meses_periodo", options={"default": 0}, type="float",nullable=true)
     */
    private $mesesPeriodo = 0;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="vr_salario_actual", options={"default": 0}, type="float",nullable=true)
     */
    private $vrSalarioActual = 0;

    /**
     * @ORM\Column(name="vr_salario_promedio", options={"default": 0}, type="float",nullable=true)
     */
    private $vrSalarioPromedio = 0;

    /**
     * @ORM\Column(name="vr_salario_promedio_propuesto", options={"default": 0}, type="float",nullable=true)
     * @Assert\NotBlank(
     *     message="El campo no puede estar vacio"
     * )
     */
    private $vrSalarioPromedioPropuesto = 0;

    /**
     * @ORM\Column(name="vr_disfrute_propuesto", options={"default": 0}, type="float", nullable=true)
     */
    private $vrDisfrutePropuesto = 0;


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
     * @ORM\Column(name="vr_bruto", options={"default": 0}, type="float",nullable=true)
     */
    private $vrBruto = 0;

    /**
     * @ORM\Column(name="estado_pago_generado", options={"default": false}, type="boolean",nullable=true)
     */
    private $estadoPagoGenerado = false;

    /**
     * @ORM\Column(name="estado_pago_banco", options={"default": false}, type="boolean",nullable=true)
     */
    private $estadoPagoBanco = false;

    /**
     * @ORM\Column(name="estado_contabilizado", options={"default": false}, type="boolean",nullable=true)
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
     * @ORM\Column(name="estado_anulado", options={"default": false}, type="boolean",nullable=true)
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="estado_pagado", options={"default": false}, type="boolean",nullable=true)
     */
    private $estadoPagado = false;

    /**
     * @ORM\Column(name="estado_liquidado", options={"default": false}, type="boolean", nullable=true)
     */
    private $estadoLiquidado = false;

    /**
     * @ORM\Column(name="usuario", type="string", length=25, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="vr_recargo_nocturno_inicial", options={"default": 0}, type="float",nullable=true)
     */
    private $vrRecargoNocturnoInicial = 0;

    /**
     * @ORM\Column(name="vr_recargo_nocturno", options={"default": 0}, type="float",nullable=true)
     */
    private $vrRecargoNocturno = 0;

    /**
     * @ORM\Column(name="vr_promedio_recargo_nocturno", options={"default": 0}, type="float",nullable=true)
     */
    private $vrPromedioRecargoNocturno = 0;

    /**
     * @ORM\Column(name="vr_ibc_promedio", options={"default": 0}, type="float",nullable=true)
     */
    private $vrIbcPromedio = 0;

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="vacacionesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="vacacionesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuGrupo", inversedBy="vacacionesGrupoRel")
     * @ORM\JoinColumn(name="codigo_grupo_fk", referencedColumnName="codigo_grupo_pk")
     */
    protected $grupoRel;

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
    public function getVrValor()
    {
        return $this->vrValor;
    }

    /**
     * @param mixed $vrValor
     */
    public function setVrValor($vrValor): void
    {
        $this->vrValor = $vrValor;
    }

    /**
     * @return mixed
     */
    public function getVrDisfrute()
    {
        return $this->vrDisfrute;
    }

    /**
     * @param mixed $vrDisfrute
     */
    public function setVrDisfrute($vrDisfrute): void
    {
        $this->vrDisfrute = $vrDisfrute;
    }

    /**
     * @return mixed
     */
    public function getVrDinero()
    {
        return $this->vrDinero;
    }

    /**
     * @param mixed $vrDinero
     */
    public function setVrDinero($vrDinero): void
    {
        $this->vrDinero = $vrDinero;
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
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * @param mixed $dias
     */
    public function setDias($dias): void
    {
        $this->dias = $dias;
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
    public function getVrDisfrutePropuesto()
    {
        return $this->vrDisfrutePropuesto;
    }

    /**
     * @param mixed $vrDisfrutePropuesto
     */
    public function setVrDisfrutePropuesto($vrDisfrutePropuesto): void
    {
        $this->vrDisfrutePropuesto = $vrDisfrutePropuesto;
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
}
