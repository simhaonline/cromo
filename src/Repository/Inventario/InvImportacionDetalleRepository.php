<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvImportacionDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @method InvImportacionDetalle|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvImportacionDetalle|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvImportacionDetalle[]    findAll()
 * @method InvImportacionDetalle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvImportacionDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvImportacionDetalle::class);
    }

    public function importacion($codigoImportacion): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT id.codigoImportacionDetallePk,
                  id.codigoImportacionFk,
                  id.codigoItemFk,
                  id.cantidad,
                  id.cantidadPendiente,
                  id.vrPrecioExtranjero,
                  id.porcentajeIvaExtranjero,
                  id.vrIvaExtranjero,
                  id.vrSubtotalExtranjero,
                  id.vrNetoExtranjero,
                  id.vrTotalExtranjero,                                    
                  id.vrPrecioLocal,
                  id.vrPrecioLocalTotal,
                  id.porcentajeIvaLocal,
                  id.vrIvaLocal,
                  id.vrSubtotalLocal,
                  id.vrNetoLocal,
                  id.vrTotalLocal,
                  id.porcentajeParticipaCosto,
                  id.vrCostoParticipa,
                  i.nombre as itemNombre,
                  i.referencia as itemReferencia,
                  m.nombre as itemMarcaNombre                         
        FROM App\Entity\Inventario\InvImportacionDetalle id
        LEFT JOIN id.itemRel i
        LEFT JOIN i.marcaRel m
        WHERE id.codigoImportacionFk = :codigoImportacion'
        )->setParameter('codigoImportacion', $codigoImportacion);

        return $query->execute();
    }

    public function eliminar($arImportacion, $arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if (!$arImportacion->getEstadoAutorizado()) {
            if ($arrDetallesSeleccionados) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(InvImportacionDetalle::class)->find($codigo);
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

    public function listaDetalle($codigoImportacion){
        return $this->_em->createQueryBuilder()
            ->select('imd.codigoItemFk')
            ->addSelect('i.nombre as itemNombre')
            ->addSelect('m.nombre as marca')
            ->addSelect('imd.cantidad')
            ->addSelect('imd.vrPrecioExtranjero')
            ->addSelect('imd.porcentajeIvaExtranjero')
            ->addSelect('imd.vrTotalExtranjero')
            ->from(InvImportacionDetalle::class,'imd')
            ->leftJoin('imd.itemRel','i')
            ->leftJoin('i.marcaRel','m')
            ->where("imd.codigoImportacionFk = {$codigoImportacion}")->getQuery()->execute();
    }

    public function listarDetallesPendientes()
    {
        $em = $this->getEntityManager();
        $session = new Session();
        $queryBuilder = $em->createQueryBuilder()->from(InvImportacionDetalle::class, 'id')
            ->select('id.codigoImportacionDetallePk')
            ->addSelect('id.codigoItemFk')
            ->addSelect('i.nombre AS itemNombre')
            ->addSelect('i.referencia AS itemReferencia')
            ->addSelect('i.cantidadExistencia')
            ->addSelect('id.cantidad')
            ->addSelect('id.cantidadPendiente')
            ->addSelect('im.numero AS importacionNumero')
            ->join('id.itemRel', 'i')
            ->join('id.importacionRel', 'im')
            ->where('im.estadoAprobado = 1')
            ->andWhere('im.estadoAnulado = 0')
            ->andWhere('id.cantidadPendiente > 0')
            ->orderBy('im.fecha', 'DESC')
            ->addOrderBy('id.codigoImportacionDetallePk', 'ASC');
        $query = $em->createQuery($queryBuilder->getDQL());
        return $query->execute();
    }

    public function cuentaCompra($codigo){
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvImportacionDetalle::class, 'id')
            ->select('i.codigoCuentaCompraFk')
            ->addSelect('SUM(id.vrSubtotalLocal) as vrSubtotalLocal')
            ->leftJoin('id.itemRel', 'i')
            ->where('id.codigoImportacionFk = ' . $codigo)
            ->groupBy('i.codigoCuentaCompraFk');
        $arrCuentas = $queryBuilder->getQuery()->getResult();
        return $arrCuentas;
    }

    public function cuentaInventarioTransito($codigo){
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvImportacionDetalle::class, 'id')
            ->select('i.codigoCuentaInventarioTransitoFk')
            ->addSelect('SUM(id.vrSubtotalLocal) as vrSubtotalLocal')
            ->leftJoin('id.itemRel', 'i')
            ->where('id.codigoImportacionFk = ' . $codigo)
            ->groupBy('i.codigoCuentaInventarioTransitoFk');
        $arrCuentas = $queryBuilder->getQuery()->getResult();
        return $arrCuentas;
    }

}
