<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteProducto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteProductoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteProducto::class);
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteProducto','p')
            ->select('p.codigoProductoPk AS ID')
            ->addSelect('p.nombre AS NOMBRE')
            ->addSelect('p.orden AS ORDEN');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function apiWindowsLista($raw) {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TteProducto::class, 'p')
            ->select('p.codigoProductoPk')
            ->addSelect('p.nombre');
        $arProducto = $queryBuilder->getQuery()->getResult();
        return $arProducto;
    }

}