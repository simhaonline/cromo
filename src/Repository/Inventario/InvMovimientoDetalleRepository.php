<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvSucursal;
use App\Utilidades\Mensajes;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Inventario\InvMovimientoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class InvMovimientoDetalleRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvMovimientoDetalle::class);
    }

    /**
     * @param $arMovimiento InvMovimiento
     * @param $arrSeleccionados array
     */
    public function eliminar($arMovimiento, $arrSeleccionados)
    {
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados as $codigoMovimientoDetalle) {
                $arMovimientoDetalle = $this->_em->getRepository('App:Inventario\InvMovimientoDetalle')->find($codigoMovimientoDetalle);
                if ($arMovimientoDetalle) {
                    if ($arMovimientoDetalle->getCodigoOrdenCompraDetalleFk()) {
                        $arOrdenCompraDetalle = $this->_em->getRepository('App:Inventario\InvOrdenCompraDetalle')->findOneBy(['codigoOrdenCompraDetallePk' => $arMovimientoDetalle->getCodigoOrdenCompraDetalleFk()]);
                        if ($arOrdenCompraDetalle) {
                            $arOrdenCompraDetalle->setCantidadPendiente($arOrdenCompraDetalle->getCantidadPendiente() + $arMovimientoDetalle->getCantidad());
                            $this->_em->persist($arOrdenCompraDetalle);
                        }
                    }
                    $this->_em->remove($arMovimientoDetalle);
                }
            }
            $this->_em->flush();
        }
    }

    public function listarItems($nombreItem = '', $codigoItem = '')
    {
        $qb = $this->_em->createQueryBuilder()->from('App:Inventario\InvItem', 'ii')
            ->select('ii.codigoItemPk')
            ->addSelect('ii.nombre')
            ->addSelect('ii.cantidadExistencia')
            ->addSelect('ii.cantidadOrdenCompra')
            ->addSelect('ii.cantidadSolicitud')
            ->addSelect('ii.stockMinimo')
            ->addSelect('ii.stockMaximo')
            ->where('ii.codigoItemPk <> 0');
        if ($codigoItem != '') {
            $qb->andWhere("ii.codigoItemPk = {$codigoItem}");
        }
        if ($nombreItem != '') {
            $qb->andWhere("ii.nombre LIKE '%{$nombreItem}%' ");
        }
        $qb->orderBy('ii.codigoItemPk', 'ASC');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    /**
     * @param $arrControles array
     * @param $arMovimiento InvMovimiento
     * @param $form FormInterface
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarDetalles($arrControles, $form, $arMovimiento)
    {
        if ($this->contarDetalles($arMovimiento->getCodigoMovimientoPk()) > 0) {
            $arMovimiento->setSucursalRel($this->getEntityManager()->getRepository(InvSucursal::class)->find($form->get('sucursalRel')->getData()));
            $arrBodega = $arrControles['arrBodega'];
            $arrLote = $arrControles['arrLote'];
            $arrCantidad = $arrControles['arrCantidad'];
            $arrPrecio = $arrControles['arrValor'];
            $arrPorcentajeDescuento = $arrControles['arrDescuento'];
            $arrPorcentajeIva = $arrControles['arrIva'];
            $arrCodigo = $arrControles['arrCodigo'];
            foreach ($arrCodigo as $codigoMovimientoDetalle) {
                $arMovimientoDetalle = $this->getEntityManager()->getRepository(InvMovimientoDetalle::class)->find($codigoMovimientoDetalle);
                $arMovimientoDetalle->setCodigoBodegaFk($arrBodega[$codigoMovimientoDetalle]);
                $arMovimientoDetalle->setLoteFk($arrLote[$codigoMovimientoDetalle]);
                $arMovimientoDetalle->setCantidad($arrCantidad[$codigoMovimientoDetalle]);
                $arMovimientoDetalle->setVrPrecio($arrPrecio[$codigoMovimientoDetalle]);
                $arMovimientoDetalle->setPorcentajeDescuento($arrPorcentajeDescuento[$codigoMovimientoDetalle]);
                $arMovimientoDetalle->setPorcentajeIva($arrPorcentajeIva[$codigoMovimientoDetalle]);
                $this->getEntityManager()->persist($arMovimientoDetalle);
            }
            $this->getEntityManager()->getRepository(InvMovimiento::class)->liquidar($arMovimiento);
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param $codigoMovimiento
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function contarDetalles($codigoMovimiento)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimientoDetalle::class, 'imd')
            ->select("COUNT(imd.codigoMovimientoDetallePk)")
            ->where("imd.codigoMovimientoFk = {$codigoMovimiento} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }
}