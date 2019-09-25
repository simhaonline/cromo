<?php

namespace App\Entity\Tesoreria;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Tesoreria\TesEgresoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TesEgreso
{
    public $infoLog = [
        "primaryKey" => "codigoEgresoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="codigo_egreso_pk",type="integer")
     */
    private $codigoEgresoPk;

    /**
     * @ORM\Column(name="codigo_egreso_tipo_fk" , type="string" , length=10, nullable=true)
     */
    private $codigoEgresoTipoFk;

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
     * @ORM\Column(name="fecha_pago", type="date", nullable=true)
     */
    private $fechaPago;

    /**
     * @ORM\Column(name="numero" , type="integer")
     */
    private $numero = 0;

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
     * @ORM\Column(name="estado_impreso", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoImpreso = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tesoreria\TesTercero" , inversedBy="egresosTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk" , referencedColumnName="codigo_tercero_pk")
     */
    private $terceroRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tesoreria\TesEgresoTipo" , inversedBy="egresosEgresoTipoRel")
     * @ORM\JoinColumn(name="codigo_egreso_tipo_fk" , referencedColumnName="codigo_egreso_tipo_pk")
     */
    private $egresoTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCuenta" , inversedBy="egresosCuentaRel")
     * @ORM\JoinColumn(name="codigo_cuenta_fk" , referencedColumnName="codigo_cuenta_pk")
     */
    private $cuentaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tesoreria\TesEgresoDetalle", mappedBy="egresoRel")
     */
    private $egresoDetallesEgresoRel;

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
    public function getFechaPago()
    {
        return $this->fechaPago;
    }

    /**
     * @param mixed $fechaPago
     */
    public function setFechaPago($fechaPago): void
    {
        $this->fechaPago = $fechaPago;
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
    public function getEstadoImpreso()
    {
        return $this->estadoImpreso;
    }

    /**
     * @param mixed $estadoImpreso
     */
    public function setEstadoImpreso($estadoImpreso): void
    {
        $this->estadoImpreso = $estadoImpreso;
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
    public function getEgresoDetallesEgresoRel()
    {
        return $this->egresoDetallesEgresoRel;
    }

    /**
     * @param mixed $egresoDetallesEgresoRel
     */
    public function setEgresoDetallesEgresoRel($egresoDetallesEgresoRel): void
    {
        $this->egresoDetallesEgresoRel = $egresoDetallesEgresoRel;
    }



}
