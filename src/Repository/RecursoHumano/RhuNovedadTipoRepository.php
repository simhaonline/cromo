<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuNovedadTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuNovedadTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuNovedadTipo::class);
    }

    public function camposPredeterminados(){
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuNovedadTipo::class,'nt')
            ->select('nt.codigoNovedadTipoPk AS ID')
            ->addSelect('nt.nombre')
            ->where('nt.codigoNovedadTipoPk IS NOT NULL');
        return $queryBuilder->getQuery()->execute();
    }

}