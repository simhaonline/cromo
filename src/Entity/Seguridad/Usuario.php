<?php


namespace App\Entity\Seguridad;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Table(name="usuario")
 * @ORM\Entity(repositoryClass="App\Repository\Seguridad\UsuarioRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"username"},message="Ya existe el usuario")
 */
class Usuario implements UserInterface, \Serializable
{


    public $infoLog = [
        "primaryKey" => "username",
        "todos"     => true,
    ];

    /**
     * @ORM\Column(type="string", name="username", length=25)
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $username;

    /**
     * @ORM\Column(name="nombre_corto",type="string",length=255, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=80 ,nullable=true)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="cargo", type="string", length=255, nullable=true)
     */
    private $cargo;

    /**
     * @ORM\Column(name="telefono", type="string", length=30,nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="fecha_ultimo_ingreso", type="datetime",nullable=true)
     */
    private $fechaUltimoIngreso;

    /**
     * @ORM\Column(name="skype", type="string", length=100, nullable=true)
     */
    private $skype;

    /**
     * @ORM\Column(name="numero_conexiones", type="integer", options={"default" : 0},nullable=true)
     */
    private $numeroConexiones = 0;

    /**
     * @ORM\Column(name="extension", type="string", length=10,nullable=true)
     */
    private $extension;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(name="clave_escritorio",type="string", length=50,nullable=true)
     */
    private $claveEscritorio;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $email;

    /**
     * @ORM\Column(name="codigo_operacion_fk", type="string", length=20, nullable=true)
     */
    private $codigoOperacionFk;

    /**
     * @ORM\Column(name="is_active", type="boolean",options={"default":false})
     */
    private $isActive;

    /**
     * @ORM\Column(name="restringir_movimientos", type="boolean",options={"default":false})
     */
    private $restringirMovimientos;

    /**
     * @ORM\Column(name="rol", type="string", length=20, options={"default":"ROLE_USER"})
     */
    private $rol = 'ROLE_USER';

    /**
     * @ORM\Column(name="foto", type="blob", columnDefinition="longblob",  nullable=true)
     */
    private $foto;

    /**
     * @ORM\Column(name="notificaciones_pendientes", type="integer", options={"default":0})
     */
    private $notificacionesPendientes=0;

    /**
     * @ORM\Column(name="codigo_asesor_fk", type="integer", nullable=true, options={"default":null})
     */
    private $codigoAsesorFk;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteOperacion", inversedBy="usuariosOperacionRel")
     * @ORM\JoinColumn(name="codigo_operacion_fk", referencedColumnName="codigo_operacion_pk")
     */
    protected $operacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenAsesor", inversedBy="usuariosAsesorRel")
     * @ORM\JoinColumn(name="codigo_asesor_fk", referencedColumnName="codigo_asesor_pk")
     */
    protected $asesorRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\General\GenTarea", mappedBy="usuarioRecibeRel")
     */
    protected $genTareasUsuarioRecibeRel;

    public function __construct()
    {
        $this->isActive = true;

        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid('', true));
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles()
    {
        return array($this->rol);
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
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
    public function getNumeroConexiones()
    {
        return $this->numeroConexiones;
    }

    /**
     * @param mixed $numeroConexiones
     */
    public function setNumeroConexiones($numeroConexiones): void
    {
        $this->numeroConexiones = $numeroConexiones;
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
    public function getFechaUltimoIngreso()
    {
        return $this->fechaUltimoIngreso;
    }

    /**
     * @param mixed $fechaUltimoIngreso
     */
    public function setFechaUltimoIngreso($fechaUltimoIngreso): void
    {
        $this->fechaUltimoIngreso = $fechaUltimoIngreso;
    }


    /**
     * @return mixed
     */
    public function getFechaUltimaConexion()
    {
        return $this->fechaUltimaConexion;
    }

    /**
     * @return mixed
     */
    public function getGenTareasUsuarioRecibeRel()
    {
        return $this->genTareasUsuarioRecibeRel;
    }

    /**
     * @param mixed $genTareasUsuarioRecibeRel
     */
    public function setGenTareasUsuarioRecibeRel($genTareasUsuarioRecibeRel): void
    {
        $this->genTareasUsuarioRecibeRel = $genTareasUsuarioRecibeRel;
    }

    /**
     * @param mixed $fechaUltimaConexion
     */
    public function setFechaUltimaConexion($fechaUltimaConexion): void
    {
        $this->fechaUltimaConexion = $fechaUltimaConexion;
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
    public function getisActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
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
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * @param mixed $cargo
     */
    public function setCargo($cargo): void
    {
        $this->cargo = $cargo;
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
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param mixed $extension
     */
    public function setExtension($extension): void
    {
        $this->extension = $extension;
    }

    /**
     * @return mixed
     */
    public function getClaveEscritorio()
    {
        return $this->claveEscritorio;
    }

    /**
     * @param mixed $claveEscritorio
     */
    public function setClaveEscritorio($claveEscritorio): void
    {
        $this->claveEscritorio = $claveEscritorio;
    }

    /**
     * @return mixed
     */
    public function getNotificacionesPendientes()
    {
        return $this->notificacionesPendientes;
    }

    public function setNotificacionesPendientes($notificacionesPendientes)
    {
        $this->notificacionesPendientes = $notificacionesPendientes;
        return $this;
    }

    public function setRol($rol)
    {
        $this->rol = $rol;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFoto()
    {
        return $this->foto;
    }

    public function setFoto($foto)
    {
        $this->foto = $foto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * @param mixed $skype
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRestringirMovimientos()
    {
        return $this->restringirMovimientos;
    }

    /**
     * @param mixed $restringirMovimientos
     */
    public function setRestringirMovimientos($restringirMovimientos): void
    {
        $this->restringirMovimientos = $restringirMovimientos;
    }

    /**
     * @return mixed
     */
    public function getCodigoAsesorFk()
    {
        return $this->codigoAsesorFk;
    }

    /**
     * @param mixed $codigoAsesorFk
     */
    public function setCodigoAsesorFk($codigoAsesorFk): void
    {
        $this->codigoAsesorFk = $codigoAsesorFk;
    }

    /**
     * @return mixed
     */
    public function getAsesorRel()
    {
        return $this->asesorRel;
    }

    /**
     * @param mixed $asesorRel
     */
    public function setAsesorRel($asesorRel): void
    {
        $this->asesorRel = $asesorRel;
    }



}

