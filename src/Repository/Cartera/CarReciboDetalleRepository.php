<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarDescuentoConcepto;
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

    public function liquidar($id)
    {
        $em = $this->getEntityManager();
        $pago = 0;
        $pagoTotal = 0;
        $floDescuento = 0;
        $floAjustePeso = 0;
        $floRetencionIca = 0;
        $floRetencionIva = 0;
        $floRetencionFuente = 0;
        $otrosDecuentos = 0;
        $arRecibo = $em->getRepository(CarRecibo::class)->find($id);
        $arRecibosDetalle = $em->getRepository(CarReciboDetalle::class)->findBy(array('codigoReciboFk' => $id));
        foreach ($arRecibosDetalle as $arReciboDetalle) {
            $floDescuento += $arReciboDetalle->getVrDescuento();
            $floAjustePeso += $arReciboDetalle->getVrAjustePeso();
            $floRetencionIca += $arReciboDetalle->getVrRetencionIca();
            $floRetencionIva += $arReciboDetalle->getVrRetencionIva();
            $floRetencionFuente += $arReciboDetalle->getVrRetencionFuente();
            $otrosDecuentos += $arReciboDetalle->getVrOtroDescuento();
            $pago += $arReciboDetalle->getVrPago() * $arReciboDetalle->getOperacion();
            $pagoTotal += $arReciboDetalle->getVrPagoAfectar();
        }
        $arRecibo->setVrPago($pago);
        $arRecibo->setVrPagoTotal($pagoTotal);
        $arRecibo->setVrTotalDescuento($floDescuento);
        $arRecibo->setVrTotalAjustePeso($floAjustePeso);
        $arRecibo->setVrTotalRetencionIca($floRetencionIca);
        $arRecibo->setVrTotalRetencionIva($floRetencionIva);
        $arRecibo->setVrTotalRetencionFuente($floRetencionFuente);
        $arRecibo->setVrTotalOtroDescuento($otrosDecuentos);
        $em->persist($arRecibo);
        $em->flush();
        return true;
    }

    public function vrPagoRecibo($codigoCuentaCobrar, $id)
    {
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

    public function eliminarSeleccionados($arrSeleccionados)
    {
        if ($arrSeleccionados) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {
                $arReciboDetalle = $em->getRepository(CarReciboDetalle::class)->find($codigo);
                $em->remove($arReciboDetalle);
            }
            $em->flush();
        }
    }

    public function actualizarDetalle($arrControles, $codigoRecibo)
    {
        $em = $this->getEntityManager();
        if (isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arReciboDetalle = $em->getRepository(CarReciboDetalle::class)->find($intCodigo);
                $arDescuentoConcepto = null;
                $codigoDescuentoConcepto = isset($arrControles['cboDescuentoConcepto' . $intCodigo]) && $arrControles['cboDescuentoConcepto' . $intCodigo] != '' ? $arrControles['cboDescuentoConcepto' . $intCodigo] : null;
                $valorPago = isset($arrControles['TxtValorPago' . $intCodigo]) && $arrControles['TxtValorPago' . $intCodigo] != '' ? $arrControles['TxtValorPago' . $intCodigo] : 0;
                $valorAjustePeso = isset($arrControles['TxtVrAjustePeso' . $intCodigo]) && $arrControles['TxtVrAjustePeso' . $intCodigo] != '' ? $arrControles['TxtVrAjustePeso' . $intCodigo] : 0;
                $valorDescuento = isset($arrControles['TxtVrDescuento' . $intCodigo]) && $arrControles['TxtVrDescuento' . $intCodigo] != '' ? $arrControles['TxtVrDescuento' . $intCodigo] : 0;
                $valorRetencionIva = isset($arrControles['TxtVrRetencionIva' . $intCodigo]) && $arrControles['TxtVrRetencionIva' . $intCodigo] != '' ? $arrControles['TxtVrRetencionIva' . $intCodigo] : 0;
                $valorRetencionIca = isset($arrControles['TxtVrRetencionIca' . $intCodigo]) && $arrControles['TxtVrRetencionIca' . $intCodigo] != '' ? $arrControles['TxtVrRetencionIca' . $intCodigo] : 0;
                $valorRetencionFte = isset($arrControles['TxtVrRetencionFuente' . $intCodigo]) && $arrControles['TxtVrRetencionFuente' . $intCodigo] != '' ? $arrControles['TxtVrRetencionFuente' . $intCodigo] : 0;
                $valorOtroDescuento = isset($arrControles['TxtVrOtroDescuento' . $intCodigo]) && $arrControles['TxtVrOtroDescuento' . $intCodigo] != '' ? $arrControles['TxtVrOtroDescuento' . $intCodigo] : 0;
                if($valorOtroDescuento > 0) {
                    if($codigoDescuentoConcepto != "SS") {
                        $arDescuentoConcepto = $em->getRepository(CarDescuentoConcepto::class)->find($codigoDescuentoConcepto);
                    }
                }
                $valorPagoAfectar =
                    $valorPago
                    - $valorAjustePeso
                    + $valorDescuento
                    + $valorRetencionIva
                    + $valorRetencionIca
                    + $valorRetencionFte
                    + $valorOtroDescuento;
                $arReciboDetalle->setVrDescuento($valorDescuento);
                $arReciboDetalle->setVrAjustePeso($valorAjustePeso);
                $arReciboDetalle->setVrRetencionIca($valorRetencionIca);
                $arReciboDetalle->setVrRetencionIva($valorRetencionIva);
                $arReciboDetalle->setVrRetencionFuente($valorRetencionFte);
                $arReciboDetalle->setVrOtroDescuento($valorOtroDescuento);
                $arReciboDetalle->setVrPago($valorPago);
                $arReciboDetalle->setVrPagoAfectar($valorPagoAfectar);
                $arReciboDetalle->setDescuentoConceptoRel($arDescuentoConcepto);
                $em->persist($arReciboDetalle);
            }
            $em->flush();
            $em->getRepository(CarReciboDetalle::class)->liquidar($codigoRecibo);
        }
    }

    public function listaFormato($codigoRecibo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarReciboDetalle::class, 'rd');
        $queryBuilder
            ->select('rd.codigoReciboDetallePk')
            ->addSelect('cr.nombreCorto AS clienteNombreCorto')
            ->addSelect('cct.nombre AS cuentaCobrarTipo')
            ->addSelect('rd.numeroFactura')
            ->addSelect('cc.fecha')
            ->addSelect('rd.vrDescuento')
            ->addSelect('rd.vrAjustePeso')
            ->addSelect('rd.vrRetencionFuente')
            ->addSelect('rd.vrRetencionIca')
            ->addSelect('rd.vrPagoAfectar')
            ->leftJoin('rd.reciboRel', 'r')
            ->leftJoin('r.clienteRel', 'cr')
            ->leftJoin('rd.cuentaCobrarRel', 'cc')
            ->leftJoin('rd.cuentaCobrarTipoRel', 'cct')
            ->where('rd.codigoReciboFk = ' . $codigoRecibo);
        $queryBuilder->orderBy('rd.codigoReciboDetallePk', 'ASC');

        return $queryBuilder->getQuery()->getResult() ;
    }

    public function listaContabilizar($codigoRecibo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarReciboDetalle::class, 'rd');
        $queryBuilder
            ->select('rd.codigoReciboDetallePk')
            ->addSelect('rd.numeroFactura')
            ->addSelect('rd.vrDescuento')
            ->addSelect('rd.vrAjustePeso')
            ->addSelect('rd.vrRetencionFuente')
            ->addSelect('rd.vrRetencionIca')
            ->addSelect('rd.vrRetencionIva')
            ->addSelect('rd.vrPago')
            ->addSelect('rd.vrPagoAfectar')
            ->addSelect('cc.numeroDocumento')
            ->addSelect('cct.prefijo')
            ->addSelect('cct.codigoCuentaRetencionFuenteFk')
            ->addSelect('cct.codigoCuentaIndustriaComercioFk')
            ->addSelect('cct.codigoCuentaRetencionIvaFk')
            ->addSelect('cct.codigoCuentaDescuentoFk')
            ->addSelect('cct.codigoCuentaClienteFk')
            ->addSelect('cct.codigoCuentaAjustePesoFk')
            ->leftJoin('rd.cuentaCobrarTipoRel', 'cct')
            ->leftJoin('rd.cuentaCobrarRel', 'cc')
            ->where('rd.codigoReciboFk = ' . $codigoRecibo);
        $queryBuilder->orderBy('rd.codigoReciboDetallePk', 'ASC');

        return $queryBuilder->getQuery()->getResult() ;
    }
}