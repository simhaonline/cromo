<?php

namespace App\Repository\Cartera;


use App\Entity\Cartera\CarConfiguracion;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CarConfiguracionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarConfiguracion::class);
    }

    public function contabilizarRecibo(): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarConfiguracion::class, 'c')
            ->select('c.codigoCuentaRetencionIvaFk')
            ->addSelect('c.codigoCuentaAjustePesoFk')
            ->addSelect('c.codigoCuentaDescuentoFk')
            ->addSelect('c.codigoCuentaIndustriaComercioFk')
            ->addSelect('c.codigoCuentaRetencionFuenteFk')
            ->addSelect('c.contabilizarReciboFechaPago')
            ->where('c.codigoConfiguracionPk = 1');
        return $queryBuilder->getQuery()->getSingleResult();

    }
}