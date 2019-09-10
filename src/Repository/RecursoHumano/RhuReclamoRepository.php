<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmbargo;
use App\Entity\RecursoHumano\RhuReclamo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuReclamoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuReclamo::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuReclamo::class, 'r')
            ->select('r.codigoReclamoPk')
            ->addSelect('r.codigoReclamoConceptoFk')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.nombreCorto')
            ->addSelect('r.fecha')
            ->addSelect('r.fechaCierre')
            ->addSelect('r.responsable')
            ->addSelect('r.estadoAutorizado')
            ->addSelect('r.estadoAprobado')
            ->addSelect('r.estadoAnulado')
            ->leftJoin('r.empleadoRel', 'e');

        if ($session->get('RhuReclamo_codigoReclamoPk')) {
            $queryBuilder->andWhere("r.codigoReclamoPk = '{$session->get('RhuReclamo_codigoReclamoPk')}'");
        }

        if ($session->get('RhuReclamo_codigoReclamoConceptoFk')) {
            $queryBuilder->andWhere("r.codigoReclamoConceptoFk = '{$session->get('RhuReclamo_codigoReclamoConceptoFk')}'");
        }

        if ($session->get('RhuReclamo_codigoEmpleadoFk')) {
            $queryBuilder->andWhere("r.codigoEmpleadoFk = '{$session->get('RhuReclamo_codigoEmpleadoFk')}'");
        }

        if ($session->get('RhuReclamo_fechaDesde') != null) {
            $queryBuilder->andWhere("r.fechaDesde >= '{$session->get('RhuReclamo_fechaDesde')} 00:00:00'");
        }

        if ($session->get('RhuReclamo_fechaHasta') != null) {
            $queryBuilder->andWhere("r.fechaHasta <= '{$session->get('RhuReclamo_fechaHasta')} 23:59:59'");
        }
        
        switch ($session->get('RhuReclamo_estadoAutorizado')) {
            case '0':
                $queryBuilder->andWhere("r.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("r.estadoAutorizado = 1");
                break;
        }

        switch ($session->get('RhuReclamo_estadoAprobado')) {
            case '0':
                $queryBuilder->andWhere("r.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("r.estadoAprobado = 1");
                break;
        }

        switch ($session->get('RhuReclamo_estadoAnulado')) {
            case '0':
                $queryBuilder->andWhere("r.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("r.estadoAnulado = 1");
                break;
        }

        return $queryBuilder;

    }
}