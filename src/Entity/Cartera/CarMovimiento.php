<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarMovimientoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class CarMovimiento
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
     * @ORM\Column(name="codigo_movimiento_tipo_fk" , type="string" , length=10, nullable=true)
     */
    private $codigoMovimientoTipoFk;

    /**
     * @ORM\Column(name="codigo_movimiento_clase_fk" , type="string" , length=10, nullable=true)
     */
    private $codigoMovimientoClaseFk;

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
     * @ORM\Column(name="numero" , type="integer")
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="comentarios" , type="string", nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="vr_total_bruto" ,type="float", nullable=true)
     */
    private $vrTotalBruto = 0;

    /**
     * @ORM\Column(name="vr_retencion", type="float", nullable=true)
     */
    private $vrRetencion = 0;

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
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cartera\CarCliente" , inversedBy="movimientosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk" , referencedColumnName="codigo_cliente_pk")
     */
    private $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cartera\CarMovimientoClase" , inversedBy="movimientosMovimientoClaseRel")
     * @ORM\JoinColumn(name="codigo_movimiento_clase_fk" , referencedColumnName="codigo_movimiento_clase_pk")
     */
    private $movimientoClaseRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cartera\CarMovimientoTipo" , inversedBy="movimientosMovimientoTipoRel")
     * @ORM\JoinColumn(name="codigo_movimiento_tipo_fk" , referencedColumnName="codigo_movimiento_tipo_pk")
     */
    private $movimientoTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCuenta" , inversedBy="carMovimientosCuentaRel")
     * @ORM\JoinColumn(name="codigo_cuenta_fk" , referencedColumnName="codigo_cuenta_pk")
     */
    private $cuentaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarMovimientoDetalle", mappedBy="movimientoRel")
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
     * @return int
     */
    public function getNumero(): int
    {
        return $this->numero;
    }

    /**
     * @param int $numero
     */
    public function setNumero(int $numero): void
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
     * @return int
     */
    public function getVrTotalBruto(): int
    {
        return $this->vrTotalBruto;
    }

    /**
     * @param int $vrTotalBruto
     */
    public function setVrTotalBruto(int $vrTotalBruto): void
    {
        $this->vrTotalBruto = $vrTotalBruto;
    }

    /**
     * @return int
     */
    public function getVrRetencion(): int
    {
        return $this->vrRetencion;
    }

    /**
     * @param int $vrRetencion
     */
    public function setVrRetencion(int $vrRetencion): void
    {
        $this->vrRetencion = $vrRetencion;
    }

    /**
     * @return int
     */
    public function getVrTotalNeto(): int
    {
        return $this->vrTotalNeto;
    }

    /**
     * @param int $vrTotalNeto
     */
    public function setVrTotalNeto(int $vrTotalNeto): void
    {
        $this->vrTotalNeto = $vrTotalNeto;
    }

    /**
     * @return bool
     */
    public function getEstadoAutorizado(): bool
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
    public function getEstadoAprobado(): bool
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
    public function getEstadoAnulado(): bool
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
     * @return bool
     */
    public function getEstadoContabilizado(): bool
    {
        return $this->estadoContabilizado;
    }

    /**
     * @param bool $estadoContabilizado
     */
    public function setEstadoContabilizado(bool $estadoContabilizado): void
    {
        $this->estadoContabilizado = $estadoContabilizado;
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


}
