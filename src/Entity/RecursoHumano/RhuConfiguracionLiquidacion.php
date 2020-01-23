<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuConfiguracionLiquidacionRepository")
 */
class RhuConfiguracionLiquidacion
{

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_liquidacion_pk", type="integer")
     */
    private $codigoConfiguracionLiquidacionPk;

    /**
     * @ORM\Column(name="aplicar", options={"default" : false}, type="boolean")
     */
    private $aplicar= false;

    /**
     * @ORM\Column(name="recargo_nocturno", type="string", length=10, nullable=true)
     */
    private $recargoNocturno;

    /**
     * @return mixed
     */
    public function getCodigoConfiguracionLiquidacionPk()
    {
        return $this->codigoConfiguracionLiquidacionPk;
    }

    /**
     * @param mixed $codigoConfiguracionLiquidacionPk
     */
    public function setCodigoConfiguracionLiquidacionPk($codigoConfiguracionLiquidacionPk): void
    {
        $this->codigoConfiguracionLiquidacionPk = $codigoConfiguracionLiquidacionPk;
    }

    /**
     * @return mixed
     */
    public function getRecargoNocturno()
    {
        return $this->recargoNocturno;
    }

    /**
     * @param mixed $recargoNocturno
     */
    public function setRecargoNocturno($recargoNocturno): void
    {
        $this->recargoNocturno = $recargoNocturno;
    }

    /**
     * @return mixed
     */
    public function getAplicar()
    {
        return $this->aplicar;
    }

    /**
     * @param mixed $aplicar
     */
    public function setAplicar($aplicar): void
    {
        $this->aplicar = $aplicar;
    }



}
