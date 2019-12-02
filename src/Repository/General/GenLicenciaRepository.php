<?php

namespace App\Repository\General;

use App\Entity\General\GenLicencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method GenLicencia|null find($id, $lockMode = null, $lockVersion = null)
 * @method GenLicencia|null findOneBy(array $criteria, array $orderBy = null)
 * @method GenLicencia[]    findAll()
 * @method GenLicencia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenLicenciaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenLicencia::class);
    }

//    /**
//     * @return GenLicencia[] Returns an array of GenLicencia objects
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
    public function findOneBySomeField($value): ?GenLicencia
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
