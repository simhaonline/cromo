<?php

namespace App\Repository\Inventario;

use App\Utilidades\Mensajes;
use App\Entity\Inventario\InvSolicitudDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvSolicitudDetalleRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvSolicitudDetalle::class);
    }

    public function lista($codigoRegistro)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('sd')
            ->from(InvSolicitudDetalle::class, 'sd')
            ->where("sd.codigoSolicitudFk = {$codigoRegistro}")
            ->orderBy('sd.codigoSolicitudDetallePk', 'DESC');
        return $queryBuilder;
    }

    /**
     * @param $arRegistro
     * @param $arrDetallesSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arRegistro, $arrDetallesSeleccionados)
    {
        if ($arRegistro->getEstadoAutorizado() == 0) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $this->getEntityManager()->getRepository(InvSolicitudDetalle::class)->find($codigo);
                    if ($ar) {
                        $this->getEntityManager()->remove($ar);
                    }
                }
                try {
                    $this->getEntityManager()->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                }
            }
        } else {
            Mensajes::error('No se puede eliminar, el registro se encuentra autorizado');
        }
    }

    public function listarDetallesPendientes($nombreItem = '', $codigoItem = '', $codigoRegistro = '')
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvSolicitudDetalle::class, 'isd');
        $queryBuilder
            ->select('isd.codigoSolicitudDetallePk')
            ->join('isd.itemRel', 'it')
            ->join('isd.solicitudRel', 's')
            ->addSelect('it.nombre')
            ->addSelect('it.cantidadExistencia')
            ->addSelect('isd.cantidad')
            ->addSelect('isd.cantidadPendiente')
            ->addSelect('it.stockMinimo')
            ->addSelect('it.stockMaximo')
            ->where('s.estadoAprobado = true')
            ->where('s.estadoAnulado = false')
            ->andWhere('isd.cantidadPendiente > 0');
        if ($nombreItem != '') {
            $queryBuilder->andWhere("it.nombre LIKE '%{$nombreItem}%'");
        }
        if ($codigoItem != '') {
            $queryBuilder->andWhere("isd.codigoItemFk = {$codigoItem}");
        }
        if ($codigoRegistro != '') {
            $queryBuilder->andWhere("isd.codigoSolicitudFk = {$codigoRegistro} ");
        }
        $queryBuilder->orderBy('isd.codigoSolicitudDetallePk', 'ASC');
        return $queryBuilder;
    }
}