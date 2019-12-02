<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinCentroCosto;
use App\Entity\Financiero\FinCuenta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class FinCentroCostoRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FinCentroCosto::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinCentroCosto::class, 'cc')
            ->select('cc.codigoCentroCostoPk')
            ->addSelect('cc.nombre');
        if ($session->get('filtroFinBuscarCentroCostoCodigo') != '') {
            $queryBuilder->andWhere("cc.codigoCentroCostoPk LIKE '{$session->get('filtroFinBuscarCentroCostoCodigo')}%'");
        }
        if ($session->get('filtroFinBuscarCentroCostoNombre') != '') {
            $queryBuilder->andWhere("cc.nombre LIKE '%{$session->get('filtroFinBuscarCentroCostoNombre')}%'");
        }
        return $queryBuilder;
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