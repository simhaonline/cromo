<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuSeleccion;
use App\Entity\RecursoHumano\RhuSeleccionReferencia;
use App\Entity\RecursoHumano\RhuSeleccionTipo;
use App\Entity\RecursoHumano\RhuSeleccionVisita;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuSeleccionReferenciaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuSeleccionReferencia::class);
    }


}