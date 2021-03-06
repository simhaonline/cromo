<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarClienteRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class CarCliente
{
    public $infoLog = [
        "primaryKey" => "codigoClientePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cliente_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoClientePk;

    /**
     * @ORM\Column(name="codigo_identificacion_fk", type="string", length=3, nullable=true)
     */
    private $codigoIdentificacionFk;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=15, nullable=false)
     */
    private $numeroIdentificacion;
    
    /**
     * @ORM\Column(name="digito_verificacion", type="string", length=1, nullable=true)
     */
    private $digitoVerificacion;             
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=200)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="codigo_forma_pago_fk", type="string", length=10, nullable=true)
     */    
    private $codigoFormaPagoFk; 
    
    /**
     * @ORM\Column(name="plazo_pago", type="integer")
     */    
    private $plazoPago = 0;     
    
    /**
     * @ORM\Column(name="direccion", type="string", length=120, nullable=true)
     */
    private $direccion;   
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;         
    
    /**
     * @ORM\Column(name="telefono", type="string", length=30, nullable=true)
     */
    private $telefono;     
    
    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true, nullable=true)
     */
    private $celular;    
        
    /**
     * @ORM\Column(name="fax", type="string", length=20, nullable=true, nullable=true)
     */
    private $fax;    
    
    /**
     * @ORM\Column(name="correo", type="string", length=1000, nullable=true)
     */
    private $correo;
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;

    /**
     * @ORM\Column(name="contacto", type="string", length=120, nullable=true)
     */
    private $contacto;

    /**
     * @ORM\Column(name="contacto_telefono", type="string", length=60, nullable=true)
     */
    private $contactoTelefono;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenIdentificacion", inversedBy="carClientesIdentificacionRel")
     * @ORM\JoinColumn(name="codigo_identificacion_fk", referencedColumnName="codigo_identificacion_pk")
     */
    private $identificacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenFormaPago", inversedBy="carClientesFormaPagoRel")
     * @ORM\JoinColumn(name="codigo_forma_pago_fk", referencedColumnName="codigo_forma_pago_pk")
     */
    protected $formaPagoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCiudad", inversedBy="carClientesCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;

    /**
     * @ORM\OneToMany(targetEntity="CarCuentaCobrar", mappedBy="clienteRel")
     */
    protected $cuentaCobrarClientesRel;

    /**
     * @ORM\OneToMany(targetEntity="CarNotaCredito", mappedBy="clienteRel")
     */
    protected $notasCreditosClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="CarNotaDebito", mappedBy="clienteRel")
     */
    protected $notasDebitosClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="CarRecibo", mappedBy="clienteRel")
     */
    protected $recibosClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="CarCompromiso", mappedBy="clienteRel")
     */
    protected $compromisosClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarAnticipo", mappedBy="clienteRel")
     */
    protected $anticiposClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarIngreso", mappedBy="clienteRel")
     */
    protected $ingresosClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarIngresoDetalle", mappedBy="clienteRel")
     */
    protected $ingresosDetallesClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarMovimiento", mappedBy="clienteRel")
     */
    protected $movimientosClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarMovimientoDetalle", mappedBy="clienteRel")
     */
    protected $movimientosDetallesClienteRel;

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
    public function getCodigoClientePk()
    {
        return $this->codigoClientePk;
    }

    /**
     * @param mixed $codigoClientePk
     */
    public function setCodigoClientePk($codigoClientePk): void
    {
        $this->codigoClientePk = $codigoClientePk;
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
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param mixed $fax
     */
    public function setFax($fax): void
    {
        $this->fax = $fax;
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
    public function getContactoTelefono()
    {
        return $this->contactoTelefono;
    }

    /**
     * @param mixed $contactoTelefono
     */
    public function setContactoTelefono($contactoTelefono): void
    {
        $this->contactoTelefono = $contactoTelefono;
    }

    /**
     * @return mixed
     */
    public function getCuentaCobrarClientesRel()
    {
        return $this->cuentaCobrarClientesRel;
    }

    /**
     * @param mixed $cuentaCobrarClientesRel
     */
    public function setCuentaCobrarClientesRel($cuentaCobrarClientesRel): void
    {
        $this->cuentaCobrarClientesRel = $cuentaCobrarClientesRel;
    }

    /**
     * @return mixed
     */
    public function getNotasCreditosClienteRel()
    {
        return $this->notasCreditosClienteRel;
    }

    /**
     * @param mixed $notasCreditosClienteRel
     */
    public function setNotasCreditosClienteRel($notasCreditosClienteRel): void
    {
        $this->notasCreditosClienteRel = $notasCreditosClienteRel;
    }

    /**
     * @return mixed
     */
    public function getNotasDebitosClienteRel()
    {
        return $this->notasDebitosClienteRel;
    }

    /**
     * @param mixed $notasDebitosClienteRel
     */
    public function setNotasDebitosClienteRel($notasDebitosClienteRel): void
    {
        $this->notasDebitosClienteRel = $notasDebitosClienteRel;
    }

    /**
     * @return mixed
     */
    public function getRecibosClienteRel()
    {
        return $this->recibosClienteRel;
    }

    /**
     * @param mixed $recibosClienteRel
     */
    public function setRecibosClienteRel($recibosClienteRel): void
    {
        $this->recibosClienteRel = $recibosClienteRel;
    }

    /**
     * @return mixed
     */
    public function getAnticiposClienteRel()
    {
        return $this->anticiposClienteRel;
    }

    /**
     * @param mixed $anticiposClienteRel
     */
    public function setAnticiposClienteRel($anticiposClienteRel): void
    {
        $this->anticiposClienteRel = $anticiposClienteRel;
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
    public function getFormaPagoRel()
    {
        return $this->formaPagoRel;
    }

    /**
     * @param mixed $formaPagoRel
     */
    public function setFormaPagoRel($formaPagoRel): void
    {
        $this->formaPagoRel = $formaPagoRel;
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
    public function getCompromisosClienteRel()
    {
        return $this->compromisosClienteRel;
    }

    /**
     * @param mixed $compromisosClienteRel
     */
    public function setCompromisosClienteRel($compromisosClienteRel): void
    {
        $this->compromisosClienteRel = $compromisosClienteRel;
    }

    /**
     * @return mixed
     */
    public function getIngresosClienteRel()
    {
        return $this->ingresosClienteRel;
    }

    /**
     * @param mixed $ingresosClienteRel
     */
    public function setIngresosClienteRel($ingresosClienteRel): void
    {
        $this->ingresosClienteRel = $ingresosClienteRel;
    }

    /**
     * @return mixed
     */
    public function getIngresosDetallesClienteRel()
    {
        return $this->ingresosDetallesClienteRel;
    }

    /**
     * @param mixed $ingresosDetallesClienteRel
     */
    public function setIngresosDetallesClienteRel($ingresosDetallesClienteRel): void
    {
        $this->ingresosDetallesClienteRel = $ingresosDetallesClienteRel;
    }

    /**
     * @return mixed
     */
    public function getMovimientosClienteRel()
    {
        return $this->movimientosClienteRel;
    }

    /**
     * @param mixed $movimientosClienteRel
     */
    public function setMovimientosClienteRel($movimientosClienteRel): void
    {
        $this->movimientosClienteRel = $movimientosClienteRel;
    }

    /**
     * @return mixed
     */
    public function getMovimientosDetallesClienteRel()
    {
        return $this->movimientosDetallesClienteRel;
    }

    /**
     * @param mixed $movimientosDetallesClienteRel
     */
    public function setMovimientosDetallesClienteRel($movimientosDetallesClienteRel): void
    {
        $this->movimientosDetallesClienteRel = $movimientosDetallesClienteRel;
    }



}
