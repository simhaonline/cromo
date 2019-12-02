<?php

namespace App\Repository\General;

use App\Entity\General\GenFactura;
use App\Entity\General\GenFacturaDetalle;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Transporte\TteFactura;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class GenFacturaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenFactura::class);
    }

    public function lista(){
        $session = new Session();
        $qb = $this->_em->createQueryBuilder()
            ->from(GenFactura::class,"f")
            ->select("f.codigoFacturaPk")
            ->addSelect("f.numero")
            ->addSelect("f.identificacion")
            ->addSelect("f.tercero")
            ->addSelect("f.facturaTipo")
            ->addSelect("f.modulo")
            ->addSelect("f.fecha")
            ->addSelect("f.fechaVence")
            ->addSelect("f.vrTotal")
            ->where("f.codigoFacturaPk != 0");
        if($session->get('filtroGenFacturaNumero')){
            $qb->andWhere("f.numero = {$session->get('filtroGenFacturaNumero')}");
        }
        if($session->get('filtroGenFacturaTercero')){
            $qb->andWhere("f.tercero = '{$session->get('filtroGenFacturaTercero')}'");
        }
        if($session->get('filtroGenFacturaIdentificacion')){
            $qb->andWhere("f.identificacion = '{$session->get('filtroGenFacturaIdentificacion')}'");
        }
        if($session->get('filtroGenFacturaModulo')){
            $qb->andWhere("f.modulo = '{$session->get('filtroGenFacturaModulo')}'");
        }
        return $qb;
    }

    /**
     * @param $arFactura InvMovimiento
     */
    public function insertarFacturaInventario($arFactura){
        $em = $this->_em;
        $arFacturaNueva = new GenFactura();
        $arFacturaNueva->setFecha($arFactura->getFecha());
        $arFacturaNueva->setCiudadFactura($arFactura->getCiudadFactura());
        $arFacturaNueva->setFacturaTipo($arFactura->getCodigoFacturaTipoFk());
        $arFacturaNueva->setNumero($arFactura->getNumero());
        $arFacturaNueva->setDireccion($arFactura->getDireccion());
        $arFacturaNueva->setFechaVence($arFactura->getFechaVence());
        $arFacturaNueva->setSoporte($arFactura->getSoporte());
        $arFacturaNueva->setPlazoPago($arFactura->getPlazoPago());
        $arFacturaNueva->setUsuario($arFactura->getUsuario());
        $arFacturaNueva->setComentario($arFactura->getComentarios());
        $arFacturaNueva->setVrSubtotal($arFactura->getVrSubtotal());
        $arFacturaNueva->setVrDescuento($arFactura->getVrDescuento());
        $arFacturaNueva->setVrIva($arFactura->getVrIva());
        $arFacturaNueva->setVrNeto($arFactura->getVrNeto());
        $arFacturaNueva->setVrRetencionFuente($arFactura->getVrRetencionFuente());
        $arFacturaNueva->setVrRetencionIva($arFactura->getVrRetencionIva());
        $arFacturaNueva->setVrTotal($arFactura->getVrTotal());
        $em->persist($arFacturaNueva);
        $em->flush();
        $em->getRepository(GenFacturaDetalle::class)->insertarFacturaDetallesInventario($arFactura);
    }

    /**
     * @param $arFactura TteFactura
     */
    public function insertarFacturaTransporte($arFactura){
        $arFacturaNueva = new GenFactura();
        $arFacturaNueva->setFecha($arFactura->getFecha());
        $arFacturaNueva->setFacturaTipo($arFactura->getCodigoFacturaTipoFk());
//        $arFacturaNueva->setCiudadFactura()); //Pendiente validar
        $arFacturaNueva->setNumero($arFactura->getNumero());
//        $arFacturaNueva->setDireccion();//Pendiente validar
        $arFacturaNueva->setFechaVence($arFactura->getFechaVence());
        $arFacturaNueva->setSoporte($arFactura->getSoporte());
        $arFacturaNueva->setPlazoPago($arFactura->getPlazoPago());
        $arFacturaNueva->setUsuario($arFactura->getUsuario());
        $arFacturaNueva->setComentario($arFactura->getComentario());
        $arFacturaNueva->setVrSubtotal($arFactura->getVrSubtotal());
        $arFacturaNueva->setVrDescuento(0);
        $arFacturaNueva->setVrIva($arFactura->getVrIva());
        $arFacturaNueva->setVrNeto($arFactura->getVrTotalNeto());
        $arFacturaNueva->setVrRetencionFuente($arFactura->getVrRetencionFuente());
        $arFacturaNueva->setVrRetencionIva(0);
        $arFacturaNueva->setVrTotal($arFactura->getVrTotal());
        $this->_em->persist($arFacturaNueva);
        $this->_em->flush();
    }
}