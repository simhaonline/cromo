<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteConfiguracion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use SoapClient;

class TteConfiguracionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
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
}