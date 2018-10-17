<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuContratoTipo;
use App\Entity\RecursoHumano\RhuCostoGrupo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuCostoGrupoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuCostoGrupo::class);
    }

    public function camposPredeterminados(){
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuCostoGrupo::class,'rcg')
            ->select('rcg.codigoCostoGrupoPk as ID')
            ->addSelect('rcg.nombre')
            ->addSelect('rcg.codigoCentroCostoFk')
            ->where('rcg.codigoCostoGrupoPk IS NOT NULL');
        return $queryBuilder->getQuery()->execute();
    }
}