<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuPensionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuParametroPrestacion
{
    public $infoLog = [
        "primaryKey" => "codigoParametroPrecionPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_parametro_precion_pk", type="integer")
     */
    private $codigoParametroPrecionPk;

    /**
     * @ORM\Column(name="tipo", type="string", length=3, nullable=true)
     */
    private $tipo;

    /**
     * @ORM\Column(name="orden", type="integer")
     */
    private $orden = 0;

    /**
     * @ORM\Column(name="dia_desde", type="integer")
     */
    private $diaDesde = 0;

    /**
     * @ORM\Column(name="dia_hasta", type="integer")
     */
    private $diaHasta = 0;

    /**
     * @ORM\Column(name="porcentaje", type="float")
     */
    private $porcentaje = 0;

    /**
     * @ORM\Column(name="origen", type="string", length=3, nullable=true)
     */
    private $origen;

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
    public function getCodigoParametroPrecionPk()
    {
        return $this->codigoParametroPrecionPk;
    }

    /**
     * @param mixed $codigoParametroPrecionPk
     */
    public function setCodigoParametroPrecionPk($codigoParametroPrecionPk): void
    {
        $this->codigoParametroPrecionPk = $codigoParametroPrecionPk;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo): void
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * @param mixed $orden
     */
    public function setOrden($orden): void
    {
        $this->orden = $orden;
    }

    /**
     * @return mixed
     */
    public function getDiaDesde()
    {
        return $this->diaDesde;
    }

    /**
     * @param mixed $diaDesde
     */
    public function setDiaDesde($diaDesde): void
    {
        $this->diaDesde = $diaDesde;
    }

    /**
     * @return mixed
     */
    public function getDiaHasta()
    {
        return $this->diaHasta;
    }

    /**
     * @param mixed $diaHasta
     */
    public function setDiaHasta($diaHasta): void
    {
        $this->diaHasta = $diaHasta;
    }

    /**
     * @return mixed
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * @param mixed $porcentaje
     */
    public function setPorcentaje($porcentaje): void
    {
        $this->porcentaje = $porcentaje;
    }

    /**
     * @return mixed
     */
    public function getOrigen()
    {
        return $this->origen;
    }

    /**
     * @param mixed $origen
     */
    public function setOrigen($origen): void
    {
        $this->origen = $origen;
    }



}

