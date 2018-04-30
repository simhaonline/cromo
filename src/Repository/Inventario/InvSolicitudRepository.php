<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvSolicitud;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvSolicitudRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvSolicitud::class);
    }

    public function lista(){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb ->select('s.codigoSolicitudPk')
            ->addSelect('s.numero')
            ->addSelect('s.fecha')
            ->from('App:Inventario\InvSolicitud','s')
            ->where('s.codigoSolicitudPk <> 0')
            ->orderBy('s.codigoSolicitudPk','DESC');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();
    }
}