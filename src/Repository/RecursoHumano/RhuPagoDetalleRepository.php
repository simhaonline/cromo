<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuPagoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuPagoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuPagoDetalle::class);
    }

    public function eliminarTodoDetalles($codigoProgramacion){
        $this->_em->createQueryBuilder()->delete(RhuPagoDetalle::class,'pd')
            ->leftJoin('pd.pagoRel','p')
            ->leftJoin('p.programacionDetalleRel','prd')
            ->where("prd.codigoProgramacionFk = {$codigoProgramacion}")->getQuery()->execute();
    }

    /**
     * @param $codigoPago
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista($codigoPago){
        return $this->_em->createQueryBuilder()->from(RhuPagoDetalle::class,'pd')
            ->leftJoin('pd.conceptoRel','c')
            ->select('pd.codigoPagoDetallePk')
            ->addSelect('pd.codigoConceptoFk')
            ->addSelect('c.nombre')
            ->addSelect('pd.detalle')
            ->addSelect('pd.porcentaje')
            ->addSelect('pd.horas')
            ->addSelect('pd.dias')
            ->addSelect('pd.vrHora')
            ->addSelect('pd.operacion')
            ->addSelect('pd.vrPago')
            ->addSelect('pd.vrIngresoBasePrestacion')
            ->addSelect('pd.vrIngresoBaseCotizacion')
            ->where("pd.codigoPagoFk = {$codigoPago}");
    }
}