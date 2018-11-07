<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvImportacionCosto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method InvImportacionCosto|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvImportacionCosto|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvImportacionCosto[]    findAll()
 * @method InvImportacionCosto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvImportacionCostoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvImportacionCosto::class);
    }

    // /**
    //  * @return InvImportacionCosto[] Returns an array of InvImportacionCosto objects
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
    public function findOneBySomeField($value): ?InvImportacionCosto
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
