<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuGrupo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuGrupoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuGrupo::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from(RhuGrupo::class,'gp')
            ->select('gp.codigoGrupoPk AS ID')
            ->addSelect('gp.nombre AS NOMBRE');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}