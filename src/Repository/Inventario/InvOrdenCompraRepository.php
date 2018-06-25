<?php

namespace App\Repository\Inventario;

use App\Controller\Estructura\MensajesController;
use App\Entity\Inventario\InvOrdenCompra;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvOrdenCompraRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvOrdenCompra::class);
    }

    public function camposPredeterminados()
    {
        $qb = $this->getEntityManager()->createQueryBuilder()->from('App:Inventario\InvOrdenCompra', 'ioc');
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
     */
    public function aprobar($arOrdenCompra)
    {
        $arOrdenCompraTipo = $this->_em->getRepository('App:Inventario\InvOrdenCompraTipo')->find($arOrdenCompra->getCodigoOrdenCompraTipoFk());
        if (!$arOrdenCompra->getEstadoAprobado()) {
            $arOrdenCompraTipo->setConsecutivo($arOrdenCompraTipo->getConsecutivo() + 1);
            $arOrdenCompra->setEstadoAprobado(1);
            $arOrdenCompra->setNumero($arOrdenCompraTipo->getConsecutivo());
            $this->_em->persist($arOrdenCompraTipo);
            $this->_em->persist($arOrdenCompra);

            $arOrdenCompraDetalles = $this->_em->getRepository('App:Inventario\InvOrdenCompraDetalle')->findBy(['codigoOrdenCompraFk' => $arOrdenCompra->getCodigoOrdenCompraPk()]);
            foreach ($arOrdenCompraDetalles as $arOrdenCompraDetalle) {
                $arItem = $this->_em->getRepository('App:Inventario\InvItem')->findOneBy(['codigoItemPk' => $arOrdenCompraDetalle->getCodigoItemFk()]);
                if ($arOrdenCompraDetalle->getCodigoSolicitudDetalleFk()) {
                    $arItem->setCantidadSolicitud($arItem->getCantidadSolicitud() - $arOrdenCompraDetalle->getCantidadSolicitada());
                }
                $arItem->setCantidadOrdenCompra($arItem->getCantidadOrdenCompra() + $arOrdenCompraDetalle->getCantidadSolicitada());
                $this->_em->persist($arItem);
            }
            $this->_em->flush();
        }
    }

    /**
     * @param $arOrdenCompra InvOrdenCompra
     */
    public function anular($arOrdenCompra)
    {
        if ($arOrdenCompra->getEstadoAprobado() == 1) {
            $arOrdenCompra->setEstadoAnulado(1);
            $this->_em->persist($arOrdenCompra);
            $this->_em->flush();

            $arOrdenCompraDetalles = $this->_em->getRepository('App:Inventario\InvOrdenCompraDetalle')->findBy(['codigoOrdenCompraFk' => $arOrdenCompra->getCodigoOrdenCompraPk()]);
            foreach ($arOrdenCompraDetalles as $arOrdenCompraDetalle) {
                $arItem = $this->_em->getRepository('App:Inventario\InvItem')->findOneBy(['codigoItemPk' => $arOrdenCompraDetalle->getCodigoItemFk()]);
                if ($arOrdenCompraDetalle->getCodigoSolicitudDetalleFk()) {
                    $arItem->setCantidadSolicitud($arItem->getCantidadSolicitud() + $arOrdenCompraDetalle->getCantidad());
                }
                $arItem->setCantidadOrdenCompra($arItem->getCantidadOrdenCompra() - $arOrdenCompraDetalle->getCantidadSolicitada());
                $this->_em->persist($arItem);
            }
            $this->_em->flush();
        }
    }

    /**
     * @param $arOrdenCompra InvOrdenCompra
     */
    public function desautorizar($arOrdenCompra)
    {
        if ($arOrdenCompra->getEstadoAutorizado() == 1 && $arOrdenCompra->getEstadoAprobado() == 0) {
            $arOrdenCompra->setEstadoAutorizado(0);
            $this->_em->persist($arOrdenCompra);
            $this->_em->flush();
        } else {
            MensajesController::error('El registro esta impreso y no se puede desautorizar');
        }
    }

    /**
     * @param $arOrdenCompra InvOrdenCompra
     */
    public function autorizar($arOrdenCompra)
    {
        if (count($this->_em->getRepository('App:Inventario\InvOrdenCompraDetalle')->findBy(['codigoOrdenCompraFk' => $arOrdenCompra->getCodigoOrdenCompraPk()])) > 0) {
            $arOrdenCompra->setEstadoAutorizado(1);
            $this->_em->persist($arOrdenCompra);
            $this->_em->flush();
        } else {
            MensajesController::error('No se puede autorizar, el registro no tiene detalles');
        }
    }

    /**
     * @param $arOrdenCompra InvOrdenCompra
     * @param $arrCantidad array
     * @param $arrIva array
     */
    public function actualizar($arOrdenCompra, $arrValor, $arrCantidad, $arrIva)
    {
        $vrTotalGlobal = 0;
        $vrIvaGlobal = 0;
        $vrSubtotalGlobal = 0;
        $arOrdenCompraDetalles = $this->_em->getRepository('App:Inventario\InvOrdenCompraDetalle')->findBy(['codigoOrdenCompraFk' => $arOrdenCompra->getCodigoOrdenCompraPk()]);
        if (count($arOrdenCompraDetalles) > 0) {
            foreach ($arOrdenCompraDetalles as $arOrdenCompraDetalle) {
                $id = $arOrdenCompraDetalle->getCodigoOrdenCompraDetallePk();
                $vrUnitario = $arrValor[$id] != '' ? $arrValor[$id] : 0;
                $cantidad = $arrCantidad[$id] != '' ? $arrCantidad[$id] : 0;
                $porIva = $arrIva[$id] != '' ? $arrIva[$id] : 0;

                $vrSubtotal = $vrUnitario * $cantidad;
                $vrIva = $vrSubtotal * ($porIva / 100);
                $vrTotal = $vrSubtotal + $vrIva;

                $vrTotalGlobal += $vrTotal;
                $vrIvaGlobal += $vrIva;
                $vrSubtotalGlobal += $vrSubtotal;

                $arOrdenCompraDetalle->setPorIva($porIva);
                $arOrdenCompraDetalle->setCantidadSolicitada($cantidad);
                $arOrdenCompraDetalle->setVrUnitario($vrUnitario);
                $arOrdenCompraDetalle->setVrSubtotal($vrSubtotal);
                $arOrdenCompraDetalle->setVrIva($vrIva);
                $arOrdenCompraDetalle->setVrTotal($vrTotal);
                $this->_em->persist($arOrdenCompraDetalle);
            }

            $arOrdenCompra->setVrIva($vrIvaGlobal);
            $arOrdenCompra->setVrSubtotal($vrSubtotalGlobal);
            $arOrdenCompra->setVrTotal($vrTotalGlobal);
            $this->_em->persist($arOrdenCompra);
        } else {
            $arOrdenCompra->setVrIva(0);
            $arOrdenCompra->setVrSubtotal(0);
            $arOrdenCompra->setVrTotal(0);
            $this->_em->persist($arOrdenCompra);
        }
        $this->_em->flush();
    }
}