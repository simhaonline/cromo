<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteVehiculoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteVehiculo
{
    public $infoLog = [
        "primaryKey" => "codigoVehiculoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoVehiculoPk;

    /**
     * @ORM\Column(name="placa", type="string", length=10, nullable=true)
     */
    private $placa;

    /**
     * @ORM\Column(name="placa_remolque", type="string", length=10, nullable=true)
     */
    private $placaRemolque;

    /**
     * @ORM\Column(name="modelo", type="integer", nullable=true)
     */
    private $modelo;

    /**
     * @ORM\Column(name="modelo_repotenciado", type="integer", nullable=true)
     */
    private $modeloRepotenciado;

    /**
     * @ORM\Column(name="motor", type="string", length=50, nullable=true)
     */
    private $motor;

    /**
     * @ORM\Column(name="numero_ejes", type="integer", nullable=true)
     */
    private $numeroEjes;

    /**
     * @ORM\Column(name="chasis", type="string", length=50, nullable=true)
     */
    private $chasis;

    /**
     * @ORM\Column(name="serie", type="string", length=50, nullable=true)
     */
    private $serie;

    /**
     * @ORM\Column(name="peso_vacio", type="integer", nullable=true)
     */
    private $pesoVacio = 0;

    /**
     * @ORM\Column(name="capacidad", type="integer", nullable=true)
     */
    private $capacidad = 0;

    /**
     * @ORM\Column(name="celular", type="string", length=30, nullable=true)
     */
    private $celular;

    /**
     * @ORM\Column(name="registro_nacional_carga", type="string", length=50, nullable=true)
     */
    private $registroNacionalCarga;

    /**
     * @ORM\Column(name="configuracion", type="string", length=20, nullable=true)
     */
    private $configuracion;

    /**
     * @ORM\Column(name="numero_poliza", type="string", length=30, nullable=true)
     */
    private $numeroPoliza;

    /**
     * @ORM\Column(name="fecha_vence_poliza", type="date", nullable=true)
     */
    private $fechaVencePoliza;

    /**
     * @ORM\Column(name="numero_tecnicomecanica", type="string", length=100, nullable=true)
     */
    private $numeroTecnicomecanica;

    /**
     * @ORM\Column(name="fecha_vence_tecnicomecanica", type="date", nullable=true)
     */
    private $fechaVenceTecnicomecanica;

    /**
     * @ORM\Column(name="codigo_poseedor_fk", type="integer", nullable=true)
     */
    private $codigoPoseedorFk;

    /**
     * @ORM\Column(name="codigo_propietario_fk", type="integer", nullable=true)
     */
    private $codigoPropietarioFk;

    /**
     * @ORM\Column(name="codigo_aseguradora_fk", type="integer", nullable=true)
     */
    private $codigoAseguradoraFk;

    /**
     * @ORM\Column(name="codigo_color_fk", type="string", length=20, nullable=true)
     */
    private $codigoColorFk;

    /**
     * @ORM\Column(name="codigo_marca_fk", type="string", length=20, nullable=true)
     */
    private $codigoMarcaFk;

    /**
     * @ORM\Column(name="codigo_linea_fk", type="string", length=20, nullable=true)
     */
    private $codigoLineaFk;

    /**
     * @ORM\Column(name="codigo_tipo_combustible_fk", type="string", length=20, nullable=true)
     */
    private $codigoTipoCombustibleFk;

    /**
     * @ORM\Column(name="codigo_tipo_carroceria_fk", type="string", length=20, nullable=true)
     */
    private $codigoTipoCarroceriaFk;

    /**
     * @ORM\Column(name="estado_inactivo", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoInactivo = false;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteMarca", inversedBy="vehiculosMarcaRel")
     * @ORM\JoinColumn(name="codigo_marca_fk", referencedColumnName="codigo_marca_pk")
     */
    private $marcaRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TtePoseedor", inversedBy="vehiculosPoseedorRel")
     * @ORM\JoinColumn(name="codigo_poseedor_fk", referencedColumnName="codigo_poseedor_pk")
     */
    private $poseedorRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TtePoseedor", inversedBy="vehiculosPropietarioRel")
     * @ORM\JoinColumn(name="codigo_propietario_fk", referencedColumnName="codigo_poseedor_pk")
     */
    private $propietarioRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteAseguradora", inversedBy="vehiculosAseguradoraRel")
     * @ORM\JoinColumn(name="codigo_aseguradora_fk", referencedColumnName="codigo_aseguradora_pk")
     */
    private $aseguradoraRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteTipoCombustible", inversedBy="vehiculosTipoCombustibleRel")
     * @ORM\JoinColumn(name="codigo_tipo_combustible_fk", referencedColumnName="codigo_tipo_combustible_pk")
     */
    private $tipoCombustibleRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteColor", inversedBy="vehiculosColorRel")
     * @ORM\JoinColumn(name="codigo_color_fk", referencedColumnName="codigo_color_pk")
     */
    private $colorRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteTipoCarroceria", inversedBy="vehiculosTipoCarroceriaRel")
     * @ORM\JoinColumn(name="codigo_tipo_carroceria_fk", referencedColumnName="codigo_tipo_carroceria_pk")
     */
    private $tipoCarroceriaRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteLinea", inversedBy="vehiculosLineaRel")
     * @ORM\JoinColumn(name="codigo_linea_fk", referencedColumnName="codigo_linea_pk")
     */
    private $lineaRel;

    /**
     * @ORM\OneToMany(targetEntity="TteDespacho", mappedBy="vehiculoRel")
     */
    protected $despachosVehiculoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteRecogida", mappedBy="vehiculoRel")
     */
    protected $recogidasVechiculoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteDespachoRecogida", mappedBy="vehiculoRel")
     */
    protected $despachosRecogidasVehiculoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteMonitoreo", mappedBy="vehiculoRel")
     */
    protected $monitoreosVehiculoRel;

    /**
     * @return mixed
     */
    public function getCodigoVehiculoPk()
    {
        return $this->codigoVehiculoPk;
    }

    /**
     * @param mixed $codigoVehiculoPk
     */
    public function setCodigoVehiculoPk($codigoVehiculoPk): void
    {
        $this->codigoVehiculoPk = $codigoVehiculoPk;
    }

    /**
     * @return mixed
     */
    public function getPlaca()
    {
        return $this->placa;
    }

    /**
     * @param mixed $placa
     */
    public function setPlaca($placa): void
    {
        $this->placa = $placa;
    }

    /**
     * @return mixed
     */
    public function getPlacaRemolque()
    {
        return $this->placaRemolque;
    }

    /**
     * @param mixed $placaRemolque
     */
    public function setPlacaRemolque($placaRemolque): void
    {
        $this->placaRemolque = $placaRemolque;
    }

    /**
     * @return mixed
     */
    public function getModelo()
    {
        return $this->modelo;
    }

    /**
     * @param mixed $modelo
     */
    public function setModelo($modelo): void
    {
        $this->modelo = $modelo;
    }

    /**
     * @return mixed
     */
    public function getModeloRepotenciado()
    {
        return $this->modeloRepotenciado;
    }

    /**
     * @param mixed $modeloRepotenciado
     */
    public function setModeloRepotenciado($modeloRepotenciado): void
    {
        $this->modeloRepotenciado = $modeloRepotenciado;
    }

    /**
     * @return mixed
     */
    public function getMotor()
    {
        return $this->motor;
    }

    /**
     * @param mixed $motor
     */
    public function setMotor($motor): void
    {
        $this->motor = $motor;
    }

    /**
     * @return mixed
     */
    public function getNumeroEjes()
    {
        return $this->numeroEjes;
    }

    /**
     * @param mixed $numeroEjes
     */
    public function setNumeroEjes($numeroEjes): void
    {
        $this->numeroEjes = $numeroEjes;
    }

    /**
     * @return mixed
     */
    public function getChasis()
    {
        return $this->chasis;
    }

    /**
     * @param mixed $chasis
     */
    public function setChasis($chasis): void
    {
        $this->chasis = $chasis;
    }

    /**
     * @return mixed
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * @param mixed $serie
     */
    public function setSerie($serie): void
    {
        $this->serie = $serie;
    }

    /**
     * @return mixed
     */
    public function getPesoVacio()
    {
        return $this->pesoVacio;
    }

    /**
     * @param mixed $pesoVacio
     */
    public function setPesoVacio($pesoVacio): void
    {
        $this->pesoVacio = $pesoVacio;
    }

    /**
     * @return mixed
     */
    public function getCapacidad()
    {
        return $this->capacidad;
    }

    /**
     * @param mixed $capacidad
     */
    public function setCapacidad($capacidad): void
    {
        $this->capacidad = $capacidad;
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
    public function getRegistroNacionalCarga()
    {
        return $this->registroNacionalCarga;
    }

    /**
     * @param mixed $registroNacionalCarga
     */
    public function setRegistroNacionalCarga($registroNacionalCarga): void
    {
        $this->registroNacionalCarga = $registroNacionalCarga;
    }

    /**
     * @return mixed
     */
    public function getConfiguracion()
    {
        return $this->configuracion;
    }

    /**
     * @param mixed $configuracion
     */
    public function setConfiguracion($configuracion): void
    {
        $this->configuracion = $configuracion;
    }

    /**
     * @return mixed
     */
    public function getNumeroPoliza()
    {
        return $this->numeroPoliza;
    }

    /**
     * @param mixed $numeroPoliza
     */
    public function setNumeroPoliza($numeroPoliza): void
    {
        $this->numeroPoliza = $numeroPoliza;
    }

    /**
     * @return mixed
     */
    public function getFechaVencePoliza()
    {
        return $this->fechaVencePoliza;
    }

    /**
     * @param mixed $fechaVencePoliza
     */
    public function setFechaVencePoliza($fechaVencePoliza): void
    {
        $this->fechaVencePoliza = $fechaVencePoliza;
    }

    /**
     * @return mixed
     */
    public function getFechaVenceTecnicomecanica()
    {
        return $this->fechaVenceTecnicomecanica;
    }

    /**
     * @param mixed $fechaVenceTecnicomecanica
     */
    public function setFechaVenceTecnicomecanica($fechaVenceTecnicomecanica): void
    {
        $this->fechaVenceTecnicomecanica = $fechaVenceTecnicomecanica;
    }

    /**
     * @return mixed
     */
    public function getCodigoPoseedorFk()
    {
        return $this->codigoPoseedorFk;
    }

    /**
     * @param mixed $codigoPoseedorFk
     */
    public function setCodigoPoseedorFk($codigoPoseedorFk): void
    {
        $this->codigoPoseedorFk = $codigoPoseedorFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPropietarioFk()
    {
        return $this->codigoPropietarioFk;
    }

    /**
     * @param mixed $codigoPropietarioFk
     */
    public function setCodigoPropietarioFk($codigoPropietarioFk): void
    {
        $this->codigoPropietarioFk = $codigoPropietarioFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoAseguradoraFk()
    {
        return $this->codigoAseguradoraFk;
    }

    /**
     * @param mixed $codigoAseguradoraFk
     */
    public function setCodigoAseguradoraFk($codigoAseguradoraFk): void
    {
        $this->codigoAseguradoraFk = $codigoAseguradoraFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoColorFk()
    {
        return $this->codigoColorFk;
    }

    /**
     * @param mixed $codigoColorFk
     */
    public function setCodigoColorFk($codigoColorFk): void
    {
        $this->codigoColorFk = $codigoColorFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoMarcaFk()
    {
        return $this->codigoMarcaFk;
    }

    /**
     * @param mixed $codigoMarcaFk
     */
    public function setCodigoMarcaFk($codigoMarcaFk): void
    {
        $this->codigoMarcaFk = $codigoMarcaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoLineaFk()
    {
        return $this->codigoLineaFk;
    }

    /**
     * @param mixed $codigoLineaFk
     */
    public function setCodigoLineaFk($codigoLineaFk): void
    {
        $this->codigoLineaFk = $codigoLineaFk;
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
    public function getMarcaRel()
    {
        return $this->marcaRel;
    }

    /**
     * @param mixed $marcaRel
     */
    public function setMarcaRel($marcaRel): void
    {
        $this->marcaRel = $marcaRel;
    }

    /**
     * @return mixed
     */
    public function getPoseedorRel()
    {
        return $this->poseedorRel;
    }

    /**
     * @param mixed $poseedorRel
     */
    public function setPoseedorRel($poseedorRel): void
    {
        $this->poseedorRel = $poseedorRel;
    }

    /**
     * @return mixed
     */
    public function getPropietarioRel()
    {
        return $this->propietarioRel;
    }

    /**
     * @param mixed $propietarioRel
     */
    public function setPropietarioRel($propietarioRel): void
    {
        $this->propietarioRel = $propietarioRel;
    }

    /**
     * @return mixed
     */
    public function getAseguradoraRel()
    {
        return $this->aseguradoraRel;
    }

    /**
     * @param mixed $aseguradoraRel
     */
    public function setAseguradoraRel($aseguradoraRel): void
    {
        $this->aseguradoraRel = $aseguradoraRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosVehiculoRel()
    {
        return $this->despachosVehiculoRel;
    }

    /**
     * @param mixed $despachosVehiculoRel
     */
    public function setDespachosVehiculoRel($despachosVehiculoRel): void
    {
        $this->despachosVehiculoRel = $despachosVehiculoRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosRecogidasVehiculoRel()
    {
        return $this->despachosRecogidasVehiculoRel;
    }

    /**
     * @param mixed $despachosRecogidasVehiculoRel
     */
    public function setDespachosRecogidasVehiculoRel($despachosRecogidasVehiculoRel): void
    {
        $this->despachosRecogidasVehiculoRel = $despachosRecogidasVehiculoRel;
    }

    /**
     * @return mixed
     */
    public function getMonitoreosVehiculoRel()
    {
        return $this->monitoreosVehiculoRel;
    }

    /**
     * @param mixed $monitoreosVehiculoRel
     */
    public function setMonitoreosVehiculoRel($monitoreosVehiculoRel): void
    {
        $this->monitoreosVehiculoRel = $monitoreosVehiculoRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoTipoCombustibleFk()
    {
        return $this->codigoTipoCombustibleFk;
    }

    /**
     * @param mixed $codigoTipoCombustibleFk
     */
    public function setCodigoTipoCombustibleFk($codigoTipoCombustibleFk): void
    {
        $this->codigoTipoCombustibleFk = $codigoTipoCombustibleFk;
    }

    /**
     * @return mixed
     */
    public function getTipoCombustibleRel()
    {
        return $this->tipoCombustibleRel;
    }

    /**
     * @param mixed $tipoCombustibleRel
     */
    public function setTipoCombustibleRel($tipoCombustibleRel): void
    {
        $this->tipoCombustibleRel = $tipoCombustibleRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoTipoCarroceriaFk()
    {
        return $this->codigoTipoCarroceriaFk;
    }

    /**
     * @param mixed $codigoTipoCarroceriaFk
     */
    public function setCodigoTipoCarroceriaFk($codigoTipoCarroceriaFk): void
    {
        $this->codigoTipoCarroceriaFk = $codigoTipoCarroceriaFk;
    }

    /**
     * @return mixed
     */
    public function getTipoCarroceriaRel()
    {
        return $this->tipoCarroceriaRel;
    }

    /**
     * @param mixed $tipoCarroceriaRel
     */
    public function setTipoCarroceriaRel($tipoCarroceriaRel): void
    {
        $this->tipoCarroceriaRel = $tipoCarroceriaRel;
    }

    /**
     * @return mixed
     */
    public function getRecogidasVechiculoRel()
    {
        return $this->recogidasVechiculoRel;
    }

    /**
     * @param mixed $recogidasVechiculoRel
     */
    public function setRecogidasVechiculoRel($recogidasVechiculoRel): void
    {
        $this->recogidasVechiculoRel = $recogidasVechiculoRel;
    }

    /**
     * @return mixed
     */
    public function getColorRel()
    {
        return $this->colorRel;
    }

    /**
     * @param mixed $colorRel
     */
    public function setColorRel($colorRel): void
    {
        $this->colorRel = $colorRel;
    }

    /**
     * @return mixed
     */
    public function getLineaRel()
    {
        return $this->lineaRel;
    }

    /**
     * @param mixed $lineaRel
     */
    public function setLineaRel($lineaRel): void
    {
        $this->lineaRel = $lineaRel;
    }

    /**
     * @return mixed
     */
    public function getNumeroTecnicomecanica()
    {
        return $this->numeroTecnicomecanica;
    }

    /**
     * @param mixed $numeroTecnicomecanica
     */
    public function setNumeroTecnicomecanica( $numeroTecnicomecanica ): void
    {
        $this->numeroTecnicomecanica = $numeroTecnicomecanica;
    }

    /**
     * @return mixed
     */
    public function getEstadoInactivo()
    {
        return $this->estadoInactivo;
    }

    /**
     * @param mixed $estadoInactivo
     */
    public function setEstadoInactivo($estadoInactivo): void
    {
        $this->estadoInactivo = $estadoInactivo;
    }



}

