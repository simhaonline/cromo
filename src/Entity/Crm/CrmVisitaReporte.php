<?php

namespace App\Entity\Crm;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Crm\CrmVisitaReporteRepository")
 */
class CrmVisitaReporte
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_visita_reporte_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoVisitaReportePk;

    /**
     * @ORM\Column(name="reporte", type="text", nullable=true)
     */
    private $reporte;

    /**
     * @ORM\Column(name="codigo_visita_fk", type="integer", nullable=true)
     */
    private $codigoVisitaFk;

    /**
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Crm\CrmVisita", inversedBy="visitasReporteRel")
     * @ORM\JoinColumn(name="codigo_visita_fk", referencedColumnName="codigo_visita_pk")
     */
    protected $visitaRel;

    /**
     * @return mixed
     */
    public function getCodigoVisitaReportePk()
    {
        return $this->codigoVisitaReportePk;
    }

    /**
     * @param mixed $codigoVisitaReportePk
     */
    public function setCodigoVisitaReportePk($codigoVisitaReportePk): void
    {
        $this->codigoVisitaReportePk = $codigoVisitaReportePk;
    }

    /**
     * @return mixed
     */
    public function getReporte()
    {
        return $this->reporte;
    }

    /**
     * @param mixed $reporte
     */
    public function setReporte($reporte): void
    {
        $this->reporte = $reporte;
    }

    /**
     * @return mixed
     */
    public function getCodigoVisitaFk()
    {
        return $this->codigoVisitaFk;
    }

    /**
     * @param mixed $codigoVisitaFk
     */
    public function setCodigoVisitaFk($codigoVisitaFk): void
    {
        $this->codigoVisitaFk = $codigoVisitaFk;
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
    public function getVisitaRel()
    {
        return $this->visitaRel;
    }

    /**
     * @param mixed $visitaRel
     */
    public function setVisitaRel($visitaRel): void
    {
        $this->visitaRel = $visitaRel;
    }



}
