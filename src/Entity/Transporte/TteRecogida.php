<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteRecogidaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteRecogida
{
    public $infoLog = [
        "primaryKey" => "codigoRecogidaPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoRecogidaPk;

    /**
     * @ORM\Column(name="codigo_operacion_fk", type="string", length=20, nullable=true)
     */
    private $codigoOperacionFk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_conductor_fk", type="integer", nullable=true)
     */
    private $codigoConductorFk;

    /**
     * @ORM\Column(name="codigo_vehiculo_fk", type="string", length=20, nullable=true)
     */
    private $codigoVehiculoFk;

    /**
     * @ORM\Column(name="fecha_registro", type="datetime", nullable=true)
     */
    private $fechaRegistro;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_efectiva", type="datetime", nullable=true)
     */
    private $fechaEfectiva;

    /**
     * @ORM\Column(name="anunciante", type="string", length=200, nullable=true)
     */
    private $anunciante;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="string", length=20, nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="direccion", type="string", length=200, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="telefono", type="string", length=50, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="unidades", type="float", options={"default" : 0})
     */
    private $unidades = 0;

    /**
     * @ORM\Column(name="peso_real", type="float", options={"default" : 0})
     */
    private $pesoReal = 0;

    /**
     * @ORM\Column(name="peso_volumen", type="float", options={"default" : 0})
     */
    private $pesoVolumen = 0;

    /**
     * @ORM\Column(name="vr_declara", type="float", options={"default" : 0})
     */
    private $vrDeclara = 0;

    /**
         * @ORM\Column(name="estado_planificado", type="boolean",options={"default":false})
     */
    private $estadoPlanificado = false;

    /**
     * @ORM\Column(name="estado_programado", type="boolean",options={"default":false})
     */
    private $estadoProgramado = false;

    /**
     * @ORM\Column(name="estado_recogido", type="boolean",options={"default":false})
     */
    private $estadoRecogido = false;

    /**
     * @ORM\Column(name="estado_descargado", type="boolean",options={"default":false})
     */
    private $estadoDescargado = false;

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
     * @ORM\Column(name="codigo_despacho_recogida_fk", type="integer", nullable=true)
     */
    private $codigoDespachoRecogidaFk;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="TteOperacion", inversedBy="recogidasOperacionRel")
     * @ORM\JoinColumn(name="codigo_operacion_fk", referencedColumnName="codigo_operacion_pk")
     */
    private $operacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCiudad", inversedBy="recogidasCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCliente", inversedBy="recogidasClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    private $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteDespachoRecogida", inversedBy="recogidasDespachoRecogidaRel")
     * @ORM\JoinColumn(name="codigo_despacho_recogida_fk", referencedColumnName="codigo_despacho_recogida_pk")
     */
    private $despachoRecogidaRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteConductor", inversedBy="recogidasConductorRel")
     * @ORM\JoinColumn(name="codigo_conductor_fk", referencedColumnName="codigo_conductor_pk")
     */
    private $conductorRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteVehiculo", inversedBy="recogidasVechiculoRel")
     * @ORM\JoinColumn(name="codigo_vehiculo_fk", referencedColumnName="codigo_vehiculo_pk")
     */
    private $vehiculoRel;

    /**
     * @return mixed
     */
    public function getCodigoRecogidaPk()
    {
        return $this->codigoRecogidaPk;
    }

    /**
     * @param mixed $codigoRecogidaPk
     */
    public function setCodigoRecogidaPk($codigoRecogidaPk): void
    {
        $this->codigoRecogidaPk = $codigoRecogidaPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoOperacionFk()
    {
        return $this->codigoOperacionFk;
    }

    /**
     * @param mixed $codigoOperacionFk
     */
    public function setCodigoOperacionFk($codigoOperacionFk): void
    {
        $this->codigoOperacionFk = $codigoOperacionFk;
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
    public function getFechaEfectiva()
    {
        return $this->fechaEfectiva;
    }

    /**
     * @param mixed $fechaEfectiva
     */
    public function setFechaEfectiva($fechaEfectiva): void
    {
        $this->fechaEfectiva = $fechaEfectiva;
    }

    /**
     * @return mixed
     */
    public function getAnunciante()
    {
        return $this->anunciante;
    }

    /**
     * @param mixed $anunciante
     */
    public function setAnunciante($anunciante): void
    {
        $this->anunciante = $anunciante;
    }

    /**
     * @return mixed
     */
    public function getCodigoCiudadFk()
    {
        return $this->codigoCiudadFk;
    }

    /**
     * @param mixed $codigoCiudadFk
     */
    public function setCodigoCiudadFk($codigoCiudadFk): void
    {
        $this->codigoCiudadFk = $codigoCiudadFk;
    }

    /**
     * @return mixed
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param mixed $direccion
     */
    public function setDireccion($direccion): void
    {
        $this->direccion = $direccion;
    }

    /**
     * @return mixed
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param mixed $telefono
     */
    public function setTelefono($telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * @return mixed
     */
    public function getUnidades()
    {
        return $this->unidades;
    }

    /**
     * @param mixed $unidades
     */
    public function setUnidades($unidades): void
    {
        $this->unidades = $unidades;
    }

    /**
     * @return mixed
     */
    public function getPesoReal()
    {
        return $this->pesoReal;
    }

    /**
     * @param mixed $pesoReal
     */
    public function setPesoReal($pesoReal): void
    {
        $this->pesoReal = $pesoReal;
    }

    /**
     * @return mixed
     */
    public function getPesoVolumen()
    {
        return $this->pesoVolumen;
    }

    /**
     * @param mixed $pesoVolumen
     */
    public function setPesoVolumen($pesoVolumen): void
    {
        $this->pesoVolumen = $pesoVolumen;
    }

    /**
     * @return mixed
     */
    public function getVrDeclara()
    {
        return $this->vrDeclara;
    }

    /**
     * @param mixed $vrDeclara
     */
    public function setVrDeclara($vrDeclara): void
    {
        $this->vrDeclara = $vrDeclara;
    }

    /**
     * @return mixed
     */
    public function getEstadoPlanificado()
    {
        return $this->estadoPlanificado;
    }

    /**
     * @param mixed $estadoPlanificado
     */
    public function setEstadoPlanificado($estadoPlanificado): void
    {
        $this->estadoPlanificado = $estadoPlanificado;
    }

    /**
     * @return mixed
     */
    public function getEstadoProgramado()
    {
        return $this->estadoProgramado;
    }

    /**
     * @param mixed $estadoProgramado
     */
    public function setEstadoProgramado($estadoProgramado): void
    {
        $this->estadoProgramado = $estadoProgramado;
    }

    /**
     * @return mixed
     */
    public function getEstadoRecogido()
    {
        return $this->estadoRecogido;
    }

    /**
     * @param mixed $estadoRecogido
     */
    public function setEstadoRecogido($estadoRecogido): void
    {
        $this->estadoRecogido = $estadoRecogido;
    }

    /**
     * @return mixed
     */
    public function getEstadoDescargado()
    {
        return $this->estadoDescargado;
    }

    /**
     * @param mixed $estadoDescargado
     */
    public function setEstadoDescargado($estadoDescargado): void
    {
        $this->estadoDescargado = $estadoDescargado;
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
    public function getOperacionRel()
    {
        return $this->operacionRel;
    }

    /**
     * @param mixed $operacionRel
     */
    public function setOperacionRel($operacionRel): void
    {
        $this->operacionRel = $operacionRel;
    }

    /**
     * @return mixed
     */
    public function getCiudadRel()
    {
        return $this->ciudadRel;
    }

    /**
     * @param mixed $ciudadRel
     */
    public function setCiudadRel($ciudadRel): void
    {
        $this->ciudadRel = $ciudadRel;
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

    /**
     * @return mixed
     */
    public function getCodigoConductorFk()
    {
        return $this->codigoConductorFk;
    }

    /**
     * @param mixed $codigoConductorFk
     */
    public function setCodigoConductorFk($codigoConductorFk): void
    {
        $this->codigoConductorFk = $codigoConductorFk;
    }

    /**
     * @return mixed
     */
    public function getConductorRel()
    {
        return $this->conductorRel;
    }

    /**
     * @param mixed $conductorRel
     */
    public function setConductorRel($conductorRel): void
    {
        $this->conductorRel = $conductorRel;
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



}

