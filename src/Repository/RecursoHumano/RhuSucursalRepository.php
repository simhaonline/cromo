<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuSucursal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuSucursalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuSucursal::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuSucursal::class, 's')
            ->select('s.codigoSucursalPk')
            ->addSelect('s.nombre')
            ->addSelect('s.estadoActivo');

        if ($session->get('RhuSucursal_codigoSucursalPk')) {
            $queryBuilder->andWhere(" = '{$session->get('RhuSucursal_codigoSucursalPk')}'");
        }

        if ($session->get('RhuSucursal_nombre')) {
            $queryBuilder->andWhere("s.nombre = '%{$session->get('RhuSucursal_cnombre')}%'");
        }

        switch ($session->get('RhuSucursal_estadoActivo')) {
            case '0':
                $queryBuilder->andWhere("s.estadoActivo = 0");
                break;
            case '1':
                $queryBuilder->andWhere("s.estadoActivo= 1");
                break;
        }

        return $queryBuilder;
    }
}