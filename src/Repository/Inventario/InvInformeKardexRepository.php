<?php

namespace App\Repository\Inventario;

use App\Entity\General\GenImpuesto;
use App\Entity\Inventario\InvInformeDisponible;
use App\Entity\Inventario\InvInformeKardex;
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

class InvInformeKardexRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvInformeKardex::class);
    }
    public function lista($usuario)
    {
        $em = $this->getEntityManager();
        $session = new Session();
        $queryBuilder = $em->createQueryBuilder()->from(InvInformeKardex::class, 'ik')
            ->select('ik.codigoMovimientoDetalle')
            ->addSelect('ik.fecha')
            ->addSelect('ik.cantidad')
            ->addSelect('ik.cantidadOperada')
            ->addSelect('ik.nombreDocumento')
            ->addSelect('ik.codigoRemisionDetalleFk')
            ->addSelect('ik.numero')
            ->addSelect('ik.codigoItemFk')
            ->addSelect('ik.nombreItem')
            ->addSelect('ik.referencia')
            ->addSelect('ik.codigoBodegaFk')
            ->addSelect('ik.loteFk')
            ->addSelect('ik.fechaVencimiento')
            ->addSelect('ik.saldo')
            ->addSelect('ik.vrCosto')
            ->addSelect('ik.vrPrecio')
            ->addSelect('ik.usuario')

            ->where("ik.usuario='{$usuario}'")
            ->orderBy('ik.fecha', 'ASC');
        $arInformeDisponible = $queryBuilder->getQuery()->getResult();
        return $arInformeDisponible;
    }
    public function generarInforme($usuario)
    {
        $em = $this->getEntityManager();
        $session = new Session();
        $em->createQueryBuilder()->delete(InvInformeKardex::class, 'ik')
            ->where("ik.usuario = '{$usuario}'")->getQuery()->execute();
        $saldo = 0;
        $arMovimientos = $em->getRepository(InvMovimientoDetalle::class)->listaKardex()->getQuery()->getResult();
        foreach ($arMovimientos as $arMovimiento) {
            $saldo += $arMovimiento['cantidadOperada'];
            $arInformeKardex = new InvInformeKardex();
            $arInformeKardex->setCodigoMovimientoDetalle($arMovimiento['codigoMovimientoDetallePk']);
            $arInformeKardex->setFecha($arMovimiento['fecha']);
            $arInformeKardex->setCantidad($arMovimiento['cantidad']);
            $arInformeKardex->setCantidadOperada($arMovimiento['cantidadOperada']);
            $arInformeKardex->setSaldo($saldo);
            $arInformeKardex->setNombreDocumento($arMovimiento['nombreDocumento']);
            $arInformeKardex->setCodigoRemisionDetalleFk($arMovimiento['codigoRemisionDetalleFk']);
            $arInformeKardex->setNumero($arMovimiento['numeroMovimiento']);
            $arInformeKardex->setCodigoItemFk($arMovimiento['codigoItemFk']);
            $arInformeKardex->setNombreItem($arMovimiento['nombreItem']);
            $arInformeKardex->setReferencia($arMovimiento['itemReferencia']);
            $arInformeKardex->setCodigoBodegaFk($arMovimiento['codigoBodegaFk']);
            $arInformeKardex->setLoteFk($arMovimiento['loteFk']);
            $arInformeKardex->setFechaVencimiento($arMovimiento['fechaVencimiento']);
            $arInformeKardex->setVrCosto($arMovimiento['vrCosto']);
            $arInformeKardex->setVrPrecio($arMovimiento['vrPrecio']);
            $arInformeKardex->setUsuario($usuario);
            $em->persist($arInformeKardex);
        }
        $em->flush();
        return true;
    }

}