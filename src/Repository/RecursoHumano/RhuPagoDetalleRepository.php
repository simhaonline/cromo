<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuPagoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuPagoDetalle::class);
    }

    /**
     * @param $codigoPago
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista($codigoPago)
    {
        if ($codigoPago) {
            $query = $this->_em->createQueryBuilder()->from(RhuPagoDetalle::class, 'pd')
                ->leftJoin('pd.conceptoRel', 'c')
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
                ->addSelect('pd.vrDevengado')
                ->addSelect('pd.vrDeduccion')
                ->addSelect('pd.vrPagoOperado')
                ->addSelect('pd.vrIngresoBasePrestacion')
                ->addSelect('pd.vrIngresoBaseCotizacion')
                ->where("pd.codigoPagoFk = {$codigoPago}");
        } else {
            $query = null;
        }
        return $query ? $query->getQuery()->execute() : null;
    }

    /**
     * @param $codigoPago
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function creditos($codigoProgramacion)
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()->from(RhuPagoDetalle::class, 'pd')
            ->select('pd.codigoPagoDetallePk')
            ->addSelect('pd.codigoCreditoFk')
            ->addSelect('pd.vrPago')
            ->leftJoin('pd.pagoRel', 'p')
            ->where('pd.codigoCreditoFk IS NOT NULL')
            ->andWhere('p.codigoProgramacionFk = ' . $codigoProgramacion);
        return $query->getQuery()->execute();
    }
}