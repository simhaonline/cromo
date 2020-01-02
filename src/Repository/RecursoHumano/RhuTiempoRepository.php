<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuTiempo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuTiempoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuTiempo::class);
    }

    public function camposPredeterminados(){
        return $this->_em->createQueryBuilder()
            ->select('t.codigoTiempoPk AS ID')
            ->addSelect('t.nombre')
            ->addSelect('t.abreviatura')
            ->addSelect('t.factor')
            ->addSelect('t.factorHorasDia')
            ->addSelect('t.orden')
            ->from(RhuTiempo::class,'t')
            ->where('t.codigoTiempoPk IS NOT NULL')->getQuery()->execute();
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoTiempo = null;
        $nombre = null;

        if ($filtros) {
            $codigoTiempo = $filtros['codigoTiempo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuTiempo::class, 't')
            ->select('t.codigoTiempoPk')
            ->addSelect('t.nombre')
            ->addSelect('t.factor')
            ->addSelect('t.factorHorasDia')
            ->addSelect('t.abreviatura')
            ->addSelect('t.orden');

        if ($codigoTiempo) {
            $queryBuilder->andWhere("t.codigoTiempoPk = '{$codigoTiempo}'");
        }

        if ($nombre) {
            $queryBuilder->andWhere("t.nombre LIKE '%{$nombre}%'");
        }


        $queryBuilder->addOrderBy('t.codigoTiempoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(RhuTiempo::class)->find($codigo);
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