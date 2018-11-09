<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvImportacionCosto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvImportacionCostoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvImportacionCosto::class);
    }

    public function lista($codigoImportacion){
        return $this->_em->createQueryBuilder()
            ->select('iic.codigoImportacionCostoPk AS id')
            ->addSelect('iic.codigoImportacionCostoConceptoFk AS concepto')
            ->addSelect('c.nombre')
            ->addSelect('iic.vrValor')
            ->from(InvImportacionCosto::class,'iic')
            ->leftJoin('iic.importacionCostoConceptoRel','c')
            ->where("iic.codigoImportacionFk = {$codigoImportacion}");
    }

    /**
     * @param $codigoImportacion
     * @return mixed
     */
    public function totalCostos($codigoImportacion){
        return $this->_em->createQueryBuilder()
            ->select('SUM(iic.vrValor)')
            ->from(InvImportacionCosto::class,'iic')
            ->where("iic.codigoImportacionFk = {$codigoImportacion} ")->getQuery()->execute();
    }
}
