<?php


namespace App\Entity\Financiero;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Financiero\FinCentroCostoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoCentroCostoPk"},message="Ya existe el codigo centro de costo")
 */
class FinCentroCosto
{
    public $infoLog = [
        "primaryKey" => "codigoCentroCostoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_centro_costo_pk",type="string", length=20)
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     * @Assert\Length(
     *     min = "1",
     *     max = 20,
     *     minMessage="El campo no puede contener menos de {{ limit }} caracteres",
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     *
     */
    private $codigoCentroCostoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=false)
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     * @Assert\Length(
     *     min = "1",
     *     max = 120,
     *     minMessage="El campo no puede contener menos de {{ limit }} caracteres",
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     */
    private $nombre;

    /**
     * @ORM\Column(name="estado_inactivo", type="boolean", nullable=true, options={"default":false})
     */
    private $estadoInactivo = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Financiero\FinRegistro", mappedBy="centroCostoRel")
     */
    protected $registrosCentroCostoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Financiero\FinAsientoDetalle", mappedBy="centroCostoRel")
     */
    protected $asientosDetallesCentroCostoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurPuesto", mappedBy="centroCostoRel")
     */
    protected $turPuestosCentroCostoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurDistribucionEmpleado", mappedBy="centroCostoRel")
     */
    protected $distribucionesEmpleadosCentroCostoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCostoEmpleadoServicio", mappedBy="centroCostoRel")
     */
    protected $costosEmpleadosServiciosCentroCostoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuContrato", mappedBy="centroCostoRel")
     */
    protected $contratosCentroCostoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", mappedBy="centroCostoRel")
     */
    protected $solicitudesCentroCostoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tesoreria\TesMovimientoDetalle", mappedBy="centroCostoRel")
     */
    protected $movimientosDetallesCentroCostoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarMovimientoDetalle", mappedBy="centroCostoRel")
     */
    protected $carMovimientosDetallesCentroCostoRel;

    /**
     * @return mixed
     */
    public function getCodigoCentroCostoPk()
    {
        return $this->codigoCentroCostoPk;
    }

    /**
     * @param mixed $codigoCentroCostoPk
     */
    public function setCodigoCentroCostoPk($codigoCentroCostoPk): void
    {
        $this->codigoCentroCostoPk = $codigoCentroCostoPk;
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
    public function getEstadoInactivo()
    {
        return $this->estadoInactivo;
    }

    /**
     * @param mixed $estadoInactivo
     */
    public function setEstadoInactivo($estadoInactivo): void
    {
        $this->estadoInactivo = $estadoInactivo;
    }

    /**
     * @return mixed
     */
    public function getRegistrosCentroCostoRel()
    {
        return $this->registrosCentroCostoRel;
    }

    /**
     * @param mixed $registrosCentroCostoRel
     */
    public function setRegistrosCentroCostoRel($registrosCentroCostoRel): void
    {
        $this->registrosCentroCostoRel = $registrosCentroCostoRel;
    }

    /**
     * @return mixed
     */
    public function getAsientosDetallesCentroCostoRel()
    {
        return $this->asientosDetallesCentroCostoRel;
    }

    /**
     * @param mixed $asientosDetallesCentroCostoRel
     */
    public function setAsientosDetallesCentroCostoRel($asientosDetallesCentroCostoRel): void
    {
        $this->asientosDetallesCentroCostoRel = $asientosDetallesCentroCostoRel;
    }

    /**
     * @return mixed
     */
    public function getTurPuestosCentroCostoRel()
    {
        return $this->turPuestosCentroCostoRel;
    }

    /**
     * @param mixed $turPuestosCentroCostoRel
     */
    public function setTurPuestosCentroCostoRel($turPuestosCentroCostoRel): void
    {
        $this->turPuestosCentroCostoRel = $turPuestosCentroCostoRel;
    }

    /**
     * @return mixed
     */
    public function getDistribucionesEmpleadosCentroCostoRel()
    {
        return $this->distribucionesEmpleadosCentroCostoRel;
    }

    /**
     * @param mixed $distribucionesEmpleadosCentroCostoRel
     */
    public function setDistribucionesEmpleadosCentroCostoRel($distribucionesEmpleadosCentroCostoRel): void
    {
        $this->distribucionesEmpleadosCentroCostoRel = $distribucionesEmpleadosCentroCostoRel;
    }

    /**
     * @return mixed
     */
    public function getCostosEmpleadosServiciosCentroCostoRel()
    {
        return $this->costosEmpleadosServiciosCentroCostoRel;
    }

    /**
     * @param mixed $costosEmpleadosServiciosCentroCostoRel
     */
    public function setCostosEmpleadosServiciosCentroCostoRel($costosEmpleadosServiciosCentroCostoRel): void
    {
        $this->costosEmpleadosServiciosCentroCostoRel = $costosEmpleadosServiciosCentroCostoRel;
    }

    /**
     * @return mixed
     */
    public function getContratosCentroCostoRel()
    {
        return $this->contratosCentroCostoRel;
    }

    /**
     * @param mixed $contratosCentroCostoRel
     */
    public function setContratosCentroCostoRel($contratosCentroCostoRel): void
    {
        $this->contratosCentroCostoRel = $contratosCentroCostoRel;
    }

    /**
     * @return mixed
     */
    public function getMovimientosDetallesCentroCostoRel()
    {
        return $this->movimientosDetallesCentroCostoRel;
    }

    /**
     * @param mixed $movimientosDetallesCentroCostoRel
     */
    public function setMovimientosDetallesCentroCostoRel($movimientosDetallesCentroCostoRel): void
    {
        $this->movimientosDetallesCentroCostoRel = $movimientosDetallesCentroCostoRel;
    }

    /**
     * @return mixed
     */
    public function getCarMovimientosDetallesCentroCostoRel()
    {
        return $this->carMovimientosDetallesCentroCostoRel;
    }

    /**
     * @param mixed $carMovimientosDetallesCentroCostoRel
     */
    public function setCarMovimientosDetallesCentroCostoRel($carMovimientosDetallesCentroCostoRel): void
    {
        $this->carMovimientosDetallesCentroCostoRel = $carMovimientosDetallesCentroCostoRel;
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
    public function getSolicitudesCentroCostoRel()
    {
        return $this->solicitudesCentroCostoRel;
    }

    /**
     * @param mixed $solicitudesCentroCostoRel
     */
    public function setSolicitudesCentroCostoRel($solicitudesCentroCostoRel): void
    {
        $this->solicitudesCentroCostoRel = $solicitudesCentroCostoRel;
    }



}

