<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuRequisitoConceptoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuRequisitoConcepto
{
    public $infoLog = [
        "primaryKey" => "codigoRequisitoConceptoPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_requisito_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRequisitoConceptoPk;                    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;    
    
    /**     
     * @ORM\Column(name="general", type="boolean")
     */    
    private $general = 0;

    /**
     * @ORM\OneToMany(targetEntity="RhuRequisitoCargo", mappedBy="requisitoConceptoRel")
     */
    protected $requisitosCargosRequisitoConceptoRel;

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
    public function getCodigoRequisitoConceptoPk()
    {
        return $this->codigoRequisitoConceptoPk;
    }

    /**
     * @param mixed $codigoRequisitoConceptoPk
     */
    public function setCodigoRequisitoConceptoPk($codigoRequisitoConceptoPk): void
    {
        $this->codigoRequisitoConceptoPk = $codigoRequisitoConceptoPk;
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
    public function getGeneral()
    {
        return $this->general;
    }

    /**
     * @param mixed $general
     */
    public function setGeneral($general): void
    {
        $this->general = $general;
    }

    /**
     * @return mixed
     */
    public function getRequisitosCargosRequisitoConceptoRel()
    {
        return $this->requisitosCargosRequisitoConceptoRel;
    }

    /**
     * @param mixed $requisitosCargosRequisitoConceptoRel
     */
    public function setRequisitosCargosRequisitoConceptoRel($requisitosCargosRequisitoConceptoRel): void
    {
        $this->requisitosCargosRequisitoConceptoRel = $requisitosCargosRequisitoConceptoRel;
    }
}
