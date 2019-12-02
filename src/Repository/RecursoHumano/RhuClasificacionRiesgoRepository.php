<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuClasificacionRiesgo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuClasificacionRiesgoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuClasificacionRiesgo::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:RecursoHumano\RhuClasificacionRiesgo','cr')
            ->select('cr.codigoClasificacionRiesgoPk AS ID')
            ->addSelect('cr.nombre AS NOMBRE');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}