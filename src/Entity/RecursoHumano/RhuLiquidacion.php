<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuLiquidacionRepository")
 */
class RhuLiquidacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_liquidacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLiquidacionPk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

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
     * @ORM\Column(name="codigo_grupo_fk", type="string", length=10, nullable=true)
     */
    private $codigoGrupoFk;

    /**
     * @ORM\Column(name="codigo_contrato_motivo_fk", type="string", length=10, nullable=true)
     */
    private $codigoContratoMotivoFk;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="vr_cesantias", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrCesantias = 0;

    /**
     * @ORM\Column(name="vr_intereses_cesantias", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrInteresesCesantias = 0;

    /**
     * @ORM\Column(name="vr_prima", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrPrima = 0;

    /**
     * @ORM\Column(name="vr_vacacion", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrVacacion = 0;

    /**
     * @ORM\Column(name="vr_indemnizacion", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrIndemnizacion = 0;

    /**
     * @ORM\Column(name="dias_cesantias", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasCesantias = 0;

    /**
     * @ORM\Column(name="dias_cesantias_ausentismo", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasCesantiasAusentismo = 0;

    /**
     * @ORM\Column(name="dias_vacacion", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasVacacion = 0;

    /**
     * @ORM\Column(name="dias_vacacion_ausentismo", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasVacacionAusentismo = 0;

    /**
     * @ORM\Column(name="dias_prima", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasPrima = 0;

    /**
     * @ORM\Column(name="dias_prima_ausentismo", options={"default" : 0}, type="integer", nullable=true)
     */
    private $diasPrimaAusentismo = 0;

    /**
     * @ORM\Column(name="fecha_ultimo_pago_prima", type="date", nullable=true)
     */
    private $fechaUltimoPagoPrima;

    /**
     * @ORM\Column(name="fecha_ultimo_pago_vacacion", type="date", nullable=true)
     */
    private $fechaUltimoPagoVacacion;

    /**
     * @ORM\Column(name="fecha_ultimo_pago_cesantias", type="date", nullable=true)
     */
    private $fechaUltimoPagoCesantias;

    /**
     * @ORM\Column(name="estado_autorizado", options={"default" : false}, type="boolean", nullable=true)
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", options={"default" : false}, type="boolean", nullable=true)
     */
    private $estadoAnulado = false;


}


