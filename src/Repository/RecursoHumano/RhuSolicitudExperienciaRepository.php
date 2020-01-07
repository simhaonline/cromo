<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuSolicitudExperiencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuSolicitudExperienciaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuSolicitudExperiencia::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoSolicitudExperiencia = null;
        $nombre = null;

        if ($filtros) {
            $codigoSolicitudExperiencia = $filtros['codigoSolicitudExperiencia'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuSolicitudExperiencia::class, 'se')
            ->select('se.codigoSolicitudExperienciaPk')
            ->addSelect('se.nombre');
        if ($codigoSolicitudExperiencia) {
            $queryBuilder->andWhere("se.codigoSolicitudExperienciaPk = '{$codigoSolicitudExperiencia}'");
        }

        if($nombre){
            $queryBuilder->andWhere("se.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('se.codigoSolicitudExperienciaPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(RhuSolicitudExperiencia::class)->find($codigo);
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
            ->from('App:RecursoHumano\RhuSolicitudExperiencia','se')
            ->select('se.codigoSolicitudExperienciaPk AS ID')
            ->addSelect('se.nombre');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}