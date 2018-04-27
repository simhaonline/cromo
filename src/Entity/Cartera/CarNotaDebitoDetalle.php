<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarNotaDebitoDetalleRepository")
 */
class CarNotaDebitoDetalle
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_nota_debito_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoNotaDebitoDetallePk;

    /**
     * @ORM\Column(name="codigo_nota_debito_fk", type="integer", nullable=true)
     */
    private $codigoNotaDebitoFk;

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
     * @ORM\ManyToOne(targetEntity="CarNotaDebito", inversedBy="notasDebitosDetallesNotaDebitoRel")
     * @ORM\JoinColumn(name="codigo_nota_debito_fk", referencedColumnName="codigo_nota_debito_pk")
     */
    protected $notaDebitoRel;

    /**
     * @return mixed
     */
    public function getCodigoNotaDebitoDetallePk()
    {
        return $this->codigoNotaDebitoDetallePk;
    }

    /**
     * @param mixed $codigoNotaDebitoDetallePk
     */
    public function setCodigoNotaDebitoDetallePk($codigoNotaDebitoDetallePk): void
    {
        $this->codigoNotaDebitoDetallePk = $codigoNotaDebitoDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoNotaDebitoFk()
    {
        return $this->codigoNotaDebitoFk;
    }

    /**
     * @param mixed $codigoNotaDebitoFk
     */
    public function setCodigoNotaDebitoFk($codigoNotaDebitoFk): void
    {
        $this->codigoNotaDebitoFk = $codigoNotaDebitoFk;
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
    public function getNotaDebitoRel()
    {
        return $this->notaDebitoRel;
    }

    /**
     * @param mixed $notaDebitoRel
     */
    public function setNotaDebitoRel($notaDebitoRel): void
    {
        $this->notaDebitoRel = $notaDebitoRel;
    }


}
