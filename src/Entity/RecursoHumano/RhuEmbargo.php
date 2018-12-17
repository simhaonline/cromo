<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuEmbargoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuEmbargo
{
    public $infoLog = [
        "primaryKey" => "codigoEmbargoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_embargo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEmbargoPk;

    /**
     * @ORM\Column(name="codigo_embargo_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoEmbargoTipoFk;

    /**
     * @ORM\Column(name="codigo_embargo_juzgado_fk", type="string", length=30 ,nullable=true)
     */
    private $codigoEmbargoJuzgadoFk;

    /**
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(name="numero", type="string", length=30, nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="estado_activo", type="boolean",options={"default":false})
     */
    private $estadoActivo = false;

    /**
     * @ORM\Column(name="estado_autorizado", options={"default" : false}, type="boolean")
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", options={"default" : false}, type="boolean")
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", options={"default" : false}, type="boolean")
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="valor_fijo", type="boolean", nullable=true,options={"default":false})
     */
    private $valorFijo = false;

    /**
     * @ORM\Column(name="oficina_destino", type="string", length=4, nullable=true)
     * @Assert\Length(
     *      max = 4,
     *      maxMessage = "El codigo de la oficina no debe contener mas de 4 dígitos"
     * )
     */
    private $oficinaDestino;

    /**
     * @ORM\Column(name="consecutivo_juzgado", type="string", length=5, nullable=true)
     * @Assert\Length(
     *      max = 5,
     *      maxMessage = "El consecutivo no debe contener mas de 5 dígitos"
     * )
     */
    private $consecutivoJuzgado;

    /**
     * @ORM\Column(name="codigo_instancia", type="string", length=2, nullable=true)
     * @Assert\Length(
     *      max = 2,
     *      maxMessage = "El codigo de la instancia no debe contener mas de 2 dígitos"
     * )
     */
    private $codigoInstancia;

    /**
     * @ORM\Column(name="porcentaje_devengado", type="boolean", nullable=true,options={"default":false})
     */
    private $porcentajeDevengado = false;

    /**
     * @ORM\Column(name="porcentaje_devengado_prestacional", type="boolean", nullable=true,options={"default":false})
     */
    private $porcentajeDevengadoPrestacional = false;

    /**
     * @ORM\Column(name="porcentaje_devengado_prestacional_menos_descuento_ley", type="boolean", nullable=true,options={"default":false})
     */
    private $porcentajeDevengadoPrestacionalMenosDescuentoLey = false;

    /**
     * @ORM\Column(name="porcentaje_devengado_prestacional_menos_descuento_ley_transporte", type="boolean", nullable=true,options={"default":false})
     */
    private $porcentajeDevengadoPrestacionalMenosDescuentoLeyTransporte = false;

    /**
     * @ORM\Column(name="porcentaje_devengado_menos_descuento_ley", type="boolean", nullable=true,options={"default":false})
     */
    private $porcentajeDevengadoMenosDescuentoLey = false;

    /**
     * @ORM\Column(name="porcentaje_devengado_menos_descuento_ley_transporte", type="boolean", nullable=true,options={"default":false})
     */
    private $porcentajeDevengadoMenosDescuentoLeyTransporte = false;

    /**
     * @ORM\Column(name="porcentaje_exceda_salario_minimo", type="boolean", nullable=true,options={"default":false})
     */
    private $porcentajeExcedaSalarioMinimo = false;

    /**
     * @ORM\Column(name="porcentaje_salario_minimo", type="boolean", nullable=true,options={"default":false})
     */
    private $porcentajeSalarioMinimo = false;

    /**
     * @ORM\Column(name="partes_exceda_salario_minimo", type="boolean", nullable=true,options={"default":false})
     */
    private $partesExcedaSalarioMinimo = false;

    /**
     * @ORM\Column(name="partes_exceda_salario_minimo_menos_descuento_ley", type="boolean", nullable=true,options={"default":false})
     */
    private $partesExcedaSalarioMinimoMenosDescuentoLey = false;

    /**
     * @ORM\Column(name="partes", type="float")
     */
    private $partes = 0;

    /**
     * @ORM\Column(name="vr_valor", type="float", nullable=true)
     */
    private $vrValor = 0;

    /**
     * @ORM\Column(name="vr_porcentaje", type="float", nullable=true)
     */
    private $vrPorcentaje = 0;

    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */
    private $codigoUsuario;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="cuenta", type="string", length=50, nullable=true)
     */
    private $cuenta;

    /**
     * @ORM\Column(name="tipo_cuenta", type="string", length=50, nullable=true)
     * @Assert\Length(
     *     max=50,
     *     maxMessage="El campo no puede contener mas de 50 caracteres"
     * )
     */
    private $tipoCuenta;

    /**
     * @ORM\Column(name="numero_expediente", type="string", length=50, nullable=true)
     */
    private $numeroExpediente;

    /**
     * @ORM\Column(name="numero_proceso", type="string", length=50, nullable=true)
     */
    private $numeroProceso;

    /**
     * @ORM\Column(name="numero_radicado", type="string", length=50, nullable=true)
     */
    private $numeroRadicado;

    /**
     * @ORM\Column(name="oficio", type="string", length=50, nullable=true)
     */
    private $oficio;

    /**
     * @ORM\Column(name="oficio_inactivacion", type="string", length=50, nullable=true)
     */
    private $oficioInactivacion;

    /**
     * @ORM\Column(name="fecha_inicio_folio", type="date", nullable=true)
     */
    private $fechaInicioFolio;

    /**
     * @ORM\Column(name="fecha_inactivacion", type="date", nullable=true)
     */
    private $fechaInactivacion;

    /**
     *
     * @ORM\Column(name="numero_identificacion_demandante", type="string", length=20, nullable=true)
     */
    private $numeroIdentificacionDemandante;

    /**
     *
     * @ORM\Column(name="nombre_corto_demandante", type="string", length=80, nullable=true)
     */
    private $nombreCortoDemandante;

    /**
     *
     * @ORM\Column(name="apellidos_demandante", type="string", length=80, nullable=true)
     */
    private $apellidosDemandante;

    /**
     *
     * @ORM\Column(name="numero_identificacion_beneficiario", type="string", length=20, nullable=true)
     */
    private $numeroIdentificacionBeneficiario;
    /**
     *
     * @ORM\Column(name="oficina", type="string", length=30, nullable=true)
     */
    private $oficina;

    /**
     * @ORM\Column(name="vr_monto_maximo", type="float", nullable=true)
     */
    private $VrMontoMaximo;

    /**
     *
     * @ORM\Column(name="saldo", type="float", nullable=true)
     */
    private $saldo;

    /**
     * @ORM\Column(name="descuento", type="float", nullable=true)
     */
    private $descuento;

    /**
     * @ORM\Column(name="validar_monto_maximo", type="boolean", nullable=true,options={"default":false})
     */
    private $validarMontoMaximo = false;

    /**
     *
     * @ORM\Column(name="nombre_corto_beneficiario", type="string", length=80, nullable=true)
     */
    private $nombreCortoBeneficiario;

    /**
     * @ORM\Column(name="afecta_nomina", type="boolean", nullable=true,options={"default":false})
     */
    private $afectaNomina = false;

    /**
     * @ORM\Column(name="afecta_vacacion", type="boolean", nullable=true,options={"default":false})
     */
    private $afectaVacacion = false;

    /**
     * @ORM\Column(name="afecta_prima", type="boolean", nullable=true,options={"default":false})
     */
    private $afectaPrima = false;

    /**
     * @ORM\Column(name="afecta_liquidacion", type="boolean", nullable=true,options={"default":false})
     */
    private $afectaLiquidacion = false;

    /**
     * @ORM\Column(name="afecta_cesantia", type="boolean", nullable=true,options={"default":false})
     */
    private $afectaCesantia = false;

    /**
     * @ORM\Column(name="afecta_indemnizacion", type="boolean", nullable=true,options={"default":false})
     */
    private $afectaIndemnizacion = false;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmbargoTipo", inversedBy="embargosEmbargoTipoRel")
     * @ORM\JoinColumn(name="codigo_embargo_tipo_fk", referencedColumnName="codigo_embargo_tipo_pk")
     * @Assert\NotBlank(
     *     message="Este campo no puede estar vacio"
     * )
     */
    protected $embargoTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="embargosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmbargoJuzgado", inversedBy="embargosEmbargoJuzgadoRel")
     * @ORM\JoinColumn(name="codigo_embargo_juzgado_fk", referencedColumnName="codigo_embargo_juzgado_pk")
     */
    protected $embargoJuzgadoRel;

    /**
     * @return mixed
     */
    public function getCodigoEmbargoPk()
    {
        return $this->codigoEmbargoPk;
    }

    /**
     * @param mixed $codigoEmbargoPk
     */
    public function setCodigoEmbargoPk($codigoEmbargoPk): void
    {
        $this->codigoEmbargoPk = $codigoEmbargoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEmbargoTipoFk()
    {
        return $this->codigoEmbargoTipoFk;
    }

    /**
     * @param mixed $codigoEmbargoTipoFk
     */
    public function setCodigoEmbargoTipoFk($codigoEmbargoTipoFk): void
    {
        $this->codigoEmbargoTipoFk = $codigoEmbargoTipoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEmbargoJuzgadoFk()
    {
        return $this->codigoEmbargoJuzgadoFk;
    }

    /**
     * @param mixed $codigoEmbargoJuzgadoFk
     */
    public function setCodigoEmbargoJuzgadoFk($codigoEmbargoJuzgadoFk): void
    {
        $this->codigoEmbargoJuzgadoFk = $codigoEmbargoJuzgadoFk;
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
    public function getEstadoActivo()
    {
        return $this->estadoActivo;
    }

    /**
     * @param mixed $estadoActivo
     */
    public function setEstadoActivo($estadoActivo): void
    {
        $this->estadoActivo = $estadoActivo;
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
    public function getValorFijo()
    {
        return $this->valorFijo;
    }

    /**
     * @param mixed $valorFijo
     */
    public function setValorFijo($valorFijo): void
    {
        $this->valorFijo = $valorFijo;
    }

    /**
     * @return mixed
     */
    public function getOficinaDestino()
    {
        return $this->oficinaDestino;
    }

    /**
     * @param mixed $oficinaDestino
     */
    public function setOficinaDestino($oficinaDestino): void
    {
        $this->oficinaDestino = $oficinaDestino;
    }

    /**
     * @return mixed
     */
    public function getConsecutivoJuzgado()
    {
        return $this->consecutivoJuzgado;
    }

    /**
     * @param mixed $consecutivoJuzgado
     */
    public function setConsecutivoJuzgado($consecutivoJuzgado): void
    {
        $this->consecutivoJuzgado = $consecutivoJuzgado;
    }

    /**
     * @return mixed
     */
    public function getCodigoInstancia()
    {
        return $this->codigoInstancia;
    }

    /**
     * @param mixed $codigoInstancia
     */
    public function setCodigoInstancia($codigoInstancia): void
    {
        $this->codigoInstancia = $codigoInstancia;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeDevengado()
    {
        return $this->porcentajeDevengado;
    }

    /**
     * @param mixed $porcentajeDevengado
     */
    public function setPorcentajeDevengado($porcentajeDevengado): void
    {
        $this->porcentajeDevengado = $porcentajeDevengado;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeDevengadoPrestacional()
    {
        return $this->porcentajeDevengadoPrestacional;
    }

    /**
     * @param mixed $porcentajeDevengadoPrestacional
     */
    public function setPorcentajeDevengadoPrestacional($porcentajeDevengadoPrestacional): void
    {
        $this->porcentajeDevengadoPrestacional = $porcentajeDevengadoPrestacional;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeDevengadoPrestacionalMenosDescuentoLey()
    {
        return $this->porcentajeDevengadoPrestacionalMenosDescuentoLey;
    }

    /**
     * @param mixed $porcentajeDevengadoPrestacionalMenosDescuentoLey
     */
    public function setPorcentajeDevengadoPrestacionalMenosDescuentoLey($porcentajeDevengadoPrestacionalMenosDescuentoLey): void
    {
        $this->porcentajeDevengadoPrestacionalMenosDescuentoLey = $porcentajeDevengadoPrestacionalMenosDescuentoLey;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeDevengadoPrestacionalMenosDescuentoLeyTransporte()
    {
        return $this->porcentajeDevengadoPrestacionalMenosDescuentoLeyTransporte;
    }

    /**
     * @param mixed $porcentajeDevengadoPrestacionalMenosDescuentoLeyTransporte
     */
    public function setPorcentajeDevengadoPrestacionalMenosDescuentoLeyTransporte($porcentajeDevengadoPrestacionalMenosDescuentoLeyTransporte): void
    {
        $this->porcentajeDevengadoPrestacionalMenosDescuentoLeyTransporte = $porcentajeDevengadoPrestacionalMenosDescuentoLeyTransporte;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeDevengadoMenosDescuentoLey()
    {
        return $this->porcentajeDevengadoMenosDescuentoLey;
    }

    /**
     * @param mixed $porcentajeDevengadoMenosDescuentoLey
     */
    public function setPorcentajeDevengadoMenosDescuentoLey($porcentajeDevengadoMenosDescuentoLey): void
    {
        $this->porcentajeDevengadoMenosDescuentoLey = $porcentajeDevengadoMenosDescuentoLey;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeDevengadoMenosDescuentoLeyTransporte()
    {
        return $this->porcentajeDevengadoMenosDescuentoLeyTransporte;
    }

    /**
     * @param mixed $porcentajeDevengadoMenosDescuentoLeyTransporte
     */
    public function setPorcentajeDevengadoMenosDescuentoLeyTransporte($porcentajeDevengadoMenosDescuentoLeyTransporte): void
    {
        $this->porcentajeDevengadoMenosDescuentoLeyTransporte = $porcentajeDevengadoMenosDescuentoLeyTransporte;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeExcedaSalarioMinimo()
    {
        return $this->porcentajeExcedaSalarioMinimo;
    }

    /**
     * @param mixed $porcentajeExcedaSalarioMinimo
     */
    public function setPorcentajeExcedaSalarioMinimo($porcentajeExcedaSalarioMinimo): void
    {
        $this->porcentajeExcedaSalarioMinimo = $porcentajeExcedaSalarioMinimo;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeSalarioMinimo()
    {
        return $this->porcentajeSalarioMinimo;
    }

    /**
     * @param mixed $porcentajeSalarioMinimo
     */
    public function setPorcentajeSalarioMinimo($porcentajeSalarioMinimo): void
    {
        $this->porcentajeSalarioMinimo = $porcentajeSalarioMinimo;
    }

    /**
     * @return mixed
     */
    public function getPartesExcedaSalarioMinimo()
    {
        return $this->partesExcedaSalarioMinimo;
    }

    /**
     * @param mixed $partesExcedaSalarioMinimo
     */
    public function setPartesExcedaSalarioMinimo($partesExcedaSalarioMinimo): void
    {
        $this->partesExcedaSalarioMinimo = $partesExcedaSalarioMinimo;
    }

    /**
     * @return mixed
     */
    public function getPartesExcedaSalarioMinimoMenosDescuentoLey()
    {
        return $this->partesExcedaSalarioMinimoMenosDescuentoLey;
    }

    /**
     * @param mixed $partesExcedaSalarioMinimoMenosDescuentoLey
     */
    public function setPartesExcedaSalarioMinimoMenosDescuentoLey($partesExcedaSalarioMinimoMenosDescuentoLey): void
    {
        $this->partesExcedaSalarioMinimoMenosDescuentoLey = $partesExcedaSalarioMinimoMenosDescuentoLey;
    }

    /**
     * @return mixed
     */
    public function getPartes()
    {
        return $this->partes;
    }

    /**
     * @param mixed $partes
     */
    public function setPartes($partes): void
    {
        $this->partes = $partes;
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
    public function getVrPorcentaje()
    {
        return $this->vrPorcentaje;
    }

    /**
     * @param mixed $vrPorcentaje
     */
    public function setVrPorcentaje($vrPorcentaje): void
    {
        $this->vrPorcentaje = $vrPorcentaje;
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
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * @param mixed $cuenta
     */
    public function setCuenta($cuenta): void
    {
        $this->cuenta = $cuenta;
    }

    /**
     * @return mixed
     */
    public function getTipoCuenta()
    {
        return $this->tipoCuenta;
    }

    /**
     * @param mixed $tipoCuenta
     */
    public function setTipoCuenta($tipoCuenta): void
    {
        $this->tipoCuenta = $tipoCuenta;
    }

    /**
     * @return mixed
     */
    public function getNumeroExpediente()
    {
        return $this->numeroExpediente;
    }

    /**
     * @param mixed $numeroExpediente
     */
    public function setNumeroExpediente($numeroExpediente): void
    {
        $this->numeroExpediente = $numeroExpediente;
    }

    /**
     * @return mixed
     */
    public function getNumeroProceso()
    {
        return $this->numeroProceso;
    }

    /**
     * @param mixed $numeroProceso
     */
    public function setNumeroProceso($numeroProceso): void
    {
        $this->numeroProceso = $numeroProceso;
    }

    /**
     * @return mixed
     */
    public function getNumeroRadicado()
    {
        return $this->numeroRadicado;
    }

    /**
     * @param mixed $numeroRadicado
     */
    public function setNumeroRadicado($numeroRadicado): void
    {
        $this->numeroRadicado = $numeroRadicado;
    }

    /**
     * @return mixed
     */
    public function getOficio()
    {
        return $this->oficio;
    }

    /**
     * @param mixed $oficio
     */
    public function setOficio($oficio): void
    {
        $this->oficio = $oficio;
    }

    /**
     * @return mixed
     */
    public function getOficioInactivacion()
    {
        return $this->oficioInactivacion;
    }

    /**
     * @param mixed $oficioInactivacion
     */
    public function setOficioInactivacion($oficioInactivacion): void
    {
        $this->oficioInactivacion = $oficioInactivacion;
    }

    /**
     * @return mixed
     */
    public function getFechaInicioFolio()
    {
        return $this->fechaInicioFolio;
    }

    /**
     * @param mixed $fechaInicioFolio
     */
    public function setFechaInicioFolio($fechaInicioFolio): void
    {
        $this->fechaInicioFolio = $fechaInicioFolio;
    }

    /**
     * @return mixed
     */
    public function getFechaInactivacion()
    {
        return $this->fechaInactivacion;
    }

    /**
     * @param mixed $fechaInactivacion
     */
    public function setFechaInactivacion($fechaInactivacion): void
    {
        $this->fechaInactivacion = $fechaInactivacion;
    }

    /**
     * @return mixed
     */
    public function getNumeroIdentificacionDemandante()
    {
        return $this->numeroIdentificacionDemandante;
    }

    /**
     * @param mixed $numeroIdentificacionDemandante
     */
    public function setNumeroIdentificacionDemandante($numeroIdentificacionDemandante): void
    {
        $this->numeroIdentificacionDemandante = $numeroIdentificacionDemandante;
    }

    /**
     * @return mixed
     */
    public function getNombreCortoDemandante()
    {
        return $this->nombreCortoDemandante;
    }

    /**
     * @param mixed $nombreCortoDemandante
     */
    public function setNombreCortoDemandante($nombreCortoDemandante): void
    {
        $this->nombreCortoDemandante = $nombreCortoDemandante;
    }

    /**
     * @return mixed
     */
    public function getApellidosDemandante()
    {
        return $this->apellidosDemandante;
    }

    /**
     * @param mixed $apellidosDemandante
     */
    public function setApellidosDemandante($apellidosDemandante): void
    {
        $this->apellidosDemandante = $apellidosDemandante;
    }

    /**
     * @return mixed
     */
    public function getNumeroIdentificacionBeneficiario()
    {
        return $this->numeroIdentificacionBeneficiario;
    }

    /**
     * @param mixed $numeroIdentificacionBeneficiario
     */
    public function setNumeroIdentificacionBeneficiario($numeroIdentificacionBeneficiario): void
    {
        $this->numeroIdentificacionBeneficiario = $numeroIdentificacionBeneficiario;
    }

    /**
     * @return mixed
     */
    public function getOficina()
    {
        return $this->oficina;
    }

    /**
     * @param mixed $oficina
     */
    public function setOficina($oficina): void
    {
        $this->oficina = $oficina;
    }

    /**
     * @return mixed
     */
    public function getVrMontoMaximo()
    {
        return $this->VrMontoMaximo;
    }

    /**
     * @param mixed $VrMontoMaximo
     */
    public function setVrMontoMaximo($VrMontoMaximo): void
    {
        $this->VrMontoMaximo = $VrMontoMaximo;
    }

    /**
     * @return mixed
     */
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * @param mixed $saldo
     */
    public function setSaldo($saldo): void
    {
        $this->saldo = $saldo;
    }

    /**
     * @return mixed
     */
    public function getDescuento()
    {
        return $this->descuento;
    }

    /**
     * @param mixed $descuento
     */
    public function setDescuento($descuento): void
    {
        $this->descuento = $descuento;
    }

    /**
     * @return mixed
     */
    public function getValidarMontoMaximo()
    {
        return $this->validarMontoMaximo;
    }

    /**
     * @param mixed $validarMontoMaximo
     */
    public function setValidarMontoMaximo($validarMontoMaximo): void
    {
        $this->validarMontoMaximo = $validarMontoMaximo;
    }

    /**
     * @return mixed
     */
    public function getNombreCortoBeneficiario()
    {
        return $this->nombreCortoBeneficiario;
    }

    /**
     * @param mixed $nombreCortoBeneficiario
     */
    public function setNombreCortoBeneficiario($nombreCortoBeneficiario): void
    {
        $this->nombreCortoBeneficiario = $nombreCortoBeneficiario;
    }

    /**
     * @return mixed
     */
    public function getAfectaNomina()
    {
        return $this->afectaNomina;
    }

    /**
     * @param mixed $afectaNomina
     */
    public function setAfectaNomina($afectaNomina): void
    {
        $this->afectaNomina = $afectaNomina;
    }

    /**
     * @return mixed
     */
    public function getAfectaVacacion()
    {
        return $this->afectaVacacion;
    }

    /**
     * @param mixed $afectaVacacion
     */
    public function setAfectaVacacion($afectaVacacion): void
    {
        $this->afectaVacacion = $afectaVacacion;
    }

    /**
     * @return mixed
     */
    public function getAfectaPrima()
    {
        return $this->afectaPrima;
    }

    /**
     * @param mixed $afectaPrima
     */
    public function setAfectaPrima($afectaPrima): void
    {
        $this->afectaPrima = $afectaPrima;
    }

    /**
     * @return mixed
     */
    public function getAfectaLiquidacion()
    {
        return $this->afectaLiquidacion;
    }

    /**
     * @param mixed $afectaLiquidacion
     */
    public function setAfectaLiquidacion($afectaLiquidacion): void
    {
        $this->afectaLiquidacion = $afectaLiquidacion;
    }

    /**
     * @return mixed
     */
    public function getAfectaCesantia()
    {
        return $this->afectaCesantia;
    }

    /**
     * @param mixed $afectaCesantia
     */
    public function setAfectaCesantia($afectaCesantia): void
    {
        $this->afectaCesantia = $afectaCesantia;
    }

    /**
     * @return mixed
     */
    public function getAfectaIndemnizacion()
    {
        return $this->afectaIndemnizacion;
    }

    /**
     * @param mixed $afectaIndemnizacion
     */
    public function setAfectaIndemnizacion($afectaIndemnizacion): void
    {
        $this->afectaIndemnizacion = $afectaIndemnizacion;
    }

    /**
     * @return mixed
     */
    public function getEmbargoTipoRel()
    {
        return $this->embargoTipoRel;
    }

    /**
     * @param mixed $embargoTipoRel
     */
    public function setEmbargoTipoRel($embargoTipoRel): void
    {
        $this->embargoTipoRel = $embargoTipoRel;
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
    public function getEmbargoJuzgadoRel()
    {
        return $this->embargoJuzgadoRel;
    }

    /**
     * @param mixed $embargoJuzgadoRel
     */
    public function setEmbargoJuzgadoRel($embargoJuzgadoRel): void
    {
        $this->embargoJuzgadoRel = $embargoJuzgadoRel;
    }
}