<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuAccidenteMecanismoAccidente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuAccidenteMecanismoAccidenteRepository  extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuAccidenteMecanismoAccidente::class);
    }
}