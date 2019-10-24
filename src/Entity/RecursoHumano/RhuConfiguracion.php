<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="rhu_configuracion")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuConfiguracionRepository")
 */
class RhuConfiguracion
{
    public $infoLog = [
        "primaryKey" => "codigoConfiguracionPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_pk", type="integer")
     */
    private $codigoConfiguracionPk;

    /**
     * @ORM\Column(name="vr_salario_minimo", type="float",options={"default":0}, nullable=true)
     * @Assert\NotBlank(message="Este campo no puede estar vacío")
     */
    private $vrSalarioMinimo = 0;

    /**
     * @ORM\Column(name="codigo_concepto_auxilio_transporte_fk", type="string", length=10, nullable=true)
     */
    private $codigoConceptoAuxilioTransporteFk;

    /**
     * @ORM\Column(name="codigo_concepto_vacacion_fk", type="string", length=10, nullable=true)
     */
    private $codigoConceptoVacacionFk;

    /**
     * @ORM\Column(name="vr_auxilio_transporte", type="float", nullable=true)
     */
    private $vrAuxilioTransporte;

    /**
     * @ORM\Column(name="codigo_concepto_fondo_solidaridad_pension_fk", type="string", length=10, nullable=true)
     */
    private $codigoConceptoFondoSolidaridadPensionFk;

    /**
     * @ORM\Column(name="codigo_concepto_adicional_devengado_pactado_fk", type="string", length=10, nullable=true)
     */
    private $codigoConceptoAdicionalDevengadoPactadoFk;

    /**
     * @ORM\Column(name="auxilio_transporte_no_prestacional", type="boolean", nullable=true)
     */
    private $auxilioTransporteNoPrestacional = false;

    /**
     * @ORM\Column(name="descontar_ausentismos_de_licencias" , type="boolean" ,nullable=true)
     */
    private $descontarAusentismosDeLicencias = false;

    /**
     * @ORM\Column(name="vacaciones_base_descuento_ley_ibc_mes_anterior", type="boolean", nullable=true)
     */
    private $vacacionesBaseDescuentoLeyIbcMesAnterior = false;

    /**
     * @ORM\Column(name="vacaciones_recargo_nocturno_ultimo_anio", type="boolean", nullable=true)
     */
    private $vacacionesRecargoNocturnoUltimoAnio = false;

    /**
     * @ORM\Column(name="vacaciones_recargo_nocturno_ultimo_anio_especial", type="boolean", nullable=true)
     */
    private $vacacionesRecargoNocturnoUltimoAnioEspecial = false;

    /**
     * @ORM\Column(name="vacaciones_liquidar_recargo_nocturno_porcentaje_concepto", type="boolean", nullable=true)
     */
    private $vacacionesLiquidarRecargoNocturnoPorcentajeConcepto = false;

    /**
     * @ORM\Column(name="liquidar_prestaciones_salario_suplementario", type="boolean", options={"default":false}, nullable=true)
     */
    private $liquidarPrestacionesSalarioSuplementario = false;

    /**
     * @ORM\Column(name="liquidar_vacaciones_promedio_ultimo_anio", type="boolean", options={"default":false}, nullable=true)
     */
    private $liquidarVacacionesPromedioUltimoAnio = false;

    /**
     * @ORM\Column(name="liquidar_licencias_ibc_mes_anterior", type="boolean", options={"default":false}, nullable=true)
     */
    private $liquidarLicenciasIbcMesAnterior = false;

    /**
     * @ORM\Column(name="validar_fecha_ultimo_pago_liquidacion", type="boolean", nullable=true)
     */
    private $validarFechaUltimoPagoLiquidacion = false;

    /**
     * @ORM\Column(name="liquidar_cesantia_anio_anterior", type="boolean", nullable=true)
     */
    private $liquidarCesantiaAnioAnterior = false;

    /**
     * @ORM\Column(name="eliminar_ausentismo", type="boolean")
     */
    private $eliminarAusentismo = false;

