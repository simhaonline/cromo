<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvInventarioValorizado;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Session\Session;

class InvInventarioValorizadoRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvInventarioValorizado::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvInventarioValorizado::class, 'iv')
            ->select('iv.codigoInventarioValorizadoPk')
            ->addSelect('iv.codigoItemFk')
            ->addSelect('iv.fecha')
            ->addSelect('i.nombre AS nombreItem')
            ->addSelect('iv.saldo')
            ->addSelect('iv.vrCosto')
            ->addSelect('iv.vrCostoTotal')
            ->leftJoin('iv.itemRel', 'i');
        if ($session->get('filtroInvInformeItemValorizadoCodigo')) {
            $queryBuilder->andWhere("iv.codigoItemFk = '{$session->get('filtroInvInformeItemValorizadoCodigo')}'");
        }
        return $queryBuilder;
    }

    public function generar($fechaHasta)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('DELETE App\Entity\Inventario\InvInventarioValorizado iv ');
        $query->execute();
        $arItems = $em->getRepository(InvItem::class)->findBy(array('afectaInventario' => 1));
        foreach ($arItems as $arItem) {
            $arInventarioValorizado = new InvInventarioValorizado();
            $arInventarioValorizado->setItemRel($arItem);
            $arInventarioValorizado->setFecha(date_create($fechaHasta));
            $arInventarioValorizado->setSaldo(0);
            $arInventarioValorizado->setVrCosto(0);
            $arInventarioValorizado->getVrCostoTotal(0);
            $arMovimientoDetalle = $em->getRepository(InvMovimientoDetalle::class)->registroFecha($arItem->getCodigoItemPk(), $fechaHasta);
            if($arMovimientoDetalle) {
                $arMovimientoDetalle = $arMovimientoDetalle[0];
                $arInventarioValorizado->setSaldo($arMovimientoDetalle['cantidadSaldo']);
                $arInventarioValorizado->setVrCosto($arMovimientoDetalle['vrCosto']);
                $total = $arMovimientoDetalle['vrCosto'] * $arMovimientoDetalle['cantidadSaldo'];
                $arInventarioValorizado->setVrCostoTotal($total);
            }
            $em->persist($arInventarioValorizado);
        }
        $em->flush();
    }

}