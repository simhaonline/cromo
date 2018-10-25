<?php

namespace App\Repository\Inventario;


use App\Entity\Inventario\InvMovimientoDetalle;
use App\Entity\Inventario\InvRemisionDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Session\Session;

class InvRemisionDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvRemisionDetalle::class);
    }

    public function remision($codigoRemision)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvRemisionDetalle::class,'rd')
            ->select('rd.codigoRemisionDetallePk')
            ->addSelect('rd.codigoRemisionFk')
            ->addSelect('rd.codigoItemFk')
            ->addSelect('rd.loteFk')
            ->addSelect('rd.codigoBodegaFk')
            ->addSelect('rd.cantidad')
            ->addSelect('rd.cantidadPendiente')
            ->addSelect('i.nombre as itemNombre')
            ->addSelect('m.nombre as itemMarcaNombre')
            ->addSelect('rd.cantidadAfectada')
            ->addSelect('rd.vrPrecio')
            ->addSelect('rd.porcentajeIva')
            ->addSelect('rd.vrIva')
            ->addSelect('rd.vrSubtotal')
            ->addSelect('rd.vrNeto')
            ->leftJoin('rd.itemRel','i')
            ->leftJoin('i.marcaRel','m')
            ->addSelect('rd.vrTotal')
            ->where("rd.codigoRemisionFk = {$codigoRemision}");

        return $queryBuilder;
    }

    /**
     * @param $arRemision
     * @param $arrDetallesSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arRemision, $arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if (!$arRemision->getEstadoAutorizado()) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(InvRemisionDetalle::class)->find($codigo);
                    if ($ar) {
                        $em->remove($ar);
                    }
                }
                try {
                    $em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro porque se encuentra en uso');
                }
            }
        } else {
            Mensajes::error('No se puede eliminar, el registro se encuentra autorizado');
        }
    }

    public function listarDetallesPendientes()
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(InvRemisionDetalle::class, 'rd')
            ->select('rd.codigoRemisionDetallePk')
            ->addSelect('rd.codigoItemFk')
            ->addSelect('rd.cantidad')
            ->addSelect('rd.cantidadPendiente')
            ->addSelect('i.nombre AS itemNombre')
            ->addSelect('r.numero')
            ->addSelect('rd.loteFk')
            ->addSelect('rd.codigoBodegaFk')
            ->leftJoin('rd.itemRel', 'i')
            ->leftJoin('rd.remisionRel', 'r')
            ->where('r.estadoAnulado = 0')
            ->andWhere('r.estadoAprobado = 1')
            ->andWhere('rd.cantidadPendiente > 0')
            ->orderBy('rd.codigoRemisionDetallePk', 'DESC');
        if($session->get('filtroInvRemisionNumero')){
            $queryBuilder->andWhere("r.numero = '{$session->get('filtroInvRemisionNumero')}'");
        }

        return $queryBuilder;
    }

    public function pendientes(){
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvRemisionDetalle::class,'rd')
            ->select('rd.codigoRemisionDetallePk')
            ->addSelect('rd.codigoRemisionFk')
            ->addSelect('rd.codigoItemFk')
            ->addSelect('rd.cantidadPendiente')
            ->addSelect('rd.cantidad')
            ->addSelect('i.nombre')
            ->addSelect('r.numero')
            ->addSelect('r.fecha as fechaPedido')
            ->addSelect('rt.nombre as pedidoTipo')
            ->addSelect('t.nombreCorto as terceroNombreCorto')
            ->leftJoin('rd.itemRel','i')
            ->leftJoin('rd.remisionRel','r')
            ->leftJoin('r.remisionTipoRel','rt')
            ->leftJoin('r.terceroRel', 't')
            ->where('r.estadoAprobado = 1')
            ->andWhere('r.estadoAnulado = 0')
            ->andWhere('rd.cantidadPendiente > 0');
        if($session->get('filtroInvRemisionTipo')){
            $queryBuilder->andWhere("r.codigoRemisionTipoFk = '{$session->get('filtroInvRemisionTipo')}'");
        }
        return $queryBuilder->getQuery();
    }


}