<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAspirante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuAspiranteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuAspirante::class);
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:RecursoHumano\RhuAspirante','a')
            ->select('a');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}