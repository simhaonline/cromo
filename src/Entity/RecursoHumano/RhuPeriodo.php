<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuPeriodoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoPeriodoPk"},message="Ya existe el código del pago tipo")
 *
 */
class RhuPeriodo
{
    public $infoLog = [
        "primaryKey" => "codigoPeriodoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_periodo_pk", type="string", length=10)
     */
    private $codigoPeriodoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="dias", type="integer")
     */
    private $dias = 0;

    /**
     * @ORM\Column(name="limite_horas_extra", type="integer")
     */
    private $limiteHorasExtra = 0;

    /**
     * Esta propiedad define si tiene cortes en el mes o no 10 15 30
     * @ORM\Column(name="continuo", type="boolean", nullable=true)
     */
    private $continuo = false;

    /**
     * Especifica de cuantos periodos consta el mes, aplica solo para no continuos
     * @ORM\Column(name="periodos_mes", type="float")
     */
    private $periodosMes = 0;

    /**
     * @ORM\OneToMany(targetEntity="RhuPago", mappedBy="periodoRel" )
     */
    protected $pagosPeriodoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuGrupo", mappedBy="periodoRel" )
     */
    protected $gruposPeriodoRel;

    /**
     * @return mixed
     */
    public function getCodigoPeriodoPk()
    {
        return $this->codigoPeriodoPk;
    }

    /**
     * @param mixed $codigoPeriodoPk
     */
    public function setCodigoPeriodoPk($codigoPeriodoPk): void
    {
        $this->codigoPeriodoPk = $codigoPeriodoPk;
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
     * @return int
     */
    public function getDias(): int
    {
        return $this->dias;
    }

    /**
     * @param int $dias
     */
    public function setDias(int $dias): void
    {
        $this->dias = $dias;
    }

    /**
     * @return int
     */
    public function getLimiteHorasExtra(): int
    {
        return $this->limiteHorasExtra;
    }

    /**
     * @param int $limiteHorasExtra
     */
    public function setLimiteHorasExtra(int $limiteHorasExtra): void
    {
        $this->limiteHorasExtra = $limiteHorasExtra;
    }

    /**
     * @return bool
     */
    public function isContinuo(): bool
    {
        return $this->continuo;
    }

    /**
     * @param bool $continuo
     */
    public function setContinuo(bool $continuo): void
    {
        $this->continuo = $continuo;
    }

    /**
     * @return int
     */
    public function getPeriodosMes(): int
    {
        return $this->periodosMes;
    }

    /**
     * @param int $periodosMes
     */
    public function setPeriodosMes(int $periodosMes): void
    {
        $this->periodosMes = $periodosMes;
    }

    /**
     * @return mixed
     */
    public function getPagosPeriodoRel()
    {
        return $this->pagosPeriodoRel;
    }

    /**
     * @param mixed $pagosPeriodoRel
     */
    public function setPagosPeriodoRel($pagosPeriodoRel): void
    {
        $this->pagosPeriodoRel = $pagosPeriodoRel;
    }

    /**
     * @return mixed
     */
    public function getGruposPeriodoRel()
    {
        return $this->gruposPeriodoRel;
    }

    /**
     * @param mixed $gruposPeriodoRel
     */
    public function setGruposPeriodoRel($gruposPeriodoRel): void
    {
        $this->gruposPeriodoRel = $gruposPeriodoRel;
    }




}