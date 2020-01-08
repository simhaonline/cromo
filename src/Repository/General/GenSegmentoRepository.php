<?php

namespace App\Repository\General;

use App\Entity\General\GenCuenta;
use App\Entity\General\GenSegmento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class GenSegmentoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenSegmento::class);
    }

    public function lista(){
        $em=$this->getEntityManager();

        $arGenSegmento=$em->createQueryBuilder()
            ->from('App:General\GenSegmento','seg')
            ->select('seg.codigoSegmentoPk')
            ->addSelect('seg.nombre')
            ->getQuery()->getResult();

        return $arGenSegmento;
    }
}