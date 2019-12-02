<?php

namespace App\Repository\Transporte;

use App\Entity\Financiero\FinCentroCosto;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Transporte\TteCierre;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteConfiguracion;
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
use App\Entity\Transporte\TteIntermediacionCompra;
use App\Entity\Transporte\TteIntermediacionRecogida;
use App\Entity\Transporte\TteIntermediacionVenta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TteIntermediacionRecogidaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteIntermediacionRecogida::class);
    }

    public function detalle($codigoIntermediacion)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteIntermediacionRecogida::class, 'ir')
            ->select('ir.codigoIntermediacionRecogidaPk')
            ->addSelect('ir.vrFlete')
            ->addSelect('ir.vrFleteParticipacion')
            ->addSelect('ir.porcentajeParticipacion')
            ->addSelect('p.nombreCorto AS poseedorNombreCorto')
            ->addSelect('dt.nombre as despachoTipoNombre')
            ->leftJoin('ir.poseedorRel', 'p')
            ->leftJoin('ir.despachoRecogidaTipoRel', 'dt')
            ->where('ir.codigoIntermediacionFk = ' . $codigoIntermediacion)
        ->orderBy('ir.codigoPoseedorFk');
        return $queryBuilder->getQuery()->execute();
    }

    public function registroContabilizar($codigo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteIntermediacionRecogida::class, 'ir')
            ->select('ir.codigoIntermediacionRecogidaPk')
            ->addSelect('ir.codigoPoseedorFk')
            ->addSelect('ir.vrFlete')
            ->addSelect('ir.vrFleteParticipacion')
            ->addSelect('dt.codigoCuentaFleteFk')
            ->leftJoin('ir.despachoRecogidaTipoRel', 'dt')
            ->where('ir.codigoIntermediacionFk = ' . $codigo);
        $arIntermediacionVenta = $queryBuilder->getQuery()->getResult();
        return $arIntermediacionVenta;
    }

}