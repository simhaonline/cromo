<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteConfiguracion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use SoapClient;

class TteConfiguracionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteConfiguracion::class);
    }

    public function liquidarDespacho(): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteConfiguracion::class, 'c')
            ->select('c.vrBaseRetencionFuente')
            ->addSelect('c.porcentajeIndustriaComercio')
            ->addSelect('c.porcentajeRetencionFuente')
            ->where('c.codigoConfiguracionPk = 1');
        return $queryBuilder->getQuery()->getSingleResult();

    }

    public function retencionTransporte(): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteConfiguracion::class, 'c')
            ->select('c.codigoImpuestoRetencionTransporteFk')
            ->where('c.codigoConfiguracionPk = 1');
        return $queryBuilder->getQuery()->getSingleResult();

    }

    public function contabilizarIntermediacion(): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteConfiguracion::class, 'c')
            ->select('c.codigoComprobanteIntermediacionFk')
            ->where('c.codigoConfiguracionPk = 1');
        return $queryBuilder->getQuery()->getSingleResult();

    }

    public function contabilizar(): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteConfiguracion::class, 'c')
            ->select('c.contabilizarDespachoTipo')
            ->where('c.codigoConfiguracionPk = 1');
        return $queryBuilder->getQuery()->getSingleResult();

    }
}