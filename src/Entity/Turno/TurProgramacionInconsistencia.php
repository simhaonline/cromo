<?php

namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurProgramacionInconsistenciaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})

 */
class TurProgramacionInconsistencia
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_programacion_inconsistencia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProgramacionInconsistenciaPk;

    /**
     * @ORM\Column(name="inconsistencia", type="string", length=100, nullable=true)
     */
    private $inconsistencia;

    /**
     * @ORM\Column(name="detalle", type="string", length=200, nullable=true)
     */
    private $detalle;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=true)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="dia", type="integer", nullable=true)
     */
    private $dia = 0;

    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */
    private $mes = 0;

    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */
    private $anio = 0;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_recurso_grupo_fk", type="integer", nullable=true)
     */
    private $codigoRecursoGrupoFk;

    /**
     * @ORM\Column(name="zona", type="string", length=100, nullable=true)
     */
    private $zona;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @return mixed
     */
    public function getCodigoProgramacionInconsistenciaPk()
    {
        return $this->codigoProgramacionInconsistenciaPk;
    }

    /**
     * @param mixed $codigoProgramacionInconsistenciaPk
     */
    public function setCodigoProgramacionInconsistenciaPk($codigoProgramacionInconsistenciaPk): void
    {
        $this->codigoProgramacionInconsistenciaPk = $codigoProgramacionInconsistenciaPk;
    }

    /**
     * @return mixed
     */
    public function getInconsistencia()
    {
        return $this->inconsistencia;
    }

    /**
     * @param mixed $inconsistencia
     */
    public function setInconsistencia($inconsistencia): void
    {
        $this->inconsistencia = $inconsistencia;
    }

    /**
     * @return mixed
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * @param mixed $detalle
     */
    public function setDetalle($detalle): void
    {
        $this->detalle = $detalle;
    }

    /**
     * @return mixed
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * @param mixed $numeroIdentificacion
     */
    public function setNumeroIdentificacion($numeroIdentificacion): void
    {
        $this->numeroIdentificacion = $numeroIdentificacion;
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
    public function getCodigoRecursoGrupoFk()
    {
        return $this->codigoRecursoGrupoFk;
    }

    /**
     * @param mixed $codigoRecursoGrupoFk
     */
    public function setCodigoRecursoGrupoFk($codigoRecursoGrupoFk): void
    {
        $this->codigoRecursoGrupoFk = $codigoRecursoGrupoFk;
    }

    /**
     * @return mixed
     */
    public function getZona()
    {
        return $this->zona;
    }

    /**
     * @param mixed $zona
     */
    public function setZona($zona): void
    {
        $this->zona = $zona;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
    }

}
