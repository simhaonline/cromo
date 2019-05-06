<?php

namespace App\Repository\Turno;


use App\Entity\Turno\TurPedidoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurPedidoTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurPedidoTipo::class);
    }

//    public function lista()
//    {
//        $session = new Session();
//        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedidoTipo::class, 'c')
//            ->select('c.codigoContratoPk');
////        if ($session->get('filtroTurNombreCliente') != '') {
////            $queryBuilder->andWhere("tc.nombreCorto LIKE '%{$session->get('filtroTurNombreCliente')}%' ");
////        }
////        if ($session->get('filtroTurNitCliente') != '') {
////            $queryBuilder->andWhere("tc.numeroIdentificacion = {$session->get('filtroTurNitCliente')} ");
////        }
////        if ($session->get('filtroTurCodigoCliente') != '') {
////            $queryBuilder->andWhere("tc.codigoClientePk = {$session->get('filtroTurCodigoCliente')} ");
////        }
//
//        return $queryBuilder;
//    }

}
