<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuExamenClaseRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuExamenClase
{
    public $infoLog = [
        "primaryKey" => "codigoExamenClasePk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_clase_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenClasePk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuExamen", mappedBy="examenClaseRel")
     */
    protected $examenesExamenClaseRel;

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
    public function getCodigoExamenClasePk()
    {
        return $this->codigoExamenClasePk;
    }

    /**
     * @param mixed $codigoExamenClasePk
     */
    public function setCodigoExamenClasePk($codigoExamenClasePk): void
    {
        $this->codigoExamenClasePk = $codigoExamenClasePk;
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
    public function getExamenesExamenClaseRel()
    {
        return $this->examenesExamenClaseRel;
    }

    /**
     * @param mixed $examenesExamenClaseRel
     */
    public function setExamenesExamenClaseRel($examenesExamenClaseRel): void
    {
        $this->examenesExamenClaseRel = $examenesExamenClaseRel;
    }
}
