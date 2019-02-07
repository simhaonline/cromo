<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvCostoDetalle;
use App\Entity\Inventario\InvImportacionDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;


class InvCostoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvCostoDetalle::class);
    }

    public function costo($codigo): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT cd.codigoCostoDetallePk,
                  cd.codigoCostoFk,
                  cd.codigoItemFk,
                  cd.vrCosto,
                  i.nombre as itemNombre,
                  i.referencia as itemReferencia,
                  m.nombre as itemMarcaNombre                         
        FROM App\Entity\Inventario\InvCostoDetalle cd
        LEFT JOIN cd.itemRel i 
        LEFT JOIN i.marcaRel m
        WHERE cd.codigoCostoFk = :codigoCosto'
        )->setParameter('codigoCosto', $codigo);

        return $query->execute();
    }
}
