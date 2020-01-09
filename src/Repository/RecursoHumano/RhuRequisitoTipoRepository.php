<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuRequisito;
use App\Entity\RecursoHumano\RhuRequisitoCargo;
use App\Entity\RecursoHumano\RhuRequisitoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuRequisitoTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuRequisitoTipo::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoRequisitoTipo = null;
        $nombre = null;

        if ($filtros) {
            $codigoRequisitoTipo = $filtros['codigoRequisitoTipo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuRequisitoTipo::class, 'rt')
            ->select('rt.codigoRequisitoTipoPk')
            ->addSelect('rt.nombre');
        if ($codigoRequisitoTipo) {
            $queryBuilder->andWhere("rt.codigoRequisitoTipoPk = '{$codigoRequisitoTipo}'");
        }
        if($nombre){
            $queryBuilder->andWhere("rt.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('rt.codigoRequisitoTipoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }
    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(RhuRequisitoTipo::class)->find($codigo);
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