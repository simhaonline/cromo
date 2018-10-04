<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuProgramacionRepository")
 */
class RhuProgramacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_programacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProgramacionPk;

    /**
     * @ORM\Column(name="codigo_pago_tipo_fk", options={"default": 0}, type="integer", nullable=false)
     */
    private $codigoPagoTipoFk;

    /**
     * @ORM\Column(name="fecha_desde",  type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta",  type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="dias", options={"default": 0}, type="integer")
     */
    private $dias = 0;

    /**
     * @ORM\Column(name="codigo_grupo_fk", options={"default": 0}, type="integer", nullable=true)
     */
    private $codigoGrupoFk;

    /**
     * @ORM\Column(name="fecha_pagado", type="datetime", nullable=true)
     */
    private $fechaPagado;

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", options={"default": 0}, type="integer", nullable=true)
     */
    private $codigoCentroCostoFk;

    /**
     * @ORM\Column(name="codigo_concepto_anticipo_fk", options={"default": 0}, type="integer", nullable=true)
     */
    private $codigoConceptoAnticipoFk;

    /**
     * @ORM\Column(name="estado_generado", options={"default": false}, type="boolean")
     */
    private $estadoGenerado = false;

    /**
     * @ORM\Column(name="estado_pagado", options={"default": false}, type="boolean")
     */
    private $estadoPagado = false;

    /**
     * @ORM\Column(name="estado_autorizado", options={"default": false}, type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", options={"default": false}, type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", options={"default": false}, type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="estado_pagado_banco", options={"default": false}, type="boolean")
     */
    private $estadoPagadoBanco = false;

    /**
     * @ORM\Column(name="verificar_pagos_adicionales", options={"default": false}, type="boolean")
     */
    private $verificarPagosAdicionales = false;

    /**
     * @ORM\Column(name="verificar_incapacidades", options={"default": false}, type="boolean")
     */
    private $verificarIncapacidades = false;

    /**
     * @ORM\Column(name="novedades_verificadas", options={"default": false}, type="boolean")
     */
    private $novedadesVerificadas = false;

    /**
     * @ORM\Column(name="contratos_terminados", options={"default": false}, type="boolean",nullable=true)
     */
    private $contratosTerminados = false;

    /**
     * @ORM\Column(name="vr_neto", options={"default": 0}, type="float")
     */
    private $vr_neto = 0;

    /**
     * @ORM\Column(name="empleados_generados", options={"default": false}, type="boolean")
     */
    private $empleadosGenerados = false;

    /**
     * @ORM\Column(name="no_generar_periodo", options={"default": false}, type="boolean")
     */
    private $noGeneraPeriodo = false;

    /**
     * @ORM\Column(name="numero_empleados", options={"default": 0}, type="integer")
     */
    private $numeroEmpleados = 0;

    /**
     * @ORM\Column(name="nombre", options={"default": 0}, type="string",length=80, nullable=true)
     * @Assert\Length(
     *      max = 80,
     *      maxMessage = "La cantidad máxima de caracteres para el nombre es de 80"
     * )
     */
    private $nombre;

    /**
     * @ORM\Column(name="inconsistencias", options={"default": false}, type="boolean")
     */
    private $inconsistencias = 0;

    /**
     * @ORM\Column(name="codigo_usuario", options={"default": 0}, type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="mensaje_pago", options={"default": 0}, type="string", length=400, nullable=true)
     */
    private $mensaje_pago;

    /**
     * @ORM\Column(name="codigo_soporte_pago_periodo_fk", options={"default": 0}, type="integer", nullable=true)
     */
    private $codigoSoportePagoPeriodoFk;

    /**
     * @ORM\Column(name="aplicar_vacacacion", options={"default": false}, type="boolean", nullable=true)
     */
    private $aplicarVacacion = true;

    /**
     * @ORM\Column(name="aplicar_incapacidad", options={"default": false}, type="boolean", nullable=true)
     */
    private $aplicarIncapacidad = true;

    /**
     * @ORM\Column(name="aplicar_licencia", options={"default": false}, type="boolean", nullable=true)
     */
    private $aplicarLicencia = true;

    /**
     * @ORM\Column(name="aplicar_adicional", options={"default": false}, type="boolean", nullable=true)
     */
    private $aplicarAdicional = true;

    /**
     * @ORM\Column(name="aplicar_extra", options={"default": false}, type="boolean", nullable=true)
     */
    private $aplicarExtra = true;

    /**
     * @ORM\Column(name="aplicar_salud", options={"default": false}, type="boolean", nullable=true)
     */
    private $aplicarSalud = true;

    /**
     * @ORM\Column(name="aplicar_pension", options={"default": false}, type="boolean", nullable=true)
     */
    private $aplicarPension = true;

    /**
     * @ORM\Column(name="aplicar_transporte", options={"default": false}, type="boolean", nullable=true)
     */
    private $aplicarTransporte = true;
    /**
     * @ORM\Column(name="aplicar_salario", options={"default": false}, type="boolean", nullable=true)
     */
    private $aplicarSalario = true;
    /**
     * @ORM\Column(name="aplicar_adicionales_pago_permanentes", options={"default": false}, type="boolean" , nullable=true)
     */
    private $aplicarAdicionalesPagoPermanentes = true;
    /**
     * @ORM\Column(name="aplicar_creditos", options={"default": false}, type="boolean", nullable=true)
     */
    private $aplicarCreditos = true;
    /**
     * @ORM\Column(name="aplicar_embargos", options={"default": false}, type="boolean", nullable=true)
     */
    private $aplicarEmbargos = true;

    /**
     * @ORM\Column(name="aplicar_anticipo", options={"default": false}, type="boolean", nullable=true)
     */
    private $aplicarAnticipo = false;

    /**
     * @ORM\Column(name="servicio_generado", options={"default": false}, type="boolean")
     */
    private $servicioGenerado = false;

    /**
     * @ORM\Column(name="estado_credito_generado", options={"default": false}, type="boolean", nullable=true)
     */
    private $estadoCreditoGenerado = false;

    /**
     * @return mixed
     */
    public function getCodigoProgramacionPk()
    {
        return $this->codigoProgramacionPk;
    }

    /**
     * @param mixed $codigoProgramacionPk
     */
    public function setCodigoProgramacionPk($codigoProgramacionPk): void
    {
        $this->codigoProgramacionPk = $codigoProgramacionPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPagoTipoFk()
    {
        return $this->codigoPagoTipoFk;
    }

    /**
     * @param mixed $codigoPagoTipoFk
     */
    public function setCodigoPagoTipoFk($codigoPagoTipoFk): void
    {
        $this->codigoPagoTipoFk = $codigoPagoTipoFk;
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
    public function getCodigoGrupoFk()
    {
        return $this->codigoGrupoFk;
    }

    /**
     * @param mixed $codigoGrupoFk
     */
    public function setCodigoGrupoFk($codigoGrupoFk): void
    {
        $this->codigoGrupoFk = $codigoGrupoFk;
    }

    /**
     * @return mixed
     */
    public function getFechaPagado()
    {
        return $this->fechaPagado;
    }

    /**
     * @param mixed $fechaPagado
     */
    public function setFechaPagado($fechaPagado): void
    {
        $this->fechaPagado = $fechaPagado;
    }

    /**
     * @return mixed
     */
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * @param mixed $codigoCentroCostoFk
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk): void
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoConceptoAnticipoFk()
    {
        return $this->codigoConceptoAnticipoFk;
    }

    /**
     * @param mixed $codigoConceptoAnticipoFk
     */
    public function setCodigoConceptoAnticipoFk($codigoConceptoAnticipoFk): void
    {
        $this->codigoConceptoAnticipoFk = $codigoConceptoAnticipoFk;
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
    public function getEstadoPagado()
    {
        return $this->estadoPagado;
    }

    /**
     * @param mixed $estadoPagado
     */
    public function setEstadoPagado($estadoPagado): void
    {
        $this->estadoPagado = $estadoPagado;
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
    public function getEstadoPagadoBanco()
    {
        return $this->estadoPagadoBanco;
    }

    /**
     * @param mixed $estadoPagadoBanco
     */
    public function setEstadoPagadoBanco($estadoPagadoBanco): void
    {
        $this->estadoPagadoBanco = $estadoPagadoBanco;
    }

    /**
     * @return mixed
     */
    public function getVerificarPagosAdicionales()
    {
        return $this->verificarPagosAdicionales;
    }

    /**
     * @param mixed $verificarPagosAdicionales
     */
    public function setVerificarPagosAdicionales($verificarPagosAdicionales): void
    {
        $this->verificarPagosAdicionales = $verificarPagosAdicionales;
    }

    /**
     * @return mixed
     */
    public function getVerificarIncapacidades()
    {
        return $this->verificarIncapacidades;
    }

    /**
     * @param mixed $verificarIncapacidades
     */
    public function setVerificarIncapacidades($verificarIncapacidades): void
    {
        $this->verificarIncapacidades = $verificarIncapacidades;
    }

    /**
     * @return mixed
     */
    public function getNovedadesVerificadas()
    {
        return $this->novedadesVerificadas;
    }

    /**
     * @param mixed $novedadesVerificadas
     */
    public function setNovedadesVerificadas($novedadesVerificadas): void
    {
        $this->novedadesVerificadas = $novedadesVerificadas;
    }

    /**
     * @return mixed
     */
    public function getContratosTerminados()
    {
        return $this->contratosTerminados;
    }

    /**
     * @param mixed $contratosTerminados
     */
    public function setContratosTerminados($contratosTerminados): void
    {
        $this->contratosTerminados = $contratosTerminados;
    }

    /**
     * @return mixed
     */
    public function getVrNeto()
    {
        return $this->vr_neto;
    }

    /**
     * @param mixed $vr_neto
     */
    public function setVrNeto($vr_neto): void
    {
        $this->vr_neto = $vr_neto;
    }

    /**
     * @return mixed
     */
    public function getEmpleadosGenerados()
    {
        return $this->empleadosGenerados;
    }

    /**
     * @param mixed $empleadosGenerados
     */
    public function setEmpleadosGenerados($empleadosGenerados): void
    {
        $this->empleadosGenerados = $empleadosGenerados;
    }

    /**
     * @return mixed
     */
    public function getNoGeneraPeriodo()
    {
        return $this->noGeneraPeriodo;
    }

    /**
     * @param mixed $noGeneraPeriodo
     */
    public function setNoGeneraPeriodo($noGeneraPeriodo): void
    {
        $this->noGeneraPeriodo = $noGeneraPeriodo;
    }

    /**
     * @return mixed
     */
    public function getNumeroEmpleados()
    {
        return $this->numeroEmpleados;
    }

    /**
     * @param mixed $numeroEmpleados
     */
    public function setNumeroEmpleados($numeroEmpleados): void
    {
        $this->numeroEmpleados = $numeroEmpleados;
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
    public function getInconsistencias()
    {
        return $this->inconsistencias;
    }

    /**
     * @param mixed $inconsistencias
     */
    public function setInconsistencias($inconsistencias): void
    {
        $this->inconsistencias = $inconsistencias;
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
    public function getMensajePago()
    {
        return $this->mensaje_pago;
    }

    /**
     * @param mixed $mensaje_pago
     */
    public function setMensajePago($mensaje_pago): void
    {
        $this->mensaje_pago = $mensaje_pago;
    }

    /**
     * @return mixed
     */
    public function getCodigoSoportePagoPeriodoFk()
    {
        return $this->codigoSoportePagoPeriodoFk;
    }

    /**
     * @param mixed $codigoSoportePagoPeriodoFk
     */
    public function setCodigoSoportePagoPeriodoFk($codigoSoportePagoPeriodoFk): void
    {
        $this->codigoSoportePagoPeriodoFk = $codigoSoportePagoPeriodoFk;
    }

    /**
     * @return mixed
     */
    public function getAplicarVacacion()
    {
        return $this->aplicarVacacion;
    }

    /**
     * @param mixed $aplicarVacacion
     */
    public function setAplicarVacacion($aplicarVacacion): void
    {
        $this->aplicarVacacion = $aplicarVacacion;
    }

    /**
     * @return mixed
     */
    public function getAplicarIncapacidad()
    {
        return $this->aplicarIncapacidad;
    }

    /**
     * @param mixed $aplicarIncapacidad
     */
    public function setAplicarIncapacidad($aplicarIncapacidad): void
    {
        $this->aplicarIncapacidad = $aplicarIncapacidad;
    }

    /**
     * @return mixed
     */
    public function getAplicarLicencia()
    {
        return $this->aplicarLicencia;
    }

    /**
     * @param mixed $aplicarLicencia
     */
    public function setAplicarLicencia($aplicarLicencia): void
    {
        $this->aplicarLicencia = $aplicarLicencia;
    }

    /**
     * @return mixed
     */
    public function getAplicarAdicional()
    {
        return $this->aplicarAdicional;
    }

    /**
     * @param mixed $aplicarAdicional
     */
    public function setAplicarAdicional($aplicarAdicional): void
    {
        $this->aplicarAdicional = $aplicarAdicional;
    }

    /**
     * @return mixed
     */
    public function getAplicarExtra()
    {
        return $this->aplicarExtra;
    }

    /**
     * @param mixed $aplicarExtra
     */
    public function setAplicarExtra($aplicarExtra): void
    {
        $this->aplicarExtra = $aplicarExtra;
    }

    /**
     * @return mixed
     */
    public function getAplicarSalud()
    {
        return $this->aplicarSalud;
    }

    /**
     * @param mixed $aplicarSalud
     */
    public function setAplicarSalud($aplicarSalud): void
    {
        $this->aplicarSalud = $aplicarSalud;
    }

    /**
     * @return mixed
     */
    public function getAplicarPension()
    {
        return $this->aplicarPension;
    }

    /**
     * @param mixed $aplicarPension
     */
    public function setAplicarPension($aplicarPension): void
    {
        $this->aplicarPension = $aplicarPension;
    }

    /**
     * @return mixed
     */
    public function getAplicarTransporte()
    {
        return $this->aplicarTransporte;
    }

    /**
     * @param mixed $aplicarTransporte
     */
    public function setAplicarTransporte($aplicarTransporte): void
    {
        $this->aplicarTransporte = $aplicarTransporte;
    }

    /**
     * @return mixed
     */
    public function getAplicarSalario()
    {
        return $this->aplicarSalario;
    }

    /**
     * @param mixed $aplicarSalario
     */
    public function setAplicarSalario($aplicarSalario): void
    {
        $this->aplicarSalario = $aplicarSalario;
    }

    /**
     * @return mixed
     */
    public function getAplicarAdicionalesPagoPermanentes()
    {
        return $this->aplicarAdicionalesPagoPermanentes;
    }

    /**
     * @param mixed $aplicarAdicionalesPagoPermanentes
     */
    public function setAplicarAdicionalesPagoPermanentes($aplicarAdicionalesPagoPermanentes): void
    {
        $this->aplicarAdicionalesPagoPermanentes = $aplicarAdicionalesPagoPermanentes;
    }

    /**
     * @return mixed
     */
    public function getAplicarCreditos()
    {
        return $this->aplicarCreditos;
    }

    /**
     * @param mixed $aplicarCreditos
     */
    public function setAplicarCreditos($aplicarCreditos): void
    {
        $this->aplicarCreditos = $aplicarCreditos;
    }

    /**
     * @return mixed
     */
    public function getAplicarEmbargos()
    {
        return $this->aplicarEmbargos;
    }

    /**
     * @param mixed $aplicarEmbargos
     */
    public function setAplicarEmbargos($aplicarEmbargos): void
    {
        $this->aplicarEmbargos = $aplicarEmbargos;
    }

    /**
     * @return mixed
     */
    public function getAplicarAnticipo()
    {
        return $this->aplicarAnticipo;
    }

    /**
     * @param mixed $aplicarAnticipo
     */
    public function setAplicarAnticipo($aplicarAnticipo): void
    {
        $this->aplicarAnticipo = $aplicarAnticipo;
    }

    /**
     * @return mixed
     */
    public function getServicioGenerado()
    {
        return $this->servicioGenerado;
    }

    /**
     * @param mixed $servicioGenerado
     */
    public function setServicioGenerado($servicioGenerado): void
    {
        $this->servicioGenerado = $servicioGenerado;
    }

    /**
     * @return mixed
     */
    public function getEstadoCreditoGenerado()
    {
        return $this->estadoCreditoGenerado;
    }

    /**
     * @param mixed $estadoCreditoGenerado
     */
    public function setEstadoCreditoGenerado($estadoCreditoGenerado): void
    {
        $this->estadoCreditoGenerado = $estadoCreditoGenerado;
    }
}
