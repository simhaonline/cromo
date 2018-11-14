<?php

namespace App\Repository\Inventario;


use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvPedidoDetalle;
use App\Entity\Inventario\InvRemision;
use App\Entity\Inventario\InvRemisionDetalle;
use App\Entity\Inventario\InvRemisionTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Utilidades\Mensajes;

class InvRemisionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvRemision::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvRemision::class,'r')
            ->leftJoin('r.terceroRel', 't')
            ->leftJoin('r.remisionTipoRel', 'rt')
            ->select('r.codigoRemisionPk')
            ->addSelect('r.numero')
            ->addSelect('r.fecha')
            ->addSelect('r.vrSubtotal')
            ->addSelect('r.vrIva')
            ->addSelect('r.vrNeto')
            ->addSelect('r.vrTotal')
            ->addSelect('rt.nombre')
            ->addSelect('r.estadoAutorizado')
            ->addSelect('r.estadoAprobado')
            ->addSelect('r.estadoAnulado')
            ->addSelect('t.nombreCorto AS terceroNombreCorto')
            ->where('r.codigoRemisionPk <> 0')
            ->orderBy('r.codigoRemisionPk','DESC');
        if($session->get('filtroInvRemisionTipo')) {
            $queryBuilder->andWhere("r.codigoRemisionTipoFk = '{$session->get('filtroInvRemisionTipo')}'");
        }

        return $queryBuilder;

    }

    /**
     * @param $codigoRemision
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function liquidar($codigoRemision)
    {
        $em = $this->getEntityManager();
        $arRemision = $em->getRepository(InvRemision::class)->find($codigoRemision);
        $arRemisionDetalles = $em->getRepository(InvRemisionDetalle::class)->findBy(['codigoRemisionFk' => $codigoRemision]);
        $subtotalGeneral = 0;
        $ivaGeneral = 0;
        $totalGeneral = 0;
        foreach ($arRemisionDetalles as $arRemisionDetalle) {
            $arRemisionDetalleAct = $em->getRepository(InvRemisionDetalle::class)->find($arRemisionDetalle->getCodigoRemisionDetallePk());
            $subtotal = $arRemisionDetalle->getCantidad() * $arRemisionDetalle->getVrPrecio();
            $porcentajeIva = $arRemisionDetalle->getPorcentajeIva();
            $iva = $subtotal * $porcentajeIva / 100;
            $subtotalGeneral += $subtotal;
            $ivaGeneral += $iva;
            $total = $subtotal + $iva;
            $totalGeneral += $total;
            $arRemisionDetalleAct->setVrSubtotal($subtotal);
            $arRemisionDetalleAct->setVrIva($iva);
            $arRemisionDetalleAct->setVrTotal($total);
            $em->persist($arRemisionDetalleAct);
        }
        $arRemision->setVrSubtotal($subtotalGeneral);
        $arRemision->setVrIva($ivaGeneral);
        $arRemision->setVrTotal($totalGeneral);
        $em->persist($arRemision);
        $em->flush();
    }


    /**
     * @param $arRemision
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arRemision)
    {
        $em = $this->getEntityManager();
        if(!$arRemision->getEstadoAutorizado()) {
            $respuesta = $this->validarDetalles($arRemision->getCodigoRemisionPk());
            if ($respuesta) {
                Mensajes::error($respuesta);
            } else {
                if ($em->getRepository(InvRemisionDetalle::class)->contarDetalles($arRemision->getCodigoRemisionPk()) > 0) {
                    $arRemision->setEstadoAutorizado(1);
                    $em->persist($arRemision);
                    $arRemisionDetalles = $em->getRepository(InvRemisionDetalle::class)->findBy(array('codigoRemisionFk' => $arRemision->getCodigoRemisionPk()));
                    foreach ($arRemisionDetalles as $arRemisionDetalle) {
                        if($arRemisionDetalle->getCodigoPedidoDetalleFk()) {
                            $arPedidoDetalle = $em->getRepository(InvPedidoDetalle::class)->find($arRemisionDetalle->getCodigoPedidoDetalleFk());
                            $arPedidoDetalle->setCantidadAfectada($arPedidoDetalle->getCantidadAfectada() + $arRemisionDetalle->getCantidad());
                            $arPedidoDetalle->setCantidadPendiente($arPedidoDetalle->getCantidad() - $arPedidoDetalle->getCantidadAfectada());
                            $em->persist($arPedidoDetalle);
                        }
                    }
                    $em->flush();
                } else {
                    Mensajes::error("El registro no tiene detalles");
                }
            }
        } else {
            Mensajes::error('El documento no puede estar autorizado previamente');
        }
    }

    /**
     * @param $arRemision
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arRemision)
    {
        if($arRemision->getEstadoAutorizado()) {
            $arRemision->setEstadoAutorizado(0);
                $this->getEntityManager()->persist($arRemision);
                $this->getEntityManager()->flush();

        } else {
            Mensajes::error('El documento no esta autorizado');
        }
    }

    /**
     * @param $arRemision
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arRemision)
    {
        if($arRemision->getEstadoAutorizado() == 1 && $arRemision->getEstadoAprobado() == 0) {
            if($this->afectar($arRemision)) {
                $arRemisionTipo = $this->getEntityManager()->getRepository(InvRemisionTipo::class)->find($arRemision->getCodigoRemisionTipoFk());
                if($arRemisionTipo){
                    $arRemisionTipo->setConsecutivo($arRemisionTipo->getConsecutivo() + 1);
                    $arRemision->setNumero($arRemisionTipo->getConsecutivo());
                    $this->getEntityManager()->persist($arRemisionTipo);
                }
                $arRemision->setEstadoAprobado(1);
                $this->getEntityManager()->persist($arRemision);
                $this->getEntityManager()->flush();
            }
        } else {
            Mensajes::error('El documento debe estar autorizado y no puede estar previamente aprobado');
        }
    }

    /**
     * @param $arMovimiento InvMovimiento
     * @param $tipo
     * @throws \Doctrine\ORM\ORMException
     */
    public function afectar($arRemision)
    {
        $em = $this->getEntityManager();
        $validacion = true;
        $arRemisionDetalles = $this->getEntityManager()->getRepository(InvRemisionDetalle::class)->findBy(['codigoRemisionFk' => $arRemision->getCodigoRemisionPk()]);
        foreach ($arRemisionDetalles as $arRemisionDetalle) {
            $arItem = $this->getEntityManager()->getRepository(InvItem::class)->find($arRemisionDetalle->getCodigoItemFk());
            if($arItem->getAfectaInventario() == 1) {
                $arLote = $this->getEntityManager()->getRepository(InvLote::class)
                    ->findOneBy(['loteFk' => $arRemisionDetalle->getLoteFk(), 'codigoItemFk' => $arRemisionDetalle->getCodigoItemFk(), 'codigoBodegaFk' => $arRemisionDetalle->getCodigoBodegaFk()]);
                if($arRemisionDetalle->getOperacionInventario() == -1) {
                    if($arRemisionDetalle->getCantidad() > $arLote->getCantidadDisponible()) {
                        Mensajes::error("La cantidad: " . $arRemisionDetalle->getCantidad() . " del lote: " . $arRemisionDetalle->getLoteFk() ." en la remision con id: " . $arRemisionDetalle->getCodigoRemisionDetallePk() . " no tiene existencia suficiente");
                        $validacion = false;
                        break;
                    }
                }

                $arLote->setCantidadRemisionada($arLote->getCantidadRemisionada() + $arRemisionDetalle->getCantidadOperada());
                $arLote->setCantidadDisponible($arLote->getCantidadExistencia() - $arLote->getCantidadRemisionada());
                $em->persist($arLote);
                $em->persist($arRemisionDetalle);
                $arItem->setCantidadRemisionada($arItem->getCantidadRemisionada() + $arRemisionDetalle->getCantidadOperada());
                $arItem->setCantidadDisponible($arItem->getCantidadExistencia() - $arItem->getCantidadRemisionada());
                $em->persist($arItem);
            }
        }
        return $validacion;
    }

    /**
     * @param $arRemision
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function anular($arRemision)
    {
        if($arRemision->getEstadoAprobado() == 1 && $arRemision->getEstadoAnulado() == 0) {
            $arRemision->setEstadoAnulado(1);
            $arRemision->setVrSubtotal(0);
            $arRemision->setVrIva(0);
            $arRemision->setVrTotal(0);
            $this->getEntityManager()->persist($arRemision);
            $this->getEntityManager()->flush();

        } else {
            Mensajes::error('El documento debe estar aprobado y no puede estar previamente anulado');
        }
    }


    /**
     * @param $codigoRemision
     * @param $arrControles
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarDetalles($codigoRemision, $arrControles){
        $em = $this->getEntityManager();
        if($this->contarDetalles($codigoRemision) > 0){
            $arrBodega = $arrControles['arrBodega'];
            $arrLote = $arrControles['arrLote'];
            $arrCantidad = $arrControles['TxtCantidad'];
            $arrPrecio = $arrControles['TxtPrecio'];
            $arrCodigo = $arrControles['TxtCodigo'];
            foreach ($arrCodigo as $codigo) {
                $arRemisionDetalle = $em->getRepository(InvRemisionDetalle::class)->find($codigo);
                $arRemisionDetalle->setCodigoBodegaFk($arrBodega[$codigo]);
                $arRemisionDetalle->setLoteFk($arrLote[$codigo]);
                $arRemisionDetalle->setCantidad( $arrCantidad[$codigo] != '' ? $arrCantidad[$codigo] :0 );
                $arRemisionDetalle->setVrPrecio( $arrPrecio[$codigo] != '' ? $arrPrecio[$codigo] : 0);
                $em->persist($arRemisionDetalle);
            }
        }
        $em->flush();
        $this->liquidar($codigoRemision);
    }

    /**
     * @param $codigoRemision
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function contarDetalles($codigoRemision)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvRemisionDetalle::class, 'rd')
            ->select("COUNT(rd.codigoRemisionDetallePk)")
            ->where("rd.codigoRemisionFk = {$codigoRemision} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }

    public function validarDetalles($codigoRemision)
    {
        $respuesta = "";
        $arRemisionDetalles = $this->getEntityManager()->getRepository(InvRemisionDetalle::class)->validarDetalles($codigoRemision);
        foreach ($arRemisionDetalles as $arRemisionDetalle) {
            if ($arRemisionDetalle['afectaInventario']) {
                if($arRemisionDetalle['codigoBodegaFk'] == "" || $arRemisionDetalle['loteFk'] == "") {
                    $respuesta = "El detalle con id " . $arRemisionDetalle['codigoRemisionDetallePk'] . " no tiene bodega o lote";
                    break;
                } else {
                    $arBodega = $this->getEntityManager()->getRepository(InvBodega::class)->find($arRemisionDetalle['codigoBodegaFk']);
                    if (!$arBodega) {
                        $respuesta = 'La bodega ingresada en el detalle con id ' . $arRemisionDetalle['codigoRemisionDetallePk'] . ', no existe.';
                        break;
                    }
                }
            }
            if ($arRemisionDetalle['cantidad'] == 0) {
                $respuesta = 'El detalle con id ' . $arRemisionDetalle['codigoRemisionDetallePk'] . ' tiene cantidad 0.';
                break;
            }
        }
        return $respuesta;
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(InvRemision::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(InvRemisionDetalle::class)->findBy(['codigoRemisionFk' => $arRegistro->getCodigoRemisionPk()])) <= 0) {
                                $this->getEntityManager()->remove($arRegistro);
                            } else {
                                $respuesta = 'No se puede eliminar, el registro tiene detalles';
                            }
                        } else {
                            $respuesta = 'No se puede eliminar, el registro se encuentra autorizado';
                        }
                    } else {
                        $respuesta = 'No se puede eliminar, el registro se encuentra aprobado';
                    }
                }
                if($respuesta != ''){
                    Mensajes::error($respuesta);
                } else {
                    $this->getEntityManager()->flush();
                }
            }
        }
    }

}