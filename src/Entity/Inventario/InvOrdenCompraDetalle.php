<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvOrdenCompraDetalleRepository")
 */
class InvOrdenCompraDetalle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoOrdenCompraDetallePk;

    /**
     * @var int
     *
     * @ORM\Column(name="codigo_solicitud_detalle_fk", type="integer", nullable=true)
     */
    private $codigoSolicitudDetalleFk;

    /**
     * @ORM\Column(name="codigo_orden_compra_fk", type="integer", nullable=true)
     */
    private $codigoOrdenCompraFk;

    /**
     * @var int
     *
     * @ORM\Column(name="codigo_item_fk", type="integer", nullable=true)
     */
    private $codigoItemFk;

    /**
     * @var int
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=true)
     */
    private $cantidad = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="valor", type="float", nullable=true)
     */
    private $valor = 0;

    /**
     * @ORM\Column(name="vr_subtotal", type="float", nullable=true)
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="porcentaje_iva", type="integer", nullable=true)
     */
    private $porcentajeIva = 0;

    /**
     * @ORM\Column(name="vr_iva", type="float", nullable=true)
     */
    private $vrIva = 0;

    /**
     * @ORM\Column(name="vr_total", type="float", nullable=true)
     */
    private $vrTotal = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="cantidad_pendiente", type="integer", nullable=true)
     */
    private $cantidadPendiente;

    /**
     * @return mixed
     */
    public function getCodigoOrdenCompraDetallePk()
    {
        return $this->codigoOrdenCompraDetallePk;
    }

    /**
     * @param mixed $codigoOrdenCompraDetallePk
     */
    public function setCodigoOrdenCompraDetallePk($codigoOrdenCompraDetallePk): void
    {
        $this->codigoOrdenCompraDetallePk = $codigoOrdenCompraDetallePk;
    }

    /**
     * @return int
     */
    public function getCodigoSolicitudDetalleFk(): int
    {
        return $this->codigoSolicitudDetalleFk;
    }

    /**
     * @param int $codigoSolicitudDetalleFk
     */
    public function setCodigoSolicitudDetalleFk(int $codigoSolicitudDetalleFk): void
    {
        $this->codigoSolicitudDetalleFk = $codigoSolicitudDetalleFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoOrdenCompraFk()
    {
        return $this->codigoOrdenCompraFk;
    }

    /**
     * @param mixed $codigoOrdenCompraFk
     */
    public function setCodigoOrdenCompraFk($codigoOrdenCompraFk): void
    {
        $this->codigoOrdenCompraFk = $codigoOrdenCompraFk;
    }

    /**
     * @return int
     */
    public function getCodigoItemFk(): int
    {
        return $this->codigoItemFk;
    }

    /**
     * @param int $codigoItemFk
     */
    public function setCodigoItemFk(int $codigoItemFk): void
    {
        $this->codigoItemFk = $codigoItemFk;
    }

    /**
     * @return int
     */
    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    /**
     * @param int $cantidad
     */
    public function setCantidad(int $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return float
     */
    public function getValor(): float
    {
        return $this->valor;
    }

    /**
     * @param float $valor
     */
    public function setValor(float $valor): void
    {
        $this->valor = $valor;
    }

    /**
     * @return mixed
     */
    public function getVrSubtotal()
    {
        return $this->vrSubtotal;
    }

    /**
     * @param mixed $vrSubtotal
     */
    public function setVrSubtotal($vrSubtotal): void
    {
        $this->vrSubtotal = $vrSubtotal;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeIva()
    {
        return $this->porcentajeIva;
    }

    /**
     * @param mixed $porcentajeIva
     */
    public function setPorcentajeIva($porcentajeIva): void
    {
        $this->porcentajeIva = $porcentajeIva;
    }

    /**
     * @return mixed
     */
    public function getVrIva()
    {
        return $this->vrIva;
    }

    /**
     * @param mixed $vrIva
     */
    public function setVrIva($vrIva): void
    {
        $this->vrIva = $vrIva;
    }

    /**
     * @return mixed
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * @param mixed $vrTotal
     */
    public function setVrTotal($vrTotal): void
    {
        $this->vrTotal = $vrTotal;
    }

    /**
     * @return int
     */
    public function getCantidadPendiente(): int
    {
        return $this->cantidadPendiente;
    }

    /**
     * @param int $cantidadPendiente
     */
    public function setCantidadPendiente(int $cantidadPendiente): void
    {
        $this->cantidadPendiente = $cantidadPendiente;
    }


}

