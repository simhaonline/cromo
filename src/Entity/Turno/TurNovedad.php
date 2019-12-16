<?php

namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;

/**
 *  @ORM\Entity(repositoryClass="App\Repository\Turno\TurNovedadRepository")
 */
class TurNovedad
{

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_novedad_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoNovedadPk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_novedad_tipo_fk", type="integer", nullable=true)
     */
    private $codigoNovedadTipoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_empleado_reemplazo_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoReemplazoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */
    private $codigoContratoFk;

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
     * @ORM\Column(name="estado_autorizado", type="boolean", options={"default":false})
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean", options={"default":false})
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean", options={"default":false})
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="estado_aplicada", type="boolean")
     */
    private $estadoAplicada = false;

    /**
     * @ORM\Column(name="origen", type="string", length=3, nullable=true)
     */
    private $origen;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", inversedBy="turNovedadesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", inversedBy="novedadesEmpeladoReemplazoRel")
     * @ORM\JoinColumn(name="codigo_empleado_reemplazo_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoReemplazoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurNovedadTipo", inversedBy="novedadesNovedadTipoRel")
     * @ORM\JoinColumn(name="codigo_novedad_tipo_fk", referencedColumnName="codigo_novedad_tipo_pk" )
     */
    protected $novedadTipoRel;

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
     * @return bool
     */
    public function getEstadoAplicada()
    {
        return $this->estadoAplicada;
    }

    /**
     * @param bool $estadoAplicada
     */
    public function setEstadoAplicada(bool $estadoAplicada): void
    {
        $this->estadoAplicada = $estadoAplicada;
    }

    /**
     * @return bool
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * @param bool $estadoAutorizado
     */
    public function setEstadoAutorizado(bool $estadoAutorizado): void
    {
        $this->estadoAutorizado = $estadoAutorizado;
    }

    /**
     * @return bool
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * @param bool $estadoAprobado
     */
    public function setEstadoAprobado(bool $estadoAprobado): void
    {
        $this->estadoAprobado = $estadoAprobado;
    }

    /**
     * @return bool
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * @param bool $estadoAnulado
     */
    public function setEstadoAnulado(bool $estadoAnulado): void
    {
        $this->estadoAnulado = $estadoAnulado;
    }

    /**
     * @return mixed
     */
    public function getOrigen()
    {
        return $this->origen;
    }

    /**
     * @param mixed $origen
     */
    public function setOrigen($origen): void
    {
        $this->origen = $origen;
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
    public function getEmpleadoReemplazoRel()
    {
        return $this->empleadoReemplazoRel;
    }

    /**
     * @param mixed $empleadoReemplazoRel
     */
    public function setEmpleadoReemplazoRel($empleadoReemplazoRel): void
    {
        $this->empleadoReemplazoRel = $empleadoReemplazoRel;
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
    public function getCodigoEmpleadoReemplazoFk()
    {
        return $this->codigoEmpleadoReemplazoFk;
    }

    /**
     * @param mixed $codigoEmpleadoReemplazoFk
     */
    public function setCodigoEmpleadoReemplazoFk($codigoEmpleadoReemplazoFk): void
    {
        $this->codigoEmpleadoReemplazoFk = $codigoEmpleadoReemplazoFk;
    }


}
