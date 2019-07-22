<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurSoporteHoraRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurSoporteHora
{
    public $infoLog = [
        "primaryKey" => "codigoSoporteHoraPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_soporte_hora_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSoporteHoraPk;

    /**
     * @ORM\Column(name="codigo_soporte_fk", type="integer", nullable=true)
     */
    private $codigoSoporteFk;

    /**
     * @ORM\Column(name="codigo_soporte_contrato_fk", type="integer", nullable=true)
     */
    private $codigoSoporteContratoFk;

    /**
     * @ORM\Column(name="codigo_programacion_fk", type="integer", nullable=true)
     */
    private $codigoProgramacionFk;

    /**
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio = 0;

    /**
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes = 0;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_real", type="date", nullable=true)
     */
    private $fechaReal;

    /**
     * @ORM\Column(name="descanso", type="integer", nullable=true)
     */
    private $descanso = 0;

    /**
     * @ORM\Column(name="novedad", type="integer", nullable=true)
     */
    private $novedad = false;

    /**
     * @ORM\Column(name="incapacidad", type="integer", nullable=true)
     */
    private $incapacidad = false;

    /**
     * @ORM\Column(name="incapacidad_no_legalizada", type="integer")
     */
    private $incapacidadNoLegalizada = 0;

    /**
     * @ORM\Column(name="licencia", type="integer", nullable=true)
     */
    private $licencia = false;

    /**
     * @ORM\Column(name="licencia_no_remunerada", type="integer")
     */
    private $licenciaNoRemunerada = 0;

    /**
     * @ORM\Column(name="vacacion", type="integer", nullable=true)
     */
    private $vacacion = false;

    /**
     * @ORM\Column(name="ingreso", type="integer", nullable=true)
     */
    private $ingreso = false;

    /**
     * @ORM\Column(name="retiro", type="integer", nullable=true)
     */
    private $retiro = false;

    /**
     * @ORM\Column(name="induccion", type="integer", nullable=true)
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
     * @ORM\Column(name="horas", type="float")
     */
    private $horas = 0;

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
     * @ORM\Column(name="horas_descanso", type="float")
     */
    private $horasDescanso = 0;

    /**
     * @ORM\Column(name="horas_novedad", type="float")
     */
    private $horasNovedad = 0;

    /**
     * @ORM\Column(name="codigo_turno_fk", type="string", length=5)
     */
    private $codigoTurnoFk;

    /**
     * @ORM\Column(name="festivo", type="boolean")
     */
    private $festivo = false;

    /**
     * @ORM\Column(name="complementario", type="integer", nullable=true)
     */
    private $complementario = false;

    /**
     * @ORM\Column(name="adicional", type="integer", nullable=true)
     */
    private $adicional = false;

    /**
     * @ORM\ManyToOne(targetEntity="TurSoporte", inversedBy="soportesHorasSoporteRel")
     * @ORM\JoinColumn(name="codigo_soporte_fk", referencedColumnName="codigo_soporte_pk")
     */
    protected $soporteRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurSoporteContrato", inversedBy="soportesHorasSoporteContratoRel")
     * @ORM\JoinColumn(name="codigo_soporte_contrato_fk", referencedColumnName="codigo_soporte_contrato_pk")
     */
    protected $soporteContratoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurProgramacion", inversedBy="soportesHorasProgramacionRel")
     * @ORM\JoinColumn(name="codigo_programacion_fk", referencedColumnName="codigo_programacion_pk")
     */
    protected $programacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurTurno", inversedBy="soportesHorasTurnoRel")
     * @ORM\JoinColumn(name="codigo_turno_fk", referencedColumnName="codigo_turno_pk")
     */
    protected $turnoRel;

    /**
     * @return mixed
     */
    public function getCodigoSoporteHoraPk()
    {
        return $this->codigoSoporteHoraPk;
    }

    /**
     * @param mixed $codigoSoporteHoraPk
     */
    public function setCodigoSoporteHoraPk($codigoSoporteHoraPk): void
    {
        $this->codigoSoporteHoraPk = $codigoSoporteHoraPk;
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
    public function getCodigoSoporteContratoFk()
    {
        return $this->codigoSoporteContratoFk;
    }

    /**
     * @param mixed $codigoSoporteContratoFk
     */
    public function setCodigoSoporteContratoFk($codigoSoporteContratoFk): void
    {
        $this->codigoSoporteContratoFk = $codigoSoporteContratoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoProgramacionFk()
    {
        return $this->codigoProgramacionFk;
    }

    /**
     * @param mixed $codigoProgramacionFk
     */
    public function setCodigoProgramacionFk($codigoProgramacionFk): void
    {
        $this->codigoProgramacionFk = $codigoProgramacionFk;
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
    public function getFechaReal()
    {
        return $this->fechaReal;
    }

    /**
     * @param mixed $fechaReal
     */
    public function setFechaReal($fechaReal): void
    {
        $this->fechaReal = $fechaReal;
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
    public function getIncapacidadNoLegalizada()
    {
        return $this->incapacidadNoLegalizada;
    }

    /**
     * @param mixed $incapacidadNoLegalizada
     */
    public function setIncapacidadNoLegalizada($incapacidadNoLegalizada): void
    {
        $this->incapacidadNoLegalizada = $incapacidadNoLegalizada;
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
    public function getLicenciaNoRemunerada()
    {
        return $this->licenciaNoRemunerada;
    }

    /**
     * @param mixed $licenciaNoRemunerada
     */
    public function setLicenciaNoRemunerada($licenciaNoRemunerada): void
    {
        $this->licenciaNoRemunerada = $licenciaNoRemunerada;
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
    public function getCodigoTurnoFk()
    {
        return $this->codigoTurnoFk;
    }

    /**
     * @param mixed $codigoTurnoFk
     */
    public function setCodigoTurnoFk($codigoTurnoFk): void
    {
        $this->codigoTurnoFk = $codigoTurnoFk;
    }

    /**
     * @return mixed
     */
    public function getFestivo()
    {
        return $this->festivo;
    }

    /**
     * @param mixed $festivo
     */
    public function setFestivo($festivo): void
    {
        $this->festivo = $festivo;
    }

    /**
     * @return mixed
     */
    public function getComplementario()
    {
        return $this->complementario;
    }

    /**
     * @param mixed $complementario
     */
    public function setComplementario($complementario): void
    {
        $this->complementario = $complementario;
    }

    /**
     * @return mixed
     */
    public function getAdicional()
    {
        return $this->adicional;
    }

    /**
     * @param mixed $adicional
     */
    public function setAdicional($adicional): void
    {
        $this->adicional = $adicional;
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
    public function getSoporteContratoRel()
    {
        return $this->soporteContratoRel;
    }

    /**
     * @param mixed $soporteContratoRel
     */
    public function setSoporteContratoRel($soporteContratoRel): void
    {
        $this->soporteContratoRel = $soporteContratoRel;
    }

    /**
     * @return mixed
     */
    public function getProgramacionRel()
    {
        return $this->programacionRel;
    }

    /**
     * @param mixed $programacionRel
     */
    public function setProgramacionRel($programacionRel): void
    {
        $this->programacionRel = $programacionRel;
    }

    /**
     * @return mixed
     */
    public function getTurnoRel()
    {
        return $this->turnoRel;
    }

    /**
     * @param mixed $turnoRel
     */
    public function setTurnoRel($turnoRel): void
    {
        $this->turnoRel = $turnoRel;
    }


}

