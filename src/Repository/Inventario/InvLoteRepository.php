<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvLoteRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
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

    public function lista(){
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvLote::class,'l')
            ->select('l.codigoLotePk')
            ->addSelect('l.loteFk')
            ->addSelect('l.codigoBodegaFk')
            ->where("l.codigoItemFk = {$session->get('filtroInvBuscarLoteItem')}");
        if($session->get('filtroInvBuscarLoteCodigo') != ''){
            $queryBuilder->andWhere("l.codigoLotePk =  {$session->get('filtroInvBuscarLoteCodigo')}");
        }
        return $queryBuilder;
    }

    public function existencia()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvLote::class, 'l')
            ->select('l.codigoItemFk')
            ->addSelect('l.codigoBodegaFk')
            ->addSelect('l.loteFk')
            ->where('l.cantidadDisponible > 0');
        return $queryBuilder;
    }


}