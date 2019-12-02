<?php

namespace App\Repository\General;

use App\Entity\General\GenFactura;
use App\Entity\General\GenFacturaDetalle;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Entity\Transporte\TteFactura;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class GenFacturaDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenFacturaDetalle::class);
    }

    /**
     * @param $arFactura InvMovimiento
     */
    public function insertarFacturaDetallesInventario($arFactura){
        $em = $this->_em;
        $arFacturaDetalles = $em->getRepository(InvMovimientoDetalle::class)->findBy(['codigoMovimientoFk' => $arFactura]);
        if($arFacturaDetalles){
            foreach ($arFacturaDetalles as $arFacturaDetalle) {
                $arFacturaDetalleNuevo = new GenFacturaDetalle();
                $arFacturaDetalleNuevo->setFacturaRel($arFactura);
                $arFacturaDetalleNuevo->setCodigoItem($arFacturaDetalle->getCodigoItemFk());
                $arFacturaDetalleNuevo->setNombreItem($arFacturaDetalle->getItemRel()->getNombre());
                $arFacturaDetalleNuevo->setVrPrecio($arFacturaDetalle->getVrPrecio());
                $arFacturaDetalleNuevo->setVrDescuento($arFacturaDetalle->getVrDescuento());
                $arFacturaDetalleNuevo->setVrIva($arFacturaDetalle->getVrIva());
                $arFacturaDetalleNuevo->setVrSubtotal($arFacturaDetalle->getVrSubtotal());
                $arFacturaDetalleNuevo->setVrNeto($arFacturaDetalle->getVrNeto());
                $em->persist($arFacturaDetalle);
            }
            $em->flush();

        }
    }
}