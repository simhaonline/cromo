<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuContratoRepository")
 */
class RhuContrato
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contrato_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoContratoPk;

    /**
     * @ORM\Column(name="codigo_contrato_tipo_fk", type="integer")
     */
    private $codigoContratoTipoFk;

    /**
     * @ORM\Column(name="codigo_contrato_clase_fk", type="integer", nullable=true)
     */
    private $codigoContratoClaseFk;

    /**
     * @ORM\Column(name="codigo_clasificacion_riesgo_fk", type="integer")
     */
    private $codigoClasificacionRiesgoFk;

    /**
     * @ORM\Column(name="codigo_contrato_motivo_fk", type="string", length=10, nullable=true)
     */
    private $codigoContratoMotivoFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_tiempo_fk", type="string", length=10, nullable=true)
     */
    private $codigoTiempoFk;

    /**
     * @ORM\Column(name="codigo_tipo_pension_fk", type="integer")
     */
    private $codigoTipoPensionFk;

    /**
     * @ORM\Column(name="codigo_tipo_salud_fk", type="integer", nullable=true)
     */
    private $codigoTipoSaludFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="numero", type="string", length=30, nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="codigo_cargo_fk", type="string", length=10)
     */
    private $codigoCargoFk;

    /**
     * @ORM\Column(name="cargo_descripcion", type="string", length=60, nullable=true)
     */
    private $cargoDescripcion;

    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $VrSalario = 0;

    /**
     * @ORM\Column(name="estado_terminado", type="boolean")
     */
    private $estadoActivo = 0;

    /**
     * @ORM\Column(name="comentario_terminacion", type="string", length=2000, nullable=true)
     */
    private $comentarioTerminacion;

    /**
     * @ORM\Column(name="codigo_grupo_fk", type="integer")
     */
    private $codigoGrupoFk;

    /**
     * @ORM\Column(name="fecha_ultimo_pago_cesantias", type="date", nullable=true)
     */
    private $fechaUltimoPagoCesantias;

    /**
     * @ORM\Column(name="fecha_ultimo_pago_vacaciones", type="date", nullable=true)
     */
    private $fechaUltimoPagoVacaciones;

    /**
     * @ORM\Column(name="fecha_ultimo_pago_primas", type="date", nullable=true)
     */
    private $fechaUltimoPagoPrimas;

    /**
     * @ORM\Column(name="fecha_ultimo_pago", type="date", nullable=true)
     */
    private $fechaUltimoPago;


    /**
     * @ORM\Column(name="codigo_tipo_cotizante_fk", type="integer", nullable=false)
     */
    private $codigoTipoCotizanteFk;

    /**
     * @ORM\Column(name="codigo_subtipo_cotizante_fk", type="integer", nullable=false)
     */
    private $codigoSubtipoCotizanteFk;

    /**
     * @ORM\Column(name="salario_integral", type="boolean")
     */
    private $salarioIntegral = false;

    /**
     * @ORM\Column(name="codigo_entidad_salud_fk", type="integer", nullable=true)
     */
    private $codigoEntidadSaludFk;

    /**
     * @ORM\Column(name="codigo_entidad_pension_fk", type="integer", nullable=true)
     */
    private $codigoEntidadPensionFk;

    /**
     * @ORM\Column(name="codigo_entidad_cesantia_fk", type="integer", nullable=true)
     */
    private $codigoEntidadCesantiaFk;

    /**
     * @ORM\Column(name="codigo_entidad_caja_fk", type="integer", nullable=true)
     */
    private $codigoEntidadCajaFk;

    /**
     * @ORM\Column(name="codigo_ciudad_contrato_fk", type="integer", nullable=true)
     */
    private $codigoCiudadContratoFk;

    /**
     * @ORM\Column(name="codigo_ciudad_labora_fk", type="integer", nullable=true)
     */
    private $codigoCiudadLaboraFk;

    /**
     * @ORM\Column(name="codigo_centro_trabajo_fk", type="integer", nullable=true)
     */
    private $codigoCentroTrabajoFk;

    /**
     * @ORM\Column(name="codigo_sucursal_fk", type="string", length=10, nullable=true)
     */
    private $codigoSucursalFk;

    /**
     * @ORM\Column(name="auxilio_transporte", type="boolean")
     */
    private $auxilioTransporte = false;

    /**
     * @ORM\Column(name="centro_costo_fijo", type="boolean", nullable=true)
     */
    private $centroCostoFijo = false;

    /**
     * @ORM\Column(name="centro_costo_distribuido", type="boolean", nullable=true)
     */
    private $centroCostoDistribuido = false;

    /**
     * @ORM\Column(name="centro_costo_determinado", type="boolean", nullable=true)
     */
    private $centroCostoDeterminado = false;

}
