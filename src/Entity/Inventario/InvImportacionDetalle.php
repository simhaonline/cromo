<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvImportacionDetalleRepository")
 */
class InvImportacionDetalle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="codigo_importacion_detalle_pk" , type="integer")
     */
    private $codigoImportacionDetallePk;

    /**
     * @ORM\Column(name="codigo_importacion_fk" , type="integer")
     */
    private $codigoImportacionFk;

    /**
     * @ORM\Column(name="codigo_item_fk" , type="integer")
     */
    private $codigoItemFk;

    /**
     * @ORM\Column(name="codigo_moneda_fk" , type="integer")
     */
    private $codigoMonedaFk;

    /**
     * @ORM\Column(name="cantidad" , type="float")
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="precio_importacion",type="float")
     */
    private $precioImportacion = 0;

    /**
     * @ORM\Column(name="precio_local" ,type="float")
     */
    private $precioLocal = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Inventario\InvImportacion", inversedBy="importacionesDetallesImportacionRel")
     * @ORM\JoinColumn(name="codigo_importacion_fk", referencedColumnName="codigo_importacion_pk")
     */
    protected $importacionRel;



}
