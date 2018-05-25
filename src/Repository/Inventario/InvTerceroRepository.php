<?php

namespace App\Repository\Inventario;


use App\Entity\Inventario\InvTercero;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvTerceroRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvTercero::class);
    }


    public function camposPredeterminados()
    {
        $qb = $this->getEntityManager()->createQueryBuilder()->from('App:Inventario\InvTercero','it');
        $qb
            ->select('it.codigoTerceroPk AS ID')
            ->addSelect('it.apellido1 AS APELLIDO1')
            ->addSelect('it.apellido2 AS APELLIDO2')
            ->addSelect('it.nombres AS NOMBRES')
            ->addSelect('it.numeroIdentificacion AS IDENTIFICACION')
            ->addSelect('it.direccion AS DIRECCION')
            ->where("it.codigoTerceroPk <> 0")
            ->orderBy('it.codigoTerceroPk', 'DESC');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();
    }
}