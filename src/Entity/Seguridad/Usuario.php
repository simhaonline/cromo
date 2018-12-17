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
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteOperacion", inversedBy="usuariosOperacionRel")
     * @ORM\JoinColumn(name="codigo_operacion_fk", referencedColumnName="codigo_operacion_pk")
     */
    private $operacionRel;

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

    /**
     * @param mixed $username
     */
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

    /**
     * @param mixed $password
     */
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

    /**
     * @param mixed $notificacionesPendientes
     */
    public function setNotificacionesPendientes($notificacionesPendientes)
    {
        $this->notificacionesPendientes = $notificacionesPendientes;
        return $this;
    }

    /**
     * @param mixed $rol
     */
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

    /**
     * @param mixed $foto
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;
        return $this;
    }



}

