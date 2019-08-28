<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuConfiguracionCuenta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuConfiguracionCuentaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuConfiguracionCuenta::class);
    }


}