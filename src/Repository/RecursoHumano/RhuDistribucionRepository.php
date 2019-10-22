<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuDistribucion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuDistribucionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuDistribucion::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from(RhuDistribucion::class,'gp')
            ->select('gp.codigoDistribucionPk AS ID')
            ->addSelect('gp.nombre AS NOMBRE');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
    
}