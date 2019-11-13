<?php


namespace App\Entity\Turno;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurNovedadInconsistenciaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurNovedadInconsistencia
{
    public $infoLog = [
        "primaryKey" => "codigoPedidoPk",
        "todos"     => true,
    ];

    const TP_VACACION = "vacacion";
    const TP_INCAPACIDAD = "incapacidad";
    const TP_LICENCIA = "licencia";
    const TP_INGRESO = "ingreso";
    const TP_RETIRO = "retiro";

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_novedad_inconsistencia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoNovedadInconsistenciaPk;

    /**
     * @ORM\Column(name="codigo_empleado", type="integer", nullable=false)
     */
    private $codigoEmpleado;

    /**
     * @ORM\Column(name="numero_identificacion", type="integer", nullable=false)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=80, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="codigo_contrato", type="integer", nullable=true)
     */
    private $codigoContrato;

    /**
     * @ORM\Column(name="dias_programacion", type="integer", nullable=false)
     */
    private $diasProgramacion;

    /**
     * @ORM\Column(name="dias_rhu", type="integer", nullable=false)
     */
    private $diasRHU;

    /**
     * @ORM\Column(name="tipo", type="string", nullable=false)
     */
    private $tipo;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="bloquear_importacion", type="boolean", nullable=true)
     */
    private $bloquearImportacion = false;

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
    public function getCodigoNovedadInconsistenciaPk()
    {
        return $this->codigoNovedadInconsistenciaPk;
    }

    /**
     * @param mixed $codigoNovedadInconsistenciaPk
     */
    public function setCodigoNovedadInconsistenciaPk($codigoNovedadInconsistenciaPk): void
    {
        $this->codigoNovedadInconsistenciaPk = $codigoNovedadInconsistenciaPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEmpleado()
    {
        return $this->codigoEmpleado;
    }

    /**
     * @param mixed $codigoEmpleado
     */
    public function setCodigoEmpleado($codigoEmpleado): void
    {
        $this->codigoEmpleado = $codigoEmpleado;
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
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * @param mixed $nombreCorto
     */
    public function setNombreCorto($nombreCorto): void
    {
        $this->nombreCorto = $nombreCorto;
    }

    /**
     * @return mixed
     */
    public function getCodigoContrato()
    {
        return $this->codigoContrato;
    }

    /**
     * @param mixed $codigoContrato
     */
    public function setCodigoContrato($codigoContrato): void
    {
        $this->codigoContrato = $codigoContrato;
    }

    /**
     * @return mixed
     */
    public function getDiasProgramacion()
    {
        return $this->diasProgramacion;
    }

    /**
     * @param mixed $diasProgramacion
     */
    public function setDiasProgramacion($diasProgramacion): void
    {
        $this->diasProgramacion = $diasProgramacion;
    }

    /**
     * @return mixed
     */
    public function getDiasRHU()
    {
        return $this->diasRHU;
    }

    /**
     * @param mixed $diasRHU
     */
    public function setDiasRHU($diasRHU): void
    {
        $this->diasRHU = $diasRHU;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo): void
    {
        $this->tipo = $tipo;
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

    /**
     * @return mixed
     */
    public function getBloquearImportacion()
    {
        return $this->bloquearImportacion;
    }

    /**
     * @param mixed $bloquearImportacion
     */
    public function setBloquearImportacion($bloquearImportacion): void
    {
        $this->bloquearImportacion = $bloquearImportacion;
    }


}