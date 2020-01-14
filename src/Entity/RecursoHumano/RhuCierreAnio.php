<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCierreAnioRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuCierreAnio
{
    public $infoLog = [
        "primaryKey" => "codigoCierreAnioPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cierre_anio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCierreAnioPk;

    /**
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio = 0;

    /**
     * @ORM\Column(name="vr_salario_minimo", options={"default":0},type="float", nullable=true)
     */
    private $vrSalarioMinimo = 0;

    /**
     * @ORM\Column(name="vr_auxilio_transporte", options={"default":0},type="float", nullable=true)
     */
    private $vrAuxilioTransporte = 0;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean", options={"default":false})
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean", options={"default":false})
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean", options={"default":false})
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean", options={"default":false})
     */
    private $estadoContabilizado = false;

    /**
     * @return mixed
     */
    public function getCodigoCierreAnioPk()
    {
        return $this->codigoCierreAnioPk;
    }

    /**
     * @param mixed $codigoCierreAnioPk
     */
    public function setCodigoCierreAnioPk($codigoCierreAnioPk): void
    {
        $this->codigoCierreAnioPk = $codigoCierreAnioPk;
    }

    /**
     * @return mixed
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * @param mixed $anio
     */
    public function setAnio($anio): void
    {
        $this->anio = $anio;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioMinimo()
    {
        return $this->vrSalarioMinimo;
    }

    /**
     * @param mixed $vrSalarioMinimo
     */
    public function setVrSalarioMinimo($vrSalarioMinimo): void
    {
        $this->vrSalarioMinimo = $vrSalarioMinimo;
    }

    /**
     * @return mixed
     */
    public function getVrAuxilioTransporte()
    {
        return $this->vrAuxilioTransporte;
    }

    /**
     * @param mixed $vrAuxilioTransporte
     */
    public function setVrAuxilioTransporte($vrAuxilioTransporte): void
    {
        $this->vrAuxilioTransporte = $vrAuxilioTransporte;
    }

    /**
     * @return mixed
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * @param mixed $estadoAutorizado
     */
    public function setEstadoAutorizado($estadoAutorizado): void
    {
        $this->estadoAutorizado = $estadoAutorizado;
    }

    /**
     * @return mixed
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * @param mixed $estadoAprobado
     */
    public function setEstadoAprobado($estadoAprobado): void
    {
        $this->estadoAprobado = $estadoAprobado;
    }

    /**
     * @return mixed
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * @param mixed $estadoAnulado
     */
    public function setEstadoAnulado($estadoAnulado): void
    {
        $this->estadoAnulado = $estadoAnulado;
    }

    /**
     * @return mixed
     */
    public function getEstadoContabilizado()
    {
        return $this->estadoContabilizado;
    }

    /**
     * @param mixed $estadoContabilizado
     */
    public function setEstadoContabilizado($estadoContabilizado): void
    {
        $this->estadoContabilizado = $estadoContabilizado;
    }



}
