<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteContrato;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuAporteContratoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuAporteContrato::class);
    }
    
}