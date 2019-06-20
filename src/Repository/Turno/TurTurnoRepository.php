<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurModalidad;
use App\Entity\Turno\TurTurno;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurTurnoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurTurno::class);
    }

    public function lista(){
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurTurno::class, 't')
            ->select('t');

        if ($session->get('filtroTurTurnoCodigoTurno') != '') {
            $queryBuilder->andWhere("t.codigoTurnoPk = '{$session->get('filtroTurTurnoCodigoTurno')}'");
        }
        if ($session->get('filtroTurTurnoNombre') != '') {
            $queryBuilder->andWhere("t.nombre LIKE '%{$session->get('filtroTurTurnoNombre')}%'");
        }
        return $queryBuilder;
    }

}
