<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvSolicitudDetalle;
use App\Utilidades\Mensajes;
use App\Entity\Inventario\InvSolicitud;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvSolicitudRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvSolicitud::class);
    }

    public function camposPredeterminados()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvSolicitud::class, 's');
        $queryBuilder->select('s.codigoSolicitudPk AS ID')
            ->addSelect('s.numero AS NUMERO')
            ->addSelect('st.nombre AS TIPO')
            ->addSelect('s.fecha AS FECHA')
            ->addSelect('s.estadoAutorizado AS AUTORIZADO')
            ->addSelect('s.estadoAprobado AS APROBADO')
            ->addSelect('s.estadoAnulado AS ANULADO')
            ->join('s.solicitudTipoRel', 'st')
            ->where('s.codigoSolicitudPk <> 0')
            ->orderBy('s.codigoSolicitudPk', 'DESC');
        return $queryBuilder;
    }

    /**
     * @return mixed
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvSolicitud::class, 'i')
            ->select('i.codigoSolicitudPk')
            ->join('i.solicitudTipoRel', 'it')
            ->addSelect('i.numero')
            ->addSelect('it.nombre as nombreTipo')
            ->addSelect('i.fecha')
            ->addSelect('i.estadoAutorizado')
            ->addSelect('i.estadoAprobado')
            ->addSelect('i.estadoAnulado')
            ->where('i.codigoSolicitudPk <> 0');
        if ($session->get('filtroInvSolicitudNumero') != '') {
            $queryBuilder->andWhere("i.numero = {$session->get('filtroInvSolicitudNumero')}");
        }
        switch ($session->get('filtroInvSolicitudEstadoAprobado')) {
            case '0':
                $queryBuilder->andWhere("i.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("i.estadoAprobado = 1");
                break;
        }
        if ($session->get('filtroInvSolicitudCodigoSolicitudTipo')) {
            $queryBuilder->andWhere("i.codigoSolicitudTipoFk = '{$session->get('filtroInvSolicitudCodigoSolicitudTipo')}'");
        }
        return $queryBuilder;
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arrSeleccionados)
    {
        /**
         * @var $arSolicitud InvSolicitud
         */
        $respuesta = '';
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(InvSolicitud::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(InvSolicitudDetalle::class)->findBy(['codigoSolicitudFk' => $arRegistro->getCodigoSolicitudPk()])) <= 0) {
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

    /**
     * @param $arSolicitud
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arSolicitud)
    {
        $arSolicitudTipo = $this->getEntityManager()->getRepository('App:Inventario\InvSolicitudTipo')->findOneBy(['codigoSolicitudTipoPk' => $arSolicitud->getCodigoSolicitudTipoFk()]);
        if (!$arSolicitud->getEstadoAprobado()) {
            $arSolicitudTipo->setConsecutivo($arSolicitudTipo->getConsecutivo() + 1);
            $arSolicitud->setEstadoAprobado(1);
            $arSolicitud->setNumero($arSolicitudTipo->getConsecutivo());
            $this->getEntityManager()->persist($arSolicitudTipo);
            $this->getEntityManager()->persist($arSolicitud);
        }
        $arSolicitudDetalles = $this->getEntityManager()->getRepository('App:Inventario\InvSolicitudDetalle')->findBy(['codigoSolicitudFk' => $arSolicitud->getCodigoSolicitudPk()]);
        foreach ($arSolicitudDetalles as $arSolicitudDetalle) {
            $arItem = $this->getEntityManager()->getRepository('App:Inventario\InvItem')->findOneBy(['codigoItemPk' => $arSolicitudDetalle->getCodigoItemFk()]);
            $arItem->setCantidadSolicitud($arItem->getCantidadSolicitud() + $arSolicitudDetalle->getCantidad());
            $this->getEntityManager()->persist($arItem);
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @param $arSolicitud
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function anular($arSolicitud)
    {
        $respuesta = [];
        if ($arSolicitud->getEstadoAprobado() == 1) {
            $arSolicitud->setEstadoAnulado(1);
            $this->getEntityManager()->persist($arSolicitud);
        }
        $arSolicitudDetalles = $this->getEntityManager()->getRepository('App:Inventario\InvSolicitudDetalle')->findBy(['codigoSolicitudFk' => $arSolicitud->getCodigoSolicitudPk()]);

        foreach ($arSolicitudDetalles as $arSolicitudDetalle) {
            $respuesta = $this->validarDetalleEnuso($arSolicitudDetalle->getCodigoSolicitudDetallePk());
            $arItem = $this->getEntityManager()->getRepository('App:Inventario\InvItem')->findOneBy(['codigoItemPk' => $arSolicitudDetalle->getCodigoItemFk()]);
            $arItem->setCantidadSolicitud($arItem->getCantidadSolicitud() - $arSolicitudDetalle->getCantidad());
            $this->getEntityManager()->persist($arItem);
        }
        if (count($respuesta) == 0) {
            $this->getEntityManager()->flush();
        }
        return $respuesta;
    }

    /**
     * @param $arSolicitud
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arSolicitud)
    {
        if ($arSolicitud->getEstadoAutorizado() == 1 && $arSolicitud->getEstadoAprobado() == 0) {
            $arSolicitud->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arSolicitud);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('El registro esta impreso y no se puede desautorizar');
        }
    }

    /**
     * @param $arSolicitud InvSolicitud
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arSolicitud)
    {
        if ($this->contarDetalles($arSolicitud->getCodigoSolicitudPk()) > 0) {
            $arSolicitud->setEstadoAutorizado(1);
            $this->getEntityManager()->persist($arSolicitud);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('No se puede autorizar, el registro no tiene detalles');
        }
    }

    private function validarDetalleEnuso($codigoSolicitudDetalle)
    {
        $respuesta = [];
        $qb = $this->getEntityManager()->createQueryBuilder()->from('App:Inventario\InvOrdenCompraDetalle', 'ocd')
            ->select('ocd.codigoOrdenCompraDetallePk')
            ->addSelect('ocd.codigoOrdenCompraFk')
            ->join('ocd.ordenCompraRel', 'oc')
            ->where("ocd.codigoSolicitudDetalleFk = {$codigoSolicitudDetalle}")
            ->andWhere('oc.estadoAnulado = 0');
        $query = $this->getEntityManager()->createQuery($qb->getDQL());
        $resultado = $query->execute();
        if (count($resultado) > 0) {
            foreach ($resultado as $result) {
                $respuesta[] = 'No se puede anular, el detalle con ID ' . $codigoSolicitudDetalle . ' esta siendo utilizado en la orden de compra con ID ' . $result['codigoOrdenCompraFk'];
            }
        }
        return $respuesta;
    }

    /**
     * @param $codigoSolicitud
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function contarDetalles($codigoSolicitud)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvSolicitudDetalle::class, 'isd')
            ->select("COUNT(isd.codigoSolicitudDetallePk)")
            ->where("isd.codigoSolicitudFk = {$codigoSolicitud} ");
        $resultado =  $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }


}