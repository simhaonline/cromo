<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCiudad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteCiudadRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteCiudad::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteCiudad','c')
            ->select('c.codigoCiudadPk AS ID')
            ->addSelect('c.nombre AS NOMBRE')
            ->addSelect('c.codigoDivision AS DIVISION');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

}