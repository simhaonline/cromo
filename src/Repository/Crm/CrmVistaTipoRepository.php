<?php

namespace App\Repository\Crm;

use App\Entity\Crm\CrmVistaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CrmVistaTipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method CrmVistaTipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method CrmVistaTipo[]    findAll()
 * @method CrmVistaTipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CrmVistaTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CrmVistaTipo::class);
    }

//    /**
//     * @return CrmVistaTipo[] Returns an array of CrmVistaTipo objects
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
    public function findOneBySomeField($value): ?CrmVistaTipo
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
