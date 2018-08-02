<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_inventario_valorizado")
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvInventarioValorizadoRepository")
 */
class InvInventarioValorizado
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_inventario_valorizado_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoInventarioValorizadoPk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_item_fk", type="integer", nullable=true)
     */
    private $codigoItemFk;

    /**
     * @ORM\Column(name="saldo", type="integer", options={"default" : 0})
     */
    private $saldo = 0;

    /**
     * @ORM\Column(name="vr_costo", type="float", options={"default" : 0})
     */
    private $vrCosto = 0;

    /**
     * @ORM\Column(name="vr_costo_total", type="float", options={"default" : 0})
     */
    private $vrCostoTotal = 0;

    /**
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="inventariosValorizadosItemRel")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel;

    /**
     * @return mixed
     */
    public function getCodigoInventarioValorizadoPk()
    {
        return $this->codigoInventarioValorizadoPk;
    }

    /**
     * @param mixed $codigoInventarioValorizadoPk
     */
    public function setCodigoInventarioValorizadoPk($codigoInventarioValorizadoPk): void
    {
        $this->codigoInventarioValorizadoPk = $codigoInventarioValorizadoPk;
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
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * @param mixed $saldo
     */
    public function setSaldo($saldo): void
    {
        $this->saldo = $saldo;
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
    public function getVrCostoTotal()
    {
        return $this->vrCostoTotal;
    }

    /**
     * @param mixed $vrCostoTotal
     */
    public function setVrCostoTotal($vrCostoTotal): void
    {
        $this->vrCostoTotal = $vrCostoTotal;
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
