<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinRegistroInconsistencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class FinRegistroInconsistenciaRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FinRegistroInconsistencia::class);
    }

    public function lista($utilidad){
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinRegistroInconsistencia::class, 'ri')
            ->select('ri.codigoRegistroInconsistenciaPk')
            ->addSelect('ri.numeroPrefijo')
            ->addSelect('ri.numero')
            ->addSelect('ri.codigoComprobanteFk')
            ->addSelect('ri.descripcion')
            ->addSelect('ri.diferencia')
        ->where("ri.utilidad = '" . $utilidad . "'");
        return $queryBuilder;
    }

    public function limpiar($utilidad){
        $em = $this->_em;
        $em->createQueryBuilder()->delete(FinRegistroInconsistencia::class,'r')->andWhere("r.utilidad = '" . $utilidad . "'")->getQuery()->execute();
    }

}