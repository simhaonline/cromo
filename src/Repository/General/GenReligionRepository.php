<?php

namespace App\Repository\General;

use App\Entity\General\GenReligion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GenReligionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenReligion::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:General\GenReligion','r')
            ->select('r.codigoReligionPk AS ID')
            ->addSelect('r.nombre');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}