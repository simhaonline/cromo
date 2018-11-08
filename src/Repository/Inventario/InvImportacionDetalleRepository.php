<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvImportacionDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

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
                  id.cantidad,
                  id.vrPrecioExtranjero,
                  id.porcentajeIvaExtranjero,
                  id.vrIvaExtranjero,
                  id.vrSubtotalExtranjero,
                  id.vrNetoExtranjero,
                  id.vrTotalExtranjero,                                    
                  id.vrPrecioLocal,
                  id.porcentajeIvaLocal,
                  id.vrIvaLocal,
                  id.vrSubtotalLocal,
                  id.vrNetoLocal,
                  id.vrTotalLocal,
                  i.nombre as itemNombre,
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
            if (count($arrDetallesSeleccionados)) {
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

}
