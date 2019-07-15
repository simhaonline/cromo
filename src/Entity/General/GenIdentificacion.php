<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenIdentificacionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class GenIdentificacion
{
    public $infoLog = [
        "primaryKey" => "codigoIdentificacionPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=3, nullable=false, unique=true)
     */
    private $codigoIdentificacionPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=30, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_interface", type="string", length=10, nullable=true)
     */
    private $codigoInterface;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TtePoseedor", mappedBy="identificacionRel")
     */
    protected $ttePoseedoresIdentificacionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteConductor", mappedBy="identificacionRel")
     */
    protected $tteConductoresIdentificacionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteCliente", mappedBy="identificacionRel")
     */
    protected $tteClientesIdentificacionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCliente", mappedBy="identificacionRel")
     */
    protected $turClientesIdentificacionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteDestinatario", mappedBy="identificacionRel")
     */
    protected $tteDestinatariosIdentificacionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarCliente", mappedBy="identificacionRel")
     */
    protected $carClientesIdentificacionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAspirante", mappedBy="identificacionRel")
     */
    protected $rhuAspirantesIdentificacionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="identificacionRel")
     */
    protected $rhuSeleccionesIdentificacionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", mappedBy="identificacionRel")
     */
    protected $rhuEmpleadosIdentificacionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvTercero", mappedBy="identificacionRel")
     */
    protected $invTercerosIdentificacionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Financiero\FinTercero", mappedBy="identificacionRel")
     */
    protected $finTercerosIdentificacionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compra\ComProveedor", mappedBy="identificacionRel")
     */
    protected $comProveedoresIdentificacionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Crm\CrmCliente", mappedBy="identificacionRel")
     */
    protected $crmClienteIdentificacionRel;

    /**
     * @ORM\OneToMany(targetEntity="GenConfiguracion", mappedBy="identificacionRel")
     */
    protected $configuracionesIdentificacionRel;

    /**
     * @return mixed
     */
    public function getComProveedoresIdentificacionRel()
    {
        return $this->comProveedoresIdentificacionRel;
    }

    /**
     * @param mixed $comProveedoresIdentificacionRel
     */
    public function setComProveedoresIdentificacionRel($comProveedoresIdentificacionRel): void
    {
        $this->comProveedoresIdentificacionRel = $comProveedoresIdentificacionRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoIdentificacionPk()
    {
        return $this->codigoIdentificacionPk;
    }

    /**
     * @param mixed $codigoIdentificacionPk
     */
    public function setCodigoIdentificacionPk($codigoIdentificacionPk): void
    {
        $this->codigoIdentificacionPk = $codigoIdentificacionPk;
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
    public function getTtePoseedoresIdentificacionRel()
    {
        return $this->ttePoseedoresIdentificacionRel;
    }

    /**
     * @param mixed $ttePoseedoresIdentificacionRel
     */
    public function setTtePoseedoresIdentificacionRel($ttePoseedoresIdentificacionRel): void
    {
        $this->ttePoseedoresIdentificacionRel = $ttePoseedoresIdentificacionRel;
    }

    /**
     * @return mixed
     */
    public function getTteConductoresIdentificacionRel()
    {
        return $this->tteConductoresIdentificacionRel;
    }

    /**
     * @param mixed $tteConductoresIdentificacionRel
     */
    public function setTteConductoresIdentificacionRel($tteConductoresIdentificacionRel): void
    {
        $this->tteConductoresIdentificacionRel = $tteConductoresIdentificacionRel;
    }

    /**
     * @return mixed
     */
    public function getTteClientesIdentificacionRel()
    {
        return $this->tteClientesIdentificacionRel;
    }

    /**
     * @param mixed $tteClientesIdentificacionRel
     */
    public function setTteClientesIdentificacionRel($tteClientesIdentificacionRel): void
    {
        $this->tteClientesIdentificacionRel = $tteClientesIdentificacionRel;
    }

    /**
     * @return mixed
     */
    public function getTteDestinatariosIdentificacionRel()
    {
        return $this->tteDestinatariosIdentificacionRel;
    }

    /**
     * @param mixed $tteDestinatariosIdentificacionRel
     */
    public function setTteDestinatariosIdentificacionRel($tteDestinatariosIdentificacionRel): void
    {
        $this->tteDestinatariosIdentificacionRel = $tteDestinatariosIdentificacionRel;
    }

    /**
     * @return mixed
     */
    public function getCarClientesIdentificacionRel()
    {
        return $this->carClientesIdentificacionRel;
    }

    /**
     * @param mixed $carClientesIdentificacionRel
     */
    public function setCarClientesIdentificacionRel($carClientesIdentificacionRel): void
    {
        $this->carClientesIdentificacionRel = $carClientesIdentificacionRel;
    }

    /**
     * @return mixed
     */
    public function getRhuAspirantesIdentificacionRel()
    {
        return $this->rhuAspirantesIdentificacionRel;
    }

    /**
     * @param mixed $rhuAspirantesIdentificacionRel
     */
    public function setRhuAspirantesIdentificacionRel($rhuAspirantesIdentificacionRel): void
    {
        $this->rhuAspirantesIdentificacionRel = $rhuAspirantesIdentificacionRel;
    }

    /**
     * @return mixed
     */
    public function getRhuSeleccionesIdentificacionRel()
    {
        return $this->rhuSeleccionesIdentificacionRel;
    }

    /**
     * @param mixed $rhuSeleccionesIdentificacionRel
     */
    public function setRhuSeleccionesIdentificacionRel($rhuSeleccionesIdentificacionRel): void
    {
        $this->rhuSeleccionesIdentificacionRel = $rhuSeleccionesIdentificacionRel;
    }

    /**
     * @return mixed
     */
    public function getRhuEmpleadosIdentificacionRel()
    {
        return $this->rhuEmpleadosIdentificacionRel;
    }

    /**
     * @param mixed $rhuEmpleadosIdentificacionRel
     */
    public function setRhuEmpleadosIdentificacionRel($rhuEmpleadosIdentificacionRel): void
    {
        $this->rhuEmpleadosIdentificacionRel = $rhuEmpleadosIdentificacionRel;
    }

    /**
     * @return mixed
     */
    public function getInvTercerosIdentificacionRel()
    {
        return $this->invTercerosIdentificacionRel;
    }

    /**
     * @param mixed $invTercerosIdentificacionRel
     */
    public function setInvTercerosIdentificacionRel($invTercerosIdentificacionRel): void
    {
        $this->invTercerosIdentificacionRel = $invTercerosIdentificacionRel;
    }

    /**
     * @return mixed
     */
    public function getFinTercerosIdentificacionRel()
    {
        return $this->finTercerosIdentificacionRel;
    }

    /**
     * @param mixed $finTercerosIdentificacionRel
     */
    public function setFinTercerosIdentificacionRel($finTercerosIdentificacionRel): void
    {
        $this->finTercerosIdentificacionRel = $finTercerosIdentificacionRel;
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
    public function getTurClientesIdentificacionRel()
    {
        return $this->turClientesIdentificacionRel;
    }

    /**
     * @param mixed $turClientesIdentificacionRel
     */
    public function setTurClientesIdentificacionRel($turClientesIdentificacionRel): void
    {
        $this->turClientesIdentificacionRel = $turClientesIdentificacionRel;
    }

    /**
     * @return mixed
     */
    public function getCrmClienteIdentificacionRel()
    {
        return $this->crmClienteIdentificacionRel;
    }

    /**
     * @param mixed $crmClienteIdentificacionRel
     */
    public function setCrmClienteIdentificacionRel($crmClienteIdentificacionRel): void
    {
        $this->crmClienteIdentificacionRel = $crmClienteIdentificacionRel;
    }

    /**
     * @return mixed
     */
    public function getConfiguracionesIdentificacionRel()
    {
        return $this->configuracionesIdentificacionRel;
    }

    /**
     * @param mixed $configuracionesIdentificacionRel
     */
    public function setConfiguracionesIdentificacionRel($configuracionesIdentificacionRel): void
    {
        $this->configuracionesIdentificacionRel = $configuracionesIdentificacionRel;
    }



}

