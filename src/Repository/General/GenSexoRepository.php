<?php

namespace App\Repository\General;

use App\Entity\General\GenSexo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class GenSexoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenSexo::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:General\GenSexo','s')
            ->select('s.codigoSexoPk AS ID')
            ->addSelect('s.nombre');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}