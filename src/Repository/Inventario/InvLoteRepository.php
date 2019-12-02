<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class InvLoteRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvLote::class);
    }

    /**
     * @param $arMovimientoDetalle
     * @param $codigoLote
     * @param $codigoBodega
     * @return string
     */
    public function afectar($arMovimientoDetalle, $codigoLote, $codigoBodega)
    {
        $respuesta = '';
        $operacionInv = $arMovimientoDetalle->getMovimientoRel()->getDocumentoRel()->getOperacionInventario();
        $arBodega = $this->_em->getRepository('App:Inventario\InvBodega')->find($codigoBodega);
        if ($codigoLote == '') {
            $respuesta = 'Debe ingresar un numero de lote para el detalle ' . $arMovimientoDetalle->getCodigoMovimientoDetallePk();
        } else {
            $arLote = $this->_em->getRepository('App:Inventario\InvLote')->findOneBy(['codigoItemFk' => $arMovimientoDetalle->getCodigoItemFk(), 'codigoBodegaFk' => $codigoBodega, 'loteFk' => $codigoLote]);
            if (!$arLote && $operacionInv == 1) {
                $arLote = new InvLote();
                $arLote->setCodigoBodegaFk($arBodega->getCodigoBodegaPk());
                $arLote->setCodigoItemFk($arMovimientoDetalle->getCodigoItemFk());
                $arLote->setBodegaRel($arBodega);
                $arLote->setItemRel($arMovimientoDetalle->getItemRel());
                $arLote->setCantidadDisponible($arMovimientoDetalle->getCantidad());
                $arLote->setCantidadExistencia($arMovimientoDetalle->getCantidad());
                $arLote->setLoteFk($codigoLote);
                $this->_em->persist($arLote);
                $this->_em->flush();
                $arMovimientoDetalle->setLoteFk($codigoLote);
                $this->_em->persist($arMovimientoDetalle);
            } elseif (!$arLote && $operacionInv == 2) {
                $respuesta = 'El lote ' . $codigoLote . ', no existe en la bodega ' . $arBodega->getCodigoBodegaPk();
            } elseif ($arLote) {
                if ($operacionInv == 1) {
                    $arLote->setCantidadDisponible($arLote->getCantidadDisponible() + $arMovimientoDetalle->getCantidad());
                    $arLote->setCantidadExistencia($arLote->getCantidadExistencia() + $arMovimientoDetalle->getCantidad());
                    $this->_em->persist($arLote);
                } else {
                    if ($arLote->getCantidadDisponible() - $arMovimientoDetalle->getCantidad() < 0) {
                        $respuesta = 'El lote ' . $arLote->getLoteFk() . ' de la bodega ' . $arBodega->getCodigoBodegaPk() . ', no tiene suficientes existencias para el item seleccionado.';
                    } else {
                        $arLote->setCantidadDisponible($arLote->getCantidadDisponible() - $arMovimientoDetalle->getCantidad());
                        $arLote->setCantidadExistencia($arLote->getCantidadExistencia() - $arMovimientoDetalle->getCantidad());
                        $this->_em->persist($arLote);
                    }
                }
            }
        }
        return $respuesta;
    }

    public function lista($tipoFactura = false)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvLote::class, 'l')
            ->select('l.codigoLotePk')
            ->addSelect('l.loteFk')
            ->addSelect('l.fechaVencimiento')
            ->addSelect('l.codigoBodegaFk')
            ->addSelect('l.cantidadExistencia')
            ->addSelect('l.cantidadDisponible')
            ->addSelect('l.cantidadRemisionada')
            ->where("l.codigoItemFk = {$session->get('filtroInvBuscarLoteItem')}");
        if($tipoFactura = true  && !$session->get('filtroInvBuscarLoteTodos')){
            $queryBuilder->andWhere("l.cantidadExistencia > 0");
        }
        if ($session->get('filtroInvBuscarLoteCodigo') != '') {
            $queryBuilder->andWhere("l.loteFk LIKE '%{$session->get('filtroInvBuscarLoteCodigo')}%'");
        }
        if ($session->get('filtroInvBuscarBodegaLote') != '') {
            $queryBuilder->andWhere("l.codigoBodegaFk LIKE '%{$session->get('filtroInvBuscarBodegaLote')}%'");
        }
        return $queryBuilder->getQuery()->getResult();
    }

    public function existencia()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvLote::class, 'l')
            ->select('l.codigoItemFk')
            ->addSelect('i.nombre AS itemNombre')
            ->addSelect('i.referencia AS itemReferencia')
            ->addSelect('m.nombre AS marca')
            ->addSelect('l.codigoBodegaFk')
            ->addSelect('l.loteFk')
            ->addSelect('l.cantidadExistencia')
            ->addSelect('l.cantidadRemisionada')
            ->addSelect('l.cantidadDisponible')
            ->addSelect('l.fechaVencimiento')
            ->leftJoin('l.itemRel', 'i')
            ->leftJoin('i.marcaRel', 'm')
            ->where('l.cantidadExistencia > 0')
            ->orderBy('l.codigoItemFk', "ASC")
            ->addOrderBy('l.codigoBodegaFk', "ASC")
            ->addOrderBy('l.codigoItemFk', "ASC");
        if ($session->get('filtroInvInformeItemCodigo') != '') {
            $queryBuilder->andWhere("l.codigoItemFk = {$session->get('filtroInvInformeItemCodigo')}");
        }
        if ($session->get('filtroInvInformeItemReferencia') != '') {
            $queryBuilder->andWhere("i.referencia = {$session->get('filtroInvInformeItemReferencia')}");
        }
        if ($session->get('filtroInvInformeLote') != '') {
            $queryBuilder->andWhere("l.loteFk = '{$session->get('filtroInvInformeLote')}' ");
        }
        if ($session->get('filtroInvInformeLoteBodega') != '') {
            $queryBuilder->andWhere("l.codigoBodegaFk = '{$session->get('filtroInvInformeLoteBodega')}' ");
        }
        if ($session->get('filtroInvInformeFechaVence') != null) {
            $queryBuilder->andWhere("l.fechaVencimiento <= '{$session->get('filtroInvInformeFechaVence')}'");
        }
        return $queryBuilder;
    }

    public function listaCorregirFechaVencimiento($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $codigoItem = null;
        $lote = null;
        $fechaVencimiento = null;
        $bodega = null;
        if ($filtros) {
            $codigoItem = $filtros['codigoItem'] ?? null;
            $lote = $filtros['lote'] ?? null;
            $fechaVencimiento = $filtros['fechaVencimiento'] ?? null;
            $bodega = $filtros['bodega'] ?? null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvLote::class, 'l')
            ->select('l.codigoLotePk')
            ->addSelect('l.codigoItemFk')
            ->addSelect('i.nombre AS itemNombre')
            ->addSelect('i.referencia AS itemReferencia')
            ->addSelect('m.nombre AS marca')
            ->addSelect('l.codigoBodegaFk')
            ->addSelect('l.loteFk')
            ->addSelect('l.cantidadExistencia')
            ->addSelect('l.cantidadRemisionada')
            ->addSelect('l.cantidadDisponible')
            ->addSelect('l.fechaVencimiento')
            ->leftJoin('l.itemRel', 'i')
            ->leftJoin('i.marcaRel', 'm')
            ->orderBy('l.codigoItemFk', "ASC")
            ->addOrderBy('l.codigoBodegaFk', "ASC")
            ->addOrderBy('l.codigoItemFk', "ASC");
        if ($codigoItem) {
            $queryBuilder->andWhere("l.codigoItemFk = {$codigoItem}");
        }
        if ($lote) {
            $queryBuilder->andWhere("l.loteFk  = {$lote}");
        }
//        if ($session->get('filtroInvInformeLote') != '') {
//            $queryBuilder->andWhere("l.referencia = '{$session->get('filtroInvInformeLote')}' ");
//        }
        if ($bodega) {
            $queryBuilder->andWhere("l.codigoBodegaFk = '{$bodega}' ");
        }
        if ($fechaVencimiento) {
            $queryBuilder->andWhere("l.fechaVencimiento <= '{$fechaVencimiento}'");
        }
        $queryBuilder->addOrderBy('l.codigoLotePk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function notificacionDiariaMercanciaVencidaBodega()
    {
        $fechaActual = new \DateTime('now');
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvLote::class, 'l')
            ->select('l.codigoLotePk')
            ->where("l.cantidadDisponible > 0 ")
            ->andWhere("l.fechaVencimiento <= '" . $fechaActual->format("Y-m-d") . "'")
        ->setMaxResults(1);
        $resultados = $queryBuilder->getQuery()->getResult();
        if($resultados) {
            return true;
        } else {
            return false;
        }
    }

    public function corregirFechaVencimiento()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(InvLote::class, 'l')
            ->select('l.loteFk')
            ->addSelect('l.codigoItemFk')
            ->addSelect('l.fechaVencimiento')
            ->groupBy('l.codigoItemFk')
            ->addGroupBy('l.loteFk')
            ->addGroupBy('l.fechaVencimiento');
        $arrLotes = $queryBuilder->getQuery()->getResult();

        foreach ($arrLotes as $arrLote) {
                $query = $em->createQuery("UPDATE App\Entity\Inventario\InvRemisionDetalle rd set rd.fechaVencimiento= '". $arrLote['fechaVencimiento']->format('Y-m-d') . "' WHERE rd.codigoItemFk = " . $arrLote['codigoItemFk'] . " AND rd.loteFk = '" . $arrLote['loteFk'] . "'");
                $query->execute();

            /*
             * Se comenta porque fue para carregir un caso puntual
             * $queryBuilder = $em->createQueryBuilder()->from(InvMovimientoDetalle::class, 'md')
                ->select('md.codigoMovimientoDetallePk')
                ->addSelect('m.fecha')
                ->addSelect('md.fechaVencimiento')
                ->leftJoin('md.movimientoRel', 'm')
                ->where('md.codigoItemFk = ' . $arrLote['codigoItemFk'])
                ->andWhere("md.loteFk = '" . $arrLote['loteFk'] . "'")
                ->orderBy('md.fecha', 'ASC')
            ->setMaxResults(1);
            $arrMovimientoDetalle = $queryBuilder->getQuery()->getResult();
            if($arrMovimientoDetalle) {
                $fecha = $arrMovimientoDetalle[0]['fechaVencimiento']->format('Y-m-d');
                $query = $em->createQuery("UPDATE App\Entity\Inventario\InvLote l set l.fechaVencimiento= '". $fecha ."' WHERE l.codigoItemFk = " . $arrLote['codigoItemFk'] . " AND l.loteFk = '" . $arrLote['loteFk'] . "'");
                $query->execute();
                $query = $em->createQuery("UPDATE App\Entity\Inventario\InvMovimientoDetalle md set md.fechaVencimiento= '". $fecha ."' WHERE md.codigoItemFk = " . $arrLote['codigoItemFk'] . " AND md.loteFk = '" . $arrLote['loteFk'] . "'");
                $query->execute();
            }*/

        }
    }

}
