<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteCiudadRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteCiudad
{
    public $infoLog = [
        "primaryKey" => "codigoCiudadPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_ciudad_pk",type="string", length=20, nullable=false, unique=true)
     */
    private $codigoCiudadPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_division", type="string", length=10, nullable=true)
     */
    private $codigoDivision;

    /**
     * @ORM\Column(name="nombre_division", type="string", length=100, nullable=true)
     */
    private $nombreDivision;

    /**
     * @ORM\Column(name="codigo_zona", type="string", length=10, nullable=true)
     */
    private $codigoZona;

    /**
     * @ORM\Column(name="nombre_zona", type="string", length=100, nullable=true)
     */
    private $nombreZona;

    /**
     * @ORM\Column(name="codigo_municipio", type="string", length=10, nullable=true)
     */
    private $codigoMunicipio;

    /**
     * @ORM\Column(name="nombre_municipio", type="string", length=100, nullable=true)
     */
    private $nombreMunicipio;

    /**
     * @ORM\Column(name="codigo_departamento_fk", type="string", length=2, nullable=true)
     */
    private $codigoDepartamentoFk;

    /**
     * @ORM\Column(name="codigo_ruta_fk", type="string", length=20, nullable=true)
     */
    private $codigoRutaFk;

    /**
     * @ORM\Column(name="codigo_zona_fk", type="string", length=20, nullable=true)
     */
    private $codigoZonaFk;

    /**
     * @ORM\Column(name="orden_ruta", type="integer", nullable=true)
     */
    private $ordenRuta = 0;

    /**
     * @ORM\Column(name="codigo_interface", type="string", length=10, nullable=true)
     */
    private $codigoInterface;

    /**
     * @ORM\Column(name="reexpedicion", type="boolean", nullable=true, options={"default" : 0})
     */
    private $reexpedicion = false;

    /**
     * @ORM\Column(name="lunes", type="boolean", nullable=true, options={"default" : 0})
     */
    private $lunes = false;

    /**
     * @ORM\Column(name="martes", type="boolean", nullable=true, options={"default" : 0})
     */
    private $martes = false;

    /**
     * @ORM\Column(name="miercoles", type="boolean", nullable=true, options={"default" : 0})
     */
    private $miercoles = false;

    /**
     * @ORM\Column(name="jueves", type="boolean", nullable=true, options={"default" : 0})
     */
    private $jueves = false;

    /**
     * @ORM\Column(name="viernes", type="boolean", nullable=true, options={"default" : 0})
     */
    private $viernes = false;

    /**
     * @ORM\Column(name="sabado", type="boolean", nullable=true, options={"default" : 0})
     */
    private $sabado = false;

    /**
     * @ORM\Column(name="domingo", type="boolean", nullable=true, options={"default" : 0})
     */
    private $domingo = false;

    /**
     * @ORM\ManyToOne(targetEntity="TteRuta", inversedBy="ciudadesRutaRel")
     * @ORM\JoinColumn(name="codigo_ruta_fk", referencedColumnName="codigo_ruta_pk")
     */
    private $rutaRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteZona", inversedBy="ciudadesZonaRel")
     * @ORM\JoinColumn(name="codigo_zona_fk", referencedColumnName="codigo_zona_pk")
     */
    private $zonaRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteDepartamento", inversedBy="ciudadesRel")
     * @ORM\JoinColumn(name="codigo_departamento_fk", referencedColumnName="codigo_departamento_pk")
     */
    protected $departamentoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteFrecuencia", mappedBy="ciudadOrigenRel")
     */
    protected $frecuenciasCiudadOrigenRel;

    /**
     * @ORM\OneToMany(targetEntity="TteFrecuencia", mappedBy="ciudadDestinoRel")
     */
    protected $frecuenciasCiudadDestinoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="ciudadOrigenRel")
     */
    protected $guiasCiudadOrigenRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="ciudadDestinoRel")
     */
    protected $guiasCiudadDestinoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteDespacho", mappedBy="ciudadOrigenRel")
     */
    protected $despachosCiudadOrigenRel;

    /**
     * @ORM\OneToMany(targetEntity="TteDespachoRecogida", mappedBy="ciudadRel")
     */
    protected $despachosRecogidasCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="TteDespacho", mappedBy="ciudadDestinoRel")
     */
    protected $despachosCiudadDestinoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteRecogida", mappedBy="ciudadRel")
     */
    protected $recogidasCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="TteRecogidaProgramada", mappedBy="ciudadRel")
     */
    protected $recogidasProgramadasCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteOperacion", mappedBy="ciudadRel")
     */
    protected $operacionesCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TtePrecioDetalle", mappedBy="ciudadOrigenRel")
     */
    protected $preciosDetallesCiudadOrigenRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TtePrecioDetalle", mappedBy="ciudadDestinoRel")
     */
    protected $preciosDetallesCiudadDestinoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteConductor", mappedBy="ciudadRel")
     */
    protected $conductoresCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="TteDestinatario", mappedBy="ciudadRel")
     */
    protected $ciudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteCosto", mappedBy="ciudadOrigenRel")
     */
    protected $costosCiudadOrigenRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteCosto", mappedBy="ciudadDestinoRel")
     */
    protected $costosCiudadDestinoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuiaTemporal", mappedBy="ciudadOrigenRel")
     */
    protected $guiasTemporalesCiudadOrigenRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuiaTemporal", mappedBy="ciudadDestinoRel")
     */
    protected $guiasTemporalesCiudadDestinoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteMonitoreo", mappedBy="ciudadDestinoRel")
     */
    protected $monitoreosCiudadDestinoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteCondicionFlete", mappedBy="ciudadOrigenRel")
     */
    protected $condicionesFletesCiudadOrigenRel;

    /**
     * @ORM\OneToMany(targetEntity="TteCondicionFlete", mappedBy="ciudadDestinoRel")
     */
    protected $condicionesFletesCiudadDestinoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteCondicionManejo", mappedBy="ciudadOrigenRel")
     */
    protected $condicionesManejosCiudadOrigenRel;

    /**
     * @ORM\OneToMany(targetEntity="TteCondicionManejo", mappedBy="ciudadDestinoRel")
     */
    protected $condicionesManejosCiudadDestinoRel;

    /**
     * @return mixed
     */
    public function getCodigoCiudadPk()
    {
        return $this->codigoCiudadPk;
    }

    /**
     * @param mixed $codigoCiudadPk
     */
    public function setCodigoCiudadPk($codigoCiudadPk): void
    {
        $this->codigoCiudadPk = $codigoCiudadPk;
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
    public function getCodigoDivision()
    {
        return $this->codigoDivision;
    }

    /**
     * @param mixed $codigoDivision
     */
    public function setCodigoDivision($codigoDivision): void
    {
        $this->codigoDivision = $codigoDivision;
    }

    /**
     * @return mixed
     */
    public function getNombreDivision()
    {
        return $this->nombreDivision;
    }

    /**
     * @param mixed $nombreDivision
     */
    public function setNombreDivision($nombreDivision): void
    {
        $this->nombreDivision = $nombreDivision;
    }

    /**
     * @return mixed
     */
    public function getCodigoZona()
    {
        return $this->codigoZona;
    }

    /**
     * @param mixed $codigoZona
     */
    public function setCodigoZona($codigoZona): void
    {
        $this->codigoZona = $codigoZona;
    }

    /**
     * @return mixed
     */
    public function getNombreZona()
    {
        return $this->nombreZona;
    }

    /**
     * @param mixed $nombreZona
     */
    public function setNombreZona($nombreZona): void
    {
        $this->nombreZona = $nombreZona;
    }

    /**
     * @return mixed
     */
    public function getCodigoMunicipio()
    {
        return $this->codigoMunicipio;
    }

    /**
     * @param mixed $codigoMunicipio
     */
    public function setCodigoMunicipio($codigoMunicipio): void
    {
        $this->codigoMunicipio = $codigoMunicipio;
    }

    /**
     * @return mixed
     */
    public function getNombreMunicipio()
    {
        return $this->nombreMunicipio;
    }

    /**
     * @param mixed $nombreMunicipio
     */
    public function setNombreMunicipio($nombreMunicipio): void
    {
        $this->nombreMunicipio = $nombreMunicipio;
    }

    /**
     * @return mixed
     */
    public function getCodigoDepartamentoFk()
    {
        return $this->codigoDepartamentoFk;
    }

    /**
     * @param mixed $codigoDepartamentoFk
     */
    public function setCodigoDepartamentoFk($codigoDepartamentoFk): void
    {
        $this->codigoDepartamentoFk = $codigoDepartamentoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoRutaFk()
    {
        return $this->codigoRutaFk;
    }

    /**
     * @param mixed $codigoRutaFk
     */
    public function setCodigoRutaFk($codigoRutaFk): void
    {
        $this->codigoRutaFk = $codigoRutaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoZonaFk()
    {
        return $this->codigoZonaFk;
    }

    /**
     * @param mixed $codigoZonaFk
     */
    public function setCodigoZonaFk($codigoZonaFk): void
    {
        $this->codigoZonaFk = $codigoZonaFk;
    }

    /**
     * @return mixed
     */
    public function getOrdenRuta()
    {
        return $this->ordenRuta;
    }

    /**
     * @param mixed $ordenRuta
     */
    public function setOrdenRuta($ordenRuta): void
    {
        $this->ordenRuta = $ordenRuta;
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
    public function getReexpedicion()
    {
        return $this->reexpedicion;
    }

    /**
     * @param mixed $reexpedicion
     */
    public function setReexpedicion($reexpedicion): void
    {
        $this->reexpedicion = $reexpedicion;
    }

    /**
     * @return mixed
     */
    public function getRutaRel()
    {
        return $this->rutaRel;
    }

    /**
     * @param mixed $rutaRel
     */
    public function setRutaRel($rutaRel): void
    {
        $this->rutaRel = $rutaRel;
    }

    /**
     * @return mixed
     */
    public function getZonaRel()
    {
        return $this->zonaRel;
    }

    /**
     * @param mixed $zonaRel
     */
    public function setZonaRel($zonaRel): void
    {
        $this->zonaRel = $zonaRel;
    }

    /**
     * @return mixed
     */
    public function getDepartamentoRel()
    {
        return $this->departamentoRel;
    }

    /**
     * @param mixed $departamentoRel
     */
    public function setDepartamentoRel($departamentoRel): void
    {
        $this->departamentoRel = $departamentoRel;
    }

    /**
     * @return mixed
     */
    public function getGuiasCiudadOrigenRel()
    {
        return $this->guiasCiudadOrigenRel;
    }

    /**
     * @param mixed $guiasCiudadOrigenRel
     */
    public function setGuiasCiudadOrigenRel($guiasCiudadOrigenRel): void
    {
        $this->guiasCiudadOrigenRel = $guiasCiudadOrigenRel;
    }

    /**
     * @return mixed
     */
    public function getGuiasCiudadDestinoRel()
    {
        return $this->guiasCiudadDestinoRel;
    }

    /**
     * @param mixed $guiasCiudadDestinoRel
     */
    public function setGuiasCiudadDestinoRel($guiasCiudadDestinoRel): void
    {
        $this->guiasCiudadDestinoRel = $guiasCiudadDestinoRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosCiudadOrigenRel()
    {
        return $this->despachosCiudadOrigenRel;
    }

    /**
     * @param mixed $despachosCiudadOrigenRel
     */
    public function setDespachosCiudadOrigenRel($despachosCiudadOrigenRel): void
    {
        $this->despachosCiudadOrigenRel = $despachosCiudadOrigenRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosRecogidasCiudadRel()
    {
        return $this->despachosRecogidasCiudadRel;
    }

    /**
     * @param mixed $despachosRecogidasCiudadRel
     */
    public function setDespachosRecogidasCiudadRel($despachosRecogidasCiudadRel): void
    {
        $this->despachosRecogidasCiudadRel = $despachosRecogidasCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosCiudadDestinoRel()
    {
        return $this->despachosCiudadDestinoRel;
    }

    /**
     * @param mixed $despachosCiudadDestinoRel
     */
    public function setDespachosCiudadDestinoRel($despachosCiudadDestinoRel): void
    {
        $this->despachosCiudadDestinoRel = $despachosCiudadDestinoRel;
    }

    /**
     * @return mixed
     */
    public function getRecogidasCiudadRel()
    {
        return $this->recogidasCiudadRel;
    }

    /**
     * @param mixed $recogidasCiudadRel
     */
    public function setRecogidasCiudadRel($recogidasCiudadRel): void
    {
        $this->recogidasCiudadRel = $recogidasCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getRecogidasProgramadasCiudadRel()
    {
        return $this->recogidasProgramadasCiudadRel;
    }

    /**
     * @param mixed $recogidasProgramadasCiudadRel
     */
    public function setRecogidasProgramadasCiudadRel($recogidasProgramadasCiudadRel): void
    {
        $this->recogidasProgramadasCiudadRel = $recogidasProgramadasCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getOperacionesCiudadRel()
    {
        return $this->operacionesCiudadRel;
    }

    /**
     * @param mixed $operacionesCiudadRel
     */
    public function setOperacionesCiudadRel($operacionesCiudadRel): void
    {
        $this->operacionesCiudadRel = $operacionesCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getPreciosDetallesCiudadOrigenRel()
    {
        return $this->preciosDetallesCiudadOrigenRel;
    }

    /**
     * @param mixed $preciosDetallesCiudadOrigenRel
     */
    public function setPreciosDetallesCiudadOrigenRel($preciosDetallesCiudadOrigenRel): void
    {
        $this->preciosDetallesCiudadOrigenRel = $preciosDetallesCiudadOrigenRel;
    }

    /**
     * @return mixed
     */
    public function getPreciosDetallesCiudadDestinoRel()
    {
        return $this->preciosDetallesCiudadDestinoRel;
    }

    /**
     * @param mixed $preciosDetallesCiudadDestinoRel
     */
    public function setPreciosDetallesCiudadDestinoRel($preciosDetallesCiudadDestinoRel): void
    {
        $this->preciosDetallesCiudadDestinoRel = $preciosDetallesCiudadDestinoRel;
    }

    /**
     * @return mixed
     */
    public function getConductoresCiudadRel()
    {
        return $this->conductoresCiudadRel;
    }

    /**
     * @param mixed $conductoresCiudadRel
     */
    public function setConductoresCiudadRel($conductoresCiudadRel): void
    {
        $this->conductoresCiudadRel = $conductoresCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getPoseedoresCiudadRel()
    {
        return $this->poseedoresCiudadRel;
    }

    /**
     * @param mixed $poseedoresCiudadRel
     */
    public function setPoseedoresCiudadRel($poseedoresCiudadRel): void
    {
        $this->poseedoresCiudadRel = $poseedoresCiudadRel;
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
    public function getCostosCiudadOrigenRel()
    {
        return $this->costosCiudadOrigenRel;
    }

    /**
     * @param mixed $costosCiudadOrigenRel
     */
    public function setCostosCiudadOrigenRel($costosCiudadOrigenRel): void
    {
        $this->costosCiudadOrigenRel = $costosCiudadOrigenRel;
    }

    /**
     * @return mixed
     */
    public function getCostosCiudadDestinoRel()
    {
        return $this->costosCiudadDestinoRel;
    }

    /**
     * @param mixed $costosCiudadDestinoRel
     */
    public function setCostosCiudadDestinoRel($costosCiudadDestinoRel): void
    {
        $this->costosCiudadDestinoRel = $costosCiudadDestinoRel;
    }

    /**
     * @return mixed
     */
    public function getGuiasTemporalesCiudadOrigenRel()
    {
        return $this->guiasTemporalesCiudadOrigenRel;
    }

    /**
     * @param mixed $guiasTemporalesCiudadOrigenRel
     */
    public function setGuiasTemporalesCiudadOrigenRel($guiasTemporalesCiudadOrigenRel): void
    {
        $this->guiasTemporalesCiudadOrigenRel = $guiasTemporalesCiudadOrigenRel;
    }

    /**
     * @return mixed
     */
    public function getGuiasTemporalesCiudadDestinoRel()
    {
        return $this->guiasTemporalesCiudadDestinoRel;
    }

    /**
     * @param mixed $guiasTemporalesCiudadDestinoRel
     */
    public function setGuiasTemporalesCiudadDestinoRel($guiasTemporalesCiudadDestinoRel): void
    {
        $this->guiasTemporalesCiudadDestinoRel = $guiasTemporalesCiudadDestinoRel;
    }

    /**
     * @return mixed
     */
    public function getMonitoreosCiudadDestinoRel()
    {
        return $this->monitoreosCiudadDestinoRel;
    }

    /**
     * @param mixed $monitoreosCiudadDestinoRel
     */
    public function setMonitoreosCiudadDestinoRel($monitoreosCiudadDestinoRel): void
    {
        $this->monitoreosCiudadDestinoRel = $monitoreosCiudadDestinoRel;
    }

    /**
     * @return mixed
     */
    public function getCondicionesFletesCiudadOrigenRel()
    {
        return $this->condicionesFletesCiudadOrigenRel;
    }

    /**
     * @param mixed $condicionesFletesCiudadOrigenRel
     */
    public function setCondicionesFletesCiudadOrigenRel($condicionesFletesCiudadOrigenRel): void
    {
        $this->condicionesFletesCiudadOrigenRel = $condicionesFletesCiudadOrigenRel;
    }

    /**
     * @return mixed
     */
    public function getCondicionesFletesCiudadDestinoRel()
    {
        return $this->condicionesFletesCiudadDestinoRel;
    }

    /**
     * @param mixed $condicionesFletesCiudadDestinoRel
     */
    public function setCondicionesFletesCiudadDestinoRel($condicionesFletesCiudadDestinoRel): void
    {
        $this->condicionesFletesCiudadDestinoRel = $condicionesFletesCiudadDestinoRel;
    }

    /**
     * @return mixed
     */
    public function getCondicionesManejosCiudadOrigenRel()
    {
        return $this->condicionesManejosCiudadOrigenRel;
    }

    /**
     * @param mixed $condicionesManejosCiudadOrigenRel
     */
    public function setCondicionesManejosCiudadOrigenRel($condicionesManejosCiudadOrigenRel): void
    {
        $this->condicionesManejosCiudadOrigenRel = $condicionesManejosCiudadOrigenRel;
    }

    /**
     * @return mixed
     */
    public function getCondicionesManejosCiudadDestinoRel()
    {
        return $this->condicionesManejosCiudadDestinoRel;
    }

    /**
     * @param mixed $condicionesManejosCiudadDestinoRel
     */
    public function setCondicionesManejosCiudadDestinoRel($condicionesManejosCiudadDestinoRel): void
    {
        $this->condicionesManejosCiudadDestinoRel = $condicionesManejosCiudadDestinoRel;
    }

    /**
     * @return mixed
     */
    public function getLunes()
    {
        return $this->lunes;
    }

    /**
     * @param mixed $lunes
     */
    public function setLunes($lunes): void
    {
        $this->lunes = $lunes;
    }

    /**
     * @return mixed
     */
    public function getMartes()
    {
        return $this->martes;
    }

    /**
     * @param mixed $martes
     */
    public function setMartes($martes): void
    {
        $this->martes = $martes;
    }

    /**
     * @return mixed
     */
    public function getMiercoles()
    {
        return $this->miercoles;
    }

    /**
     * @param mixed $miercoles
     */
    public function setMiercoles($miercoles): void
    {
        $this->miercoles = $miercoles;
    }

    /**
     * @return mixed
     */
    public function getJueves()
    {
        return $this->jueves;
    }

    /**
     * @param mixed $jueves
     */
    public function setJueves($jueves): void
    {
        $this->jueves = $jueves;
    }

    /**
     * @return mixed
     */
    public function getViernes()
    {
        return $this->viernes;
    }

    /**
     * @param mixed $viernes
     */
    public function setViernes($viernes): void
    {
        $this->viernes = $viernes;
    }

    /**
     * @return mixed
     */
    public function getSabado()
    {
        return $this->sabado;
    }

    /**
     * @param mixed $sabado
     */
    public function setSabado($sabado): void
    {
        $this->sabado = $sabado;
    }

    /**
     * @return mixed
     */
    public function getDomingo()
    {
        return $this->domingo;
    }

    /**
     * @param mixed $domingo
     */
    public function setDomingo($domingo): void
    {
        $this->domingo = $domingo;
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
    public function getFrecuenciasCiudadOrigenRel()
    {
        return $this->frecuenciasCiudadOrigenRel;
    }

    /**
     * @param mixed $frecuenciasCiudadOrigenRel
     */
    public function setFrecuenciasCiudadOrigenRel($frecuenciasCiudadOrigenRel): void
    {
        $this->frecuenciasCiudadOrigenRel = $frecuenciasCiudadOrigenRel;
    }

    /**
     * @return mixed
     */
    public function getFrecuenciasCiudadDestinoRel()
    {
        return $this->frecuenciasCiudadDestinoRel;
    }

    /**
     * @param mixed $frecuenciasCiudadDestinoRel
     */
    public function setFrecuenciasCiudadDestinoRel($frecuenciasCiudadDestinoRel): void
    {
        $this->frecuenciasCiudadDestinoRel = $frecuenciasCiudadDestinoRel;
    }



}