    /**
     * @ORM\Column(name="genera_porcentaje_liquidacion", type="boolean", nullable=true)
     */
    private $generaPorcentajeLiquidacion = false;

    /**
     * @ORM\Column(name="eliminar_ausentismo_cesantia", type="boolean", nullable=true)
     */
    private $eliminarAusentismoCesantia = false;

    /**
     * @ORM\Column(name="eliminar_ausentismo_primas", type="boolean", nullable=true)
     */
    private $eliminarAusentismoPrima = false;

    /**
     * @ORM\Column(name="eliminar_ausentismo_vacacion", type="boolean", nullable=true)
     */
    private $eliminarAusentismoVacacion = false;

    /**
     * @ORM\Column(name="liquidar_vacaciones_salario", type="boolean")
     */
    private $liquidarVacacionesSalario = false;

    /**
     * @ORM\Column(name="liquidar_vacaciones_salario_recargo_prestacion", type="boolean", nullable=true)
     */
    private $liquidarVacacionesSalarioRecargoPrestacion = false;

    /**
     * @ORM\Column(name="descontar_total_ausentismos_contrato_terminado_en_liquidacion" , type="boolean" ,nullable=true)
     */
    private $descontarTotalAusentismosContratoTerminadoEnLiquidacion = false;

    /**
     * @ORM\Column(name="pagar_incapacidad_salario_pactado", type="boolean")
     */
    private $pagarIncapacidadSalarioPactado = false;

    /**
     * @ORM\Column(name="liquidar_incapacidades_ibc_mes_anterior", type="boolean")
     */
    private $liquidarIncapacidadesIbcMesAnterior = false;

    /**
     * @ORM\Column(name="liquidar_vacaciones_salario_promedio_cesantias", type="boolean", nullable=true)
     */
    private $liquidarVacacionesSalarioPromedioCesantias = false;

    /**
     * @ORM\Column(name="pagar_incapacidad_completa", type="boolean")
     */
    private $pagarIncapacidadCompleta = false;

    /**
     * @ORM\Column(name="liquidar_incapacidad_sin_base", type="boolean")
     */
    private $liquidarIncapacidadSinBase = false;

    /**
     * @ORM\Column(name="concatenar_ofina_cuenta_bbva", type="boolean", nullable=true)
     */
    private $concatenarOfinaCuentaBbva = false;

    /**
     * @ORM\Column(name="codigo_entidad_riesgos_profesionales_fk", type="integer", nullable=true)
     */
    private $codigoEntidadRiesgosProfesionalesFk;

    /**
     * @ORM\Column(name="provision_porcentaje_cesantia", type="float", nullable=true, options={"default":8.33})
     */
    private $provisionPorcentajeCesantia = 0;

    /**
     * @ORM\Column(name="provision_porcentaje_interes", type="float", nullable=true, options={"default":12})
     */
    private $provisionPorcentajeInteres = 0;

    /**
     * @ORM\Column(name="provision_porcentaje_prima", type="float", nullable=true, options={"default":8.33})
     */
    private $provisionPorcentajePrima = 0;

    /**
     * @ORM\Column(name="provision_porcentaje_vacacion", type="float", nullable=true, options={"default":5})
     */
    private $provisionPorcentajeVacacion = 0;

    /**
     * @ORM\ManyToOne(targetEntity="RhuConcepto", inversedBy="configuracionConceptoAuxilioTransporteRel")
     * @ORM\JoinColumn(name="codigo_concepto_auxilio_transporte_fk", referencedColumnName="codigo_concepto_pk")
     */
    protected $conceptoAuxilioTransporteRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuConcepto", inversedBy="configuracionConceptoAuxilioTransporteRel")
     * @ORM\JoinColumn(name="codigo_concepto_vacacion_fk", referencedColumnName="codigo_concepto_pk")
     */
    protected $conceptoVacacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuConcepto", inversedBy="configuracionConceptoFondoSolidaridadRel")
     * @ORM\JoinColumn(name="codigo_concepto_fondo_solidaridad_pension_fk", referencedColumnName="codigo_concepto_pk")
     */
    protected $conceptoFondoSolidaridadRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidad", inversedBy="configuracionesEntidadRiesgosRel")
     * @ORM\JoinColumn(name="codigo_entidad_riesgos_profesionales_fk", referencedColumnName="codigo_entidad_pk")
     */
    protected $entidadRiesgosRel;

