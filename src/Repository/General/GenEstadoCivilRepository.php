<?php

namespace App\Repository\General;

use App\Entity\General\GenEstadoCivil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class GenEstadoCivilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenEstadoCivil::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:General\GenEstadoCivil','ec')
            ->select('ec.codigoEstadoCivilPk AS ID')
            ->addSelect('ec.nombre');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}