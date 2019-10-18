<?php

namespace App\Repository\Inventario;


use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvBodegaUsuario;
use App\Entity\Inventario\InvConfiguracion;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvPedidoDetalle;
use App\Entity\Inventario\InvRemision;
use App\Entity\Inventario\InvRemisionDetalle;
use App\Entity\Inventario\InvRemisionTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use http\Client\Curl\User;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Utilidades\Mensajes;

class InvRemisionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvRemision::class);
    }

    public function lista($raw, $asesor)
    {

        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $numero = null;
        $codigoRemision = null;
        $codigoTercero = null;
        $codigoRemisionTipo = null;
        $codigoAsesor = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {

            $numero = $filtros['numero'] ?? null;
            $codigoRemision = $filtros['codigoRemision'] ?? null;
            $codigoTercero = $filtros['codigoTercero'] ?? null;
            $codigoRemisionTipo = $filtros['codigoRemisionTipo'] ?? null;
            $codigoAsesor = $filtros['codigoAsesor'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvRemision::class, 'r')
            ->leftJoin('r.terceroRel', 't')
            ->leftJoin('r.remisionTipoRel', 'rt')
            ->select('r.codigoRemisionPk')
            ->addSelect('r.numero')
            ->addSelect('r.fecha')
            ->addSelect('r.vrSubtotal')
            ->addSelect('r.vrIva')
            ->addSelect('r.vrNeto')
            ->addSelect('r.vrTotal')
            ->addSelect('rt.nombre as tipo')
            ->addSelect('r.estadoAutorizado')
            ->addSelect('r.estadoAprobado')
            ->addSelect('r.estadoAnulado')
            ->addSelect('t.nombreCorto AS tercero')
            ->where('r.codigoRemisionPk <> 0')
            ->where("r.codigoAsesorFk = {$asesor}")
            ->orderBy('r.codigoRemisionPk', 'DESC');

        if ($codigoRemision) {
            $queryBuilder->andWhere("r.codigoRemisionPk = '{$codigoRemision}'");
        }
        if ($numero) {
            $queryBuilder->andWhere("r.numero = {$numero}");
        }
        if ($codigoTercero) {
            $queryBuilder->andWhere("r.codigoTerceroFk = {$codigoTercero}");
        }
        if ($codigoRemisionTipo) {
            $queryBuilder->andWhere("r.codigoRemisionTipoFk = '{$codigoRemisionTipo}'");
        }
        if ($codigoAsesor) {
            $queryBuilder->andWhere("r.codigoAsesorFk = '{$codigoAsesor}'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("r.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("r.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("r.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("r.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("r.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("r.estadoAnulado = 1");
                break;
        }
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

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
    public function autorizar($arRemision, $usuario)
    {
        $em = $this->getEntityManager();
        if (!$arRemision->getEstadoAutorizado()) {
            $respuesta = $this->validarDetalles($arRemision, $usuario);
            if ($respuesta) {
                Mensajes::error($respuesta);
            } else {
                if ($em->getRepository(InvRemisionDetalle::class)->contarDetalles($arRemision->getCodigoRemisionPk()) > 0) {
                    $arRemision->setEstadoAutorizado(1);
                    $em->persist($arRemision);
                    $arRemisionDetalles = $em->getRepository(InvRemisionDetalle::class)->findBy(array('codigoRemisionFk' => $arRemision->getCodigoRemisionPk()));
                    foreach ($arRemisionDetalles as $arRemisionDetalle) {
                        //Si afecta pedido
                        if ($arRemisionDetalle->getCodigoPedidoDetalleFk()) {
                            $arPedidoDetalle = $em->getRepository(InvPedidoDetalle::class)->find($arRemisionDetalle->getCodigoPedidoDetalleFk());
                            $arPedidoDetalle->setCantidadAfectada($arPedidoDetalle->getCantidadAfectada() + $arRemisionDetalle->getCantidad());
                            $arPedidoDetalle->setCantidadPendiente($arPedidoDetalle->getCantidad() - $arPedidoDetalle->getCantidadAfectada());
                            $em->persist($arPedidoDetalle);
                        }

                        //Si afecta remision
                        if ($arRemisionDetalle->getCodigoRemisionDetalleFk()) {
                            $arRemisionDetalleOrigen = $em->getRepository(InvRemisionDetalle::class)->find($arRemisionDetalle->getCodigoRemisionDetalleFk());
                            $arRemisionDetalleOrigen->setCantidadAfectada($arRemisionDetalleOrigen->getCantidadAfectada() + $arRemisionDetalle->getCantidad());
                            $arRemisionDetalleOrigen->setCantidadPendiente($arRemisionDetalleOrigen->getCantidad() - $arRemisionDetalleOrigen->getCantidadAfectada());
                            $em->persist($arRemisionDetalleOrigen);
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
        if ($arRemision->getEstadoAutorizado()) {
            $arRemision->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arRemision);
            $this->getEntityManager()->flush();

        } else {
            Mensajes::error('El documento no esta autorizado');
        }
    }

    /**
     * @param $arRemision InvRemision
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arRemision)
    {
        if ($arRemision->getEstadoAutorizado() == 1 && $arRemision->getEstadoAprobado() == 0) {
            if ($arRemision->getTerceroRel()->getBloqueoCartera() == 0) {
                if ($this->afectar($arRemision)) {
                    $arRemisionTipo = $this->getEntityManager()->getRepository(InvRemisionTipo::class)->find($arRemision->getCodigoRemisionTipoFk());
                    if ($arRemisionTipo) {
                        if ($arRemision->getNumero() == 0 || $arRemision->getNumero() == "") {
                            $arRemisionTipo->setConsecutivo($arRemisionTipo->getConsecutivo() + 1);
                            $arRemision->setNumero($arRemisionTipo->getConsecutivo());
                            $this->getEntityManager()->persist($arRemisionTipo);
                        }
                    }
                    $arRemision->setEstadoAprobado(1);
                    $this->getEntityManager()->persist($arRemision);
                    $this->getEntityManager()->flush();
                }
            } else {
                Mensajes::error("El registro no se puede aprobar, el cliente se encuentra bloqueado por cartera");
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
            if ($arItem->getAfectaInventario() == 1) {
                $arLote = $this->getEntityManager()->getRepository(InvLote::class)
                    ->findOneBy(['loteFk' => $arRemisionDetalle->getLoteFk(), 'codigoItemFk' => $arRemisionDetalle->getCodigoItemFk(), 'codigoBodegaFk' => $arRemisionDetalle->getCodigoBodegaFk()]);

                if ($arRemisionDetalle->getOperacionInventario() == 1) {
                    $cantidadRemisonadaFinal = $arLote->getCantidadRemisionada() + $arRemisionDetalle->getCantidadOperada();
                    $cantidadDisponibleFinal = $arLote->getCantidadExistencia() - $cantidadRemisonadaFinal;
                    if ($cantidadDisponibleFinal < 0) {
                        Mensajes::error("La cantidad: " . $arRemisionDetalle->getCantidad() . " del lote: " . $arRemisionDetalle->getLoteFk() . " en la remision detalle id: " . $arRemisionDetalle->getCodigoRemisionDetallePk() . " no tiene existencia suficiente");
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
        //$validacion = false;
        return $validacion;
    }

    /**
     * @param $arRemision
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function anular($arRemision)
    {
        $em = $this->getEntityManager();
        $validacion = true;
        if ($arRemision->getEstadoAprobado() == 1 && $arRemision->getEstadoAnulado() == 0) {
            $arRemisionDetalles = $em->getRepository(InvRemisionDetalle::class)->findBy(array('codigoRemisionFk' => $arRemision->getCodigoRemisionPk()));
            foreach ($arRemisionDetalles as $arRemisionDetalle) {
                if ($arRemisionDetalle->getCantidadAfectada() > 0) {
                    Mensajes::error("No se puede anular la remision porque uno de sus detalles es usado");
                    $validacion = false;
                    break;
                }
                $arItem = $this->getEntityManager()->getRepository(InvItem::class)->find($arRemisionDetalle->getCodigoItemFk());
                if ($arItem->getAfectaInventario() == 1) {
                    $arLote = $this->getEntityManager()->getRepository(InvLote::class)
                        ->findOneBy(['loteFk' => $arRemisionDetalle->getLoteFk(), 'codigoItemFk' => $arRemisionDetalle->getCodigoItemFk(), 'codigoBodegaFk' => $arRemisionDetalle->getCodigoBodegaFk()]);
                    $arLote->setCantidadRemisionada($arLote->getCantidadRemisionada() - $arRemisionDetalle->getCantidadOperada());
                    $arLote->setCantidadDisponible($arLote->getCantidadExistencia() - $arLote->getCantidadRemisionada());
                    $em->persist($arLote);
                    $em->persist($arRemisionDetalle);
                    $arItem->setCantidadRemisionada($arItem->getCantidadRemisionada() - $arRemisionDetalle->getCantidadOperada());
                    $arItem->setCantidadDisponible($arItem->getCantidadExistencia() - $arItem->getCantidadRemisionada());
                    $em->persist($arItem);
                }

                if ($arRemisionDetalle->getCodigoPedidoDetalleFk()) {
                    $arPedidoDetalle = $em->getRepository(InvPedidoDetalle::class)->find($arRemisionDetalle->getCodigoPedidoDetalleFk());
                    $arPedidoDetalle->setCantidadAfectada($arPedidoDetalle->getCantidadAfectada() - $arRemisionDetalle->getCantidad());
                    $arPedidoDetalle->setCantidadPendiente($arPedidoDetalle->getCantidad() - $arPedidoDetalle->getCantidadAfectada());
                    $em->persist($arPedidoDetalle);
                }
                if ($arRemisionDetalle->getCodigoRemisionDetalleFk()) {
                    $arRemisionDetalleOrigen = $em->getRepository(InvRemisionDetalle::class)->find($arRemisionDetalle->getCodigoRemisionDetalleFk());
                    $arRemisionDetalleOrigen->setCantidadAfectada($arRemisionDetalleOrigen->getCantidadAfectada() - $arRemisionDetalle->getCantidad());
                    $arRemisionDetalleOrigen->setCantidadPendiente($arRemisionDetalleOrigen->getCantidad() - $arRemisionDetalleOrigen->getCantidadAfectada());
                    $em->persist($arRemisionDetalleOrigen);
                }
                $arRemisionDetalle->setCantidad(0);
                $arRemisionDetalle->setCantidadOperada(0);
                $arRemisionDetalle->setCantidadPendiente(0);
                $arRemisionDetalle->setVrSubtotal(0);
                $arRemisionDetalle->setVrPrecio(0);
                $arRemisionDetalle->setVrNeto(0);
                $arRemisionDetalle->setVrIva(0);
                $arRemisionDetalle->setPorcentajeIva(0);
            }
            if ($validacion == true) {
                $arRemision->setEstadoAnulado(1);
                $arRemision->setVrSubtotal(0);
                $arRemision->setVrIva(0);
                $arRemision->setVrTotal(0);
                $em->persist($arRemision);
                $em->flush();
            }
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
    public function actualizarDetalles($codigoRemision, $arrControles)
    {
        $em = $this->getEntityManager();
        if ($this->contarDetalles($codigoRemision) > 0) {
            $arrBodega = $arrControles['arrBodega'];
            $arrLote = $arrControles['arrLote'];
            $arrCantidad = $arrControles['TxtCantidad'];
            $arrPrecio = $arrControles['TxtPrecio'];
            $arrCodigo = $arrControles['TxtCodigo'];
            $arrFechaVencimiento = $arrControles['arrFechaVencimiento'];
            foreach ($arrCodigo as $codigo) {
                $arRemisionDetalle = $em->getRepository(InvRemisionDetalle::class)->find($codigo);
                $arRemisionDetalle->setCodigoBodegaFk($arrBodega[$codigo]);
                $arRemisionDetalle->setLoteFk($arrLote[$codigo]);
                $fecha = $arrFechaVencimiento[$codigo];
                $arRemisionDetalle->setFechaVencimiento(date_create($fecha));
                $arRemisionDetalle->setCantidad($arrCantidad[$codigo] != '' ? $arrCantidad[$codigo] : 0);
                $arRemisionDetalle->setCantidadOperada($arRemisionDetalle->getCantidad() * $arRemisionDetalle->getOperacionInventario());
                $arRemisionDetalle->setCantidadPendiente($arRemisionDetalle->getCantidad());
                $arRemisionDetalle->setVrPrecio($arrPrecio[$codigo] != '' ? $arrPrecio[$codigo] : 0);
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

    public function validarDetalles($arRemision, $usuario)
    {
        $em = $this->getEntityManager();
        $respuesta = "";
        $arRemisionDetalles = $this->getEntityManager()->getRepository(InvRemisionDetalle::class)->validarDetalles($arRemision->getCodigoRemisionPk());
        foreach ($arRemisionDetalles as $arRemisionDetalle) {
            if ($arRemisionDetalle['afectaInventario']) {
                if ($arRemisionDetalle['codigoBodegaFk'] == "" || $arRemisionDetalle['loteFk'] == "") {
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
        if ($respuesta == "") {
            $respuesta = $this->validarCantidadesAfectar($arRemision->getCodigoRemisionPk());
        }
        if ($respuesta == "") {
            $arrConfiguracion = $em->getRepository(InvConfiguracion::class)->validarDetalles();
            foreach ($arRemisionDetalles as $arRemisionDetalle) {
                if ($arrConfiguracion['validarBodegaUsuario']) {
                    $arItem = $this->getEntityManager()->getRepository(InvItem::class)
                        ->findOneBy(['codigoItemPk' => $arRemisionDetalle['codigoItemFk']]);
                    if ($arItem->getAfectaInventario() == true) {
                        $arrBodegas = $em->getRepository(InvRemisionDetalle::class)->bodegaRemision($arRemision->getCodigoRemisionPk());
                        foreach ($arrBodegas as $arrBodega) {
                            $arBodegaUsuario = $em->getRepository(InvBodegaUsuario::class)->findOneBy(array('codigoBodegaFk' => $arrBodega['codigoBodegaFk'], 'usuario' => $usuario));
                            if (!$arBodegaUsuario) {
                                $respuesta = 'El usuario no tiene permiso para mover cantidades de la bodega ' . $arrBodega['codigoBodegaFk'];
                                break;
                            }
                        }
                    }
                }
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
        if ($arrSeleccionados) {
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
                if ($respuesta != '') {
                    Mensajes::error($respuesta);
                } else {
                    $this->getEntityManager()->flush();
                }
            }
        }
    }

    private function validarCantidadesAfectar($codigoRemision)
    {
        $em = $this->getEntityManager();
        $respuesta = "";

        //Validar pedidos
        if ($respuesta == "") {
            $queryBuilder = $em->createQueryBuilder()->from(InvRemisionDetalle::class, 'rd')
                ->select('rd.codigoPedidoDetalleFk')
                ->addSelect("SUM(rd.cantidad) AS cantidad")
                ->where("rd.codigoRemisionFk = {$codigoRemision} ")
                ->andWhere('rd.codigoPedidoDetalleFk IS NOT NULL')
                ->groupBy('rd.codigoPedidoDetalleFk');
            $arrResultado = $queryBuilder->getQuery()->getResult();
            if ($arrResultado) {
                foreach ($arrResultado as $arrElemento) {
                    $arPedidoDetalle = $em->getRepository(InvPedidoDetalle::class)->find($arrElemento['codigoPedidoDetalleFk']);
                    if ($arPedidoDetalle->getCantidadPendiente() < $arrElemento['cantidad']) {
                        $respuesta = "El pedido detalle " . $arrElemento['codigoPedidoDetalleFk'] . " tiene pendiente " . $arPedidoDetalle->getCantidadPendiente() .
                            " y no son suficientes para afectar " . $arrElemento['cantidad'];
                        break;
                    }
                }
            }
        }

        //Validar remision
        if ($respuesta == "") {
            $queryBuilder = $em->createQueryBuilder()->from(InvRemisionDetalle::class, 'rd')
                ->select('rd.codigoRemisionDetalleFk')
                ->addSelect("SUM(rd.cantidad) AS cantidad")
                ->where("rd.codigoRemisionFk = {$codigoRemision} ")
                ->andWhere('rd.codigoRemisionDetalleFk IS NOT NULL')
                ->groupBy('rd.codigoRemisionDetalleFk');
            $arrResultado = $queryBuilder->getQuery()->getResult();
            if ($arrResultado) {
                foreach ($arrResultado as $arrElemento) {
                    $arRemisionDetalle = $em->getRepository(InvRemisionDetalle::class)->find($arrElemento['codigoRemisionDetalleFk']);
                    if ($arRemisionDetalle->getCantidadPendiente() < $arrElemento['cantidad']) {
                        $respuesta = "La remision detalle " . $arrElemento['codigoRemisionDetalleFk'] . " enlazada a esta remision tiene pendiente " . $arRemisionDetalle->getCantidadPendiente() .
                            " y no son suficientes para afectar " . $arrElemento['cantidad'];
                        break;
                    }
                }
            }
        }
        return $respuesta;
    }

    public function sinAprobar()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(InvRemision::class, 'r')
            ->select('COUNT(r.codigoRemisionPk) as cantidad')
            ->andWhere('r.estadoAprobado=0');
        $arrResultado = $queryBuilder->getQuery()->getSingleResult();

        return $arrResultado['cantidad'];
    }

}