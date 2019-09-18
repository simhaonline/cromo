<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuSeleccion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuSeleccionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuSeleccion::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoSeleccion = null;
        $nombre = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoSeleccion = $filtros['codigoSeleccion'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }


        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuSeleccion::class, 's')
            ->select('s.codigoSeleccionPk')
            ->addSelect('s.numeroIdentificacion')
            ->addSelect('s.nombreCorto')
            ->addSelect('s.telefono')
            ->addSelect('s.celular')
            ->addSelect('s.correo')
            ->addSelect('s.direccion');
        if ($codigoSeleccion) {
            $queryBuilder->andWhere("s.codigoSeleccionPk = '{$codigoSeleccion}'");
        }
        if ($nombre) {
            $queryBuilder->andWhere("s.nombreCorto LIKE '%{$nombre}%'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("s.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("s.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("s.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("s.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("s.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("s.estadoAnulado = 1");
                break;
        }
        $queryBuilder->addOrderBy('s.codigoSeleccioPk', 'ASC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder()
            ->from('App:RecursoHumano\RhuSeleccion', 's')
            ->select('s');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

}