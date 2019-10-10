<?php

namespace App\Entity\Tesoreria;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Tesoreria\TesMovimientoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TesMovimiento
{
    public $infoLog = [
        "primaryKey" => "codigoMovimientoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="codigo_movimiento_pk",type="integer")
     */
    private $codigoMovimientoPk;

    /**
     * @ORM\Column(name="codigo_movimiento_clase_fk" , type="string" , length=10, nullable=true)
     */
    private $codigoMovimientoClaseFk;

    /**
     * @ORM\Column(name="codigo_movimiento_tipo_fk" , type="string" , length=10, nullable=true)
     */
    private $codigoMovimientoTipoFk;

    /**
     * @ORM\Column(name="codigo_tercero_fk" , type="integer")
     */
    private $codigoTerceroFk;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=10, nullable=false)
     */
    private $codigoCuentaFk;

    /**
     * @ORM\Column(name="fecha" ,type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(name="numero" , type="integer")
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="numero_documento" , type="string", length=30, nullable=true)
     */
    private $numeroDocumento;

    /**
     * @ORM\Column(name="comentarios" , type="string", nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="vr_total_neto" ,type="float", nullable=true)
     */
    private $vrTotalNeto = 0;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoContabilizado = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tesoreria\TesMovimientoClase" , inversedBy="movimientosMovimientoClaseRel")
     * @ORM\JoinColumn(name="codigo_movimiento_clase_fk" , referencedColumnName="codigo_movimiento_clase_pk")
     */
    private $movimientoClaseRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tesoreria\TesMovimientoTipo" , inversedBy="movimientosMovimientoTipoRel")
     * @ORM\JoinColumn(name="codigo_movimiento_tipo_fk" , referencedColumnName="codigo_movimiento_tipo_pk")
     */
    private $movimientoTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tesoreria\TesTercero" , inversedBy="movimientosTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk" , referencedColumnName="codigo_tercero_pk")
     */
    private $terceroRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCuenta" , inversedBy="movimientosCuentaRel")
     * @ORM\JoinColumn(name="codigo_cuenta_fk" , referencedColumnName="codigo_cuenta_pk")
     */
    private $cuentaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tesoreria\TesMovimientoDetalle", mappedBy="movimientoRel")
     */
    private $movimientoDetallesMovimientoRel;

    /**
     * @return mixed
     */
    public function getCodigoMovimientoPk()
    {
        return $this->codigoMovimientoPk;
    }

    /**
     * @param mixed $codigoMovimientoPk
     */
    public function setCodigoMovimientoPk($codigoMovimientoPk): void
    {
        $this->codigoMovimientoPk = $codigoMovimientoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoMovimientoClaseFk()
    {
        return $this->codigoMovimientoClaseFk;
    }

    /**
     * @param mixed $codigoMovimientoClaseFk
     */
    public function setCodigoMovimientoClaseFk($codigoMovimientoClaseFk): void
    {
        $this->codigoMovimientoClaseFk = $codigoMovimientoClaseFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoMovimientoTipoFk()
    {
        return $this->codigoMovimientoTipoFk;
    }

    /**
     * @param mixed $codigoMovimientoTipoFk
     */
    public function setCodigoMovimientoTipoFk($codigoMovimientoTipoFk): void
    {
        $this->codigoMovimientoTipoFk = $codigoMovimientoTipoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoTerceroFk()
    {
        return $this->codigoTerceroFk;
    }

    /**
     * @param mixed $codigoTerceroFk
     */
    public function setCodigoTerceroFk($codigoTerceroFk): void
    {
        $this->codigoTerceroFk = $codigoTerceroFk;
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
    public function getVrTotalNeto()
    {
        return $this->vrTotalNeto;
    }

    /**
     * @param mixed $vrTotalNeto
     */
    public function setVrTotalNeto($vrTotalNeto): void
    {
        $this->vrTotalNeto = $vrTotalNeto;
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
    public function getMovimientoClaseRel()
    {
        return $this->movimientoClaseRel;
    }

    /**
     * @param mixed $movimientoClaseRel
     */
    public function setMovimientoClaseRel($movimientoClaseRel): void
    {
        $this->movimientoClaseRel = $movimientoClaseRel;
    }

    /**
     * @return mixed
     */
    public function getMovimientoTipoRel()
    {
        return $this->movimientoTipoRel;
    }

    /**
     * @param mixed $movimientoTipoRel
     */
    public function setMovimientoTipoRel($movimientoTipoRel): void
    {
        $this->movimientoTipoRel = $movimientoTipoRel;
    }

    /**
     * @return mixed
     */
    public function getTerceroRel()
    {
        return $this->terceroRel;
    }

    /**
     * @param mixed $terceroRel
     */
    public function setTerceroRel($terceroRel): void
    {
        $this->terceroRel = $terceroRel;
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
    public function getMovimientoDetallesMovimientoRel()
    {
        return $this->movimientoDetallesMovimientoRel;
    }

    /**
     * @param mixed $movimientoDetallesMovimientoRel
     */
    public function setMovimientoDetallesMovimientoRel($movimientoDetallesMovimientoRel): void
    {
        $this->movimientoDetallesMovimientoRel = $movimientoDetallesMovimientoRel;
    }

    /**
     * @return mixed
     */
    public function getNumeroDocumento()
    {
        return $this->numeroDocumento;
    }

    /**
     * @param mixed $numeroDocumento
     */
    public function setNumeroDocumento($numeroDocumento): void
    {
        $this->numeroDocumento = $numeroDocumento;
    }



}
