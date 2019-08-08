<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAdicionalRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuIncapacidad
{
    public $infoLog = [
        "primaryKey" => "codigoIncapacidadPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_incapacidad_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoIncapacidadPk;

    /**
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_desde", type="date")
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date")
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="numero_eps", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max = 30,
     *     maxMessage="El campo no puede contener mas de 30 caracteres"
     * )
     */
    private $numeroEps;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="codigo_entidad_salud_fk", type="integer", nullable=true)
     */
    private $codigoEntidadSaludFk;

    /**
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="dias_cobro", type="integer")
     */
    private $diasCobro = 0;

    /**
     * @ORM\Column(name="dias_entidad", type="integer", nullable=true)
     */
    private $diasEntidad = 0;

    /**
     * @ORM\Column(name="dias_empresa", type="integer", nullable=true)
     */
    private $diasEmpresa = 0;

    /**
     * @ORM\Column(name="dias_acumulados", type="integer", nullable=true)
     */
    private $diasAcumulados = 0;

    /**
     * @ORM\Column(name="vr_cobro", type="float", nullable=true)
     */
    private $vrCobro = 0;

    /**
     * @ORM\Column(name="codigo_grupo_fk", type="string", length=10, nullable=true)
     */
    private $codigoGrupoFk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_incapacidad_diagnostico_fk", type="integer", nullable=true)
     */
    private $codigoIncapacidadDiagnosticoFk;

    /**
     * @ORM\Column(name="comentarios", type="string", length=2000, nullable=true)
     * @Assert\Length(
     *     max = 2000,
     *     maxMessage="El campo no puede contener mas de 2000 caracteres"
     * )
     */
    private $comentarios;

    /**
     * @ORM\Column(name="codigo_incapacidad_tipo_fk", type="integer", nullable=true)
     */
    private $codigoIncapacidadTipoFk;

    /**
     * @ORM\Column(name="estado_transcripcion", type="boolean")
     */
    private $estadoTranscripcion = false;

    /**
     * @ORM\Column(name="estado_cobrar", type="boolean", nullable=true)
     */
    private $estadoCobrar = false;

    /**
     * @ORM\Column(name="estado_cobrar_cliente", type="boolean", nullable=true)
     */
    private $estadoCobrarCliente = false;

    /**
     * @ORM\Column(name="estado_prorroga", type="boolean", nullable=true)
     */
    private $estadoProrroga = false;

    /**
     * @ORM\Column(name="vr_incapacidad", type="float")
     */
    private $vrIncapacidad = 0;

    /**
     * @ORM\Column(name="vr_propuesto", type="float", nullable=true)
     */
    private $vrPropuesto = 0;

    /**
     * @ORM\Column(name="vr_pagado", type="float")
     */
    private $vrPagado = 0;

    /**
     * @ORM\Column(name="vr_saldo", type="float")
     */
    private $vrSaldo = 0;

    /**
     * @ORM\Column(name="vr_ibc_propuesto", type="float")
     */
    private $vrIbcPropuesto = 0;

    /**
     * @ORM\Column(name="vr_ibc_mes_anterior", type="float")
     */
    private $vrIbcMesAnterior = 0;

    /**
     * @ORM\Column(name="dias_ibc_mes_anterior", type="float")
     */
    private $diasIbcMesAnterior = 0;

    /**
     * @ORM\Column(name="vr_hora", type="float")
     */
    private $vrHora = 0;

    /**
     * @ORM\Column(name="vr_hora_empresa", type="float", nullable=true)
     */
    private $vrHoraEmpresa = 0;

    /**
     * @ORM\Column(name="porcentaje_pago", type="float")
     */
    private $porcentajePago = 0;

    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */
    private $codigoUsuario;

    /**
     * @ORM\Column(name="codigo_cobro_fk", type="integer", nullable=true)
     */
    private $codigoCobroFk;

    /**
     * @ORM\Column(name="estado_legalizado", type="boolean", nullable=true)
     */
    private $estadoLegalizado = false;

    /**
     * @ORM\Column(name="estado_cobrado", type="boolean")
     */
    private $estadoCobrado = false;

    /**
     * @ORM\Column(name="pagar_empleado", type="boolean", nullable=true)
     */
    private $pagarEmpleado = true;

    /**
     * @ORM\Column(name="codigo_incapacidad_prorroga_fk", type="integer", nullable=true)
     */
    private $codigoIncapacidadProrrogaFk;

    /**
     * @ORM\Column(name="fecha_desde_empresa", type="date", nullable=true)
     */
    private $fechaDesdeEmpresa;

    /**
     * @ORM\Column(name="fecha_hasta_empresa", type="date", nullable=true)
     */
    private $fechaHastaEmpresa;

    /**
     * @ORM\Column(name="fecha_desde_entidad", type="date", nullable=true)
     */
    private $fechaDesdeEntidad;

    /**
     * @ORM\Column(name="fecha_hasta_entidad", type="date", nullable=true)
     */
    private $fechaHastaEntidad;

    /**
     * @ORM\Column(name="fecha_documento_fisico", type="date", nullable=true)
     */
    private $fechaDocumentoFisico;

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
     * @ORM\Column(name="medico", type="string", length=50, nullable=true)
     * @Assert\Length(
     *     max = 50,
     *     maxMessage="El campo no puede contener mas de 50 caracteres"
     * )
     */
    private $medico;

    /**
     * @ORM\Column(name="vr_salario", type="float", nullable=true)
     */
    private $vrSalario = 0;

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
     * @ORM\ManyToOne(targetEntity="RhuIncapacidadTipo", inversedBy="incapacidadesIncapacidadTipoRel")
     * @ORM\JoinColumn(name="codigo_incapacidad_tipo_fk", referencedColumnName="codigo_incapacidad_tipo_pk")
     */
    protected $incapacidadTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuGrupo", inversedBy="incapacidadesGrupoRel")
     * @ORM\JoinColumn(name="codigo_grupo_fk", referencedColumnName="codigo_grupo_pk")
     */
    protected $grupoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidad", inversedBy="incapacidadesEntidadSaludRel")
     * @ORM\JoinColumn(name="codigo_entidad_salud_fk", referencedColumnName="codigo_entidad_pk")
     */
    protected $entidadSaludRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="incapacidadesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="incapacidadesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuIncapacidad", inversedBy="incapacidadesIncapacidadProrrogaRel")
     * @ORM\JoinColumn(name="codigo_incapacidad_prorroga_fk", referencedColumnName="codigo_incapacidad_pk")
     */
    protected $incapacidadProrrogaRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuIncapacidadDiagnostico", inversedBy="incapacidadesIncapacidadDiagnosticoRel")
     * @ORM\JoinColumn(name="codigo_incapacidad_diagnostico_fk", referencedColumnName="codigo_incapacidad_diagnostico_pk")
     */
    protected $incapacidadDiagnosticoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuPagoDetalle", mappedBy="incapacidadRel")
     */
    protected $pagosDetallesIncapacidadRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidad", mappedBy="incapacidadProrrogaRel")
     */
    protected $incapacidadesIncapacidadProrrogaRel;

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
    public function getCodigoIncapacidadPk()
    {
        return $this->codigoIncapacidadPk;
    }

    /**
     * @param mixed $codigoIncapacidadPk
     */
    public function setCodigoIncapacidadPk($codigoIncapacidadPk): void
    {
        $this->codigoIncapacidadPk = $codigoIncapacidadPk;
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
    public function getNumeroEps()
    {
        return $this->numeroEps;
    }

    /**
     * @param mixed $numeroEps
     */
    public function setNumeroEps($numeroEps): void
    {
        $this->numeroEps = $numeroEps;
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
    public function getDiasEntidad()
    {
        return $this->diasEntidad;
    }

    /**
     * @param mixed $diasEntidad
     */
    public function setDiasEntidad($diasEntidad): void
    {
        $this->diasEntidad = $diasEntidad;
    }

    /**
     * @return mixed
     */
    public function getDiasEmpresa()
    {
        return $this->diasEmpresa;
    }

    /**
     * @param mixed $diasEmpresa
     */
    public function setDiasEmpresa($diasEmpresa): void
    {
        $this->diasEmpresa = $diasEmpresa;
    }

    /**
     * @return mixed
     */
    public function getDiasAcumulados()
    {
        return $this->diasAcumulados;
    }

    /**
     * @param mixed $diasAcumulados
     */
    public function setDiasAcumulados($diasAcumulados): void
    {
        $this->diasAcumulados = $diasAcumulados;
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
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * @param mixed $codigoClienteFk
     */
    public function setCodigoClienteFk($codigoClienteFk): void
    {
        $this->codigoClienteFk = $codigoClienteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoIncapacidadDiagnosticoFk()
    {
        return $this->codigoIncapacidadDiagnosticoFk;
    }

    /**
     * @param mixed $codigoIncapacidadDiagnosticoFk
     */
    public function setCodigoIncapacidadDiagnosticoFk($codigoIncapacidadDiagnosticoFk): void
    {
        $this->codigoIncapacidadDiagnosticoFk = $codigoIncapacidadDiagnosticoFk;
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
    public function getCodigoIncapacidadTipoFk()
    {
        return $this->codigoIncapacidadTipoFk;
    }

    /**
     * @param mixed $codigoIncapacidadTipoFk
     */
    public function setCodigoIncapacidadTipoFk($codigoIncapacidadTipoFk): void
    {
        $this->codigoIncapacidadTipoFk = $codigoIncapacidadTipoFk;
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
    public function getVrIncapacidad()
    {
        return $this->vrIncapacidad;
    }

    /**
     * @param mixed $vrIncapacidad
     */
    public function setVrIncapacidad($vrIncapacidad): void
    {
        $this->vrIncapacidad = $vrIncapacidad;
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
    public function getVrHoraEmpresa()
    {
        return $this->vrHoraEmpresa;
    }

    /**
     * @param mixed $vrHoraEmpresa
     */
    public function setVrHoraEmpresa($vrHoraEmpresa): void
    {
        $this->vrHoraEmpresa = $vrHoraEmpresa;
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
    public function getCodigoCobroFk()
    {
        return $this->codigoCobroFk;
    }

    /**
     * @param mixed $codigoCobroFk
     */
    public function setCodigoCobroFk($codigoCobroFk): void
    {
        $this->codigoCobroFk = $codigoCobroFk;
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
    public function getEstadoCobrado()
    {
        return $this->estadoCobrado;
    }

    /**
     * @param mixed $estadoCobrado
     */
    public function setEstadoCobrado($estadoCobrado): void
    {
        $this->estadoCobrado = $estadoCobrado;
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
    public function getCodigoIncapacidadProrrogaFk()
    {
        return $this->codigoIncapacidadProrrogaFk;
    }

    /**
     * @param mixed $codigoIncapacidadProrrogaFk
     */
    public function setCodigoIncapacidadProrrogaFk($codigoIncapacidadProrrogaFk): void
    {
        $this->codigoIncapacidadProrrogaFk = $codigoIncapacidadProrrogaFk;
    }

    /**
     * @return mixed
     */
    public function getFechaDesdeEmpresa()
    {
        return $this->fechaDesdeEmpresa;
    }

    /**
     * @param mixed $fechaDesdeEmpresa
     */
    public function setFechaDesdeEmpresa($fechaDesdeEmpresa): void
    {
        $this->fechaDesdeEmpresa = $fechaDesdeEmpresa;
    }

    /**
     * @return mixed
     */
    public function getFechaHastaEmpresa()
    {
        return $this->fechaHastaEmpresa;
    }

    /**
     * @param mixed $fechaHastaEmpresa
     */
    public function setFechaHastaEmpresa($fechaHastaEmpresa): void
    {
        $this->fechaHastaEmpresa = $fechaHastaEmpresa;
    }

    /**
     * @return mixed
     */
    public function getFechaDesdeEntidad()
    {
        return $this->fechaDesdeEntidad;
    }

    /**
     * @param mixed $fechaDesdeEntidad
     */
    public function setFechaDesdeEntidad($fechaDesdeEntidad): void
    {
        $this->fechaDesdeEntidad = $fechaDesdeEntidad;
    }

    /**
     * @return mixed
     */
    public function getFechaHastaEntidad()
    {
        return $this->fechaHastaEntidad;
    }

    /**
     * @param mixed $fechaHastaEntidad
     */
    public function setFechaHastaEntidad($fechaHastaEntidad): void
    {
        $this->fechaHastaEntidad = $fechaHastaEntidad;
    }

    /**
     * @return mixed
     */
    public function getFechaDocumentoFisico()
    {
        return $this->fechaDocumentoFisico;
    }

    /**
     * @param mixed $fechaDocumentoFisico
     */
    public function setFechaDocumentoFisico($fechaDocumentoFisico): void
    {
        $this->fechaDocumentoFisico = $fechaDocumentoFisico;
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
    public function getMedico()
    {
        return $this->medico;
    }

    /**
     * @param mixed $medico
     */
    public function setMedico($medico): void
    {
        $this->medico = $medico;
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
    public function getIncapacidadTipoRel()
    {
        return $this->incapacidadTipoRel;
    }

    /**
     * @param mixed $incapacidadTipoRel
     */
    public function setIncapacidadTipoRel($incapacidadTipoRel): void
    {
        $this->incapacidadTipoRel = $incapacidadTipoRel;
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
    public function getIncapacidadDiagnosticoRel()
    {
        return $this->incapacidadDiagnosticoRel;
    }

    /**
     * @param mixed $incapacidadDiagnosticoRel
     */
    public function setIncapacidadDiagnosticoRel($incapacidadDiagnosticoRel): void
    {
        $this->incapacidadDiagnosticoRel = $incapacidadDiagnosticoRel;
    }

    /**
     * @return mixed
     */
    public function getPagosDetallesIncapacidadRel()
    {
        return $this->pagosDetallesIncapacidadRel;
    }

    /**
     * @param mixed $pagosDetallesIncapacidadRel
     */
    public function setPagosDetallesIncapacidadRel($pagosDetallesIncapacidadRel): void
    {
        $this->pagosDetallesIncapacidadRel = $pagosDetallesIncapacidadRel;
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
    public function getIncapacidadProrrogaRel()
    {
        return $this->incapacidadProrrogaRel;
    }

    /**
     * @param mixed $incapacidadProrrogaRel
     */
    public function setIncapacidadProrrogaRel($incapacidadProrrogaRel): void
    {
        $this->incapacidadProrrogaRel = $incapacidadProrrogaRel;
    }

    /**
     * @return mixed
     */
    public function getIncapacidadesIncapacidadProrrogaRel()
    {
        return $this->incapacidadesIncapacidadProrrogaRel;
    }

    /**
     * @param mixed $incapacidadesIncapacidadProrrogaRel
     */
    public function setIncapacidadesIncapacidadProrrogaRel($incapacidadesIncapacidadProrrogaRel): void
    {
        $this->incapacidadesIncapacidadProrrogaRel = $incapacidadesIncapacidadProrrogaRel;
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
