<?php

namespace App\Repository\Turno;


use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurFestivo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurFestivoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurFestivo::class);
    }

    public function fecha($fechaDesde, $fechaHasta)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurFestivo::class, 'f')
            ->select('f.fecha')
            ->where("f.fecha >= '${fechaDesde}' AND f.fecha <= '${fechaHasta}'");
        $arFestivos = $queryBuilder->getQuery()->getResult();
        return $arFestivos;
    }

    public function fechaArray($fechaDesde, $fechaHasta)
    {
        $festivos = $this->fecha($fechaDesde, $fechaHasta);
        return array_map(function ($arFestivo) {
            return $arFestivo['fecha']->format('Y-m-d');
        }, $festivos);
    }

}
