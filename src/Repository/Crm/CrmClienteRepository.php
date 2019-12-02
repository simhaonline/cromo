<?php

namespace App\Repository\Crm;

use App\Entity\Crm\CrmCliente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class CrmClienteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CrmCliente::class);
    }


    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CrmCliente::class, 'c')
            ->select('c.codigoClientePk')
            ->addSelect('c.nombreCorto')
            ->addSelect('c.numeroIdentificacion')
            ->addSelect('c.digitoVerificacion')
            ->addSelect('ci.nombre as ciudad' )
            ->addSelect('c.direccion')
            ->leftJoin('c.ciudadRel', 'ci');
        if ($session->get('filtroCrmCodigoCliente') != '') {
            $queryBuilder->andWhere("c.codigoClientePk  = '{$session->get('filtroCrmCodigoCliente')}' ");
        }
        if ($session->get('filtroCrmNombreCliente') != '') {
            $queryBuilder->andWhere("c.nombreCorto LIKE '%{$session->get('filtroCrmNombreCliente')}%' ");
        }
        if ($session->get('filtroCrmClienteNombre') != '') {
            $queryBuilder->andWhere("c.nombreCorto LIKE '%{$session->get('filtroCrmClienteNombre')}%' ");
        }

        return $queryBuilder;
    }
}
