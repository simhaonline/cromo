<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarAplicacionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class CarAplicacion
{
    public $infoLog = [
        "primaryKey" => "codigoAplicacionPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_aplicacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoAplicacionPk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_fk", type="integer", nullable=true)
     */
    private $codigoCuentaCobrarFk;

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_aplicacion_fk", type="integer", nullable=true)
     */
    private $codigoCuentaCobrarAplicacionFk;

    /**
     * @ORM\Column(name="numero_documento", type="string", length=30, nullable=true)
     */
    private $numeroDocumento;

    /**
     * @ORM\Column(name="numero_documento_aplicacion", type="string", length=30, nullable=true)
     */
    private $numeroDocumentoAplicacion;

    /**
     * @ORM\Column(name="vr_aplicacion", type="float", nullable=true)
     */
    private $vrAplicacion = 0;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean", options={"default":true})
     */
    private $estadoAprobado = true;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean", options={"default":true})
     */
    private $estadoAutorizado = true;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAnulado = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cartera\CarCuentaCobrar", inversedBy="aplicacionesCuentaCobrarRel")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_fk", referencedColumnName="codigo_cuenta_cobrar_pk")
     */
    protected $cuentaCobrarRel;

    /**
     * @ORM\ManyToOne(targetEntity="CarCuentaCobrar", inversedBy="aplicacionesCuentaCobrarAplicacionRel")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_aplicacion_fk", referencedColumnName="codigo_cuenta_cobrar_pk")
     */
    protected $cuentaCobrarAplicacionRel;

    /**
     * @return mixed
     */
    public function getCodigoAplicacionPk()
    {
        return $this->codigoAplicacionPk;
    }

    /**
     * @param mixed $codigoAplicacionPk
     */
    public function setCodigoAplicacionPk($codigoAplicacionPk): void
    {
        $this->codigoAplicacionPk = $codigoAplicacionPk;
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
    public function getCodigoCuentaCobrarFk()
    {
        return $this->codigoCuentaCobrarFk;
    }

    /**
     * @param mixed $codigoCuentaCobrarFk
     */
    public function setCodigoCuentaCobrarFk($codigoCuentaCobrarFk): void
    {
        $this->codigoCuentaCobrarFk = $codigoCuentaCobrarFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaCobrarAplicacionFk()
    {
        return $this->codigoCuentaCobrarAplicacionFk;
    }

    /**
     * @param mixed $codigoCuentaCobrarAplicacionFk
     */
    public function setCodigoCuentaCobrarAplicacionFk($codigoCuentaCobrarAplicacionFk): void
    {
        $this->codigoCuentaCobrarAplicacionFk = $codigoCuentaCobrarAplicacionFk;
    }

    /**
     * @return mixed
     */
    public function getVrAplicacion()
    {
        return $this->vrAplicacion;
    }

    /**
     * @param mixed $vrAplicacion
     */
    public function setVrAplicacion($vrAplicacion): void
    {
        $this->vrAplicacion = $vrAplicacion;
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
    public function getCuentaCobrarRel()
    {
        return $this->cuentaCobrarRel;
    }

    /**
     * @param mixed $cuentaCobrarRel
     */
    public function setCuentaCobrarRel($cuentaCobrarRel): void
    {
        $this->cuentaCobrarRel = $cuentaCobrarRel;
    }

    /**
     * @return mixed
     */
    public function getCuentaCobrarAplicacionRel()
    {
        return $this->cuentaCobrarAplicacionRel;
    }

    /**
     * @param mixed $cuentaCobrarAplicacionRel
     */
    public function setCuentaCobrarAplicacionRel($cuentaCobrarAplicacionRel): void
    {
        $this->cuentaCobrarAplicacionRel = $cuentaCobrarAplicacionRel;
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

    /**
     * @return mixed
     */
    public function getNumeroDocumentoAplicacion()
    {
        return $this->numeroDocumentoAplicacion;
    }

    /**
     * @param mixed $numeroDocumentoAplicacion
     */
    public function setNumeroDocumentoAplicacion($numeroDocumentoAplicacion): void
    {
        $this->numeroDocumentoAplicacion = $numeroDocumentoAplicacion;
    }




}
