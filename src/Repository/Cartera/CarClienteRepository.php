<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarCliente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarClienteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarCliente::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()->from(CarCliente::class,'cc');
        $qb->select('cc.codigoClientePk')
            ->addSelect('cc.digitoVerificacion')
            ->addSelect('cc.nit')
            ->addSelect('cc.nombreCorto')
            ->where('cc.codigoClientePk <> 0')
            ->orderBy('cc.codigoClientePk','DESC');
        $query = $qb->getDQL();
        $query = $em->createQuery($query);

        return $query->execute();
    }

}