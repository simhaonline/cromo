<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarReciboTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarReciboTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarReciboTipo::class);
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Cartera\CarReciboTipo','rt')
            ->select('rt.codigoReciboTipoPk AS ID');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

}