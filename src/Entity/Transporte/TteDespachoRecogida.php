<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteDespachoRecogidaRepository")
 */
class TteDespachoRecogida
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoDespachoRecogidaPk;

    /**
     * @ORM\Column(name="codigo_operacion_fk", type="string", length=20, nullable=true)
     */
    private $codigoOperacionFk;

    /**
     * @ORM\Column(name="codigo_ruta_recogida_fk", type="string", length=20, nullable=true)
     */
    private $codigoRutaRecogidaFk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_vehiculo_fk", type="string", length=20, nullable=true)
     */
    private $codigoVehiculoFk;

    /**
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="unidades", type="float")
     */
    private $unidades = 0;

    /**
     * @ORM\Column(name="peso_real", type="float")
     */
    private $pesoReal = 0;

    /**
     * @ORM\Column(name="peso_volumen", type="float")
     */
    private $pesoVolumen = 0;

    /**
     * @ORM\Column(name="vr_pago", type="float")
     */
    private $vrPago = 0;

    /**
     * @ORM\Column(name="vr_declara", type="float")
     */
    private $vrDeclara = 0;

    /**
     * @ORM\Column(name="estado_descargado", type="boolean", nullable=true)
     */
    private $estadoDescargado = false;

    /**
     * @ORM\Column(name="estado_monitoreo", type="boolean", nullable=true)
     */
    private $estadoMonitoreo = false;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="TteOperacion", inversedBy="despachosRecogidasOperacionRel")
     * @ORM\JoinColumn(name="codigo_operacion_fk", referencedColumnName="codigo_operacion_pk")
     */
    private $operacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteVehiculo", inversedBy="despachosRecogidasVehiculoRel")
     * @ORM\JoinColumn(name="codigo_vehiculo_fk", referencedColumnName="codigo_vehiculo_pk")
     */
    private $vehiculoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteRutaRecogida", inversedBy="despachosRecogidasRutaRecogidaRel")
     * @ORM\JoinColumn(name="codigo_ruta_recogida_fk", referencedColumnName="codigo_ruta_recogida_pk")
     */
    private $rutaRecogidaRel;

    /**
     * @ORM\OneToMany(targetEntity="TteRecogida", mappedBy="despachoRecogidaRel")
     */
    protected $recogidasDespachoRecogidaRel;

    /**
     * @ORM\OneToMany(targetEntity="TteDespachoRecogidaAuxiliar", mappedBy="despachoRecogidaRel")
     */
    protected $despachosRecogidasAuxiliaresDespachoRecogidaRel;

    /**
     * @return mixed
     */
    public function getCodigoDespachoRecogidaPk()
    {
        return $this->codigoDespachoRecogidaPk;
    }

    /**
     * @param mixed $codigoDespachoRecogidaPk
     */
    public function setCodigoDespachoRecogidaPk($codigoDespachoRecogidaPk): void
    {
        $this->codigoDespachoRecogidaPk = $codigoDespachoRecogidaPk;
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
    public function getCodigoRutaRecogidaFk()
    {
        return $this->codigoRutaRecogidaFk;
    }

    /**
     * @param mixed $codigoRutaRecogidaFk
     */
    public function setCodigoRutaRecogidaFk($codigoRutaRecogidaFk): void
    {
        $this->codigoRutaRecogidaFk = $codigoRutaRecogidaFk;
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
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param mixed $cantidad
     */
    public function setCantidad($cantidad): void
    {
        $this->cantidad = $cantidad;
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
    public function getVrPago()
    {
        return $this->vrPago;
    }

    /**
     * @param mixed $vrPago
     */
    public function setVrPago($vrPago): void
    {
        $this->vrPago = $vrPago;
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
    public function getEstadoMonitoreo()
    {
        return $this->estadoMonitoreo;
    }

    /**
     * @param mixed $estadoMonitoreo
     */
    public function setEstadoMonitoreo($estadoMonitoreo): void
    {
        $this->estadoMonitoreo = $estadoMonitoreo;
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
    public function getRutaRecogidaRel()
    {
        return $this->rutaRecogidaRel;
    }

    /**
     * @param mixed $rutaRecogidaRel
     */
    public function setRutaRecogidaRel($rutaRecogidaRel): void
    {
        $this->rutaRecogidaRel = $rutaRecogidaRel;
    }

    /**
     * @return mixed
     */
    public function getRecogidasDespachoRecogidaRel()
    {
        return $this->recogidasDespachoRecogidaRel;
    }

    /**
     * @param mixed $recogidasDespachoRecogidaRel
     */
    public function setRecogidasDespachoRecogidaRel($recogidasDespachoRecogidaRel): void
    {
        $this->recogidasDespachoRecogidaRel = $recogidasDespachoRecogidaRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosRecogidasAuxiliaresDespachoRecogidaRel()
    {
        return $this->despachosRecogidasAuxiliaresDespachoRecogidaRel;
    }

    /**
     * @param mixed $despachosRecogidasAuxiliaresDespachoRecogidaRel
     */
    public function setDespachosRecogidasAuxiliaresDespachoRecogidaRel($despachosRecogidasAuxiliaresDespachoRecogidaRel): void
    {
        $this->despachosRecogidasAuxiliaresDespachoRecogidaRel = $despachosRecogidasAuxiliaresDespachoRecogidaRel;
    }



}

