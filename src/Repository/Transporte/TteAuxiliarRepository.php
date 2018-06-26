<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteAuxiliar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteAuxiliarRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteAuxiliar::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteAuxiliar','au')
            ->select('au.codigoAuxiliarPk AS ID')
            ->addSelect('au.nombreCorto AS NOMBRE_COMPLETO')
            ->addSelect('au.numeroIdentificacion AS IDENTIFICACION');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}