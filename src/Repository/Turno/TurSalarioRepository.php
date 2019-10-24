<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurSalario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurSalarioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurSalario::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $nombre = null;
        $codigoSalario = null;

        if ($filtros) {
            $nombre = $filtros['nombre'] ?? null;
            $codigoSalario = $filtros['codigoSalario'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurSalario::class, 's')
            ->select('s.codigoSalarioPk')
            ->addSelect('s.nombre')
            ->addSelect('s.vrSalario')
            ->addSelect('s.vrHoraDiurna')
            ->addSelect('s.vrHoraNocturna')
            ->addSelect('s.vrTurnoDia')
            ->addSelect('s.vrTurnoNoche');

        if ($codigoSalario) {
            $queryBuilder->andWhere("s.codigoSalarioPk = '{$codigoSalario}'");
        }
        if ($nombre) {
            $queryBuilder->andWhere("s.nombre like '%{$nombre}%'");
        }
        $queryBuilder->addOrderBy('s.codigoSalarioPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }



}
