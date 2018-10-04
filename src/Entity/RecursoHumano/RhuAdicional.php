<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAdicionalRepository")
 */
class RhuAdicional
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_adicional_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAdicionalPk;

    /**
     * @ORM\Column(name="codigo_pago_concepto_fk", type="integer", nullable=true)
     */
    private $codigoPagoConceptoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="tipo_adicional", type="integer", nullable=false)
     */
    private $tipoAdicional;

    /**
     * @ORM\Column(name="modalidad", type="integer", options={"default":0}, nullable=true)
     */
    private $modalidad = 0;

    /**
     * @ORM\Column(name="codigo_periodo_fk", type="integer", nullable=true)
     */
    private $codigoPeriodoFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="cantidad", options={"default":0}, type="float")
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="valor", options={"default":0}, type="float")
     */
    private $vrValor = 0;

    /**
     * @ORM\Column(name="horas", options={"default":0}, type="float")
     */
    private $horas = 0;

    /**
     * @ORM\Column(name="codigo_programacion_pago_fk", type="integer", nullable=true)
     */
    private $codigoProgramacionPagoFk;

    /**
     * @ORM\Column(name="permanente", options={"default":false}, type="boolean")
     */
    private $permanente = false;

    /**
     * @ORM\Column(name="aplica_dia_laborado", options={"default":false}, type="boolean")
     */
    private $aplicaDiaLaborado = false;

    /**
     * @ORM\Column(name="aplica_prima", options={"default":false}, options={"default":false}, type="boolean", nullable=true)
     */
    private $aplicaPrima = false;

    /**
     * @ORM\Column(name="aplica_cesantia", options={"default":false}, type="boolean", nullable=true)
     */
    private $aplicaCesantia = false;

    /**
     * @ORM\Column(name="aplica_dia_laborado_sin_descanso", options={"default":false}, type="boolean")
     */
    private $aplicaDiaLaboradoSinDescanso = false;

    /**
     * @ORM\Column(name="detalle", type="string", length=250, nullable=true)
     * @Assert\Length(
     *     max=250,
     *     maxMessage="El campo no puede contener mas de 250 caracteres"
     * )
     */
    private $detalle;

    /**
     * @ORM\Column(name="prestacional", options={"default":false}, type="boolean")
     */
    private $prestacional = false;

    /**
     * @ORM\Column(name="estado_inactivo", options={"default":false}, type="boolean")
     */
    private $estadoInactivo = false;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="fecha_creacion", type="datetime", nullable=true)
     */
    private $fechaCreacion;

    /**
     * @ORM\Column(name="fecha_ultima_edicion", type="datetime", nullable=true)
     */
    private $fechaUltimaEdicion;

    /**
     * @ORM\Column(name="codigo_usuario_ultima_edicion", type="string", length=50, nullable=true)
     */
    private $codigoUsuarioUltimaEdicion;

    /**
     * @ORM\Column(name="liquidar_horas_salario", options={"default":false}, type="boolean")
     */
    private $liquidarHorasSalario = false;

    /**
     * @return mixed
     */
    public function getCodigoAdicionalPk()
    {
        return $this->codigoAdicionalPk;
    }

    /**
     * @param mixed $codigoAdicionalPk
     */
    public function setCodigoAdicionalPk($codigoAdicionalPk): void
    {
        $this->codigoAdicionalPk = $codigoAdicionalPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPagoConceptoFk()
    {
        return $this->codigoPagoConceptoFk;
    }

    /**
     * @param mixed $codigoPagoConceptoFk
     */
    public function setCodigoPagoConceptoFk($codigoPagoConceptoFk): void
    {
        $this->codigoPagoConceptoFk = $codigoPagoConceptoFk;
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
    public function getTipoAdicional()
    {
        return $this->tipoAdicional;
    }

    /**
     * @param mixed $tipoAdicional
     */
    public function setTipoAdicional($tipoAdicional): void
    {
        $this->tipoAdicional = $tipoAdicional;
    }

    /**
     * @return mixed
     */
    public function getModalidad()
    {
        return $this->modalidad;
    }

    /**
     * @param mixed $modalidad
     */
    public function setModalidad($modalidad): void
    {
        $this->modalidad = $modalidad;
    }

    /**
     * @return mixed
     */
    public function getCodigoPeriodoFk()
    {
        return $this->codigoPeriodoFk;
    }

    /**
     * @param mixed $codigoPeriodoFk
     */
    public function setCodigoPeriodoFk($codigoPeriodoFk): void
    {
        $this->codigoPeriodoFk = $codigoPeriodoFk;
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
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param mixed $cantidad
     */
    public function setCantidad($cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return mixed
     */
    public function getVrValor()
    {
        return $this->vrValor;
    }

    /**
     * @param mixed $vrValor
     */
    public function setVrValor($vrValor): void
    {
        $this->vrValor = $vrValor;
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
    public function getCodigoProgramacionPagoFk()
    {
        return $this->codigoProgramacionPagoFk;
    }

    /**
     * @param mixed $codigoProgramacionPagoFk
     */
    public function setCodigoProgramacionPagoFk($codigoProgramacionPagoFk): void
    {
        $this->codigoProgramacionPagoFk = $codigoProgramacionPagoFk;
    }

    /**
     * @return mixed
     */
    public function getPermanente()
    {
        return $this->permanente;
    }

    /**
     * @param mixed $permanente
     */
    public function setPermanente($permanente): void
    {
        $this->permanente = $permanente;
    }

    /**
     * @return mixed
     */
    public function getAplicaDiaLaborado()
    {
        return $this->aplicaDiaLaborado;
    }

    /**
     * @param mixed $aplicaDiaLaborado
     */
    public function setAplicaDiaLaborado($aplicaDiaLaborado): void
    {
        $this->aplicaDiaLaborado = $aplicaDiaLaborado;
    }

    /**
     * @return mixed
     */
    public function getAplicaPrima()
    {
        return $this->aplicaPrima;
    }

    /**
     * @param mixed $aplicaPrima
     */
    public function setAplicaPrima($aplicaPrima): void
    {
        $this->aplicaPrima = $aplicaPrima;
    }

    /**
     * @return mixed
     */
    public function getAplicaCesantia()
    {
        return $this->aplicaCesantia;
    }

    /**
     * @param mixed $aplicaCesantia
     */
    public function setAplicaCesantia($aplicaCesantia): void
    {
        $this->aplicaCesantia = $aplicaCesantia;
    }

    /**
     * @return mixed
     */
    public function getAplicaDiaLaboradoSinDescanso()
    {
        return $this->aplicaDiaLaboradoSinDescanso;
    }

    /**
     * @param mixed $aplicaDiaLaboradoSinDescanso
     */
    public function setAplicaDiaLaboradoSinDescanso($aplicaDiaLaboradoSinDescanso): void
    {
        $this->aplicaDiaLaboradoSinDescanso = $aplicaDiaLaboradoSinDescanso;
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
    public function getPrestacional()
    {
        return $this->prestacional;
    }

    /**
     * @param mixed $prestacional
     */
    public function setPrestacional($prestacional): void
    {
        $this->prestacional = $prestacional;
    }

    /**
     * @return mixed
     */
    public function getEstadoInactivo()
    {
        return $this->estadoInactivo;
    }

    /**
     * @param mixed $estadoInactivo
     */
    public function setEstadoInactivo($estadoInactivo): void
    {
        $this->estadoInactivo = $estadoInactivo;
    }

    /**
     * @return mixed
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * @param mixed $estadoAutorizado
     */
    public function setEstadoAutorizado($estadoAutorizado): void
    {
        $this->estadoAutorizado = $estadoAutorizado;
    }

    /**
     * @return mixed
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * @param mixed $estadoAprobado
     */
    public function setEstadoAprobado($estadoAprobado): void
    {
        $this->estadoAprobado = $estadoAprobado;
    }

    /**
     * @return mixed
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * @param mixed $estadoAnulado
     */
    public function setEstadoAnulado($estadoAnulado): void
    {
        $this->estadoAnulado = $estadoAnulado;
    }

    /**
     * @return mixed
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * @param mixed $fechaCreacion
     */
    public function setFechaCreacion($fechaCreacion): void
    {
        $this->fechaCreacion = $fechaCreacion;
    }

    /**
     * @return mixed
     */
    public function getFechaUltimaEdicion()
    {
        return $this->fechaUltimaEdicion;
    }

    /**
     * @param mixed $fechaUltimaEdicion
     */
    public function setFechaUltimaEdicion($fechaUltimaEdicion): void
    {
        $this->fechaUltimaEdicion = $fechaUltimaEdicion;
    }

    /**
     * @return mixed
     */
    public function getCodigoUsuarioUltimaEdicion()
    {
        return $this->codigoUsuarioUltimaEdicion;
    }

    /**
     * @param mixed $codigoUsuarioUltimaEdicion
     */
    public function setCodigoUsuarioUltimaEdicion($codigoUsuarioUltimaEdicion): void
    {
        $this->codigoUsuarioUltimaEdicion = $codigoUsuarioUltimaEdicion;
    }

    /**
     * @return mixed
     */
    public function getLiquidarHorasSalario()
    {
        return $this->liquidarHorasSalario;
    }

    /**
     * @param mixed $liquidarHorasSalario
     */
    public function setLiquidarHorasSalario($liquidarHorasSalario): void
    {
        $this->liquidarHorasSalario = $liquidarHorasSalario;
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
