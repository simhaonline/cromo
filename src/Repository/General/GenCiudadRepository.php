<?php

namespace App\Repository\General;

use App\Entity\General\GenCiudad;
use App\Entity\General\GenFormaPago;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class GenCiudadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenCiudad::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:General\GenCiudad','c')
            ->select('c.codigoCiudadPk AS ID')
            ->addSelect('c.nombre');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function apiWindowsLista($raw) {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(GenCiudad::class, 'c')
            ->select('c.codigoCiudadPk')
            ->addSelect('c.nombre');
        $ar = $queryBuilder->getQuery()->getResult();
        return $ar;
    }
}