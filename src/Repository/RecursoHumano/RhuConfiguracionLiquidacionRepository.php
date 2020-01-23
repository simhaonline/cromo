<?php

namespace App\Repository\RecursoHumano;

use App\Entity\Financiero\FinCuenta;
use App\Entity\RecursoHumano\RhuConfiguracionLiquidacion;
use App\Entity\RecursoHumano\RhuConfiguracionProvision;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuConfiguracionLiquidacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuConfiguracionLiquidacion::class);
    }


}