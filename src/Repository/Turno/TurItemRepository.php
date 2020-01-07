<?php


namespace App\Repository\Turno;

use App\Entity\Turno\TurItem;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurItem::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $itemCodigo = null;
        $itemNombre = null;
        if ($filtros) {
            $itemCodigo = $filtros['itemCodigo'] ?? null;
            $itemNombre = $filtros['itemNombre'] ?? null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurItem::class, 'i')
            ->select('i.codigoItemPk')
            ->addSelect('i.nombre')
            ->addSelect('i.codigoImpuestoIvaVentaFk')
            ->addSelect('i.porcentajeIva')
            ->addSelect('i.codigoImpuestoRetencionFk');

        if ($itemCodigo) {
            $queryBuilder->andWhere("i.codigoItemPk = '{$itemCodigo}'");
        }
        if ($itemNombre) {
            $queryBuilder->andWhere("i.nombre like '%{$itemNombre}%' ");
        }

        $queryBuilder->addOrderBy('i.codigoItemPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(TurItem::class)->find($codigo);
                    if ($ar) {
                        $em->remove($ar);
                    }
                }
                try {
                    $em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                }
            }
        }
    }
}