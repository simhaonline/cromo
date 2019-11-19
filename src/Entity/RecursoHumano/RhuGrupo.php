<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuGrupoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoGrupoPk"},message="Ya existe el código del grupo")
 */
class RhuGrupo
{
    public $infoLog = [
        "primaryKey" => "codigoGrupoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_grupo_pk", type="string", length=10)
     */        
    private $codigoGrupoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_distribucion_fk", type="string", length=10, nullable=true)
     */
    private $codigoDistribucionFk;

    /**
     * @ORM\Column(name="cargar_contrato", type="boolean",options={"default" : false}, nullable=true)
     */
    private $cargarContrato = false;

    /**
     * @ORM\Column(name="cargar_soporte", type="boolean",options={"default" : false}, nullable=true)
     */
    private $cargarSoporte = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuDistribucion", inversedBy="gruposDistribucionRel")
     * @ORM\JoinColumn(name="codigo_distribucion_fk",referencedColumnName="codigo_distribucion_pk")
     */
    protected $distribucionRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="grupoRel")
     */
    protected $contratosGrupoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuProgramacion", mappedBy="grupoRel")
     */
    protected $programacionesGrupoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuNovedad", mappedBy="grupoRel")
     */
    protected $novedadesGrupoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuCredito", mappedBy="grupoRel")
     */
    protected $creditosGrupoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuVacacion", mappedBy="grupoRel")
     */
    protected $vacacionesGrupoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuSolicitud", mappedBy="grupoRel")
     */
    protected $solicitudesGrupoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurSoporte", mappedBy="grupoRel")
     */
    protected $soportesGrupoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuPago", mappedBy="grupoRel")
     */
    protected $pagosGrupoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidad", mappedBy="grupoRel")
     */
    protected $incapacidadesGrupoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuLicencia", mappedBy="grupoRel")
     */
    protected $licenciasGrupoRel;

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
    public function getCodigoGrupoPk()
    {
        return $this->codigoGrupoPk;
    }

    /**
     * @param mixed $codigoGrupoPk
     */
    public function setCodigoGrupoPk($codigoGrupoPk): void
    {
        $this->codigoGrupoPk = $codigoGrupoPk;
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
    public function getContratosGrupoRel()
    {
        return $this->contratosGrupoRel;
    }

    /**
     * @param mixed $contratosGrupoRel
     */
    public function setContratosGrupoRel($contratosGrupoRel): void
    {
        $this->contratosGrupoRel = $contratosGrupoRel;
    }

    /**
     * @return mixed
     */
    public function getProgramacionesGrupoRel()
    {
        return $this->programacionesGrupoRel;
    }

    /**
     * @param mixed $programacionesGrupoRel
     */
    public function setProgramacionesGrupoRel($programacionesGrupoRel): void
    {
        $this->programacionesGrupoRel = $programacionesGrupoRel;
    }

    /**
     * @return mixed
     */
    public function getNovedadesGrupoRel()
    {
        return $this->novedadesGrupoRel;
    }

    /**
     * @param mixed $novedadesGrupoRel
     */
    public function setNovedadesGrupoRel($novedadesGrupoRel): void
    {
        $this->novedadesGrupoRel = $novedadesGrupoRel;
    }

    /**
     * @return mixed
     */
    public function getCreditosGrupoRel()
    {
        return $this->creditosGrupoRel;
    }

    /**
     * @param mixed $creditosGrupoRel
     */
    public function setCreditosGrupoRel($creditosGrupoRel): void
    {
        $this->creditosGrupoRel = $creditosGrupoRel;
    }

    /**
     * @return mixed
     */
    public function getVacacionesGrupoRel()
    {
        return $this->vacacionesGrupoRel;
    }

    /**
     * @param mixed $vacacionesGrupoRel
     */
    public function setVacacionesGrupoRel($vacacionesGrupoRel): void
    {
        $this->vacacionesGrupoRel = $vacacionesGrupoRel;
    }

    /**
     * @return mixed
     */
    public function getSolicitudesGrupoRel()
    {
        return $this->solicitudesGrupoRel;
    }

    /**
     * @param mixed $solicitudesGrupoRel
     */
    public function setSolicitudesGrupoRel($solicitudesGrupoRel): void
    {
        $this->solicitudesGrupoRel = $solicitudesGrupoRel;
    }

    /**
     * @return mixed
     */
    public function getSoportesGrupoRel()
    {
        return $this->soportesGrupoRel;
    }

    /**
     * @param mixed $soportesGrupoRel
     */
    public function setSoportesGrupoRel($soportesGrupoRel): void
    {
        $this->soportesGrupoRel = $soportesGrupoRel;
    }

    /**
     * @return mixed
     */
    public function getPagosGrupoRel()
    {
        return $this->pagosGrupoRel;
    }

    /**
     * @param mixed $pagosGrupoRel
     */
    public function setPagosGrupoRel($pagosGrupoRel): void
    {
        $this->pagosGrupoRel = $pagosGrupoRel;
    }

    /**
     * @return mixed
     */
    public function getIncapacidadesGrupoRel()
    {
        return $this->incapacidadesGrupoRel;
    }

    /**
     * @param mixed $incapacidadesGrupoRel
     */
    public function setIncapacidadesGrupoRel($incapacidadesGrupoRel): void
    {
        $this->incapacidadesGrupoRel = $incapacidadesGrupoRel;
    }

    /**
     * @return mixed
     */
    public function getCargarContrato()
    {
        return $this->cargarContrato;
    }

    /**
     * @param mixed $cargarContrato
     */
    public function setCargarContrato($cargarContrato): void
    {
        $this->cargarContrato = $cargarContrato;
    }

    /**
     * @return mixed
     */
    public function getCargarSoporte()
    {
        return $this->cargarSoporte;
    }

    /**
     * @param mixed $cargarSoporte
     */
    public function setCargarSoporte($cargarSoporte): void
    {
        $this->cargarSoporte = $cargarSoporte;
    }

    /**
     * @return mixed
     */
    public function getLicenciasGrupoRel()
    {
        return $this->licenciasGrupoRel;
    }

    /**
     * @param mixed $licenciasGrupoRel
     */
    public function setLicenciasGrupoRel($licenciasGrupoRel): void
    {
        $this->licenciasGrupoRel = $licenciasGrupoRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoDistribucionFk()
    {
        return $this->codigoDistribucionFk;
    }

    /**
     * @param mixed $codigoDistribucionFk
     */
    public function setCodigoDistribucionFk($codigoDistribucionFk): void
    {
        $this->codigoDistribucionFk = $codigoDistribucionFk;
    }

    /**
     * @return mixed
     */
    public function getDistribucionRel()
    {
        return $this->distribucionRel;
    }

    /**
     * @param mixed $distribucionRel
     */
    public function setDistribucionRel($distribucionRel): void
    {
        $this->distribucionRel = $distribucionRel;
    }



}
