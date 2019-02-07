<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvCostoDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class InvCostoDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoCostoDetallePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="codigo_costo_detalle_pk" , type="integer")
     */
    private $codigoCostoDetallePk;

    /**
     * @ORM\Column(name="codigo_costo_fk" , type="integer")
     */
    private $codigoCostoFk;

    /**
     * @ORM\Column(name="codigo_item_fk" , type="integer")
     */
    private $codigoItemFk;

    /**
     * @ORM\Column(name="vr_costo",type="float")
     */
    private $vrCosto = 0;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Inventario\InvCosto", inversedBy="costosDetallesCostoRel")
     * @ORM\JoinColumn(name="codigo_costo_fk", referencedColumnName="codigo_costo_pk")
     */
    protected $costoRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="costosDetallesItemRel")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel;

    /**
     * @return mixed
     */
    public function getCodigoCostoDetallePk()
    {
        return $this->codigoCostoDetallePk;
    }

    /**
     * @param mixed $codigoCostoDetallePk
     */
    public function setCodigoCostoDetallePk($codigoCostoDetallePk): void
    {
        $this->codigoCostoDetallePk = $codigoCostoDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCostoFk()
    {
        return $this->codigoCostoFk;
    }

    /**
     * @param mixed $codigoCostoFk
     */
    public function setCodigoCostoFk($codigoCostoFk): void
    {
        $this->codigoCostoFk = $codigoCostoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoItemFk()
    {
        return $this->codigoItemFk;
    }

    /**
     * @param mixed $codigoItemFk
     */
    public function setCodigoItemFk($codigoItemFk): void
    {
        $this->codigoItemFk = $codigoItemFk;
    }

    /**
     * @return mixed
     */
    public function getVrCosto()
    {
        return $this->vrCosto;
    }

    /**
     * @param mixed $vrCosto
     */
    public function setVrCosto($vrCosto): void
    {
        $this->vrCosto = $vrCosto;
    }

    /**
     * @return mixed
     */
    public function getCostoRel()
    {
        return $this->costoRel;
    }

    /**
     * @param mixed $costoRel
     */
    public function setCostoRel($costoRel): void
    {
        $this->costoRel = $costoRel;
    }

    /**
     * @return mixed
     */
    public function getItemRel()
    {
        return $this->itemRel;
    }

    /**
     * @param mixed $itemRel
     */
    public function setItemRel($itemRel): void
    {
        $this->itemRel = $itemRel;
    }


}
