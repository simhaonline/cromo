<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarIngresoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class CarIngreso
{
    public $infoLog = [
        "primaryKey" => "codigoIngresoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="codigo_ingreso_pk",type="integer")
     */
    private $codigoIngresoPk;

    /**
     * @ORM\Column(name="codigo_ingreso_tipo_fk" , type="string" , length=10, nullable=true)
     */
    private $codigoIngresoTipoFk;

    /**
     * @ORM\Column(name="codigo_cliente_fk" , type="integer")
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=10, nullable=false)
     */
    private $codigoCuentaFk;

    /**
     * @ORM\Column(name="fecha", type="date")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Cartera\CarCliente" , inversedBy="ingresosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk" , referencedColumnName="codigo_cliente_pk")
     */
    private $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cartera\CarIngresoTipo" , inversedBy="ingresosIngresoTipoRel")
     * @ORM\JoinColumn(name="codigo_ingreso_tipo_fk" , referencedColumnName="codigo_ingreso_tipo_pk")
     */
    private $ingresoTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCuenta" , inversedBy="ingresosCuentaRel")
     * @ORM\JoinColumn(name="codigo_cuenta_fk" , referencedColumnName="codigo_cuenta_pk")
     */
    private $cuentaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarIngresoDetalle", mappedBy="ingresoRel")
     */
    private $ingresoDetallesIngresoRel;

    /**
     * @return mixed
     */
    public function getCodigoIngresoPk()
    {
        return $this->codigoIngresoPk;
    }

    /**
     * @param mixed $codigoIngresoPk
     */
    public function setCodigoIngresoPk($codigoIngresoPk): void
    {
        $this->codigoIngresoPk = $codigoIngresoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoIngresoTipoFk()
    {
        return $this->codigoIngresoTipoFk;
    }

    /**
     * @param mixed $codigoIngresoTipoFk
     */
    public function setCodigoIngresoTipoFk($codigoIngresoTipoFk): void
    {
        $this->codigoIngresoTipoFk = $codigoIngresoTipoFk;
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
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * @param mixed $clienteRel
     */
    public function setClienteRel($clienteRel): void
    {
        $this->clienteRel = $clienteRel;
    }

    /**
     * @return mixed
     */
    public function getIngresoTipoRel()
    {
        return $this->ingresoTipoRel;
    }

    /**
     * @param mixed $ingresoTipoRel
     */
    public function setIngresoTipoRel($ingresoTipoRel): void
    {
        $this->ingresoTipoRel = $ingresoTipoRel;
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
    public function getIngresoDetallesIngresoRel()
    {
        return $this->ingresoDetallesIngresoRel;
    }

    /**
     * @param mixed $ingresoDetallesIngresoRel
     */
    public function setIngresoDetallesIngresoRel($ingresoDetallesIngresoRel): void
    {
        $this->ingresoDetallesIngresoRel = $ingresoDetallesIngresoRel;
    }



}
