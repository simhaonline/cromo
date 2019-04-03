<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteVehiculo;
use App\Entity\Transporte\TteVehiculoDisponible;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteVehiculoDisponibleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteVehiculoDisponible::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteVehiculoDisponible::class, 'vd')
            ->select('vd.codigoVehiculoDisponiblePk')
            ->leftJoin('vd.vehiculoRel','v')
            ->addSelect('vd.fecha')
            ->addSelect('vd.responsable')
            ->addSelect('vd.celular')
            ->addSelect('vd.estadoDespachado')
            ->addSelect('vd.fechaDespacho')
            ->addSelect('vd.estadoDescartado')
            ->addSelect('vd.motivo')
            ->addSelect('vd.fechaDescartado')
            ->addSelect('vd.comentario')
            ->addSelect('v.placa')
            ->where('vd.codigoVehiculoDisponiblePk IS NOT NULL')
            ->orderBy('vd.fecha', 'DESC');
        if ($session->get('filtroTteVehiculo')) {
            $queryBuilder->andWhere("vd.codigoVehiculoFk = '{$session->get('filtroTteVehiculo')}'");
        }
        if ($session->get('filtroTteVehiculoDisponibleFechaDesde') != null) {
            $queryBuilder->andWhere("vd.fecha >= '{$session->get('filtroTteVehiculoDisponibleFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroTteVehiculoDisponibleFechaHasta') != null) {
            $queryBuilder->andWhere("vd.fecha <= '{$session->get('filtroTteVehiculoDisponibleFechaHasta')} 23:59:59'");
        }
        switch ($session->get('filtroTteVehiculoDisponibleEstadoDespachado')) {
            case '0':
                $queryBuilder->andWhere("vd.estadoDespachado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("vd.estadoDespachado = 1");
                break;
        }
        switch ($session->get('filtroTteVehiculoDisponibleEstadoDescartado')) {
            case '0':
                $queryBuilder->andWhere("vd.estadoDescartado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("vd.estadoDescartado = 1");
                break;
        }

        return $queryBuilder;
    }

}