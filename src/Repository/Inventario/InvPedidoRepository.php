<?php

namespace App\Repository\Inventario;


use App\Entity\Inventario\InvPedido;
use App\Entity\Inventario\InvPedidoDetalle;
use App\Entity\Inventario\InvPedidoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Utilidades\Mensajes;
class InvPedidoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvPedido::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvPedido::class,'p')
            ->leftJoin('p.terceroRel', 't')
            ->leftJoin('p.pedidoTipoRel', 'pt')
            ->select('p.codigoPedidoPk')
            ->addSelect('p.numero')
            ->addSelect('p.fecha')
            ->addSelect('p.vrSubtotal')
            ->addSelect('p.vrIva')
            ->addSelect('p.vrNeto')
            ->addSelect('p.vrTotal')
            ->addSelect('pt.nombre')
            ->addSelect('p.estadoAutorizado')
            ->addSelect('p.estadoAprobado')
            ->addSelect('p.estadoAnulado')
            ->addSelect('p.usuario')
            ->addSelect('t.nombreCorto AS terceroNombreCorto')
            ->where('p.codigoPedidoPk <> 0')
            ->orderBy('p.codigoPedidoPk','DESC');
        if($session->get('filtroInvNumeroPedido')) {
            $queryBuilder->andWhere("p.numero = {$session->get('filtroInvNumeroPedido')}");
        }
        if($session->get('filtroInvPedidoTipo')) {
            $queryBuilder->andWhere("p.codigoPedidoTipoFk = '{$session->get('filtroInvPedidoTipo')}'");
        }
        if($session->get('filtroInvCodigoTercero')){
            $queryBuilder->andWhere("p.codigoTerceroFk = {$session->get('filtroInvCodigoTercero')}");
        }
        return $queryBuilder;

    }

    /**
     * @param $codigoPedido
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function liquidar($codigoPedido)
    {
        $em = $this->getEntityManager();
        $arPedido = $em->getRepository(InvPedido::class)->find($codigoPedido);
        $arPedidoDetalles = $em->getRepository(InvPedidoDetalle::class)->findBy(['codigoPedidoFk' => $codigoPedido]);
        $subtotalGeneral = 0;
        $ivaGeneral = 0;
        $totalGeneral = 0;
        foreach ($arPedidoDetalles as $arPedidoDetalle) {
            $arPedidoDetalleAct = $em->getRepository(InvPedidoDetalle::class)->find($arPedidoDetalle->getCodigoPedidoDetallePk());
            $subtotal = $arPedidoDetalle->getCantidad() * $arPedidoDetalle->getVrPrecio();
            $porcentajeIva = $arPedidoDetalle->getPorcentajeIva();
            $iva = $subtotal * $porcentajeIva / 100;
            $subtotalGeneral += $subtotal;
            $ivaGeneral += $iva;
            $total = $subtotal + $iva;
            $totalGeneral += $total;
            $arPedidoDetalleAct->setVrSubtotal($subtotal);
            $arPedidoDetalleAct->setVrIva($iva);
            $arPedidoDetalleAct->setVrTotal($total);
            $em->persist($arPedidoDetalleAct);
        }
        $arPedido->setVrSubtotal($subtotalGeneral);
        $arPedido->setVrIva($ivaGeneral);
        $arPedido->setVrTotal($totalGeneral);
        $em->persist($arPedido);
        $em->flush();
    }

    /**
     * @param $arPedido InvPedido
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arPedido)
    {
        if(!$arPedido->getEstadoAutorizado()) {
            $registros = $this->getEntityManager()->createQueryBuilder()->from(InvPedidoDetalle::class,'pd')
                ->select('COUNT(pd.codigoPedidoDetallePk) AS registros')
                ->where('pd.codigoPedidoFk = ' . $arPedido->getCodigoPedidoPk())
                ->getQuery()->getSingleResult();
            if($registros['registros'] > 0) {
                $arPedido->setEstadoAutorizado(1);
                $this->getEntityManager()->persist($arPedido);
                $this->getEntityManager()->flush();
            } else {
                Mensajes::error("El registro no tiene detalles");
            }
        } else {
            Mensajes::error('El documento ya esta autorizado');
        }
    }

    /**
     * @param $arPedido InvPedido
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arPedido)
    {
        if($arPedido->getEstadoAutorizado()) {
                $arPedido->setEstadoAutorizado(0);
                $this->getEntityManager()->persist($arPedido);
                $this->getEntityManager()->flush();

        } else {
            Mensajes::error('El documento no esta autorizado');
        }
    }

    /**
     * @param $arPedido InvPedido
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arPedido)
    {
        if($arPedido->getEstadoAutorizado() == 1 && $arPedido->getEstadoAprobado() == 0) {
            $arPedidoTipo = $this->getEntityManager()->getRepository(InvPedidoTipo::class)->find($arPedido->getCodigoPedidoTipoFk());
            if($arPedidoTipo){
                $arPedidoTipo->setConsecutivo($arPedidoTipo->getConsecutivo() + 1);
                $arPedido->setNumero($arPedidoTipo->getConsecutivo());
                $this->getEntityManager()->persist($arPedidoTipo);
            }
            $arPedido->setEstadoAprobado(1);
            $this->getEntityManager()->persist($arPedido);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('El documento debe estar autorizado y no puede estar previamente aprobado');
        }
    }

    /**
     * @param $arPedido InvPedido
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function anular($arPedido)
    {
        $em = $this->getEntityManager();
        $validacion = true;
        if($arPedido->getEstadoAprobado() == 1 && $arPedido->getEstadoAnulado() == 0) {
            $arPedidoDetalles = $em->getRepository(InvPedidoDetalle::class)->findBy(array('codigoPedidoFk' => $arPedido->getCodigoPedidoPk()));
            foreach ($arPedidoDetalles as $arPedidoDetalle) {
                if($arPedidoDetalle->getCantidadAfectada() > 0) {
                    Mensajes::error("No se puede anular el documento porque uno de sus detalles es usado");
                    $validacion = false;
                    break;
                }
                $arPedidoDetalle->setCantidad(0);
                $arPedidoDetalle->setCantidadPendiente(0);
                $arPedidoDetalle->setVrSubtotal(0);
                $arPedidoDetalle->setVrNeto(0);
                $arPedidoDetalle->setPorcentajeIva(0);
                $arPedidoDetalle->setVrIva(0);
                $arPedidoDetalle->setVrTotal(0);
                $arPedidoDetalle->setVrPrecio(0);
                $em->persist($arPedidoDetalle);
            }
            if($validacion == true) {
                $arPedido->setEstadoAnulado(1);
                $arPedido->setVrSubtotal(0);
                $arPedido->setVrIva(0);
                $arPedido->setVrTotal(0);
                $em->persist($arPedido);
                $em->flush();
            }
        } else {
            Mensajes::error('El documento debe estar aprobado y no puede estar previamente anulado');
        }
    }

    /**
     * @param $codigoPedido
     * @param $arrControles
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarDetalles($codigoPedido, $arrControles){
        $em = $this->getEntityManager();
        if($this->contarDetalles($codigoPedido) > 0){
            $arrCantidad = $arrControles['TxtCantidad'];
            $arrPrecio = $arrControles['TxtPrecio'];
            $arrCodigo = $arrControles['TxtCodigo'];
            foreach ($arrCodigo as $codigo) {
                $arPedidoDetalle = $em->getRepository(InvPedidoDetalle::class)->find($codigo);
                $arPedidoDetalle->setCantidad( $arrCantidad[$codigo] != '' ? $arrCantidad[$codigo] :0 );
                $arPedidoDetalle->setCantidadPendiente( $arrCantidad[$codigo] != '' ? $arrCantidad[$codigo] :0 );
                $arPedidoDetalle->setVrPrecio( $arrPrecio[$codigo] != '' ? $arrPrecio[$codigo] : 0);
                $em->persist($arPedidoDetalle);
            }
        }
        $em->flush();
        $this->liquidar($codigoPedido);
    }

    /**
     * @param $codigoPedido
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function contarDetalles($codigoPedido)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvPedidoDetalle::class, 'pd')
            ->select("COUNT(pd.codigoPedidoDetallePk)")
            ->where("pd.codigoPedidoFk = {$codigoPedido} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
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
                $arRegistro = $this->getEntityManager()->getRepository(InvPedido::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(InvPedidoDetalle::class)->findBy(['codigoPedidoFk' => $arRegistro->getCodigoPedidoPk()])) <= 0) {
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