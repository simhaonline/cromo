<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuProvisionDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuProvisionDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_provision_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProvisionPk;

    /**
     * @ORM\Column(name="codigo_provision_periodo_fk", type="integer", nullable=false)
     */
    private $codigoProvisionPeriodoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */
    private $anio;

    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */
    private $mes;

    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="vr_pension", type="float")
     */
    private $vrPension = 0;

    /**
     * @ORM\Column(name="vr_salud", type="float")
     */
    private $vrSalud = 0;

    /**
     * @ORM\Column(name="vr_riesgos", type="float")
     */
    private $vrRiesgos = 0;

    /**
     * @ORM\Column(name="vr_caja", type="float")
     */
    private $vrCaja = 0;

    /**
     * @ORM\Column(name="vr_sena", type="float")
     */
    private $vrSena = 0;

    /**
     * @ORM\Column(name="vr_icbf", type="float")
     */
    private $vrIcbf = 0;

    /**
     * @ORM\Column(name="vr_cesantias", type="float")
     */
    private $vrCesantias = 0;

    /**
     * @ORM\Column(name="vr_intereses_cesantias", type="float")
     */
    private $vrInteresesCesantias = 0;

    /**
     * @ORM\Column(name="vr_vacaciones", type="float")
     */
    private $vrVacaciones = 0;

    /**
     * @ORM\Column(name="vr_primas", type="float")
     */
    private $vrPrimas = 0;

    /**
     * @ORM\Column(name="vr_indemnizacion", type="float")
     */
    private $vrIndemnizacion = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_cotizacion", type="float")
     */
    private $vrIngresoBaseCotizacion = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion", type="float")
     */
    private $vrIngresoBasePrestacion = 0;

    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $vrSalario = 0;

    /**
     * @ORM\Column(name="vr_salud_aporte", type="float", nullable=true)
     */
    private $vrSaludAporte = 0;

    /**
     * @ORM\Column(name="vr_salud_descuento", type="float", nullable=true)
     */
    private $vrSaludDescuento = 0;

    /**
     * @ORM\Column(name="vr_salud_mayor_descuento", type="float", nullable=true)
     */
    private $vrSaludMayorDescuento = 0;

    /**
     * @ORM\Column(name="vr_pension_aporte", type="float", nullable=true)
     */
    private $vrPensionAporte = 0;

    /**
     * @ORM\Column(name="vr_pension_descuento", type="float", nullable=true)
     */
    private $vrPensionDescuento = 0;

    /**
     * @ORM\Column(name="vr_pension_mayor_descuento", type="float", nullable=true)
     */
    private $vrPensionMayorDescuento = 0;

    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean")
     */
    private $estadoContabilizado = 0;

    /**
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */
    private $estadoCerrado = 0;
}
