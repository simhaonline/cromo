<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TtePoseedorRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TtePoseedor
{
    public $infoLog = [
        "primaryKey" => "codigoPoseedorPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoPoseedorPk;


    /**
     * @ORM\Column(name="codigo_identificacion_fk", type="string", length=3 , nullable=true)
     */
    private $codigoIdentificacionFk;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=true)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="digito_verificacion", type="string", length=1, nullable=true)
     */
    private $digitoVerificacion;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=150, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="nombre1", type="string", length=50, nullable=true)
     */
    private $nombre1;

    /**
     * @ORM\Column(name="nombre2", type="string", length=50, nullable=true)
     */
    private $nombre2;

    /**
     * @ORM\Column(name="apellido1", type="string", length=50, nullable=true)
     */
    private $apellido1;

    /**
     * @ORM\Column(name="apellido2", type="string", length=50, nullable=true)
     */
    private $apellido2;

    /**
     * @ORM\Column(name="direccion", type="string", length=200, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="string", length=20, nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="telefono", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max = 30,
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     */
    private $telefono;

    /**
     * @ORM\Column(name="movil", type="string", length=30, nullable=true)
     */
    private $movil;

    /**
     * @ORM\Column(name="correo", type="string", length=1000, nullable=true)
     */
    private $correo;

    /**
     * @ORM\Column(name="retencion_fuente", type="boolean", nullable=true,options={"default":true})
     */
    private $retencionFuente = true;

    /**
     * @ORM\Column(name="retencion_industria_comercio", type="boolean", nullable=true,options={"default":true})
     */
    private $retencionIndustriaComercio = true;

    /**
     * @ORM\ManyToOne(targetEntity="TteCiudad", inversedBy="poseedoresCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenIdentificacion", inversedBy="ttePoseedoresIdentificacionRel")
     * @ORM\JoinColumn(name="codigo_identificacion_fk", referencedColumnName="codigo_identificacion_pk")
     */
    private $identificacionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteVehiculo", mappedBy="poseedorRel")
     */
    protected $vehiculosPoseedorRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteVehiculo", mappedBy="propietarioRel")
     */
    protected $vehiculosPropietarioRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteIntermediacionCompra", mappedBy="poseedorRel")
     */
    protected $intermediacionesComprasPoseedorRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteIntermediacionRecogida", mappedBy="poseedorRel")
     */
    protected $intermediacionesRecogidasPoseedorRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteDespacho", mappedBy="poseedorRel")
     */
    protected $despachosPoseedorRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteDespachoRecogida", mappedBy="poseedorRel")
     */
    protected $despachosRecogidasPoseedorRel;

    /**
     * @return mixed
     */
    public function getCodigoPoseedorPk()
    {
        return $this->codigoPoseedorPk;
    }

    /**
     * @param mixed $codigoPoseedorPk
     */
    public function setCodigoPoseedorPk($codigoPoseedorPk): void
    {
        $this->codigoPoseedorPk = $codigoPoseedorPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoIdentificacionFk()
    {
        return $this->codigoIdentificacionFk;
    }

    /**
     * @param mixed $codigoIdentificacionFk
     */
    public function setCodigoIdentificacionFk($codigoIdentificacionFk): void
    {
        $this->codigoIdentificacionFk = $codigoIdentificacionFk;
    }

    /**
     * @return mixed
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * @param mixed $numeroIdentificacion
     */
    public function setNumeroIdentificacion($numeroIdentificacion): void
    {
        $this->numeroIdentificacion = $numeroIdentificacion;
    }

    /**
     * @return mixed
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * @param mixed $nombreCorto
     */
    public function setNombreCorto($nombreCorto): void
    {
        $this->nombreCorto = $nombreCorto;
    }

    /**
     * @return mixed
     */
    public function getNombre1()
    {
        return $this->nombre1;
    }

    /**
     * @param mixed $nombre1
     */
    public function setNombre1($nombre1): void
    {
        $this->nombre1 = $nombre1;
    }

    /**
     * @return mixed
     */
    public function getNombre2()
    {
        return $this->nombre2;
    }

    /**
     * @param mixed $nombre2
     */
    public function setNombre2($nombre2): void
    {
        $this->nombre2 = $nombre2;
    }

    /**
     * @return mixed
     */
    public function getApellido1()
    {
        return $this->apellido1;
    }

    /**
     * @param mixed $apellido1
     */
    public function setApellido1($apellido1): void
    {
        $this->apellido1 = $apellido1;
    }

    /**
     * @return mixed
     */
    public function getApellido2()
    {
        return $this->apellido2;
    }

    /**
     * @param mixed $apellido2
     */
    public function setApellido2($apellido2): void
    {
        $this->apellido2 = $apellido2;
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
    public function getMovil()
    {
        return $this->movil;
    }

    /**
     * @param mixed $movil
     */
    public function setMovil($movil): void
    {
        $this->movil = $movil;
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
    public function getIdentificacionRel()
    {
        return $this->identificacionRel;
    }

    /**
     * @param mixed $identificacionRel
     */
    public function setIdentificacionRel($identificacionRel): void
    {
        $this->identificacionRel = $identificacionRel;
    }

    /**
     * @return mixed
     */
    public function getVehiculosPoseedorRel()
    {
        return $this->vehiculosPoseedorRel;
    }

    /**
     * @param mixed $vehiculosPoseedorRel
     */
    public function setVehiculosPoseedorRel($vehiculosPoseedorRel): void
    {
        $this->vehiculosPoseedorRel = $vehiculosPoseedorRel;
    }

    /**
     * @return mixed
     */
    public function getVehiculosPropietarioRel()
    {
        return $this->vehiculosPropietarioRel;
    }

    /**
     * @param mixed $vehiculosPropietarioRel
     */
    public function setVehiculosPropietarioRel($vehiculosPropietarioRel): void
    {
        $this->vehiculosPropietarioRel = $vehiculosPropietarioRel;
    }

    /**
     * @return mixed
     */
    public function getDigitoVerificacion()
    {
        return $this->digitoVerificacion;
    }

    /**
     * @param mixed $digitoVerificacion
     */
    public function setDigitoVerificacion($digitoVerificacion): void
    {
        $this->digitoVerificacion = $digitoVerificacion;
    }

    /**
     * @return mixed
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * @param mixed $correo
     */
    public function setCorreo($correo): void
    {
        $this->correo = $correo;
    }

    /**
     * @return mixed
     */
    public function getIntermediacionesComprasPoseedorRel()
    {
        return $this->intermediacionesComprasPoseedorRel;
    }

    /**
     * @param mixed $intermediacionesComprasPoseedorRel
     */
    public function setIntermediacionesComprasPoseedorRel($intermediacionesComprasPoseedorRel): void
    {
        $this->intermediacionesComprasPoseedorRel = $intermediacionesComprasPoseedorRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosPoseedorRel()
    {
        return $this->despachosPoseedorRel;
    }

    /**
     * @param mixed $despachosPoseedorRel
     */
    public function setDespachosPoseedorRel($despachosPoseedorRel): void
    {
        $this->despachosPoseedorRel = $despachosPoseedorRel;
    }

    /**
     * @return mixed
     */
    public function getIntermediacionesRecogidasPoseedorRel()
    {
        return $this->intermediacionesRecogidasPoseedorRel;
    }

    /**
     * @param mixed $intermediacionesRecogidasPoseedorRel
     */
    public function setIntermediacionesRecogidasPoseedorRel($intermediacionesRecogidasPoseedorRel): void
    {
        $this->intermediacionesRecogidasPoseedorRel = $intermediacionesRecogidasPoseedorRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosRecogidasPoseedorRel()
    {
        return $this->despachosRecogidasPoseedorRel;
    }

    /**
     * @param mixed $despachosRecogidasPoseedorRel
     */
    public function setDespachosRecogidasPoseedorRel($despachosRecogidasPoseedorRel): void
    {
        $this->despachosRecogidasPoseedorRel = $despachosRecogidasPoseedorRel;
    }

    /**
     * @return mixed
     */
    public function getRetencionFuente()
    {
        return $this->retencionFuente;
    }

    /**
     * @param mixed $retencionFuente
     */
    public function setRetencionFuente($retencionFuente): void
    {
        $this->retencionFuente = $retencionFuente;
    }

    /**
     * @return mixed
     */
    public function getRetencionIndustriaComercio()
    {
        return $this->retencionIndustriaComercio;
    }

    /**
     * @param mixed $retencionIndustriaComercio
     */
    public function setRetencionIndustriaComercio($retencionIndustriaComercio): void
    {
        $this->retencionIndustriaComercio = $retencionIndustriaComercio;
    }



}

