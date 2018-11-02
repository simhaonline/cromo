<?php

namespace App\Repository\General;

use App\Entity\General\GenModulo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GenModulo|null find($id, $lockMode = null, $lockVersion = null)
 * @method GenModulo|null findOneBy(array $criteria, array $orderBy = null)
 * @method GenModulo[]    findAll()
 * @method GenModulo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenModuloRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenModulo::class);
    }

//    /**
//     * @return GenModulo[] Returns an array of GenModulo objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GenModulo
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
