<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurPuestoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurPuesto
{
    public $infoLog = [
        "primaryKey" => "codigoPuestoPk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_puesto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPuestoPk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="nombre", type="string", length=300)
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     * @Assert\Length(
     *     max = 300,
     *     maxMessage="El campo no puede contener mas de 300 caracteres"
     * )
     */
    private $nombre;


    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     * @Assert\Length(
     *     max = 80,
     *     maxMessage="El campo no puede contener mas de 80 caracteres"
     * )
     */
    private $direccion;

    /**
     * @ORM\Column(name="telefono", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max = 30,
     *     maxMessage="El campo no puede contener mas de 30 caracteres"
     * )
     */
    private $telefono;

    /**
     * @ORM\Column(name="celular", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max = 30,
     *     maxMessage="El campo no puede contener mas de 30 caracteres"
     * )
     */
    private $celular;

    /**
     * @ORM\Column(name="comunicacion", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max = 30,
     *     maxMessage="El campo no puede contener mas de 30 caracteres"
     * )
     */
    private $comunicacion;

    /**
     * @ORM\Column(name="codigo_programador_fk", type="integer", nullable=true)
     */
    private $codigoProgramadorFk;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="comentario", type="text", nullable=true)
     */
    private $comentario;

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCentroCostoFk;

    /**
     * @ORM\Column(name="ubicacion_gps", type="string", length=100, nullable=true)
     */
    private $ubicacionGps;

    /**
     * @ORM\Column(name="estado_inactivo", type="boolean", nullable=true, options={"default":false})
     */
    private $estadoInactivo = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Turno\TurCliente", inversedBy="PuestosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCiudad", inversedBy="turCiudadPuestoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Turno\TurProgramador", inversedBy="turProgramadorPuestosRel")
     * @ORM\JoinColumn(name="codigo_programador_fk", referencedColumnName="codigo_programador_pk")
     */
    protected $programadorRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Financiero\FinCentroCosto", inversedBy="turPuestosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;

    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="puestoRel")
     */
    protected $pedidosDetallesPuestoRel;

    /**
     * @ORM\OneToMany(targetEntity="TurContratoDetalle", mappedBy="puestoRel")
     */
    protected $contratosDetallesPuestoRel;

    /**
     * @return mixed
     */
    public function getCodigoPuestoPk()
    {
        return $this->codigoPuestoPk;
    }

    /**
     * @param mixed $codigoPuestoPk
     */
    public function setCodigoPuestoPk($codigoPuestoPk): void
    {
        $this->codigoPuestoPk = $codigoPuestoPk;
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
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
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
    public function getComunicacion()
    {
        return $this->comunicacion;
    }

    /**
     * @param mixed $comunicacion
     */
    public function setComunicacion($comunicacion): void
    {
        $this->comunicacion = $comunicacion;
    }

    /**
     * @return mixed
     */
    public function getCodigoProgramadorFk()
    {
        return $this->codigoProgramadorFk;
    }

    /**
     * @param mixed $codigoProgramadorFk
     */
    public function setCodigoProgramadorFk($codigoProgramadorFk): void
    {
        $this->codigoProgramadorFk = $codigoProgramadorFk;
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
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * @param mixed $codigoCentroCostoFk
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk): void
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;
    }

    /**
     * @return mixed
     */
    public function getUbicacionGps()
    {
        return $this->ubicacionGps;
    }

    /**
     * @param mixed $ubicacionGps
     */
    public function setUbicacionGps($ubicacionGps): void
    {
        $this->ubicacionGps = $ubicacionGps;
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
    public function getProgramadorRel()
    {
        return $this->programadorRel;
    }

    /**
     * @param mixed $programadorRel
     */
    public function setProgramadorRel($programadorRel): void
    {
        $this->programadorRel = $programadorRel;
    }

    /**
     * @return mixed
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }

    /**
     * @param mixed $centroCostoRel
     */
    public function setCentroCostoRel($centroCostoRel): void
    {
        $this->centroCostoRel = $centroCostoRel;
    }

    /**
     * @return mixed
     */
    public function getPedidosDetallesPuestoRel()
    {
        return $this->pedidosDetallesPuestoRel;
    }

    /**
     * @param mixed $pedidosDetallesPuestoRel
     */
    public function setPedidosDetallesPuestoRel($pedidosDetallesPuestoRel): void
    {
        $this->pedidosDetallesPuestoRel = $pedidosDetallesPuestoRel;
    }

    /**
     * @return mixed
     */
    public function getContratosDetallesPuestoRel()
    {
        return $this->contratosDetallesPuestoRel;
    }

    /**
     * @param mixed $contratosDetallesPuestoRel
     */
    public function setContratosDetallesPuestoRel($contratosDetallesPuestoRel): void
    {
        $this->contratosDetallesPuestoRel = $contratosDetallesPuestoRel;
    }


}

