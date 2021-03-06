<?php

namespace App\Repository\Transporte;


use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteFacturaDetalleConcepto;
use App\Entity\Transporte\TteFacturaPlanilla;
use App\Entity\Transporte\TteGuia;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TteFacturaDetalleConceptoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteFacturaDetalleConcepto::class);
    }

    public function listaFacturaDetalle($codigoFactura)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFacturaDetalleConcepto::class, 'fdc');
        $queryBuilder
            ->select('fdc.codigoFacturaDetalleConceptoPk')
            ->addSelect('fdc.cantidad')
            ->addSelect('fdc.vrPrecio')
            ->addSelect('fdc.vrIva')
            ->addSelect('fdc.vrSubtotal')
            ->addSelect('fdc.vrTotal')
            ->addSelect('fdc.porcentajeIva')
            ->addSelect('fcd.nombre AS concepto')
            ->addSelect('fcd.codigoImpuestoIvaVentaFk')
            ->addSelect('fcd.codigoImpuestoRetencionFk')
            ->addSelect('fdc.porcentajeIva')
            ->leftJoin('fdc.facturaConceptoDetalleRel', 'fcd')
            ->where('fdc.codigoFacturaFk = ' . $codigoFactura);
        return $queryBuilder;
    }

}