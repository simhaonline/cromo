<?php

namespace App\Repository\Inventario;

use App\Controller\Estructura\MensajesController;
use App\Entity\Inventario\InvSolicitud;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvSolicitudRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvSolicitud::class);
    }


    public function camposPredeterminados()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('s.codigoSolicitudPk AS ID')
            ->addSelect('s.numero AS NUMERO')
            ->addSelect('st.nombre AS TIPO')
            ->addSelect('s.fecha AS FECHA')
            ->from('App:Inventario\InvSolicitud', 's')
            ->join('s.solicitudTipoRel','st')
            ->where('s.codigoSolicitudPk <> 0')
            ->orderBy('s.codigoSolicitudPk', 'DESC');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();
    }

    /**
     * @param $arrSeleccionados array
     */
    public function eliminar($arrSeleccionados)
    {
        /**
         * @var $arSolicitud InvSolicitud
         */
        $respuesta = '';
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados as $codigoSolicitud) {
                $arSolicitud = $this->_em->getRepository($this->_entityName)->find($codigoSolicitud);
                if ($arSolicitud) {
                    if ($arSolicitud->getEstadoImpreso() == 0) {
                        if ($arSolicitud->getEstadoAutorizado() == 0) {
                            if (count($this->_em->getRepository('App:Inventario\InvSolicitudDetalle')->findBy(['codigoSolicitudFk' => $arSolicitud->getCodigoSolicitudPk()])) <= 0) {
                                    $this->_em->remove($arSolicitud);
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
     * @param $arSolicitud InvSolicitud
     */
    public function imprimir($arSolicitud)
    {
        $arSolicitudTipo = $this->_em->getRepository('App:Inventario\InvSolicitudTipo')->findOneBy(['codigoSolicitudTipoPk' => $arSolicitud->getCodigoSolicitudTipoFk()]);
        if(!$arSolicitud->getEstadoImpreso()){
            $arSolicitudTipo->setConsecutivo($arSolicitudTipo->getConsecutivo()+1);
            $arSolicitud->setEstadoImpreso(1);
            $this->_em->persist($arSolicitudTipo);
            $this->_em->persist($arSolicitud);
            $this->_em->flush();
        }
    }

    /**
     * @param $arSolicitud
     */
    public function anular($arSolicitud)
    {
        if ($arSolicitud->getEstadoImpreso() == 1) {
            $arSolicitud->setEstadoAnulado(1);
            $this->_em->persist($arSolicitud);
            $this->_em->flush();
        }
    }

    /**
     * @param $arSolicitud InvSolicitud
     */
    public function desautorizar($arSolicitud)
    {
        if ($arSolicitud->getEstadoAutorizado() == 1 && $arSolicitud->getEstadoImpreso() == 0) {
            $arSolicitud->setEstadoAutorizado(0);
            $this->_em->persist($arSolicitud);
            $this->_em->flush();
        } else {
            MensajesController::error('El registro esta impreso y no se puede desautorizar');
        }
    }

    /**
     * @param $arSolicitud InvSolicitud
     */
    public function autorizar($arSolicitud)
    {
        if (count($this->_em->getRepository('App:Inventario\InvSolicitudDetalle')->findBy(['codigoSolicitudFk' => $arSolicitud->getCodigoSolicitudPk()])) > 0) {
            $arSolicitud->setEstadoAutorizado(1);
            $this->_em->persist($arSolicitud);
            $this->_em->flush();
        } else {
            MensajesController::error('No se puede autorizar, el registro no tiene detalles');
        }
    }

}