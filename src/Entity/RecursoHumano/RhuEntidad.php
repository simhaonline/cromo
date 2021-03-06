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
     * @ORM\Column(name="nombre_corto", type="string", length=120, nullable=true)
     */
    private $nombreCorto;

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
     * @ORM\OneToMany(targetEntity="RhuAporteDetalle", mappedBy="entidadPensionRel")
     */
    protected $aportesDetallesEntidadPensionRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAporteDetalle", mappedBy="entidadSaludRel")
     */
    protected $aportesDetallesEntidadSaludRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAporteDetalle", mappedBy="entidadCajaRel")
     */
    protected $aportesDetallesEntidadCajaRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAporteDetalle", mappedBy="entidadRiesgosRel")
     */
    protected $aportesDetallesEntidadRiesgosRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAporteContrato", mappedBy="entidadPensionRel")
     */
    protected $aportesContratosEntidadPensionRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAporteContrato", mappedBy="entidadSaludRel")
     */
    protected $aportesContratosEntidadSaludRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAporteContrato", mappedBy="entidadCajaRel")
     */
    protected $aportesContratosEntidadCajaRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAporteContrato", mappedBy="entidadRiesgosRel")
     */
    protected $aportesContratosEntidadRiesgosRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuConfiguracion", mappedBy="entidadRiesgosRel")
     */
    protected $configuracionesEntidadRiesgosRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidad", mappedBy="entidadSaludRel")
     */
    protected $incapacidadesEntidadSaludRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuLicencia", mappedBy="entidadSaludRel")
     */
    protected $licenciasEntidadSaludRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuTrasladoPension", mappedBy="entidadPensionAnteriorRel")
     */
    protected $trasladosPensionesEntidadPensionAnteriorRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuTrasladoPension", mappedBy="entidadPensionNuevaRel")
     */
    protected $trasladosPensionesEntidadPensionNuevaRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuTrasladoSalud", mappedBy="entidadSaludAnteriorRel")
     */
    protected $trasladosSaludEntidadSaludAnteriorRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuTrasladoSalud", mappedBy="entidadSaludNuevaRel")
     */
    protected $trasladosSaludEntidadSaludNuevaRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAporteEntidad", mappedBy="entidadRel")
     */
    protected $aportesEntidadesEntidadRel;

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

    /**
     * @return mixed
     */
    public function getAportesDetallesEntidadPensionRel()
    {
        return $this->aportesDetallesEntidadPensionRel;
    }

    /**
     * @param mixed $aportesDetallesEntidadPensionRel
     */
    public function setAportesDetallesEntidadPensionRel($aportesDetallesEntidadPensionRel): void
    {
        $this->aportesDetallesEntidadPensionRel = $aportesDetallesEntidadPensionRel;
    }

    /**
     * @return mixed
     */
    public function getAportesDetallesEntidadSaludRel()
    {
        return $this->aportesDetallesEntidadSaludRel;
    }

    /**
     * @param mixed $aportesDetallesEntidadSaludRel
     */
    public function setAportesDetallesEntidadSaludRel($aportesDetallesEntidadSaludRel): void
    {
        $this->aportesDetallesEntidadSaludRel = $aportesDetallesEntidadSaludRel;
    }

    /**
     * @return mixed
     */
    public function getAportesDetallesEntidadCajaRel()
    {
        return $this->aportesDetallesEntidadCajaRel;
    }

    /**
     * @param mixed $aportesDetallesEntidadCajaRel
     */
    public function setAportesDetallesEntidadCajaRel($aportesDetallesEntidadCajaRel): void
    {
        $this->aportesDetallesEntidadCajaRel = $aportesDetallesEntidadCajaRel;
    }

    /**
     * @return mixed
     */
    public function getAportesDetallesEntidadRiesgosRel()
    {
        return $this->aportesDetallesEntidadRiesgosRel;
    }

    /**
     * @param mixed $aportesDetallesEntidadRiesgosRel
     */
    public function setAportesDetallesEntidadRiesgosRel($aportesDetallesEntidadRiesgosRel): void
    {
        $this->aportesDetallesEntidadRiesgosRel = $aportesDetallesEntidadRiesgosRel;
    }

    /**
     * @return mixed
     */
    public function getConfiguracionesEntidadRiesgosRel()
    {
        return $this->configuracionesEntidadRiesgosRel;
    }

    /**
     * @param mixed $configuracionesEntidadRiesgosRel
     */
    public function setConfiguracionesEntidadRiesgosRel($configuracionesEntidadRiesgosRel): void
    {
        $this->configuracionesEntidadRiesgosRel = $configuracionesEntidadRiesgosRel;
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
    public function getIncapacidadesEntidadSaludRel()
    {
        return $this->incapacidadesEntidadSaludRel;
    }

    /**
     * @param mixed $incapacidadesEntidadSaludRel
     */
    public function setIncapacidadesEntidadSaludRel($incapacidadesEntidadSaludRel): void
    {
        $this->incapacidadesEntidadSaludRel = $incapacidadesEntidadSaludRel;
    }

    /**
     * @return mixed
     */
    public function getLicenciasEntidadSaludRel()
    {
        return $this->licenciasEntidadSaludRel;
    }

    /**
     * @param mixed $licenciasEntidadSaludRel
     */
    public function setLicenciasEntidadSaludRel($licenciasEntidadSaludRel): void
    {
        $this->licenciasEntidadSaludRel = $licenciasEntidadSaludRel;
    }

    /**
     * @return mixed
     */
    public function getTrasladosPensionesEntidadPensionAnteriorRel()
    {
        return $this->trasladosPensionesEntidadPensionAnteriorRel;
    }

    /**
     * @param mixed $trasladosPensionesEntidadPensionAnteriorRel
     */
    public function setTrasladosPensionesEntidadPensionAnteriorRel($trasladosPensionesEntidadPensionAnteriorRel): void
    {
        $this->trasladosPensionesEntidadPensionAnteriorRel = $trasladosPensionesEntidadPensionAnteriorRel;
    }

    /**
     * @return mixed
     */
    public function getTrasladosPensionesEntidadPensionNuevaRel()
    {
        return $this->trasladosPensionesEntidadPensionNuevaRel;
    }

    /**
     * @param mixed $trasladosPensionesEntidadPensionNuevaRel
     */
    public function setTrasladosPensionesEntidadPensionNuevaRel($trasladosPensionesEntidadPensionNuevaRel): void
    {
        $this->trasladosPensionesEntidadPensionNuevaRel = $trasladosPensionesEntidadPensionNuevaRel;
    }

    /**
     * @return mixed
     */
    public function getTrasladosSaludEntidadSaludAnteriorRel()
    {
        return $this->trasladosSaludEntidadSaludAnteriorRel;
    }

    /**
     * @param mixed $trasladosSaludEntidadSaludAnteriorRel
     */
    public function setTrasladosSaludEntidadSaludAnteriorRel($trasladosSaludEntidadSaludAnteriorRel): void
    {
        $this->trasladosSaludEntidadSaludAnteriorRel = $trasladosSaludEntidadSaludAnteriorRel;
    }

    /**
     * @return mixed
     */
    public function getTrasladosSaludEntidadSaludNuevaRel()
    {
        return $this->trasladosSaludEntidadSaludNuevaRel;
    }

    /**
     * @param mixed $trasladosSaludEntidadSaludNuevaRel
     */
    public function setTrasladosSaludEntidadSaludNuevaRel($trasladosSaludEntidadSaludNuevaRel): void
    {
        $this->trasladosSaludEntidadSaludNuevaRel = $trasladosSaludEntidadSaludNuevaRel;
    }

    /**
     * @return mixed
     */
    public function getAportesEntidadesEntidadRel()
    {
        return $this->aportesEntidadesEntidadRel;
    }

    /**
     * @param mixed $aportesEntidadesEntidadRel
     */
    public function setAportesEntidadesEntidadRel($aportesEntidadesEntidadRel): void
    {
        $this->aportesEntidadesEntidadRel = $aportesEntidadesEntidadRel;
    }

    /**
     * @return mixed
     */
    public function getAportesContratosEntidadPensionRel()
    {
        return $this->aportesContratosEntidadPensionRel;
    }

    /**
     * @param mixed $aportesContratosEntidadPensionRel
     */
    public function setAportesContratosEntidadPensionRel($aportesContratosEntidadPensionRel): void
    {
        $this->aportesContratosEntidadPensionRel = $aportesContratosEntidadPensionRel;
    }

    /**
     * @return mixed
     */
    public function getAportesContratosEntidadSaludRel()
    {
        return $this->aportesContratosEntidadSaludRel;
    }

    /**
     * @param mixed $aportesContratosEntidadSaludRel
     */
    public function setAportesContratosEntidadSaludRel($aportesContratosEntidadSaludRel): void
    {
        $this->aportesContratosEntidadSaludRel = $aportesContratosEntidadSaludRel;
    }

    /**
     * @return mixed
     */
    public function getAportesContratosEntidadCajaRel()
    {
        return $this->aportesContratosEntidadCajaRel;
    }

    /**
     * @param mixed $aportesContratosEntidadCajaRel
     */
    public function setAportesContratosEntidadCajaRel($aportesContratosEntidadCajaRel): void
    {
        $this->aportesContratosEntidadCajaRel = $aportesContratosEntidadCajaRel;
    }

    /**
     * @return mixed
     */
    public function getAportesContratosEntidadRiesgosRel()
    {
        return $this->aportesContratosEntidadRiesgosRel;
    }

    /**
     * @param mixed $aportesContratosEntidadRiesgosRel
     */
    public function setAportesContratosEntidadRiesgosRel($aportesContratosEntidadRiesgosRel): void
    {
        $this->aportesContratosEntidadRiesgosRel = $aportesContratosEntidadRiesgosRel;
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



}
