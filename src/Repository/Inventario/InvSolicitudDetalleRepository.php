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
                try {
                    $this->_em->flush();
                } catch (\Exception $e) {
                    MensajesController::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                }
            }
        } else {
            MensajesController::error('No se puede eliminar, el registro se encuentra autorizado');
        }
    }

    public function listarDetallesPendientes($nombreItem = '', $codigoItem = '', $codigoSolicitud = '')
    {
        $qb = $this->_em->createQueryBuilder()->from('App:Inventario\InvSolicitudDetalle', 'isd');
        $qb
            ->select('isd.codigoSolicitudDetallePk')
            ->join('isd.itemRel', 'it')
            ->join('isd.solicitudRel','s')
            ->addSelect('it.nombre')
            ->addSelect('it.cantidadExistencia')
            ->addSelect('isd.cantidadSolicitada')
            ->addSelect('isd.cantidadPendiente')
            ->addSelect('it.stockMinimo')
            ->addSelect('it.stockMaximo')
            ->where('s.estadoAprobado = true')
            ->where('s.estadoAnulado = false')
            ->andWhere('isd.cantidadPendiente > 0');
        if ($nombreItem != '') {
            $qb->andWhere("it.nombre LIKE '%{$nombreItem}%'");
        }
        if ($codigoItem != '') {
            $qb->andWhere("isd.codigoItemFk = {$codigoItem}");
        }
        if ($codigoSolicitud != '') {
            $qb->andWhere("isd.codigoSolicitudFk = {$codigoSolicitud} ");
        }
        $qb->orderBy('isd.codigoSolicitudDetallePk', 'ASC');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}