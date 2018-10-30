<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinCentroCosto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class FinCentroCostoRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FinCentroCosto::class);
    }


    public function camposPredeterminados()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(FinCentroCosto::class, 'cc')
            ->select('cc.codigoCentroCostoPk as ID')
            ->addSelect('cc.nombre')
            ->addSelect('cc.estadoInactivo as Estado_inactivo')
            ->where('cc.codigoCentroCostoPk IS NOT NULL');
        return $queryBuilder->getQuery()->execute();
    }
}