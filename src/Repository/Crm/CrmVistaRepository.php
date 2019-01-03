<?php

namespace App\Repository\Crm;

use App\Entity\Crm\CrmVista;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CrmVista|null find($id, $lockMode = null, $lockVersion = null)
 * @method CrmVista|null findOneBy(array $criteria, array $orderBy = null)
 * @method CrmVista[]    findAll()
 * @method CrmVista[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CrmVistaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CrmVista::class);
    }

//    /**
//     * @return CrmVista[] Returns an array of CrmVista objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CrmVista
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
