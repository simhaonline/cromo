<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarDescuentoConcepto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CarDescuentoConceptoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarDescuentoConcepto::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoDescuentoConcepto = null;
        $nombre = null;

        if ($filtros) {
            $codigoDescuentoConcepto = $filtros['codigoDescuentoConcepto'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarDescuentoConcepto::class, 'dc')
            ->select('dc.codigoDescuentoConceptoPk')
            ->addSelect('dc.nombre')
            ->addSelect('dc.codigoCuentaFk');
        if ($codigoDescuentoConcepto) {
            $queryBuilder->andWhere("dc.codigoDescuentoConceptoPk LIKE '%{$codigoDescuentoConcepto}%'");
        }
        if($nombre){
            $queryBuilder->andWhere("dc.nombre LIKE '%{$nombre}%'");
        }
        $queryBuilder->addOrderBy('dc.codigoDescuentoConceptoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(CarDescuentoConcepto::class)->find($codigo);
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