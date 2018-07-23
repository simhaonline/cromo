<?php

namespace App\Entity\Inventario;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_sucursal")
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvSucursalRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoSucursalPk"},message="Ya existe el código de la sucursal")
 */
class InvSucursal
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_sucursal_pk", type="string", length=10)
     */
    private $codigoSucursalPk;

    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */
    private $codigoTerceroFk;

    /**
     * @ORM\Column(name="direccion", type="string", length=150, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="contacto", type="string", length=150, nullable=true)
     */
    private $contacto;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\ManyToOne(targetEntity="InvTercero",inversedBy="sucursalesTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk",referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCiudad",inversedBy="invSucursalesCiuidadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk",referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;

    /**
     * @ORM\OneToMany(targetEntity="InvMovimiento", mappedBy="sucursalRel")
     */
    protected $movimientosSucursalRel;

    /**
     * @return mixed
     */
    public function getCodigoSucursalPk()
    {
        return $this->codigoSucursalPk;
    }

    /**
     * @param mixed $codigoSucursalPk
     */
    public function setCodigoSucursalPk($codigoSucursalPk): void
    {
        $this->codigoSucursalPk = $codigoSucursalPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoTerceroFk()
    {
        return $this->codigoTerceroFk;
    }

    /**
     * @param mixed $codigoTerceroFk
     */
    public function setCodigoTerceroFk($codigoTerceroFk): void
    {
        $this->codigoTerceroFk = $codigoTerceroFk;
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
    public function getTerceroRel()
    {
        return $this->terceroRel;
    }

    /**
     * @param mixed $terceroRel
     */
    public function setTerceroRel($terceroRel): void
    {
        $this->terceroRel = $terceroRel;
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
    public function getMovimientosSucursalRel()
    {
        return $this->movimientosSucursalRel;
    }

    /**
     * @param mixed $movimientosSucursalRel
     */
    public function setMovimientosSucursalRel($movimientosSucursalRel): void
    {
        $this->movimientosSucursalRel = $movimientosSucursalRel;
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
}
