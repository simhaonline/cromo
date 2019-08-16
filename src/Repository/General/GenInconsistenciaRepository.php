<?php

namespace App\Repository\General;

use App\Entity\General\GenInconsistencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class GenInconsistenciaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenInconsistencia::class);
    }

    public function limpiar($modigoModelo, $codigo) {
        $em = $this->getEntityManager();
        $em->createQueryBuilder()
            ->delete(GenInconsistencia::class,'i')
            ->andWhere("i.codigoModeloFk = '" . $modigoModelo . "'")
            ->andWhere("i.codigo = " . $codigo)
            ->getQuery()->execute();
    }

}