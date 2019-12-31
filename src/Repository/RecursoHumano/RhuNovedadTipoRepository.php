<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuNovedadTipo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuNovedadTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuNovedadTipo::class);
    }

    public function camposPredeterminados(){
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuNovedadTipo::class,'nt')
            ->leftJoin('nt.conceptoRel','c')
            ->select('nt.codigoNovedadTipoPk AS ID')
            ->addSelect('nt.nombre')
            ->addSelect('nt.subTipo')
            ->addSelect('c.nombre AS CONCEPTO')
            ->where('nt.codigoNovedadTipoPk IS NOT NULL');
        return $queryBuilder->getQuery()->execute();
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoNovedadTipo = null;
        $nombre = null;

        if ($filtros) {
            $codigoNovedadTipo = $filtros['codigoNovedadTipo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuNovedadTipo::class, 'nt')
            ->select('nt.codigoNovedadTipoPk')
            ->addSelect('nt.nombre');

        if ($codigoNovedadTipo) {
            $queryBuilder->andWhere("nt.codigoNovedadTipoPk = '{$codigoNovedadTipo}'");
        }

        if ($nombre) {
            $queryBuilder->andWhere("nt.nombre like '%{$nombre}%' ");
        }

        $queryBuilder->addOrderBy('nt.codigoNovedadTipoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(RhuNovedadTipo::class)->find($codigo);
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