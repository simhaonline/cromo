<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCierre;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteCosto;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteDespachoRecogida;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteFacturaTipo;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteIntermediacion;
use App\Entity\Transporte\TteIntermediacionDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteIntermediacionDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteIntermediacionDetalle::class);
    }

    public function detalle($codigoIntermediacion)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteIntermediacionDetalle::class, 'id')
            ->select('id.codigoIntermediacionDetallePk')
            ->addSelect('id.vrFlete')
            ->addSelect('id.vrPago')
            ->addSelect('id.porcentajeParticipacion')
            ->addSelect('id.vrIngreso')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('ft.nombre AS facturaTipoNombre')
            ->leftJoin('id.clienteRel', 'c')
            ->leftJoin('id.facturaTipoRel', 'ft')
            ->where('id.codigoIntermediacionFk = ' . $codigoIntermediacion)
        ->orderBy('id.codigoClienteFk');
        return $queryBuilder->getQuery()->execute();
    }

}