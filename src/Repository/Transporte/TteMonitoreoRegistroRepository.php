<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteMonitoreoRegistro;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteMonitoreoRegistroRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteMonitoreoRegistro::class);
    }

    public function monitoreo($codigoMonitoreo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteMonitoreoRegistro::class, 'mr');
        $queryBuilder
            ->select('mr.codigoMonitoreoRegistroPk')
            ->addSelect('mr.fecha')
            ->addSelect('mr.latitud')
            ->addSelect('mr.longitud')
            ->where('mr.codigoMonitoreoFk = ' . $codigoMonitoreo);
        $queryBuilder->orderBy('mr.fecha', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

    public function datosMapa($codigoMonitoreo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteMonitoreoRegistro::class, 'mr');
        $queryBuilder
            ->select('mr.codigoMonitoreoRegistroPk')
            ->addSelect('mr.fecha')
            ->addSelect('mr.latitud')
            ->addSelect('mr.longitud')
            ->where('mr.codigoMonitoreoFk = ' . $codigoMonitoreo);
        $queryBuilder->orderBy('mr.fecha', 'ASC');
        $datos = [];
        $arMonitoreoRegistros = $queryBuilder->getQuery()->getResult();
        foreach ($arMonitoreoRegistros as $arMonitoreoRegistro) {

            if ($arMonitoreoRegistro['latitud'] && $arMonitoreoRegistro['longitud']) {
                $datos[] = [
                    'codigo' => $arMonitoreoRegistro['codigoMonitoreoRegistroPk'],
                    'lat' => $arMonitoreoRegistro['latitud'],
                    'lng' => $arMonitoreoRegistro['longitud'],
                    'fecha' => $arMonitoreoRegistro['fecha']->format('Y-m-d H:i'),
                    'cliente' => "cliente",
                ];
            }
        }

        return $datos;
    }

}