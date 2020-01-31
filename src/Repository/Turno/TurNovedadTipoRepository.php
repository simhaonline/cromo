<?php


namespace App\Repository\Turno;


use App\Entity\Turno\TurNovedadTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TurNovedadTipoRepository  extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurNovedadTipo::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoNovedadTipo = null;
        $nombre= null;


        if ($filtros) {
            $codigoNovedadTipo = $filtros['codigoNovedadTipo'] ?? null;
            $nombre = $filtros['$nombre'] ?? null;
        }


        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurNovedadTipo::class, 'nt')
            ->select('nt.codigoNovedadTipoPk')
            ->addSelect('nt.nombre')
            ->addSelect('nt.estadoLicencia')
            ->addSelect('nt.estadoIncapacidad')
            ->addSelect('nt.estadoIngreso')
            ->addSelect('nt.estadoRetiro')
            ->addSelect('nt.estadoRetiro')
        ;
        if ($codigoNovedadTipo) {
            $queryBuilder->andWhere("nt.codigoIncidentePk = '{$codigoNovedadTipo}'");
        }
        if ($nombre) {
            $queryBuilder->andWhere("nt.nombre like '%{$nombre}%' ");
        }


        $queryBuilder->addOrderBy('nt.codigoNovedadTipoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }
}