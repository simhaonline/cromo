<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurConfiguracionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurConfiguracion
{
    public $infoLog = [
        "primaryKey" => "codigoConfiguracionPk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_pk", type="integer")
     */
    private $codigoConfiguracionPk;

    /**
     * @ORM\Column(name="vr_salario_minimo", options={"default":0}, type="float", nullable=true)
     */
    private $vrSalarioMinimo = 0;

    /**
     * @ORM\Column(name="vr_auxilio_transporte", options={"default":0}, type="float", nullable=true)
     */
    private $vrAuxilioTransporte = 0;

    /**
     * @ORM\Column(name="codigo_formato_factura", type="integer", nullable=true)
     */
    private $codigoFormatoFactura;

    /**
     * @ORM\Column(name="redondear_valor_factura", type="boolean", nullable=true)
     */
    private $redondearValorFactura = false;

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
    public function getCodigoFormatoFactura()
    {
        return $this->codigoFormatoFactura;
    }

    /**
     * @param mixed $codigoFormatoFactura
     */
    public function setCodigoFormatoFactura($codigoFormatoFactura): void
    {
        $this->codigoFormatoFactura = $codigoFormatoFactura;
    }

    /**
     * @return mixed
     */
    public function getRedondearValorFactura()
    {
        return $this->redondearValorFactura;
    }

    /**
     * @param mixed $redondearValorFactura
     */
    public function setRedondearValorFactura($redondearValorFactura): void
    {
        $this->redondearValorFactura = $redondearValorFactura;
    }



}

