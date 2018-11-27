<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuProgramacionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuProgramacion
{
    public $infoLog = [
        "primaryKey" => "codigoProgramacionPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_programacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProgramacionPk;

    /**
     * @ORM\Column(name="codigo_pago_tipo_fk", type="string", length=10, nullable=true)
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
     * @ORM\Column(name="fecha_hasta_periodo",  type="date", nullable=true)
     */
    private $fechaHastaPeriodo;

    /**
     * @ORM\Column(name="nombre", options={"default": 0}, type="string",length=80, nullable=true)
     * @Assert\Length(
     *      max = 80,
     *      maxMessage = "La cantidad máxima de caracteres para el nombre es de 80"
     * )
     */
    private $nombre;

    /**
     * @ORM\Column(name="dias", options={"default": 0}, type="integer")
     */
    private $dias = 0;

    /**
     * @ORM\Column(name="cantidad", options={"default": 0}, type="integer")
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="vr_neto", options={"default": 0}, type="float")
     */
    private $vrNeto = 0;

    /**
     * @ORM\Column(name="codigo_grupo_fk", type="string", length=10, nullable=true)
     */
    private $codigoGrupoFk;

    /**
     * @ORM\Column(name="fecha_pagado", type="datetime", nullable=true)
     */
    private $fechaPagado;

    /**
     * @ORM\Column(name="empelados_generados", options={"default": false}, type="boolean", nullable=true)
     */
    private $empleadosGenerados  = false;

    /**
     * @ORM\Column(name="estado_autorizado", options={"default": false}, type="boolean", nullable=true)
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", options={"default": false}, type="boolean", nullable=true)
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_pagado", options={"default": false}, type="boolean", nullable=true)
     */
    private $estadoPagado = false;

    /**
     * @ORM\Column(name="estado_anulado", options={"default": false}, type="boolean", nullable=true)
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="mensaje_pago", type="text", nullable=true)
     */
    private $mensajePago;

    /**
     * @ORM\Column(name="usuario", type="string",length=25, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="RhuGrupo", inversedBy="programacionesGrupoRel")
     * @ORM\JoinColumn(name="codigo_grupo_fk",referencedColumnName="codigo_grupo_pk")
     */
    protected $grupoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuProgramacionDetalle", mappedBy="programacionRel")
     */
    protected $programacionesDetallesProgramacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoTipo", inversedBy="programacionesPagoTipoRel")
     * @ORM\JoinColumn(name="codigo_pago_tipo_fk", referencedColumnName="codigo_pago_tipo_pk")
     */
    protected $pagoTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuPago", mappedBy="programacionRel")
     */
    protected $pagosProgramacionRel;

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
    public function getFechaHastaPeriodo()
    {
        return $this->fechaHastaPeriodo;
    }

    /**
     * @param mixed $fechaHastaPeriodo
     */
    public function setFechaHastaPeriodo($fechaHastaPeriodo): void
    {
        $this->fechaHastaPeriodo = $fechaHastaPeriodo;
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
    public function getMensajePago()
    {
        return $this->mensajePago;
    }

    /**
     * @param mixed $mensajePago
     */
    public function setMensajePago($mensajePago): void
    {
        $this->mensajePago = $mensajePago;
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

    /**
     * @return mixed
     */
    public function getProgramacionesDetallesProgramacionRel()
    {
        return $this->programacionesDetallesProgramacionRel;
    }

    /**
     * @param mixed $programacionesDetallesProgramacionRel
     */
    public function setProgramacionesDetallesProgramacionRel($programacionesDetallesProgramacionRel): void
    {
        $this->programacionesDetallesProgramacionRel = $programacionesDetallesProgramacionRel;
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
    public function getPagosProgramacionRel()
    {
        return $this->pagosProgramacionRel;
    }

    /**
     * @param mixed $pagosProgramacionRel
     */
    public function setPagosProgramacionRel($pagosProgramacionRel): void
    {
        $this->pagosProgramacionRel = $pagosProgramacionRel;
    }
}