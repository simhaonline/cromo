<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteMonitoreo;
use App\Entity\Transporte\TteVehiculo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteMonitoreoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteMonitoreo::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteMonitoreo::class, 'm')
            ->select('m.codigoMonitoreoPk')
            ->addSelect('m.fechaInicio')
            ->addSelect('m.fechaFin')
            ->addSelect('m.codigoVehiculoFk')
            ->addSelect('m.soporte')
            ->addSelect('m.codigoDespachoFk')
            ->where('m.codigoMonitoreoPk <> 0')
        ->orderBy('m.fechaRegistro', 'DESC');

        return $queryBuilder->getQuery()->execute();
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