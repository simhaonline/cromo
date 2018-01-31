<?php

namespace App\Repository;

use App\Entity\Monitoreo;
use App\Entity\Vehiculo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class MonitoreoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Monitoreo::class);
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
        $arVehiculo = $em->getRepository(Vehiculo::class)->find($codigoVehiculo);
        $arMonitoreo = new Monitoreo();
        $arMonitoreo->setVehiculoRel($arVehiculo);
        $em->persist($arMonitoreo);
        $em->flush();

        return true;
    }
}