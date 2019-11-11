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
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurFacturaDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
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
            $mensajeError = "";
            foreach ($arrCodigo as $codigoFacturaDetalle) {
                $arFacturaDetalle = $this->getEntityManager()->getRepository(TurFacturaDetalle::class)->find($codigoFacturaDetalle);
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

}
