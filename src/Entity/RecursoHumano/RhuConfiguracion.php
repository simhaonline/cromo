<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="rhu_configuracion")
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuConfiguracionRepository")
 */
class RhuConfiguracion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_pk", type="integer")
     */
    private $codigoConfiguracionPk;

    /**
     * @ORM\Column(name="vr_salario", type="float",options={"default":0}, nullable=true)
     * @Assert\NotBlank(message="Este campo no puede estar vacío")
     */
    private $vrSalario = 0;

    /**
     * @ORM\Column(name="codigo_auxilio_transporte",options={"default":0}, type="string", length=10, nullable=true)
     */
    private $codigoAuxilioTransporte = 0;

    /**
     * @ORM\Column(name="vr_auxilio_transporte", type="float", nullable=true)
     */
    private $vrAuxilioTransporte;

    /**
     * @return mixed
     */
    public function getCodigoConfiguracionPk()
    {
        return $this->codigoConfiguracionPk;
    }

    /**
     * @param mixed $codigoConfiguracionPk
     */
    public function setCodigoConfiguracionPk($codigoConfiguracionPk): void
    {
        $this->codigoConfiguracionPk = $codigoConfiguracionPk;
    }

    /**
     * @return mixed
     */
    public function getVrSalario()
    {
        return $this->vrSalario;
    }

    /**
     * @param mixed $vrSalario
     */
    public function setVrSalario($vrSalario): void
    {
        $this->vrSalario = $vrSalario;
    }

    /**
     * @return mixed
     */
    public function getCodigoAuxilioTransporte()
    {
        return $this->codigoAuxilioTransporte;
    }

    /**
     * @param mixed $codigoAuxilioTransporte
     */
    public function setCodigoAuxilioTransporte($codigoAuxilioTransporte): void
    {
        $this->codigoAuxilioTransporte = $codigoAuxilioTransporte;
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
}
