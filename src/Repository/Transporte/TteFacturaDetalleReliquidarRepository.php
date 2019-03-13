<?php

namespace App\Repository\Transporte;


use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteFacturaDetalleReliquidar;
use App\Entity\Transporte\TteFacturaPlanilla;
use App\Entity\Transporte\TteGuia;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteFacturaDetalleReliquidarRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteFacturaDetalleReliquidar::class);
    }

    public function lista($codigoFactura)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFacturaDetalleReliquidar::class, 'fdr')
            ->select('fdr.codigoFacturaDetalleReliquidarPk')
            ->addSelect('fdr.codigoGuiaFk')
            ->addSelect('fdr.vrFlete')
            ->addSelect('fdr.vrManejo')
            ->addSelect('fdr.vrFleteNuevo')
            ->addSelect('fdr.vrManejoNuevo')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('co.nombre AS ciudadOrigen')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.documentoCliente')
            ->addSelect('fd.unidades')
            ->addSelect('fd.pesoReal')
            ->addSelect('g.pesoFacturado')
            ->addSelect('fd.pesoVolumen')
            ->addSelect('fd.vrDeclara')
            ->leftJoin('fdr.facturaDetalle', 'fd')
            ->leftJoin('fd.guiaRel', 'g')
            ->leftJoin('g.ciudadOrigenRel', 'co')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->where('fdr.codigoFacturaFk =  ' . $codigoFactura);
        return $queryBuilder->getQuery()->getResult();
    }


    public function limpiarTabla($codigoFactura){
        $em = $this->_em;
        $em->createQueryBuilder()->delete(TteFacturaDetalleReliquidar::class,'fdr')->where("fdr.codigoFacturaFk = '" . $codigoFactura . "'")->getQuery()->execute();
    }
}