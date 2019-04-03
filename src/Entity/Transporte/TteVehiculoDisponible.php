<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteVehiculoDisponibleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteVehiculoDisponible
{
    public $infoLog = [
        "primaryKey" => "codigoVehiculoDisponiblePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoVehiculoDisponiblePk;

    /**
     * @ORM\Column(name="codigo_vehiculo_fk", type="string", length=20, nullable=true)
     */
    private $codigoVehiculoFk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="responsable", type="string", length=200, nullable=true)
     */
    private $responsable;

    /**
     * @ORM\Column(name="celular", type="string", length=30, nullable=true)
     */
    private $celular;

    /**
     * @ORM\Column(name="estado_despachado", type="boolean",options={"default":false})
     */
    private $estadoDespachado = false;

    /**
     * @ORM\Column(name="fecha_despacho", type="datetime", nullable=true)
     */
    private $fechaDespacho;

    /**
     * @ORM\Column(name="estado_descartado", type="boolean",options={"default":false})
     */
    private $estadoDescartado = false;

    /**
     * @ORM\Column(name="fecha_descartado", type="datetime", nullable=true)
     */
    private $fechaDescartado;

    /**
     * @ORM\Column(name="motivo", type="string", length=1000, nullable=true)
     */
    private $motivo;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\Column(name="usuario", type="string", length=25, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="usuario_descarte", type="string", length=25, nullable=true)
     */
    private $usuarioDescarte;

    /**
     * @ORM\ManyToOne(targetEntity="TteVehiculo", inversedBy="vehiculosDisponibleVechiculoRel")
     * @ORM\JoinColumn(name="codigo_vehiculo_fk", referencedColumnName="codigo_vehiculo_pk")
     */
    private $vehiculoRel;

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
    public function getCodigoVehiculoDisponiblePk()
    {
        return $this->codigoVehiculoDisponiblePk;
    }

    /**
     * @param mixed $codigoVehiculoDisponiblePk
     */
    public function setCodigoVehiculoDisponiblePk($codigoVehiculoDisponiblePk): void
    {
        $this->codigoVehiculoDisponiblePk = $codigoVehiculoDisponiblePk;
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
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * @param mixed $responsable
     */
    public function setResponsable($responsable): void
    {
        $this->responsable = $responsable;
    }

    /**
     * @return mixed
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * @param mixed $celular
     */
    public function setCelular($celular): void
    {
        $this->celular = $celular;
    }

    /**
     * @return mixed
     */
    public function getEstadoDespachado()
    {
        return $this->estadoDespachado;
    }

    /**
     * @param mixed $estadoDespachado
     */
    public function setEstadoDespachado($estadoDespachado): void
    {
        $this->estadoDespachado = $estadoDespachado;
    }

    /**
     * @return mixed
     */
    public function getFechaDespacho()
    {
        return $this->fechaDespacho;
    }

    /**
     * @param mixed $fechaDespacho
     */
    public function setFechaDespacho($fechaDespacho): void
    {
        $this->fechaDespacho = $fechaDespacho;
    }

    /**
     * @return mixed
     */
    public function getEstadoDescartado()
    {
        return $this->estadoDescartado;
    }

    /**
     * @param mixed $estadoDescartado
     */
    public function setEstadoDescartado($estadoDescartado): void
    {
        $this->estadoDescartado = $estadoDescartado;
    }

    /**
     * @return mixed
     */
    public function getFechaDescartado()
    {
        return $this->fechaDescartado;
    }

    /**
     * @param mixed $fechaDescartado
     */
    public function setFechaDescartado($fechaDescartado): void
    {
        $this->fechaDescartado = $fechaDescartado;
    }

    /**
     * @return mixed
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * @param mixed $motivo
     */
    public function setMotivo($motivo): void
    {
        $this->motivo = $motivo;
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
    public function getUsuarioDescarte()
    {
        return $this->usuarioDescarte;
    }

    /**
     * @param mixed $usuarioDescarte
     */
    public function setUsuarioDescarte($usuarioDescarte): void
    {
        $this->usuarioDescarte = $usuarioDescarte;
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



}

