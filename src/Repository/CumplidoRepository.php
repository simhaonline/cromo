<?php

namespace App\Repository;

use App\Entity\Cumplido;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CumplidoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Cumplido::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT c.codigoCumplidoPk, 
        g.cantidad        
        FROM App\Entity\Cumplido c'
        );
        return $query->execute();
    }

}