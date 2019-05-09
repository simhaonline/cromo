<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteMonitoreoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteMonitoreo
{
    public $infoLog = [
        "primaryKey" => "codigoMonitoreoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoMonitoreoPk;

    /**
     * @ORM\Column(name="fecha_registro", type="datetime", nullable=true)
     */
    private $fechaRegistro;

    /**
     * @ORM\Column(name="fecha_inicio", type="datetime", nullable=true)
     */
    private $fechaInicio;

    /**
     * @ORM\Column(name="fecha_fin", type="datetime", nullable=true)
     */
    private $fechaFin;

    /**
     * @ORM\Column(name="soporte", type="string", length=50, nullable=true)
     */
    private $soporte;

    /**
     * @ORM\Column(name="codigo_vehiculo_fk", type="string", length=20, nullable=true)
     */
    private $codigoVehiculoFk;

    /**
     * @ORM\Column(name="codigo_despacho_fk", type="integer", nullable=true)
     */
    private $codigoDespachoFk;

    /**
     * @ORM\Column(name="codigo_despacho_recogida_fk", type="integer", nullable=true)
     */
    private $codigoDespachoRecogidaFk;

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
     * @ORM\Column(name="estado_cerrado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoCerrado = false;

    /**
     * @ORM\Column(name="codigo_ciudad_destino_fk", type="string", length=20, nullable=true)
     */
    private $codigoCiudadDestinoFk;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="TteVehiculo", inversedBy="monitoreosVehiculoRel")
     * @ORM\JoinColumn(name="codigo_vehiculo_fk", referencedColumnName="codigo_vehiculo_pk")
     */
    private $vehiculoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteDespacho", inversedBy="monitoreosDespachoRel")
     * @ORM\JoinColumn(name="codigo_despacho_fk", referencedColumnName="codigo_despacho_pk")
     */
    private $despachoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteDespachoRecogida", inversedBy="monitoreosDespachoRecogidaRel")
     * @ORM\JoinColumn(name="codigo_despacho_recogida_fk", referencedColumnName="codigo_despacho_recogida_pk")
     */
    private $despachoRecogidaRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCiudad", inversedBy="monitoreosCiudadDestinoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_destino_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadDestinoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteMonitoreoDetalle", mappedBy="monitoreoRel")
     */
    protected $monitoreosDetallesMonitoreoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteMonitoreoRegistro", mappedBy="monitoreoRel")
     */
    protected $monitoreosRegistrosMonitoreoRel;

    /**
     * @return array
     */
    public function getInfoLog(): array
    {
        return $this->infoLog;
    }

    /**
     * @param array $infoLog
     */
    public function setInfoLog(array $infoLog): void
    {
        $this->infoLog = $infoLog;
    }

    /**
     * @return mixed
     */
    public function getCodigoMonitoreoPk()
    {
        return $this->codigoMonitoreoPk;
    }

    /**
     * @param mixed $codigoMonitoreoPk
     */
    public function setCodigoMonitoreoPk($codigoMonitoreoPk): void
    {
        $this->codigoMonitoreoPk = $codigoMonitoreoPk;
    }

    /**
     * @return mixed
     */
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }

    /**
     * @param mixed $fechaRegistro
     */
    public function setFechaRegistro($fechaRegistro): void
    {
        $this->fechaRegistro = $fechaRegistro;
    }

    /**
     * @return mixed
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * @param mixed $fechaInicio
     */
    public function setFechaInicio($fechaInicio): void
    {
        $this->fechaInicio = $fechaInicio;
    }

    /**
     * @return mixed
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * @param mixed $fechaFin
     */
    public function setFechaFin($fechaFin): void
    {
        $this->fechaFin = $fechaFin;
    }

    /**
     * @return mixed
     */
    public function getSoporte()
    {
        return $this->soporte;
    }

    /**
     * @param mixed $soporte
     */
    public function setSoporte($soporte): void
    {
        $this->soporte = $soporte;
    }

    /**
     * @return mixed
     */
    public function getCodigoVehiculoFk()
    {
        return $this->codigoVehiculoFk;
    }

    /**
     * @param mixed $codigoVehiculoFk
     */
    public function setCodigoVehiculoFk($codigoVehiculoFk): void
    {
        $this->codigoVehiculoFk = $codigoVehiculoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoDespachoFk()
    {
        return $this->codigoDespachoFk;
    }

    /**
     * @param mixed $codigoDespachoFk
     */
    public function setCodigoDespachoFk($codigoDespachoFk): void
    {
        $this->codigoDespachoFk = $codigoDespachoFk;
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
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }

    /**
     * @param mixed $estadoCerrado
     */
    public function setEstadoCerrado($estadoCerrado): void
    {
        $this->estadoCerrado = $estadoCerrado;
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
    public function getVehiculoRel()
    {
        return $this->vehiculoRel;
    }

    /**
     * @param mixed $vehiculoRel
     */
    public function setVehiculoRel($vehiculoRel): void
    {
        $this->vehiculoRel = $vehiculoRel;
    }

    /**
     * @return mixed
     */
    public function getDespachoRel()
    {
        return $this->despachoRel;
    }

    /**
     * @param mixed $despachoRel
     */
    public function setDespachoRel($despachoRel): void
    {
        $this->despachoRel = $despachoRel;
    }

    /**
     * @return mixed
     */
    public function getMonitoreosDetallesMonitoreoRel()
    {
        return $this->monitoreosDetallesMonitoreoRel;
    }

    /**
     * @param mixed $monitoreosDetallesMonitoreoRel
     */
    public function setMonitoreosDetallesMonitoreoRel($monitoreosDetallesMonitoreoRel): void
    {
        $this->monitoreosDetallesMonitoreoRel = $monitoreosDetallesMonitoreoRel;
    }

    /**
     * @return mixed
     */
    public function getMonitoreosRegistrosMonitoreoRel()
    {
        return $this->monitoreosRegistrosMonitoreoRel;
    }

    /**
     * @param mixed $monitoreosRegistrosMonitoreoRel
     */
    public function setMonitoreosRegistrosMonitoreoRel($monitoreosRegistrosMonitoreoRel): void
    {
        $this->monitoreosRegistrosMonitoreoRel = $monitoreosRegistrosMonitoreoRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoCiudadDestinoFk()
    {
        return $this->codigoCiudadDestinoFk;
    }

    /**
     * @param mixed $codigoCiudadDestinoFk
     */
    public function setCodigoCiudadDestinoFk($codigoCiudadDestinoFk): void
    {
        $this->codigoCiudadDestinoFk = $codigoCiudadDestinoFk;
    }

    /**
     * @return mixed
     */
    public function getCiudadDestinoRel()
    {
        return $this->ciudadDestinoRel;
    }

    /**
     * @param mixed $ciudadDestinoRel
     */
    public function setCiudadDestinoRel($ciudadDestinoRel): void
    {
        $this->ciudadDestinoRel = $ciudadDestinoRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoDespachoRecogidaFk()
    {
        return $this->codigoDespachoRecogidaFk;
    }

    /**
     * @param mixed $codigoDespachoRecogidaFk
     */
    public function setCodigoDespachoRecogidaFk($codigoDespachoRecogidaFk): void
    {
        $this->codigoDespachoRecogidaFk = $codigoDespachoRecogidaFk;
    }

    /**
     * @return mixed
     */
    public function getDespachoRecogidaRel()
    {
        return $this->despachoRecogidaRel;
    }

    /**
     * @param mixed $despachoRecogidaRel
     */
    public function setDespachoRecogidaRel($despachoRecogidaRel): void
    {
        $this->despachoRecogidaRel = $despachoRecogidaRel;
    }



}

