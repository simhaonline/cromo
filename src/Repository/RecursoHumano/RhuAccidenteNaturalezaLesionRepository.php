<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuAccidenteNaturalezaLesion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuAccidenteNaturalezaLesionRepository  extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuAccidenteNaturalezaLesion::class);
    }
}