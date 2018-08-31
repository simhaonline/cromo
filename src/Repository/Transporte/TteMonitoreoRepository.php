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
        $fecha =  new \DateTime('now');
        if($session->get('filtroTteMovMonitoreoFiltroFecha') == true){
            if ($session->get('filtroTteMovMonitoreoFechaDesde') != null) {
                $queryBuilder->andWhere("m.fechaInicio >= '{$session->get('filtroTteMovMonitoreoFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("m.fechaInicio >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroTteMovMonitoreoFechaHasta') != null) {
                $queryBuilder->andWhere("m.fechaFin <= '{$session->get('filtroTteMovMonitoreoFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("m.fechaFin <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }

        return $queryBuilder;
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