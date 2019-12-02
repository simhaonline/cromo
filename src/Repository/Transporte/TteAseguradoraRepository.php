<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteAseguradora;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TteAseguradoraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteAseguradora::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteAseguradora','a')
            ->select('a.codigoAseguradoraPk AS ID')
            ->addSelect('a.nombre AS NOMBRE')
            ->addSelect('a.numeroIdentificacion AS NIT')
            ->addSelect('a.digitoVerificacion AS DIGITO');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }


}