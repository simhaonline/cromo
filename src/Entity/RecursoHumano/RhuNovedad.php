<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuNovedadRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuNovedad
{
    public $infoLog = [
        "primaryKey" => "codigoNovedadPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_novedad_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoNovedadPk;
    
    /**
     * @ORM\Column(name="codigo_novedad_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoNovedadTipoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="codigo_entidad_fk", type="integer", nullable=true)
     */
    private $codigoEntidadFk;

    /**
     * @ORM\Column(name="codigo_grupo_fk", type="string",length=10, nullable=true)
     */
    private $codigoGrupoFk;

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
     * @ORM\Column(name="dias", options={"default" : 0}, type="float", nullable=true)
     */
    private $dias = 0;

    /**
     * @ORM\Column(name="comentarios", type="text", nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="usuario", type="string", length=25, nullable=true)
     */
    private $usuario;

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
     * @ORM\Column(name="prorroga", options={"default" : false}, type="boolean", nullable=true)
     */
    private $prorroga = false;

    /**
     * @ORM\Column(name="transcripcion", options={"default" : false}, type="boolean", nullable=true)
     */
    private $transcripcion = false;

    /**
     * @ORM\Column(name="legalizado", options={"default" : false}, type="boolean", nullable=true)
     */
    private $legalizado = false;

    /**
     * @ORM\Column(name="porcentaje", options={"default" : 0}, type="float", nullable=true)
     */
    private $porcentaje = 0;

    /**
     * @ORM\Column(name="vr_valor", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrValor = 0;

    /**
     * @ORM\Column(name="vr_ibc", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrIbc = 0;

    /**
     * @ORM\Column(name="dias_ibc", options={"default" : 0}, type="float", nullable=true)
     */
    private $diasIbc = 0;

    /**
     * @ORM\Column(name="vr_ibc_propuesto", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrIbcPropuesto = 0;

    /**
     * @ORM\Column(name="vr_propuesto", options={"default" : 0}, type="float", nullable=true)
     */
    private $vrPropuesto = 0;

    /**
     * @ORM\ManyToOne(targetEntity="RhuNovedadTipo", inversedBy="novedadesNovedadTipoRel")
     * @ORM\JoinColumn(name="codigo_novedad_tipo_fk", referencedColumnName="codigo_novedad_tipo_pk")
     */
    protected $novedadTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="novedadesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="novedadesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidad", inversedBy="novedadesEntidadRel")
     * @ORM\JoinColumn(name="codigo_entidad_fk", referencedColumnName="codigo_entidad_pk")
     */
    protected $entidadRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuGrupo", inversedBy="novedadesGrupoRel")
     * @ORM\JoinColumn(name="codigo_grupo_fk", referencedColumnName="codigo_grupo_pk")
     */
    protected $grupoRel;

    /**
     * @return mixed
     */
    public function getCodigoNovedadPk()
    {
        return $this->codigoNovedadPk;
    }

    /**
     * @param mixed $codigoNovedadPk
     */
    public function setCodigoNovedadPk($codigoNovedadPk): void
    {
        $this->codigoNovedadPk = $codigoNovedadPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoNovedadTipoFk()
    {
        return $this->codigoNovedadTipoFk;
    }

    /**
     * @param mixed $codigoNovedadTipoFk
     */
    public function setCodigoNovedadTipoFk($codigoNovedadTipoFk): void
    {
        $this->codigoNovedadTipoFk = $codigoNovedadTipoFk;
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
    public function getCodigoEntidadFk()
    {
        return $this->codigoEntidadFk;
    }

    /**
     * @param mixed $codigoEntidadFk
     */
    public function setCodigoEntidadFk($codigoEntidadFk): void
    {
        $this->codigoEntidadFk = $codigoEntidadFk;
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
    public function getProrroga()
    {
        return $this->prorroga;
    }

    /**
     * @param mixed $prorroga
     */
    public function setProrroga($prorroga): void
    {
        $this->prorroga = $prorroga;
    }

    /**
     * @return mixed
     */
    public function getTranscripcion()
    {
        return $this->transcripcion;
    }

    /**
     * @param mixed $transcripcion
     */
    public function setTranscripcion($transcripcion): void
    {
        $this->transcripcion = $transcripcion;
    }

    /**
     * @return mixed
     */
    public function getLegalizado()
    {
        return $this->legalizado;
    }

    /**
     * @param mixed $legalizado
     */
    public function setLegalizado($legalizado): void
    {
        $this->legalizado = $legalizado;
    }

    /**
     * @return mixed
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * @param mixed $porcentaje
     */
    public function setPorcentaje($porcentaje): void
    {
        $this->porcentaje = $porcentaje;
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
    public function getVrIbc()
    {
        return $this->vrIbc;
    }

    /**
     * @param mixed $vrIbc
     */
    public function setVrIbc($vrIbc): void
    {
        $this->vrIbc = $vrIbc;
    }

    /**
     * @return mixed
     */
    public function getDiasIbc()
    {
        return $this->diasIbc;
    }

    /**
     * @param mixed $diasIbc
     */
    public function setDiasIbc($diasIbc): void
    {
        $this->diasIbc = $diasIbc;
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
    public function getNovedadTipoRel()
    {
        return $this->novedadTipoRel;
    }

    /**
     * @param mixed $novedadTipoRel
     */
    public function setNovedadTipoRel($novedadTipoRel): void
    {
        $this->novedadTipoRel = $novedadTipoRel;
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
    public function getEntidadRel()
    {
        return $this->entidadRel;
    }

    /**
     * @param mixed $entidadRel
     */
    public function setEntidadRel($entidadRel): void
    {
        $this->entidadRel = $entidadRel;
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
