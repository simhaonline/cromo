<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuItemRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuItem
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
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenImpuesto", inversedBy="turItemsImpuestoRetencionRel")
     * @ORM\JoinColumn(name="codigo_impuesto_retencion_fk",referencedColumnName="codigo_impuesto_pk")
     */
    protected $impuestoRetencionRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenImpuesto", inversedBy="turItemsImpuestoIvaVentaRel")
     * @ORM\JoinColumn(name="codigo_impuesto_iva_venta_fk",referencedColumnName="codigo_impuesto_pk")
     */
    protected $impuestoIvaVentaRel;

//    /**
//     * @ORM\OneToMany(targetEntity="RhuContratoDetalle", mappedBy="itemRel")
//     */
//    protected $contratosDetallesItemRel;
//
//    /**
//     * @ORM\OneToMany(targetEntity="RhuPedidoDetalle", mappedBy="itemRel")
//     */
//    protected $pedidosDetallesItemRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuFacturaDetalle", mappedBy="itemRel")
     */
    protected $facturasDetallesItemRel;


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



}

