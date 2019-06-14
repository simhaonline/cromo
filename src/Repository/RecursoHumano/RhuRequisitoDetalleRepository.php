<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuRequisito;
use App\Entity\RecursoHumano\RhuRequisitoCargo;
use App\Entity\RecursoHumano\RhuRequisitoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuRequisitoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuRequisitoDetalle::class);
    }

    public function lista($codigoRequisito)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuRequisitoDetalle::class, 'rd')
            ->select('rd.codigoRequisitoDetallePk')
            ->addSelect('rc.nombre AS concepto')
            ->addSelect('rc.general')
            ->addSelect('rd.tipo')
            ->addSelect('rd.estadoEntregado')
            ->addSelect('rd.estadoNoAplica')
            ->addSelect('rd.cantidad')
            ->addSelect('rd.cantidadEntregada')
            ->leftJoin('rd.requisitoRel', 'r')
            ->leftJoin('rd.requisitoConceptoRel', 'rc')
            ->where("rd.codigoRequisitoFk = {$codigoRequisito}");

        return $queryBuilder->getQuery()->getResult();
    }

//    public function entregar($arRemision, $arrDetallesSeleccionados)
//    {
//        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuRequisitoDetalle::class, 'rd')
//            ->select('rd.codigoRequisitoDetallePk')
//            ->addSelect('rc.nombre AS concepto')
//            ->addSelect('rc.general')
//            ->addSelect('rd.tipo')
//            ->addSelect('rd.estadoEntregado')
//            ->addSelect('rd.estadoNoAplica')
//            ->addSelect('rd.cantidad')
//            ->addSelect('rd.cantidadEntregada')
//            ->leftJoin('rd.requisitoRel', 'r')
//            ->leftJoin('rd.requisitoConceptoRel', 'rc')
//            ->where("rd.codigoRequisitoFk = {$codigoRequisito}");
//
//        return $queryBuilder->getQuery()->getResult();
//    }
}