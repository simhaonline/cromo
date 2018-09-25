<?php

namespace App\Repository\Inventario;

use App\Utilidades\Mensajes;
use App\Entity\Inventario\InvSolicitudDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

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
            if ($arrDetallesSeleccionados) {
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
            }
        } else {
            Mensajes::error('No se puede eliminar, el registro se encuentra autorizado');
        }
    }

    public function listarDetallesPendientes()
    {
        $session = new Session();
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
            ->andWhere('s.estadoAnulado = false')
            ->andWhere('isd.cantidadPendiente > 0');
        if ($session->get('filtroInvItemNombre') != '') {
            $queryBuilder->andWhere("it.nombre LIKE '%{$session->get('filtroInvItemNombre')}%'");
        }
        if ($session->get('filtroInvItemCodigo') != '') {
            $queryBuilder->andWhere("isd.codigoItemFk = {$session->get('filtroInvItemCodigo')}");
        }
        if ($session->get('filtroInvSolicitudCodigo') != '') {
            $queryBuilder->andWhere("isd.codigoSolicitudFk = {$session->get('filtroInvSolicitudCodigo')} ");
        }
        $queryBuilder->orderBy('isd.codigoSolicitudDetallePk', 'ASC');
        return $queryBuilder;
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function pendientes(){
        $session = new Session();
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(InvSolicitudDetalle::class,'sd')
            ->select('sd.codigoSolicitudDetallePk')
            ->addSelect('s.numero')
            ->addSelect('sd.codigoSolicitudFk')
            ->addSelect('sd.cantidadPendiente')
            ->addSelect('sd.codigoItemFk')
            ->addSelect('it.nombre')
            ->addSelect('st.nombre as tipo')
            ->leftJoin('sd.solicitudRel', 's')
            ->leftJoin('s.solicitudTipoRel', 'st')
            ->leftJoin('sd.itemRel', 'it')
            ->where('s.estadoAprobado = 1')
            ->andWhere('sd.cantidadPendiente > 0');
        if($session->get('filtroInvInformeSolicitudSolicitudTipo') != null){
            $queryBuilder->andWhere("s.codigoSolicitudTipoFk = '{$session->get('filtroInvInformeSolicitudSolicitudTipo')}'");
        }
        return $queryBuilder->getQuery();
    }
}