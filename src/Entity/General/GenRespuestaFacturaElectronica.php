<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenRespuestaFacturaElectronicaRepository")
 */
class GenRespuestaFacturaElectronica
{

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_respuesta_factura_electronica_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRespuestaFacturaElectronicaPk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_modelo_fk", type="string", length=80, nullable=true, options={"default" : NULL})
     */
    private $codigoModeloFk;

    /**
     * @ORM\Column(name="codigo_documento", type="integer", nullable=true)
     */
    private $codigoDocumento;

    /**
     * @ORM\Column(name="status_code", type="string",length=10, nullable=true)
     */
    private $statusCode;

    /**
     * @ORM\Column(name="error_message", type="text", nullable=true)
     */
    private $errorMessage;

    /**
     * @ORM\Column(name="error_reason", type="text", nullable=true)
     */
    private $errorReason;

    /**
     * @return mixed
     */
    public function getCodigoRespuestaFacturaElectronicaPk()
    {
        return $this->codigoRespuestaFacturaElectronicaPk;
    }

    /**
     * @param mixed $codigoRespuestaFacturaElectronicaPk
     */
    public function setCodigoRespuestaFacturaElectronicaPk($codigoRespuestaFacturaElectronicaPk): void
    {
        $this->codigoRespuestaFacturaElectronicaPk = $codigoRespuestaFacturaElectronicaPk;
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
    public function getCodigoModeloFk()
    {
        return $this->codigoModeloFk;
    }

    /**
     * @param mixed $codigoModeloFk
     */
    public function setCodigoModeloFk($codigoModeloFk): void
    {
        $this->codigoModeloFk = $codigoModeloFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoDocumento()
    {
        return $this->codigoDocumento;
    }

    /**
     * @param mixed $codigoDocumento
     */
    public function setCodigoDocumento($codigoDocumento): void
    {
        $this->codigoDocumento = $codigoDocumento;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $statusCode
     */
    public function setStatusCode($statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param mixed $errorMessage
     */
    public function setErrorMessage($errorMessage): void
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return mixed
     */
    public function getErrorReason()
    {
        return $this->errorReason;
    }

    /**
     * @param mixed $errorReason
     */
    public function setErrorReason($errorReason): void
    {
        $this->errorReason = $errorReason;
    }


}

