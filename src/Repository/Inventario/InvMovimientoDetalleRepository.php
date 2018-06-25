<?php

namespace App\Repository\Inventario;

use App\Controller\Estructura\MensajesController;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Inventario\InvMovimientoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

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
                    if($arMovimientoDetalle->getCodigoOrdenCompraDetalleFk()){
                        $arOrdenCompraDetalle = $this->_em->getRepository('App:Inventario\InvOrdenCompraDetalle')->findOneBy(['codigoOrdenCompraDetallePk' => $arMovimientoDetalle->getCodigoOrdenCompraDetalleFk()]);
                        if($arOrdenCompraDetalle){
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
}