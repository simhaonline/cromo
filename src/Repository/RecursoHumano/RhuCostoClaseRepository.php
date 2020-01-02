<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCostoClase;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuCostoClaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuCostoClase::class);
    }

    public function camposPredeterminados(){
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuCostoClase::class,'rcc')
            ->select('rcc.codigoCostoClasePk as ID')
            ->addSelect('rcc.nombre')
            ->addSelect('rcc.operativo')
            ->where('rcc.codigoCostoClasePk IS NOT NULL');
        return $queryBuilder->getQuery()->execute();
    }


    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoCostoClase = null;
        $nombre = null;

        if ($filtros) {
            $codigoCostoClase = $filtros['codigoCostoClase'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuCostoClase::class, 'c')
            ->select('c.codigoCostoClasePk')
            ->addSelect('c.nombre')
            ->addSelect('c.operativo')
            ->addSelect('c.orden');


        if ($codigoCostoClase) {
            $queryBuilder->andWhere("c.codigoCostoClasePk = '{$codigoCostoClase}'");
        }
        if ($nombre) {
            $queryBuilder->andWhere("c.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('c.codigoCostoClasePk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(RhuCostoClase::class)->find($codigo);
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