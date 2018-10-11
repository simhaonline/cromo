<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuEntidadRepository")
 */
class RhuEntidad
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_entidad_pk", type="integer")
     */
    private $codigoEntidadPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_entidad_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoEntidadTipoFk;

    /**
     * @ORM\Column(name="nit", type="string", length=10, nullable=true)
     *   @Assert\Length(
     *     max = 10,
     *     maxMessage="El campo no puede contener mas de 10 caracteres"
     * )
     */
    private $nit;

    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="codigo_interface", type="string", length=20, nullable=true)
     */
    private $codigoInterface;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="entidadSaludRel")
     */
    protected $contratosEntidadSaludRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="entidadPensionRel")
     */
    protected $contratosEntidadPensionRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="entidadCesantiaRel")
     */
    protected $contratosEntidadCesantiaRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="entidadCajaRel")
     */
    protected $contratosEntidadCajaRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadTipo", inversedBy="entidadesEntidadTipoRel")
     * @ORM\JoinColumn(name="codigo_entidad_tipo_fk", referencedColumnName="codigo_entidad_tipo_pk")
     */
    protected $entidadTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoEntidadPk()
    {
        return $this->codigoEntidadPk;
    }

    /**
     * @param mixed $codigoEntidadPk
     */
    public function setCodigoEntidadPk($codigoEntidadPk): void
    {
        $this->codigoEntidadPk = $codigoEntidadPk;
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
    public function getCodigoEntidadTipoFk()
    {
        return $this->codigoEntidadTipoFk;
    }

    /**
     * @param mixed $codigoEntidadTipoFk
     */
    public function setCodigoEntidadTipoFk($codigoEntidadTipoFk): void
    {
        $this->codigoEntidadTipoFk = $codigoEntidadTipoFk;
    }

    /**
     * @return mixed
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * @param mixed $nit
     */
    public function setNit($nit): void
    {
        $this->nit = $nit;
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
    public function getCodigoInterface()
    {
        return $this->codigoInterface;
    }

    /**
     * @param mixed $codigoInterface
     */
    public function setCodigoInterface($codigoInterface): void
    {
        $this->codigoInterface = $codigoInterface;
    }

    /**
     * @return mixed
     */
    public function getContratosEntidadSaludRel()
    {
        return $this->contratosEntidadSaludRel;
    }

    /**
     * @param mixed $contratosEntidadSaludRel
     */
    public function setContratosEntidadSaludRel($contratosEntidadSaludRel): void
    {
        $this->contratosEntidadSaludRel = $contratosEntidadSaludRel;
    }

    /**
     * @return mixed
     */
    public function getContratosEntidadPensionRel()
    {
        return $this->contratosEntidadPensionRel;
    }

    /**
     * @param mixed $contratosEntidadPensionRel
     */
    public function setContratosEntidadPensionRel($contratosEntidadPensionRel): void
    {
        $this->contratosEntidadPensionRel = $contratosEntidadPensionRel;
    }

    /**
     * @return mixed
     */
    public function getContratosEntidadCesantiaRel()
    {
        return $this->contratosEntidadCesantiaRel;
    }

    /**
     * @param mixed $contratosEntidadCesantiaRel
     */
    public function setContratosEntidadCesantiaRel($contratosEntidadCesantiaRel): void
    {
        $this->contratosEntidadCesantiaRel = $contratosEntidadCesantiaRel;
    }

    /**
     * @return mixed
     */
    public function getContratosEntidadCajaRel()
    {
        return $this->contratosEntidadCajaRel;
    }

    /**
     * @param mixed $contratosEntidadCajaRel
     */
    public function setContratosEntidadCajaRel($contratosEntidadCajaRel): void
    {
        $this->contratosEntidadCajaRel = $contratosEntidadCajaRel;
    }

    /**
     * @return mixed
     */
    public function getEntidadTipoRel()
    {
        return $this->entidadTipoRel;
    }

    /**
     * @param mixed $entidadTipoRel
     */
    public function setEntidadTipoRel($entidadTipoRel): void
    {
        $this->entidadTipoRel = $entidadTipoRel;
    }
}
