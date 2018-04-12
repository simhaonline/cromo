<?php

namespace App\Repository\Transporte;


use App\Entity\Transporte\TteRelacionCaja;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteRelacionCajaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteRelacionCaja::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT rc.codigoRelacionCajaPk, 
        rc.fecha,
        rc.vrTotal
        FROM App\Entity\Transporte\TteRelacionCaja rc         
        ORDER BY rc.codigoRelacionCajaPk DESC'
        );
        return $query->execute();

    }

}