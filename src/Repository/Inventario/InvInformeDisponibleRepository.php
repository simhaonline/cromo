<?php

namespace App\Repository\Inventario;

use App\Entity\General\GenImpuesto;
use App\Entity\Inventario\InvInformeDisponible;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvRemisionDetalle;
use App\Entity\Turno\TurCostoEmpleado;
use App\Utilidades\Mensajes;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Inventario\InvMovimientoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvInformeDisponibleRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvInformeDisponible::class);
    }
    public function lista($usuario)
    {
        $em = $this->getEntityManager();
        $session = new Session();
        $queryBuilder = $em->createQueryBuilder()->from(InvInformeDisponible::class, 'id')
            ->select('id.pk')
            ->addSelect('id.tipo')
            ->addSelect('id.fecha')
            ->addSelect('id.cantidad')
            ->addSelect('id.cantidadOperada')
            ->addSelect('id.nombreDocumento')
            ->addSelect('id.codigoRemisionDetalleFk')
            ->addSelect('id.numero')
            ->addSelect('id.codigoItemFk')
            ->addSelect('id.nombreItem')
            ->addSelect('id.referencia')
            ->addSelect('id.codigoBodegaFk')
            ->addSelect('id.loteFk')
            ->addSelect('id.fechaVencimiento')
            ->addSelect('id.disponible')
            ->addSelect('id.usuario')
            ->where("id.usuario='{$usuario}'")
            ->orderBy('id.fecha', 'ASC');
        $arInformeDisponible = $queryBuilder->getQuery()->getResult();
        return $arInformeDisponible;
    }
    public function generarInforme($usuario)
    {
        $em = $this->getEntityManager();
        $session = new Session();
        $em->createQueryBuilder()->delete(InvInformeDisponible::class, 'id')
            ->where("id.usuario = '{$usuario}'")->getQuery()->execute();

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
            ->select('md.codigoMovimientoDetallePk')
            ->addSelect('md.codigoItemFk')
            ->addSelect('i.nombre AS nombreItem')
            ->addSelect('i.referencia as itemReferencia')
            ->addSelect('md.cantidad')
            ->addSelect('md.cantidadOperada')
            ->addSelect('md.cantidadSaldo')
            ->addSelect('md.vrCosto')
            ->addSelect('md.vrPrecio')
            ->addSelect('md.loteFk')
            ->addSelect('md.codigoBodegaFk')
            ->addSelect('md.fechaVencimiento')
            ->addSelect('m.fecha')
            ->addSelect('m.numero AS numeroMovimiento')
            ->addSelect('d.nombre AS nombreDocumento')
            ->addSelect('md.codigoRemisionDetalleFk')
            ->leftJoin('md.movimientoRel', 'm')
            ->leftJoin('m.documentoRel', 'd')
            ->leftJoin('md.itemRel', 'i')
            ->where('md.codigoMovimientoDetallePk != 0')
            ->andWhere('m.estadoAprobado = 1')
            ->andWhere('m.estadoAnulado = 0')
            ->andWhere('md.operacionInventario <> 0')
            ->orderBy('m.fecha', 'ASC')
            ->addOrderBy('md.codigoMovimientoDetallePk');
        if ($session->get('filtroInvItemCodigo')) {
            $queryBuilder->andWhere("md.codigoItemFk = '{$session->get('filtroInvItemCodigo')}'");
        }
        if ($session->get('filtroInvKardexLote') != '') {
            $queryBuilder->andWhere("md.loteFk = '{$session->get('filtroInvKardexLote')}' ");
        }
        if ($session->get('filtroInvKardexFechaHasta') != null) {
            $queryBuilder->andWhere("m.fecha <= '{$session->get('filtroInvKardexFechaHasta')} 23:59:59'");
        }
        $arMovimientos =$queryBuilder->getQuery()->getResult();
        foreach ($arMovimientos as $arMovimiento) {
            $arInformeDisponible = new InvInformeDisponible();
            $arInformeDisponible->setPk($arMovimiento['codigoMovimientoDetallePk']);
            $arInformeDisponible->setTipo('M');
            $arInformeDisponible->setFecha($arMovimiento['fecha']);
            $arInformeDisponible->setCantidad($arMovimiento['cantidad']);
            $arInformeDisponible->setCantidadOperada($arMovimiento['cantidadOperada']);
            $arInformeDisponible->setNombreDocumento($arMovimiento['nombreDocumento']);
            $arInformeDisponible->setCodigoRemisionDetalleFk($arMovimiento['codigoRemisionDetalleFk']);
            $arInformeDisponible->setNumero($arMovimiento['numeroMovimiento']);
            $arInformeDisponible->setCodigoItemFk($arMovimiento['codigoItemFk']);
            $arInformeDisponible->setNombreItem($arMovimiento['nombreItem']);
            $arInformeDisponible->setReferencia($arMovimiento['itemReferencia']);
            $arInformeDisponible->setCodigoBodegaFk($arMovimiento['codigoBodegaFk']);
            $arInformeDisponible->setLoteFk($arMovimiento['loteFk']);
            $arInformeDisponible->setFechaVencimiento($arMovimiento['fechaVencimiento']);
            $arInformeDisponible->setUsuario($usuario);
            $em->persist($arInformeDisponible);
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvRemisionDetalle::class, 'rd')
            ->select('rd.codigoRemisionDetallePk')
            ->addSelect('rd.codigoItemFk')
            ->addSelect('i.nombre AS nombreItem')
            ->addSelect('i.referencia')
            ->addSelect('rd.cantidad')
            ->addSelect('rd.cantidadOperada')
            ->addSelect('rd.cantidadPendiente')
            ->addSelect('rd.vrPrecio')
            ->addSelect('rd.loteFk')
            ->addSelect('rd.codigoBodegaFk')
            ->addSelect('rd.fechaVencimiento')
            ->addSelect('r.fecha')
            ->addSelect('r.numero AS numeroRemision')
            ->addSelect('rt.nombre AS remisionTipo')
            ->leftJoin('rd.remisionRel', 'r')
            ->leftJoin('r.remisionTipoRel', 'rt')
            ->leftJoin('rd.itemRel', 'i')
            ->where('rd.codigoRemisionDetallePk != 0')
            ->andWhere('r.estadoAprobado = 1')
            ->andWhere('r.estadoAnulado = 0')
            ->andWhere('rd.operacionInventario <> 0')
            ->orderBy('r.fecha', 'ASC');
        if ($session->get('filtroInvItemCodigo')) {
            $queryBuilder->andWhere("rd.codigoItemFk = '{$session->get('filtroInvItemCodigo')}'");
        }
        if ($session->get('filtroInvKardexLote') != '') {
            $queryBuilder->andWhere("rd.loteFk = '{$session->get('filtroInvKardexLote')}' ");
        }
        if ($session->get('filtroInvKardexFechaHasta') != null) {
            $queryBuilder->andWhere("r.fecha <= '{$session->get('filtroInvKardexFechaHasta')} 23:59:59'");
        }
        $arRemisiones = $queryBuilder->getQuery()->getResult();
        foreach ($arRemisiones as $arRemision) {
            $arInformeDisponible = new InvInformeDisponible();
            $arInformeDisponible->setPk($arRemision['codigoRemisionDetallePk']);
            $arInformeDisponible->setTipo('R');
            $arInformeDisponible->setFecha($arRemision['fecha']);
            $arInformeDisponible->setCantidad($arRemision['cantidad']);
            $arInformeDisponible->setCantidadOperada($arRemision['cantidadOperada']*-1);
            $arInformeDisponible->setNombreDocumento($arRemision['remisionTipo']);
            $arInformeDisponible->setNumero($arRemision['numeroRemision']);
            $arInformeDisponible->setCodigoItemFk($arRemision['codigoItemFk']);
            $arInformeDisponible->setNombreItem($arRemision['nombreItem']);
            $arInformeDisponible->setReferencia($arRemision['referencia']);
            $arInformeDisponible->setCodigoBodegaFk($arRemision['codigoBodegaFk']);
            $arInformeDisponible->setLoteFk($arRemision['loteFk']);
            $arInformeDisponible->setFechaVencimiento($arRemision['fechaVencimiento']);
            $arInformeDisponible->setUsuario($usuario);
            $em->persist($arInformeDisponible);
        }
        $em->flush();

        //Actualizar la cantidad disponible
        $disponible = 0;
        $qb = $em->createQueryBuilder()->from(InvInformeDisponible::class, 'id')
            ->select('id.codigoInformeDisponiblePk')
            ->addSelect('id.cantidadOperada')
            ->addSelect('id.codigoRemisionDetalleFk')
            ->addSelect('id.tipo')
            ->orderBy('id.fecha', 'ASC')
            ->where("id.usuario='{$usuario}'");
        $arInformeDisponibles = $qb->getQuery()->getResult();
        foreach ($arInformeDisponibles as $arInformeDisponible) {
            if($arInformeDisponible['tipo'] == 'M' && $arInformeDisponible['codigoRemisionDetalleFk']) {
                $disponible = $disponible;
            } else {
                $disponible += $arInformeDisponible['cantidadOperada'];
            }

            $arInformeDisponibleAct = $em->getRepository(InvInformeDisponible::class)->find($arInformeDisponible['codigoInformeDisponiblePk']);
            $arInformeDisponibleAct->setDisponible($disponible);
            $em->persist($arInformeDisponibleAct);

        }
        $em->flush();
        return true;
    }

}