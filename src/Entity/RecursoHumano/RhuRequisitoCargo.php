<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuRequisitoCargoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuRequisitoCargo
{
    public $infoLog = [
        "primaryKey" => "codigoRequisitoCargoPk",
        "todos"     => true,
    ];
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_requisito_cargo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRequisitoCargoPk;

    /**
     * @ORM\Column(name="codigo_requisito_concepto_fk", type="integer")
     */
    private $codigoRequisitoConceptoFk;

    /**
     * @ORM\Column(name="codigo_cargo_fk", type="string", length=10)
     */
    private $codigoCargoFk;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="requisitosCargosCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuRequisitoConcepto", inversedBy="requisitosCargosRequisitoConceptoRel")
     * @ORM\JoinColumn(name="codigo_requisito_concepto_fk", referencedColumnName="codigo_requisito_concepto_pk")
     */
    protected $requisitoConceptoRel;

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
    public function getCodigoRequisitoCargoPk()
    {
        return $this->codigoRequisitoCargoPk;
    }

    /**
     * @param mixed $codigoRequisitoCargoPk
     */
    public function setCodigoRequisitoCargoPk($codigoRequisitoCargoPk): void
    {
        $this->codigoRequisitoCargoPk = $codigoRequisitoCargoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoRequisitoConceptoFk()
    {
        return $this->codigoRequisitoConceptoFk;
    }

    /**
     * @param mixed $codigoRequisitoConceptoFk
     */
    public function setCodigoRequisitoConceptoFk($codigoRequisitoConceptoFk): void
    {
        $this->codigoRequisitoConceptoFk = $codigoRequisitoConceptoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCargoFk()
    {
        return $this->codigoCargoFk;
    }

    /**
     * @param mixed $codigoCargoFk
     */
    public function setCodigoCargoFk($codigoCargoFk): void
    {
        $this->codigoCargoFk = $codigoCargoFk;
    }

    /**
     * @return mixed
     */
    public function getCargoRel()
    {
        return $this->cargoRel;
    }

    /**
     * @param mixed $cargoRel
     */
    public function setCargoRel($cargoRel): void
    {
        $this->cargoRel = $cargoRel;
    }

    /**
     * @return mixed
     */
    public function getRequisitoConceptoRel()
    {
        return $this->requisitoConceptoRel;
    }

    /**
     * @param mixed $requisitoConceptoRel
     */
    public function setRequisitoConceptoRel($requisitoConceptoRel): void
    {
        $this->requisitoConceptoRel = $requisitoConceptoRel;
    }
}
