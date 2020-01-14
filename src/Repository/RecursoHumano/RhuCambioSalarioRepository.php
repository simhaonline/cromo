<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAdicional;
use App\Entity\RecursoHumano\RhuCambioSalario;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\Turno\TurPedido;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuCambioSalarioRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuCambioSalario::class);
    }

}
