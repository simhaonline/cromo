<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteMarca;
use App\Entity\Transporte\TteTipoCombustible;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteTipoCombustibleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteTipoCombustible::class);
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteTipoCombustible','tc')
            ->select('tc.codigoTipoCombustiblePk AS ID')
            ->addSelect('tc.nombre AS NOMBRE');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

}