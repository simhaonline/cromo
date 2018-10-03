<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuProgramacionRepository")
 */
class RhuProgramacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_programacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProgramacionPk;

    /**
     * @ORM\Column(name="codigo_pago_tipo_fk", type="integer", nullable=false)
     */
    private $codigoPagoTipoFk;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="dias", type="integer")
     */
    private $dias = 0;

    /**
     * @ORM\Column(name="codigo_grupo_fk", type="integer", nullable=true)
     */
    private $codigoGrupoFk;

    /**
     * @return mixed
     */
    public function getCodigoProgramacionPk()
    {
        return $this->codigoProgramacionPk;
    }

    /**
     * @param mixed $codigoProgramacionPk
     */
    public function setCodigoProgramacionPk($codigoProgramacionPk): void
    {
        $this->codigoProgramacionPk = $codigoProgramacionPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPagoTipoFk()
    {
        return $this->codigoPagoTipoFk;
    }

    /**
     * @param mixed $codigoPagoTipoFk
     */
    public function setCodigoPagoTipoFk($codigoPagoTipoFk): void
    {
        $this->codigoPagoTipoFk = $codigoPagoTipoFk;
    }

    /**
     * @return mixed
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * @param mixed $fechaDesde
     */
    public function setFechaDesde($fechaDesde): void
    {
        $this->fechaDesde = $fechaDesde;
    }

    /**
     * @return mixed
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * @param mixed $fechaHasta
     */
    public function setFechaHasta($fechaHasta): void
    {
        $this->fechaHasta = $fechaHasta;
    }

    /**
     * @return mixed
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * @param mixed $dias
     */
    public function setDias($dias): void
    {
        $this->dias = $dias;
    }

    /**
     * @return mixed
     */
    public function getCodigoGrupoFk()
    {
        return $this->codigoGrupoFk;
    }

    /**
     * @param mixed $codigoGrupoFk
     */
    public function setCodigoGrupoFk($codigoGrupoFk): void
    {
        $this->codigoGrupoFk = $codigoGrupoFk;
    }



}