    /**
     * @return mixed
     */
    public function getCodigoConfiguracionPk()
    {
        return $this->codigoConfiguracionPk;
    }

    /**
     * @param mixed $codigoConfiguracionPk
     */
    public function setCodigoConfiguracionPk($codigoConfiguracionPk): void
    {
        $this->codigoConfiguracionPk = $codigoConfiguracionPk;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioMinimo()
    {
        return $this->vrSalarioMinimo;
    }

    /**
     * @param mixed $vrSalarioMinimo
     */
    public function setVrSalarioMinimo($vrSalarioMinimo): void
    {
        $this->vrSalarioMinimo = $vrSalarioMinimo;
    }

    /**
     * @return mixed
     */
    public function getCodigoConceptoAuxilioTransporteFk()
    {
        return $this->codigoConceptoAuxilioTransporteFk;
    }

    /**
     * @param mixed $codigoConceptoAuxilioTransporteFk
     */
    public function setCodigoConceptoAuxilioTransporteFk($codigoConceptoAuxilioTransporteFk): void
    {
        $this->codigoConceptoAuxilioTransporteFk = $codigoConceptoAuxilioTransporteFk;
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
    public function getCodigoConceptoFondoSolidaridadPensionFk()
    {
        return $this->codigoConceptoFondoSolidaridadPensionFk;
    }

    /**
     * @param mixed $codigoConceptoFondoSolidaridadPensionFk
     */
    public function setCodigoConceptoFondoSolidaridadPensionFk($codigoConceptoFondoSolidaridadPensionFk): void
    {
        $this->codigoConceptoFondoSolidaridadPensionFk = $codigoConceptoFondoSolidaridadPensionFk;
    }

    /**
     * @return mixed
     */
    public function getAuxilioTransporteNoPrestacional()
    {
        return $this->auxilioTransporteNoPrestacional;
    }

    /**
     * @param mixed $auxilioTransporteNoPrestacional
     */
    public function setAuxilioTransporteNoPrestacional($auxilioTransporteNoPrestacional): void
    {
        $this->auxilioTransporteNoPrestacional = $auxilioTransporteNoPrestacional;
    }

    /**
     * @return mixed
     */
    public function getDescontarAusentismosDeLicencias()
    {
        return $this->descontarAusentismosDeLicencias;
    }

    /**
     * @param mixed $descontarAusentismosDeLicencias
     */
    public function setDescontarAusentismosDeLicencias($descontarAusentismosDeLicencias): void
    {
        $this->descontarAusentismosDeLicencias = $descontarAusentismosDeLicencias;
    }

    /**
     * @return mixed
     */
    public function getVacacionesBaseDescuentoLeyIbcMesAnterior()
    {
        return $this->vacacionesBaseDescuentoLeyIbcMesAnterior;
    }

    /**
     * @param mixed $vacacionesBaseDescuentoLeyIbcMesAnterior
     */
    public function setVacacionesBaseDescuentoLeyIbcMesAnterior($vacacionesBaseDescuentoLeyIbcMesAnterior): void
    {
        $this->vacacionesBaseDescuentoLeyIbcMesAnterior = $vacacionesBaseDescuentoLeyIbcMesAnterior;
    }

    /**
     * @return mixed
     */
    public function getVacacionesRecargoNocturnoUltimoAnio()
    {
        return $this->vacacionesRecargoNocturnoUltimoAnio;
    }

    /**
     * @param mixed $vacacionesRecargoNocturnoUltimoAnio
     */
    public function setVacacionesRecargoNocturnoUltimoAnio($vacacionesRecargoNocturnoUltimoAnio): void
    {
        $this->vacacionesRecargoNocturnoUltimoAnio = $vacacionesRecargoNocturnoUltimoAnio;
    }

    /**
     * @return mixed
     */
    public function getVacacionesLiquidarRecargoNocturnoPorcentajeConcepto()
    {
        return $this->vacacionesLiquidarRecargoNocturnoPorcentajeConcepto;
    }

    /**
     * @param mixed $vacacionesLiquidarRecargoNocturnoPorcentajeConcepto
     */
    public function setVacacionesLiquidarRecargoNocturnoPorcentajeConcepto($vacacionesLiquidarRecargoNocturnoPorcentajeConcepto): void
    {
        $this->vacacionesLiquidarRecargoNocturnoPorcentajeConcepto = $vacacionesLiquidarRecargoNocturnoPorcentajeConcepto;
    }

    /**
     * @return mixed
     */
    public function getLiquidarPrestacionesSalarioSuplementario()
    {
        return $this->liquidarPrestacionesSalarioSuplementario;
    }

    /**
     * @param mixed $liquidarPrestacionesSalarioSuplementario
     */
    public function setLiquidarPrestacionesSalarioSuplementario($liquidarPrestacionesSalarioSuplementario): void
    {
        $this->liquidarPrestacionesSalarioSuplementario = $liquidarPrestacionesSalarioSuplementario;
    }

    /**
     * @return mixed
     */
    public function getLiquidarVacacionesPromedioUltimoAnio()
    {
        return $this->liquidarVacacionesPromedioUltimoAnio;
    }

    /**
     * @param mixed $liquidarVacacionesPromedioUltimoAnio
     */
    public function setLiquidarVacacionesPromedioUltimoAnio($liquidarVacacionesPromedioUltimoAnio): void
    {
        $this->liquidarVacacionesPromedioUltimoAnio = $liquidarVacacionesPromedioUltimoAnio;
    }

    /**
     * @return mixed
     */
    public function getLiquidarLicenciasIbcMesAnterior()
    {
        return $this->liquidarLicenciasIbcMesAnterior;
    }

    /**
     * @param mixed $liquidarLicenciasIbcMesAnterior
     */
    public function setLiquidarLicenciasIbcMesAnterior($liquidarLicenciasIbcMesAnterior): void
    {
        $this->liquidarLicenciasIbcMesAnterior = $liquidarLicenciasIbcMesAnterior;
    }

    /**
     * @return mixed
     */
    public function getValidarFechaUltimoPagoLiquidacion()
    {
        return $this->validarFechaUltimoPagoLiquidacion;
    }

    /**
     * @param mixed $validarFechaUltimoPagoLiquidacion
     */
    public function setValidarFechaUltimoPagoLiquidacion($validarFechaUltimoPagoLiquidacion): void
    {
        $this->validarFechaUltimoPagoLiquidacion = $validarFechaUltimoPagoLiquidacion;
    }

    /**
     * @return mixed
     */
    public function getLiquidarCesantiaAnioAnterior()
    {
        return $this->liquidarCesantiaAnioAnterior;
    }

    /**
     * @param mixed $liquidarCesantiaAnioAnterior
     */
    public function setLiquidarCesantiaAnioAnterior($liquidarCesantiaAnioAnterior): void
    {
        $this->liquidarCesantiaAnioAnterior = $liquidarCesantiaAnioAnterior;
    }

    /**
     * @return mixed
     */
    public function getEliminarAusentismo()
    {
        return $this->eliminarAusentismo;
    }

    /**
     * @param mixed $eliminarAusentismo
     */
    public function setEliminarAusentismo($eliminarAusentismo): void
    {
        $this->eliminarAusentismo = $eliminarAusentismo;
    }

    /**
     * @return mixed
     */
    public function getGeneraPorcentajeLiquidacion()
    {
        return $this->generaPorcentajeLiquidacion;
    }

    /**
     * @param mixed $generaPorcentajeLiquidacion
     */
    public function setGeneraPorcentajeLiquidacion($generaPorcentajeLiquidacion): void
    {
        $this->generaPorcentajeLiquidacion = $generaPorcentajeLiquidacion;
    }

    /**
     * @return mixed
     */
    public function getEliminarAusentismoCesantia()
    {
        return $this->eliminarAusentismoCesantia;
    }

    /**
     * @param mixed $eliminarAusentismoCesantia
     */
    public function setEliminarAusentismoCesantia($eliminarAusentismoCesantia): void
    {
        $this->eliminarAusentismoCesantia = $eliminarAusentismoCesantia;
    }

    /**
     * @return mixed
     */
    public function getEliminarAusentismoPrima()
    {
        return $this->eliminarAusentismoPrima;
    }

    /**
     * @param mixed $eliminarAusentismoPrima
     */
    public function setEliminarAusentismoPrima($eliminarAusentismoPrima): void
    {
        $this->eliminarAusentismoPrima = $eliminarAusentismoPrima;
    }

    /**
     * @return mixed
     */
    public function getEliminarAusentismoVacacion()
    {
        return $this->eliminarAusentismoVacacion;
    }

    /**
     * @param mixed $eliminarAusentismoVacacion
     */
    public function setEliminarAusentismoVacacion($eliminarAusentismoVacacion): void
    {
        $this->eliminarAusentismoVacacion = $eliminarAusentismoVacacion;
    }

    /**
     * @return mixed
     */
    public function getLiquidarVacacionesSalario()
    {
        return $this->liquidarVacacionesSalario;
    }

    /**
     * @param mixed $liquidarVacacionesSalario
     */
    public function setLiquidarVacacionesSalario($liquidarVacacionesSalario): void
    {
        $this->liquidarVacacionesSalario = $liquidarVacacionesSalario;
    }

    /**
     * @return mixed
     */
    public function getLiquidarVacacionesSalarioRecargoPrestacion()
    {
        return $this->liquidarVacacionesSalarioRecargoPrestacion;
    }

    /**
     * @param mixed $liquidarVacacionesSalarioRecargoPrestacion
     */
    public function setLiquidarVacacionesSalarioRecargoPrestacion($liquidarVacacionesSalarioRecargoPrestacion): void
    {
        $this->liquidarVacacionesSalarioRecargoPrestacion = $liquidarVacacionesSalarioRecargoPrestacion;
    }

    /**
     * @return mixed
     */
    public function getDescontarTotalAusentismosContratoTerminadoEnLiquidacion()
    {
        return $this->descontarTotalAusentismosContratoTerminadoEnLiquidacion;
    }

    /**
     * @param mixed $descontarTotalAusentismosContratoTerminadoEnLiquidacion
     */
    public function setDescontarTotalAusentismosContratoTerminadoEnLiquidacion($descontarTotalAusentismosContratoTerminadoEnLiquidacion): void
    {
        $this->descontarTotalAusentismosContratoTerminadoEnLiquidacion = $descontarTotalAusentismosContratoTerminadoEnLiquidacion;
    }

    /**
     * @return mixed
     */
    public function getPagarIncapacidadSalarioPactado()
    {
        return $this->pagarIncapacidadSalarioPactado;
    }

    /**
     * @param mixed $pagarIncapacidadSalarioPactado
     */
    public function setPagarIncapacidadSalarioPactado($pagarIncapacidadSalarioPactado): void
    {
        $this->pagarIncapacidadSalarioPactado = $pagarIncapacidadSalarioPactado;
    }

    /**
     * @return mixed
     */
    public function getLiquidarIncapacidadesIbcMesAnterior()
    {
        return $this->liquidarIncapacidadesIbcMesAnterior;
    }

    /**
     * @param mixed $liquidarIncapacidadesIbcMesAnterior
     */
    public function setLiquidarIncapacidadesIbcMesAnterior($liquidarIncapacidadesIbcMesAnterior): void
    {
        $this->liquidarIncapacidadesIbcMesAnterior = $liquidarIncapacidadesIbcMesAnterior;
    }

    /**
     * @return mixed
     */
    public function getLiquidarVacacionesSalarioPromedioCesantias()
    {
        return $this->liquidarVacacionesSalarioPromedioCesantias;
    }

    /**
     * @param mixed $liquidarVacacionesSalarioPromedioCesantias
     */
    public function setLiquidarVacacionesSalarioPromedioCesantias($liquidarVacacionesSalarioPromedioCesantias): void
    {
        $this->liquidarVacacionesSalarioPromedioCesantias = $liquidarVacacionesSalarioPromedioCesantias;
    }

    /**
     * @return mixed
     */
    public function getPagarIncapacidadCompleta()
    {
        return $this->pagarIncapacidadCompleta;
    }

    /**
     * @param mixed $pagarIncapacidadCompleta
     */
    public function setPagarIncapacidadCompleta($pagarIncapacidadCompleta): void
    {
        $this->pagarIncapacidadCompleta = $pagarIncapacidadCompleta;
    }

    /**
     * @return mixed
     */
    public function getLiquidarIncapacidadSinBase()
    {
        return $this->liquidarIncapacidadSinBase;
    }

    /**
     * @param mixed $liquidarIncapacidadSinBase
     */
    public function setLiquidarIncapacidadSinBase($liquidarIncapacidadSinBase): void
    {
        $this->liquidarIncapacidadSinBase = $liquidarIncapacidadSinBase;
    }

    /**
     * @return mixed
     */
    public function getConcatenarOfinaCuentaBbva()
    {
        return $this->concatenarOfinaCuentaBbva;
    }

    /**
     * @param mixed $concatenarOfinaCuentaBbva
     */
    public function setConcatenarOfinaCuentaBbva($concatenarOfinaCuentaBbva): void
    {
        $this->concatenarOfinaCuentaBbva = $concatenarOfinaCuentaBbva;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadRiesgosProfesionalesFk()
    {
        return $this->codigoEntidadRiesgosProfesionalesFk;
    }

    /**
     * @param mixed $codigoEntidadRiesgosProfesionalesFk
     */
    public function setCodigoEntidadRiesgosProfesionalesFk($codigoEntidadRiesgosProfesionalesFk): void
    {
        $this->codigoEntidadRiesgosProfesionalesFk = $codigoEntidadRiesgosProfesionalesFk;
    }

    /**
     * @return mixed
     */
    public function getProvisionPorcentajeCesantia()
    {
        return $this->provisionPorcentajeCesantia;
    }

    /**
     * @param mixed $provisionPorcentajeCesantia
     */
    public function setProvisionPorcentajeCesantia($provisionPorcentajeCesantia): void
    {
        $this->provisionPorcentajeCesantia = $provisionPorcentajeCesantia;
    }

    /**
     * @return mixed
     */
    public function getProvisionPorcentajeInteres()
    {
        return $this->provisionPorcentajeInteres;
    }

    /**
     * @param mixed $provisionPorcentajeInteres
     */
    public function setProvisionPorcentajeInteres($provisionPorcentajeInteres): void
    {
        $this->provisionPorcentajeInteres = $provisionPorcentajeInteres;
    }

    /**
     * @return mixed
     */
    public function getProvisionPorcentajePrima()
    {
        return $this->provisionPorcentajePrima;
    }

    /**
     * @param mixed $provisionPorcentajePrima
     */
    public function setProvisionPorcentajePrima($provisionPorcentajePrima): void
    {
        $this->provisionPorcentajePrima = $provisionPorcentajePrima;
    }

    /**
     * @return mixed
     */
    public function getProvisionPorcentajeVacacion()
    {
        return $this->provisionPorcentajeVacacion;
    }

    /**
     * @param mixed $provisionPorcentajeVacacion
     */
    public function setProvisionPorcentajeVacacion($provisionPorcentajeVacacion): void
    {
        $this->provisionPorcentajeVacacion = $provisionPorcentajeVacacion;
    }

    /**
     * @return mixed
     */
    public function getConceptoAuxilioTransporteRel()
    {
        return $this->conceptoAuxilioTransporteRel;
    }

    /**
     * @param mixed $conceptoAuxilioTransporteRel
     */
    public function setConceptoAuxilioTransporteRel($conceptoAuxilioTransporteRel): void
    {
        $this->conceptoAuxilioTransporteRel = $conceptoAuxilioTransporteRel;
    }

    /**
     * @return mixed
     */
    public function getConceptoFondoSolidaridadRel()
    {
        return $this->conceptoFondoSolidaridadRel;
    }

    /**
     * @param mixed $conceptoFondoSolidaridadRel
     */
    public function setConceptoFondoSolidaridadRel($conceptoFondoSolidaridadRel): void
    {
        $this->conceptoFondoSolidaridadRel = $conceptoFondoSolidaridadRel;
    }

    /**
     * @return mixed
     */
    public function getEntidadRiesgosRel()
    {
        return $this->entidadRiesgosRel;
    }

    /**
     * @param mixed $entidadRiesgosRel
     */
    public function setEntidadRiesgosRel($entidadRiesgosRel): void
    {
        $this->entidadRiesgosRel = $entidadRiesgosRel;
    }

    /**
     * @return array
     */
    public function getInfoLog(): array
    {
        return $this->infoLog;
    }

    /**
     * @param array $infoLog
     */
    public function setInfoLog(array $infoLog): void
    {
        $this->infoLog = $infoLog;
    }

    /**
     * @return mixed
     */
    public function getVacacionesRecargoNocturnoUltimoAnioEspecial()
    {
        return $this->vacacionesRecargoNocturnoUltimoAnioEspecial;
    }

    /**
     * @param mixed $vacacionesRecargoNocturnoUltimoAnioEspecial
     */
    public function setVacacionesRecargoNocturnoUltimoAnioEspecial($vacacionesRecargoNocturnoUltimoAnioEspecial): void
    {
        $this->vacacionesRecargoNocturnoUltimoAnioEspecial = $vacacionesRecargoNocturnoUltimoAnioEspecial;
    }

    /**
     * @return mixed
     */
    public function getCodigoConceptoAdicionalDevengadoPactadoFk()
    {
        return $this->codigoConceptoAdicionalDevengadoPactadoFk;
    }

    /**
     * @param mixed $codigoConceptoAdicionalDevengadoPactadoFk
     */
    public function setCodigoConceptoAdicionalDevengadoPactadoFk($codigoConceptoAdicionalDevengadoPactadoFk): void
    {
        $this->codigoConceptoAdicionalDevengadoPactadoFk = $codigoConceptoAdicionalDevengadoPactadoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoConceptoVacacionFk()
    {
        return $this->codigoConceptoVacacionFk;
    }

    /**
     * @param mixed $codigoConceptoVacacionFk
     */
    public function setCodigoConceptoVacacionFk($codigoConceptoVacacionFk): void
    {
        $this->codigoConceptoVacacionFk = $codigoConceptoVacacionFk;
    }

    /**
     * @return mixed
     */
    public function getConceptoVacacionRel()
    {
        return $this->conceptoVacacionRel;
    }

    /**
     * @param mixed $conceptoVacacionRel
     */
    public function setConceptoVacacionRel($conceptoVacacionRel): void
    {
        $this->conceptoVacacionRel = $conceptoVacacionRel;
    }



}
