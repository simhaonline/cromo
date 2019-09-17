<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAspirante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuAspiranteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuAspirante::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoAspirante = null;
        $nombre = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoAspirante = $filtros['codigoAspirante'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuAspirante::class, 'a')
            ->select('a.codigoAspirantePk')
            ->addSelect('a.numeroIdentificacion')
            ->addSelect('a.nombreCorto')
            ->addSelect('a.telefono')
            ->addSelect('a.celular')
            ->addSelect('a.correo')
            ->addSelect('a.direccion')
            ->addSelect('a.estadoAutorizado')
            ->addSelect('a.estadoAprobado');
        if ($codigoAspirante) {
            $queryBuilder->andWhere("a.codigoAspirantePk = '{$codigoAspirante}'");
        }
        if ($nombre) {
            $queryBuilder->andWhere("a.nombreCorto LIKE '%{$nombre}%'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("a.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("a.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("a.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("a.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("a.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("a.estadoAnulado = 1");
                break;
        }
        $queryBuilder->addOrderBy('a.codigoAspirantePk', 'ASC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder()
            ->from('App:RecursoHumano\RhuAspirante', 'a')
            ->select('a');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}