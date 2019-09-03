<?php


namespace App\Repository\Tesoreria;


use App\Entity\Tesoreria\TesCuentaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TesCuentaTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesCuentaTipo::class);
    }
}