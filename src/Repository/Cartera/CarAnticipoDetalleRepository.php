<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarAnticipoDetalle;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarAnticipoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarAnticipoDetalle::class);
    }

    public function lista($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarAnticipoDetalle::class, 'ad');
        $queryBuilder
            ->select('ad.codigoAnticipoDetallePk')
            ->addSelect('ad.vrPago')
            ->addSelect('ac.nombre AS concepto')
            ->leftJoin('ad.anticipoConceptoRel', 'ac')
            ->where('ad.codigoAnticipoFk = ' . $id);

        return $queryBuilder;
    }
}