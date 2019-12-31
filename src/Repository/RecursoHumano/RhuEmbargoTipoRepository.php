<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmbargoTipo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuEmbargoTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuEmbargoTipo::class);
    }

    public function camposPredeterminados(){
        return $this->_em->createQueryBuilder()->from(RhuEmbargoTipo::class,'et')
            ->select('et.codigoEmbargoTipoPk AS ID')
            ->addSelect('et.nombre')
            ->addSelect('et.codigoConceptoFk')
            ->where('et.codigoEmbargoTipoPk IS NOT NULL');
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoEmbargoTipo = null;
        $nombre = null;

        if ($filtros) {
            $codigoEmbargoTipo = $filtros['codigoEmbargoTipo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuEmbargoTipo::class, 'et')
            ->select('et.codigoEmbargoTipoPk')
            ->addSelect('et.nombre')
            ->addSelect('c.nombre as concepto')
            ->leftJoin('et.conceptoRel', 'c');
        if ($codigoEmbargoTipo) {
            $queryBuilder->andWhere("et.codigoEmbargoTipoPk = '{$codigoEmbargoTipo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("et.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('et.codigoEmbargoTipoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(RhuEmbargoTipo::class)->find($codigo);
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