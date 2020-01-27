<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuSeleccion;
use App\Entity\RecursoHumano\RhuSeleccionAntecedente;
use App\Entity\RecursoHumano\RhuSeleccionPrueba;
use App\Entity\RecursoHumano\RhuSeleccionTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuSeleccionAntecedenteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
            parent::__construct($registry, RhuSeleccionAntecedente::class);
    }


}