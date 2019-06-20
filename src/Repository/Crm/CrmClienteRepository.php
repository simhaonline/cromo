<?php

namespace App\Repository\Crm;

use App\Entity\Crm\CrmCliente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class CrmClienteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CrmCliente::class);
    }


    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CrmCliente::class, 'c')
            ->select('c.codigoClientePk')
            ->addSelect('c.nombreCorto');
        if ($session->get('filtroCrmCodigoCliente') != '') {
            $queryBuilder->andWhere("c.codigoClientePk  = '{$session->get('filtroCrmCodigoCliente')}' ");
        }
        if ($session->get('filtroCrmNombreCliente') != '') {
            $queryBuilder->andWhere("c.nombreCorto LIKE '%{$session->get('filtroCrmNombreCliente')}%' ");
        }
        return $queryBuilder;
    }
}
