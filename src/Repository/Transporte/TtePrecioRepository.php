<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TtePrecio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TtePrecioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TtePrecio::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT p.codigoPrecioPk, 
        p.nombre
        FROM App\Entity\Transporte\TtePrecio p 
        ORDER BY p.codigoPrecioPk DESC '
        );
        return $query->execute();
    }

}