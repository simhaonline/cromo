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
     * @ORM\Column(name="vr_auxilio_transporte", type="float", nullable=true)
     */
    private $vrAuxilioTransporte;

    /**
     * @ORM\Column(name="codigo_concepto_fondo_solidaridad_pension_fk", type="string", length=10, nullable=true)
     */
    private $codigoConceptoFondoSolidaridadPensionFk;

    /**
     * @ORM\Column(name="vacaciones_base_descuento_ley_ibc_mes_anterior", type="boolean", nullable=true)
     */
    private $vacacionesBaseDescuentoLeyIbcMesAnterior = false;

    /**
     * @ORM\Column(name="vacaciones_recargo_nocturno_ultimo_anio", type="boolean", nullable=true)
     */
    private $vacacionesRecargoNocturnoUltimoAnio = false;

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
     * @ORM\Column(name="codigo_entidad_riesgos_profesionales_fk", type="integer", nullable=true)
     */
    private $codigoEntidadRiesgosProfesionalesFk;

    /**
     * @ORM\ManyToOne(targetEntity="RhuConcepto", inversedBy="configuracionConceptoAuxilioTransporteRel")
     * @ORM\JoinColumn(name="codigo_concepto_auxilio_transporte_fk", referencedColumnName="codigo_concepto_pk")
     */
    protected $conceptoAuxilioTransporteRel;

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



}
