<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarNotaCreditoDetalleRepository")
 */
class CarNotaCreditoDetalle
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_nota_credito_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoNotaCreditoDetallePk;

    /**
     * @ORM\Column(name="codigo_nota_credito_fk", type="integer", nullable=true)
     */
    private $codigoNotaCreditoFk;

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_fk", type="integer", nullable=true)
     */
    private $codigoCuentaCobrarFk;

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_tipo_fk", type="integer", nullable=true)
     */
    private $codigoCuentaCobrarTipoFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="numero_factura", type="integer", nullable=true)
     */
    private $numeroFactura;

    /**
     * @ORM\Column(name="valor", type="float")
     */
    private $valor = 0;

    /**
     * @ORM\Column(name="vr_pago_detalle", type="float")
     */
    private $vrPago = 0;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="CarNotaCredito", inversedBy="notasCreditosDetallesNotaCreditoRel")
     * @ORM\JoinColumn(name="codigo_nota_credito_fk", referencedColumnName="codigo_nota_credito_pk")
     */
    protected $notaCreditoRel;

    /**
     * @return mixed
     */
    public function getCodigoNotaCreditoDetallePk()
    {
        return $this->codigoNotaCreditoDetallePk;
    }

    /**
     * @param mixed $codigoNotaCreditoDetallePk
     */
    public function setCodigoNotaCreditoDetallePk($codigoNotaCreditoDetallePk): void
    {
        $this->codigoNotaCreditoDetallePk = $codigoNotaCreditoDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoNotaCreditoFk()
    {
        return $this->codigoNotaCreditoFk;
    }

    /**
     * @param mixed $codigoNotaCreditoFk
     */
    public function setCodigoNotaCreditoFk($codigoNotaCreditoFk): void
    {
        $this->codigoNotaCreditoFk = $codigoNotaCreditoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaCobrarFk()
    {
        return $this->codigoCuentaCobrarFk;
    }

    /**
     * @param mixed $codigoCuentaCobrarFk
     */
    public function setCodigoCuentaCobrarFk($codigoCuentaCobrarFk): void
    {
        $this->codigoCuentaCobrarFk = $codigoCuentaCobrarFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaCobrarTipoFk()
    {
        return $this->codigoCuentaCobrarTipoFk;
    }

    /**
     * @param mixed $codigoCuentaCobrarTipoFk
     */
    public function setCodigoCuentaCobrarTipoFk($codigoCuentaCobrarTipoFk): void
    {
        $this->codigoCuentaCobrarTipoFk = $codigoCuentaCobrarTipoFk;
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
    public function getNumeroFactura()
    {
        return $this->numeroFactura;
    }

    /**
     * @param mixed $numeroFactura
     */
    public function setNumeroFactura($numeroFactura): void
    {
        $this->numeroFactura = $numeroFactura;
    }

    /**
     * @return mixed
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param mixed $valor
     */
    public function setValor($valor): void
    {
        $this->valor = $valor;
    }

    /**
     * @return mixed
     */
    public function getVrPago()
    {
        return $this->vrPago;
    }

    /**
     * @param mixed $vrPago
     */
    public function setVrPago($vrPago): void
    {
        $this->vrPago = $vrPago;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
    }

    /**
     * @return mixed
     */
    public function getNotaCreditoRel()
    {
        return $this->notaCreditoRel;
    }

    /**
     * @param mixed $notaCreditoRel
     */
    public function setNotaCreditoRel($notaCreditoRel): void
    {
        $this->notaCreditoRel = $notaCreditoRel;
    }

}
