<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteNovedadTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteNovedadTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteNovedadTipo::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteNovedadTipo','nt')
            ->select('nt.codigoNovedadTipoPk AS ID')
            ->addSelect('nt.nombre');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}