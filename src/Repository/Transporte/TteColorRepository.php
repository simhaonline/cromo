<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteColor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteColorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteColor::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteColor','cl')
            ->select('cl.codigoColorPk AS ID')
            ->addSelect('cl.nombre AS NOMBRE');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}