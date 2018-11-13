<?php

namespace App\Repository\General;

use App\Entity\General\GenMoneda;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GenMonedaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenMoneda::class);
    }
//    public function camposPredeterminados(){
//        $qb = $this-> _em->createQueryBuilder()
//            ->from('App:General\GenCiudad','c')
//            ->select('c.codigoCiudadPk AS ID')
//            ->addSelect('c.nombre');
//        $query = $this->_em->createQuery($qb->getDQL());
//        return $query->execute();
//    }
}