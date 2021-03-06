<?php

namespace App\Repository\Turno;


use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurFestivo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurFestivoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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

    public function festivos($fechaDesde, $fechaHasta)
    {
        $em = $this->getEntityManager();
        $fecha = $fechaHasta;
        $nuevafecha = strtotime('+1 month', strtotime($fecha));
        $nuevafecha = date('Y-m-j', $nuevafecha);

        $dql = "SELECT f.fecha FROM App:Turno\TurFestivo f "
            . "WHERE f.fecha >= '{$fechaDesde}' AND f.fecha <= '{$nuevafecha}'";
        $query = $em->createQuery($dql);
        $arFestivo = $query->getResult();
        return $arFestivo;
    }

}
