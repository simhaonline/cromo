<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteAseguradora;
use App\Entity\Transporte\TteUbicacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TteUbicacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteUbicacion::class);
    }

    public function datosMapa($codigoDespacho)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteUbicacion::class, 'u');
        $queryBuilder
            ->select('u.codigoUbicacionPk')
            ->addSelect('u.fecha')
            ->addSelect('u.latitud')
            ->addSelect('u.longitud')
            ->where('u.codigoDespachoFk = ' . $codigoDespacho);
        $queryBuilder->orderBy('u.fecha', 'DESC')
        ->setMaxResults(10);

        $datos = [];
        $arUbicaciones = $queryBuilder->getQuery()->getResult();
        foreach ($arUbicaciones as $arUbicacion) {

            if ($arUbicacion['latitud'] && $arUbicacion['longitud']) {
                $datos[] = [
                    'codigo' => $arUbicacion['codigoUbicacionPk'],
                    'lat' => $arUbicacion['latitud'],
                    'lng' => $arUbicacion['longitud'],
                    'fecha' => $arUbicacion['fecha']->format('Y-m-d H:i'),
                    'cliente' => "cliente",
                ];
            }
        }

        return $datos;
    }

}