<?php

namespace App\Repository\Crm;

use App\Entity\Crm\CrmVisita;
use App\Entity\Crm\CrmVisitaReporte;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CrmVisita|null find($id, $lockMode = null, $lockVersion = null)
 * @method CrmVisita|null findOneBy(array $criteria, array $orderBy = null)
 * @method CrmVisita[]    findAll()
 * @method CrmVisita[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CrmVisitaReporteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CrmVisitaReporte::class);
    }

    public function reporte($id){

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CrmVisitaReporte::class, 'r')
            ->select('r.codigoVisitaReportePk')
            ->addSelect('r.fecha')
            ->addSelect('r.reporte')
            ->where('r.codigoVisitaFk = ' . $id )
            ->orderBy('r.codigoVisitaReportePk', 'ASC')
            ->getQuery();
        $result = $queryBuilder->getResult();

        return $result;

    }

}
