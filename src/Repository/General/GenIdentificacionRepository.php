<?php

namespace App\Repository\General;

use App\Entity\General\GenIdentificacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class GenIdentificacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenIdentificacion::class);
    }

    public function apiWindowsLista($raw) {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(GenIdentificacion::class, 'i')
            ->select('i.codigoIdentificacionPk')
            ->addSelect('i.nombre');
        $ar = $queryBuilder->getQuery()->getResult();
        return $ar;
    }
}