<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinRegistroInconsistencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class FinRegistroInconsistenciaRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FinRegistroInconsistencia::class);
    }

    public function lista(){
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinRegistroInconsistencia::class, 'ri')
            ->select('ri');
        return $queryBuilder;
    }

    public function limpiar(){
        $em = $this->_em;
        $em->createQueryBuilder()->delete(FinRegistroInconsistencia::class,'r')->getQuery()->execute();
    }

}