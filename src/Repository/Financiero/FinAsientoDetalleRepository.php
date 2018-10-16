<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinAsientoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class FinAsientoDetalleRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FinAsientoDetalle::class);
    }

    public function asiento($codigo){
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinAsientoDetalle::class,'ad')
            ->select('ad.codigoAsientoDetallePk')
            ->addSelect('ad.codigoCuentaFk')
            ->addSelect('ad.codigoCentroCostoFk')
            ->addSelect('ad.codigoTerceroFk')
            ->addSelect('ad.vrDebito')
            ->addSelect('ad.vrCredito')
            ->where('ad.codigoAsientoFk = ' . $codigo);
        return $queryBuilder->getQuery();
    }

}