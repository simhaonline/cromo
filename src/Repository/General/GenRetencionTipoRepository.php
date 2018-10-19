<?php

namespace App\Repository\General;

use App\Entity\General\GenRetencionTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GenRetencionTipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method GenRetencionTipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method GenRetencionTipo[]    findAll()
 * @method GenRetencionTipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenRetencionTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenRetencionTipo::class);
    }

//    /**
//     * @return GenTablaRetencion[] Returns an array of GenTablaRetencion objects
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
    public function findOneBySomeField($value): ?GenTablaRetencion
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
