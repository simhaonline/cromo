<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenCiudadRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class GenCiudad
{
    public $infoLog = [
        "primaryKey" => "codigoCiudadPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_ciudad_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCiudadPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_departamento_fk", type="integer")
     */
    private $codigoDepartamentoFk;

    /**
     * @ORM\Column(name="codigo_dane", type="string", length=5)
     */
    private $codigoDane;

    /**
     * @ORM\Column(name="codigo_dane_mascara", type="string", length=5, nullable=true)
     */
    private $codigoDaneMascara;

    /**
     * @ORM\Column(name="codigo_dane_completo", type="string", length=10, nullable=true)
     */
    private $codigoDaneCompleto;

    /**
     * @ORM\Column(name="codigo_dane_division", type="string", length=15, nullable=true)
     */
    private $codigoDaneDivision;

    /**
     * @ORM\ManyToOne(targetEntity="GenDepartamento", inversedBy="ciudadesRel")
     * @ORM\JoinColumn(name="codigo_departamento_fk", referencedColumnName="codigo_departamento_pk")
     */
    protected $departamentoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarCliente", mappedBy="ciudadRel")
     */
    protected $carClientesCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteCliente", mappedBy="ciudadRel")
     */
    protected $tteClientesCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCliente", mappedBy="ciudadRel")
     */
    protected $turClientesCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Financiero\FinTercero", mappedBy="ciudadRel")
     */
    protected $finTercerosCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAspirante", mappedBy="ciudadRel")
     */
    protected $rhuAspirantesCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAspirante", mappedBy="ciudadExpedicionRel")
     */
    protected $rhuAspirantesCiudadExpedicionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAspirante", mappedBy="ciudadNacimientoRel")
     */
    protected $rhuAspirantesCiudadNacimientoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", mappedBy="ciudadRel")
     */
    protected $rhuSolicitudesCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="ciudadRel")
     */
    protected $rhuSeleccionesCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="ciudadExpedicionRel")
     */
    protected $rhuSeleccionesCiudadExpedicionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="ciudadNacimientoRel")
     */
    protected $rhuSeleccionesCiudadNacimientoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", mappedBy="ciudadRel")
     */
    protected $rhuEmpleadosCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", mappedBy="ciudadExpedicionRel")
     */
    protected $rhuEmpleadosCiudadExpedicionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAcademia", mappedBy="ciudadRel")
     */
    protected $rhuAcademiaCiuidadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuEstudio", mappedBy="ciudadRel")
     */
    protected $rhuEstudiosCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvSucursal", mappedBy="ciudadRel")
     */
    protected $invSucursalesCiuidadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvTercero", mappedBy="ciudadRel")
     */
    protected $invTercerosCiuidadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", mappedBy="ciudadNacimientoRel")
     */
    protected $rhuEmpleadosCiudadNacimientoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuContrato", mappedBy="ciudadContratoRel")
     */
    protected $rhuContratosCiudadContratoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuContrato", mappedBy="ciudadLaboraRel")
     */
    protected $rhuContratosCiudadLaboraRel;

    /**
     * @ORM\OneToMany(targetEntity="GenConfiguracion", mappedBy="ciudadRel")
     */
    protected $configuracionesCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Crm\CrmCliente", mappedBy="ciudadRel")
     */
    protected $crmClienteCiuidadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurPuesto", mappedBy="ciudadRel")
     */
    protected $turCiudadPuestoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuExamen", mappedBy="ciudadRel")
     */
    protected $examenesCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Crm\CrmContacto", mappedBy="ciudadRel")
     */
    protected $crmContactoCiuidadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TtePoseedor", mappedBy="ciudadRel")
     */
    protected $ttePoseedorCiuidadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurClienteIca", mappedBy="ciudadRel")
     */
    protected $turClientesIcaCiudadRel;

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
    public function getCodigoDane()
    {
        return $this->codigoDane;
    }

    /**
     * @param mixed $codigoDane
     */
    public function setCodigoDane($codigoDane): void
    {
        $this->codigoDane = $codigoDane;
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
    public function getCarClientesCiudadRel()
    {
        return $this->carClientesCiudadRel;
    }

    /**
     * @param mixed $carClientesCiudadRel
     */
    public function setCarClientesCiudadRel($carClientesCiudadRel): void
    {
        $this->carClientesCiudadRel = $carClientesCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getTteClientesCiudadRel()
    {
        return $this->tteClientesCiudadRel;
    }

    /**
     * @param mixed $tteClientesCiudadRel
     */
    public function setTteClientesCiudadRel($tteClientesCiudadRel): void
    {
        $this->tteClientesCiudadRel = $tteClientesCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getTurClientesCiudadRel()
    {
        return $this->turClientesCiudadRel;
    }

    /**
     * @param mixed $turClientesCiudadRel
     */
    public function setTurClientesCiudadRel($turClientesCiudadRel): void
    {
        $this->turClientesCiudadRel = $turClientesCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getFinTercerosCiudadRel()
    {
        return $this->finTercerosCiudadRel;
    }

    /**
     * @param mixed $finTercerosCiudadRel
     */
    public function setFinTercerosCiudadRel($finTercerosCiudadRel): void
    {
        $this->finTercerosCiudadRel = $finTercerosCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getRhuAspirantesCiudadRel()
    {
        return $this->rhuAspirantesCiudadRel;
    }

    /**
     * @param mixed $rhuAspirantesCiudadRel
     */
    public function setRhuAspirantesCiudadRel($rhuAspirantesCiudadRel): void
    {
        $this->rhuAspirantesCiudadRel = $rhuAspirantesCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getRhuAspirantesCiudadExpedicionRel()
    {
        return $this->rhuAspirantesCiudadExpedicionRel;
    }

    /**
     * @param mixed $rhuAspirantesCiudadExpedicionRel
     */
    public function setRhuAspirantesCiudadExpedicionRel($rhuAspirantesCiudadExpedicionRel): void
    {
        $this->rhuAspirantesCiudadExpedicionRel = $rhuAspirantesCiudadExpedicionRel;
    }

    /**
     * @return mixed
     */
    public function getRhuAspirantesCiudadNacimientoRel()
    {
        return $this->rhuAspirantesCiudadNacimientoRel;
    }

    /**
     * @param mixed $rhuAspirantesCiudadNacimientoRel
     */
    public function setRhuAspirantesCiudadNacimientoRel($rhuAspirantesCiudadNacimientoRel): void
    {
        $this->rhuAspirantesCiudadNacimientoRel = $rhuAspirantesCiudadNacimientoRel;
    }

    /**
     * @return mixed
     */
    public function getRhuSolicitudesCiudadRel()
    {
        return $this->rhuSolicitudesCiudadRel;
    }

    /**
     * @param mixed $rhuSolicitudesCiudadRel
     */
    public function setRhuSolicitudesCiudadRel($rhuSolicitudesCiudadRel): void
    {
        $this->rhuSolicitudesCiudadRel = $rhuSolicitudesCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getRhuSeleccionesCiudadRel()
    {
        return $this->rhuSeleccionesCiudadRel;
    }

    /**
     * @param mixed $rhuSeleccionesCiudadRel
     */
    public function setRhuSeleccionesCiudadRel($rhuSeleccionesCiudadRel): void
    {
        $this->rhuSeleccionesCiudadRel = $rhuSeleccionesCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getRhuSeleccionesCiudadExpedicionRel()
    {
        return $this->rhuSeleccionesCiudadExpedicionRel;
    }

    /**
     * @param mixed $rhuSeleccionesCiudadExpedicionRel
     */
    public function setRhuSeleccionesCiudadExpedicionRel($rhuSeleccionesCiudadExpedicionRel): void
    {
        $this->rhuSeleccionesCiudadExpedicionRel = $rhuSeleccionesCiudadExpedicionRel;
    }

    /**
     * @return mixed
     */
    public function getRhuSeleccionesCiudadNacimientoRel()
    {
        return $this->rhuSeleccionesCiudadNacimientoRel;
    }

    /**
     * @param mixed $rhuSeleccionesCiudadNacimientoRel
     */
    public function setRhuSeleccionesCiudadNacimientoRel($rhuSeleccionesCiudadNacimientoRel): void
    {
        $this->rhuSeleccionesCiudadNacimientoRel = $rhuSeleccionesCiudadNacimientoRel;
    }

    /**
     * @return mixed
     */
    public function getRhuEmpleadosCiudadRel()
    {
        return $this->rhuEmpleadosCiudadRel;
    }

    /**
     * @param mixed $rhuEmpleadosCiudadRel
     */
    public function setRhuEmpleadosCiudadRel($rhuEmpleadosCiudadRel): void
    {
        $this->rhuEmpleadosCiudadRel = $rhuEmpleadosCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getRhuEmpleadosCiudadExpedicionRel()
    {
        return $this->rhuEmpleadosCiudadExpedicionRel;
    }

    /**
     * @param mixed $rhuEmpleadosCiudadExpedicionRel
     */
    public function setRhuEmpleadosCiudadExpedicionRel($rhuEmpleadosCiudadExpedicionRel): void
    {
        $this->rhuEmpleadosCiudadExpedicionRel = $rhuEmpleadosCiudadExpedicionRel;
    }

    /**
     * @return mixed
     */
    public function getRhuAcademiaCiuidadRel()
    {
        return $this->rhuAcademiaCiuidadRel;
    }

    /**
     * @param mixed $rhuAcademiaCiuidadRel
     */
    public function setRhuAcademiaCiuidadRel($rhuAcademiaCiuidadRel): void
    {
        $this->rhuAcademiaCiuidadRel = $rhuAcademiaCiuidadRel;
    }

    /**
     * @return mixed
     */
    public function getRhuEstudiosCiudadRel()
    {
        return $this->rhuEstudiosCiudadRel;
    }

    /**
     * @param mixed $rhuEstudiosCiudadRel
     */
    public function setRhuEstudiosCiudadRel($rhuEstudiosCiudadRel): void
    {
        $this->rhuEstudiosCiudadRel = $rhuEstudiosCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getInvSucursalesCiuidadRel()
    {
        return $this->invSucursalesCiuidadRel;
    }

    /**
     * @param mixed $invSucursalesCiuidadRel
     */
    public function setInvSucursalesCiuidadRel($invSucursalesCiuidadRel): void
    {
        $this->invSucursalesCiuidadRel = $invSucursalesCiuidadRel;
    }

    /**
     * @return mixed
     */
    public function getInvTercerosCiuidadRel()
    {
        return $this->invTercerosCiuidadRel;
    }

    /**
     * @param mixed $invTercerosCiuidadRel
     */
    public function setInvTercerosCiuidadRel($invTercerosCiuidadRel): void
    {
        $this->invTercerosCiuidadRel = $invTercerosCiuidadRel;
    }

    /**
     * @return mixed
     */
    public function getRhuEmpleadosCiudadNacimientoRel()
    {
        return $this->rhuEmpleadosCiudadNacimientoRel;
    }

    /**
     * @param mixed $rhuEmpleadosCiudadNacimientoRel
     */
    public function setRhuEmpleadosCiudadNacimientoRel($rhuEmpleadosCiudadNacimientoRel): void
    {
        $this->rhuEmpleadosCiudadNacimientoRel = $rhuEmpleadosCiudadNacimientoRel;
    }

    /**
     * @return mixed
     */
    public function getRhuContratosCiudadContratoRel()
    {
        return $this->rhuContratosCiudadContratoRel;
    }

    /**
     * @param mixed $rhuContratosCiudadContratoRel
     */
    public function setRhuContratosCiudadContratoRel($rhuContratosCiudadContratoRel): void
    {
        $this->rhuContratosCiudadContratoRel = $rhuContratosCiudadContratoRel;
    }

    /**
     * @return mixed
     */
    public function getRhuContratosCiudadLaboraRel()
    {
        return $this->rhuContratosCiudadLaboraRel;
    }

    /**
     * @param mixed $rhuContratosCiudadLaboraRel
     */
    public function setRhuContratosCiudadLaboraRel($rhuContratosCiudadLaboraRel): void
    {
        $this->rhuContratosCiudadLaboraRel = $rhuContratosCiudadLaboraRel;
    }

    /**
     * @return mixed
     */
    public function getConfiguracionesCiudadRel()
    {
        return $this->configuracionesCiudadRel;
    }

    /**
     * @param mixed $configuracionesCiudadRel
     */
    public function setConfiguracionesCiudadRel($configuracionesCiudadRel): void
    {
        $this->configuracionesCiudadRel = $configuracionesCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getCrmClienteCiuidadRel()
    {
        return $this->crmClienteCiuidadRel;
    }

    /**
     * @param mixed $crmClienteCiuidadRel
     */
    public function setCrmClienteCiuidadRel($crmClienteCiuidadRel): void
    {
        $this->crmClienteCiuidadRel = $crmClienteCiuidadRel;
    }

    /**
     * @return mixed
     */
    public function getTurCiudadPuestoRel()
    {
        return $this->turCiudadPuestoRel;
    }

    /**
     * @param mixed $turCiudadPuestoRel
     */
    public function setTurCiudadPuestoRel($turCiudadPuestoRel): void
    {
        $this->turCiudadPuestoRel = $turCiudadPuestoRel;
    }

    /**
     * @return mixed
     */
    public function getExamenesCiudadRel()
    {
        return $this->examenesCiudadRel;
    }

    /**
     * @param mixed $examenesCiudadRel
     */
    public function setExamenesCiudadRel($examenesCiudadRel): void
    {
        $this->examenesCiudadRel = $examenesCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getCrmContactoCiuidadRel()
    {
        return $this->crmContactoCiuidadRel;
    }

    /**
     * @param mixed $crmContactoCiuidadRel
     */
    public function setCrmContactoCiuidadRel($crmContactoCiuidadRel): void
    {
        $this->crmContactoCiuidadRel = $crmContactoCiuidadRel;
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
    public function getTtePoseedorCiuidadRel()
    {
        return $this->ttePoseedorCiuidadRel;
    }

    /**
     * @param mixed $ttePoseedorCiuidadRel
     */
    public function setTtePoseedorCiuidadRel($ttePoseedorCiuidadRel): void
    {
        $this->ttePoseedorCiuidadRel = $ttePoseedorCiuidadRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoDaneMascara()
    {
        return $this->codigoDaneMascara;
    }

    /**
     * @param mixed $codigoDaneMascara
     */
    public function setCodigoDaneMascara($codigoDaneMascara): void
    {
        $this->codigoDaneMascara = $codigoDaneMascara;
    }

    /**
     * @return mixed
     */
    public function getCodigoDaneCompleto()
    {
        return $this->codigoDaneCompleto;
    }

    /**
     * @param mixed $codigoDaneCompleto
     */
    public function setCodigoDaneCompleto($codigoDaneCompleto): void
    {
        $this->codigoDaneCompleto = $codigoDaneCompleto;
    }

    /**
     * @return mixed
     */
    public function getCodigoDaneDivision()
    {
        return $this->codigoDaneDivision;
    }

    /**
     * @param mixed $codigoDaneDivision
     */
    public function setCodigoDaneDivision($codigoDaneDivision): void
    {
        $this->codigoDaneDivision = $codigoDaneDivision;
    }

    /**
     * @return mixed
     */
    public function getTurClientesIcaCiudadRel()
    {
        return $this->turClientesIcaCiudadRel;
    }

    /**
     * @param mixed $turClientesIcaCiudadRel
     */
    public function setTurClientesIcaCiudadRel($turClientesIcaCiudadRel): void
    {
        $this->turClientesIcaCiudadRel = $turClientesIcaCiudadRel;
    }

}

