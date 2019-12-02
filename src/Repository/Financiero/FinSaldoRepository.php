<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinSaldo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class FinSaldoRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FinSaldo::class);
    }

    public function balancePrueba()
    {

        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinSaldo::class, 's')
            ->select('s.codigoCuentaFk')
            ->addSelect('s.vrDebito')
            ->addSelect('s.vrCredito')
            ->addSelect('s.vrSaldoFinal')
            ->addSelect('s.vrSaldoAnterior')
            ->addSelect('c.nombre')
            ->leftJoin('s.cuentaRel', 'c');
        $fecha = new \DateTime('now');

        if ($session->get('filtroFinCuenta') != '') {
            $queryBuilder->andWhere("r.codigoCuentaFk = '{$session->get('filtroFinCuenta')}'");
        }
        if ($session->get('filtroFinSaldoFiltroFecha') == true) {
            if ($session->get('filtroFinSaldoFechaDesde') != null) {
                $queryBuilder->andWhere("r.fecha >= '{$session->get('filtroFinSaldoFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("r.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFinSaldoFechaHasta') != null) {
                $queryBuilder->andWhere("r.fecha <= '{$session->get('filtroFinSaldoFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("r.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        return $queryBuilder;
    }

}