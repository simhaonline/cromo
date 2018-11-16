<?php

namespace App\Repository\Turno;


use App\Entity\Turno\TurContrato;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurContratoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurContrato::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurContrato::class, 'c')
            ->select('c.codigoContratoPk');
//        if ($session->get('filtroTurNombreCliente') != '') {
//            $queryBuilder->andWhere("tc.nombreCorto LIKE '%{$session->get('filtroTurNombreCliente')}%' ");
//        }
//        if ($session->get('filtroTurNitCliente') != '') {
//            $queryBuilder->andWhere("tc.numeroIdentificacion = {$session->get('filtroTurNitCliente')} ");
//        }
//        if ($session->get('filtroTurCodigoCliente') != '') {
//            $queryBuilder->andWhere("tc.codigoClientePk = {$session->get('filtroTurCodigoCliente')} ");
//        }

        return $queryBuilder;
    }

}
