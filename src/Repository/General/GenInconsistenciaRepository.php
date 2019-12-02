<?php

namespace App\Repository\General;

use App\Entity\General\GenInconsistencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class GenInconsistenciaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenInconsistencia::class);
    }

    public function limpiar($modigoModelo, $codigo) {
        $em = $this->getEntityManager();
        $em->createQueryBuilder()
            ->delete(GenInconsistencia::class,'i')
            ->andWhere("i.codigoModeloFk = '" . $modigoModelo . "'")
            ->andWhere("i.codigoModelo = " . $codigo)
            ->getQuery()->execute();
    }

    public function lista($modelo, $codigoModelo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(GenInconsistencia::class, 'i')
            ->select('i.codigoInconsistenciaPk')
            ->addSelect('i.codigoReferencia')
            ->addSelect('i.referencia')
            ->addSelect('i.nombreReferencia')
            ->addSelect('i.descripcion')
            ->where("i.codigoModeloFk = '" . $modelo . "'")
            ->andWhere('i.codigoModelo = ' . $codigoModelo);
        return $queryBuilder;
    }

}