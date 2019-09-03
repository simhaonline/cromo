<?php


namespace App\Repository\Tesoreria;


use App\Entity\Tesoreria\TesMovimiento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TesMovimientoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesMovimiento::class);
    }

    public function  lista(){
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesMovimiento::class, 'c')
            ->select('c.codigoMovimientoPk')
            ->addSelect('ct.nombre')
            ->addSelect('c.numero')
            ->addSelect('c.fecha')
            ->leftJoin('c.movimientoTipoRel', 'ct');

        if ($session->get('filtroTesMovimientoMovimientoTipo') != "") {
            $queryBuilder->andWhere("c.codigoMovimientoTipoFk =  '{$session->get('filtroTesMovimientoMovimientoTipo')}'");
        }

        if ($session->get('filtroTesMovimientoCodigo') != "") {
            $queryBuilder->andWhere("c.codigoMovimientoPk =  '{$session->get('filtroTesMovimientoCodigo')}'");
        }

        if ($session->get('filtroTesMovimientoNumero') != "") {
            $queryBuilder->andWhere("c.numero =  '{$session->get('filtroTesMovimientoNumero')}'");
        }

        if ($session->get('filtroTesMovimientoFechaDesde') != null) {
            $queryBuilder->andWhere("c.fecha >= '{$session->get('filtroTesMovimientoFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroTesMovimientoFechaHasta') != null) {
            $queryBuilder->andWhere("c.fecha <= '{$session->get('filtroTesMovimientoFechaHasta')} 23:59:59'");
        }

        return $queryBuilder;
    }
}