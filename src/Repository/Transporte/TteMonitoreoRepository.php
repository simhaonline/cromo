<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteMonitoreo;
use App\Entity\Transporte\TteVehiculo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteMonitoreoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteMonitoreo::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT m.codigoMonitoreoPk
        FROM App\Entity\Monitoreo m'
        );

        return $query->execute();
    }

    public function generar($codigoVehiculo): bool
    {
        $em = $this->getEntityManager();
        $arVehiculo = $em->getRepository(TteVehiculo::class)->find($codigoVehiculo);
        $arMonitoreo = new TteMonitoreo();
        $arMonitoreo->setVehiculoRel($arVehiculo);
        $em->persist($arMonitoreo);
        $em->flush();

        return true;
    }
}