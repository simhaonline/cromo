<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenAsesorRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class GenAsesor
{
    public $infoLog = [
        "primaryKey" => "codigoAsesorPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_asesor_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAsesorPk;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=15, nullable=false)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="telefono", type="string", length=20, nullable=true)
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
     * @ORM\Column(name="usuario", type="string", length=25, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvMovimiento",mappedBy="asesorRel")
     */
    protected $movimientosAsesorRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarCuentaCobrar",mappedBy="asesorRel")
     */
    protected $cuentasCobrarAsesorRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvRemision",mappedBy="asesorRel")
     */
    protected $remisionesAsesorRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarRecibo",mappedBy="asesorRel")
     */
    protected $reciboAsesorRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarReciboDetalle",mappedBy="asesorRel")
     */
    protected $recibosDetallesAsesorRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCliente",mappedBy="asesorRel")
     */
    protected $turClienteAsesorRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarAnticipo",mappedBy="asesorRel")
     */
    protected $anticipoAsesorRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteCliente", mappedBy="asesorRel")
     */
    protected $asesorAsesorRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Seguridad\Usuario",mappedBy="asesorRel")
     */
    protected $usuariosAsesorRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvCotizacion",mappedBy="asesorRel")
     */
    protected $cotizacionesAsesorRel;

    /**
     * @return mixed
     */
    public function getCodigoAsesorPk()
    {
        return $this->codigoAsesorPk;
    }

    /**
     * @param mixed $codigoAsesorPk
     */
    public function setCodigoAsesorPk($codigoAsesorPk): void
    {
        $this->codigoAsesorPk = $codigoAsesorPk;
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
    public function getMovimientosAsesorRel()
    {
        return $this->movimientosAsesorRel;
    }

    /**
     * @param mixed $movimientosAsesorRel
     */
    public function setMovimientosAsesorRel($movimientosAsesorRel): void
    {
        $this->movimientosAsesorRel = $movimientosAsesorRel;
    }

    /**
     * @return mixed
     */
    public function getRemisionesAsesorRel()
    {
        return $this->remisionesAsesorRel;
    }

    /**
     * @param mixed $remisionesAsesorRel
     */
    public function setRemisionesAsesorRel($remisionesAsesorRel): void
    {
        $this->remisionesAsesorRel = $remisionesAsesorRel;
    }

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
    public function getCuentasCobrarAsesorRel()
    {
        return $this->cuentasCobrarAsesorRel;
    }

    /**
     * @param mixed $cuentasCobrarAsesorRel
     */
    public function setCuentasCobrarAsesorRel($cuentasCobrarAsesorRel): void
    {
        $this->cuentasCobrarAsesorRel = $cuentasCobrarAsesorRel;
    }

    /**
     * @return mixed
     */
    public function getReciboAsesorRel()
    {
        return $this->reciboAsesorRel;
    }

    /**
     * @param mixed $reciboAsesorRel
     */
    public function setReciboAsesorRel($reciboAsesorRel): void
    {
        $this->reciboAsesorRel = $reciboAsesorRel;
    }

    /**
     * @return mixed
     */
    public function getAnticipoAsesorRel()
    {
        return $this->anticipoAsesorRel;
    }

    /**
     * @param mixed $anticipoAsesorRel
     */
    public function setAnticipoAsesorRel($anticipoAsesorRel): void
    {
        $this->anticipoAsesorRel = $anticipoAsesorRel;
    }

    /**
     * @return mixed
     */
    public function getAsesorAsesorRel()
    {
        return $this->asesorAsesorRel;
    }

    /**
     * @param mixed $asesorAsesorRel
     */
    public function setAsesorAsesorRel( $asesorAsesorRel ): void
    {
        $this->asesorAsesorRel = $asesorAsesorRel;
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
    public function setUsuario( $usuario ): void
    {
        $this->usuario = $usuario;
    }

    /**
     * @return mixed
     */
    public function getRecibosDetallesAsesorRel()
    {
        return $this->recibosDetallesAsesorRel;
    }

    /**
     * @param mixed $recibosDetallesAsesorRel
     */
    public function setRecibosDetallesAsesorRel($recibosDetallesAsesorRel): void
    {
        $this->recibosDetallesAsesorRel = $recibosDetallesAsesorRel;
    }

    /**
     * @return mixed
     */
    public function getTurClienteAsesorRel()
    {
        return $this->turClienteAsesorRel;
    }

    /**
     * @param mixed $turClienteAsesorRel
     */
    public function setTurClienteAsesorRel($turClienteAsesorRel): void
    {
        $this->turClienteAsesorRel = $turClienteAsesorRel;
    }

    /**
     * @return mixed
     */
    public function getUsuariosAsesorRel()
    {
        return $this->usuariosAsesorRel;
    }

    /**
     * @param mixed $usuariosAsesorRel
     */
    public function setUsuariosAsesorRel($usuariosAsesorRel): void
    {
        $this->usuariosAsesorRel = $usuariosAsesorRel;
    }

    /**
     * @return mixed
     */
    public function getCotizacionesAsesorRel()
    {
        return $this->cotizacionesAsesorRel;
    }

    /**
     * @param mixed $cotizacionesAsesorRel
     */
    public function setCotizacionesAsesorRel($cotizacionesAsesorRel): void
    {
        $this->cotizacionesAsesorRel = $cotizacionesAsesorRel;
    }



}

