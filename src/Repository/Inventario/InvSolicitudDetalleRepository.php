<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvSolicitudDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvSolicitudDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvSolicitudDetalle::class);
    }

    public function lista(){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb ->select('sd,ir.nombre')
            ->from('App:Inventario\InvSolicitudDetalle','sd')
            ->join('sd.itemRel','ir')
            ->where('sd.codigoSolicitudDetallePk <> 0')
            ->orderBy('sd.codigoSolicitudPk','DESC');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();
    }
}