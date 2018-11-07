<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvImportacionDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method InvImportacionDetalle|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvImportacionDetalle|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvImportacionDetalle[]    findAll()
 * @method InvImportacionDetalle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvImportacionDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvImportacionDetalle::class);
    }

    // /**
    //  * @return InvImportacionDetalle[] Returns an array of InvImportacionDetalle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InvImportacionDetalle
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
