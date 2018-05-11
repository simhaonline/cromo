<?php


namespace App\Entity\Contabilidad;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Contabilidad\CtbTerceroRepository")
 * @DoctrineAssert\UniqueEntity(fields={"numeroIdentificacion"},message="Ya existe el número de identificación")
 */
class CtbTercero
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoTerceroPk;

    /**
     * @ORM\Column(name="codigo_identificacion_tipo_fk", type="integer")
     */
    private $codigoIdentificacionTipoFk;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=false)
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     * @Assert\Length(
     *     min = "1",
     *     max = 20,
     *     minMessage="El campo no puede contener menos de {{ limit }} caracteres",
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="digito_verificacion", type="string", length=1, nullable=true, unique=true)
     * @Assert\Length(
     *     max = 1,
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     */
    private $digitoVerificacion;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=300)
     * @Assert\Length(
     *     max = 300,
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="nombre1", type="string", length=50, nullable=true)
     * @Assert\Length(
     *     max = 50,
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     */
    private $nombre1;

    /**
     * @ORM\Column(name="nombre2", type="string", length=50, nullable=true)
     * @Assert\Length(
     *     max = 50,
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     */
    private $nombre2;

    /**
     * @ORM\Column(name="apellido1", type="string", length=50, nullable=true)
     * @Assert\Length(
     *     max = 50,
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     */
    private $apellido1;

    /**
     * @ORM\Column(name="apellido2", type="string", length=50, nullable=true)
     * @Assert\Length(
     *     max = 50,
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     */
    private $apellido2;

    /**
     * @ORM\Column(name="razon_social", type="string", length=300, nullable=true)
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     * @Assert\Length(
     *     min = "1",
     *     max = 300,
     *     minMessage = "El campo no puede contener mas de {{ limit }} caracteres",
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     */
    private $razonSocial;

    /**
     * @ORM\Column(name="direccion", type="string", length=120, nullable=true)
     * @Assert\Length(
     *     max = 120,
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     */
    private $direccion;

    /**
     * @ORM\Column(name="telefono", type="string", length=20, nullable=true)
     * @Assert\Length(
     *     max = 20,
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     */
    private $telefono;

    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true)
     * @Assert\Length(
     *     max = 20,
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     */
    private $celular;

    /**
     * @ORM\Column(name="fax", type="string", length=20, nullable=true)
     * @Assert\Length(
     *     max = 20,
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     */
    private $fax;

    /**
     * @ORM\Column(name="email", type="string", length=80, nullable=true)
     * @Assert\Length(
     *     max = 80,
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCiudad", inversedBy="ctbTercerosCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     * @Assert\NotNull(message="Este campo no puede estar vacio")
     */
    protected $ciudadRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenIdentificacionTipo", inversedBy="ctbTercerosIdentificacionTipoRel")
     * @ORM\JoinColumn(name="codigo_identificacion_tipo_fk", referencedColumnName="codigo_identificacion_tipo_pk")
     * @Assert\NotNull(message="Este campo no puede estar vacio")
     */
    protected $identificacionTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contabilidad\CtbRegistro", mappedBy="terceroRel")
     */
    protected $ctbRegistrosTerceroRel;

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
    public function getCodigoIdentificacionTipoFk()
    {
        return $this->codigoIdentificacionTipoFk;
    }

    /**
     * @param mixed $codigoIdentificacionTipoFk
     */
    public function setCodigoIdentificacionTipoFk($codigoIdentificacionTipoFk): void
    {
        $this->codigoIdentificacionTipoFk = $codigoIdentificacionTipoFk;
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
    public function getRazonSocial()
    {
        return $this->razonSocial;
    }

    /**
     * @param mixed $razonSocial
     */
    public function setRazonSocial($razonSocial): void
    {
        $this->razonSocial = $razonSocial;
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
    public function getIdentificacionTipoRel()
    {
        return $this->identificacionTipoRel;
    }

    /**
     * @param mixed $identificacionTipoRel
     */
    public function setIdentificacionTipoRel($identificacionTipoRel): void
    {
        $this->identificacionTipoRel = $identificacionTipoRel;
    }

    /**
     * @return mixed
     */
    public function getCtbRegistrosTerceroRel()
    {
        return $this->ctbRegistrosTerceroRel;
    }

    /**
     * @param mixed $ctbRegistrosTerceroRel
     */
    public function setCtbRegistrosTerceroRel($ctbRegistrosTerceroRel): void
    {
        $this->ctbRegistrosTerceroRel = $ctbRegistrosTerceroRel;
    }

}

