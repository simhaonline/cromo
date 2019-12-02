<?php

namespace App\Repository\RecursoHumano;

use App\Entity\General\GenImpuesto;
use App\Entity\RecursoHumano\RhuFacturaDetalle;
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

class RhuFacturaDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuFacturaDetalle::class);
    }

    public function lista($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuFacturaDetalle::class, 'fd');
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
            ->leftJoin('fd.itemRel', 'i')
            ->leftJoin('fd.puestoRel', 'p')
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
                $arFacturaDetalle = $this->getEntityManager()->getRepository(RhuFacturaDetalle::class)->find($codigoFacturaDetalle);
                $arFacturaDetalle->setCantidad($arrCantidad[$codigoFacturaDetalle]);
                $arFacturaDetalle->setVrPrecio($arrPrecio[$codigoFacturaDetalle]);
                $codigoImpuestoIva = $arrImpuestoIva[$codigoFacturaDetalle];
                if($arFacturaDetalle->getCodigoImpuestoIvaFk()) {
                    $arImpuestoIva = $em->getRepository(GenImpuesto::class)->find($codigoImpuestoIva);
                    $arFacturaDetalle->setPorcentajeIva($arImpuestoIva->getPorcentaje());
                    $arFacturaDetalle->setPorcentajeBaseIva($arImpuestoIva->getPorcentajeBase());
                }
                $arFacturaDetalle->setCodigoImpuestoIvaFk($codigoImpuestoIva);
                $codigoImpuestoRetencion = $arrImpuestoRetencion[$codigoFacturaDetalle];
                if($arFacturaDetalle->getCodigoImpuestoRetencionFk() != $codigoImpuestoRetencion) {
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
        $arRegistro = $em->getRepository(RhuFactura::class)->find($id);
        if ($arRegistro->getEstadoAutorizado() == 0) {
            if ($arrDetallesSeleccionados) {
                if (count($arrDetallesSeleccionados)) {
                    foreach ($arrDetallesSeleccionados as $codigo) {
                        $ar = $this->getEntityManager()->getRepository(RhuFacturaDetalle::class)->find($codigo);
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
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuFacturaDetalle::class, 'fd')
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
            ->addSelect('ci.nombre as ciudad')
            ->addSelect('fd.cantidad')
            ->addSelect('fd.porcentajeIva')
            ->addSelect('fd.vrIva')
            ->addSelect('fd.vrPrecio')
            ->addSelect('fd.vrSubtotal')
            ->addSelect('fd.vrNeto')
            ->addSelect('fd.vrTotal')
            ->addSelect('fd.vrRetencionFuente')
            ->leftJoin('fd.facturaRel', 'f')
            ->leftJoin('fd.itemRel', 'i')
            ->leftJoin('f.clienteRel', 'c')
            ->leftJoin('c.ciudadRel', 'ci');
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
        return $queryBuilder;
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

}
