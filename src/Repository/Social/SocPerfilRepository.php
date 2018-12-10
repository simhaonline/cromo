<?php

namespace App\Repository\Social;

use App\Entity\Social\SocPerfil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SocPerfil|null find($id, $lockMode = null, $lockVersion = null)
 * @method SocPerfil|null findOneBy(array $criteria, array $orderBy = null)
 * @method SocPerfil[]    findAll()
 * @method SocPerfil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocPerfilRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SocPerfil::class);
    }

    public function listaPerfil(){
        $em=$this->getEntityManager();

//        $arPerfil=$em->createQueryBuilder()
//            ->from('App:Social\SocPerfil','p')
//            ->addSelect('p.')
    }

//    /**
//     * @return SocPerfil[] Returns an array of SocPerfil objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SocPerfil
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
