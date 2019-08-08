<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurSoporteContratoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurSoporteContrato
{
    public $infoLog = [
        "primaryKey" => "codigoSoporteContratoPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_soporte_contrato_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSoporteContratoPk;

    /**
     * @ORM\Column(name="codigo_soporte_fk", type="integer", nullable=true)
     */
    private $codigoSoporteFk;

    /**
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio = 0;

    /**
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes = 0;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $vrSalario = 0;

    /**
     * @ORM\Column(name="descanso", type="integer")
     */
    private $descanso = 0;

    /**
     * @ORM\Column(name="novedad", type="integer")
     */
    private $novedad = 0;

    /**
     * @ORM\Column(name="incapacidad", type="integer")
     */
    private $incapacidad = 0;

    /**
     * @ORM\Column(name="licencia", type="integer")
     */
    private $licencia = 0;

    /**
     * @ORM\Column(name="vacacion", type="integer")
     */
    private $vacacion = 0;

    /**
     * @ORM\Column(name="ingreso", type="integer")
     */
    private $ingreso = 0;

    /**
     * @ORM\Column(name="retiro", type="integer")
     */
    private $retiro = 0;

    /**
     * @ORM\Column(name="induccion", type="integer")
     */
    private $induccion = 0;

    /**
     * @ORM\Column(name="ausentismo", type="integer", nullable=true)
     */
    private $ausentismo = 0;

    /**
     * @ORM\Column(name="dias", type="float")
     */
    private $dias = 0;

    /**
     * @ORM\Column(name="dias_trasnporte", type="float")
     */
    private $diasTransporte = 0;

    /**
     * @ORM\Column(name="horas", type="float")
     */
    private $horas = 0;

    /**
     * @ORM\Column(name="horas_descanso", type="float")
     */
    private $horasDescanso = 0;

    /**
     * @ORM\Column(name="horas_novedad", type="float")
     */
    private $horasNovedad = 0;

    /**
     * @ORM\Column(name="horas_diurnas", type="float")
     */
    private $horasDiurnas = 0;

    /**
     * @ORM\Column(name="horas_nocturnas", type="float")
     */
    private $horasNocturnas = 0;

    /**
     * @ORM\Column(name="horas_festivas_diurnas", type="float")
     */
    private $horasFestivasDiurnas = 0;

    /**
     * @ORM\Column(name="horas_festivas_nocturnas", type="float")
     */
    private $horasFestivasNocturnas = 0;

    /**
     * @ORM\Column(name="horas_extras_ordinarias_diurnas", type="float")
     */
    private $horasExtrasOrdinariasDiurnas = 0;

    /**
     * @ORM\Column(name="horas_extras_ordinarias_nocturnas", type="float")
     */
    private $horasExtrasOrdinariasNocturnas = 0;

    /**
     * @ORM\Column(name="horas_extras_festivas_diurnas", type="float")
     */
    private $horasExtrasFestivasDiurnas = 0;

    /**
     * @ORM\Column(name="horas_extras_festivas_nocturnas", type="float")
     */
    private $horasExtrasFestivasNocturnas = 0;

    /**
     * @ORM\Column(name="horas_recargo", type="float", nullable=true)
     */
    private $horasRecargo = 0;

    /**
     * @ORM\Column(name="horas_recargo_nocturno", type="float")
     */
    private $horasRecargoNocturno = 0;

    /**
     * @ORM\Column(name="horas_recargo_festivo_diurno", type="float")
     */
    private $horasRecargoFestivoDiurno = 0;

    /**
     * @ORM\Column(name="horas_recargo_festivo_nocturno", type="float")
     */
    private $horasRecargoFestivoNocturno = 0;


    /**
     * @ORM\Column(name="horas_descanso_reales", type="float")
     */
    private $horasDescansoReales = 0;

    /**
     * @ORM\Column(name="horas_diurnas_reales", type="float")
     */
    private $horasDiurnasReales = 0;

    /**
     * @ORM\Column(name="horas_nocturnas_reales", type="float")
     */
    private $horasNocturnasReales = 0;

    /**
     * @ORM\Column(name="horas_festivas_diurnas_reales", type="float")
     */
    private $horasFestivasDiurnasReales = 0;

    /**
     * @ORM\Column(name="horas_festivas_nocturnas_reales", type="float")
     */
    private $horasFestivasNocturnasReales = 0;

    /**
     * @ORM\Column(name="horas_extras_ordinarias_diurnas_reales", type="float")
     */
    private $horasExtrasOrdinariasDiurnasReales = 0;

    /**
     * @ORM\Column(name="horas_extras_ordinarias_nocturnas_reales", type="float")
     */
    private $horasExtrasOrdinariasNocturnasReales = 0;

    /**
     * @ORM\Column(name="horas_extras_festivas_diurnas_reales", type="float")
     */
    private $horasExtrasFestivasDiurnasReales = 0;

    /**
     * @ORM\Column(name="horas_extras_festivas_nocturnas_reales", type="float")
     */
    private $horasExtrasFestivasNocturnasReales = 0;

    /**
     * @ORM\Column(name="horas_recargo_reales", type="float", nullable=true)
     */
    private $horasRecargoReales = 0;

    /**
     * @ORM\Column(name="horas_recargo_nocturno_reales", type="float")
     */
    private $horasRecargoNocturnoReales = 0;

    /**
     * @ORM\Column(name="horas_recargo_festivo_diurno_reales", type="float")
     */
    private $horasRecargoFestivoDiurnoReales = 0;

    /**
     * @ORM\Column(name="horas_recargo_festivo_nocturno_reales", type="float")
     */
    private $horasRecargoFestivoNocturnoReales = 0;

    /**
     * @ORM\ManyToOne(targetEntity="TurSoporte", inversedBy="soportesContratosSoporteRel")
     * @ORM\JoinColumn(name="codigo_soporte_fk", referencedColumnName="codigo_soporte_pk")
     */
    protected $soporteRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuContrato", inversedBy="soportesContratosContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", inversedBy="soportesContratosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="TurSoporteHora", mappedBy="soporteContratoRel")
     */
    protected $soportesHorasSoporteContratoRel;

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
    public function getCodigoSoporteContratoPk()
    {
        return $this->codigoSoporteContratoPk;
    }

    /**
     * @param mixed $codigoSoporteContratoPk
     */
    public function setCodigoSoporteContratoPk($codigoSoporteContratoPk): void
    {
        $this->codigoSoporteContratoPk = $codigoSoporteContratoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSoporteFk()
    {
        return $this->codigoSoporteFk;
    }

    /**
     * @param mixed $codigoSoporteFk
     */
    public function setCodigoSoporteFk($codigoSoporteFk): void
    {
        $this->codigoSoporteFk = $codigoSoporteFk;
    }

    /**
     * @return mixed
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * @param mixed $anio
     */
    public function setAnio($anio): void
    {
        $this->anio = $anio;
    }

    /**
     * @return mixed
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * @param mixed $mes
     */
    public function setMes($mes): void
    {
        $this->mes = $mes;
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
    public function getDescanso()
    {
        return $this->descanso;
    }

    /**
     * @param mixed $descanso
     */
    public function setDescanso($descanso): void
    {
        $this->descanso = $descanso;
    }

    /**
     * @return mixed
     */
    public function getNovedad()
    {
        return $this->novedad;
    }

    /**
     * @param mixed $novedad
     */
    public function setNovedad($novedad): void
    {
        $this->novedad = $novedad;
    }

    /**
     * @return mixed
     */
    public function getIncapacidad()
    {
        return $this->incapacidad;
    }

    /**
     * @param mixed $incapacidad
     */
    public function setIncapacidad($incapacidad): void
    {
        $this->incapacidad = $incapacidad;
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
    public function getVacacion()
    {
        return $this->vacacion;
    }

    /**
     * @param mixed $vacacion
     */
    public function setVacacion($vacacion): void
    {
        $this->vacacion = $vacacion;
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
    public function getInduccion()
    {
        return $this->induccion;
    }

    /**
     * @param mixed $induccion
     */
    public function setInduccion($induccion): void
    {
        $this->induccion = $induccion;
    }

    /**
     * @return mixed
     */
    public function getAusentismo()
    {
        return $this->ausentismo;
    }

    /**
     * @param mixed $ausentismo
     */
    public function setAusentismo($ausentismo): void
    {
        $this->ausentismo = $ausentismo;
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
    public function getDiasTransporte()
    {
        return $this->diasTransporte;
    }

    /**
     * @param mixed $diasTransporte
     */
    public function setDiasTransporte($diasTransporte): void
    {
        $this->diasTransporte = $diasTransporte;
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
    public function getHorasDescanso()
    {
        return $this->horasDescanso;
    }

    /**
     * @param mixed $horasDescanso
     */
    public function setHorasDescanso($horasDescanso): void
    {
        $this->horasDescanso = $horasDescanso;
    }

    /**
     * @return mixed
     */
    public function getHorasNovedad()
    {
        return $this->horasNovedad;
    }

    /**
     * @param mixed $horasNovedad
     */
    public function setHorasNovedad($horasNovedad): void
    {
        $this->horasNovedad = $horasNovedad;
    }

    /**
     * @return mixed
     */
    public function getHorasDiurnas()
    {
        return $this->horasDiurnas;
    }

    /**
     * @param mixed $horasDiurnas
     */
    public function setHorasDiurnas($horasDiurnas): void
    {
        $this->horasDiurnas = $horasDiurnas;
    }

    /**
     * @return mixed
     */
    public function getHorasNocturnas()
    {
        return $this->horasNocturnas;
    }

    /**
     * @param mixed $horasNocturnas
     */
    public function setHorasNocturnas($horasNocturnas): void
    {
        $this->horasNocturnas = $horasNocturnas;
    }

    /**
     * @return mixed
     */
    public function getHorasFestivasDiurnas()
    {
        return $this->horasFestivasDiurnas;
    }

    /**
     * @param mixed $horasFestivasDiurnas
     */
    public function setHorasFestivasDiurnas($horasFestivasDiurnas): void
    {
        $this->horasFestivasDiurnas = $horasFestivasDiurnas;
    }

    /**
     * @return mixed
     */
    public function getHorasFestivasNocturnas()
    {
        return $this->horasFestivasNocturnas;
    }

    /**
     * @param mixed $horasFestivasNocturnas
     */
    public function setHorasFestivasNocturnas($horasFestivasNocturnas): void
    {
        $this->horasFestivasNocturnas = $horasFestivasNocturnas;
    }

    /**
     * @return mixed
     */
    public function getHorasExtrasOrdinariasDiurnas()
    {
        return $this->horasExtrasOrdinariasDiurnas;
    }

    /**
     * @param mixed $horasExtrasOrdinariasDiurnas
     */
    public function setHorasExtrasOrdinariasDiurnas($horasExtrasOrdinariasDiurnas): void
    {
        $this->horasExtrasOrdinariasDiurnas = $horasExtrasOrdinariasDiurnas;
    }

    /**
     * @return mixed
     */
    public function getHorasExtrasOrdinariasNocturnas()
    {
        return $this->horasExtrasOrdinariasNocturnas;
    }

    /**
     * @param mixed $horasExtrasOrdinariasNocturnas
     */
    public function setHorasExtrasOrdinariasNocturnas($horasExtrasOrdinariasNocturnas): void
    {
        $this->horasExtrasOrdinariasNocturnas = $horasExtrasOrdinariasNocturnas;
    }

    /**
     * @return mixed
     */
    public function getHorasExtrasFestivasDiurnas()
    {
        return $this->horasExtrasFestivasDiurnas;
    }

    /**
     * @param mixed $horasExtrasFestivasDiurnas
     */
    public function setHorasExtrasFestivasDiurnas($horasExtrasFestivasDiurnas): void
    {
        $this->horasExtrasFestivasDiurnas = $horasExtrasFestivasDiurnas;
    }

    /**
     * @return mixed
     */
    public function getHorasExtrasFestivasNocturnas()
    {
        return $this->horasExtrasFestivasNocturnas;
    }

    /**
     * @param mixed $horasExtrasFestivasNocturnas
     */
    public function setHorasExtrasFestivasNocturnas($horasExtrasFestivasNocturnas): void
    {
        $this->horasExtrasFestivasNocturnas = $horasExtrasFestivasNocturnas;
    }

    /**
     * @return mixed
     */
    public function getHorasRecargo()
    {
        return $this->horasRecargo;
    }

    /**
     * @param mixed $horasRecargo
     */
    public function setHorasRecargo($horasRecargo): void
    {
        $this->horasRecargo = $horasRecargo;
    }

    /**
     * @return mixed
     */
    public function getHorasRecargoNocturno()
    {
        return $this->horasRecargoNocturno;
    }

    /**
     * @param mixed $horasRecargoNocturno
     */
    public function setHorasRecargoNocturno($horasRecargoNocturno): void
    {
        $this->horasRecargoNocturno = $horasRecargoNocturno;
    }

    /**
     * @return mixed
     */
    public function getHorasRecargoFestivoDiurno()
    {
        return $this->horasRecargoFestivoDiurno;
    }

    /**
     * @param mixed $horasRecargoFestivoDiurno
     */
    public function setHorasRecargoFestivoDiurno($horasRecargoFestivoDiurno): void
    {
        $this->horasRecargoFestivoDiurno = $horasRecargoFestivoDiurno;
    }

    /**
     * @return mixed
     */
    public function getHorasRecargoFestivoNocturno()
    {
        return $this->horasRecargoFestivoNocturno;
    }

    /**
     * @param mixed $horasRecargoFestivoNocturno
     */
    public function setHorasRecargoFestivoNocturno($horasRecargoFestivoNocturno): void
    {
        $this->horasRecargoFestivoNocturno = $horasRecargoFestivoNocturno;
    }

    /**
     * @return mixed
     */
    public function getHorasDescansoReales()
    {
        return $this->horasDescansoReales;
    }

    /**
     * @param mixed $horasDescansoReales
     */
    public function setHorasDescansoReales($horasDescansoReales): void
    {
        $this->horasDescansoReales = $horasDescansoReales;
    }

    /**
     * @return mixed
     */
    public function getHorasDiurnasReales()
    {
        return $this->horasDiurnasReales;
    }

    /**
     * @param mixed $horasDiurnasReales
     */
    public function setHorasDiurnasReales($horasDiurnasReales): void
    {
        $this->horasDiurnasReales = $horasDiurnasReales;
    }

    /**
     * @return mixed
     */
    public function getHorasNocturnasReales()
    {
        return $this->horasNocturnasReales;
    }

    /**
     * @param mixed $horasNocturnasReales
     */
    public function setHorasNocturnasReales($horasNocturnasReales): void
    {
        $this->horasNocturnasReales = $horasNocturnasReales;
    }

    /**
     * @return mixed
     */
    public function getHorasFestivasDiurnasReales()
    {
        return $this->horasFestivasDiurnasReales;
    }

    /**
     * @param mixed $horasFestivasDiurnasReales
     */
    public function setHorasFestivasDiurnasReales($horasFestivasDiurnasReales): void
    {
        $this->horasFestivasDiurnasReales = $horasFestivasDiurnasReales;
    }

    /**
     * @return mixed
     */
    public function getHorasFestivasNocturnasReales()
    {
        return $this->horasFestivasNocturnasReales;
    }

    /**
     * @param mixed $horasFestivasNocturnasReales
     */
    public function setHorasFestivasNocturnasReales($horasFestivasNocturnasReales): void
    {
        $this->horasFestivasNocturnasReales = $horasFestivasNocturnasReales;
    }

    /**
     * @return mixed
     */
    public function getHorasExtrasOrdinariasDiurnasReales()
    {
        return $this->horasExtrasOrdinariasDiurnasReales;
    }

    /**
     * @param mixed $horasExtrasOrdinariasDiurnasReales
     */
    public function setHorasExtrasOrdinariasDiurnasReales($horasExtrasOrdinariasDiurnasReales): void
    {
        $this->horasExtrasOrdinariasDiurnasReales = $horasExtrasOrdinariasDiurnasReales;
    }

    /**
     * @return mixed
     */
    public function getHorasExtrasOrdinariasNocturnasReales()
    {
        return $this->horasExtrasOrdinariasNocturnasReales;
    }

    /**
     * @param mixed $horasExtrasOrdinariasNocturnasReales
     */
    public function setHorasExtrasOrdinariasNocturnasReales($horasExtrasOrdinariasNocturnasReales): void
    {
        $this->horasExtrasOrdinariasNocturnasReales = $horasExtrasOrdinariasNocturnasReales;
    }

    /**
     * @return mixed
     */
    public function getHorasExtrasFestivasDiurnasReales()
    {
        return $this->horasExtrasFestivasDiurnasReales;
    }

    /**
     * @param mixed $horasExtrasFestivasDiurnasReales
     */
    public function setHorasExtrasFestivasDiurnasReales($horasExtrasFestivasDiurnasReales): void
    {
        $this->horasExtrasFestivasDiurnasReales = $horasExtrasFestivasDiurnasReales;
    }

    /**
     * @return mixed
     */
    public function getHorasExtrasFestivasNocturnasReales()
    {
        return $this->horasExtrasFestivasNocturnasReales;
    }

    /**
     * @param mixed $horasExtrasFestivasNocturnasReales
     */
    public function setHorasExtrasFestivasNocturnasReales($horasExtrasFestivasNocturnasReales): void
    {
        $this->horasExtrasFestivasNocturnasReales = $horasExtrasFestivasNocturnasReales;
    }

    /**
     * @return mixed
     */
    public function getHorasRecargoReales()
    {
        return $this->horasRecargoReales;
    }

    /**
     * @param mixed $horasRecargoReales
     */
    public function setHorasRecargoReales($horasRecargoReales): void
    {
        $this->horasRecargoReales = $horasRecargoReales;
    }

    /**
     * @return mixed
     */
    public function getHorasRecargoNocturnoReales()
    {
        return $this->horasRecargoNocturnoReales;
    }

    /**
     * @param mixed $horasRecargoNocturnoReales
     */
    public function setHorasRecargoNocturnoReales($horasRecargoNocturnoReales): void
    {
        $this->horasRecargoNocturnoReales = $horasRecargoNocturnoReales;
    }

    /**
     * @return mixed
     */
    public function getHorasRecargoFestivoDiurnoReales()
    {
        return $this->horasRecargoFestivoDiurnoReales;
    }

    /**
     * @param mixed $horasRecargoFestivoDiurnoReales
     */
    public function setHorasRecargoFestivoDiurnoReales($horasRecargoFestivoDiurnoReales): void
    {
        $this->horasRecargoFestivoDiurnoReales = $horasRecargoFestivoDiurnoReales;
    }

    /**
     * @return mixed
     */
    public function getHorasRecargoFestivoNocturnoReales()
    {
        return $this->horasRecargoFestivoNocturnoReales;
    }

    /**
     * @param mixed $horasRecargoFestivoNocturnoReales
     */
    public function setHorasRecargoFestivoNocturnoReales($horasRecargoFestivoNocturnoReales): void
    {
        $this->horasRecargoFestivoNocturnoReales = $horasRecargoFestivoNocturnoReales;
    }

    /**
     * @return mixed
     */
    public function getSoporteRel()
    {
        return $this->soporteRel;
    }

    /**
     * @param mixed $soporteRel
     */
    public function setSoporteRel($soporteRel): void
    {
        $this->soporteRel = $soporteRel;
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
    public function getSoportesHorasSoporteContratoRel()
    {
        return $this->soportesHorasSoporteContratoRel;
    }

    /**
     * @param mixed $soportesHorasSoporteContratoRel
     */
    public function setSoportesHorasSoporteContratoRel($soportesHorasSoporteContratoRel): void
    {
        $this->soportesHorasSoporteContratoRel = $soportesHorasSoporteContratoRel;
    }



}
