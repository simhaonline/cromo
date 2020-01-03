<?php

namespace App\Repository\Turno;

use App\Entity\General\GenImpuesto;
use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurFactura;
use App\Entity\Turno\TurFacturaDetalle;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurFacturaDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurFacturaDetalle::class);
    }

    public function lista($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurFacturaDetalle::class, 'fd');
        $queryBuilder
            ->select('fd.codigoFacturaDetallePk')
            ->addSelect('i.nombre AS item')
            ->addSelect('fd.cantidad')
            ->addSelect('fd.vrPrecio')
            ->addSelect('fd.vrSubtotal')
            ->addSelect('fd.vrIva')
            ->addSelect('fd.vrTotal')
            ->addSelect('fd.codigoImpuestoIvaFk')
            ->addSelect('fd.codigoImpuestoRetencionFk')
            ->addSelect('p.nombre as puestoNombre')
            ->addSelect('fd.codigoPedidoDetalleFk')
            ->addSelect('fd.detalle')
            ->addSelect('fd.porcentajeBaseIva')
            ->addSelect('fd.vrBaseIva')
            ->addSelect('fd.codigoGrupoFk')
            ->addSelect('m.nombre as modalidadNombre')
            ->leftJoin('fd.itemRel', 'i')
            ->leftJoin('fd.puestoRel', 'p')
            ->leftJoin('fd.modalidadRel', 'm')
            ->where('fd.codigoFacturaFk = ' . $id);

        return $queryBuilder;
    }

    public function actualizarDetalles($arrControles, $form, $arFactura)
    {
        $em = $this->getEntityManager();
        $this->getEntityManager()->persist($arFactura);
        if ($this->contarDetalles($arFactura->getCodigoFacturaPk()) > 0) {
            $arrCantidad = $arrControles['arrCantidad'];
            $arrPrecio = $arrControles['arrValor'];
            $arrCodigo = $arrControles['arrCodigo'];
            $arrImpuestoIva = $arrControles['cboImpuestoIva'];
            $arrImpuestoRetencion = $arrControles['cboImpuestoRetencion'];
            $arrDetalle = $arrControles['arrDetalle'];
            $mensajeError = "";
            foreach ($arrCodigo as $codigoFacturaDetalle) {
                $arFacturaDetalle = $this->getEntityManager()->getRepository(TurFacturaDetalle::class)->find($codigoFacturaDetalle);
                $arFacturaDetalle->setCantidad($arrCantidad[$codigoFacturaDetalle]);
                $arFacturaDetalle->setVrPrecio($arrPrecio[$codigoFacturaDetalle]);
                $codigoImpuestoIva = $arrImpuestoIva[$codigoFacturaDetalle];
                if ($arFacturaDetalle->getCodigoImpuestoIvaFk()) {
                    $arImpuestoIva = $em->getRepository(GenImpuesto::class)->find($codigoImpuestoIva);
                    $arFacturaDetalle->setPorcentajeIva($arImpuestoIva->getPorcentaje());
                    $arFacturaDetalle->setPorcentajeBaseIva($arImpuestoIva->getPorcentajeBase());
                }
                $arFacturaDetalle->setCodigoImpuestoIvaFk($codigoImpuestoIva);
                $codigoImpuestoRetencion = $arrImpuestoRetencion[$codigoFacturaDetalle];
                if ($arFacturaDetalle->getCodigoImpuestoRetencionFk() != $codigoImpuestoRetencion) {
                    $arImpuestoRetencion = $em->getRepository(GenImpuesto::class)->find($codigoImpuestoRetencion);
//                    $arFacturaDetalle->setPorcentajeRetencion($arImpuestoRetencion->getPorcentaje());
                }
                $arFacturaDetalle->setCodigoImpuestoRetencionFk($codigoImpuestoRetencion);
                $arFacturaDetalle->setDetalle($arrDetalle[$codigoFacturaDetalle]);
                $em->persist($arFacturaDetalle);
            }
            if ($mensajeError == "") {
                $em->getRepository(TurFactura::class)->liquidar($arFactura);
                $this->getEntityManager()->flush();
            } else {
                Mensajes::error($mensajeError);
            }
        }

    }

    /**
     * @param $arrDetallesSeleccionados
     * @param $id
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($id, $arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        $arRegistro = $em->getRepository(TurFactura::class)->find($id);
        if ($arRegistro->getEstadoAutorizado() == 0) {
            if ($arrDetallesSeleccionados) {
                if (count($arrDetallesSeleccionados)) {
                    foreach ($arrDetallesSeleccionados as $codigo) {
                        $ar = $this->getEntityManager()->getRepository(TurFacturaDetalle::class)->find($codigo);
                        if ($ar) {
                            $this->getEntityManager()->remove($ar);
                            $this->getEntityManager()->flush();
                        }
                    }
                    try {
                        $this->getEntityManager()->flush();
                    } catch (\Exception $e) {
                        Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                    }
                }
            }
        } else {
            Mensajes::error('No se puede eliminar, el registro se encuentra autorizado');
        }
    }

    public function contarDetalles($codigoFactura)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurFacturaDetalle::class, 'fd')
            ->select("COUNT(fd.codigoFacturaDetallePk)")
            ->where("fd.codigoFacturaFk = {$codigoFactura} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }

    public function informe()
    {
        $session = new Session();

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurFacturaDetalle::class, 'fd')
            ->select('fd.codigoFacturaDetallePk')
            ->addSelect('f.numero')
            ->addSelect('f.fecha')
            ->addSelect('i.nombre')
            ->addSelect('c.nombreCorto')
            ->addSelect('p.nombre AS puesto')
            ->addSelect('ci.nombre as ciudad')
            ->addSelect('fd.cantidad')
            ->addSelect('fd.porcentajeIva')
            ->addSelect('fd.vrIva')
            ->addSelect('fd.vrPrecio')
            ->addSelect('fd.vrSubtotal')
            ->addSelect('fd.vrTotal')
            ->addSelect('fd.vrRetencionFuente')
            ->leftJoin('fd.facturaRel', 'f')
            ->leftJoin('fd.itemRel', 'i')
            ->leftJoin('f.clienteRel', 'c')
            ->leftJoin('c.ciudadRel', 'ci')
            ->leftJoin('fd.puestoRel', 'p')
            ->where('f.estadoAnulado = 0')
            ->addOrderBy('fd.codigoFacturaDetallePk', 'DESC');
        if ($session->get('filtroTurInformeComercialFacturaDetalleClienteCodigo') != null) {
            $queryBuilder->andWhere("f.codigoClienteFk = {$session->get('filtroTurInformeComercialFacturaDetalleClienteCodigo')}");
        }
        if ($session->get('filtroTurInformeComercialFacturaDetalleFechaDesde') != null) {
            $queryBuilder->andWhere("f.fecha >= '{$session->get('filtroTurInformeComercialFacturaDetalleFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroTurInformeComercialFacturaDetalleFechaHasta') != null) {
            $queryBuilder->andWhere("f.fecha <= '{$session->get('filtroTurInformeComercialFacturaDetalleFechaHasta')} 23:59:59'");
        }

        if ($session->get('filtroTurInformeComercialFacturaDetalleCodigoAutorizado') != null) {
            $queryBuilder->andWhere("f.estadoAutorizado = {$session->get('filtroTurInformeComercialFacturaDetalleCodigoAutorizado')}");
        }
        if ($session->get('filtroTurInformeComercialFacturaDetalleCodigoAnulado') != null) {
            $queryBuilder->andWhere("f.estadoAprobado = {$session->get('filtroTurInformeComercialFacturaDetalleCodigoAnulado')}");
        }
        if ($session->get('filtroTurInformeComercialFacturaDetalleCiudad') != null) {
            $queryBuilder->andWhere("c.codigoCiudadFk = {$session->get('filtroTurInformeComercialFacturaDetalleCiudad')}");
        }
        return $queryBuilder->getQuery()->getResult();
    }

    public function retencionFacturaContabilizar($codigo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurFacturaDetalle::class, 'fd')
            ->select('fd.codigoImpuestoRetencionFk')
            ->addSelect('SUM(fd.vrRetencionFuente) as vrRetencionFuente')
            ->where('fd.codigoFacturaFk = ' . $codigo)
            ->andWhere('fd.vrRetencionFuente > 0')
            ->groupBy('fd.codigoImpuestoRetencionFk');
        $arrCuentas = $queryBuilder->getQuery()->getResult();
        return $arrCuentas;
    }

    public function ivaFacturaContabilizar($codigo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurFacturaDetalle::class, 'fd')
            ->select('fd.codigoImpuestoIvaFk')
            ->addSelect('SUM(fd.vrIva) as vrIva')
            ->addSelect('SUM(fd.vrBaseIva) as vrBaseIva')
            ->where('fd.codigoFacturaFk = ' . $codigo)
            ->andWhere('fd.vrIva > 0')
            ->groupBy('fd.codigoImpuestoIvaFk');
        $arrCuentas = $queryBuilder->getQuery()->getResult();
        return $arrCuentas;
    }

    public function ventaFacturaContabilizar($codigo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurFacturaDetalle::class, 'fd')
            ->select('i.codigoCuentaVentaFk as codigoCuentaFk')
            ->addSelect('SUM(fd.vrSubtotal) as vrSubtotal')
            ->leftJoin('fd.itemRel', 'i')
            ->where('fd.codigoFacturaFk = ' . $codigo)
            ->groupBy('i.codigoCuentaVentaFk');
        $arrCuentas = $queryBuilder->getQuery()->getResult();
        return $arrCuentas;
    }

    public function listaCliente($codigoCliente, $codigoFactura = "", $tipo = "")
    {
        $session = new Session();
        $dql = "SELECT fd FROM App\Entity\Turno\TurFacturaDetalle fd JOIN fd.facturaRel f JOIN f.facturaTipoRel ft WHERE f.codigoClienteFk =  " . $codigoCliente . " AND f.estadoAutorizado = 1 AND f.numero > 0 AND f.estadoAnulado = 0";
        if ($session->get('filtroCodigoFactura') != '') {
            $dql .= " AND fd.codigoFacturaFk = " . $session->get('filtroCodigoFactura'). " ";
        }
        if ($session->get('filtroNumeroFactura') != ''){
            $dql .= " AND f.numero = " . $session->get('filtroNumeroFactura'). " ";
        }
        $dql .= " ORDER BY fd.codigoFacturaDetallePk DESC";
        return $dql;
    }

}
