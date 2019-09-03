<?php


namespace App\Repository\Tesoreria;


use App\Entity\Tesoreria\TesCuenta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TesCuentaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesCuenta::class);
    }

    public function  lista(){
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesCuenta::class, 'c')
            ->select('c.codigoCuentaPk')
            ->addSelect('ct.nombre')
            ->addSelect('c.numero')
            ->addSelect('c.fecha')
            ->leftJoin('c.cuentaTipoRel', 'ct');

        if ($session->get('filtroTesCuentaCuentaTipo') != "") {
            $queryBuilder->andWhere("c.codigoCuentaTipoFk =  '{$session->get('filtroTesCuentaCuentaTipo')}'");
        }

        if ($session->get('filtroTesCuentaCodigo') != "") {
            $queryBuilder->andWhere("c.codigoCuentaPk =  '{$session->get('filtroTesCuentaCodigo')}'");
        }

        if ($session->get('filtroTesCuentaNumero') != "") {
            $queryBuilder->andWhere("c.numero =  '{$session->get('filtroTesCuentaNumero')}'");
        }

        if ($session->get('filtroTesCuentaFechaDesde') != null) {
            $queryBuilder->andWhere("c.fecha >= '{$session->get('filtroTesCuentaFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroTesCuentaFechaHasta') != null) {
            $queryBuilder->andWhere("c.fecha <= '{$session->get('filtroTesCuentaFechaHasta')} 23:59:59'");
        }

        return $queryBuilder;
    }
}