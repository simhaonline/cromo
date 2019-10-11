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
     * @ORM\Column(name="tipo_documento", type="string",length=1, nullable=true)
     */
    private $tipoDocumento;

    /**
     * @ORM\Column(name="prefijo", type="string",length=5, nullable=true)
     */
    private $prefijo;

    /**
     * @ORM\Column(name="consecutivo", type="integer", nullable=true)
     */
    private $consecutivo=0;

    /**
     * @ORM\Column(name="cufe", type="string",length=500, nullable=true)
     */
    private $cufe;

    /**
     * @ORM\Column(name="codigo_qr", type="string",length=2000, nullable=true)
     */
    private $codigoQr;

    /**
     * @ORM\Column(name="fecha_expedicion", type="date", nullable=true)
     */
    private $fechaExpedicion;

    /**
     * @ORM\Column(name="fecha_respuesta", type="date", nullable=true)
     */
    private $fechaRespuesta;

    /**
     * @ORM\Column(name="descripcion_proceso", type="string",length=500, nullable=true)
     */
    private $descripcionProceso;

    /**
     * @ORM\Column(name="estado_proceso", type="integer", nullable=true)
     */
    private $estadoProceso=0;

    /**
     * @ORM\Column(name="lista_mensajes_proceso", type="text", nullable=true)
     */
    private $listaMensajesProceso;

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
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * @param mixed $tipoDocumento
     */
    public function setTipoDocumento($tipoDocumento): void
    {
        $this->tipoDocumento = $tipoDocumento;
    }

    /**
     * @return mixed
     */
    public function getPrefijo()
    {
        return $this->prefijo;
    }

    /**
     * @param mixed $prefijo
     */
    public function setPrefijo($prefijo): void
    {
        $this->prefijo = $prefijo;
    }

    /**
     * @return mixed
     */
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }

    /**
     * @param mixed $consecutivo
     */
    public function setConsecutivo($consecutivo): void
    {
        $this->consecutivo = $consecutivo;
    }

    /**
     * @return mixed
     */
    public function getCufe()
    {
        return $this->cufe;
    }

    /**
     * @param mixed $cufe
     */
    public function setCufe($cufe): void
    {
        $this->cufe = $cufe;
    }

    /**
     * @return mixed
     */
    public function getCodigoQr()
    {
        return $this->codigoQr;
    }

    /**
     * @param mixed $codigoQr
     */
    public function setCodigoQr($codigoQr): void
    {
        $this->codigoQr = $codigoQr;
    }

    /**
     * @return mixed
     */
    public function getFechaExpedicion()
    {
        return $this->fechaExpedicion;
    }

    /**
     * @param mixed $fechaExpedicion
     */
    public function setFechaExpedicion($fechaExpedicion): void
    {
        $this->fechaExpedicion = $fechaExpedicion;
    }

    /**
     * @return mixed
     */
    public function getFechaRespuesta()
    {
        return $this->fechaRespuesta;
    }

    /**
     * @param mixed $fechaRespuesta
     */
    public function setFechaRespuesta($fechaRespuesta): void
    {
        $this->fechaRespuesta = $fechaRespuesta;
    }

    /**
     * @return mixed
     */
    public function getDescripcionProceso()
    {
        return $this->descripcionProceso;
    }

    /**
     * @param mixed $descripcionProceso
     */
    public function setDescripcionProceso($descripcionProceso): void
    {
        $this->descripcionProceso = $descripcionProceso;
    }

    /**
     * @return mixed
     */
    public function getEstadoProceso()
    {
        return $this->estadoProceso;
    }

    /**
     * @param mixed $estadoProceso
     */
    public function setEstadoProceso($estadoProceso): void
    {
        $this->estadoProceso = $estadoProceso;
    }

    /**
     * @return mixed
     */
    public function getListaMensajesProceso()
    {
        return $this->listaMensajesProceso;
    }

    /**
     * @param mixed $listaMensajesProceso
     */
    public function setListaMensajesProceso($listaMensajesProceso): void
    {
        $this->listaMensajesProceso = $listaMensajesProceso;
    }


}

