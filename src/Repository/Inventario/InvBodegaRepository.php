<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvBodega;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvBodegaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvBodega::class);
    }

    public function lista(){
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvBodega::class,'b')
            ->select('b.codigoBodegaPk')
            ->addSelect('b.nombre')
            ->where('b.codigoBodegaPk IS NOT NULL');
        if($session->get('filtroInvBuscarBodegaCodigo') != ''){
            $queryBuilder->andWhere("b.codigoBodegaPk  = '{$session->get('filtroInvBuscarBodegaCodigo')}'");
            $session->set('filtroInvBuscarBodegaCodigo',null);
        }
        if($session->get('filtroInvBuscarBodegaNombre') != ''){
            $queryBuilder->andWhere("b.nombre LIKE '%{$session->get('filtroInvBuscarBodegaNombre')}%'");
            $session->set('filtroInvBuscarBodegaNombre',null);
        }
        return $queryBuilder;
    }

    public function camposPredeterminados(){
        $qb = $this->_em->createQueryBuilder()->from('App:Inventario\InvBodega','ib')
            ->select('ib.codigoBodegaPk as ID')
            ->addSelect('ib.nombre as NOMBRE');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}