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

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoSucursal = null;
        $nombre = null;
        $estadoActivo = null;

        if ($filtros) {
            $codigoSucursal = $filtros['codigoSucursal'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
            $estadoActivo = $filtros['estadoActivo'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuSucursal::class, 's')
            ->select('s.codigoSucursalPk')
            ->addSelect('s.nombre')
            ->addSelect('s.estadoActivo');

        if ($codigoSucursal) {
            $queryBuilder->andWhere("s.codigoSucursalPk = '{$codigoSucursal}'");
        }

        if ($nombre) {
            $queryBuilder->andWhere("s.nombre LIKE '%{$nombre}%'");
        }

        switch ($estadoActivo) {
            case '0':
                $queryBuilder->andWhere("s.estadoActivo = 0");
                break;
            case '1':
                $queryBuilder->andWhere("s.estadoActivo= 1");
                break;
        }

        $queryBuilder->addOrderBy('s.codigoSucursalPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }
}