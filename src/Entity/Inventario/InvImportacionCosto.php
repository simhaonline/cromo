<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvImportacionCostoRepository")
 */
class InvImportacionCosto
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="codigo_importacion_costo_pk" , type="integer")
     */
    private $codigoImportacionCostoPk;

    /**
     * @ORM\Column(name="codigo_item_fk" , type="integer")
     */
    private $codigoItemFk;

    /**
     * @ORM\Column(name="codigo_moneda_fk" , type="integer")
     */
    private $codigoMonedaFk;

    /**
     * @ORM\Column(name="precio" ,type="float")
     */
    private $precio = 0;

    /**
     * @ORM\Column(name="por_descuento" ,type="float")
     */
    private $porDescuento = 0;

    /**
     * @ORM\Column(name="vr_descuento" ,type="float")
     */
    private $vrDescuento = 0;

    /**
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad = 0;
}
