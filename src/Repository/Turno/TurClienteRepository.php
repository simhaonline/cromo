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

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurCliente::class, 'c')
            ->select('c.codigoClientePk')
            ->addSelect('c.nombreCorto')
            ->addSelect('c.numeroIdentificacion')
            ->addSelect('c.telefono')
            ->addSelect('c.movil')
            ->addSelect('c.direccion')
            ->where('c.codigoClientePk <> 0');
//            ->orderBy('c.codigoClientePk', 'ASC');
        if ($session->get('filtroTurClienteNombre') != '') {
            $queryBuilder->andWhere("c.nombreCorto LIKE '%{$session->get('filtroTurClienteNombre')}%' ");
        }
        if ($session->get('filtroTurClienteNit') != '') {
            $queryBuilder->andWhere("c.numeroIdentificacion = {$session->get('filtroTurClienteIdentificacion')} ");
        }
        if ($session->get('filtroTurClienteCodigo') != '') {
            $queryBuilder->andWhere("c.codigoClientePk = {$session->get('filtroTurClienteCodigo')} ");
        }
        return $queryBuilder;
    }

}
