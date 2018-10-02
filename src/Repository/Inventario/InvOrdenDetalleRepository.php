<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvMovimientoDetalle;
use App\Entity\Inventario\InvSolicitudDetalle;
use App\Utilidades\Mensajes;
use App\Entity\Inventario\InvOrden;
use App\Entity\Inventario\InvOrdenDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvOrdenDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvOrdenDetalle::class);
    }

    public function camposPredeterminados()
    {
        $qb = $this->getEntityManager()->createQueryBuilder()->from(InvOrdenCompra::class, 'ioc');
        $qb
            ->select('ioc.codigoOrdenCompraPk as ID')
            ->addSelect('ioc.numero as NUMERO')
            ->addSelect('ioc.fecha as FECHA')
            ->addSelect('ioc.soporte as SOPORTE')
            ->addSelect('ioc.estadoAutorizado as AUTORIZADO')
            ->addSelect('ioc.estadoAprobado as APROBADO')
            ->addSelect('ioc.estadoAnulado as ANULADO');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();
    }

    /**
     * @param $arOrdenCompra InvOrdenCompra
     * @param $arrDetallesSeleccionados
     */
    public function eliminar($arOrdenCompra, $arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arOrdenCompra->getEstadoAutorizado() == 0) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigoOrdenDetalle) {
                    $arOrdenDetalle = $em->getRepository(InvOrdenDetalle::class)->find($codigoOrdenDetalle);
                    if ($arOrdenDetalle) {
                        if ($arOrdenDetalle->getCodigoSolicitudDetalleFk() != '') {
                            $arSolicitudDetalle = $em->getRepository(InvSolicitudDetalle::class)->find($arOrdenDetalle->getCodigoSolicitudDetalleFk());
                            if ($arSolicitudDetalle) {
                                $arSolicitudDetalle->setCantidadPendiente($arSolicitudDetalle->getCantidadPendiente() + $arOrdenDetalle->getCantidad());
                                $em->persist($arSolicitudDetalle);
                            }
                        }
                        //Si el detalle tiene una solicitud detalle relacionado
                        if ($arOrdenDetalle->getCodigoSolicitudDetalleFk()) {
                            $arSolicitudDetalle = $em->getRepository(InvSolicitudDetalle::class)->find($arOrdenDetalle->getCodigoSolicitudDetalleFk());
                            $arSolicitudDetalle->setCantidadAfectada($arSolicitudDetalle->getCantidadAfectada() - $arOrdenDetalle->getCantidad());
                            $arSolicitudDetalle->setCantidadPendiente($arSolicitudDetalle->getCantidad() - $arSolicitudDetalle->getCantidadAfectada());
                            $em->persist($arSolicitudDetalle);
                        }

                        $em->remove($arOrdenDetalle);
                    }
                }
                try {
                    $em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                }
            }
        } else {
            Mensajes::error('No se puede eliminar, el registro se encuentra autorizado');
        }
    }

    public function listarDetallesPendientes()
    {
        $em = $this->getEntityManager();
        $session = new Session();
        $queryBuilder = $em->createQueryBuilder()->from(InvOrdenDetalle::class, 'od')
            ->select('od.codigoOrdenDetallePk')
            ->addSelect('it.codigoItemPk AS codigoItem')
            ->addSelect('it.nombre')
            ->addSelect('it.cantidadExistencia')
            ->addSelect('od.cantidad')
            ->addSelect('od.cantidadPendiente')
            ->addSelect('it.stockMinimo')
            ->addSelect('it.stockMaximo')
            ->addSelect('o.numero AS ordenCompra')
            ->join('od.itemRel', 'it')
            ->join('od.ordenRel', 'o')
            ->where('o.estadoAprobado = 1')
            ->where('o.estadoAnulado = 0')
            ->andWhere('od.cantidadPendiente > 0')
        ->orderBy('od.codigoOrdenDetallePk', 'ASC');
        if ($session->get('filtroInvMovimientoItemCodigo') != '') {
            $queryBuilder->andWhere("od.codigoItemFk = {$session->get('filtroInvMovimientoItemCodigo')}");
        }
        if ($session->get('filtroInvNumeroOrden') != '') {
            $queryBuilder->andWhere("o.numero = {$session->get('filtroInvNumeroOrden')}");
        }
        $query = $em->createQuery($queryBuilder->getDQL());
        return $query->execute();
    }

    /**
     * @param $codigoOrden
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista($codigoOrden){
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvOrdenDetalle::class,'od')
            ->select('od.codigoOrdenDetallePk')
            ->addSelect('od.codigoItemFk')
            ->addSelect('i.nombre')
            ->addSelect('od.cantidad')
            ->addSelect('od.vrPrecio')
            ->addSelect('od.vrSubtotal')
            ->addSelect('od.porcentajeDescuento')
            ->addSelect('od.vrDescuento')
            ->addSelect('od.porcentajeIva')
            ->addSelect('od.vrIva')
            ->addSelect('od.vrTotal')
            ->join('od.itemRel','i')
            ->where("od.codigoOrdenFk = {$codigoOrden}");
        return $queryBuilder;
    }

    /**
     * @param $arrControles array
     * @param $arMovimiento InvOrden
     * @param $form FormInterface
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarDetalles($arrControles, $arOrden)
    {
        $em = $this->getEntityManager();
        $this->getEntityManager()->persist($arOrden);
        $mensajeError = "";
        if(isset($arrControles['arrCodigo'])) {
            $arrCantidad = $arrControles['arrCantidad'];
            $arrPrecio = $arrControles['arrValor'];
            $arrPorcentajeDescuento = $arrControles['arrDescuento'];
            $arrPorcentajeIva = $arrControles['arrIva'];
            $arrCodigo = $arrControles['arrCodigo'];
            foreach ($arrCodigo as $codigoOrdenDetalle) {
                $arOrdenDetalle = $this->getEntityManager()->getRepository(InvOrdenDetalle::class)->find($codigoOrdenDetalle);
                $cantidadAnterior = $arOrdenDetalle->getCantidad();
                $cantidadNueva = $arrCantidad[$codigoOrdenDetalle];
                $arOrdenDetalle->setCantidad($arrCantidad[$codigoOrdenDetalle]);
                $arOrdenDetalle->setCantidadPendiente($arrCantidad[$codigoOrdenDetalle]);
                $arOrdenDetalle->setVrPrecio($arrPrecio[$codigoOrdenDetalle]);
                $arOrdenDetalle->setPorcentajeDescuento($arrPorcentajeDescuento[$codigoOrdenDetalle]);
                $arOrdenDetalle->setPorcentajeIva($arrPorcentajeIva[$codigoOrdenDetalle]);
                $em->persist($arOrdenDetalle);
                //Si tiene pedido enlazado
                if ($arOrdenDetalle->getCodigoSolicitudDetalleFk()) {
                    $cantidadAfectar = $cantidadNueva - $cantidadAnterior;
                    if ($cantidadAfectar != 0) {
                        $arSolicitudDetalle = $em->getRepository(InvSolicitudDetalle::class)->find($arOrdenDetalle->getCodigoSolicitudDetalleFk());
                        if ($cantidadAfectar <= $arSolicitudDetalle->getCantidadPendiente()) {
                            $arSolicitudDetalle->setCantidadAfectada($arSolicitudDetalle->getCantidadAfectada() + $cantidadAfectar);
                            $arSolicitudDetalle->setCantidadPendiente($arSolicitudDetalle->getCantidad() - $arSolicitudDetalle->getCantidadAfectada());
                            $em->persist($arSolicitudDetalle);
                        } else {
                            $mensajeError = "El id " . $codigoOrdenDetalle . " va afectar mas cantidades de las pendientes en el detalle relacionado";
                            break;
                        }
                    }
                }
            }
        }

        if ($mensajeError == "") {
            $em->flush();
            $em->getRepository(InvOrden::class)->liquidar($arOrden);

        } else {
            Mensajes::error($mensajeError);
        }


    }

    public function informeOrdenCompraPendientes()
    {
        $em = $this->getEntityManager();
        $session = new Session();
        $queryBuilder = $em->createQueryBuilder()->from(InvOrdenDetalle::class, 'od')
            ->select('od.codigoOrdenDetallePk')
            ->addSelect('i.codigoItemPk AS codigoItem')
            ->addSelect('o.numero')
            ->addSelect('i.nombre')
            ->addSelect('i.cantidadExistencia')
            ->addSelect('od.cantidad')
            ->addSelect('od.cantidadPendiente')
            ->addSelect('i.stockMinimo')
            ->addSelect('i.stockMaximo')
            ->join('od.itemRel', 'i')
            ->join('od.ordenRel', 'o')
            ->where('o.estadoAprobado = 1')
            ->where('o.estadoAnulado = 0')
            ->andWhere('od.cantidadPendiente > 0')
            ->orderBy('od.codigoOrdenDetallePk', 'ASC');
        if ($session->get('filtroInvCodigoOrdenTipo') != null) {
            $queryBuilder->andWhere("o.codigoOrdenTipoFk = '{$session->get('filtroInvCodigoOrdenTipo')}'");
        }
        if ($session->get('filtroInvOrdenNumero') != '') {
            $queryBuilder->andWhere("o.numero = {$session->get('filtroInvOrdenNumero')}");
        }

        return $queryBuilder;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function regenerarCantidadAfectada() {
        $em = $this->getEntityManager();
        $arDetalles = $this->listaRegenerarCantidadAfectada();
        foreach ($arDetalles as $arDetalle) {
            $cantidad = $arDetalle['cantidad'];
            $cantidadAfectada = $em->getRepository(InvMovimientoDetalle::class)->cantidadAfectaOrden($arDetalle['codigoOrdenDetallePk']);
            $cantidadPendiente = $cantidad - $cantidadAfectada;
            if($cantidadAfectada != $arDetalle['cantidadAfectada'] || $cantidadPendiente != $arDetalle['cantidadPendiente']) {
                $arDetalleAct = $em->getRepository(InvOrdenDetalle::class)->find($arDetalle['codigoOrdenDetallePk']);
                $arDetalleAct->setCantidadAfectada($cantidadAfectada);
                $arDetalleAct->setCantidadPendiente($cantidadPendiente);
                $em->persist($arDetalleAct);
            }
        }
        $em->flush();
    }

    private function listaRegenerarCantidadAfectada()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(InvOrdenDetalle::class, 'od')
            ->select('od.codigoOrdenDetallePk')
            ->addSelect('od.cantidad')
            ->addSelect('od.cantidadAfectada')
            ->addSelect('od.cantidadPendiente');
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $arCodigo
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function cantidadAfectaSolicitud($arCodigo)
    {
        $cantidad = 0;
        $queryBuilder = $this->_em->createQueryBuilder()->from(InvOrdenDetalle::class, 'r')
            ->select("SUM(r.cantidad)")
            ->where("r.codigoSolicitudDetalleFk = {$arCodigo} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        if ($resultado[1]) {
            $cantidad = $resultado[1];
        }
        return $cantidad;
    }
}