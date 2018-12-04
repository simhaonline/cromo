<?php

namespace App\Repository\General;

use App\Entity\General\GenImagen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GenImagen|null find($id, $lockMode = null, $lockVersion = null)
 * @method GenImagen|null findOneBy(array $criteria, array $orderBy = null)
 * @method GenImagen[]    findAll()
 * @method GenImagen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenImagenRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenImagen::class);
    }

//    /**
//     * @return GenImagen[] Returns an array of GenImagen objects
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
    public function findOneBySomeField($value): ?GenImagen
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
