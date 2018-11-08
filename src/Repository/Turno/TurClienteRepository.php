<?php

namespace App\Repository\Turno;


use App\Entity\Turno\TurCliente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurClienteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurCliente::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurCliente::class, 'tc')
            ->select('tc.codigoClientePk')
            ->addSelect('tc.nombreCorto')
            ->addSelect('tc.numeroIdentificacion')
            ->addSelect('tc.telefono')
            ->addSelect('tc.movil')
            ->addSelect('tc.direccion')
            ->where('tc.codigoClientePk IS NOT NULL')
            ->orderBy('tc.codigoClientePk', 'ASC');
        if ($session->get('filtroTurNombreCliente') != '') {
            $queryBuilder->andWhere("tc.nombreCorto LIKE '%{$session->get('filtroTurNombreCliente')}%' ");
        }
        if ($session->get('filtroTurNitCliente') != '') {
            $queryBuilder->andWhere("tc.numeroIdentificacion = {$session->get('filtroTurNitCliente')} ");
        }
        if ($session->get('filtroTurCodigoCliente') != '') {
            $queryBuilder->andWhere("tc.codigoClientePk = {$session->get('filtroTurCodigoCliente')} ");
        }

        return $queryBuilder;
    }

}
