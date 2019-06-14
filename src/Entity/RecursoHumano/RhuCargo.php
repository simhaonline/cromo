<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCargoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoCargoPk"},message="Ya existe el código del grupo")
 */
class RhuCargo
{
    public $infoLog = [
        "primaryKey" => "codigoCargoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cargo_pk", type="string", length=10)
     */        
    private $codigoCargoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_cargo_supervigilancia_fk", type="integer", nullable=true)
     */
    private $codigoCargoSupervigilanciaFk;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCargoSupervigilancia", inversedBy="cargosCargoSupervigilanciaRel")
     * @ORM\JoinColumn(name="codigo_cargo_supervigilancia_fk", referencedColumnName="codigo_cargo_supervigilancia_pk")
     */
    protected $cargoSupervigilanciaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", mappedBy="cargoRel")
     */
    protected $solicitudesCargoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccion", mappedBy="cargoRel")
     */
    protected $seleccionesCargoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAspirante", mappedBy="cargoRel")
     */
    protected $rhuAspirantesCargoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="cargoRel")
     */
    protected $empleadosCargoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="cargoRel")
     */
    protected $contratosCargoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuExamen", mappedBy="cargoRel")
     */
    protected $examenesCargoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuRequisitoCargo", mappedBy="cargoRel")
     */
    protected $requisitosCargosCargoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuRequisito", mappedBy="cargoRel")
     */
    protected $requisitosCargoRel;

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
    public function getCodigoCargoPk()
    {
        return $this->codigoCargoPk;
    }

    /**
     * @param mixed $codigoCargoPk
     */
    public function setCodigoCargoPk($codigoCargoPk): void
    {
        $this->codigoCargoPk = $codigoCargoPk;
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
    public function getCodigoCargoSupervigilanciaFk()
    {
        return $this->codigoCargoSupervigilanciaFk;
    }

    /**
     * @param mixed $codigoCargoSupervigilanciaFk
     */
    public function setCodigoCargoSupervigilanciaFk($codigoCargoSupervigilanciaFk): void
    {
        $this->codigoCargoSupervigilanciaFk = $codigoCargoSupervigilanciaFk;
    }

    /**
     * @return mixed
     */
    public function getSolicitudesCargoRel()
    {
        return $this->solicitudesCargoRel;
    }

    /**
     * @param mixed $solicitudesCargoRel
     */
    public function setSolicitudesCargoRel($solicitudesCargoRel): void
    {
        $this->solicitudesCargoRel = $solicitudesCargoRel;
    }

    /**
     * @return mixed
     */
    public function getSeleccionesCargoRel()
    {
        return $this->seleccionesCargoRel;
    }

    /**
     * @param mixed $seleccionesCargoRel
     */
    public function setSeleccionesCargoRel($seleccionesCargoRel): void
    {
        $this->seleccionesCargoRel = $seleccionesCargoRel;
    }

    /**
     * @return mixed
     */
    public function getRhuAspirantesCargoRel()
    {
        return $this->rhuAspirantesCargoRel;
    }

    /**
     * @param mixed $rhuAspirantesCargoRel
     */
    public function setRhuAspirantesCargoRel($rhuAspirantesCargoRel): void
    {
        $this->rhuAspirantesCargoRel = $rhuAspirantesCargoRel;
    }

    /**
     * @return mixed
     */
    public function getEmpleadosCargoRel()
    {
        return $this->empleadosCargoRel;
    }

    /**
     * @param mixed $empleadosCargoRel
     */
    public function setEmpleadosCargoRel($empleadosCargoRel): void
    {
        $this->empleadosCargoRel = $empleadosCargoRel;
    }

    /**
     * @return mixed
     */
    public function getContratosCargoRel()
    {
        return $this->contratosCargoRel;
    }

    /**
     * @param mixed $contratosCargoRel
     */
    public function setContratosCargoRel($contratosCargoRel): void
    {
        $this->contratosCargoRel = $contratosCargoRel;
    }

    /**
     * @return mixed
     */
    public function getExamenesCargoRel()
    {
        return $this->examenesCargoRel;
    }

    /**
     * @param mixed $examenesCargoRel
     */
    public function setExamenesCargoRel($examenesCargoRel): void
    {
        $this->examenesCargoRel = $examenesCargoRel;
    }

    /**
     * @return mixed
     */
    public function getRequisitosCargosCargoRel()
    {
        return $this->requisitosCargosCargoRel;
    }

    /**
     * @param mixed $requisitosCargosCargoRel
     */
    public function setRequisitosCargosCargoRel($requisitosCargosCargoRel): void
    {
        $this->requisitosCargosCargoRel = $requisitosCargosCargoRel;
    }

    /**
     * @return mixed
     */
    public function getCargoSupervigilanciaRel()
    {
        return $this->cargoSupervigilanciaRel;
    }

    /**
     * @param mixed $cargoSupervigilanciaRel
     */
    public function setCargoSupervigilanciaRel($cargoSupervigilanciaRel): void
    {
        $this->cargoSupervigilanciaRel = $cargoSupervigilanciaRel;
    }
}
