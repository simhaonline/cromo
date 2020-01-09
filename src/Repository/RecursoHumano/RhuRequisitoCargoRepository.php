<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuRequisitoCargo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuRequisitoCargoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuRequisitoCargo::class);
    }


    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoRequisitoCargo = null;

        if ($filtros) {
            $codigoRequisitoCargo = $filtros['codigoRequisitoCargo'] ?? null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuRequisitoCargo::class, 'rhrc')
            ->select('rhrc.codigoRequisitoCargoPk')
            ->addSelect('rc.nombre AS nombreRequisito')
            ->addSelect('c.nombre AS nombreCargo')
            ->addSelect('rc.general')
            ->leftJoin('rhrc.requisitoConceptoRel','rc')
            ->leftJoin('rhrc.cargoRel','c')
            ->orderBy('rhrc.codigoRequisitoCargoPk', 'ASC');

        if ($codigoRequisitoCargo) {
            $queryBuilder->andWhere("rhrc.codigoRequisitoCargoPk = '{$codigoRequisitoCargo}'");
        }
        $queryBuilder->addOrderBy('rhrc.codigoRequisitoCargoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(RhuRequisitoCargo::class)->find($codigo);
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