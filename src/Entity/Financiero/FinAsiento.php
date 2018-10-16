<?php


namespace App\Entity\Financiero;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Financiero\FinAsientoRepository")
 */
class FinAsiento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_asiento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAsientoPk;

    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_contable", type="date", nullable=true)
     */
    private $fechaContable;

    /**
     * @ORM\Column(name="fecha_documento", type="date", nullable=true)
     */
    private $fechaDocumento;

    /**
     * @ORM\Column(name="codigo_comprobante_fk", type="string", length=20, nullable=true)
     */
    private $codigoComprobanteFk;

    /**
     * @ORM\Column(name="vr_debito", type="float", nullable=true, options={"default" : 0})
     */
    private $vrDebito = 0;

    /**
     * @ORM\Column(name="vr_credito", type="float", nullable=true, options={"default" : 0})
     */
    private $vrCredito = 0;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoContabilizado = false;

    /**
     * @ORM\Column(name="comentario", type="text", nullable=true)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Financiero\FinComprobante", inversedBy="asientosComprobanteRel")
     * @ORM\JoinColumn(name="codigo_comprobante_fk", referencedColumnName="codigo_comprobante_pk")
     */
    protected $comprobanteRel;


    /**
     * @return mixed
     */
    public function getCodigoAsientoPk()
    {
        return $this->codigoAsientoPk;
    }

    /**
     * @param mixed $codigoAsientoPk
     */
    public function setCodigoAsientoPk($codigoAsientoPk): void
    {
        $this->codigoAsientoPk = $codigoAsientoPk;
    }

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero): void
    {
        $this->numero = $numero;
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
    public function getFechaContable()
    {
        return $this->fechaContable;
    }

    /**
     * @param mixed $fechaContable
     */
    public function setFechaContable($fechaContable): void
    {
        $this->fechaContable = $fechaContable;
    }

    /**
     * @return mixed
     */
    public function getFechaDocumento()
    {
        return $this->fechaDocumento;
    }

    /**
     * @param mixed $fechaDocumento
     */
    public function setFechaDocumento($fechaDocumento): void
    {
        $this->fechaDocumento = $fechaDocumento;
    }

    /**
     * @return mixed
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * @param mixed $comentario
     */
    public function setComentario($comentario): void
    {
        $this->comentario = $comentario;
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

    /**
     * @return mixed
     */
    public function getCodigoComprobanteFk()
    {
        return $this->codigoComprobanteFk;
    }

    /**
     * @param mixed $codigoComprobanteFk
     */
    public function setCodigoComprobanteFk($codigoComprobanteFk): void
    {
        $this->codigoComprobanteFk = $codigoComprobanteFk;
    }

    /**
     * @return mixed
     */
    public function getComprobanteRel()
    {
        return $this->comprobanteRel;
    }

    /**
     * @param mixed $comprobanteRel
     */
    public function setComprobanteRel($comprobanteRel): void
    {
        $this->comprobanteRel = $comprobanteRel;
    }

    /**
     * @return mixed
     */
    public function getVrDebito()
    {
        return $this->vrDebito;
    }

    /**
     * @param mixed $vrDebito
     */
    public function setVrDebito($vrDebito): void
    {
        $this->vrDebito = $vrDebito;
    }

    /**
     * @return mixed
     */
    public function getVrCredito()
    {
        return $this->vrCredito;
    }

    /**
     * @param mixed $vrCredito
     */
    public function setVrCredito($vrCredito): void
    {
        $this->vrCredito = $vrCredito;
    }



}

