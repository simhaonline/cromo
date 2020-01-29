<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuSolicitud;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuSolicitudRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuSolicitud::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoSolicitud = null;
        $nombre = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoSolicitud = $filtros['codigoSolicitud'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuSolicitud::class, 's')
            ->select('s.codigoSolicitudPk')
            ->addSelect('s.fecha')
            ->addSelect('m.nombre AS motivo')
            ->addSelect('g.nombre AS grupo')
            ->addSelect('s.cantidadSolicitada')
            ->addSelect('s.nombre')
            ->addSelect('s.salarioFijo')
            ->addSelect('s.salarioVariable')
            ->addSelect('s.edadMinima')
            ->addSelect('s.edadMaxima')
            ->addSelect('s.estadoAprobado')
            ->addSelect('s.estadoAutorizado')
            ->addSelect('s.estadoAnulado')
            ->leftJoin('s.solicitudMotivoRel', 'm')
            ->leftJoin('s.grupoRel', 'g');
        if ($codigoSolicitud) {
            $queryBuilder->andWhere("s.codigoSolicitudPk = '{$codigoSolicitud}'");
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
        $queryBuilder->addOrderBy('s.codigoSolicitudPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function autorizar($arSolicitud)
    {
        $em = $this->getEntityManager();
        if (!$arSolicitud->getEstadoAutorizado()) {
            $arSolicitud->setEstadoAutorizado(1);
            $em->persist($arSolicitud);
            $em->flush();
        } else {
            Mensajes::error('La solicitud ya esta autorizado');
        }
    }

    public function desautorizar($arSolicitud)
    {
        if ($arSolicitud->getEstadoAutorizado()) {
            $arSolicitud->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arSolicitud);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('El documento no esta autorizado');

        }
    }

    public function Aprobar($arSolicitud)
    {
        $em = $this->getEntityManager();
        if (!$arSolicitud->getEstadoAprobado()) {
            $arSolicitud->setEstadoAprobado(1);
            $em->persist($arSolicitud);
            $em->flush();
        } else {
            Mensajes::error('El aspirante ya esta aprobado');
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
            ->from('App:RecursoHumano\RhuSolicitud', 's')
            ->select('s.codigoSolicitudPk AS ID')
            ->addSelect('s.fecha AS FECHA')
            ->addSelect('s.nombre AS NOMBRE')
            ->addSelect('s.cantidadSolicitada AS CANTIDAD')
            ->addSelect('s.estadoAutorizado AS AUTORIZADO')
            ->addSelect('s.estadoCerrado AS CERRADO');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}