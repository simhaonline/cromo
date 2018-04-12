<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteRecibo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteReciboRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteRecibo::class);
    }

    public function relacionCaja($codigoRelacionCaja): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT r.codigoReciboPk, 
        r.fecha,
        r.vrFlete,
        r.vrManejo,
        r.vrTotal 
        FROM App\Entity\Transporte\TteRecibo r 
        WHERE r.codigoRelacionCajaFk = :codigoRelacionCaja'
        )->setParameter('codigoRelacionCaja', $codigoRelacionCaja);

        return $query->execute();

    }

}