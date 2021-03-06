<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuContratoMotivoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuContratoMotivo
{
    public $infoLog = [
        "primaryKey" => "codigoContratoMotivoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contrato_motivo_pk", type="string", length=10)
     */
    private $codigoContratoMotivoPk;
    
    /**
     * @ORM\Column(name="motivo", type="string", length=80, nullable=true)
     */    
    private $motivo;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="contratoMotivoRel")
     */
    protected $contratosContratoMotivoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuLiquidacion", mappedBy="motivoTerminacionRel")
     */
    protected $liquidacionesMotivoTerminacionContratoRel;

    /**
     * @return mixed
     */
    public function getCodigoContratoMotivoPk()
    {
        return $this->codigoContratoMotivoPk;
    }

    /**
     * @param mixed $codigoContratoMotivoPk
     */
    public function setCodigoContratoMotivoPk($codigoContratoMotivoPk): void
    {
        $this->codigoContratoMotivoPk = $codigoContratoMotivoPk;
    }

    /**
     * @return mixed
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * @param mixed $motivo
     */
    public function setMotivo($motivo): void
    {
        $this->motivo = $motivo;
    }

    /**
     * @return mixed
     */
    public function getContratosContratoMotivoRel()
    {
        return $this->contratosContratoMotivoRel;
    }

    /**
     * @param mixed $contratosContratoMotivoRel
     */
    public function setContratosContratoMotivoRel($contratosContratoMotivoRel): void
    {
        $this->contratosContratoMotivoRel = $contratosContratoMotivoRel;
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
    public function getLiquidacionesMotivoTerminacionContratoRel()
    {
        return $this->liquidacionesMotivoTerminacionContratoRel;
    }

    /**
     * @param mixed $liquidacionesMotivoTerminacionContratoRel
     */
    public function setLiquidacionesMotivoTerminacionContratoRel($liquidacionesMotivoTerminacionContratoRel): void
    {
        $this->liquidacionesMotivoTerminacionContratoRel = $liquidacionesMotivoTerminacionContratoRel;
    }


}
