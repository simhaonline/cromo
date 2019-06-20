<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurTurnoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurTurno
{
    public $infoLog = [
        "primaryKey" => "codigoTurnoPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_turno_pk", type="string", length=5)
     */
    private $codigoTurnoPk;

    /**
     * @ORM\Column(name="nombre", type="text", nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="hora_desde", type="time", nullable=true)
     */
    private $horaDesde;

    /**
     * @ORM\Column(name="hora_hasta", type="time", nullable=true)
     */
    private $horaHasta;

    /**
     * @ORM\Column(name="horas", type="float")
     * @Assert\Range(
     *      min = 0,
     *      max = 24,
     *      minMessage = "El valor minimo de horas es 0",
     *      maxMessage = "El valor maximo de horas es 24"
     * )
     */
    private $horas = 0;

    /**
     * @ORM\Column(name="horas_diurnas", type="float")
     * @Assert\Range(
     *      min = 0,
     *      max = 16,
     *      minMessage = "El valor minimo de horas diurnas es 0",
     *      maxMessage = "El valor maximo de horas diurnas es 16"
     * )
     */
    private $horasDiurnas = 0;

    /**
     * @ORM\Column(name="horas_nocturnas", type="float")
     * @Assert\Range(
     *      min = 0,
     *      max = 9,
     *      minMessage = "El valor minimo de horas nocturnas es 0",
     *      maxMessage = "El valor maximo de horas nocturnas es 9"
     * )
     */
    private $horasNocturnas = 0;

    /**
     * @ORM\Column(name="novedad", type="boolean", options={"default":false})
     */
    private $novedad = false;

    /**
     * @ORM\Column(name="descanso", type="boolean", options={"default":false})
     */
    private $descanso = false;

    /**
     * @ORM\Column(name="incapacidad", type="boolean", options={"default":false})
     */
    private $incapacidad = false;

    /**
     * @ORM\Column(name="licencia", type="boolean", options={"default":false})
     */
    private $licencia = false;

    /**
     * @ORM\Column(name="vacacion", type="boolean", options={"default":false})
     */
    private $vacacion = false;

    /**
     * @ORM\Column(name="ingreso", type="boolean", options={"default":false})
     */
    private $ingreso = false;

    /**
     * @ORM\Column(name="retiro", type="boolean", options={"default":false})
     */
    private $retiro = false;

    /**
     * @ORM\Column(name="induccion", type="boolean", options={"default":false})
     */
    private $induccion = false;

    /**
     * @ORM\Column(name="ausentismo", type="boolean", nullable=true, options={"default":false})
     */
    private $ausentismo = false;

    /**
     * @ORM\Column(name="dia", type="boolean", nullable=true, options={"default":false})
     */
    private $dia = false;

    /**
     * @ORM\Column(name="noche", type="boolean", nullable=true, options={"default":false})
     */
    private $noche = false;

    /**
     * @return mixed
     */
    public function getCodigoTurnoPk()
    {
        return $this->codigoTurnoPk;
    }

    /**
     * @param mixed $codigoTurnoPk
     */
    public function setCodigoTurnoPk($codigoTurnoPk): void
    {
        $this->codigoTurnoPk = $codigoTurnoPk;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getHoraDesde()
    {
        return $this->horaDesde;
    }

    /**
     * @param mixed $horaDesde
     */
    public function setHoraDesde($horaDesde): void
    {
        $this->horaDesde = $horaDesde;
    }

    /**
     * @return mixed
     */
    public function getHoraHasta()
    {
        return $this->horaHasta;
    }

    /**
     * @param mixed $horaHasta
     */
    public function setHoraHasta($horaHasta): void
    {
        $this->horaHasta = $horaHasta;
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
    public function getDia()
    {
        return $this->dia;
    }

    /**
     * @param mixed $dia
     */
    public function setDia($dia): void
    {
        $this->dia = $dia;
    }

    /**
     * @return mixed
     */
    public function getNoche()
    {
        return $this->noche;
    }

    /**
     * @param mixed $noche
     */
    public function setNoche($noche): void
    {
        $this->noche = $noche;
    }



}

