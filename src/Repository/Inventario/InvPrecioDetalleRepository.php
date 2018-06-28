<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvPrecioDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
class InvPrecioDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvPrecioDetalle::class);
    }

    public function precioVenta($codigoPrecioVenta, $codigoItem): float
    {
        $precio = 0;
        if($codigoPrecioVenta && $codigoItem) {
            $arPrecioDetalle = $this->getEntityManager()->getRepository(InvPrecioDetalle::class)->findOneBy(array('codigoPrecioDetallePk' => $codigoPrecioVenta, 'codigoItemFk' => $codigoItem));
            if($arPrecioDetalle) {
                $precio = $arPrecioDetalle->getVrPrecio();
            }
        }

        return $precio;
    }

    public function listar(){

    }

}