<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvSolicitud;
use App\Entity\Inventario\InvSolicitudDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;

class InvSolicitudDetalleRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvSolicitudDetalle::class);
    }

    public function lista()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('sd,ir.nombre')
            ->from('App:Inventario\InvSolicitudDetalle', 'sd')
            ->join('sd.itemRel', 'ir')
            ->where('sd.codigoSolicitudDetallePk <> 0')
            ->orderBy('sd.codigoSolicitudPk', 'DESC');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();
    }

    /**
     * @param $arSolicitud InvSolicitud
     * @return string
     */
    public function autorizar($arSolicitud)
    {
        $respuesta = '';
        if (count($this->_em->getRepository($this->_entityName)->findBy(['codigoSolicitudFk' => $arSolicitud->getCodigoSolicitudPk()])) > 0) {
            $arSolicitud->setEstadoAutorizado(1);
            $this->_em->persist($arSolicitud);
            $this->_em->flush();
        } else {
            $respuesta = 'No se puede autorizar, el registro no tiene detalles';
        }
        return $respuesta;
    }

    /**
     * @param $arSolicitud InvSolicitud
     * @return string
     */
    public function desautorizar($arSolicitud)
    {
        $respuesta = '';
        if ($arSolicitud->getEstadoAutorizado() == 1 && $arSolicitud->getEstadoImpreso() == 0) {
            $arSolicitud->setEstadoAutorizado(0);
            $this->_em->persist($arSolicitud);
        } else {
            $respuesta = 'El registro esta impreso y no se puede desautorizar';
        }
        return $respuesta;
    }

    /**
     * @param $arSolicitud InvSolicitud
     * @param $arrControles array
     * @return string
     */
    public function eliminar($arSolicitud, $arrControles)
    {
        $respuesta = '';
        if ($arSolicitud->getEstadoAutorizado() == 0) {
            if ($arrControles) {
                foreach ($arrControles as $codigoSolicitudDetalle) {
                    $arSolicitudDetalle = $this->_em->getRepository($this->_entityName)->find($codigoSolicitudDetalle);
                    if ($arSolicitudDetalle) {
                        $this->_em->remove($arSolicitudDetalle);
                    }
                }
            }
        } else {
            $respuesta = 'No se puede eliminar, el registro se encuentra autorizado';
        }
        return $respuesta;
    }

    /**
     * @param $arSolicitud InvSolicitud
     */
    public function imprimir($arSolicitud)
    {

    }

    public function anular($arSolicitud)
    {
        if ($arSolicitud->getEstadoImpreso() == 1) {
            $arSolicitud->setEstadoAnulado(1);
            $this->_em->persist($arSolicitud);
            $this->_em->flush();
        }
    }
}