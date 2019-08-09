<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuLicenciaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuLicencia
{
    public $infoLog = [
        "primaryKey" => "codigoLicenciaPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_licencia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLicenciaPk;

    /**
     * @ORM\Column(name="codigo_licencia_tipo_fk", type="integer")
     */
    private $codigoLicenciaTipoFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */
    private $codigoCentroCostoFk;

    /**
     * @ORM\Column(name="codigo_entidad_salud_fk", type="integer", nullable=true)
     */
    private $codigoEntidadSaludFk;

    /**
     * @ORM\Column(name="codigo_grupo_fk", type="string", nullable=true)
     */
    private $codigoGrupoFk;

    /**
     * @ORM\Column(name="comentarios", type="text", nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="afecta_transporte", type="boolean")
     */
    private $afectaTransporte = false;

    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */
    private $codigoUsuario;
    /**
     * @ORM\Column(name="maternidad", type="boolean")
     */
    private $maternidad = false;

    /**
     * @ORM\Column(name="paternidad", type="boolean")
     */
    private $paternidad = false;

    /**
     * @ORM\Column(name="estado_cobrar", type="boolean")
     */
    private $estadoCobrar = false;
    /**
     * @ORM\Column(name="estado_cobrar_cliente", type="boolean")
     */
    private $estadoCobrarCliente = false;

    /**
     * @ORM\Column(name="dias_cobro", type="integer")
     */
    private $diasCobro = 0;

    /**
     * @ORM\Column(name="estado_prorroga", type="boolean", nullable=true)
     */
    private $estadoProrroga = false;

    /**
     * @ORM\Column(name="estado_transcripcion", type="boolean")
     */
    private $estadoTranscripcion = false;

    /**
     * @ORM\Column(name="estado_legalizado", type="boolean")
     */
    private $estadoLegalizado = false;

    /**
     * @ORM\Column(name="pagar_empleado", type="boolean", nullable=true)
     */
    private $pagarEmpleado = false;

    /**
     * @ORM\Column(name="porcentaje_pago", type="float")
     */
    private $porcentajePago = 0;

    /**
     * @ORM\Column(name="vr_cobro", type="float")
     */
    private $vrCobro = 0;

    /**
     * @ORM\Column(name="vr_licencia", type="float")
     */
    private $vrLicencia = 0;

    /**
     * @ORM\Column(name="vr_saldo", type="float")
     */
    private $vrSaldo = 0;

    /**
     * @ORM\Column(name="vr_pagado", type="float")
     */
    private $vrPagado= 0;

    /**
     * @ORM\Column(name="vr_ibc_mes_anterior", type="float", nullable=true)
     */
    private $vrIbcMesAnterior = 0;

    /**
     * @ORM\Column(name="dias_ibc_mes_anterior", type="float", nullable=true)
     */
    private $diasIbcMesAnterior = 0;

    /**
     * @ORM\Column(name="vr_hora", type="float", nullable=true)
     */
    private $vrHora = 0;

    /**
     * @ORM\Column(name="codigo_novedad_programacion", type="integer", nullable=true)
     */
    private $codigoNovedadProgramacion = 0;

    /**
     * @ORM\Column(name="aplicar_adicional", type="boolean", nullable=true)
     */
    private $aplicarAdicional = false;

    /**
     * @ORM\Column(name="fecha_aplicacion", type="date", nullable=true)
     */
    private $fechaAplicacion;

    /**
     * @ORM\Column(name="vr_abono", type="float", nullable=true)
     */
    private $vrAbono = 0;

    /**
     * @ORM\Column(name="vr_ibc_propuesto", type="float", nullable=true)
     */
    private $vrIbcPropuesto = 0;

    /**
     * @ORM\Column(name="vr_propuesto", type="float", nullable=true)
     */
    private $vrPropuesto = 0;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="licenciasEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="licenciasContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidad", inversedBy="licenciasEntidadSaludRel")
     * @ORM\JoinColumn(name="codigo_entidad_salud_fk", referencedColumnName="codigo_entidad_pk")
     */
    protected $entidadSaludRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuPagoDetalle", mappedBy="licenciaRel")
     */
    protected $pagosDetallesLicenciaRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuLicenciaTipo", inversedBy="licenciasLicenciaTipoRel")
     * @ORM\JoinColumn(name="codigo_licencia_tipo_fk", referencedColumnName="codigo_licencia_tipo_pk")
     */
    protected $licenciaTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuGrupo", inversedBy="licenciasGrupoRel")
     * @ORM\JoinColumn(name="codigo_grupo_fk", referencedColumnName="codigo_grupo_pk")
     */
    protected $grupoRel;

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
    public function getCodigoLicenciaPk()
    {
        return $this->codigoLicenciaPk;
    }

    /**
     * @param mixed $codigoLicenciaPk
     */
    public function setCodigoLicenciaPk($codigoLicenciaPk): void
    {
        $this->codigoLicenciaPk = $codigoLicenciaPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoLicenciaTipoFk()
    {
        return $this->codigoLicenciaTipoFk;
    }

    /**
     * @param mixed $codigoLicenciaTipoFk
     */
    public function setCodigoLicenciaTipoFk($codigoLicenciaTipoFk): void
    {
        $this->codigoLicenciaTipoFk = $codigoLicenciaTipoFk;
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
    public function getCodigoEntidadSaludFk()
    {
        return $this->codigoEntidadSaludFk;
    }

    /**
     * @param mixed $codigoEntidadSaludFk
     */
    public function setCodigoEntidadSaludFk($codigoEntidadSaludFk): void
    {
        $this->codigoEntidadSaludFk = $codigoEntidadSaludFk;
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

    /**
     * @return mixed
     */
    public function getAfectaTransporte()
    {
        return $this->afectaTransporte;
    }

    /**
     * @param mixed $afectaTransporte
     */
    public function setAfectaTransporte($afectaTransporte): void
    {
        $this->afectaTransporte = $afectaTransporte;
    }

    /**
     * @return mixed
     */
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * @param mixed $codigoUsuario
     */
    public function setCodigoUsuario($codigoUsuario): void
    {
        $this->codigoUsuario = $codigoUsuario;
    }

    /**
     * @return mixed
     */
    public function getMaternidad()
    {
        return $this->maternidad;
    }

    /**
     * @param mixed $maternidad
     */
    public function setMaternidad($maternidad): void
    {
        $this->maternidad = $maternidad;
    }

    /**
     * @return mixed
     */
    public function getPaternidad()
    {
        return $this->paternidad;
    }

    /**
     * @param mixed $paternidad
     */
    public function setPaternidad($paternidad): void
    {
        $this->paternidad = $paternidad;
    }

    /**
     * @return mixed
     */
    public function getEstadoCobrar()
    {
        return $this->estadoCobrar;
    }

    /**
     * @param mixed $estadoCobrar
     */
    public function setEstadoCobrar($estadoCobrar): void
    {
        $this->estadoCobrar = $estadoCobrar;
    }

    /**
     * @return mixed
     */
    public function getEstadoCobrarCliente()
    {
        return $this->estadoCobrarCliente;
    }

    /**
     * @param mixed $estadoCobrarCliente
     */
    public function setEstadoCobrarCliente($estadoCobrarCliente): void
    {
        $this->estadoCobrarCliente = $estadoCobrarCliente;
    }

    /**
     * @return mixed
     */
    public function getDiasCobro()
    {
        return $this->diasCobro;
    }

    /**
     * @param mixed $diasCobro
     */
    public function setDiasCobro($diasCobro): void
    {
        $this->diasCobro = $diasCobro;
    }

    /**
     * @return mixed
     */
    public function getEstadoProrroga()
    {
        return $this->estadoProrroga;
    }

    /**
     * @param mixed $estadoProrroga
     */
    public function setEstadoProrroga($estadoProrroga): void
    {
        $this->estadoProrroga = $estadoProrroga;
    }

    /**
     * @return mixed
     */
    public function getEstadoTranscripcion()
    {
        return $this->estadoTranscripcion;
    }

    /**
     * @param mixed $estadoTranscripcion
     */
    public function setEstadoTranscripcion($estadoTranscripcion): void
    {
        $this->estadoTranscripcion = $estadoTranscripcion;
    }

    /**
     * @return mixed
     */
    public function getEstadoLegalizado()
    {
        return $this->estadoLegalizado;
    }

    /**
     * @param mixed $estadoLegalizado
     */
    public function setEstadoLegalizado($estadoLegalizado): void
    {
        $this->estadoLegalizado = $estadoLegalizado;
    }

    /**
     * @return mixed
     */
    public function getPagarEmpleado()
    {
        return $this->pagarEmpleado;
    }

    /**
     * @param mixed $pagarEmpleado
     */
    public function setPagarEmpleado($pagarEmpleado): void
    {
        $this->pagarEmpleado = $pagarEmpleado;
    }

    /**
     * @return mixed
     */
    public function getPorcentajePago()
    {
        return $this->porcentajePago;
    }

    /**
     * @param mixed $porcentajePago
     */
    public function setPorcentajePago($porcentajePago): void
    {
        $this->porcentajePago = $porcentajePago;
    }

    /**
     * @return mixed
     */
    public function getVrCobro()
    {
        return $this->vrCobro;
    }

    /**
     * @param mixed $vrCobro
     */
    public function setVrCobro($vrCobro): void
    {
        $this->vrCobro = $vrCobro;
    }

    /**
     * @return mixed
     */
    public function getVrLicencia()
    {
        return $this->vrLicencia;
    }

    /**
     * @param mixed $vrLicencia
     */
    public function setVrLicencia($vrLicencia): void
    {
        $this->vrLicencia = $vrLicencia;
    }

    /**
     * @return mixed
     */
    public function getVrSaldo()
    {
        return $this->vrSaldo;
    }

    /**
     * @param mixed $vrSaldo
     */
    public function setVrSaldo($vrSaldo): void
    {
        $this->vrSaldo = $vrSaldo;
    }

    /**
     * @return mixed
     */
    public function getVrPagado()
    {
        return $this->vrPagado;
    }

    /**
     * @param mixed $vrPagado
     */
    public function setVrPagado($vrPagado): void
    {
        $this->vrPagado = $vrPagado;
    }

    /**
     * @return mixed
     */
    public function getVrIbcMesAnterior()
    {
        return $this->vrIbcMesAnterior;
    }

    /**
     * @param mixed $vrIbcMesAnterior
     */
    public function setVrIbcMesAnterior($vrIbcMesAnterior): void
    {
        $this->vrIbcMesAnterior = $vrIbcMesAnterior;
    }

    /**
     * @return mixed
     */
    public function getDiasIbcMesAnterior()
    {
        return $this->diasIbcMesAnterior;
    }

    /**
     * @param mixed $diasIbcMesAnterior
     */
    public function setDiasIbcMesAnterior($diasIbcMesAnterior): void
    {
        $this->diasIbcMesAnterior = $diasIbcMesAnterior;
    }

    /**
     * @return mixed
     */
    public function getVrHora()
    {
        return $this->vrHora;
    }

    /**
     * @param mixed $vrHora
     */
    public function setVrHora($vrHora): void
    {
        $this->vrHora = $vrHora;
    }

    /**
     * @return mixed
     */
    public function getCodigoNovedadProgramacion()
    {
        return $this->codigoNovedadProgramacion;
    }

    /**
     * @param mixed $codigoNovedadProgramacion
     */
    public function setCodigoNovedadProgramacion($codigoNovedadProgramacion): void
    {
        $this->codigoNovedadProgramacion = $codigoNovedadProgramacion;
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
    public function getVrAbono()
    {
        return $this->vrAbono;
    }

    /**
     * @param mixed $vrAbono
     */
    public function setVrAbono($vrAbono): void
    {
        $this->vrAbono = $vrAbono;
    }

    /**
     * @return mixed
     */
    public function getVrIbcPropuesto()
    {
        return $this->vrIbcPropuesto;
    }

    /**
     * @param mixed $vrIbcPropuesto
     */
    public function setVrIbcPropuesto($vrIbcPropuesto): void
    {
        $this->vrIbcPropuesto = $vrIbcPropuesto;
    }

    /**
     * @return mixed
     */
    public function getVrPropuesto()
    {
        return $this->vrPropuesto;
    }

    /**
     * @param mixed $vrPropuesto
     */
    public function setVrPropuesto($vrPropuesto): void
    {
        $this->vrPropuesto = $vrPropuesto;
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
    public function getEntidadSaludRel()
    {
        return $this->entidadSaludRel;
    }

    /**
     * @param mixed $entidadSaludRel
     */
    public function setEntidadSaludRel($entidadSaludRel): void
    {
        $this->entidadSaludRel = $entidadSaludRel;
    }

    /**
     * @return mixed
     */
    public function getPagosDetallesLicenciaRel()
    {
        return $this->pagosDetallesLicenciaRel;
    }

    /**
     * @param mixed $pagosDetallesLicenciaRel
     */
    public function setPagosDetallesLicenciaRel($pagosDetallesLicenciaRel): void
    {
        $this->pagosDetallesLicenciaRel = $pagosDetallesLicenciaRel;
    }

    /**
     * @return mixed
     */
    public function getLicenciaTipoRel()
    {
        return $this->licenciaTipoRel;
    }

    /**
     * @param mixed $licenciaTipoRel
     */
    public function setLicenciaTipoRel($licenciaTipoRel): void
    {
        $this->licenciaTipoRel = $licenciaTipoRel;
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
    public function getGrupoRel()
    {
        return $this->grupoRel;
    }

    /**
     * @param mixed $grupoRel
     */
    public function setGrupoRel($grupoRel): void
    {
        $this->grupoRel = $grupoRel;
    }



}
