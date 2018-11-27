<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuPagoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuPago
{
    public $infoLog = [
        "primaryKey" => "codigoPagoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoPk;

    /**
     * @ORM\Column(name="codigo_pago_tipo_fk", type="string", length=10,  nullable=true)
     */
    private $codigoPagoTipoFk;

    /**
     * @ORM\Column(name="codigo_entidad_salud_fk", type="integer",  nullable=true)
     */
    private $codigoEntidadSaludFk;

    /**
     * @ORM\Column(name="codigo_entidad_pension_fk", type="integer",  nullable=true)
     */
    private $codigoEntidadPensionFk;

    /**
     * @ORM\Column(name="codigo_periodo_fk", type="string", length=10, nullable=true)
     */
    private $codigoPeriodoFk;

    /**
     * @ORM\Column(name="numero", options={"default" : 0}, type="integer", nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="codigo_programacion_detalle_fk", type="integer", nullable=true)
     */
    private $codigoProgramacionDetalleFk;

    /**
     * @ORM\Column(name="codigo_programacion_fk", type="integer", nullable=true)
     */
    private $codigoProgramacionFk;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="fecha_desde_contrato", type="date", nullable=true)
     */
    private $fechaDesdeContrato;

    /**
     * @ORM\Column(name="fecha_hasta_contrato", type="date", nullable=true)
     */
    private $fechaHastaContrato;

    /**
     * Es el salario que tenia el contrato cuando se genero el pago
     * @ORM\Column(name="vr_salario_contrato", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrSalarioContrato = 0;

    /**
     * @ORM\Column(name="vr_devengado", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrDevengado = 0;

    /**
     * @ORM\Column(name="vr_deduccion", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrDeduccion = 0;

    /**
     * @ORM\Column(name="vr_neto", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrNeto = 0;

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
     * @ORM\Column(name="estado_egreso", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoEgreso = false;

    /**
     * @ORM\Column(name="comentario", type="string", length=500, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\Column(name="usuario", type="string", length=25, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="pagosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk",referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="pagosContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk",referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoTipo", inversedBy="pagosPagoTipoRel")
     * @ORM\JoinColumn(name="codigo_pago_tipo_fk",referencedColumnName="codigo_pago_tipo_pk")
     */
    protected $pagoTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuProgramacionDetalle", inversedBy="pagosProgramacionDetalleRel")
     * @ORM\JoinColumn(name="codigo_programacion_detalle_fk",referencedColumnName="codigo_programacion_detalle_pk")
     */
    protected $programacionDetalleRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidad", inversedBy="pagosEntidadSaludRel")
     * @ORM\JoinColumn(name="codigo_entidad_salud_fk",referencedColumnName="codigo_entidad_pk")
     */
    protected $entidadSaludRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidad", inversedBy="pagosEntidadPensionRel")
     * @ORM\JoinColumn(name="codigo_entidad_pension_fk",referencedColumnName="codigo_entidad_pk")
     */
    protected $entidadPensionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuProgramacion", inversedBy="pagosProgramacionRel")
     * @ORM\JoinColumn(name="codigo_programacion_fk",referencedColumnName="codigo_programacion_pk")
     */
    protected $programacionRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuPagoDetalle", mappedBy="pagoRel" )
     */
    protected $pagosDetallesPagoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuEgresoDetalle", mappedBy="pagoRel" )
     */
    protected $egresosDetallesPagoRel;

    /**
     * @return mixed
     */
    public function getCodigoPagoPk()
    {
        return $this->codigoPagoPk;
    }

    /**
     * @param mixed $codigoPagoPk
     */
    public function setCodigoPagoPk($codigoPagoPk): void
    {
        $this->codigoPagoPk = $codigoPagoPk;
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
    public function getCodigoEntidadPensionFk()
    {
        return $this->codigoEntidadPensionFk;
    }

    /**
     * @param mixed $codigoEntidadPensionFk
     */
    public function setCodigoEntidadPensionFk($codigoEntidadPensionFk): void
    {
        $this->codigoEntidadPensionFk = $codigoEntidadPensionFk;
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
    public function getEstadoEgreso()
    {
        return $this->estadoEgreso;
    }

    /**
     * @param mixed $estadoEgreso
     */
    public function setEstadoEgreso($estadoEgreso): void
    {
        $this->estadoEgreso = $estadoEgreso;
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
    public function getCodigoProgramacionDetalleFk()
    {
        return $this->codigoProgramacionDetalleFk;
    }

    /**
     * @param mixed $codigoProgramacionDetalleFk
     */
    public function setCodigoProgramacionDetalleFk($codigoProgramacionDetalleFk): void
    {
        $this->codigoProgramacionDetalleFk = $codigoProgramacionDetalleFk;
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
    public function getFechaDesdeContrato()
    {
        return $this->fechaDesdeContrato;
    }

    /**
     * @param mixed $fechaDesdeContrato
     */
    public function setFechaDesdeContrato($fechaDesdeContrato): void
    {
        $this->fechaDesdeContrato = $fechaDesdeContrato;
    }

    /**
     * @return mixed
     */
    public function getFechaHastaContrato()
    {
        return $this->fechaHastaContrato;
    }

    /**
     * @param mixed $fechaHastaContrato
     */
    public function setFechaHastaContrato($fechaHastaContrato): void
    {
        $this->fechaHastaContrato = $fechaHastaContrato;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioContrato()
    {
        return $this->vrSalarioContrato;
    }

    /**
     * @param mixed $vrSalarioContrato
     */
    public function setVrSalarioContrato($vrSalarioContrato): void
    {
        $this->vrSalarioContrato = $vrSalarioContrato;
    }

    /**
     * @return mixed
     */
    public function getVrDevengado()
    {
        return $this->vrDevengado;
    }

    /**
     * @param mixed $vrDevengado
     */
    public function setVrDevengado($vrDevengado): void
    {
        $this->vrDevengado = $vrDevengado;
    }

    /**
     * @return mixed
     */
    public function getVrDeduccion()
    {
        return $this->vrDeduccion;
    }

    /**
     * @param mixed $vrDeduccion
     */
    public function setVrDeduccion($vrDeduccion): void
    {
        $this->vrDeduccion = $vrDeduccion;
    }

    /**
     * @return mixed
     */
    public function getVrNeto()
    {
        return $this->vrNeto;
    }

    /**
     * @param mixed $vrNeto
     */
    public function setVrNeto($vrNeto): void
    {
        $this->vrNeto = $vrNeto;
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
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * @param mixed $comentario
     */
    public function setComentario($comentario): void
    {
        $this->comentario = $comentario;
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
    public function getPagoTipoRel()
    {
        return $this->pagoTipoRel;
    }

    /**
     * @param mixed $pagoTipoRel
     */
    public function setPagoTipoRel($pagoTipoRel): void
    {
        $this->pagoTipoRel = $pagoTipoRel;
    }

    /**
     * @return mixed
     */
    public function getProgramacionDetalleRel()
    {
        return $this->programacionDetalleRel;
    }

    /**
     * @param mixed $programacionDetalleRel
     */
    public function setProgramacionDetalleRel($programacionDetalleRel): void
    {
        $this->programacionDetalleRel = $programacionDetalleRel;
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
    public function getEntidadPensionRel()
    {
        return $this->entidadPensionRel;
    }

    /**
     * @param mixed $entidadPensionRel
     */
    public function setEntidadPensionRel($entidadPensionRel): void
    {
        $this->entidadPensionRel = $entidadPensionRel;
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
    public function getPagosDetallesPagoRel()
    {
        return $this->pagosDetallesPagoRel;
    }

    /**
     * @param mixed $pagosDetallesPagoRel
     */
    public function setPagosDetallesPagoRel($pagosDetallesPagoRel): void
    {
        $this->pagosDetallesPagoRel = $pagosDetallesPagoRel;
    }

    /**
     * @return mixed
     */
    public function getEgresosDetallesPagoRel()
    {
        return $this->egresosDetallesPagoRel;
    }

    /**
     * @param mixed $egresosDetallesPagoRel
     */
    public function setEgresosDetallesPagoRel($egresosDetallesPagoRel): void
    {
        $this->egresosDetallesPagoRel = $egresosDetallesPagoRel;
    }
}
