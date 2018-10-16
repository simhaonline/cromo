<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinCuenta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class FinCuentaRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FinCuenta::class);
    }

    public function lista(){
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinCuenta::class,'c')
            ->select('c.codigoCuentaPk')
            ->addSelect('c.nombre')
            ->where('c.codigoCuentaPk IS NOT NULL');
        if($session->get('filtroFinBuscarCuentaCodigo') != ''){
            $queryBuilder->andWhere("c.codigoCuentaPk  = '{$session->get('filtroFinBuscarCuentaCodigo')}'");
        }
        if($session->get('filtroFinBuscarCuentaNombre') != ''){
            $queryBuilder->andWhere("c.nombre LIKE '%{$session->get('filtroFinBuscarCuentaNombre')}%'");
        }
        return $queryBuilder;
    }

}