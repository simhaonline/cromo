<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurItemRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurItem
{
    public $infoLog = [
        "primaryKey" => "codigoItemPk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_item_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoItemPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=500, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="porcentaje_iva", type="integer", nullable=true)
     */
    private $porcentajeIva = 0;

    /**
     * @ORM\Column(name="codigo_interface", type="string", length=20, nullable=true)
     */
    private $codigoInterface;

    /**
     * @ORM\Column(name="codigo_cuenta_venta_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaVentaFk;

    /**
     * @ORM\Column(name="codigo_impuesto_retencion_fk", type="string", length=5, nullable=true)
     */
    private $codigoImpuestoRetencionFk;

    /**
     * @ORM\Column(name="codigo_impuesto_iva_venta_fk", type="string", length=5, nullable=true)
     */
    private $codigoImpuestoIvaVentaFk;

    /**
     * @ORM\Column(name="orden", type="integer", nullable=true)
     */
    private $orden = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenImpuesto", inversedBy="turItemsImpuestoRetencionRel")
     * @ORM\JoinColumn(name="codigo_impuesto_retencion_fk",referencedColumnName="codigo_impuesto_pk")
     */
    protected $impuestoRetencionRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenImpuesto", inversedBy="turItemsImpuestoIvaVentaRel")
     * @ORM\JoinColumn(name="codigo_impuesto_iva_venta_fk",referencedColumnName="codigo_impuesto_pk")
     */
    protected $impuestoIvaVentaRel;

    /**
     * @ORM\OneToMany(targetEntity="TurContratoDetalle", mappedBy="itemRel")
     */
    protected $contratosDetallesItemRel;

    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="itemRel")
     */
    protected $pedidosDetallesItemRel;

    /**
     * @ORM\OneToMany(targetEntity="TurFacturaDetalle", mappedBy="itemRel")
     */
    protected $facturasDetallesItemRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCotizacionDetalle", mappedBy="itemRel")
     */
    protected $CotizacionesDetallesItemRel;

    /**
     * @ORM\OneToMany(targetEntity="TurClienteIca", mappedBy="itemRel")
     */
    protected $clientesIcaItemRel;

    /**
     * @return mixed
     */
    public function getCodigoItemPk()
    {
        return $this->codigoItemPk;
    }

    /**
     * @param mixed $codigoItemPk
     */
    public function setCodigoItemPk($codigoItemPk): void
    {
        $this->codigoItemPk = $codigoItemPk;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
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
    public function getCodigoInterface()
    {
        return $this->codigoInterface;
    }

    /**
     * @param mixed $codigoInterface
     */
    public function setCodigoInterface($codigoInterface): void
    {
        $this->codigoInterface = $codigoInterface;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaVentaFk()
    {
        return $this->codigoCuentaVentaFk;
    }

    /**
     * @param mixed $codigoCuentaVentaFk
     */
    public function setCodigoCuentaVentaFk($codigoCuentaVentaFk): void
    {
        $this->codigoCuentaVentaFk = $codigoCuentaVentaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoImpuestoRetencionFk()
    {
        return $this->codigoImpuestoRetencionFk;
    }

    /**
     * @param mixed $codigoImpuestoRetencionFk
     */
    public function setCodigoImpuestoRetencionFk($codigoImpuestoRetencionFk): void
    {
        $this->codigoImpuestoRetencionFk = $codigoImpuestoRetencionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoImpuestoIvaVentaFk()
    {
        return $this->codigoImpuestoIvaVentaFk;
    }

    /**
     * @param mixed $codigoImpuestoIvaVentaFk
     */
    public function setCodigoImpuestoIvaVentaFk($codigoImpuestoIvaVentaFk): void
    {
        $this->codigoImpuestoIvaVentaFk = $codigoImpuestoIvaVentaFk;
    }

    /**
     * @return mixed
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * @param mixed $orden
     */
    public function setOrden($orden): void
    {
        $this->orden = $orden;
    }

    /**
     * @return mixed
     */
    public function getImpuestoRetencionRel()
    {
        return $this->impuestoRetencionRel;
    }

    /**
     * @param mixed $impuestoRetencionRel
     */
    public function setImpuestoRetencionRel($impuestoRetencionRel): void
    {
        $this->impuestoRetencionRel = $impuestoRetencionRel;
    }

    /**
     * @return mixed
     */
    public function getImpuestoIvaVentaRel()
    {
        return $this->impuestoIvaVentaRel;
    }

    /**
     * @param mixed $impuestoIvaVentaRel
     */
    public function setImpuestoIvaVentaRel($impuestoIvaVentaRel): void
    {
        $this->impuestoIvaVentaRel = $impuestoIvaVentaRel;
    }

    /**
     * @return mixed
     */
    public function getContratosDetallesItemRel()
    {
        return $this->contratosDetallesItemRel;
    }

    /**
     * @param mixed $contratosDetallesItemRel
     */
    public function setContratosDetallesItemRel($contratosDetallesItemRel): void
    {
        $this->contratosDetallesItemRel = $contratosDetallesItemRel;
    }

    /**
     * @return mixed
     */
    public function getPedidosDetallesItemRel()
    {
        return $this->pedidosDetallesItemRel;
    }

    /**
     * @param mixed $pedidosDetallesItemRel
     */
    public function setPedidosDetallesItemRel($pedidosDetallesItemRel): void
    {
        $this->pedidosDetallesItemRel = $pedidosDetallesItemRel;
    }

    /**
     * @return mixed
     */
    public function getFacturasDetallesItemRel()
    {
        return $this->facturasDetallesItemRel;
    }

    /**
     * @param mixed $facturasDetallesItemRel
     */
    public function setFacturasDetallesItemRel($facturasDetallesItemRel): void
    {
        $this->facturasDetallesItemRel = $facturasDetallesItemRel;
    }

    /**
     * @return mixed
     */
    public function getCotizacionesDetallesItemRel()
    {
        return $this->CotizacionesDetallesItemRel;
    }

    /**
     * @param mixed $CotizacionesDetallesItemRel
     */
    public function setCotizacionesDetallesItemRel($CotizacionesDetallesItemRel): void
    {
        $this->CotizacionesDetallesItemRel = $CotizacionesDetallesItemRel;
    }




}

