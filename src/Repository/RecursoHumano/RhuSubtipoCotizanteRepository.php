<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuSubtipoCotizante;
use App\Entity\RecursoHumano\RhuTipoCotizante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuSubtipoCotizanteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuSubtipoCotizante::class);
    }
}