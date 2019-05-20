<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuDotacionDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuDotacionDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuDotacionDetalle::class);
    }
}