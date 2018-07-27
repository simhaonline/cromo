<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboDetalle;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarReciboDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarReciboDetalle::class);
    }

    public function liquidar($id) {
        $em = $this->getEntityManager();
        $arRecibo = $em->getRepository(CarRecibo::class)->find($id);
        $intCantidad = 0;
        $pago = 0;
        $pagoTotal = 0;
        $floDescuento = 0;
        $floAjustePeso = 0;
        $floRetencionIca = 0;
        $floRetencionIva = 0;
        $floRetencionFuente = 0;
        $arRecibo = $em->getRepository(CarRecibo::class)->find($id);
        $arRecibosDetalle = $em->getRepository(CarReciboDetalle::class)->findBy(array('codigoReciboFk' => $id));
        foreach ($arRecibosDetalle as $arReciboDetalle) {
            $floDescuento += $arReciboDetalle->getVrDescuento();
            $floAjustePeso += $arReciboDetalle->getVrAjustePeso();
            $floRetencionIca += $arReciboDetalle->getVrRetencionIca();
            $floRetencionIva += $arReciboDetalle->getVrRetencionIva();
            $floRetencionFuente += $arReciboDetalle->getVrRetencionFuente();
            $pago += $arReciboDetalle->getVrPago() * $arReciboDetalle->getOperacion();
            $pagoTotal += $arReciboDetalle->getVrPagoAfectar(); //* $arReciboDetalle->getOperacion();
        }
        $arRecibo->setVrPago($pago);
        $arRecibo->setVrPagoTotal($pagoTotal);
        $arRecibo->setVrTotalDescuento($floDescuento);
        $arRecibo->setVrTotalAjustePeso($floAjustePeso);
        $arRecibo->setVrTotalRetencionIca($floRetencionIca);
        $arRecibo->setVrTotalRetencionIva($floRetencionIva);
        $arRecibo->setVrTotalRetencionFuente($floRetencionFuente);
        $em->persist($arRecibo);
        $em->flush();
        return true;
    }

    public function vrPagoRecibo($codigoCuentaCobrar, $id) {
        $em = $this->getEntityManager();
        $dql = "SELECT SUM(rd.vrPagoAfectar) as valor FROM App\Entity\Cartera\CarReciboDetalle rd "
            . "WHERE rd.codigoCuentaCobrarFk = " . $codigoCuentaCobrar . " AND rd.codigoReciboFk = " . $id;
        $query = $em->createQuery($dql);
        $vrTotalPago = $query->getSingleScalarResult();
        if (!$vrTotalPago) {
            $vrTotalPago = 0;
        }
        return $vrTotalPago;
    }
}