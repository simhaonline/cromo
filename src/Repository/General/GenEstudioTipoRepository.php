<?php

namespace App\Repository\General;

use App\Entity\General\GenEstadoCivil;
use App\Entity\General\GenEstudioTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GenEstudioTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenEstudioTipo::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:General\GenEstudioTipo','et')
            ->select('et.codigoEstudioTipoPk AS ID')
            ->addSelect('et.nombre');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}