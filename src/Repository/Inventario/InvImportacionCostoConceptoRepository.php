<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvImportacionCostoConcepto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvImportacionCostoConceptoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvImportacionCostoConcepto::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function camposPredeterminados(){
        return $this->_em->createQueryBuilder()
            ->select('icc.codigoImportacionCostoConceptoPk AS ID')
            ->addSelect('icc.nombre')
            ->from(InvImportacionCostoConcepto::class,'icc')
            ->where('icc.codigoImportacionCostoConceptoPk IS NOT NULL')->getQuery()->execute();
    }
}