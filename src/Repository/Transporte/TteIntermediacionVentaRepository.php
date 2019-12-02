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
use App\Entity\Transporte\TteIntermediacionVenta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TteIntermediacionVentaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteIntermediacionVenta::class);
    }

    public function detalle($codigoIntermediacion)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteIntermediacionVenta::class, 'id')
            ->select('id.codigoIntermediacionVentaPk')
            ->addSelect('id.vrFlete')
            ->addSelect('id.vrFleteParticipacion')
            ->addSelect('id.vrFleteIngreso')
            ->addSelect('id.porcentajeParticipacion')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('ft.nombre AS facturaTipoNombre')
            ->leftJoin('id.clienteRel', 'c')
            ->leftJoin('id.facturaTipoRel','ft')
            ->where('id.codigoIntermediacionFk = ' . $codigoIntermediacion)
        ->orderBy('id.codigoClienteFk');
        return $queryBuilder->getQuery()->execute();
    }

    public function registroContabilizar($codigo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteIntermediacionVenta::class, 'iv')
            ->select('iv.codigoIntermediacionVentaPk')
            ->addSelect('iv.codigoClienteFk')
            ->addSelect('iv.vrFlete')
            ->addSelect('iv.vrFleteParticipacion')
            ->addSelect('iv.vrFleteIngreso')
            ->addSelect('ft.codigoCuentaClienteFk')
            ->addSelect('ft.codigoComprobanteFk')
            ->addSelect('ft.prefijo')
            ->addSelect('ft.codigoCuentaIngresoFleteFk')
            ->addSelect('ft.codigoCuentaIngresoFleteIntermediacionFk')
            ->addSelect('ft.codigoFacturaClaseFk')
            ->leftJoin('iv.facturaTipoRel', 'ft')
            ->where('iv.codigoIntermediacionFk = ' . $codigo);
        $arIntermediacionVenta = $queryBuilder->getQuery()->getResult();
        return $arIntermediacionVenta;
    }

}