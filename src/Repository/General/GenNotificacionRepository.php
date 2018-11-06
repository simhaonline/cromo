<?php

namespace App\Repository\General;

use App\Entity\General\GenNotificacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GenNotificacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method GenNotificacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method GenNotificacion[]    findAll()
 * @method GenNotificacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenNotificacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenNotificacion::class);
    }

//    /**
//     * @return GenNotificacion[] Returns an array of GenNotificacion objects
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
    public function findOneBySomeField($value): ?GenNotificacion
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
