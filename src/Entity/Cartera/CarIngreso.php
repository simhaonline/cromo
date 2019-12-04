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
     * @ORM\Column(name="estado_impreso", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoImpreso = false;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

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
    public function isEstadoAutorizado(): bool
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
    public function isEstadoAprobado(): bool
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
    public function isEstadoAnulado(): bool
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
    public function isEstadoContabilizado(): bool
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
     * @return bool
     */
    public function isEstadoImpreso(): bool
    {
        return $this->estadoImpreso;
    }

    /**
     * @param bool $estadoImpreso
     */
    public function setEstadoImpreso(bool $estadoImpreso): void
    {
        $this->estadoImpreso = $estadoImpreso;
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



}
