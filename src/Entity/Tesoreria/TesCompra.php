<?php

namespace App\Entity\Tesoreria;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Tesoreria\TesCompraRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TesCompra
{
    public $infoLog = [
        "primaryKey" => "codigoCompraPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="codigo_compra_pk",type="integer")
     */
    private $codigoCompraPk;

    /**
     * @ORM\Column(name="codigo_compra_tipo_fk" , type="string" , length=10, nullable=true)
     */
    private $codigoCompraTipoFk;

    /**
     * @ORM\Column(name="codigo_tercero_fk" , type="integer")
     */
    private $codigoTerceroFk;

    /**
     * @ORM\Column(name="fecha" ,type="date")
     */
    private $fecha;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Tesoreria\TesCompraTipo" , inversedBy="comprasCompraTipoRel")
     * @ORM\JoinColumn(name="codigo_compra_tipo_fk" , referencedColumnName="codigo_compra_tipo_pk")
     */
    private $compraTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tesoreria\TesTercero" , inversedBy="comprasTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk" , referencedColumnName="codigo_tercero_pk")
     */
    private $terceroRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tesoreria\TesCompraDetalle", mappedBy="compraRel")
     */
    private $comprasDetallesCompraRel;

    /**
     * @return mixed
     */
    public function getCodigoCompraPk()
    {
        return $this->codigoCompraPk;
    }

    /**
     * @param mixed $codigoCompraPk
     */
    public function setCodigoCompraPk($codigoCompraPk): void
    {
        $this->codigoCompraPk = $codigoCompraPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCompraTipoFk()
    {
        return $this->codigoCompraTipoFk;
    }

    /**
     * @param mixed $codigoCompraTipoFk
     */
    public function setCodigoCompraTipoFk($codigoCompraTipoFk): void
    {
        $this->codigoCompraTipoFk = $codigoCompraTipoFk;
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
    public function getCompraTipoRel()
    {
        return $this->compraTipoRel;
    }

    /**
     * @param mixed $compraTipoRel
     */
    public function setCompraTipoRel($compraTipoRel): void
    {
        $this->compraTipoRel = $compraTipoRel;
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
    public function getComprasDetallesCompraRel()
    {
        return $this->comprasDetallesCompraRel;
    }

    /**
     * @param mixed $comprasDetallesCompraRel
     */
    public function setComprasDetallesCompraRel($comprasDetallesCompraRel): void
    {
        $this->comprasDetallesCompraRel = $comprasDetallesCompraRel;
    }



}
