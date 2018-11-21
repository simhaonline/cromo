<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuEgresoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuEgreso
{
    public $infoLog = [
        "primaryKey" => "codigoEgresoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_egreso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEgresoPk;

    /**
     * @ORM\Column(name="codigo_egreso_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoEgresoTipoFk;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=10, nullable=true)
     */
    private $codigoCuentaFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_trasmision", type="date", nullable=true)
     */
    private $fechaTrasmision;

    /**
     * @ORM\Column(name="fecha_aplicacion", type="date", nullable=true)
     */
    private $fechaAplicacion;

    /**
     * @ORM\Column(name="numero",options={"default" : 0}, type="integer", nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="numero_registros", type="integer")
     */
    private $numeroRegistros = 0;

    /**
     * @ORM\Column(name="estado_autorizado", options={"default" : false}, type="boolean", nullable=true)
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
     * @ORM\Column(name="comentario", type="string", length=200,nullable=true)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEgresoTipo", inversedBy="egresosEgresoTipoRel")
     * @ORM\JoinColumn(name="codigo_egreso_tipo_fk", referencedColumnName="codigo_egreso_tipo_pk")
     */
    protected $egresoTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCuenta", inversedBy="rhuEgresosCuentaRel")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuEgresoDetalle", mappedBy="egresoRel")
     */
    protected $egresosDetallesEgresoRel;

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
    public function getCodigoEgresoTipoFk()
    {
        return $this->codigoEgresoTipoFk;
    }

    /**
     * @param mixed $codigoEgresoTipoFk
     */
    public function setCodigoEgresoTipoFk($codigoEgresoTipoFk): void
    {
        $this->codigoEgresoTipoFk = $codigoEgresoTipoFk;
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
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * @param mixed $vrTotal
     */
    public function setVrTotal($vrTotal): void
    {
        $this->vrTotal = $vrTotal;
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
    public function getEgresoTipoRel()
    {
        return $this->egresoTipoRel;
    }

    /**
     * @param mixed $egresoTipoRel
     */
    public function setEgresoTipoRel($egresoTipoRel): void
    {
        $this->egresoTipoRel = $egresoTipoRel;
    }

    /**
     * @return mixed
     */
    public function getCuentaRel()
    {
        return $this->cuentaRel;
    }

    /**
     * @param mixed $cuentaRel
     */
    public function setCuentaRel($cuentaRel): void
    {
        $this->cuentaRel = $cuentaRel;
    }

    /**
     * @return mixed
     */
    public function getEgresosDetallesEgresoRel()
    {
        return $this->egresosDetallesEgresoRel;
    }

    /**
     * @param mixed $egresosDetallesEgresoRel
     */
    public function setEgresosDetallesEgresoRel($egresosDetallesEgresoRel): void
    {
        $this->egresosDetallesEgresoRel = $egresosDetallesEgresoRel;
    }
}
