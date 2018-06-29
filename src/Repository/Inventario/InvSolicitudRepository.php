<?php

namespace App\Repository\Inventario;

use App\Controller\Estructura\MensajesController;
use App\Entity\Inventario\InvSolicitud;
use App\Entity\Inventario\InvSolicitudTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvSolicitudRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvSolicitud::class);
    }


    public function camposPredeterminados()
    {
        $qb = $this->getEntityManager()->createQueryBuilder()->from('App:Inventario\InvSolicitud', 's');
        $qb->select('s.codigoSolicitudPk AS ID')
            ->addSelect('s.numero AS NUMERO')
            ->addSelect('st.nombre AS TIPO')
            ->addSelect('s.fecha AS FECHA')
            ->addSelect('s.estadoAutorizado AS AUTORIZADO')
            ->addSelect('s.estadoAprobado AS APROBADO')
            ->addSelect('s.estadoAnulado AS ANULADO')
            ->join('s.solicitudTipoRel', 'st')
            ->where('s.codigoSolicitudPk <> 0')
            ->orderBy('s.codigoSolicitudPk', 'DESC');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();
    }

    /**
     * @param string $numero
     * @param string $estadoAprobado
     * @param InvSolicitudTipo $arSolicitudTipo
     * @return mixed
     */
    public function listaSolicitud($numero = '', $estadoAprobado = '', $arSolicitudTipo = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()->from('App:Inventario\InvSolicitud', 'i')
            ->select('i.codigoSolicitudPk')
            ->join('i.solicitudTipoRel', 'it')
            ->addSelect('i.numero')
            ->addSelect('it.nombre as nombreTipo')
            ->addSelect('i.fecha')
            ->addSelect('i.estadoAutorizado')
            ->addSelect('i.estadoAprobado')
            ->addSelect('i.estadoAnulado')
            ->where('i.codigoSolicitudPk <> 0');
        if ($numero != '') {
            $qb->andWhere("i.numero = {$numero}");
        }
        switch ($estadoAprobado) {
            case '0':
                $qb->andWhere("i.estadoAprobado = 0");
                break;
            case '1':
                $qb->andWhere("i.estadoAprobado = 1");
                break;
        }
        if($arSolicitudTipo){
            $qb->andWhere("i.codigoSolicitudTipoFk = '{$arSolicitudTipo->getCodigoSolicitudTipoPk()}'");
        }
        $query = $this->getEntityManager()->createQuery($qb->getDQL());
        return $query->execute();
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
            foreach ($arrSeleccionados as $codigoSolicitud) {
                $arSolicitud = $this->getEntityManager()->getRepository($this->_entityName)->find($codigoSolicitud);
                if ($arSolicitud) {
                    if ($arSolicitud->getEstadoAprobado() == 0) {
                        if ($arSolicitud->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository('App:Inventario\InvSolicitudDetalle')->findBy(['codigoSolicitudFk' => $arSolicitud->getCodigoSolicitudPk()])) <= 0) {
                                $this->getEntityManager()->remove($arSolicitud);
                            } else {
                                $respuesta = 'No se puede eliminar, el registro tiene detalles';
                            }
                        } else {
                            $respuesta = 'No se puede eliminar, el registro se encuentra autorizado';
                        }
                    } else {
                        $respuesta = 'No se puede eliminar, el registro se encuentra impreso';
                    }
                }
                MensajesController::error($respuesta);
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
            MensajesController::error('El registro esta impreso y no se puede desautorizar');
        }
    }

    /**
     * @param $arSolicitud
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arSolicitud)
    {
        if (count($this->getEntityManager()->getRepository('App:Inventario\InvSolicitudDetalle')->findBy(['codigoSolicitudFk' => $arSolicitud->getCodigoSolicitudPk()])) > 0) {
            $arSolicitud->setEstadoAutorizado(1);
            $this->getEntityManager()->persist($arSolicitud);
            $this->getEntityManager()->flush();
        } else {
            MensajesController::error('No se puede autorizar, el registro no tiene detalles');
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

}