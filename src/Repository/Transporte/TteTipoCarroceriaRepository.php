<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteTipoCarroceria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TteTipoCarroceriaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteTipoCarroceria::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteTipoCarroceria','cr')
            ->select('cr.codigoTipoCarroceriaPk AS ID')
            ->addSelect('cr.nombre AS NOMBRE');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }


}