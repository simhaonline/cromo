<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuRequisitoConcepto;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuRequisitoConceptoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuRequisitoConcepto::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoRequisitoConcepto = null;
        $nombre = null;

        if ($filtros) {
            $codigoRequisitoConcepto = $filtros['codigoRequisitoConcepto'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuRequisitoConcepto::class, 'rc')
            ->select('rc.codigoRequisitoConceptoPk')
            ->addSelect('rc.nombre')
            ->addSelect('rc.general')
            ->orderBy('rc.codigoRequisitoConceptoPk', 'ASC');

        if ($codigoRequisitoConcepto) {
            $queryBuilder->andWhere("rc.codigoRequisitoConceptoPk = '{$codigoRequisitoConcepto}'");
        }

        if($nombre){
            $queryBuilder->andWhere("rc.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('rc.codigoRequisitoConceptoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(RhuRequisitoConcepto::class)->find($codigo);
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