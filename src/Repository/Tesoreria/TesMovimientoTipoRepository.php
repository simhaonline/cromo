<?php


namespace App\Repository\Tesoreria;


use App\Entity\Tesoreria\TesMovimientoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TesMovimientoTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesMovimientoTipo::class);
    }
}