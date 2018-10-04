<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuEgresoRepository")
 */
class RhuEgreso
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_egreso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEgresoPk;

    /**
     * @ORM\Column(name="codigo_pago_banco_tipo_fk", type="integer", nullable=true)
     */
    private $codigoPagoBancoTipoFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="numero",options={"default" : 0}, type="integer", nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="fecha_trasmision", type="date", nullable=true)
     */
    private $fechaTrasmision;

    /**
     * @ORM\Column(name="fecha_aplicacion", type="date", nullable=true)
     */
    private $fechaAplicacion;

    /**
     * @ORM\Column(name="secuencia", type="string", length=1, nullable=true)
     */
    private $secuencia;

    /**
     * @ORM\Column(name="descripcion", type="string", length=100, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="integer", nullable=true)
     */
    private $codigoCuentaFk;

    /**
     * @ORM\Column(name="vr_total_pago",options={"default" : 0}, type="float", nullable=true)
     */
    private $vrTotalPago = 0;

    /**
     * @ORM\Column(name="numero_registros",options={"default" : 0}, type="integer", nullable=true)
     */
    private $numeroRegistros = 0;

    /**
     * @ORM\Column(name="estado_autorizado", options={"default" : false}, type="boolean", nullable=true)
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_impreso", options={"default" : false}, type="boolean", nullable=true)
     */
    private $estadoImpreso = false;

    /**
     * @ORM\Column(name="estado_generado", options={"default" : false}, type="boolean", nullable=true)
     */
    private $estadoGenerado = false;

    /**
     * @ORM\Column(name="permitir_todo_tipo", options={"default" : false}, type="boolean",nullable=true)
     */
    private $permitirTodoTipo = false;

    /**
     * @ORM\Column(name="estado_contabilizado", options={"default" : false}, type="boolean", nullable=true)
     */
    private $estadoContabilizado = false;

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
     * @ORM\Column(name="validar_cuenta", options={"default" : false}, type="boolean",nullable=true)
     */
    private  $validarCuenta = false;

    /**
     * @ORM\Column(name="comentarios", type="text", nullable=true)
     */
    private $comentarios;

    /**
     * @return mixed
     */
    public function getCodigoEgresoPk()
    {
        return $this->codigoEgresoPk;
    }

    /**
     * @param mixed $codigoEgresoPk
     */
    public function setCodigoEgresoPk($codigoEgresoPk): void
    {
        $this->codigoEgresoPk = $codigoEgresoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPagoBancoTipoFk()
    {
        return $this->codigoPagoBancoTipoFk;
    }

    /**
     * @param mixed $codigoPagoBancoTipoFk
     */
    public function setCodigoPagoBancoTipoFk($codigoPagoBancoTipoFk): void
    {
        $this->codigoPagoBancoTipoFk = $codigoPagoBancoTipoFk;
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
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero): void
    {
        $this->numero = $numero;
    }

    /**
     * @return mixed
     */
    public function getFechaTrasmision()
    {
        return $this->fechaTrasmision;
    }

    /**
     * @param mixed $fechaTrasmision
     */
    public function setFechaTrasmision($fechaTrasmision): void
    {
        $this->fechaTrasmision = $fechaTrasmision;
    }

    /**
     * @return mixed
     */
    public function getFechaAplicacion()
    {
        return $this->fechaAplicacion;
    }

    /**
     * @param mixed $fechaAplicacion
     */
    public function setFechaAplicacion($fechaAplicacion): void
    {
        $this->fechaAplicacion = $fechaAplicacion;
    }

    /**
     * @return mixed
     */
    public function getSecuencia()
    {
        return $this->secuencia;
    }

    /**
     * @param mixed $secuencia
     */
    public function setSecuencia($secuencia): void
    {
        $this->secuencia = $secuencia;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * @param mixed $codigoCuentaFk
     */
    public function setCodigoCuentaFk($codigoCuentaFk): void
    {
        $this->codigoCuentaFk = $codigoCuentaFk;
    }

    /**
     * @return mixed
     */
    public function getVrTotalPago()
    {
        return $this->vrTotalPago;
    }

    /**
     * @param mixed $vrTotalPago
     */
    public function setVrTotalPago($vrTotalPago): void
    {
        $this->vrTotalPago = $vrTotalPago;
    }

    /**
     * @return mixed
     */
    public function getNumeroRegistros()
    {
        return $this->numeroRegistros;
    }

    /**
     * @param mixed $numeroRegistros
     */
    public function setNumeroRegistros($numeroRegistros): void
    {
        $this->numeroRegistros = $numeroRegistros;
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
    public function getEstadoImpreso()
    {
        return $this->estadoImpreso;
    }

    /**
     * @param mixed $estadoImpreso
     */
    public function setEstadoImpreso($estadoImpreso): void
    {
        $this->estadoImpreso = $estadoImpreso;
    }

    /**
     * @return mixed
     */
    public function getEstadoGenerado()
    {
        return $this->estadoGenerado;
    }

    /**
     * @param mixed $estadoGenerado
     */
    public function setEstadoGenerado($estadoGenerado): void
    {
        $this->estadoGenerado = $estadoGenerado;
    }

    /**
     * @return mixed
     */
    public function getPermitirTodoTipo()
    {
        return $this->permitirTodoTipo;
    }

    /**
     * @param mixed $permitirTodoTipo
     */
    public function setPermitirTodoTipo($permitirTodoTipo): void
    {
        $this->permitirTodoTipo = $permitirTodoTipo;
    }

    /**
     * @return mixed
     */
    public function getEstadoContabilizado()
    {
        return $this->estadoContabilizado;
    }

    /**
     * @param mixed $estadoContabilizado
     */
    public function setEstadoContabilizado($estadoContabilizado): void
    {
        $this->estadoContabilizado = $estadoContabilizado;
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
    public function getValidarCuenta()
    {
        return $this->validarCuenta;
    }

    /**
     * @param mixed $validarCuenta
     */
    public function setValidarCuenta($validarCuenta): void
    {
        $this->validarCuenta = $validarCuenta;
    }

    /**
     * @return mixed
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * @param mixed $comentarios
     */
    public function setComentarios($comentarios): void
    {
        $this->comentarios = $comentarios;
    }
}
