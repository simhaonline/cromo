<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenImpuestoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class GenImpuesto
{
    public $infoLog = [
        "primaryKey" => "codigoImpuestoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_impuesto_pk", type="string", length=5, nullable=false)
     */
    private $codigoImpuestoPk;

    /**
     * @ORM\Column(name="codigo_impuesto_tipo_fk", type="string", length=3, nullable=true)
     */
    private $codigoImpuestoTipoFk;

    /**
     * @ORM\Column(name="nombre", type="string", length=30, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="porcentaje", type="float", nullable=true, options={"default" : 0})
     */
    private $porcentaje = 0;

    /**
     * @ORM\Column(name="base", type="float", nullable=true, options={"default" : 0})
     */
    private $base = 0;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaFk;

    /**
     * @ORM\Column(name="codigo_cuenta_devolucion_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaDevolucionFk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenImpuestoTipo", inversedBy="impuestosImpuestoTipoRel")
     * @ORM\JoinColumn(name="codigo_impuesto_tipo_fk", referencedColumnName="codigo_impuesto_tipo_pk")
     */
    protected $impuestoTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvItem", mappedBy="impuestoRetencionRel")
     */
    private $itemsImpuestoRetencionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvItem", mappedBy="impuestoRetencionCompraRel")
     */
    private $itemsImpuestoRetencionCompraRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvItem", mappedBy="impuestoIvaVentaRel")
     */
    private $itemsImpuestoIvaVentaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvItem", mappedBy="impuestoIvaCompraRel")
     */
    private $itemsImpuestoIvaCompraRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurItem", mappedBy="impuestoRetencionRel")
     */
    private $turItemsImpuestoRetencionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurItem", mappedBy="impuestoIvaVentaRel")
     */
    private $turItemsImpuestoIvaVentaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteFacturaConceptoDetalle", mappedBy="impuestoRetencionRel")
     */
    private $facturasConceptosDetallesImpuestoRetencionRel;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteFacturaConceptoDetalle", mappedBy="impuestoIvaVentaRel")
     */
    private $facturasConceptosDetallesImpuestoIvaVentaRel;

    /**
     * @return mixed
     */
    public function getCodigoImpuestoPk()
    {
        return $this->codigoImpuestoPk;
    }

    /**
     * @param mixed $codigoImpuestoPk
     */
    public function setCodigoImpuestoPk( $codigoImpuestoPk ): void
    {
        $this->codigoImpuestoPk = $codigoImpuestoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoImpuestoTipoFk()
    {
        return $this->codigoImpuestoTipoFk;
    }

    /**
     * @param mixed $codigoImpuestoTipoFk
     */
    public function setCodigoImpuestoTipoFk( $codigoImpuestoTipoFk ): void
    {
        $this->codigoImpuestoTipoFk = $codigoImpuestoTipoFk;
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
    public function setNombre( $nombre ): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * @param mixed $porcentaje
     */
    public function setPorcentaje( $porcentaje ): void
    {
        $this->porcentaje = $porcentaje;
    }

    /**
     * @return mixed
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * @param mixed $base
     */
    public function setBase( $base ): void
    {
        $this->base = $base;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * @param mixed $codigoCuentaFk
     */
    public function setCodigoCuentaFk( $codigoCuentaFk ): void
    {
        $this->codigoCuentaFk = $codigoCuentaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaDevolucionFk()
    {
        return $this->codigoCuentaDevolucionFk;
    }

    /**
     * @param mixed $codigoCuentaDevolucionFk
     */
    public function setCodigoCuentaDevolucionFk( $codigoCuentaDevolucionFk ): void
    {
        $this->codigoCuentaDevolucionFk = $codigoCuentaDevolucionFk;
    }

    /**
     * @return mixed
     */
    public function getImpuestoTipoRel()
    {
        return $this->impuestoTipoRel;
    }

    /**
     * @param mixed $impuestoTipoRel
     */
    public function setImpuestoTipoRel( $impuestoTipoRel ): void
    {
        $this->impuestoTipoRel = $impuestoTipoRel;
    }

    /**
     * @return mixed
     */
    public function getItemsImpuestoRetencionRel()
    {
        return $this->itemsImpuestoRetencionRel;
    }

    /**
     * @param mixed $itemsImpuestoRetencionRel
     */
    public function setItemsImpuestoRetencionRel( $itemsImpuestoRetencionRel ): void
    {
        $this->itemsImpuestoRetencionRel = $itemsImpuestoRetencionRel;
    }

    /**
     * @return mixed
     */
    public function getItemsImpuestoIvaVentaRel()
    {
        return $this->itemsImpuestoIvaVentaRel;
    }

    /**
     * @param mixed $itemsImpuestoIvaVentaRel
     */
    public function setItemsImpuestoIvaVentaRel( $itemsImpuestoIvaVentaRel ): void
    {
        $this->itemsImpuestoIvaVentaRel = $itemsImpuestoIvaVentaRel;
    }

    /**
     * @return mixed
     */
    public function getFacturasConceptosDetallesImpuestoRetencionRel()
    {
        return $this->facturasConceptosDetallesImpuestoRetencionRel;
    }

    /**
     * @param mixed $facturasConceptosDetallesImpuestoRetencionRel
     */
    public function setFacturasConceptosDetallesImpuestoRetencionRel( $facturasConceptosDetallesImpuestoRetencionRel ): void
    {
        $this->facturasConceptosDetallesImpuestoRetencionRel = $facturasConceptosDetallesImpuestoRetencionRel;
    }

    /**
     * @return mixed
     */
    public function getFacturasConceptosDetallesImpuestoIvaVentaRel()
    {
        return $this->facturasConceptosDetallesImpuestoIvaVentaRel;
    }

    /**
     * @param mixed $facturasConceptosDetallesImpuestoIvaVentaRel
     */
    public function setFacturasConceptosDetallesImpuestoIvaVentaRel( $facturasConceptosDetallesImpuestoIvaVentaRel ): void
    {
        $this->facturasConceptosDetallesImpuestoIvaVentaRel = $facturasConceptosDetallesImpuestoIvaVentaRel;
    }

    /**
     * @return mixed
     */
    public function getTurItemsImpuestoRetencionRel()
    {
        return $this->turItemsImpuestoRetencionRel;
    }

    /**
     * @param mixed $turItemsImpuestoRetencionRel
     */
    public function setTurItemsImpuestoRetencionRel($turItemsImpuestoRetencionRel): void
    {
        $this->turItemsImpuestoRetencionRel = $turItemsImpuestoRetencionRel;
    }

    /**
     * @return mixed
     */
    public function getTurItemsImpuestoIvaVentaRel()
    {
        return $this->turItemsImpuestoIvaVentaRel;
    }

    /**
     * @param mixed $turItemsImpuestoIvaVentaRel
     */
    public function setTurItemsImpuestoIvaVentaRel($turItemsImpuestoIvaVentaRel): void
    {
        $this->turItemsImpuestoIvaVentaRel = $turItemsImpuestoIvaVentaRel;
    }

    /**
     * @return mixed
     */
    public function getItemsImpuestoIvaCompraRel()
    {
        return $this->itemsImpuestoIvaCompraRel;
    }

    /**
     * @param mixed $itemsImpuestoIvaCompraRel
     */
    public function setItemsImpuestoIvaCompraRel($itemsImpuestoIvaCompraRel): void
    {
        $this->itemsImpuestoIvaCompraRel = $itemsImpuestoIvaCompraRel;
    }

    /**
     * @return mixed
     */
    public function getItemsImpuestoRetencionCompraRel()
    {
        return $this->itemsImpuestoRetencionCompraRel;
    }

    /**
     * @param mixed $itemsImpuestoRetencionCompraRel
     */
    public function setItemsImpuestoRetencionCompraRel($itemsImpuestoRetencionCompraRel): void
    {
        $this->itemsImpuestoRetencionCompraRel = $itemsImpuestoRetencionCompraRel;
    }




}

