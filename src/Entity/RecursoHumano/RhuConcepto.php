<?php

namespace App\Entity\RecursoHumano;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuConceptoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoPagoConceptoPk"},message="Ya existe el código del pago concepto")
 */
class RhuConcepto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_concepto_pk", type="string", length=10)
     */
    private $codigoPagoConceptoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="compone_salario", type="boolean")
     */
    private $componeSalario = false;

    /**
     * @ORM\Column(name="compone_porcentaje", type="boolean")
     */
    private $componePorcentaje = false;

    /**
     * @ORM\Column(name="porcentaje_suplementario", type="float", nullable=true)
     */
    private $porcentajeSuplementario = 0;

    /**
     * @ORM\Column(name="compone_valor", type="boolean")
     */
    private $componeValor = false;

    /**
     * @ORM\Column(name="por_porcentaje", type="float")
     */
    private $porPorcentaje = 0;

    /**
     * @ORM\Column(name="por_porcentaje_tiempo_extra", type="float")
     */
    private $porPorcentajeTiempoExtra = 0;

    /**
     * @ORM\Column(name="porcentaje_vacaciones", type="float", nullable=true)
     */
    private $porcentajeVacaciones = 0;

    /**
     * @ORM\Column(name="aplica_porcentaje_vacacion_suplementario", type="boolean", nullable=true)
     */
    private $aplicaPorcentajeVacacionSuplementario = false;

    /**
     * @ORM\Column(name="prestacional", type="boolean")
     */
    private $prestacional = false;

    /**
     * @ORM\Column(name="genera_ingreso_base_prestacion", type="boolean")
     */
    private $generaIngresoBasePrestacion = false;

    /**
     * @ORM\Column(name="genera_ingreso_base_cotizacion", type="boolean")
     */
    private $generaIngresoBaseCotizacion = false;

    /**
     * @ORM\Column(name="operacion", type="integer")
     */
    private $operacion = 0;

    /**
     * @ORM\Column(name="concepto_adicion", type="boolean")
     */
    private $conceptoAdicion = false;

    /**
     * @ORM\Column(name="concepto_auxilio_transporte", type="boolean")
     */
    private $conceptoAuxilioTransporte = false;

    /**
     * @ORM\Column(name="concepto_incapacidad", type="boolean")
     */
    private $conceptoIncapacidad = false;

    /**
     * @ORM\Column(name="concepto_incapacidad_entidad", type="boolean", nullable=true)
     */
    private $conceptoIncapacidadEntidad = false;

    /**
     * @ORM\Column(name="concepto_pension", type="boolean")
     */
    private $conceptoPension = false;

    /**
     * @ORM\Column(name="concepto_salud", type="boolean")
     */
    private $conceptoSalud = false;

    /**
     * @ORM\Column(name="concepto_vacacion", type="boolean")
     */
    private $conceptoVacacion = false;

    /**
     * @ORM\Column(name="concepto_comision", type="boolean")
     */
    private $conceptoComision = false;

    /**
     * @ORM\Column(name="concepto_cesantia", type="boolean")
     */
    private $conceptoCesantia = false;

    /**
     * @ORM\Column(name="concepto_retencion", type="boolean")
     */
    private $conceptoRetencion = false;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaFk;

    /**
     * @ORM\Column(name="tipo_cuenta", type="bigint")
     */
    private $tipoCuenta = 1;

    /**
     * @ORM\Column(name="codigo_cuenta_operacion_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaOperacionFk;

    /**
     * @ORM\Column(name="tipo_cuenta_operacion", type="bigint", nullable=true)
     */
    private $tipoCuentaOperacion = 1;

    /**
     * @ORM\Column(name="codigo_cuenta_comercial_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaComercialFk;

    /**
     * @ORM\Column(name="tipo_cuenta_comercial", type="bigint", nullable=true)
     */
    private $tipoCuentaComercial = 1;

    /**
     * @ORM\Column(name="provision_indemnizacion", type="boolean")
     */
    private $provisionIndemnizacion = false;

    /**
     * @ORM\Column(name="provision_vacacion", type="boolean")
     */
    private $provisionVacacion = false;

    /**
     * 1=Bonificacion, 2=Descuento, 3=Comision
     * @ORM\Column(name="tipo_adicional", type="smallint")
     */
    private $tipoAdicional = 1;

    /**
     * @ORM\Column(name="codigo_interface", type="string", length=30, nullable=true)
     */
    private $codigoInterface;


    /**
     * @ORM\Column(name="numero_dian", type="integer", nullable=true)
     */
    private $numeroDian;

    /**
     * @ORM\Column(name="recargo_nocturno", type="boolean", nullable=true)
     */
    private $recargoNocturno = false;

    /**
     * @ORM\Column(name="recargo", type="boolean", nullable=true)
     */
    private $recargo = false;

    /**
     * @ORM\Column(name="hora_extra", type="boolean")
     */
    private $horaExtra = false;

    /**
     * @ORM\Column(name="ajuste_suplementario", type="boolean", nullable=true)
     */
    private $ajusteSuplementario = false;

    /**
     * @ORM\Column(name="vacacion_suplementario", type="boolean", nullable=true)
     */
    private $vacacionSuplementario = false;

    /**
     * @ORM\Column(name="concepto_licencia_entidad", type="boolean", nullable=true)
     */
    private $conceptoLicenciaEntidad = false;

    /**
     * @ORM\Column(name="numero_identificacion_tercero_contabilidad", type="string", length=20, nullable=true)
     */
    private $numeroIdentificacionTerceroContabilidad;

    /**
     * @ORM\Column(name="concepto_fondo_solidaridad_pensional", type="boolean")
     */
    private $conceptoFondoSolidaridadPensional = false;

    /**
     * @ORM\Column(name="ajuste_retencion", type="boolean", nullable=true)
     */
    private $ajusteRetencion = false;

    /**
     * @ORM\Column(name="ocultar_formato", type="boolean", nullable=true)
     */
    private $ocultarFormato = false;

    /**
     * @ORM\Column(name="contabilizar_empleado", type="boolean", nullable=true)
     */
    private $contabilizarEmpleado = false;

    /**
     * @ORM\Column(name="genera_costo_cliente", type="boolean", nullable=true)
     */
    private $generaCostoCliente = true;
}