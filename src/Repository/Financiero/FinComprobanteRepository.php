<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinComprobante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class FinComprobanteRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FinComprobante::class);
    }


    public function camposPredeterminados()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(FinComprobante::class, 'c')
            ->select('c.codigoComprobantePk as ID')
            ->addSelect('c.nombre')
            ->where('c.codigoComprobantePk IS NOT NULL');
        return $queryBuilder->getQuery()->execute();
    }
}