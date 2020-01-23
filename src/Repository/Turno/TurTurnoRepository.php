<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurModalidad;
use App\Entity\Turno\TurTurno;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurTurnoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurTurno::class);
    }

    public function lista(){
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurTurno::class, 't')
            ->select('t.codigoTurnoPk')
            ->addSelect('t.nombre ')
            ->addSelect('t.horas ')
            ->addSelect('t.horaDesde ')
            ->addSelect('t.horaHasta  ')
            ->addSelect('t.horasDiurnas ')
            ->addSelect('t.horasNocturnas ')
            ->addSelect('t.novedad ')
            ->addSelect('t.descanso ')
            ->addSelect('t.incapacidad ')
            ->addSelect('t.licencia ')
            ->addSelect('t.vacacion ')
            ->addSelect('t.ingreso ')
            ->addSelect('t.retiro ')
            ->addSelect('t.induccion ')
            ->addSelect('t.ausentismo ')
            ->addSelect('t.complementario')
            ->addSelect('t.dia ')
            ->addSelect('t.noche ');
        if ($session->get('filtroTurTurnoCodigoTurno') != '') {
            $queryBuilder->andWhere("t.codigoTurnoPk = '{$session->get('filtroTurTurnoCodigoTurno')}'");
        }
        if ($session->get('filtroTurTurnoNombre') != '') {
            $queryBuilder->andWhere("t.nombre LIKE '%{$session->get('filtroTurTurnoNombre')}%'");
        }
        return $queryBuilder->getQuery()->getResult();
    }

}
