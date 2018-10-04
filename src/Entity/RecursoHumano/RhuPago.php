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
     * @ORM\Column(name="codigo_periodo_fk", type="string", length=10, nullable=true)
     */
    private $codigoPeriodoFk;

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
     * @ORM\Column(name="codigo_grupo_fk", type="integer", nullable=true)
     */
    private $codigoGrupoFk;

    /**
     * @ORM\Column(name="codigo_programacion_fk", type="integer", nullable=true)
     */
    private $codigoProgramacionFk;

    /**
     * @ORM\Column(name="codigo_programacion_detalle_fk", type="integer", nullable=true)
     */
    private $codigoProgramacionDetalleFk;

    /**
     * @ORM\Column(name="codigo_vacacion_fk", type="integer", nullable=true)
     */
    private $codigoVacacionFk;

    /**
     * @ORM\Column(name="codigo_liquidacion_fk", type="integer", nullable=true)
     */
    private $codigoLiquidacionFk;

    /**
     * @ORM\Column(name="codigo_entidad_salud_fk", type="integer", nullable=true)
     */
    private $codigoEntidadSaludFk;

    /**
     * @ORM\Column(name="codigo_entidad_pension_fk", type="integer", nullable=true)
     */
    private $codigoEntidadPensionFk;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="fecha_desde_contrato", type="date", nullable=true)
     */
    private $fechaDesdeContrato;

    /**
     * @ORM\Column(name="fecha_hasta_contrato", type="date", nullable=true)
     */
    private $fechaHastaContrato;

    /**
     * Es el salario que tenia el contrato cuando se genero el pago
     * @ORM\Column(name="vr_salario_contrato", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrSalarioContrato = 0;

    /**
     * @ORM\Column(name="vr_devengado", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrDevengado = 0;

    /**
     * @ORM\Column(name="vr_deduccion", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrDeduccion = 0;

    /**
     * @ORM\Column(name="vr_auxilio_transporte", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrAuxilioTransporte = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_cotizacion", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrIngresoBaseCotizacion = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrIngresoBasePrestacion = 0;

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
     * @ORM\Column(name="comentario", type="string", length=500, nullable=true)
     */
    private $comentario;

}
