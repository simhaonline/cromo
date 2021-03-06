<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarDescuentoConcepto;
use App\Entity\Cartera\CarIngresoConcepto;
use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboDetalle;
use App\Entity\General\GenAsesor;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CarReciboDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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
        $otrosIngresos = 0;
        $arRecibo = $em->getRepository(CarRecibo::class)->find($id);
        $arRecibosDetalle = $em->getRepository(CarReciboDetalle::class)->findBy(array('codigoReciboFk' => $id));
        foreach ($arRecibosDetalle as $arReciboDetalle) {
            $floDescuento += $arReciboDetalle->getVrDescuento();
            $floAjustePeso += $arReciboDetalle->getVrAjustePeso();
            $floRetencionIca += $arReciboDetalle->getVrRetencionIca();
            $floRetencionIva += $arReciboDetalle->getVrRetencionIva();
            $floRetencionFuente += $arReciboDetalle->getVrRetencionFuente();
            $otrosDecuentos += $arReciboDetalle->getVrOtroDescuento();
            $otrosIngresos += $arReciboDetalle->getVrOtroIngreso();
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
        $arRecibo->setVrTotalOtroIngreso($otrosIngresos);
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
            $error = false;
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arReciboDetalle = $em->getRepository(CarReciboDetalle::class)->find($intCodigo);
                $arDescuentoConcepto = null;
                $arIngresoConcepto = null;
                $codigoDescuentoConcepto = isset($arrControles['cboDescuentoConcepto' . $intCodigo]) && $arrControles['cboDescuentoConcepto' . $intCodigo] != '' ? $arrControles['cboDescuentoConcepto' . $intCodigo] : null;
                $codigoIngresoConcepto = isset($arrControles['cboIngresoConcepto' . $intCodigo]) && $arrControles['cboIngresoConcepto' . $intCodigo] != '' ? $arrControles['cboIngresoConcepto' . $intCodigo] : null;
                $valorPago = isset($arrControles['TxtValorPago' . $intCodigo]) && $arrControles['TxtValorPago' . $intCodigo] != '' ? $arrControles['TxtValorPago' . $intCodigo] : 0;
                $valorAjustePeso = isset($arrControles['TxtVrAjustePeso' . $intCodigo]) && $arrControles['TxtVrAjustePeso' . $intCodigo] != '' ? $arrControles['TxtVrAjustePeso' . $intCodigo] : 0;
                $valorDescuento = isset($arrControles['TxtVrDescuento' . $intCodigo]) && $arrControles['TxtVrDescuento' . $intCodigo] != '' ? $arrControles['TxtVrDescuento' . $intCodigo] : 0;
                $valorRetencionIva = isset($arrControles['TxtVrRetencionIva' . $intCodigo]) && $arrControles['TxtVrRetencionIva' . $intCodigo] != '' ? $arrControles['TxtVrRetencionIva' . $intCodigo] : 0;
                $valorRetencionIca = isset($arrControles['TxtVrRetencionIca' . $intCodigo]) && $arrControles['TxtVrRetencionIca' . $intCodigo] != '' ? $arrControles['TxtVrRetencionIca' . $intCodigo] : 0;
                $valorRetencionFte = isset($arrControles['TxtVrRetencionFuente' . $intCodigo]) && $arrControles['TxtVrRetencionFuente' . $intCodigo] != '' ? $arrControles['TxtVrRetencionFuente' . $intCodigo] : 0;
                $valorOtroDescuento = isset($arrControles['TxtVrOtroDescuento' . $intCodigo]) && $arrControles['TxtVrOtroDescuento' . $intCodigo] != '' ? $arrControles['TxtVrOtroDescuento' . $intCodigo] : 0;
                $valorOtroIngreso = isset($arrControles['TxtVrOtroIngreso' . $intCodigo]) && $arrControles['TxtVrOtroIngreso' . $intCodigo] != '' ? $arrControles['TxtVrOtroIngreso' . $intCodigo] : 0;
                $comentario = isset($arrControles['TxtComentario' . $intCodigo]) && $arrControles['TxtComentario' . $intCodigo] != '' ? $arrControles['TxtComentario' . $intCodigo] : '';
                $arReciboDetalle->setComentario($comentario);
                $codigoAsesor = isset($arrControles['cboAsesor' . $intCodigo]) && $arrControles['cboAsesor' . $intCodigo] != '' ? $arrControles['cboAsesor' . $intCodigo] : null;
                if($codigoAsesor) {
                    $arAsesor = $em->getRepository(GenAsesor::class)->find($codigoAsesor);
                    if($arAsesor) {
                        $arReciboDetalle->setAsesorRel($arAsesor);
                    }
                }
                if(is_numeric($valorPago) && is_numeric($valorDescuento) && is_numeric($valorRetencionIca) && is_numeric($valorRetencionIva) && is_numeric($valorRetencionFte) && is_numeric($valorOtroDescuento) && is_numeric($valorOtroIngreso)) {
                    if($valorOtroDescuento > 0) {
                        if($codigoDescuentoConcepto != "SS") {
                            $arDescuentoConcepto = $em->getRepository(CarDescuentoConcepto::class)->find($codigoDescuentoConcepto);
                        }
                    }
                    if($valorOtroIngreso > 0) {
                        if($codigoIngresoConcepto != "SS") {
                            $arIngresoConcepto = $em->getRepository(CarIngresoConcepto::class)->find($codigoIngresoConcepto);
                        }
                    }
                    $valorPagoAfectar =
                        $valorPago
                        - $valorAjustePeso
                        - $valorOtroIngreso
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
                    $arReciboDetalle->setVrOtroIngreso($valorOtroIngreso);
                    $arReciboDetalle->setVrPago($valorPago);
                    $arReciboDetalle->setVrPagoAfectar($valorPagoAfectar);
                    $arReciboDetalle->setDescuentoConceptoRel($arDescuentoConcepto);
                    $arReciboDetalle->setIngresoConceptoRel($arIngresoConcepto);
                    $em->persist($arReciboDetalle);
                } else {
                    Mensajes::error("El detalle " . $intCodigo . " tiene valores incorrectos, verifique que no tenga comas ni simbolos especiales solo numero y el signo punto son permitidos (separador decimal es punto)");
                    $error = true;
                    break;
                }
            }
            if($error == false) {
                $em->flush();
                $em->getRepository(CarReciboDetalle::class)->liquidar($codigoRecibo);
            }
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
            ->addSelect('rd.vrRetencionIva')
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
            ->addSelect('rd.vrOtroDescuento')
            ->addSelect('rd.vrOtroIngreso')
            ->addSelect('rd.vrPago')
            ->addSelect('rd.vrPagoAfectar')
            ->addSelect('rd.codigoDescuentoConceptoFk')
            ->addSelect('rd.codigoIngresoConceptoFk')
            ->addSelect('rd.codigoCuentaCobrarAplicacionFk')
            ->addSelect('cc.numeroDocumento')
            ->addSelect('cc.fechaVence')
            ->addSelect('cc.codigoCentroCostoFk')
            ->addSelect('cct.codigoComprobanteFk')
            ->addSelect('cct.prefijo')
            ->addSelect('cct.codigoCuentaClienteFk')
            ->addSelect('ccat.codigoCuentaAplicacionFk')
            ->leftJoin('rd.cuentaCobrarRel', 'cc')
            ->leftJoin('rd.cuentaCobrarTipoRel', 'cct')
            ->leftJoin('rd.cuentaCobrarAplicacionRel', 'cca')
            ->leftJoin('rd.cuentaCobrarAplicacionTipoRel', 'ccat')
            ->where('rd.codigoReciboFk = ' . $codigoRecibo);
        $queryBuilder->orderBy('rd.codigoReciboDetallePk', 'ASC');

        return $queryBuilder->getQuery()->getResult() ;
    }

    public function detalle()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarReciboDetalle::class, 'rd')
            ->select('rd.codigoReciboDetallePk')
            ->addSelect('rd.numeroFactura')
            ->addSelect('r.numero')
            ->addSelect('cct.nombre')
            ->addSelect('rd.vrDescuento')
            ->addSelect('rd.vrRetencionIca')
            ->addSelect('rd.vrRetencionIva')
            ->addSelect('rd.vrRetencionFuente')
            ->addSelect('rd.vrOtroDescuento')
            ->addSelect('rd.vrOtroIngreso')
            ->addSelect('rd.vrAjustePeso')
            ->addSelect('rd.vrPago')
            ->addSelect('rd.vrPagoAfectar')
            ->leftJoin('rd.reciboRel', 'r')
            ->leftJoin('rd.cuentaCobrarTipoRel', 'cct')
            ->where('r.codigoReciboPk <> 0')
            ->orderBy('r.estadoAprobado', 'ASC')
            ->addOrderBy('r.fecha', 'DESC');
        if($session->get('filtroCarCodigoCliente')){
            $queryBuilder->andWhere("r.codigoClienteFk = {$session->get('filtroCarCodigoCliente')}");
        }
        if($session->get('filtroCarReciboNumero') != ''){
            $queryBuilder->andWhere("r.numero = {$session->get('filtroCarReciboNumero')}");
        }
        if ($session->get('filtroInvInformeReciboDetalleFechaDesde') != null) {
            $queryBuilder->andWhere("r.fecha >= '{$session->get('filtroInvInformeReciboDetalleFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroInvInformeReciboDetalleFechaHasta') != null) {
            $queryBuilder->andWhere("r.fecha <= '{$session->get('filtroInvInformeReciboDetalleFechaHasta')} 23:59:59'");
        }
        return $queryBuilder;
    }

    public function detalleReferencia($codigoCuentaCobrar)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarReciboDetalle::class, 'rd')
            ->select('rd.codigoReciboDetallePk')
            ->addSelect('rd.numeroFactura')
            ->addSelect('r.numero')
            ->addSelect('r.fechaPago')
            ->addSelect('cct.nombre')
            ->addSelect('rd.vrDescuento')
            ->addSelect('rd.vrRetencionIca')
            ->addSelect('rd.vrRetencionIva')
            ->addSelect('rd.vrRetencionFuente')
            ->addSelect('rd.vrOtroDescuento')
            ->addSelect('rd.vrOtroIngreso')
            ->addSelect('rd.vrAjustePeso')
            ->addSelect('rd.vrPago')
            ->addSelect('rd.vrPagoAfectar')
            ->leftJoin('rd.reciboRel', 'r')
            ->leftJoin('rd.cuentaCobrarTipoRel', 'cct')
            ->where('rd.codigoCuentaCobrarFk = ' . $codigoCuentaCobrar)
            ->orWhere('rd.codigoCuentaCobrarAplicacionFk = ' . $codigoCuentaCobrar)
            ->orderBy('r.estadoAprobado', 'ASC')
            ->addOrderBy('r.fecha', 'DESC');
        return $queryBuilder->getQuery()->getResult();
    }

    public function recaudo()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarReciboDetalle::class, 'rd')
            ->select('rd.codigoReciboFk')
            ->addSelect('r.numero')
            ->addSelect('rd.numeroFactura')
            ->addSelect('rt.nombre AS tipo')
            ->addSelect('r.fecha')
            ->addSelect('r.fechaPago')
            ->addSelect('cr.numeroIdentificacion AS nit')
            ->addSelect('cr.nombreCorto AS clienteNombre')
            ->addSelect('cta.nombre AS cuenta')
            ->addSelect('r.codigoAsesorFk')
            ->addSelect('r.usuario')
            ->addSelect('rd.vrPago')
            ->addSelect('a.nombre as asesor')
            ->leftJoin('rd.reciboRel', 'r')
            ->leftJoin('r.clienteRel', 'cr')
            ->leftJoin('r.reciboTipoRel', 'rt')
            ->leftJoin('r.cuentaRel', 'cta')
            ->leftJoin('rd.asesorRel', 'a')
            ->where('r.codigoReciboPk <> 0')
            ->andWhere('r.estadoAprobado = 1')
            ->groupBy('rd.codigoAsesorFk')
            ->addGroupBy('rd.codigoReciboFk')
            ->addGroupBy('rd.numeroFactura')
            ->addGroupBy('rd.vrPago');
        $fecha = new \DateTime('now');
        if ($session->get('filtroGenAsesor')) {
            $queryBuilder->andWhere("rd.codigoAsesorFk = '{$session->get('filtroGenAsesor')}'");
        }
        if ($session->get('filtroCarInformeReciboTipo') != "") {
            $queryBuilder->andWhere("r.codigoReciboTipoFk = '" . $session->get('filtroCarInformeReciboTipo') . "'");
        }
        if ($session->get('filtroCarReciboNumero')) {
            $queryBuilder->andWhere("r.numero = '{$session->get('filtroCarReciboNumero')}'");
        }
        if ($session->get('filtroCarCodigoCliente')) {
            $queryBuilder->andWhere("r.codigoClienteFk = {$session->get('filtroCarCodigoCliente')}");
        }
        if ($session->get('filtroFecha') == true) {
            if ($session->get('filtroInformeReciboFechaDesde') != null) {
                $queryBuilder->andWhere("r.fechaPago >= '{$session->get('filtroInformeReciboFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("r.fechaPago >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroInformeReciboFechaHasta') != null) {
                $queryBuilder->andWhere("r.fechaPago <= '{$session->get('filtroInformeReciboFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("r.fechaPago <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        return $queryBuilder;
    }

}