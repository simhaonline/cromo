<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuClasificacionRiesgo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuClasificacionRiesgoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuClasificacionRiesgo::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoClasificacionRiesgo = null;
        $nombre = null;

        if ($filtros) {
            $codigoClasificacionRiesgo = $filtros['codigoClasificacionRiesgo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }


        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuClasificacionRiesgo::class, 'cr')
            ->select('cr.codigoClasificacionRiesgoPk')
            ->addSelect('cr.nombre')
            ->addSelect('cr.porcentaje');
        if ($codigoClasificacionRiesgo) {
            $queryBuilder->andWhere("cr.codigoClasificacionRiesgoPk = '{$codigoClasificacionRiesgo}'");
        }
        if($nombre){
            $queryBuilder->andWhere("cr.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('cr.codigoClasificacionRiesgoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

    }
    
    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(RhuClasificacionRiesgo::class)->find($codigo);
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

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:RecursoHumano\RhuClasificacionRiesgo','cr')
            ->select('cr.codigoClasificacionRiesgoPk AS ID')
            ->addSelect('cr.nombre AS NOMBRE');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}