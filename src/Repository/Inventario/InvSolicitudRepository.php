<?php

namespace App\Repository\Inventario;

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


    public function lista()
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
     * @return string
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
            }
        }
        return $respuesta;
    }

}