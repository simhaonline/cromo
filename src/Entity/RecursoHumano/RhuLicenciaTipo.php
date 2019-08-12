<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuLicenciaTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuLicenciaTipo
{
    public $infoLog = [
        "primaryKey" => "codigoLicenciaTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_licencia_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLicenciaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=false)
     */
    private $nombre;

    /**
     * @ORM\Column(name="afecta_salud", type="boolean")
     */
    private $afectaSalud = false;
    /**
     * @ORM\Column(name="ausentismo", type="boolean")
     */
    private $ausentismo = false;
    /**
     * @ORM\Column(name="maternidad", type="boolean")
     */
    private $maternidad = false;

    /**
     * @ORM\Column(name="paternidad", type="boolean")
     */
    private $paternidad = false;
    /**
     * @ORM\Column(name="remunerada", type="boolean", nullable=true)
     */
    private $remunerada = false;

    /**
     * @ORM\Column(name="suspension_contrato_trabajo", type="boolean", nullable=false)
     */
    private $suspensionContratoTrabajo = false;

    /**
     * @ORM\Column(name="codigo_concepto_fk", type="string",  length=80, nullable=true)
     */
    private $codigoConceptoFk;

    /**
     * @ORM\Column(name="tipo_novedad_turno", type="string", length=5, nullable=true)
     */
    private $tipoNovedadTurno;

    /**
     * @ORM\OneToMany(targetEntity="RhuLicencia", mappedBy="licenciaTipoRel")
     */
    protected $licenciasLicenciaTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuConcepto", inversedBy="conceptosLicenciasTiposRel")
     * @ORM\JoinColumn(name="codigo_concepto_fk", referencedColumnName="codigo_concepto_pk")
     */
    protected $conceptoRel;

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
    public function getCodigoLicenciaTipoPk()
    {
        return $this->codigoLicenciaTipoPk;
    }

    /**
     * @param mixed $codigoLicenciaTipoPk
     */
    public function setCodigoLicenciaTipoPk($codigoLicenciaTipoPk): void
    {
        $this->codigoLicenciaTipoPk = $codigoLicenciaTipoPk;
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
    public function getAfectaSalud()
    {
        return $this->afectaSalud;
    }

    /**
     * @param mixed $afectaSalud
     */
    public function setAfectaSalud($afectaSalud): void
    {
        $this->afectaSalud = $afectaSalud;
    }

    /**
     * @return mixed
     */
    public function getAusentismo()
    {
        return $this->ausentismo;
    }

    /**
     * @param mixed $ausentismo
     */
    public function setAusentismo($ausentismo): void
    {
        $this->ausentismo = $ausentismo;
    }

    /**
     * @return mixed
     */
    public function getMaternidad()
    {
        return $this->maternidad;
    }

    /**
     * @param mixed $maternidad
     */
    public function setMaternidad($maternidad): void
    {
        $this->maternidad = $maternidad;
    }

    /**
     * @return mixed
     */
    public function getPaternidad()
    {
        return $this->paternidad;
    }

    /**
     * @param mixed $paternidad
     */
    public function setPaternidad($paternidad): void
    {
        $this->paternidad = $paternidad;
    }

    /**
     * @return mixed
     */
    public function getRemunerada()
    {
        return $this->remunerada;
    }

    /**
     * @param mixed $remunerada
     */
    public function setRemunerada($remunerada): void
    {
        $this->remunerada = $remunerada;
    }

    /**
     * @return mixed
     */
    public function getSuspensionContratoTrabajo()
    {
        return $this->suspensionContratoTrabajo;
    }

    /**
     * @param mixed $suspensionContratoTrabajo
     */
    public function setSuspensionContratoTrabajo($suspensionContratoTrabajo): void
    {
        $this->suspensionContratoTrabajo = $suspensionContratoTrabajo;
    }

    /**
     * @return mixed
     */
    public function getCodigoConceptoFk()
    {
        return $this->codigoConceptoFk;
    }

    /**
     * @param mixed $codigoConceptoFk
     */
    public function setCodigoConceptoFk($codigoConceptoFk): void
    {
        $this->codigoConceptoFk = $codigoConceptoFk;
    }

    /**
     * @return mixed
     */
    public function getTipoNovedadTurno()
    {
        return $this->tipoNovedadTurno;
    }

    /**
     * @param mixed $tipoNovedadTurno
     */
    public function setTipoNovedadTurno($tipoNovedadTurno): void
    {
        $this->tipoNovedadTurno = $tipoNovedadTurno;
    }

    /**
     * @return mixed
     */
    public function getLicenciasLicenciaTipoRel()
    {
        return $this->licenciasLicenciaTipoRel;
    }

    /**
     * @param mixed $licenciasLicenciaTipoRel
     */
    public function setLicenciasLicenciaTipoRel($licenciasLicenciaTipoRel): void
    {
        $this->licenciasLicenciaTipoRel = $licenciasLicenciaTipoRel;
    }

    /**
     * @return mixed
     */
    public function getConceptoRel()
    {
        return $this->conceptoRel;
    }

    /**
     * @param mixed $conceptoRel
     */
    public function setConceptoRel($conceptoRel): void
    {
        $this->conceptoRel = $conceptoRel;
    }


}
