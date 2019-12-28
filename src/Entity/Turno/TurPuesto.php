<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurPuestoRepository")
 */
class TurPuesto
{
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
     * @ORM\Column(name="codigo_puesto_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoPuestoTipoFk;

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
     * @ORM\Column(name="contacto", type="string", length=300, nullable=true)
     */
    private $contacto;

    /**
     * @ORM\Column(name="comentario", type="text", nullable=true)
     */
    private $comentario;

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCentroCostoFk;

    /**
     * @ORM\Column(name="latitud", type="float", nullable=true, options={"default":0})
     */
    private $latitud = 0;

    /**
     * @ORM\Column(name="longitud", type="float", nullable=true, options={"default":0})
     */
    private $longitud = 0;

    /**
     * @ORM\Column(name="estado_inactivo", type="boolean", nullable=true, options={"default":false})
     */
    private $estadoInactivo = false;

    /**
     * @ORM\Column(name="codigo_salario_fk", type="string", length=10, nullable=true)
     */
    private $codigoSalarioFk;

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
     * @ORM\ManyToOne(targetEntity="TurPuestoTipo", inversedBy="puestosPuestoTipoRel")
     * @ORM\JoinColumn(name="codigo_puesto_tipo_fk", referencedColumnName="codigo_puesto_tipo_pk")
     */
    protected $puestoTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Turno\TurSalario", inversedBy="puestosSalariosRel")
     * @ORM\JoinColumn(name="codigo_salario_fk", referencedColumnName="codigo_salario_pk")
     */
    protected $salarioRel;

    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="puestoRel")
     */
    protected $pedidosDetallesPuestoRel;

    /**
     * @ORM\OneToMany(targetEntity="TurFacturaDetalle", mappedBy="puestoRel")
     */
    protected $facturasDetallesPuestoRel;

    /**
     * @ORM\OneToMany(targetEntity="TurContratoDetalle", mappedBy="puestoRel")
     */
    protected $contratosDetallesPuestoRel;

    /**
     * @ORM\OneToMany(targetEntity="TurProgramacion", mappedBy="puestoRel")
     */
    protected $programacionesPuestoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCostoEmpleadoServicio", mappedBy="puestoRel")
     */
    protected $costosEmpleadosServiciosPuestoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCostoServicio", mappedBy="puestoRel")
     */
    protected $costosServiciosPuestoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurSoporteHora", mappedBy="puestoRel")
     */
    protected $soportesHorasPuestoRel;

    /**
     * @ORM\OneToMany(targetEntity="TurPuestoAdicional", mappedBy="puestoRel")
     */
    protected $puestosAdicionalesPuestoRel;

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
    public function getCodigoPuestoTipoFk()
    {
        return $this->codigoPuestoTipoFk;
    }

    /**
     * @param mixed $codigoPuestoTipoFk
     */
    public function setCodigoPuestoTipoFk($codigoPuestoTipoFk): void
    {
        $this->codigoPuestoTipoFk = $codigoPuestoTipoFk;
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
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * @param mixed $contacto
     */
    public function setContacto($contacto): void
    {
        $this->contacto = $contacto;
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
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * @param mixed $latitud
     */
    public function setLatitud($latitud): void
    {
        $this->latitud = $latitud;
    }

    /**
     * @return mixed
     */
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * @param mixed $longitud
     */
    public function setLongitud($longitud): void
    {
        $this->longitud = $longitud;
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
    public function getCodigoSalarioFk()
    {
        return $this->codigoSalarioFk;
    }

    /**
     * @param mixed $codigoSalarioFk
     */
    public function setCodigoSalarioFk($codigoSalarioFk): void
    {
        $this->codigoSalarioFk = $codigoSalarioFk;
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
    public function getPuestoTipoRel()
    {
        return $this->puestoTipoRel;
    }

    /**
     * @param mixed $puestoTipoRel
     */
    public function setPuestoTipoRel($puestoTipoRel): void
    {
        $this->puestoTipoRel = $puestoTipoRel;
    }

    /**
     * @return mixed
     */
    public function getSalarioRel()
    {
        return $this->salarioRel;
    }

    /**
     * @param mixed $salarioRel
     */
    public function setSalarioRel($salarioRel): void
    {
        $this->salarioRel = $salarioRel;
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
    public function getFacturasDetallesPuestoRel()
    {
        return $this->facturasDetallesPuestoRel;
    }

    /**
     * @param mixed $facturasDetallesPuestoRel
     */
    public function setFacturasDetallesPuestoRel($facturasDetallesPuestoRel): void
    {
        $this->facturasDetallesPuestoRel = $facturasDetallesPuestoRel;
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

    /**
     * @return mixed
     */
    public function getProgramacionesPuestoRel()
    {
        return $this->programacionesPuestoRel;
    }

    /**
     * @param mixed $programacionesPuestoRel
     */
    public function setProgramacionesPuestoRel($programacionesPuestoRel): void
    {
        $this->programacionesPuestoRel = $programacionesPuestoRel;
    }

    /**
     * @return mixed
     */
    public function getCostosEmpleadosServiciosPuestoRel()
    {
        return $this->costosEmpleadosServiciosPuestoRel;
    }

    /**
     * @param mixed $costosEmpleadosServiciosPuestoRel
     */
    public function setCostosEmpleadosServiciosPuestoRel($costosEmpleadosServiciosPuestoRel): void
    {
        $this->costosEmpleadosServiciosPuestoRel = $costosEmpleadosServiciosPuestoRel;
    }

    /**
     * @return mixed
     */
    public function getCostosServiciosPuestoRel()
    {
        return $this->costosServiciosPuestoRel;
    }

    /**
     * @param mixed $costosServiciosPuestoRel
     */
    public function setCostosServiciosPuestoRel($costosServiciosPuestoRel): void
    {
        $this->costosServiciosPuestoRel = $costosServiciosPuestoRel;
    }

    /**
     * @return mixed
     */
    public function getSoportesHorasPuestoRel()
    {
        return $this->soportesHorasPuestoRel;
    }

    /**
     * @param mixed $soportesHorasPuestoRel
     */
    public function setSoportesHorasPuestoRel($soportesHorasPuestoRel): void
    {
        $this->soportesHorasPuestoRel = $soportesHorasPuestoRel;
    }

    /**
     * @return mixed
     */
    public function getPuestosAdicionalesPuestoRel()
    {
        return $this->puestosAdicionalesPuestoRel;
    }

    /**
     * @param mixed $puestosAdicionalesPuestoRel
     */
    public function setPuestosAdicionalesPuestoRel($puestosAdicionalesPuestoRel): void
    {
        $this->puestosAdicionalesPuestoRel = $puestosAdicionalesPuestoRel;
    }


}

