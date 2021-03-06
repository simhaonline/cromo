<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAspirante;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuAspiranteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuAspirante::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoAspirante = null;
        $nombre = null;
        $numeroIdentificacion = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;
        $estadoBloqueado = null;

        if ($filtros) {
            $codigoAspirante = $filtros['codigoAspirante'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
            $numeroIdentificacion = $filtros['numeroIdentificacion'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
            $estadoBloqueado = $filtros['estadoBloqueado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuAspirante::class, 'a')
            ->select('a.codigoAspirantePk')
            ->addSelect('a.numeroIdentificacion')
            ->addSelect('a.nombreCorto')
            ->addSelect('a.fechaNacimiento')
            ->addSelect('a.telefono')
            ->addSelect('a.celular')
            ->addSelect('a.correo')
            ->addSelect('a.direccion')
            ->addSelect('a.estadoAutorizado')
            ->addSelect('a.estadoAprobado')
            ->addSelect('a.estadoAnulado')
            ->addSelect('a.estadoBloqueado');
        if ($codigoAspirante) {
            $queryBuilder->andWhere("a.codigoAspirantePk = '{$codigoAspirante}'");
        }
        if ($numeroIdentificacion) {
            $queryBuilder->andWhere("a.numeroIdentificacion = '{$numeroIdentificacion}'");
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
        switch ($estadoBloqueado) {
            case '0':
                $queryBuilder->andWhere("a.estadoBloqueado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("a.estadoBloqueado = 1");
                break;
        }
        $queryBuilder->addOrderBy('a.codigoAspirantePk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function autorizar($arAspirante)
    {
        $em = $this->getEntityManager();
        if (!$arAspirante->getEstadoAutorizado()) {
            $arAspirante->setEstadoAutorizado(1);
            $em->persist($arAspirante);
            $em->flush();
        } else {
            Mensajes::error('El aspirante ya esta autorizado');
        }
    }

    public function desautorizar($arAspirante)
    {
        if ($arAspirante->getEstadoAutorizado() == 1 && $arAspirante->getEstadoAprobado() == 0) {
            $arAspirante->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arAspirante);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('El registro no se puede desautorizar');
        }
    }

    public function Anular($arAspirante)
    {
        $em = $this->getEntityManager();
        $arAspirante->setEstadoAnulado(1);
        $em->persist($arAspirante);
        $em->flush();
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