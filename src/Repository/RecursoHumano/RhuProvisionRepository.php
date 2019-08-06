<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuPension;
use App\Entity\RecursoHumano\RhuProvision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuProvisionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuPension::class);
    }

    public function camposPredeterminados()
    {
        return $this->_em->createQueryBuilder()
            ->select('p.codigoPensionPk AS ID')
            ->addSelect('p.nombre')
            ->addSelect('p.porcentajeEmpleado')
            ->addSelect('p.porcentajeEmpleador')
            ->addSelect('p.codigoConceptoFk')
            ->addSelect('p.orden')
            ->from(RhuPension::class, 'p')
            ->where('p.codigoPensionPk IS NOT NULL')->getQuery()->execute();
    }

    public function lista()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuProvision::class, 'p')
            ->select('p.codigoProvisionPk')
            ->addSelect('p.anio')
            ->addSelect('p.mes')
            ->addSelect('p.vrSalud')
            ->addSelect('p.vrPension')
            ->addSelect('p.vrCaja')
            ->addSelect('p.vrRiesgos')
            ->addSelect('p.vrCesantias')
            ->addSelect('p.vrInteresesCesantias')
            ->addSelect('p.vrVacaciones')
            ->addSelect('p.vrPrimas')
            ->addSelect('p.vrIngresoBaseCotizacion')
            ->addSelect('p.vrIngresoBasePrestacion')
            ->addSelect('p.fechaDesde')
            ->addSelect('p.fechaHasta')
            ->addSelect('p.estadoAutorizado')
            ->addSelect('p.estadoAprobado')
        ->addSelect('p.estadoAnulado');
        return $queryBuilder;
    }
}