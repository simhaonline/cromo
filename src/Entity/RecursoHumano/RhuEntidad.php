<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuEntidadRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuEntidad
{
    public $infoLog = [
        "primaryKey" => "codigoEntidadPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_entidad_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEntidadPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */
    private $nombre;

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
     * @ORM\Column(name="eps", type="boolean", nullable=true,options={"default":false})
     */
    private $eps = false;

    /**
     * @ORM\Column(name="arl", type="boolean", nullable=true,options={"default":false})
     */
    private $arl = false;

    /**
     * @ORM\Column(name="ccf", type="boolean", nullable=true,options={"default":false})
     */
    private $ccf = false;

    /**
     * @ORM\Column(name="ces", type="boolean", nullable=true,options={"default":false})
     */
    private $ces = false;

    /**
     * @ORM\Column(name="pen", type="boolean", nullable=true,options={"default":false})
     */
    private $pen = false;

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
     * @ORM\OneToMany(targetEntity="RhuNovedad", mappedBy="entidadRel")
     */
    protected $novedadesEntidadRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuPago", mappedBy="entidadSaludRel")
     */
    protected $pagosEntidadSaludRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuPago", mappedBy="entidadPensionRel")
     */
    protected $pagosEntidadPensionRel;

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
    public function getEps()
    {
        return $this->eps;
    }

    /**
     * @param mixed $eps
     */
    public function setEps($eps): void
    {
        $this->eps = $eps;
    }

    /**
     * @return mixed
     */
    public function getArl()
    {
        return $this->arl;
    }

    /**
     * @param mixed $arl
     */
    public function setArl($arl): void
    {
        $this->arl = $arl;
    }

    /**
     * @return mixed
     */
    public function getCcf()
    {
        return $this->ccf;
    }

    /**
     * @param mixed $ccf
     */
    public function setCcf($ccf): void
    {
        $this->ccf = $ccf;
    }

    /**
     * @return mixed
     */
    public function getCes()
    {
        return $this->ces;
    }

    /**
     * @param mixed $ces
     */
    public function setCes($ces): void
    {
        $this->ces = $ces;
    }

    /**
     * @return mixed
     */
    public function getPen()
    {
        return $this->pen;
    }

    /**
     * @param mixed $pen
     */
    public function setPen($pen): void
    {
        $this->pen = $pen;
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
    public function getNovedadesEntidadRel()
    {
        return $this->novedadesEntidadRel;
    }

    /**
     * @param mixed $novedadesEntidadRel
     */
    public function setNovedadesEntidadRel($novedadesEntidadRel): void
    {
        $this->novedadesEntidadRel = $novedadesEntidadRel;
    }

    /**
     * @return mixed
     */
    public function getPagosEntidadSaludRel()
    {
        return $this->pagosEntidadSaludRel;
    }

    /**
     * @param mixed $pagosEntidadSaludRel
     */
    public function setPagosEntidadSaludRel($pagosEntidadSaludRel): void
    {
        $this->pagosEntidadSaludRel = $pagosEntidadSaludRel;
    }

    /**
     * @return mixed
     */
    public function getPagosEntidadPensionRel()
    {
        return $this->pagosEntidadPensionRel;
    }

    /**
     * @param mixed $pagosEntidadPensionRel
     */
    public function setPagosEntidadPensionRel($pagosEntidadPensionRel): void
    {
        $this->pagosEntidadPensionRel = $pagosEntidadPensionRel;
    }
}
