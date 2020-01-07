<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuSolicitudMotivo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuSolicitudMotivoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuSolicitudMotivo::class);
    }
    
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:RecursoHumano\RhuSolicitudMotivo','sm')
            ->select('sm.codigoSolicitudMotivoPk AS ID')
        ->addSelect('sm.nombre');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoSolicitudMotivo = null;
        $nombre = null;

        if ($filtros) {
            $codigoSolicitudMotivo = $filtros['codigoSolicitudMotivo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuSolicitudMotivo::class, 'sm')
            ->select('sm.codigoSolicitudMotivoPk')
            ->addSelect('sm.nombre');
        if ($codigoSolicitudMotivo) {
            $queryBuilder->andWhere("sm.codigoSolicitudMotivoPk = '{$codigoSolicitudMotivo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("sm.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('sm.codigoSolicitudMotivoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(RhuSolicitudMotivo::class)->find($codigo);
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