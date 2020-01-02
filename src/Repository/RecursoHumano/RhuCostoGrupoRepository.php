<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuContratoTipo;
use App\Entity\RecursoHumano\RhuCostoGrupo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuCostoGrupoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuCostoGrupo::class);
    }

    public function camposPredeterminados(){
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuCostoGrupo::class,'rcg')
            ->select('rcg.codigoCostoGrupoPk as ID')
            ->addSelect('rcg.nombre')
            ->addSelect('rcg.codigoCentroCostoFk')
            ->where('rcg.codigoCostoGrupoPk IS NOT NULL');
        return $queryBuilder->getQuery()->execute();
    }


    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoCostoGrupo = null;
        $nombre = null;

        if ($filtros) {
            $codigoCostoGrupo = $filtros['codigoCostoGrupo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuCostoGrupo::class, 'g')
            ->select('g.codigoCostoGrupoPk')
            ->addSelect('g.nombre')
            ->addSelect('cc.nombre as centroCostro')
            ->leftJoin('g.centroCostoRel', 'cc');


        if ($codigoCostoGrupo) {
            $queryBuilder->andWhere("g.codigoCostoGrupoPk = '{$codigoCostoGrupo}'");
        }
        if ($nombre) {
            $queryBuilder->andWhere("g.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('g.codigoCostoGrupoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(RhuCostoGrupo::class)->find($codigo);
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