<?php

namespace App\Repository\Transporte;


use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteFacturaDetalleConcepto;
use App\Entity\Transporte\TteFacturaPlanilla;
use App\Entity\Transporte\TteGuia;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteFacturaDetalleConceptoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteFacturaDetalleConcepto::class);
    }

    public function listaFacturaDetalle($codigoFactura)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFacturaDetalleConcepto::class, 'fcd');
        $queryBuilder
            ->select('fcd.codigoFacturaDetalleConceptoPk')
            ->addSelect('fcd.cantidad')
            ->addSelect('fcd.vrValor')
            ->addSelect('fc.nombre AS concepto')
            ->leftJoin('fcd.facturaConceptoRel', 'fc')
            ->where('fcd.codigoFacturaFk = ' . $codigoFactura);
        return $queryBuilder;
    }

}