<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvTerceroRepository")
 */
class InvTercero
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="codigo_tercero_pk",type="integer")
     */
    private $codigoTerceroPk;

    /**
     * @ORM\Column(name="digito_verificacion", type="string", length=1, nullable=true)
     */
    private $digitoVerificacion;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=80)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=150, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="nombres", type="string", length=100, nullable=true)
     */
    private $nombres;

    /**
     * @ORM\Column(name="apellido1", type="string", length=50, nullable=true)
     */
    private $apellido1;

    /**
     * @ORM\Column(name="apellido2", type="string", length=50, nullable=true)
     */
    private $apellido2;

    /**
     * @ORM\Column(name="codigo_clasificacion_tributaria_fk", type="integer", nullable=true)
     */
    private $codigoClasificacionTributariaFk;

    /**
     * @ORM\Column(name="codigo_forma_pago_fk", type="integer", nullable=true)
     */
    private $codigoFormaPagoFk;

    /**
     * @ORM\Column(name="plazo_pago", type="integer")
     */
    private $plazoPago = 0;

    /**
     * @ORM\Column(name="direccion", type="string", length=80,nullable=true)
     */
    private $direccion;
    /**
     * @ORM\Column(name="telefono", type="string", length=20,nullable=true)
     */
    private $telefono;
    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true)
     */
    private $celular;

    /**
     * @ORM\Column(name="email", type="string", length=80, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(name="autoretenedor", type="boolean", nullable=true)
     */
    private $autoretenedor = false;

    /**
     * @ORM\Column(name="codigo_precio_venta_fk", type="integer", nullable=true)
     */
    private $codigoPrecioVentaFk;

    /**
     * @ORM\ManyToOne(targetEntity="InvPrecio", inversedBy="tercerosPrecioVentaRel")
     * @ORM\JoinColumn(name="codigo_precio_venta_fk", referencedColumnName="codigo_precio_pk")
     */
    protected $precioVentaRel;

    /**
     * @ORM\OneToMany(targetEntity="InvOrdenCompra",mappedBy="terceroRel")
     */
    protected $ordenesComprasTerceroRel;

    /**
     * @ORM\OneToMany(targetEntity="InvMovimiento",mappedBy="terceroRel")
     */
    protected $movimientosTerceroRel;

    /**
     * @ORM\OneToMany(targetEntity="InvPedido",mappedBy="terceroRel")
     */
    protected $pedidosTerceroRel;

    /**
     * @ORM\OneToMany(targetEntity="InvSucursal",mappedBy="terceroRel")
     */
    protected $sucursalesTerceroRel;

    /**
     * @return mixed
     */
    public function getCodigoTerceroPk()
    {
        return $this->codigoTerceroPk;
    }

    /**
     * @param mixed $codigoTerceroPk
     */
    public function setCodigoTerceroPk($codigoTerceroPk): void
    {
        $this->codigoTerceroPk = $codigoTerceroPk;
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
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * @param mixed $nombres
     */
    public function setNombres($nombres): void
    {
        $this->nombres = $nombres;
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
    public function getCodigoClasificacionTributariaFk()
    {
        return $this->codigoClasificacionTributariaFk;
    }

    /**
     * @param mixed $codigoClasificacionTributariaFk
     */
    public function setCodigoClasificacionTributariaFk($codigoClasificacionTributariaFk): void
    {
        $this->codigoClasificacionTributariaFk = $codigoClasificacionTributariaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoFormaPagoFk()
    {
        return $this->codigoFormaPagoFk;
    }

    /**
     * @param mixed $codigoFormaPagoFk
     */
    public function setCodigoFormaPagoFk($codigoFormaPagoFk): void
    {
        $this->codigoFormaPagoFk = $codigoFormaPagoFk;
    }

    /**
     * @return mixed
     */
    public function getPlazoPago()
    {
        return $this->plazoPago;
    }

    /**
     * @param mixed $plazoPago
     */
    public function setPlazoPago($plazoPago): void
    {
        $this->plazoPago = $plazoPago;
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getAutoretenedor()
    {
        return $this->autoretenedor;
    }

    /**
     * @param mixed $autoretenedor
     */
    public function setAutoretenedor($autoretenedor): void
    {
        $this->autoretenedor = $autoretenedor;
    }

    /**
     * @return mixed
     */
    public function getCodigoPrecioVentaFk()
    {
        return $this->codigoPrecioVentaFk;
    }

    /**
     * @param mixed $codigoPrecioVentaFk
     */
    public function setCodigoPrecioVentaFk($codigoPrecioVentaFk): void
    {
        $this->codigoPrecioVentaFk = $codigoPrecioVentaFk;
    }

    /**
     * @return mixed
     */
    public function getPrecioVentaRel()
    {
        return $this->precioVentaRel;
    }

    /**
     * @param mixed $precioVentaRel
     */
    public function setPrecioVentaRel($precioVentaRel): void
    {
        $this->precioVentaRel = $precioVentaRel;
    }

    /**
     * @return mixed
     */
    public function getOrdenesComprasTerceroRel()
    {
        return $this->ordenesComprasTerceroRel;
    }

    /**
     * @param mixed $ordenesComprasTerceroRel
     */
    public function setOrdenesComprasTerceroRel($ordenesComprasTerceroRel): void
    {
        $this->ordenesComprasTerceroRel = $ordenesComprasTerceroRel;
    }

    /**
     * @return mixed
     */
    public function getMovimientosTerceroRel()
    {
        return $this->movimientosTerceroRel;
    }

    /**
     * @param mixed $movimientosTerceroRel
     */
    public function setMovimientosTerceroRel($movimientosTerceroRel): void
    {
        $this->movimientosTerceroRel = $movimientosTerceroRel;
    }

    /**
     * @return mixed
     */
    public function getPedidosTerceroRel()
    {
        return $this->pedidosTerceroRel;
    }

    /**
     * @param mixed $pedidosTerceroRel
     */
    public function setPedidosTerceroRel($pedidosTerceroRel): void
    {
        $this->pedidosTerceroRel = $pedidosTerceroRel;
    }

    /**
     * @return mixed
     */
    public function getSucursalesTerceroRel()
    {
        return $this->sucursalesTerceroRel;
    }

    /**
     * @param mixed $sucursalesTerceroRel
     */
    public function setSucursalesTerceroRel($sucursalesTerceroRel): void
    {
        $this->sucursalesTerceroRel = $sucursalesTerceroRel;
    }
}

