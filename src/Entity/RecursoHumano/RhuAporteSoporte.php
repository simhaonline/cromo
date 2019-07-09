<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAporteSoporteRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuAporteSoporte
{
    public $infoLog = [
        "primaryKey" => "codigoAporteSoportePk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_aporte_soporte_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAporteSoportePk;

    /**
     * @ORM\Column(name="codigo_aporte_contrato_fk", type="integer")
     */
    private $codigoAporteContratoFk;

    /**
    * @ORM\Column(name="dias", type="integer")
    */
    private $dias = 0;

    /**
     * @ORM\Column(name="horas", type="integer")
     */
    private $horas = 0;

    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $vrSalario = 0;

    /**
     * @ORM\Column(name="ibc", type="float")
     */
    private $ibc = 0;

    /**
     * @ORM\Column(name="ibc_caja_vacaciones", type="float", nullable=true)
     */
    private $ibcCajaVacaciones = 0;

    /**
     * @ORM\Column(name="vr_vacaciones", type="float")
     */
    private $vrVacaciones = 0;

    /**
     * @ORM\Column(name="ingreso", type="string", length=1)
     */
    private $ingreso = ' ';

    /**
     * @ORM\Column(name="retiro", type="string", length=1)
     */
    private $retiro = ' ';

    /**
     * @ORM\Column(name="salario_integral", type="string", length=1)
     */
    private $salarioIntegral = ' ';

    /**
     * @ORM\Column(name="dias_licencia", type="integer")
     */
    private $diasLicencia = 0;

    /**
     * @ORM\Column(name="dias_incapacidad_general", type="integer")
     */
    private $diasIncapacidadGeneral = 0;

    /**
     * @ORM\Column(name="dias_licencia_maternidad", type="integer")
     */
    private $diasLicenciaMaternidad = 0;

    /**
     * @ORM\Column(name="dias_incapacidad_laboral", type="integer")
     */
    private $diasIncapacidadLaboral = 0;

    /**
     * @ORM\Column(name="dias_vacaciones", type="integer")
     */
    private $diasVacaciones = 0;

    /**
     * @ORM\Column(name="tarifa_pension", type="float")
     */
    private $tarifaPension = 0;

    /**
     * @ORM\Column(name="tarifa_salud", type="float")
     */
    private $tarifaSalud = 0;

    /**
     * @ORM\Column(name="tarifa_riesgos", type="float")
     */
    private $tarifaRiesgos = 0;

    /**
     * @ORM\Column(name="tarifa_caja", type="float")
     */
    private $tarifaCaja = 0;

    /**
     * @ORM\Column(name="codigo_entidad_pension_pertenece", type="string", length=6, nullable=true)
     */
    private $codigoEntidadPensionPertenece;

    /**
     * @ORM\Column(name="codigo_entidad_salud_pertenece", type="string", length=6, nullable=true)
     */
    private $codigoEntidadSaludPertenece;

    /**
     * @ORM\Column(name="codigo_entidad_caja_pertenece", type="string", length=6, nullable=true)
     */
    private $codigoEntidadCajaPertenece;

    /**
     * @ORM\Column(name="incapacidad_general", type="boolean")
     */
    private $incapacidadGeneral = false;

    /**
     * @ORM\Column(name="incapacidad_laboral", type="boolean")
     */
    private $incapacidadLaboral = false;

    /**
     * @ORM\Column(name="licencia", type="boolean")
     */
    private $licencia = false;

    /**
     * @ORM\Column(name="licencia_maternidad", type="boolean")
     */
    private $licenciaMaternidad = false;

    /**
     * @ORM\Column(name="licencia_remunerada", type="boolean", nullable=true)
     */
    private $licenciaRemunerada = false;

    /**
     * @ORM\Column(name="vacaciones", type="boolean")
     */
    private $vacaciones = false;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="fecha_retiro", type="date", nullable=true)
     */
    private $fechaRetiro;

    /**
     * @ORM\Column(name="fecha_ingreso", type="date", nullable=true)
     */
    private $fechaIngreso;

    /**
     * @ORM\Column(name="variacion_transitoria_salario", type="string", length=1)
     */
    private $variacionTransitoriaSalario = ' ';

    /**
     * @ORM\Column(name="codigo_entidad_salud_traslada", type="string", length=6, nullable=true)
     */
    private $codigoEntidadSaludTraslada;

    /**
     * @ORM\Column(name="codigo_entidad_pension_traslada", type="string", length=6, nullable=true)
     */
    private $codigoEntidadPensionTraslada;

    /**
     * @ORM\Column(name="traslado_a_otra_eps" ,type="boolean", nullable=true)
     */
    private $trasladoAOtraEps = false;

    /**
     * @ORM\Column(name="traslado_a_otra_pension" ,type="boolean", nullable=true)
     */
    private $trasladoAOtraPension = false;

    /**
     * @ORM\Column(name="traslado_desde_otra_eps" ,type="boolean", nullable=true)
     */
    private $trasladoDesdeOtraEps = false;

    /**
     * @ORM\Column(name="traslado_desde_otra_pension" ,type="boolean", nullable=true)
     */
    private $trasladoDesdeOtraPension = false;

    /**
     * @ORM\ManyToOne(targetEntity="RhuAporteContrato", inversedBy="aportesSoportesAporteContratoRel")
     * @ORM\JoinColumn(name="codigo_aporte_contrato_fk",referencedColumnName="codigo_aporte_contrato_pk")
     */
    protected $aporteContratoRel;

    /**
     * @return mixed
     */
    public function getCodigoAporteSoportePk()
    {
        return $this->codigoAporteSoportePk;
    }

    /**
     * @param mixed $codigoAporteSoportePk
     */
    public function setCodigoAporteSoportePk($codigoAporteSoportePk): void
    {
        $this->codigoAporteSoportePk = $codigoAporteSoportePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoAporteContratoFk()
    {
        return $this->codigoAporteContratoFk;
    }

    /**
     * @param mixed $codigoAporteContratoFk
     */
    public function setCodigoAporteContratoFk($codigoAporteContratoFk): void
    {
        $this->codigoAporteContratoFk = $codigoAporteContratoFk;
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
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * @param mixed $horas
     */
    public function setHoras($horas): void
    {
        $this->horas = $horas;
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
    public function getIbc()
    {
        return $this->ibc;
    }

    /**
     * @param mixed $ibc
     */
    public function setIbc($ibc): void
    {
        $this->ibc = $ibc;
    }

    /**
     * @return mixed
     */
    public function getIbcCajaVacaciones()
    {
        return $this->ibcCajaVacaciones;
    }

    /**
     * @param mixed $ibcCajaVacaciones
     */
    public function setIbcCajaVacaciones($ibcCajaVacaciones): void
    {
        $this->ibcCajaVacaciones = $ibcCajaVacaciones;
    }

    /**
     * @return mixed
     */
    public function getVrVacaciones()
    {
        return $this->vrVacaciones;
    }

    /**
     * @param mixed $vrVacaciones
     */
    public function setVrVacaciones($vrVacaciones): void
    {
        $this->vrVacaciones = $vrVacaciones;
    }

    /**
     * @return mixed
     */
    public function getIngreso()
    {
        return $this->ingreso;
    }

    /**
     * @param mixed $ingreso
     */
    public function setIngreso($ingreso): void
    {
        $this->ingreso = $ingreso;
    }

    /**
     * @return mixed
     */
    public function getRetiro()
    {
        return $this->retiro;
    }

    /**
     * @param mixed $retiro
     */
    public function setRetiro($retiro): void
    {
        $this->retiro = $retiro;
    }

    /**
     * @return mixed
     */
    public function getSalarioIntegral()
    {
        return $this->salarioIntegral;
    }

    /**
     * @param mixed $salarioIntegral
     */
    public function setSalarioIntegral($salarioIntegral): void
    {
        $this->salarioIntegral = $salarioIntegral;
    }

    /**
     * @return mixed
     */
    public function getDiasLicencia()
    {
        return $this->diasLicencia;
    }

    /**
     * @param mixed $diasLicencia
     */
    public function setDiasLicencia($diasLicencia): void
    {
        $this->diasLicencia = $diasLicencia;
    }

    /**
     * @return mixed
     */
    public function getDiasIncapacidadGeneral()
    {
        return $this->diasIncapacidadGeneral;
    }

    /**
     * @param mixed $diasIncapacidadGeneral
     */
    public function setDiasIncapacidadGeneral($diasIncapacidadGeneral): void
    {
        $this->diasIncapacidadGeneral = $diasIncapacidadGeneral;
    }

    /**
     * @return mixed
     */
    public function getDiasLicenciaMaternidad()
    {
        return $this->diasLicenciaMaternidad;
    }

    /**
     * @param mixed $diasLicenciaMaternidad
     */
    public function setDiasLicenciaMaternidad($diasLicenciaMaternidad): void
    {
        $this->diasLicenciaMaternidad = $diasLicenciaMaternidad;
    }

    /**
     * @return mixed
     */
    public function getDiasIncapacidadLaboral()
    {
        return $this->diasIncapacidadLaboral;
    }

    /**
     * @param mixed $diasIncapacidadLaboral
     */
    public function setDiasIncapacidadLaboral($diasIncapacidadLaboral): void
    {
        $this->diasIncapacidadLaboral = $diasIncapacidadLaboral;
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
    public function getTarifaPension()
    {
        return $this->tarifaPension;
    }

    /**
     * @param mixed $tarifaPension
     */
    public function setTarifaPension($tarifaPension): void
    {
        $this->tarifaPension = $tarifaPension;
    }

    /**
     * @return mixed
     */
    public function getTarifaSalud()
    {
        return $this->tarifaSalud;
    }

    /**
     * @param mixed $tarifaSalud
     */
    public function setTarifaSalud($tarifaSalud): void
    {
        $this->tarifaSalud = $tarifaSalud;
    }

    /**
     * @return mixed
     */
    public function getTarifaRiesgos()
    {
        return $this->tarifaRiesgos;
    }

    /**
     * @param mixed $tarifaRiesgos
     */
    public function setTarifaRiesgos($tarifaRiesgos): void
    {
        $this->tarifaRiesgos = $tarifaRiesgos;
    }

    /**
     * @return mixed
     */
    public function getTarifaCaja()
    {
        return $this->tarifaCaja;
    }

    /**
     * @param mixed $tarifaCaja
     */
    public function setTarifaCaja($tarifaCaja): void
    {
        $this->tarifaCaja = $tarifaCaja;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadPensionPertenece()
    {
        return $this->codigoEntidadPensionPertenece;
    }

    /**
     * @param mixed $codigoEntidadPensionPertenece
     */
    public function setCodigoEntidadPensionPertenece($codigoEntidadPensionPertenece): void
    {
        $this->codigoEntidadPensionPertenece = $codigoEntidadPensionPertenece;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadSaludPertenece()
    {
        return $this->codigoEntidadSaludPertenece;
    }

    /**
     * @param mixed $codigoEntidadSaludPertenece
     */
    public function setCodigoEntidadSaludPertenece($codigoEntidadSaludPertenece): void
    {
        $this->codigoEntidadSaludPertenece = $codigoEntidadSaludPertenece;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadCajaPertenece()
    {
        return $this->codigoEntidadCajaPertenece;
    }

    /**
     * @param mixed $codigoEntidadCajaPertenece
     */
    public function setCodigoEntidadCajaPertenece($codigoEntidadCajaPertenece): void
    {
        $this->codigoEntidadCajaPertenece = $codigoEntidadCajaPertenece;
    }

    /**
     * @return mixed
     */
    public function getIncapacidadGeneral()
    {
        return $this->incapacidadGeneral;
    }

    /**
     * @param mixed $incapacidadGeneral
     */
    public function setIncapacidadGeneral($incapacidadGeneral): void
    {
        $this->incapacidadGeneral = $incapacidadGeneral;
    }

    /**
     * @return mixed
     */
    public function getIncapacidadLaboral()
    {
        return $this->incapacidadLaboral;
    }

    /**
     * @param mixed $incapacidadLaboral
     */
    public function setIncapacidadLaboral($incapacidadLaboral): void
    {
        $this->incapacidadLaboral = $incapacidadLaboral;
    }

    /**
     * @return mixed
     */
    public function getLicencia()
    {
        return $this->licencia;
    }

    /**
     * @param mixed $licencia
     */
    public function setLicencia($licencia): void
    {
        $this->licencia = $licencia;
    }

    /**
     * @return mixed
     */
    public function getLicenciaMaternidad()
    {
        return $this->licenciaMaternidad;
    }

    /**
     * @param mixed $licenciaMaternidad
     */
    public function setLicenciaMaternidad($licenciaMaternidad): void
    {
        $this->licenciaMaternidad = $licenciaMaternidad;
    }

    /**
     * @return mixed
     */
    public function getLicenciaRemunerada()
    {
        return $this->licenciaRemunerada;
    }

    /**
     * @param mixed $licenciaRemunerada
     */
    public function setLicenciaRemunerada($licenciaRemunerada): void
    {
        $this->licenciaRemunerada = $licenciaRemunerada;
    }

    /**
     * @return mixed
     */
    public function getVacaciones()
    {
        return $this->vacaciones;
    }

    /**
     * @param mixed $vacaciones
     */
    public function setVacaciones($vacaciones): void
    {
        $this->vacaciones = $vacaciones;
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
    public function getFechaRetiro()
    {
        return $this->fechaRetiro;
    }

    /**
     * @param mixed $fechaRetiro
     */
    public function setFechaRetiro($fechaRetiro): void
    {
        $this->fechaRetiro = $fechaRetiro;
    }

    /**
     * @return mixed
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * @param mixed $fechaIngreso
     */
    public function setFechaIngreso($fechaIngreso): void
    {
        $this->fechaIngreso = $fechaIngreso;
    }

    /**
     * @return mixed
     */
    public function getVariacionTransitoriaSalario()
    {
        return $this->variacionTransitoriaSalario;
    }

    /**
     * @param mixed $variacionTransitoriaSalario
     */
    public function setVariacionTransitoriaSalario($variacionTransitoriaSalario): void
    {
        $this->variacionTransitoriaSalario = $variacionTransitoriaSalario;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadSaludTraslada()
    {
        return $this->codigoEntidadSaludTraslada;
    }

    /**
     * @param mixed $codigoEntidadSaludTraslada
     */
    public function setCodigoEntidadSaludTraslada($codigoEntidadSaludTraslada): void
    {
        $this->codigoEntidadSaludTraslada = $codigoEntidadSaludTraslada;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadPensionTraslada()
    {
        return $this->codigoEntidadPensionTraslada;
    }

    /**
     * @param mixed $codigoEntidadPensionTraslada
     */
    public function setCodigoEntidadPensionTraslada($codigoEntidadPensionTraslada): void
    {
        $this->codigoEntidadPensionTraslada = $codigoEntidadPensionTraslada;
    }

    /**
     * @return mixed
     */
    public function getTrasladoAOtraEps()
    {
        return $this->trasladoAOtraEps;
    }

    /**
     * @param mixed $trasladoAOtraEps
     */
    public function setTrasladoAOtraEps($trasladoAOtraEps): void
    {
        $this->trasladoAOtraEps = $trasladoAOtraEps;
    }

    /**
     * @return mixed
     */
    public function getTrasladoAOtraPension()
    {
        return $this->trasladoAOtraPension;
    }

    /**
     * @param mixed $trasladoAOtraPension
     */
    public function setTrasladoAOtraPension($trasladoAOtraPension): void
    {
        $this->trasladoAOtraPension = $trasladoAOtraPension;
    }

    /**
     * @return mixed
     */
    public function getTrasladoDesdeOtraEps()
    {
        return $this->trasladoDesdeOtraEps;
    }

    /**
     * @param mixed $trasladoDesdeOtraEps
     */
    public function setTrasladoDesdeOtraEps($trasladoDesdeOtraEps): void
    {
        $this->trasladoDesdeOtraEps = $trasladoDesdeOtraEps;
    }

    /**
     * @return mixed
     */
    public function getTrasladoDesdeOtraPension()
    {
        return $this->trasladoDesdeOtraPension;
    }

    /**
     * @param mixed $trasladoDesdeOtraPension
     */
    public function setTrasladoDesdeOtraPension($trasladoDesdeOtraPension): void
    {
        $this->trasladoDesdeOtraPension = $trasladoDesdeOtraPension;
    }

    /**
     * @return mixed
     */
    public function getAporteContratoRel()
    {
        return $this->aporteContratoRel;
    }

    /**
     * @param mixed $aporteContratoRel
     */
    public function setAporteContratoRel($aporteContratoRel): void
    {
        $this->aporteContratoRel = $aporteContratoRel;
    }



}
