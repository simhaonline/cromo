<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_lote")
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvLoteRepository")
 */
class InvLote
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoLotePk;

    /**
     * @ORM\Column(name="codigo_item_fk", type="integer")
     */     
    private $codigoItemFk;     
    
    /**
     * @ORM\Column(name="lote_fk", type="string", length=40)
     */      
    private $loteFk;
    
    /**
     * @ORM\Column(name="codigo_bodega_fk", type="string", length=10)
     */     
    private $codigoBodegaFk;
    
    /**
     * @ORM\Column(name="cantidad_disponible", type="integer",options={"default" : 0}, nullable=true)
     */            
    private $cantidadDisponible = 0;

    /**
     * @ORM\Column(name="cantidad_existencia", type="integer",options={"default" : 0}, nullable=true)
     */
    private $cantidadExistencia = 0;

    /**
     * @ORM\Column(name="cantidad_remisionada", type="integer", nullable=true, options={"default" : 0})
     */
    private $cantidadRemisionada = 0;

    /**
     * @ORM\Column(name="fecha_vencimiento", type="date", nullable=true)
     */            
    private $fechaVencimiento;    

    /**
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="lotesItemRel")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel;        
    
    /**
     * @ORM\ManyToOne(targetEntity="InvBodega", inversedBy="lotesBodegaRel")
     * @ORM\JoinColumn(name="codigo_bodega_fk", referencedColumnName="codigo_bodega_pk")
     */
    protected $bodegaRel;

    /**
     * @return mixed
     */
    public function getCodigoLotePk()
    {
        return $this->codigoLotePk;
    }

    /**
     * @param mixed $codigoLotePk
     */
    public function setCodigoLotePk($codigoLotePk): void
    {
        $this->codigoLotePk = $codigoLotePk;
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
    public function getLoteFk()
    {
        return $this->loteFk;
    }

    /**
     * @param mixed $loteFk
     */
    public function setLoteFk($loteFk): void
    {
        $this->loteFk = $loteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoBodegaFk()
    {
        return $this->codigoBodegaFk;
    }

    /**
     * @param mixed $codigoBodegaFk
     */
    public function setCodigoBodegaFk($codigoBodegaFk): void
    {
        $this->codigoBodegaFk = $codigoBodegaFk;
    }

    /**
     * @return mixed
     */
    public function getCantidadDisponible()
    {
        return $this->cantidadDisponible;
    }

    /**
     * @param mixed $cantidadDisponible
     */
    public function setCantidadDisponible($cantidadDisponible): void
    {
        $this->cantidadDisponible = $cantidadDisponible;
    }

    /**
     * @return mixed
     */
    public function getCantidadExistencia()
    {
        return $this->cantidadExistencia;
    }

    /**
     * @param mixed $cantidadExistencia
     */
    public function setCantidadExistencia($cantidadExistencia): void
    {
        $this->cantidadExistencia = $cantidadExistencia;
    }

    /**
     * @return mixed
     */
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * @param mixed $fechaVencimiento
     */
    public function setFechaVencimiento($fechaVencimiento): void
    {
        $this->fechaVencimiento = $fechaVencimiento;
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

    /**
     * @return mixed
     */
    public function getBodegaRel()
    {
        return $this->bodegaRel;
    }

    /**
     * @param mixed $bodegaRel
     */
    public function setBodegaRel($bodegaRel): void
    {
        $this->bodegaRel = $bodegaRel;
    }

    /**
     * @return mixed
     */
    public function getCantidadRemisionada()
    {
        return $this->cantidadRemisionada;
    }

    /**
     * @param mixed $cantidadRemisionada
     */
    public function setCantidadRemisionada($cantidadRemisionada): void
    {
        $this->cantidadRemisionada = $cantidadRemisionada;
    }



}
