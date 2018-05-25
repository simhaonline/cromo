<?php

namespace App\Repository\Inventario;

use App\Controller\Estructura\MensajesController;
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
     * @param $arSolicitud
     * @param $arrDetallesSeleccionados
     */
    public function eliminar($arSolicitud, $arrDetallesSeleccionados)
    {
        if ($arSolicitud->getEstadoAutorizado() == 0) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigoSolicitudDetalle) {
                    $arSolicitudDetalle = $this->_em->getRepository('App:Inventario\InvSolicitudDetalle')->find($codigoSolicitudDetalle);
                    if ($arSolicitudDetalle) {
                        $this->_em->remove($arSolicitudDetalle);
                    }
                }
                try{
                    $this->_em->flush();
                } catch (\Exception $e){
                    MensajesController::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                }
            }
        } else {
            MensajesController::error('No se puede eliminar, el registro se encuentra autorizado');
        }
    }



}