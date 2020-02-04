<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuExamenTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuExamenTipo
{
    public $infoLog = [
        "primaryKey" => "codigoExamenTipoPk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="ingreso", options={"default" : false}, type="boolean")
     */
    private $ingreso = false;

    /**
     * @ORM\OneToMany(targetEntity="RhuExamen", mappedBy="examenTipoRel")
     */
    protected $examenesExamenTipoRel;

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
    public function getCodigoExamenTipoPk()
    {
        return $this->codigoExamenTipoPk;
    }

    /**
     * @param mixed $codigoExamenTipoPk
     */
    public function setCodigoExamenTipoPk($codigoExamenTipoPk): void
    {
        $this->codigoExamenTipoPk = $codigoExamenTipoPk;
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
    public function getIngreso()
    {
        return $this->ingreso;
    }

    /**
     * @param bool $ingreso
     */
    public function setIngreso(bool $ingreso): void
    {
        $this->ingreso = $ingreso;
    }

    /**
     * @return mixed
     */
    public function getExamenesExamenTipoRel()
    {
        return $this->examenesExamenTipoRel;
    }

    /**
     * @param mixed $examenesExamenTipoRel
     */
    public function setExamenesExamenTipoRel($examenesExamenTipoRel): void
    {
        $this->examenesExamenTipoRel = $examenesExamenTipoRel;
    }


}
