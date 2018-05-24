<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCierreSeleccionMotivo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuCierreSeleccionMotivoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuCierreSeleccionMotivo::class);
    }

}