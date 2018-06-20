<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteMonitoreoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteMonitoreoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteMonitoreoDetalle::class);
    }

    public function monitoreo($codigoMonitoreo): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT md.codigoMonitoreoDetallePk
            
        FROM App\Entity\Transporte\TteMonitoreoDetalle md 
        WHERE md.codigoMonitoreoFk = :codigoMonitoreo'
        )->setParameter('codigoMonitoreo', $codigoMonitoreo);

        return $query->execute();
    }

}