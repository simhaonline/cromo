<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAdicionalPeriodoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuAdicionalPeriodo
{
    public $infoLog = [
        "primaryKey" => "codigoAdicionalPeriodoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_adicional_periodo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAdicionalPeriodoPk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="nombre", type="text", nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="estado_cerrado", type="boolean", nullable=true)
     */
    private $estadoCerrado = false;

    /**
     * @ORM\OneToMany(targetEntity="RhuAdicional", mappedBy="adicionalPeriodoRel")
     */
    protected $adicionalesPeriodoRel;

    /**
     * @return mixed
     */
    public function getCodigoAdicionalPeriodoPk()
    {
        return $this->codigoAdicionalPeriodoPk;
    }

    /**
     * @param mixed $codigoAdicionalPeriodoPk
     */
    public function setCodigoAdicionalPeriodoPk($codigoAdicionalPeriodoPk): void
    {
        $this->codigoAdicionalPeriodoPk = $codigoAdicionalPeriodoPk;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha): void
    {
        $this->fecha = $fecha;
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
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }

    /**
     * @param mixed $estadoCerrado
     */
    public function setEstadoCerrado($estadoCerrado): void
    {
        $this->estadoCerrado = $estadoCerrado;
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
    public function getAdicionalesPeriodoRel()
    {
        return $this->adicionalesPeriodoRel;
    }

    /**
     * @param mixed $adicionalesPeriodoRel
     */
    public function setAdicionalesPeriodoRel($adicionalesPeriodoRel): void
    {
        $this->adicionalesPeriodoRel = $adicionalesPeriodoRel;
    }



}
